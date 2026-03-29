// ===== CONFIG =====
const API_BASE = 'api.php';
const WISHES_PER_PAGE = 3;
let wishesData = [];
let currentPage = 1;

// ===== MAP DATA =====
const MAP_DATA = {
  akad: {
    title:    'Akad Nikah — Dusun Wonorejo',
    address:  'Dusun Wonorejo RT 04/RW 04, Kel. Kedungjati, Kec. Kedungjati (Rumah Ibu Dorkas)',
    embedUrl: 'https://maps.google.com/maps?q=Dusun+Wonorejo+Kedungjati+Grobogan&output=embed',
    directUrl:'https://maps.app.goo.gl/ZxTxuwP1YME76Cvf8',
    qrText:   'https://maps.app.goo.gl/ZxTxuwP1YME76Cvf8'
  },
  resepsi: {
    title:    'Resepsi — Dusun Wonorejo',
    address:  'Dusun Wonorejo RT 04/RW 04, Kel. Kedungjati, Kec. Kedungjati (Rumah Ibu Dorkas)',
    embedUrl: 'https://maps.google.com/maps?q=Dusun+Wonorejo+Kedungjati+Grobogan&output=embed',
    directUrl:'https://maps.app.goo.gl/ZxTxuwP1YME76Cvf8',
    qrText:   'https://maps.app.goo.gl/ZxTxuwP1YME76Cvf8'
  }
};

// ===== MUSIC (MP3 lokal, random dari folder music/) =====
// Tambah nama file MP3 di sini setiap kali ada lagu baru
const PLAYLIST = [
  'music/lagu1.mp3',
  'music/lagu2.mp3',
];

function initMusic() {
  if (!PLAYLIST.length) return;
  const pick = PLAYLIST[Math.floor(Math.random() * PLAYLIST.length)];
  // Buat elemen audio baru agar tidak bergantung pada DOM timing
  audioEl = new Audio(pick);
  audioEl.loop   = true;
  audioEl.volume = 0.4;
  // Preload
  audioEl.preload = 'auto';
  audioEl.load();
}

function playMusic() {
  const btn = document.getElementById('musicBtn');
  if (btn) btn.classList.add('show'); // selalu tampilkan tombol

  if (!audioEl) return;
  const playPromise = audioEl.play();
  if (playPromise !== undefined) {
    playPromise.then(() => {
      if (btn) btn.classList.add('playing');
    }).catch(() => {
      // autoplay diblokir — tombol tetap muncul, user klik untuk mulai
    });
  }
}

function toggleMusic() {
  if (!audioEl) return;
  const btn = document.getElementById('musicBtn');
  if (audioEl.paused) {
    audioEl.play().then(() => { if (btn) btn.classList.add('playing'); }).catch(() => {});
  } else {
    audioEl.pause();
    if (btn) btn.classList.remove('playing');
  }
}

let audioEl = null;

// ===== NAMA TAMU DARI URL (?to=slug) =====
function initGuest() {
  const params = new URLSearchParams(window.location.search);
  const slug   = (params.get('to') || '').toLowerCase().trim();
  if (!slug || typeof GUESTS === 'undefined') return;

  const guest = GUESTS[slug];
  if (!guest) return;

  const { name, group } = guest;

  // Cover
  const wrap = document.getElementById('coverGuestWrap');
  if (wrap) {
    document.getElementById('coverGuestName').textContent = name;
    if (group === 'IDM') {
      const grpEl = document.getElementById('coverGuestGroup');
      grpEl.textContent = 'IDM Family';
      grpEl.style.display = 'block';
    }
    wrap.style.display = 'block';
  }

  // Hero badge
  const badge = document.getElementById('heroBadge');
  if (badge) {
    document.getElementById('heroBadgeName').textContent = name;
    badge.style.display = 'inline-flex';
  }

  // RSVP prefill nama
  const nameInput = document.getElementById('rsvpName');
  if (nameInput) nameInput.value = name;

  // RSVP prefill kategori IDM
  if (group === 'IDM') {
    const catSel = document.getElementById('rsvpCategory');
    if (catSel) {
      for (const opt of catSel.options) {
        if (opt.value === 'IDM') { opt.selected = true; break; }
      }
    }
  }
}

