<?php
session_start();
session_destroy(); // Menghapus semua data sesi
header("Location: index.php"); // Mengarahkan kembali ke halaman login
exit;
?>