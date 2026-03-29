<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

define('DB_HOST', 'localhost');
define('DB_NAME', 'wedding_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB() {
    return new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}

function initTable($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS rsvp (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        name     VARCHAR(100) NOT NULL,
        phone    VARCHAR(20),
        status   ENUM('hadir','tidak') DEFAULT 'hadir',
        category VARCHAR(50) DEFAULT '',
        message  TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // migrasi kolom lama jika ada
    foreach (['guests'] as $col) {
        try { $pdo->exec("ALTER TABLE rsvp DROP COLUMN $col"); } catch (PDOException $e) {}
    }
    // pastikan category ada
    try { $pdo->exec("ALTER TABLE rsvp ADD COLUMN category VARCHAR(50) DEFAULT '' AFTER status"); } catch (PDOException $e) {}
    // update enum jika masih ada 'ragu'
    try { $pdo->exec("ALTER TABLE rsvp MODIFY COLUMN status ENUM('hadir','tidak') DEFAULT 'hadir'"); } catch (PDOException $e) {}
}

$action = $_GET['action'] ?? '';
$body   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $body['action'] ?? '';
}

try {
    $pdo = getDB();
    initTable($pdo);

    // ===== GET WISHES (dengan pagination) =====
    if ($action === 'get_wishes') {
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $perPage  = max(1, min(50, (int)($_GET['per_page'] ?? 50)));
        $offset   = ($page - 1) * $perPage;

        $total = $pdo->query("SELECT COUNT(*) FROM rsvp WHERE message IS NOT NULL AND message != ''")->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT name, status, category, message, created_at
             FROM rsvp
             WHERE message IS NOT NULL AND message != ''
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$perPage, $offset]);
        $wishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success'    => true,
            'wishes'     => $wishes,
            'total'      => (int)$total,
            'page'       => $page,
            'per_page'   => $perPage,
            'total_pages'=> (int)ceil($total / $perPage)
        ]);
        exit;
    }

    // ===== SUBMIT RSVP =====
    if ($action === 'submit_rsvp') {
        $name     = trim($body['name']     ?? '');
        $phone    = trim($body['phone']    ?? '');
        $status   = in_array($body['status'] ?? '', ['hadir','tidak']) ? $body['status'] : 'hadir';
        $category = trim($body['category'] ?? '');
        $message  = trim($body['message']  ?? '');

        if (empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Nama wajib diisi']);
            exit;
        }

        $stmt = $pdo->prepare(
            "INSERT INTO rsvp (name, phone, status, category, message) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $phone, $status, $category, $message]);

        echo json_encode(['success' => true, 'id' => (int)$pdo->lastInsertId()]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
