-- ============================================================
--  CineReview Database
--  Jalankan file ini di phpMyAdmin atau MySQL CLI:
--  mysql -u root -p < cinereview.sql
-- ============================================================

-- Buat & pilih database
CREATE DATABASE IF NOT EXISTS cinereview
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE cinereview;

-- ============================================================
-- Tabel: films
-- Menyimpan data film
-- ============================================================
CREATE TABLE IF NOT EXISTS films (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  judul       VARCHAR(200) NOT NULL,
  genre       VARCHAR(50)  NOT NULL,
  tahun       YEAR         NOT NULL,
  rating      DECIMAL(3,1) DEFAULT 0.0,
  durasi      VARCHAR(30)  NOT NULL,
  sutradara   VARCHAR(100) NOT NULL,
  sinopsis    TEXT,
  poster      VARCHAR(500),
  trailer_id  VARCHAR(50)  COMMENT 'YouTube video ID',
  harga       INT          DEFAULT 0 COMMENT 'Harga tiket dalam rupiah',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- Tabel: reviews (komentar/ulasan film)
-- ============================================================
CREATE TABLE IF NOT EXISTS reviews (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  film_id     INT          NOT NULL,
  nama        VARCHAR(100) NOT NULL,
  email       VARCHAR(150),
  rating      TINYINT      NOT NULL CHECK (rating BETWEEN 1 AND 5),
  teks        TEXT         NOT NULL,
  disetujui   TINYINT(1)   DEFAULT 1 COMMENT '1=tampil, 0=disembunyikan',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Tabel: reservasi (pemesanan tiket)
-- ============================================================
CREATE TABLE IF NOT EXISTS reservasi (
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  kode_tiket  VARCHAR(20)  NOT NULL UNIQUE,
  film_id     INT          NOT NULL,
  tanggal     DATE         NOT NULL,
  jadwal      VARCHAR(10)  NOT NULL COMMENT 'Contoh: 13:00',
  kursi       VARCHAR(100) NOT NULL COMMENT 'Contoh: A1,A2,B3',
  nama        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL,
  telepon     VARCHAR(20)  NOT NULL,
  total_harga INT          NOT NULL DEFAULT 0,
  status      ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Data awal: Films
-- ============================================================
INSERT INTO films (judul, genre, tahun, rating, durasi, sutradara, sinopsis, poster, trailer_id, harga) VALUES
('Avengers: Endgame', 'Aksi',   2019, 4.8, '181 menit', 'Anthony & Joe Russo',
 'Para Avengers berkumpul kembali untuk membatalkan aksi Thanos yang telah memusnahkan setengah alam semesta.',
 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg', 'TcMBFSGVi1c', 50000),

('Inception',         'Sci-Fi', 2010, 4.9, '148 menit', 'Christopher Nolan',
 'Seorang pencuri mahir mampu memasuki mimpi orang lain dan mencuri rahasia terdalam dari bawah sadar mereka.',
 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg', 'YoHD9XEInc0', 45000),

('The Dark Knight',   'Aksi',   2008, 4.9, '152 menit', 'Christopher Nolan',
 'Batman menghadapi musuh paling berbahayanya, Joker, yang berniat menciptakan kekacauan di Gotham City.',
 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg', 'EXeTwQWrcwY', 45000),

('Interstellar',      'Sci-Fi', 2014, 4.7, '169 menit', 'Christopher Nolan',
 'Tim astronot menjelajahi lubang cacing untuk mencari planet baru yang bisa dihuni manusia.',
 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'zSWdZVtXT7E', 45000),

('Parasite',          'Drama',  2019, 4.8, '132 menit', 'Bong Joon-ho',
 'Sebuah keluarga miskin secara perlahan menyusup ke kehidupan keluarga kaya dalam cara yang tak terduga.',
 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', '5xH0HfJHsaY', 40000),

('Get Out',           'Horor',  2017, 4.5, '104 menit', 'Jordan Peele',
 'Seorang pria kulit hitam menemukan kebenaran mengerikan saat bertemu keluarga kekasihnya.',
 'https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg', 'DzfpyUB60YY', 40000),

('The Grand Budapest Hotel', 'Komedi', 2014, 4.6, '99 menit', 'Wes Anderson',
 'Petualangan konyol seorang concierge hotel ikonik Eropa bersama petugas bellboy muda kesayangannya.',
 'https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg', '1Fg4oaktmtI', 40000),

('Dune',              'Sci-Fi', 2021, 4.6, '155 menit', 'Denis Villeneuve',
 'Seorang pangeran muda memimpin perlawanan demi menguasai planet penghasil rempah paling berharga.',
 'https://image.tmdb.org/t/p/w500/d5NXSklpcvksdi7We8R7ob5BLqS.jpg', 'n9xhJrPXop4', 55000);

-- ============================================================
-- Contoh data review awal
-- ============================================================
INSERT INTO reviews (film_id, nama, email, rating, teks) VALUES
(1, 'Budi Santoso',   'budi@email.com',   5, 'Film terbaik Marvel sepanjang masa! Endgame benar-benar memuaskan setelah perjalanan panjang 11 tahun.'),
(1, 'Siti Rahayu',    'siti@email.com',   4, 'Emosional banget, terutama adegan penutup. Sedikit terlalu panjang tapi sepadan!'),
(2, 'Andi Wijaya',    'andi@email.com',   5, 'Masterpiece Nolan. Setiap kali nonton selalu nemu hal baru. Dream within a dream!'),
(3, 'Dewi Kusuma',    'dewi@email.com',   5, 'Heath Ledger sebagai Joker adalah penampilan terbaik dalam sejarah film superhero.'),
(4, 'Rizky Pratama',  'rizky@email.com',  5, 'Visualnya luar biasa. Musik Hans Zimmer bikin bulu kuduk berdiri. Must watch!'),
(5, 'Maya Indah',     'maya@email.com',   5, 'Film Korea yang wajib ditonton. Ceritanya unpredictable banget!');

-- ============================================================
-- Contoh data reservasi awal
-- ============================================================
INSERT INTO reservasi (kode_tiket, film_id, tanggal, jadwal, kursi, nama, email, telepon, total_harga, status) VALUES
('TIK-A1B2C3', 1, '2026-08-10', '20:30', 'C3,C4',     'Budi Santoso',  'budi@email.com',  '081234567890', 100000, 'confirmed'),
('TIK-D4E5F6', 2, '2026-08-11', '15:30', 'D5,D6,D7',  'Siti Rahayu',   'siti@email.com',  '082345678901', 135000, 'pending');