// ===== COVER PARTICLES =====
function createParticles() {
  const container = document.getElementById('particles');
  if (!container) return;
  for (let i = 0; i < 80; i++) {
    const star = document.createElement('div');
    const size = Math.random() * 2.5 + 0.5;
    Object.assign(star.style, {
      position: 'absolute',
      width: size + 'px', height: size + 'px',
      borderRadius: '50%',
      background: `rgba(168,207,240,${Math.random() * 0.6 + 0.2})`,
      left: Math.random() * 100 + '%',
      top:  Math.random() * 100 + '%',
      animation: `twinkle ${Math.random() * 3 + 2}s ease-in-out ${Math.random() * 3}s infinite`
    });
    container.appendChild(star);
  }
  if (!document.getElementById('twinkle-style')) {
    const s = document.createElement('style');
    s.id = 'twinkle-style';
    s.textContent = '@keyframes twinkle{0%,100%{opacity:0.2;transform:scale(0.8)}50%{opacity:1;transform:scale(1)}}';
    document.head.appendChild(s);
  }
}

// ===== OPEN INVITATION =====
function openInvitation() {
  document.getElementById('cover').classList.add('hide');
  const inv = document.getElementById('invitation');
  inv.classList.remove('hidden');
  setTimeout(() => {
    inv.classList.add('visible');
    initObserver();
    startCountdown();
    loadWishes();
    const bg = document.querySelector('.hero-bg');
    if (bg) bg.classList.add('loaded');
    playMusic();
  }, 100);
}

// ===== COUNTDOWN =====
function startCountdown() {
  const target = new Date('2026-04-09T09:00:00');
  function update() {
    const diff = target - new Date();
    if (diff <= 0) {
      ['days','hours','minutes','seconds'].forEach(id => document.getElementById(id).textContent = '00');
      return;
    }
    document.getElementById('days').textContent    = String(Math.floor(diff / 86400000)).padStart(2,'0');
    document.getElementById('hours').textContent   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
    document.getElementById('minutes').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
    document.getElementById('seconds').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
  }
  update(); setInterval(update, 1000);
}

// ===== FADE-IN OBSERVER =====
function initObserver() {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => entry.target.classList.add('visible'), i * 70);
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade-in').forEach(el => obs.observe(el));
}

// ===== RSVP =====
async function submitRSVP(e) {
  e.preventDefault();
  const btn = e.target.querySelector('.btn-rsvp');
  btn.disabled = true; btn.textContent = 'Mengirim...';

  const payload = {
    name:     document.getElementById('rsvpName').value.trim(),
    phone:    document.getElementById('rsvpPhone').value.trim(),
    status:   document.getElementById('rsvpStatus').value,
    guests:   1,
    category: getCategoryValue(),
    message:  document.getElementById('rsvpMessage').value.trim()
  };

  try {
    const res  = await fetch(API_BASE, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'submit_rsvp', ...payload })
    });
    const data = await res.json();
    if (data.success) {
      e.target.reset(); resetOther(); showSuccess();
      if (payload.message) {
        wishesData.unshift({ name: payload.name, message: payload.message, status: payload.status, category: payload.category });
        currentPage = 1; renderWishes();
      }
    } else { alert('Gagal mengirim. Coba lagi.'); }
  } catch {
    e.target.reset(); resetOther(); showSuccess();
    if (payload.message) {
      wishesData.unshift({ name: payload.name, message: payload.message, status: payload.status, category: payload.category });
      currentPage = 1; renderWishes();
    }
  }
  btn.disabled = false; btn.textContent = 'Kirim Konfirmasi';
}

function getCategoryValue() {
  const sel = document.getElementById('rsvpCategory');
  if (sel.value === 'Lainnya') return document.getElementById('rsvpCategoryOther').value.trim() || 'Lainnya';
  return sel.value;
}
function resetOther() {
  const el = document.getElementById('rsvpCategoryOther');
  if (el) { el.value = ''; }
  const grp = document.getElementById('otherGroup');
  if (grp) grp.style.display = 'none';
}
function showSuccess() {
  const el = document.getElementById('rsvp-success');
  el.style.display = 'flex';
  setTimeout(() => el.style.display = 'none', 5000);
}

// ===== WISHES =====
async function loadWishes() {
  try {
    const res  = await fetch(`${API_BASE}?action=get_wishes`);
    const data = await res.json();
    document.getElementById('wishesLoading').remove();
    wishesData = (data.wishes && data.wishes.length > 0) ? data.wishes : [];
  } catch {
    document.getElementById('wishesLoading').remove();
    wishesData = [
      { name:'Siti Nurhaliza', message:'Semoga menjadi keluarga yang sakinah, mawaddah, warahmah. Barakallah!', category:'Teman SMA' },
      { name:'Budi Santoso',   message:'Selamat menempuh hidup baru, semoga langgeng hingga akhir hayat!',       category:'Rekan Kerja' },
      { name:'Dewi Rahayu',    message:'Bahagia selalu ya kak, semoga rumah tangganya penuh berkah!',             category:'Teman Kuliah' },
      { name:'Ahmad Fauzi',    message:'Selamat berbahagia, semoga menjadi keluarga yang harmonis.',              category:'Keluarga' }
    ];
  }
  currentPage = 1;
  renderWishes();
}

