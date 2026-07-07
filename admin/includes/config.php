<?php
// Konfigurasi koneksi ke database
//
// Credentials now live in includes/.env, which must NOT be committed to
// version control (see .gitignore) and should ideally sit outside the
// public web root if your hosting setup allows it.

require_once __DIR__ . '/env-loader.php';
loadEnv(__DIR__ . '/.env');

$host     = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    error_log("DB connection failed: " . mysqli_connect_error());

    // Set APP_DEBUG=true in .env temporarily while troubleshooting to see
    // the real error in-browser. Set it back to false (or remove it) once
    // things are working again — never leave debug mode on in production.
    if (getenv('APP_DEBUG') === 'true') {
        die("DB connection failed: " . mysqli_connect_error());
    }

    die("A server error occurred. Please try again later.");
}

// Avoid encoding mismatches (important once htmlentities() is involved).
mysqli_set_charset($con, "utf8mb4");