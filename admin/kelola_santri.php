<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}
include '../config/database.php';

// Proses Hapus Santri
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Hapus file foto jika ada
    $query_foto = mysqli_query($conn, "SELECT foto FROM santri WHERE id = '$id'");
    $data_foto = mysqli_fetch_assoc($query_foto);
    if ($data_foto['foto'] && file_exists("../public/uploads/" . $data_foto['foto'])) {
        unlink("../public/uploads/" . $data_foto['foto']);
    }
    mysqli_query($conn, "DELETE FROM santri WHERE id = '$id'");
    header("Location: kelola_santri.php");
}

$result = mysqli_query($conn, "SELECT * FROM santri ORDER BY waktu_daftar DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Santri - Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; }
        .sidebar { width: 250px; height: 100vh; background: #1b5e20; color: white; padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #e8f5e9; padding: 12px 15px; text-decoration: none; border-radius: 4px; margin-bottom: 10px; }
        .sidebar a:hover, .active { background: #2e7d32; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); }
        .table-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f1f8e9; color: #1b5e20; }
        .btn-del { color: #c62828; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Ponpes</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="kelola_santri.php" class="active">Kelola Data Santri</a>
        <a href="kelola_berita.php">Kelola Berita</a>
        <a href="../public/index.php" target="_blank">Lihat Website</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff8a80;">Keluar</a>
    </div>

    <div class="main-content">
        <h1>Data Seluruh Santri</h1>
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Sekolah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['nik']); ?></td>
                        <td><?= htmlspecialchars($row['sekolah']); ?></td>
                        <td><?= htmlspecialchars($row['status_pendaftaran']); ?></td>
                        <td>
                            <a href="detail_santri.php?id=<?= $row['id']; ?>" style="color:#2196f3; margin-right: 10px;">Detail</a>
                            <a href="kelola_santri.php?hapus=<?= $row['id']; ?>" class="btn-del" onclick="return confirm('Hapus data santri ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>