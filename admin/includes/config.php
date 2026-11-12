<?php
// Konfigurasi koneksi ke database
//
// Credentials now live in includes/.env, which must NOT be committed to
// version control (see .gitignore) and should ideally sit outside the
// public web root if your hosting setup allows it.

require_once __DIR__ . '/env-loader.php';

$envPath = __DIR__ . '/.env';
if (is_readable($envPath)) {
    loadEnv($envPath);
}

$host     = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

// Fallback otomatis (Localhost vs Production) jika .env tidak ada atau nilainya belum diset
if (!$host || !$database) {
    if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0)) {
        // Environment Localhost
        $host     = "localhost";
        $username = "root";
        $password = "";
        $database = "newsportal";
    } else {
        // Environment Production (Hostinger)
        $host     = "localhost";
        $username = "u828472685_cakrawala";
        $password = "Cakrawala123!";
        $database = "u828472685_cakrawala";
    }
}

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    error_log("DB connection failed: " . mysqli_connect_error());

    if (getenv('APP_DEBUG') === 'true' || (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0))) {
        die("DB connection failed: " . mysqli_connect_error());
    }

    die("A server error occurred. Please try again later.");
}

date_default_timezone_set('Asia/Jakarta');
mysqli_query($con, "SET time_zone = '+07:00'");
mysqli_set_charset($con, "utf8mb4");