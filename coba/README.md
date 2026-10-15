# 🎬 CineReview — Website Review Film

Proyek belajar HTML, Tailwind CSS, JavaScript, dan PHP.

## 📁 Struktur Proyek

```
coba/
├── index.html          → Halaman utama (hero + film populer + trailer)
├── movies.html         → Daftar film + search + filter + modal detail
├── review.html         → Tulis & lihat review film
├── reservasi.html      → Reservasi tiket (4 step)
├── about.html          → Tentang website
│
├── css/
│   └── style.css       → CSS tambahan (Tailwind sudah via CDN)
│
├── js/
│   ├── movies.js       → Data film + utilitas (dipakai semua halaman)
│   ├── movies-page.js  → Logika halaman movies.html
│   ├── trailer.js      → Render trailer YouTube di index
│   ├── review.js       → Logika halaman review.html
│   └── reservasi.js    → Logika halaman reservasi.html
│
└── api/
    ├── movies.php      → REST API: GET daftar film
    ├── review.php      → REST API: GET/POST review
    └── reservasi.php   → REST API: GET/POST reservasi
```

## 🚀 Cara Menjalankan

### Cara 1: Buka Langsung (Tanpa Server)
Cukup klik dua kali `index.html`. Semua fitur bekerja karena data
disimpan di `localStorage` browser.

> ⚠️ Fitur PHP (api/) tidak aktif tanpa server lokal.

### Cara 2: Pakai XAMPP (PHP aktif)
1. Install XAMPP dari https://www.apachefriends.org/
2. Salin folder `coba` ke `C:\xampp\htdocs\`
3. Nyalakan Apache di XAMPP Control Panel
4. Buka browser: `http://localhost/coba/`
5. Uncomment bagian `kirimKeBackend()` di `review.js` dan `reservasi.js`

## 🛠️ Teknologi

| Teknologi       | Kegunaan                     |
|-----------------|------------------------------|
| HTML5           | Struktur halaman             |
| Tailwind CSS    | Styling (via CDN)            |
| JavaScript (ES6)| Interaktivitas & data        |
| PHP 8           | Backend REST API             |
| localStorage    | Penyimpanan data sisi klien  |
| YouTube Embed   | Tampilkan trailer film       |

## 📺 YouTube API

Trailer saat ini menggunakan **YouTube iframe embed** (gratis, tanpa key).

Jika ingin menggunakan **YouTube Data API v3** (untuk search dinamis):
1. Daftar di https://console.cloud.google.com/
2. Aktifkan "YouTube Data API v3"
3. Buat API Key
4. Ganti `YOUR_API_KEY_HERE` di `js/trailer.js`
5. Uncomment fungsi `searchYouTubeTrailers()`

## 📚 Fitur Halaman

| Halaman         | Fitur Utama                              |
|-----------------|------------------------------------------|
| `index.html`    | Hero, film populer, 4 trailer YouTube    |
| `movies.html`   | Grid film, search, filter genre, modal   |
| `review.html`   | Form review, rating bintang, daftar review |
| `reservasi.html`| 4-step booking: film → kursi → data → konfirmasi |
| `about.html`    | Info proyek & teknologi                  |
