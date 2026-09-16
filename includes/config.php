<?php
// Konfigurasi koneksi ke database (Otomatis Deteksi Localhost vs Hostinger Production)
if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0)) {
    // Environment Localhost
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "newsportal";
} else {
    // Environment Production (Hostinger)
    $host = "localhost";
    $username = "u828472685_cakrawala";
    $password = "Cakrawala123!";
    $database = "u828472685_cakrawala";
}

// Set timezone aplikasi ke Asia/Jakarta agar waktu artikel sesuai dengan lokasi user
date_default_timezone_set('Asia/Jakarta');
ini_set('date.timezone', 'Asia/Jakarta');

// Buat koneksi ke database
$con = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Pastikan sesi MySQL juga menggunakan timezone Jakarta agar NOW()/CURRENT_TIMESTAMP konsisten
mysqli_query($con, "SET time_zone = '+07:00'");
mysqli_set_charset($con, 'utf8mb4');

?>
