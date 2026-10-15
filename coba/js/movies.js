/**
 * movies.js
 * Data film statis + fungsi utilitas yang dipakai di semua halaman.
 * Ini adalah data dummy untuk bahan belajar.
 */

// ===== DATA FILM =====
const filmData = [
  {
    id: 1,
    judul: "Avengers: Endgame",
    genre: "Aksi",
    tahun: 2019,
    rating: 4.8,
    durasi: "181 menit",
    sutradara: "Anthony & Joe Russo",
    sinopsis: "Para Avengers berkumpul kembali untuk membatalkan aksi Thanos yang telah memusnahkan setengah alam semesta.",
    poster: "https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg",
    trailer: "TcMBFSGVi1c",  // YouTube video ID
    harga: 50000
  },
  {
    id: 2,
    judul: "Inception",
    genre: "Sci-Fi",
    tahun: 2010,
    rating: 4.9,
    durasi: "148 menit",
    sutradara: "Christopher Nolan",
    sinopsis: "Seorang pencuri mahir mampu memasuki mimpi orang lain dan mencuri rahasia terdalam dari bawah sadar mereka.",
    poster: "https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg",
    trailer: "YoHD9XEInc0",
    harga: 45000
  },
  {
    id: 3,
    judul: "The Dark Knight",
    genre: "Aksi",
    tahun: 2008,
    rating: 4.9,
    durasi: "152 menit",
    sutradara: "Christopher Nolan",
    sinopsis: "Batman menghadapi musuh paling berbahayanya, Joker, yang berniat menciptakan kekacauan di Gotham City.",
    poster: "https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg",
    trailer: "EXeTwQWrcwY",
    harga: 45000
  },
  {
    id: 4,
    judul: "Interstellar",
    genre: "Sci-Fi",
    tahun: 2014,
    rating: 4.7,
    durasi: "169 menit",
    sutradara: "Christopher Nolan",
    sinopsis: "Tim astronot menjelajahi lubang cacing untuk mencari planet baru yang bisa dihuni manusia.",
    poster: "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg",
    trailer: "zSWdZVtXT7E",
    harga: 45000
  },
  {
    id: 5,
    judul: "Parasite",
    genre: "Drama",
    tahun: 2019,
    rating: 4.8,
    durasi: "132 menit",
    sutradara: "Bong Joon-ho",
    sinopsis: "Sebuah keluarga miskin secara perlahan menyusup ke kehidupan keluarga kaya dalam cara yang tak terduga.",
    poster: "https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg",
    trailer: "5xH0HfJHsaY",
    harga: 40000
  },
  {
    id: 6,
    judul: "Get Out",
    genre: "Horor",
    tahun: 2017,
    rating: 4.5,
    durasi: "104 menit",
    sutradara: "Jordan Peele",
    sinopsis: "Seorang pria kulit hitam menemukan kebenaran mengerikan saat bertemu keluarga kekasihnya yang kulit putih.",
    poster: "https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg",
    trailer: "DzfpyUB60YY",
    harga: 40000
  },
  {
    id: 7,
    judul: "The Grand Budapest Hotel",
    genre: "Komedi",
    tahun: 2014,
    rating: 4.6,
    durasi: "99 menit",
    sutradara: "Wes Anderson",
    sinopsis: "Petualangan konyol seorang concierge hotel ikonik Eropa bersama petugas bellboy muda kesayangannya.",
    poster: "https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg",
    trailer: "1Fg4oaktmtI",
    harga: 40000
  },
  {
    id: 8,
    judul: "Dune",
    genre: "Sci-Fi",
    tahun: 2021,
    rating: 4.6,
    durasi: "155 menit",
    sutradara: "Denis Villeneuve",
    sinopsis: "Seorang pangeran muda memimpin perlawanan demi menguasai planet penghasil rempah paling berharga di alam semesta.",
    poster: "https://image.tmdb.org/t/p/w500/d5NXSklpcvksdi7We8R7ob5BLqS.jpg",
    trailer: "n9xhJrPXop4",
    harga: 55000
  }
];

// ===== UTILITAS =====

/**
 * Render bintang rating (⭐)
 * @param {number} rating - nilai rating (0-5)
 * @returns {string} HTML string bintang
 */
function renderStars(rating) {
  const full  = Math.floor(rating);
  const half  = rating % 1 >= 0.5 ? 1 : 0;
  const empty = 5 - full - half;
  return '★'.repeat(full) + (half ? '½' : '') + '☆'.repeat(empty);
}

/**
 * Format harga ke Rupiah
 * @param {number} harga
 * @returns {string}
 */
function formatRupiah(harga) {
  return 'Rp ' + harga.toLocaleString('id-ID');
}

// ===== RENDER FILM POPULER (halaman index) =====
function renderPopularMovies() {
  const container = document.getElementById('popularMovies');
  if (!container) return;

  // Ambil 4 film dengan rating tertinggi
  const top4 = [...filmData].sort((a, b) => b.rating - a.rating).slice(0, 4);

  container.innerHTML = top4.map(film => `
    <div class="movie-card fade-up" onclick="window.location='movies.html'">
      <img src="${film.poster}" alt="${film.judul}" loading="lazy"/>
      <div class="card-info">
        <div class="card-title">${film.judul}</div>
        <div class="card-genre">${film.genre} · ${film.tahun}</div>
        <div class="card-rating">${renderStars(film.rating)} ${film.rating}</div>
      </div>
    </div>
  `).join('');
}

// Panggil jika ada container-nya (halaman index)
renderPopularMovies();
