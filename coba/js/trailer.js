/**
 * trailer.js
 * Menampilkan trailer YouTube di halaman index.
 *
 * Cara pakai YouTube API:
 * 1. Trailer diembed langsung via <iframe> (tidak perlu API key).
 * 2. Jika ingin fitur lanjut (search, info channel), daftarkan API key di:
 *    https://console.cloud.google.com/
 *    lalu aktifkan "YouTube Data API v3"
 *    Ganti nilai API_KEY di bawah.
 */

// ===== KONFIGURASI =====
// Jika kamu punya YouTube Data API key, taruh di sini.
// Untuk demo ini kita pakai embed langsung (gratis, tanpa key).
const YOUTUBE_API_KEY = 'YOUR_API_KEY_HERE'; // Ganti dengan API key milikmu

// Trailer yang akan ditampilkan di home page
// Format: { judul, videoId }
const featuredTrailers = [
  { judul: 'Avengers: Endgame',    videoId: 'TcMBFSGVi1c' },
  { judul: 'Inception',             videoId: 'YoHD9XEInc0' },
  { judul: 'Interstellar',          videoId: 'zSWdZVtXT7E' },
  { judul: 'Dune (2021)',           videoId: 'n9xhJrPXop4' },
];

// ===== RENDER TRAILER EMBED =====
function renderTrailers() {
  const container = document.getElementById('trailerSection');
  if (!container) return;

  container.innerHTML = featuredTrailers.map(t => `
    <div class="trailer-card fade-up">
      <!-- YouTube Embed (iframe API) -->
      <iframe
        src="https://www.youtube.com/embed/${t.videoId}?rel=0&modestbranding=1"
        title="${t.judul}"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen>
      </iframe>
      <div class="p-4">
        <p class="font-bold text-white">🎬 ${t.judul}</p>
        <p class="text-gray-400 text-xs mt-1">Official Trailer · YouTube</p>
      </div>
    </div>
  `).join('');
}

// ===== CONTOH: CARA PAKAI YOUTUBE DATA API v3 =====
/**
 * Fungsi ini adalah CONTOH bagaimana memanggil YouTube Search API.
 * Uncomment dan ganti API_KEY jika ingin mencoba.
 *
 * async function searchYouTubeTrailers(query) {
 *   const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&q=${encodeURIComponent(query + ' official trailer')}&type=video&maxResults=4&key=${YOUTUBE_API_KEY}`;
 *   const res  = await fetch(url);
 *   const data = await res.json();
 *
 *   return data.items.map(item => ({
 *     judul:   item.snippet.title,
 *     videoId: item.id.videoId,
 *   }));
 * }
 *
 * // Cara panggil:
 * // const results = await searchYouTubeTrailers('Avengers');
 * // renderTrailersDynamic(results);
 */

// ===== INIT =====
renderTrailers();
