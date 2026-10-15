<?php
/**
 * api/reservasi.php
 * REST API untuk reservasi tiket — terhubung ke MySQL.
 *
 * Endpoint:
 *   GET  api/reservasi.php             → semua reservasi
 *   GET  api/reservasi.php?kode=TIK-XX → cek by kode tiket
 *   POST api/reservasi.php             → buat reservasi baru
 */

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

// ===== GET =====
if ($method === 'GET') {
    $db = getDB();

    // Cari by kode tiket
    if (!empty($_GET['kode'])) {
        $kode = htmlspecialchars($_GET['kode'], ENT_QUOTES, 'UTF-8');
        $stmt = $db->prepare("
            SELECT r.*, f.judul AS film_judul, f.poster AS film_poster
            FROM reservasi r
            JOIN films f ON r.film_id = f.id
            WHERE r.kode_tiket = :kode
        ");
        $stmt->execute([':kode' => $kode]);
        $data = $stmt->fetch();

        if (!$data) {
            jsonResponse(['success' => false, 'message' => 'Kode tiket tidak ditemukan.'], 404);
        }

        jsonResponse(['success' => true, 'data' => $data]);
    }

    // Ambil semua reservasi (dengan info film)
    $stmt = $db->query("
        SELECT r.*, f.judul AS film_judul
        FROM reservasi r
        JOIN films f ON r.film_id = f.id
        ORDER BY r.created_at DESC
    ");
    $data = $stmt->fetchAll();

    jsonResponse(['success' => true, 'data' => $data, 'total' => count($data)]);
}

// ===== POST: Buat reservasi baru =====
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi field wajib
    $wajib = ['film_id', 'tanggal', 'jadwal', 'kursi', 'nama', 'email', 'telepon'];
    foreach ($wajib as $field) {
        if (empty($input[$field])) {
            jsonResponse(['success' => false, 'message' => "Field '$field' wajib diisi."], 400);
        }
    }

    // Validasi email
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['success' => false, 'message' => 'Format email tidak valid.'], 400);
    }

    // Validasi kursi (array, max 6)
    $kursiArr = is_array($input['kursi']) ? $input['kursi'] : explode(',', $input['kursi']);
    $kursiArr = array_map('trim', $kursiArr);
    if (count($kursiArr) < 1 || count($kursiArr) > 6) {
        jsonResponse(['success' => false, 'message' => 'Pilih 1 sampai 6 kursi.'], 400);
    }

    // Cek film ada
    $db   = getDB();
    $chek = $db->prepare("SELECT id, judul, harga FROM films WHERE id = :id");
    $chek->execute([':id' => intval($input['film_id'])]);
    $film = $chek->fetch();
    if (!$film) {
        jsonResponse(['success' => false, 'message' => 'Film tidak ditemukan.'], 404);
    }

    // Cek kursi belum dipesan di jadwal yang sama
    $kursiStr = implode(',', $kursiArr);
    $stmtCek  = $db->prepare("
        SELECT kursi FROM reservasi
        WHERE film_id = :film_id
          AND tanggal  = :tanggal
          AND jadwal   = :jadwal
          AND status  != 'cancelled'
    ");
    $stmtCek->execute([
        ':film_id' => intval($input['film_id']),
        ':tanggal' => $input['tanggal'],
        ':jadwal'  => $input['jadwal'],
    ]);

    $semuaKursiTerisi = [];
    foreach ($stmtCek->fetchAll() as $row) {
        $semuaKursiTerisi = array_merge(
            $semuaKursiTerisi,
            array_map('trim', explode(',', $row['kursi']))
        );
    }

    $bentrok = array_intersect($kursiArr, $semuaKursiTerisi);
    if (!empty($bentrok)) {
        jsonResponse([
            'success' => false,
            'message' => 'Kursi ' . implode(', ', $bentrok) . ' sudah dipesan pada jadwal ini.'
        ], 409);
    }

    // Hitung total harga
    $total = $film['harga'] * count($kursiArr);

    // Generate kode tiket unik
    $kode = 'TIK-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

    // Simpan ke database
    $stmt = $db->prepare("
        INSERT INTO reservasi
            (kode_tiket, film_id, tanggal, jadwal, kursi, nama, email, telepon, total_harga, status)
        VALUES
            (:kode, :film_id, :tanggal, :jadwal, :kursi, :nama, :email, :telepon, :total, 'pending')
    ");
    $stmt->execute([
        ':kode'    => $kode,
        ':film_id' => intval($input['film_id']),
        ':tanggal' => $input['tanggal'],
        ':jadwal'  => htmlspecialchars($input['jadwal'],  ENT_QUOTES, 'UTF-8'),
        ':kursi'   => $kursiStr,
        ':nama'    => htmlspecialchars($input['nama'],    ENT_QUOTES, 'UTF-8'),
        ':email'   => filter_var($input['email'],         FILTER_SANITIZE_EMAIL),
        ':telepon' => htmlspecialchars($input['telepon'], ENT_QUOTES, 'UTF-8'),
        ':total'   => $total,
    ]);

    jsonResponse([
        'success'    => true,
        'message'    => 'Reservasi berhasil! Simpan kode tiketmu.',
        'kode_tiket' => $kode,
        'total'      => $total,
        'film'       => $film['judul'],
    ], 201);
}

jsonResponse(['success' => false, 'message' => 'Metode tidak didukung.'], 405);
