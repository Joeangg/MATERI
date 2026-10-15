/**
 * movies-page.js
 * Logika untuk halaman movies.html
 * - Render grid film
 * - Search & filter genre
 * - Modal detail film + trailer
 */

let filteredFilms = [...filmData]; // salinan data untuk filter

// ===== RENDER GRID =====
function renderMovieGrid(data) {
  const grid = document.getElementById('movieGrid');
  if (!grid) return;

  if (data.length === 0) {
    grid.innerHTML = `<div class="col-span-4 text-center text-gray-500 py-10">Film tidak ditemukan.</div>`;
    return;
  }

  grid.innerHTML = data.map(film => `
    <div class="movie-card fade-up" onclick="openModal(${film.id})">
      <img src="${film.poster}" alt="${film.judul}" loading="lazy"/>
      <div class="card-info">
        <div class="card-title">${film.judul}</div>
        <div class="card-genre">${film.genre} · ${film.tahun}</div>
        <div class="card-rating">${renderStars(film.rating)} ${film.rating}</div>
      </div>
    </div>
  `).join('');
}

// ===== SEARCH & FILTER =====
function applyFilter() {
  const keyword = document.getElementById('searchInput').value.toLowerCase();
  const genre   = document.getElementById('genreFilter').value;

  filteredFilms = filmData.filter(film => {
    const matchKeyword = film.judul.toLowerCase().includes(keyword);
    const matchGenre   = genre === '' || film.genre === genre;
    return matchKeyword && matchGenre;
  });

  renderMovieGrid(filteredFilms);
}

document.getElementById('searchInput')?.addEventListener('input', applyFilter);
document.getElementById('genreFilter')?.addEventListener('change', applyFilter);

// ===== MODAL =====
function openModal(id) {
  const film = filmData.find(f => f.id === id);
  if (!film) return;

  const content = document.getElementById('modalContent');
  content.innerHTML = `
    <div class="flex flex-col md:flex-row gap-6">
      <!-- Poster -->
      <img src="${film.poster}" alt="${film.judul}"
        class="w-full md:w-40 rounded-xl object-cover flex-shrink-0" style="height:220px;object-fit:cover;"/>

      <!-- Info -->
      <div class="flex-1">
        <h2 class="text-2xl font-extrabold mb-1">${film.judul}</h2>
        <p class="text-gray-400 text-sm mb-3">${film.genre} · ${film.tahun} · ${film.durasi}</p>
        <p class="text-yellow-400 text-sm mb-3">${renderStars(film.rating)} <span class="text-white">${film.rating}/5</span></p>
        <p class="text-gray-300 text-sm mb-3"><span class="text-gray-500">Sutradara:</span> ${film.sutradara}</p>
        <p class="text-gray-300 text-sm mb-4 leading-relaxed">${film.sinopsis}</p>
        <p class="text-green-400 font-bold mb-4">${formatRupiah(film.harga)} / tiket</p>
        <div class="flex gap-3 flex-wrap">
          <a href="reservasi.html" class="bg-primary hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full text-sm transition">
            🎟️ Reservasi
          </a>
          <a href="review.html" class="border border-gray-600 hover:border-white text-gray-300 hover:text-white font-bold py-2 px-6 rounded-full text-sm transition">
            ✍️ Review
          </a>
        </div>
      </div>
    </div>

    <!-- Trailer Embed -->
    <div class="mt-6">
      <h3 class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-widest">Trailer</h3>
      <div class="rounded-xl overflow-hidden">
        <iframe
          src="https://www.youtube.com/embed/${film.trailer}?rel=0"
          width="100%" height="250"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          title="Trailer ${film.judul}">
        </iframe>
      </div>
    </div>
  `;

  document.getElementById('movieModal').classList.remove('hidden');
  document.getElementById('movieModal').classList.add('flex');
}

function closeModal() {
  const modal = document.getElementById('movieModal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  // Hentikan video YouTube
  document.getElementById('modalContent').innerHTML = '';
}

// Tutup modal klik di luar
document.getElementById('movieModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// Tutup modal dengan Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal();
});

// ===== INIT =====
renderMovieGrid(filmData);