function renderWishes() {
  const list       = document.getElementById('wishesList');
  const pagination = document.getElementById('wishesPagination');

  if (wishesData.length === 0) {
    list.innerHTML = '<p style="text-align:center;color:var(--text-soft);font-size:0.85rem;padding:2.5rem">Belum ada ucapan. Jadilah yang pertama!</p>';
    pagination.innerHTML = '';
    return;
  }

  const totalPages = Math.ceil(wishesData.length / WISHES_PER_PAGE);
  const start      = (currentPage - 1) * WISHES_PER_PAGE;
  const pageData   = wishesData.slice(start, start + WISHES_PER_PAGE);

  list.innerHTML = '';
  pageData.forEach(w => {
    const item = document.createElement('div');
    item.classList.add('wish-item', 'fade-in');
    item.innerHTML = buildWishHTML(w.name, w.message, w.category);
    list.appendChild(item);
    setTimeout(() => item.classList.add('visible'), 50);
  });

  pagination.innerHTML = '';
  if (totalPages <= 1) return;

  if (currentPage > 1) {
    const prev = document.createElement('button');
    prev.className = 'pg-prev'; prev.textContent = '← Prev';
    prev.onclick = () => goPage(currentPage - 1);
    pagination.appendChild(prev);
  }
  for (let i = 1; i <= totalPages; i++) {
    const b = document.createElement('button');
    b.textContent = i;
    if (i === currentPage) b.classList.add('active');
    b.onclick = () => goPage(i);
    pagination.appendChild(b);
  }
  if (currentPage < totalPages) {
    const next = document.createElement('button');
    next.className = 'pg-next'; next.textContent = 'Next →';
    next.onclick = () => goPage(currentPage + 1);
    pagination.appendChild(next);
  }
}

function goPage(page) {
  currentPage = page;
  renderWishes();
  document.querySelector('.wishes-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function buildWishHTML(name, message, category) {
  const cat = category ? `<span class="wish-category">${escapeHtml(category)}</span>` : '';
  return `
    <div class="wish-avatar">${escapeHtml(name.charAt(0).toUpperCase())}</div>
    <div class="wish-body">
      <strong>${escapeHtml(name)}</strong>
      <div class="wish-meta">${cat}</div>
      <p>${escapeHtml(message)}</p>
    </div>`;
}

function escapeHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ===== COPY =====
function copyText(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const orig = btn.textContent;
    btn.textContent = '✓ Tersalin!'; btn.classList.add('copied');
    setTimeout(() => { btn.textContent = orig; btn.classList.remove('copied'); }, 2000);
  });
}

// ===== MAP =====
function showMap(type) {
  const d = MAP_DATA[type]; if (!d) return;
  document.getElementById('mapTitle').textContent   = d.title;
  document.getElementById('mapAddress').textContent = d.address;
  document.getElementById('mapFrame').src           = d.embedUrl;
  document.getElementById('mapDirectLink').href     = d.directUrl;
  document.getElementById('mapQR').src = `https://chart.googleapis.com/chart?chs=160x160&cht=qr&chl=${encodeURIComponent(d.qrText)}&choe=UTF-8`;
  const area = document.getElementById('mapArea');
  area.classList.add('open');
  setTimeout(() => area.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 150);
}
function closeMap() {
  document.getElementById('mapArea').classList.remove('open');
  setTimeout(() => document.getElementById('mapFrame').src = '', 500);
}

// ===== NAVBAR =====
function closeNav() {
  document.getElementById('navMenu').classList.remove('open');
  document.getElementById('navToggle').classList.remove('active');
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
  createParticles();
  initGuest();
  initMusic();

  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });

  const toggle = document.getElementById('navToggle');
  if (toggle) {
    toggle.addEventListener('click', () => {
      document.getElementById('navMenu').classList.toggle('open');
      toggle.classList.toggle('active');
    });
  }

  document.querySelectorAll('.navbar a').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  });

  document.addEventListener('click', e => {
    const nav = document.querySelector('.navbar');
    if (nav && !nav.contains(e.target)) closeNav();
  });

  const catSel   = document.getElementById('rsvpCategory');
  const otherGrp = document.getElementById('otherGroup');
  const catOther = document.getElementById('rsvpCategoryOther');
  if (catSel) {
    catSel.addEventListener('change', () => {
      const show = catSel.value === 'Lainnya';
      otherGrp.style.display = show ? 'block' : 'none';
      catOther.required = show;
      if (!show) catOther.value = '';
    });
  }
});
