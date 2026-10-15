<?php
/**
 * database/setup.php
 * Script untuk membuat database dan tabel secara otomatis.
 * 
 * Akses via browser: http://localhost/coba/database/setup.php
 * Jalankan SEKALI saja saat pertama kali setup.
 */

// Konfigurasi koneksi (tanpa nama database dulu)
$host = 'localhost';
$user = 'root';
$pass = '';       // Kosong = default XAMPP

$steps = []; // Log langkah-langkah

try {
    // Koneksi ke MySQL tanpa pilih database
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $steps[] = ['ok', 'Koneksi ke MySQL berhasil ✓'];

    // Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS cinereview CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $steps[] = ['ok', 'Database <strong>cinereview</strong> dibuat ✓'];

    // Pilih database
    $pdo->exec("USE cinereview");

    // ---- Tabel films ----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS films (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            judul      VARCHAR(200) NOT NULL,
            genre      VARCHAR(50)  NOT NULL,
            tahun      YEAR         NOT NULL,
            rating     DECIMAL(3,1) DEFAULT 0.0,
            durasi     VARCHAR(30)  NOT NULL,
            sutradara  VARCHAR(100) NOT NULL,
            sinopsis   TEXT,
            poster     VARCHAR(500),
            trailer_id VARCHAR(50),
            harga      INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");
    $steps[] = ['ok', 'Tabel <strong>films</strong> dibuat ✓'];

    // ---- Tabel reviews ----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reviews (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            film_id    INT          NOT NULL,
            nama       VARCHAR(100) NOT NULL,
            email      VARCHAR(150),
            rating     TINYINT      NOT NULL,
            teks       TEXT         NOT NULL,
            disetujui  TINYINT(1)   DEFAULT 1,
            created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
        ) ENGINE=InnoDB
    ");
    $steps[] = ['ok', 'Tabel <strong>reviews</strong> dibuat ✓'];

    // ---- Tabel reservasi ----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reservasi (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            kode_tiket  VARCHAR(20)  NOT NULL UNIQUE,
            film_id     INT          NOT NULL,
            tanggal     DATE         NOT NULL,
            jadwal      VARCHAR(10)  NOT NULL,
            kursi       VARCHAR(100) NOT NULL,
            nama        VARCHAR(100) NOT NULL,
            email       VARCHAR(150) NOT NULL,
            telepon     VARCHAR(20)  NOT NULL,
            total_harga INT          NOT NULL DEFAULT 0,
            status      ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
            created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
        ) ENGINE=InnoDB
    ");
    $steps[] = ['ok', 'Tabel <strong>reservasi</strong> dibuat ✓'];

    // ---- Insert data film (skip jika sudah ada) ----
    $cek = $pdo->query("SELECT COUNT(*) FROM films")->fetchColumn();
    if ($cek == 0) {
        $pdo->exec("
            INSERT INTO films (judul,genre,tahun,rating,durasi,sutradara,sinopsis,poster,trailer_id,harga) VALUES
            ('Avengers: Endgame','Aksi',2019,4.8,'181 menit','Anthony & Joe Russo','Para Avengers berkumpul kembali untuk membatalkan aksi Thanos.','https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg','TcMBFSGVi1c',50000),
            ('Inception','Sci-Fi',2010,4.9,'148 menit','Christopher Nolan','Seorang pencuri mahir memasuki mimpi orang lain.','https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg','YoHD9XEInc0',45000),
            ('The Dark Knight','Aksi',2008,4.9,'152 menit','Christopher Nolan','Batman menghadapi musuh paling berbahaya, Joker.','https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg','EXeTwQWrcwY',45000),
            ('Interstellar','Sci-Fi',2014,4.7,'169 menit','Christopher Nolan','Tim astronot menjelajahi lubang cacing.','https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg','zSWdZVtXT7E',45000),
            ('Parasite','Drama',2019,4.8,'132 menit','Bong Joon-ho','Keluarga miskin menyusup ke kehidupan keluarga kaya.','https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg','5xH0HfJHsaY',40000),
            ('Get Out','Horor',2017,4.5,'104 menit','Jordan Peele','Seorang pria menemukan kebenaran mengerikan.','https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg','DzfpyUB60YY',40000),
            ('The Grand Budapest Hotel','Komedi',2014,4.6,'99 menit','Wes Anderson','Petualangan concierge hotel ikonik Eropa.','https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg','1Fg4oaktmtI',40000),
            ('Dune','Sci-Fi',2021,4.6,'155 menit','Denis Villeneuve','Seorang pangeran muda melawan di planet rempah.','https://image.tmdb.org/t/p/w500/d5NXSklpcvksdi7We8R7ob5BLqS.jpg','n9xhJrPXop4',55000)
        ");
        $steps[] = ['ok', '8 data film awal berhasil dimasukkan ✓'];
    } else {
        $steps[] = ['info', "Tabel films sudah berisi $cek baris, skip insert."];
    }

    // ---- Insert contoh review ----
    $cekR = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    if ($cekR == 0) {
        $pdo->exec("
            INSERT INTO reviews (film_id,nama,email,rating,teks) VALUES
            (1,'Budi Santoso','budi@email.com',5,'Film terbaik Marvel! Endgame benar-benar memuaskan.'),
            (2,'Andi Wijaya','andi@email.com',5,'Masterpiece Nolan. Dream within a dream!'),
            (3,'Dewi Kusuma','dewi@email.com',5,'Heath Ledger sebagai Joker adalah penampilan terbaik sepanjang masa.')
        ");
        $steps[] = ['ok', '3 contoh review dimasukkan ✓'];
    }

    $steps[] = ['success', '🎉 Setup selesai! Database siap digunakan.'];

} catch (PDOException $e) {
    $steps[] = ['error', 'ERROR: ' . $e->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <title>CineReview — Setup Database</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen flex items-center justify-center p-6">
  <div class="max-w-xl w-full">
    <h1 class="text-3xl font-extrabold mb-2">🎬 CineReview</h1>
    <p class="text-gray-400 mb-8">Setup Database MySQL</p>

    <div class="space-y-3">
      <?php foreach ($steps as [$type, $msg]): ?>
        <?php
          $colors = [
            'ok'      => 'bg-green-900 border-green-700 text-green-300',
            'info'    => 'bg-blue-900  border-blue-700  text-blue-300',
            'error'   => 'bg-red-900   border-red-700   text-red-300',
            'success' => 'bg-yellow-900 border-yellow-600 text-yellow-300 text-lg font-bold',
          ];
          $icons = ['ok'=>'✓', 'info'=>'ℹ', 'error'=>'✗', 'success'=>'🎉'];
        ?>
        <div class="<?= $colors[$type] ?> border rounded-xl px-4 py-3">
          <?= $icons[$type] ?> <?= $msg ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-8 bg-gray-900 rounded-xl p-5 border border-gray-800">
      <h2 class="font-bold mb-3 text-yellow-400">Langkah Selanjutnya</h2>
      <ol class="text-sm text-gray-300 space-y-2 list-decimal list-inside">
        <li>Buka <code class="bg-gray-800 px-1 rounded">js/review.js</code></li>
        <li>Ubah baris pertama: <code class="bg-gray-800 px-1 rounded">const PAKAI_DATABASE = <span class="text-green-400">true</span>;</code></li>
        <li>Buka <code class="bg-gray-800 px-1 rounded">js/reservasi.js</code> dan lakukan hal yang sama</li>
        <li>Sekarang review & reservasi tersimpan ke MySQL!</li>
      </ol>
    </div>

    <div class="mt-4 flex gap-3">
      <a href="../index.html" class="bg-red-700 hover:bg-red-600 text-white px-6 py-2 rounded-full text-sm font-bold transition">
        ← Ke Website
      </a>
      <a href="../review.html" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-full text-sm font-bold transition">
        Coba Review
      </a>
    </div>
  </div>
</body>
</html>
