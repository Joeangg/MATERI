<?php
/**
 * api/config.php
 * Konfigurasi koneksi database MySQL.
 * 
 * Ubah nilai di bawah sesuai pengaturan XAMPP-mu.
 * Default XAMPP: user=root, password=kosong, host=localhost
 */

define('DB_HOST',   'localhost');   // Host database
define('DB_USER',   'root');        // Username MySQL (default XAMPP: root)
define('DB_PASS',   '');            // Password MySQL (default XAMPP: kosong)
define('DB_NAME',   'cinereview');  // Nama database

/**
 * Fungsi untuk membuat koneksi PDO ke MySQL.
 * PDO lebih aman dari mysqli karena mendukung prepared statements.
 * 
 * @return PDO
 */
function getDB(): PDO {
    static $pdo = null; // Koneksi dibuat sekali (singleton)

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lempar exception jika error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Hasil sebagai array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Gunakan prepared statements asli
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Jangan tampilkan detail error ke user (keamanan)
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Koneksi database gagal. Pastikan MySQL menyala di XAMPP.'
            ]);
            exit;
        }
    }

    return $pdo;
}

/**
 * Helper: kirim response JSON
 */
function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Tangani preflight OPTIONS request (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(204);
    exit;
}
