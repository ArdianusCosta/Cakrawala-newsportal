<?php
// Konfigurasi koneksi ke database
$host = "localhost"; // Host MySQL
$username = "u828472685_cakrawala"; // Username MySQL
$password = "Cakrawala123!"; // Password MySQL
$database = "u828472685_cakrawala"; // Nama Database
// Buat koneksi ke database
$con = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$con) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>