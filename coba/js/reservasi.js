/**
 * reservasi.js
 * Logika untuk halaman reservasi.html
 * - Multi-step form (4 langkah)
 * - Pilih kursi interaktif
 * - Konfirmasi & simpan ke database (jika XAMPP) atau localStorage
 */

// Ganti ke true jika menjalankan di XAMPP / server lokal
const PAKAI_DATABASE_RES = false;
const API_URL_RES = 'api/reservasi.php';

// ===== ISI DROPDOWN FILM =====
populateMovieDropdown('resFilm');

// Set tanggal minimum = hari ini
const today = new Date().toISOString().split('T')[0];
document.getElementById('resDate').min = today;
document.getElementById('resDate').value = today;

// ===== GENERATE KURSI =====
const rows     = ['A','B','C','D','E'];
const cols     = 8;
// Simulasi kursi yang sudah terisi (hardcoded untuk demo)
const takenSeats = ['A2','A3','B5','C1','C7','D4','E2','E3','E4'];
let selectedSeats = [];

function generateSeats() {
  const grid = document.getElementById('seatGrid');
  if (!grid) return;
  grid.innerHTML = '';

  rows.forEach(row => {
    for (let col = 1; col <= cols; col++) {
      const seatId = `${row}${col}`;
      const div = document.createElement('div');
      div.className = 'seat';
      div.id = `seat-${seatId}`;
      div.textContent = seatId;

      if (takenSeats.includes(seatId)) {
        div.classList.add('seat-taken');
        div.title = 'Kursi sudah terisi';
      } else {
        div.addEventListener('click', () => toggleSeat(seatId, div));
      }

      grid.appendChild(div);
    }
  });
}

function toggleSeat(seatId, el) {
  if (selectedSeats.includes(seatId)) {
    // Batalkan pilihan
    selectedSeats = selectedSeats.filter(s => s !== seatId);
    el.classList.remove('seat-selected');
  } else {
    if (selectedSeats.length >= 6) {
      alert('Maksimal 6 kursi per reservasi!');
      return;
    }
    selectedSeats.push(seatId);
    el.classList.add('seat-selected');
  }
  updateSelectedDisplay();
}

function updateSelectedDisplay() {
  const el = document.getElementById('selectedSeats');
  if (!el) return;
  el.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '-';
}

// ===== NAVIGASI STEP =====
function goStep(step) {
  // Validasi sebelum pindah step
  if (step === 2) {
    if (!document.getElementById('resFilm').value) {
      alert('Pilih film terlebih dahulu!'); return;
    }
    if (!document.getElementById('resDate').value) {
      alert('Pilih tanggal terlebih dahulu!'); return;
    }
    generateSeats(); // Generate kursi saat masuk step 2
  }

  if (step === 3) {
    if (selectedSeats.length === 0) {
      alert('Pilih minimal 1 kursi!'); return;
    }
  }

  if (step === 4) {
    const name  = document.getElementById('resName').value.trim();
    const email = document.getElementById('resEmail').value.trim();
    const phone = document.getElementById('resPhone').value.trim();
    if (!name || !email || !phone) {
      alert('Semua data diri harus diisi!'); return;
    }
    if (!email.includes('@')) {
      alert('Email tidak valid!'); return;
    }
    showKonfirmasi();
  }

  // Sembunyikan semua step
  [1,2,3,4].forEach(s => {
    document.getElementById(`step${s}`)?.classList.add('hidden');
    updateStepIndicator(s, false);
  });

  // Tampilkan step aktif
  document.getElementById(`step${step}`)?.classList.remove('hidden');
  updateStepIndicator(step, true);
}

function updateStepIndicator(step, active) {
  const el = document.getElementById(`step${step}Ind`);
  if (!el) return;
  if (active) {
    el.classList.replace('bg-gray-700', 'bg-primary');
    el.classList.replace('text-gray-400', 'text-white');
  } else {
    el.classList.replace('bg-primary', 'bg-gray-700');
    el.classList.replace('text-white', 'text-gray-400');
  }
}

