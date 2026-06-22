<?php
$host = "localhost";
$user = "root";       // Username default XAMPP/Laragon
$pass = "";           // Password default biasanya kosong
$db   = "db_pesantren";

// Melakukan koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>