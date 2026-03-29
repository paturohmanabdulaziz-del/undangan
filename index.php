<?php
$guests = require __DIR__ . '/guests.php';

// Ambil slug dari URL path: /nama → slug = "nama"
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// Hapus subfolder jika ada (misal /wedding/fahmi → fahmi)
$parts = explode('/', $path);
$slug  = strtolower(end($parts));

// Cek apakah tamu ditemukan
$guest = isset($guests[$slug]) ? $guests[$slug] : null;
$guestName  = $guest ? $guest['name']  : null;
$guestGroup = $guest ? $guest['group'] : null;

// Jika tidak ada slug / bukan tamu → tampilkan halaman utama tanpa nama
$isPersonal = $guest !== null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Undangan Pernikahan — Septian &amp; Raras<?= $isPersonal ? ' · ' . htmlspecialchars($guestName) : '' ?></title>
  <meta property="og:title" content="Undangan Pernikahan Septian &amp; Raras" />
  <meta property="og:description" content="<?= $isPersonal ? 'Kepada ' . htmlspecialchars($guestName) . ', kami mengundang Anda untuk hadir di hari bahagia kami.' : 'Kami mengundang Anda untuk hadir di hari bahagia kami.' ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Raleway:wght@300;400;500;600&family=Great+Vibes&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- ===== COVER ===== -->
  <section id="cover">
    <div class="cover-overlay"></div>
    <div class="cover-particles" id="particles"></div>
    <div class="cover-content">
      <?php if ($isPersonal): ?>
      <p class="cover-to">Kepada Yth.</p>
      <p class="cover-guest-name"><?= htmlspecialchars($guestName) ?></p>
      <?php if ($guestGroup === 'IDM'): ?>
      <p class="cover-guest-group">IDM Family</p>
      <?php endif; ?>
      <div class="cover-line"><span></span><i>&#10022;</i><span></span></div>
      <?php endif; ?>
      <p class="cover-label">The Wedding of</p>
      <h1 class="cover-script">Septian &amp; Raras</h1>
      <div class="cover-line"><span></span><i>&#10022;</i><span></span></div>
      <p class="cover-date">Kamis &nbsp;·&nbsp; 9 April 2026</p>
      <button class="btn-cover" onclick="openInvitation()">
        <span>Buka Undangan</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      </button>
    </div>
  </section>

  <!-- ===== MAIN ===== -->
  <main id="invitation" class="hidden">

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
      <div class="navbar-brand">S &amp; R</div>
      <button class="navbar-toggle" id="navToggle" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <ul id="navMenu">
        <li><a href="#home"   onclick="closeNav()">Home</a></li>
        <li><a href="#couple" onclick="closeNav()">Couple</a></li>
        <li><a href="#event"  onclick="closeNav()">Acara</a></li>
        <li><a href="#gift"   onclick="closeNav()">Hadiah</a></li>
        <li><a href="#rsvp"   onclick="closeNav()">RSVP</a></li>
      </ul>
    </nav>

    <!-- Hero -->
    <section id="home" class="hero">
      <div class="hero-bg"></div>
      <div class="hero-vignette"></div>
      <div class="hero-content fade-in">
        <p class="hero-bismillah">Bismillahirrahmanirrahim</p>
        <p class="hero-sub">Dengan memohon rahmat dan ridho Allah SWT</p>
        <h2 class="hero-script">Septian Dwi Saputro</h2>
        <div class="hero-amp">&amp;</div>
        <h2 class="hero-script">Raras Sawitri Fajrin</h2>
        <div class="hero-ornament"><span></span><i>&#10022;</i><span></span></div>
        <p class="hero-date">Kamis, 9 April 2026</p>
        <?php if ($isPersonal): ?>
        <div class="hero-invite-badge">
          <span>Turut Mengundang</span>
          <strong><?= htmlspecialchars($guestName) ?></strong>
        </div>
        <?php endif; ?>
      </div>
      <div class="hero-scroll-hint"><span></span></div>
    </section>

    <!-- Couple -->
    <section id="couple" class="couple-section">
      <div class="section-tag fade-in">Mempelai</div>
      <h2 class="section-title fade-in">Dua Insan yang Bersatu</h2>
      <div class="section-ornament fade-in"><span></span><i>&#10022;</i><span></span></div>
      <div class="couple-grid">
        <div class="couple-card fade-in">
          <div class="couple-frame">
            <div class="couple-photo groom-photo"></div>
            <div class="couple-frame-corner tl"></div>
            <div class="couple-frame-corner tr"></div>
            <div class="couple-frame-corner bl"></div>
            <div class="couple-frame-corner br"></div>
          </div>
          <h3 class="couple-name">Septian Dwi Saputro</h3>
          <p class="couple-parents">Putra dari<br/>Alm. Bapak Surono &amp; Ibu Sriyanti</p>
        </div>
        <div class="couple-center fade-in">
          <div class="couple-ring">&#9901;</div>
          <p class="couple-and">dan</p>
        </div>
        <div class="couple-card fade-in">
          <div class="couple-frame">
            <div class="couple-photo bride-photo"></div>
            <div class="couple-frame-corner tl"></div>
            <div class="couple-frame-corner tr"></div>
            <div class="couple-frame-corner bl"></div>
            <div class="couple-frame-corner br"></div>
          </div>
          <h3 class="couple-name">Raras Sawitri Fajrin</h3>
          <p class="couple-parents">Putri dari<br/>Bapak Tri Wasono Mulyo &amp; Ibu Dorkas Windiartie</p>
        </div>
      </div>
    </section>

    <!-- Countdown -->
    <section class="countdown-section">
      <div class="countdown-bg"></div>
      <div class="countdown-inner fade-in">
        <p class="section-tag light">Menuju Hari Bahagia</p>
        <div class="countdown-boxes" id="countdown">
          <div class="cbox"><span id="days">00</span><label>Hari</label></div>
          <div class="cdot">:</div>
          <div class="cbox"><span id="hours">00</span><label>Jam</label></div>
          <div class="cdot">:</div>
          <div class="cbox"><span id="minutes">00</span><label>Menit</label></div>
          <div class="cdot">:</div>
          <div class="cbox"><span id="seconds">00</span><label>Detik</label></div>
        </div>
      </div>
    </section>

    <!-- Event -->
    <section id="event" class="event-section">
      <div class="section-tag fade-in">Rangkaian Acara</div>
      <h2 class="section-title fade-in">Jadwal Pernikahan</h2>
      <div class="section-ornament fade-in"><span></span><i>&#10022;</i><span></span></div>
      <div class="event-row">
        <div class="event-card fade-in">
          <div class="event-badge">01</div>
          <h3>Akad Nikah</h3>
          <div class="event-sep"></div>
          <p class="event-time">Kamis, 9 April 2026</p>
          <p class="event-time">09.00 WIB &ndash; Selesai</p>
          <div class="event-sep"></div>
          <p class="event-place">Dusun Wonorejo RT 04/RW 04</p>
          <p class="event-addr">Kel. Kedungjati, Kec. Kedungjati<br/>(Rumah Ibu Dorkas)</p>
          <button class="btn-peta" onclick="showMap('akad')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Lihat Peta
          </button>
        </div>
        <div class="event-divider-v fade-in"><span></span><i>&#10022;</i><span></span></div>
        <div class="event-card fade-in">
          <div class="event-badge">02</div>
          <h3>Resepsi</h3>
          <div class="event-sep"></div>
          <p class="event-time">Kamis, 9 April 2026</p>
          <p class="event-time">11.00 WIB &ndash; Selesai</p>
          <div class="event-sep"></div>
          <p class="event-place">Dusun Wonorejo RT 04/RW 04</p>
          <p class="event-addr">Kel. Kedungjati, Kec. Kedungjati<br/>(Rumah Ibu Dorkas)</p>
          <button class="btn-peta" onclick="showMap('resepsi')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Lihat Peta
          </button>
        </div>
        <div class="event-divider-v fade-in"><span></span><i>&#10022;</i><span></span></div>
        <div class="event-card fade-in">
          <div class="event-badge">03</div>
          <h3>Hiburan</h3>
          <p class="event-note">Solo Organ</p>
          <div class="event-sep"></div>
          <p class="event-time">Kamis, 9 April 2026</p>
          <p class="event-time">11.00 WIB &ndash; Selesai</p>
          <div class="event-sep"></div>
          <p class="event-place">Dusun Wonorejo RT 04/RW 04</p>
          <p class="event-addr">Kel. Kedungjati, Kec. Kedungjati<br/>(Rumah Ibu Dorkas)</p>
          <button class="btn-peta" onclick="showMap('resepsi')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Lihat Peta
          </button>
        </div>
      </div>
      <!-- Map Area -->
      <div class="map-area" id="mapArea">
        <div class="map-header">
          <span id="mapTitle"></span>
          <button onclick="closeMap()" aria-label="Tutup">&#10005;</button>
        </div>
        <div class="map-body">
          <div class="map-frame-wrap">
            <iframe id="mapFrame" src="" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <div class="map-side">
            <p class="map-addr-text" id="mapAddress"></p>
            <a id="mapDirectLink" href="#" target="_blank" class="btn-maps-ext">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              Buka di Maps
            </a>
            <p class="map-qr-lbl">Scan QR Lokasi</p>
            <img id="mapQR" src="" alt="QR Lokasi" class="map-qr" />
          </div>
        </div>
      </div>
    </section>

    <!-- Love Story -->
    <section class="story-section">
      <div class="section-tag fade-in">Kisah Kami</div>
      <h2 class="section-title fade-in">Perjalanan Cinta</h2>
      <div class="section-ornament fade-in"><span></span><i>&#10022;</i><span></span></div>
      <div class="story-timeline">
        <div class="story-item fade-in">
          <div class="story-dot"><i>&#10022;</i></div>
          <div class="story-card">
            <span class="story-date">30 September 2024</span>
            <h3 class="story-title">Pertemuan</h3>
            <p>Percayalah, tidak satu pun kebetulan di dunia ini — semua tersusun rapi oleh sang Pencipta. Kami bertemu di sebuah event yang sama tanpa disengaja. Sebetulnya kami sudah saling tahu, namun hanya sebatas tahu satu sama lain, tidak lebih.</p>
          </div>
        </div>
        <div class="story-item fade-in">
          <div class="story-dot"><i>&#10022;</i></div>
          <div class="story-card">
            <span class="story-date">02 Oktober 2024</span>
            <h3 class="story-title">Menjalin Hubungan</h3>
            <p>Perjalanan cinta kami bukanlah tanpa ujian. Pada 29 Oktober 2024, ayah dari Septian berpulang ke rahmatullah. Ya, seperti itulah dunia bekerja — ada yang datang dan pergi silih berganti, tanpa menunggu kita siap, tanpa kompromi, tanpa lihat situasi. Kami dihadapkan pada jarak yang memisahkan, dilingkup keraguan, dihadapkan pilihan sulit. Sempat terlintas keinginan untuk menyerah — entah berapa kali kami saling memaafkan dan kembali menguatkan.</p>
          </div>
        </div>
        <div class="story-item fade-in">
          <div class="story-dot"><i>&#10022;</i></div>
          <div class="story-card">
            <span class="story-date">14 Februari 2026</span>
            <h3 class="story-title">Lamaran</h3>
            <p>Sebetulnya kami tidak ada lamaran, namun pada hari itu atas kehendak-Nya kami melakukan pertemuan keluarga untuk membicarakan niat yang lebih serius. Setelah tanggal ditentukan, semakin kencang pula cobaannya.</p>
          </div>
        </div>
        <div class="story-item fade-in">
          <div class="story-dot"><i>&#10022;</i></div>
          <div class="story-card">
            <span class="story-date">09 April 2026</span>
            <h3 class="story-title">Menikah</h3>
            <p>Percayalah, bukan bertemu lalu berjodoh — tapi karena berjodoh kami dipertemukan. Kami memutuskan untuk mengikrarkan janji suci pernikahan, InsyaAllah di bulan April ini.</p>
            <blockquote class="story-quote">"Apa yang menjadi takdir-Mu, akan menemukan jalannya untuk menemukanmu."<cite>— Ali Bin Abi Thalib</cite></blockquote>
          </div>
        </div>
      </div>
    </section>

    <!-- Gift -->
    <section id="gift" class="gift-section">
      <div class="section-tag fade-in">Hadiah Pernikahan</div>
      <h2 class="section-title fade-in">Amplop Digital</h2>
      <div class="section-ornament fade-in"><span></span><i>&#10022;</i><span></span></div>
      <p class="gift-desc fade-in">Doa restu Anda adalah hadiah terbaik bagi kami. Namun jika ingin memberikan tanda kasih, berikut informasinya.</p>
      <div class="gift-row">
        <div class="gift-card fade-in">
          <div class="gift-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          </div>
          <p class="gift-bank">Bank BCA</p>
          <p class="gift-number">1234 5678 90</p>
          <p class="gift-name">a.n. Septian Dwi Saputro</p>
          <button class="btn-salin" onclick="copyText('1234567890', this)">Salin Rekening</button>
        </div>
        <div class="gift-card fade-in">
          <div class="gift-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="3" height="3"/><rect x="18" y="14" width="3" height="3"/><rect x="14" y="18" width="3" height="3"/><rect x="18" y="18" width="3" height="3"/></svg>
          </div>
          <p class="gift-bank">QRIS</p>
          <div class="qris-box"><div class="qris-pattern"></div><p>QRIS</p></div>
          <p class="gift-name">Scan untuk membayar</p>
        </div>
      </div>
    </section>

    <!-- RSVP -->
    <section id="rsvp" class="rsvp-section">
      <div class="rsvp-bg"></div>
      <div class="rsvp-wrap fade-in">
        <div class="rsvp-left">
          <p class="section-tag light">Konfirmasi Kehadiran</p>
          <h2 class="rsvp-title">RSVP</h2>
          <div class="section-ornament light"><span></span><i>&#10022;</i><span></span></div>
          <p class="rsvp-desc">Kehadiran Anda adalah kebahagiaan kami. Mohon konfirmasi kehadiran sebelum <strong>1 April 2026</strong>.</p>
        </div>
        <div class="rsvp-right">
          <form class="rsvp-form" onsubmit="submitRSVP(event)">
            <?php if ($isPersonal): ?>
            <input type="hidden" id="rsvpNamePrefill" value="<?= htmlspecialchars($guestName) ?>" />
            <?php endif; ?>
            <div class="form-group">
              <input type="text" id="rsvpName" placeholder="Nama Lengkap" required
                <?= $isPersonal ? 'value="' . htmlspecialchars($guestName) . '"' : '' ?> />
            </div>
            <div class="form-group">
              <input type="text" id="rsvpPhone" placeholder="Nomor HP / WhatsApp" required />
            </div>
            <div class="form-group">
              <select id="rsvpCategory" required>
                <option value="" disabled selected>Keterangan</option>
                <option value="Keluarga">Keluarga</option>
                <option value="Teman SD">Teman SD</option>
                <option value="Teman SMP">Teman SMP</option>
                <option value="Teman SMA">Teman SMA</option>
                <option value="Teman Kuliah">Teman Kuliah</option>
                <option value="Rekan Kerja">Rekan Kerja</option>
                <?php if ($guestGroup === 'IDM'): ?>
                <option value="IDM" selected>IDM</option>
                <?php else: ?>
                <option value="IDM">IDM</option>
                <?php endif; ?>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="form-group" id="otherGroup" style="display:none;">
              <input type="text" id="rsvpCategoryOther" placeholder="Sebutkan keterangan Anda..." />
            </div>
            <div class="form-group">
              <select id="rsvpStatus" required>
                <option value="" disabled selected>Konfirmasi Kehadiran</option>
                <option value="hadir">Dapat Hadir Saat Acara</option>
                <option value="tidak">Tidak Dapat Hadir</option>
              </select>
            </div>
            <div class="form-group">
              <textarea id="rsvpMessage" placeholder="Ucapan &amp; Doa untuk Mempelai" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-rsvp">Kirim Konfirmasi</button>
          </form>
          <div id="rsvp-success" class="rsvp-ok" style="display:none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Terima kasih! Konfirmasi Anda telah kami terima.
          </div>
        </div>
      </div>
    </section>

    <!-- Wishes -->
    <section class="wishes-section">
      <div class="section-tag fade-in">Ucapan &amp; Doa</div>
      <h2 class="section-title fade-in">Pesan untuk Kami</h2>
      <div class="section-ornament fade-in"><span></span><i>&#10022;</i><span></span></div>
      <div class="wishes-list" id="wishesList">
        <div class="wishes-loading" id="wishesLoading">
          <div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div>
        </div>
      </div>
      <div class="wishes-pagination" id="wishesPagination"></div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-top">
        <p class="footer-script">Septian &amp; Raras</p>
        <div class="footer-ornament"><span></span><i>&#10022;</i><span></span></div>
        <p class="footer-quote">"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya."</p>
        <p class="footer-ayat">&mdash; QS. Ar-Rum: 21</p>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 &nbsp;·&nbsp; Dibuat dengan &#9825;</p>
      </div>
    </footer>

  </main>

  <!-- Floating Music -->
  <button class="music-float" id="musicBtn" onclick="toggleMusic()" aria-label="Toggle musik">
    <div class="music-ripple"></div>
    <svg class="music-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
  </button>

  <div id="ytPlayer" style="position:fixed;bottom:-9999px;left:-9999px;width:1px;height:1px;pointer-events:none;"></div>
  <script src="script.js"></script>
</body>
</html>
