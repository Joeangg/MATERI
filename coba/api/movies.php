<?php
/**
 * api/movies.php
 * Backend PHP untuk data film.
 * 
 * Di proyek ini data film ada di js/movies.js (client-side).
 * File ini adalah contoh bagaimana data film bisa diambil dari server.
 * 
 * GET → Kembalikan semua data film dalam format JSON
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Data film (sama seperti di movies.js, tapi di server)
$films = [
    [
        'id'        => 1,
        'judul'     => 'Avengers: Endgame',
        'genre'     => 'Aksi',
        'tahun'     => 2019,
        'rating'    => 4.8,
        'durasi'    => '181 menit',
        'sutradara' => 'Anthony & Joe Russo',
        'sinopsis'  => 'Para Avengers berkumpul kembali untuk membatalkan aksi Thanos.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
        'trailer'   => 'TcMBFSGVi1c',
        'harga'     => 50000,
    ],
    [
        'id'        => 2,
        'judul'     => 'Inception',
        'genre'     => 'Sci-Fi',
        'tahun'     => 2010,
        'rating'    => 4.9,
        'durasi'    => '148 menit',
        'sutradara' => 'Christopher Nolan',
        'sinopsis'  => 'Seorang pencuri mahir memasuki mimpi orang lain.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg',
        'trailer'   => 'YoHD9XEInc0',
        'harga'     => 45000,
    ],
    [
        'id'        => 3,
        'judul'     => 'The Dark Knight',
        'genre'     => 'Aksi',
        'tahun'     => 2008,
        'rating'    => 4.9,
        'durasi'    => '152 menit',
        'sutradara' => 'Christopher Nolan',
        'sinopsis'  => 'Batman menghadapi musuh paling berbahaya, Joker.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
        'trailer'   => 'EXeTwQWrcwY',
        'harga'     => 45000,
    ],
    [
        'id'        => 4,
        'judul'     => 'Interstellar',
        'genre'     => 'Sci-Fi',
        'tahun'     => 2014,
        'rating'    => 4.7,
        'durasi'    => '169 menit',
        'sutradara' => 'Christopher Nolan',
        'sinopsis'  => 'Tim astronot menjelajahi lubang cacing.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
        'trailer'   => 'zSWdZVtXT7E',
        'harga'     => 45000,
    ],
    [
        'id'        => 5,
        'judul'     => 'Parasite',
        'genre'     => 'Drama',
        'tahun'     => 2019,
        'rating'    => 4.8,
        'durasi'    => '132 menit',
        'sutradara' => 'Bong Joon-ho',
        'sinopsis'  => 'Keluarga miskin menyusup ke kehidupan keluarga kaya.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
        'trailer'   => '5xH0HfJHsaY',
        'harga'     => 40000,
    ],
    [
        'id'        => 6,
        'judul'     => 'Get Out',
        'genre'     => 'Horor',
        'tahun'     => 2017,
        'rating'    => 4.5,
        'durasi'    => '104 menit',
        'sutradara' => 'Jordan Peele',
        'sinopsis'  => 'Seorang pria menemukan kebenaran mengerikan.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg',
        'trailer'   => 'DzfpyUB60YY',
        'harga'     => 40000,
    ],
    [
        'id'        => 7,
        'judul'     => 'The Grand Budapest Hotel',
        'genre'     => 'Komedi',
        'tahun'     => 2014,
        'rating'    => 4.6,
        'durasi'    => '99 menit',
        'sutradara' => 'Wes Anderson',
        'sinopsis'  => 'Petualangan konyol seorang concierge hotel Eropa.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg',
        'trailer'   => '1Fg4oaktmtI',
        'harga'     => 40000,
    ],
    [
        'id'        => 8,
        'judul'     => 'Dune',
        'genre'     => 'Sci-Fi',
        'tahun'     => 2021,
        'rating'    => 4.6,
        'durasi'    => '155 menit',
        'sutradara' => 'Denis Villeneuve',
        'sinopsis'  => 'Seorang pangeran muda melawan di planet rempah-rempah.',
        'poster'    => 'https://image.tmdb.org/t/p/w500/d5NXSklpcvksdi7We8R7ob5BLqS.jpg',
        'trailer'   => 'n9xhJrPXop4',
        'harga'     => 55000,
    ],
];

// Filter by genre (opsional, ?genre=Aksi)
if (!empty($_GET['genre'])) {
    $genre = htmlspecialchars($_GET['genre']);
    $films = array_filter($films, fn($f) => $f['genre'] === $genre);
    $films = array_values($films);
}

// Filter by id (opsional, ?id=1)
if (!empty($_GET['id'])) {
    $id    = intval($_GET['id']);
    $films = array_filter($films, fn($f) => $f['id'] === $id);
    $films = array_values($films);
    if (count($films) === 1) {
        echo json_encode(['success' => true, 'data' => $films[0]]);
        exit;
    }
}

echo json_encode([
    'success' => true,
    'data'    => $films,
    'total'   => count($films)
]);