// ===== TAMPILKAN KONFIRMASI =====
function showKonfirmasi() {
  const filmId   = document.getElementById('resFilm').value;
  const film     = filmData.find(f => f.id === parseInt(filmId));
  const date     = document.getElementById('resDate').value;
  const time     = document.getElementById('resTime').value;
  const name     = document.getElementById('resName').value.trim();
  const email    = document.getElementById('resEmail').value.trim();
  const phone    = document.getElementById('resPhone').value.trim();
  const total    = film ? film.harga * selectedSeats.length : 0;

  const detail = document.getElementById('konfirmasiDetail');
  detail.innerHTML = `
    <div class="flex justify-between"><span class="text-gray-500">Film</span><span class="text-white font-semibold">${film?.judul || '-'}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Tanggal</span><span class="text-white">${formatTanggal(date)}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Jadwal</span><span class="text-white">${time} WIB</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Kursi</span><span class="text-white">${selectedSeats.join(', ')}</span></div>
    <hr class="border-gray-700"/>
    <div class="flex justify-between"><span class="text-gray-500">Nama</span><span class="text-white">${name}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="text-white">${email}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">No. HP</span><span class="text-white">${phone}</span></div>
    <hr class="border-gray-700"/>
    <div class="flex justify-between text-base font-bold">
      <span class="text-gray-300">Total Bayar</span>
      <span class="text-green-400">${formatRupiah(total)}</span>
    </div>
  `;
}

// ===== SUBMIT RESERVASI =====
async function submitReservasi() {
  const filmId  = document.getElementById('resFilm').value;
  const film    = filmData.find(f => f.id === parseInt(filmId));
  const date    = document.getElementById('resDate').value;
  const time    = document.getElementById('resTime').value;
  const name    = document.getElementById('resName').value.trim();
  const email   = document.getElementById('resEmail').value.trim();
  const phone   = document.getElementById('resPhone').value.trim();
  const total   = film ? film.harga * selectedSeats.length : 0;

  const data = {
    film_id:    parseInt(filmId),    // untuk database
    filmId:     parseInt(filmId),    // untuk localStorage
    filmJudul:  film?.judul,
    tanggal:    date,
    jadwal:     time,
    kursi:      [...selectedSeats],  // array untuk database
    nama:       name,
    email:      email,
    telepon:    phone,
    total:      total,
    total_harga:total,
  };

  if (PAKAI_DATABASE_RES) {
    // ---- Kirim ke PHP + MySQL ----
    try {
      const res  = await fetch(API_URL_RES, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(data),
      });
      const json = await res.json();

      if (json.success) {
        const msg = document.getElementById('resMsg');
        msg.innerHTML = `🎉 Reservasi berhasil! Kode tiket kamu: <strong class="text-yellow-400">${json.kode_tiket}</strong>`;
        msg.classList.remove('hidden');
        document.querySelectorAll('[onclick^="goStep"], [onclick^="submitReservasi"]')
          .forEach(btn => btn.disabled = true);
      } else {
        alert('❌ ' + json.message);
      }
    } catch (err) {
      alert('⚠️ Gagal terhubung ke server. Menyimpan lokal.');
      simpanLokal(data);
    }
  } else {
    // ---- Simpan ke localStorage ----
    simpanLokal(data);
  }
}

// ===== SIMPAN KE LOCALSTORAGE =====
function simpanLokal(data) {
  const record = {
    ...data,
    id:        Date.now(),
    kode:      'TIK-' + Math.random().toString(36).substr(2, 6).toUpperCase(),
    createdAt: new Date().toISOString()
  };
  const all = JSON.parse(localStorage.getItem('cinereservasi') || '[]');
  all.push(record);
  localStorage.setItem('cinereservasi', JSON.stringify(all));

  const msg = document.getElementById('resMsg');
  msg.innerHTML = `🎉 Reservasi tersimpan! Kode tiket: <strong class="text-yellow-400">${record.kode}</strong>`;
  msg.classList.remove('hidden');
  document.querySelectorAll('[onclick^="goStep"], [onclick^="submitReservasi"]')
    .forEach(btn => btn.disabled = true);
}

// ===== HELPER =====
function formatTanggal(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}
