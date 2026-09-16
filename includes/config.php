<?php
// Konfigurasi koneksi ke database
$host = "localhost"; // Host MySQL
$username = "u828472685_cakrawala"; // Username MySQL
$password = "Cakrawala123!"; // Password MySQL
$database = "u828472685_cakrawala"; // Nama Database

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
