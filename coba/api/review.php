<?php
/**
 * api/review.php
 * REST API untuk komentar / review film — terhubung ke MySQL.
 *
 * Endpoint:
 *   GET  api/review.php           → semua review
 *   GET  api/review.php?film_id=1 → review untuk film tertentu
 *   POST api/review.php           → tambah review baru
 *   DELETE api/review.php?id=5    → hapus review (opsional)
 */

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

// ===== GET =====
if ($method === 'GET') {
    $db    = getDB();
    $filmId = isset($_GET['film_id']) ? intval($_GET['film_id']) : null;

    if ($filmId) {
        // Review untuk 1 film + info nama film (JOIN)
        $stmt = $db->prepare("
            SELECT r.*, f.judul AS film_judul, f.poster AS film_poster
            FROM reviews r
            JOIN films f ON r.film_id = f.id
            WHERE r.film_id = :film_id AND r.disetujui = 1
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([':film_id' => $filmId]);
    } else {
        // Semua review
        $stmt = $db->prepare("
            SELECT r.*, f.judul AS film_judul, f.poster AS film_poster
            FROM reviews r
            JOIN films f ON r.film_id = f.id
            WHERE r.disetujui = 1
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
    }

    $reviews = $stmt->fetchAll();

    // Format tanggal ke bahasa Indonesia
    foreach ($reviews as &$rev) {
        $rev['tanggal'] = date('d F Y', strtotime($rev['created_at']));
    }

    jsonResponse(['success' => true, 'data' => $reviews, 'total' => count($reviews)]);
}

// ===== POST: Tambah review baru =====
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi input wajib
    $wajib = ['nama', 'film_id', 'rating', 'teks'];
    foreach ($wajib as $field) {
        if (empty($input[$field])) {
            jsonResponse(['success' => false, 'message' => "Field '$field' wajib diisi."], 400);
        }
    }

    // Validasi rating 1–5
    $rating = intval($input['rating']);
    if ($rating < 1 || $rating > 5) {
        jsonResponse(['success' => false, 'message' => 'Rating harus antara 1 sampai 5.'], 400);
    }

    // Validasi film_id ada di database
    $db   = getDB();
    $chek = $db->prepare("SELECT id, judul, poster FROM films WHERE id = :id");
    $chek->execute([':id' => intval($input['film_id'])]);
    $film = $chek->fetch();

    if (!$film) {
        jsonResponse(['success' => false, 'message' => 'Film tidak ditemukan.'], 404);
    }

    // Validasi email jika diisi
    $email = '';
    if (!empty($input['email'])) {
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            jsonResponse(['success' => false, 'message' => 'Format email tidak valid.'], 400);
        }
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    }

    // Simpan review ke database (prepared statement — aman dari SQL Injection)
    $stmt = $db->prepare("
        INSERT INTO reviews (film_id, nama, email, rating, teks)
        VALUES (:film_id, :nama, :email, :rating, :teks)
    ");
    $stmt->execute([
        ':film_id' => intval($input['film_id']),
        ':nama'    => htmlspecialchars(trim($input['nama']),  ENT_QUOTES, 'UTF-8'),
        ':email'   => $email,
        ':rating'  => $rating,
        ':teks'    => htmlspecialchars(trim($input['teks']),  ENT_QUOTES, 'UTF-8'),
    ]);

    $newId = $db->lastInsertId();

    // Update rata-rata rating film
    updateRatingFilm($db, intval($input['film_id']));

    jsonResponse([
        'success'     => true,
        'message'     => 'Review berhasil disimpan!',
        'id'          => $newId,
        'film_judul'  => $film['judul'],
        'film_poster' => $film['poster'],
        'tanggal'     => date('d F Y'),
    ], 201);
}

// ===== DELETE: Hapus review =====
if ($method === 'DELETE') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id < 1) {
        jsonResponse(['success' => false, 'message' => 'ID review tidak valid.'], 400);
    }

    $db   = getDB();
    $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        jsonResponse(['success' => false, 'message' => 'Review tidak ditemukan.'], 404);
    }

    jsonResponse(['success' => true, 'message' => 'Review berhasil dihapus.']);
}

// ===== HELPER: Update rata-rata rating film =====
function updateRatingFilm(PDO $db, int $filmId): void {
    $stmt = $db->prepare("
        UPDATE films
        SET rating = (
            SELECT ROUND(AVG(rating), 1) FROM reviews
            WHERE film_id = :film_id AND disetujui = 1
        )
        WHERE id = :film_id
    ");
    $stmt->execute([':film_id' => $filmId]);
}

// Metode lain tidak didukung
jsonResponse(['success' => false, 'message' => 'Metode tidak didukung.'], 405);
