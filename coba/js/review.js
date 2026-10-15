/**
 * review.js
 * Logika untuk halaman review.html
 * - Isi dropdown film dari filmData
 * - Rating bintang interaktif
 * - Simpan review ke DATABASE via PHP API (jika server aktif)
 * - Fallback ke localStorage jika tidak ada server
 */

// Ganti ke true jika menjalankan di XAMPP / server lokal
const PAKAI_DATABASE = false;
const API_URL = 'api/review.php';

// ===== ISI DROPDOWN FILM =====
function populateMovieDropdown(selectId) {
  const select = document.getElementById(selectId);
  if (!select) return;
  filmData.forEach(film => {
    const opt = document.createElement('option');
    opt.value = film.id;
    opt.textContent = film.judul;
    select.appendChild(opt);
  });
}

populateMovieDropdown('reviewMovie');

// ===== RATING BINTANG =====
let currentRating = 0;

document.querySelectorAll('#starRating span').forEach(star => {
  // Hover
  star.addEventListener('mouseenter', () => {
    const val = parseInt(star.dataset.value);
    highlightStars(val);
  });

  // Mouse leave → kembali ke rating yang dipilih
  star.addEventListener('mouseleave', () => {
    highlightStars(currentRating);
  });

  // Klik → set rating
  star.addEventListener('click', () => {
    currentRating = parseInt(star.dataset.value);
    document.getElementById('reviewRating').value = currentRating;
    highlightStars(currentRating);
  });
});

function highlightStars(val) {
  document.querySelectorAll('#starRating span').forEach(s => {
    const sv = parseInt(s.dataset.value);
    s.textContent = sv <= val ? '★' : '☆';
    s.classList.toggle('active', sv <= val);
  });
}

// ===== FORM SUBMIT =====
document.getElementById('reviewForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();

  const name   = document.getElementById('reviewName').value.trim();
  const filmId = document.getElementById('reviewMovie').value;
  const rating = parseInt(document.getElementById('reviewRating').value);
  const text   = document.getElementById('reviewText').value.trim();

  // Validasi sederhana
  if (!name || !filmId || rating === 0 || !text) {
    alert('⚠️ Semua kolom harus diisi dan rating harus dipilih!');
    return;
  }

  const film = filmData.find(f => f.id === parseInt(filmId));

  const reviewPayload = {
    nama:       name,
    film_id:    parseInt(filmId),   // key untuk PHP/database
    filmId:     parseInt(filmId),   // key untuk localStorage
    filmJudul:  film.judul,
    filmPoster: film.poster,
    rating:     rating,
    teks:       text,
  };

  if (PAKAI_DATABASE) {
    // ---- Kirim ke PHP + MySQL ----
    await kirimKeDatabase(reviewPayload);
  } else {
    // ---- Simpan ke localStorage (tanpa server) ----
    const review = {
      ...reviewPayload,
      id:      Date.now(),
      tanggal: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
    };
    const reviews = getReviewsLocal();
    reviews.unshift(review);
    localStorage.setItem('cinereviews', JSON.stringify(reviews));
    tampilkanSukses();
    renderReviewList();
  }

  // Reset form
  this.reset();
  currentRating = 0;
  highlightStars(0);
});

// ===== AMBIL REVIEW DARI LOCALSTORAGE =====
function getReviews() {
  return JSON.parse(localStorage.getItem('cinereviews') || '[]');
}

// Alias (nama lama tetap bisa dipakai)
function getReviewsLocal() {
  return getReviews();
}

// ===== TAMPILKAN PESAN SUKSES =====
function tampilkanSukses() {
  const msg = document.getElementById('reviewMsg');
  msg.classList.remove('hidden');
  setTimeout(() => msg.classList.add('hidden'), 3000);
}

// ===== RENDER DAFTAR REVIEW =====
async function renderReviewList() {
  const container = document.getElementById('reviewList');
  if (!container) return;

  let reviews = [];

  if (PAKAI_DATABASE) {
    // Ambil dari database via API
    try {
      const res  = await fetch(API_URL);
      const json = await res.json();
      reviews = json.data || [];
      // Normalkan key agar konsisten dengan localStorage
      reviews = reviews.map(r => ({
        ...r,
        filmJudul:  r.film_judul  || r.filmJudul,
        filmPoster: r.film_poster || r.filmPoster,
        tanggal:    r.tanggal     || new Date(r.created_at).toLocaleDateString('id-ID'),
      }));
    } catch (err) {
      container.innerHTML = `<div class="text-center text-red-400 py-6">⚠️ Gagal memuat review dari server.</div>`;
      return;
    }
  } else {
    // Ambil dari localStorage
    reviews = getReviewsLocal();
  }

  if (reviews.length === 0) {
    container.innerHTML = `
      <div class="text-center text-gray-500 py-10">
        <div class="text-5xl mb-3">🎬</div>
        <p>Belum ada review. Jadilah yang pertama!</p>
      </div>`;
    return;
  }

  container.innerHTML = reviews.map(r => `
    <div class="review-card fade-up">
      <div class="flex items-start gap-4">
        <img src="${r.filmPoster}" alt="${r.filmJudul}"
          class="w-14 h-20 object-cover rounded-lg flex-shrink-0"/>
        <div class="flex-1">
          <div class="flex items-center justify-between flex-wrap gap-2 mb-1">
            <span class="font-bold text-white">${r.nama}</span>
            <span class="text-xs text-gray-500">${r.tanggal}</span>
          </div>
          <p class="text-primary text-sm font-semibold mb-1">${r.filmJudul}</p>
          <div class="text-yellow-400 text-sm mb-2">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</div>
          <p class="text-gray-300 text-sm leading-relaxed">${r.teks}</p>
        </div>
      </div>
    </div>
  `).join('');
}

// ===== KIRIM KE DATABASE VIA PHP API =====
async function kirimKeDatabase(payload) {
  try {
    const res  = await fetch(API_URL, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(payload),
    });
    const json = await res.json();

    if (json.success) {
      tampilkanSukses();
      renderReviewList(); // Refresh daftar dari database
    } else {
      alert('❌ Gagal menyimpan: ' + json.message);
    }
  } catch (err) {
    // Fallback ke localStorage jika server tidak tersedia
    console.warn('Server tidak tersedia, menyimpan ke localStorage.');
    const review = {
      ...payload,
      id:      Date.now(),
      tanggal: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
    };
    const reviews = getReviewsLocal();
    reviews.unshift(review);
    localStorage.setItem('cinereviews', JSON.stringify(reviews));
    tampilkanSukses();
    renderReviewList();
  }
}

// ===== INIT =====
renderReviewList();
