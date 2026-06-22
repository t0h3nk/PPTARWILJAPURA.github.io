<?php
session_start();
include '../config/database.php';

// Pastikan yang mengakses file ini hanya admin yang sudah login
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}

// Menangkap parameter ID dan Status dari URL
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status_baru = $_GET['status'];

    // Update status di database berdasarkan ID santri
    $query = "UPDATE santri SET status_pendaftaran = '$status_baru' WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, kembalikan ke dashboard
        header("Location: dashboard.php");
    } else {
        echo "Gagal mengupdate status: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
}
?>