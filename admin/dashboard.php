<?php
session_start();

// KEAMANAN: Cek apakah admin sudah login. Jika belum, tendang ke halaman login!
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}

include '../config/database.php';
$query = "SELECT * FROM santri ORDER BY waktu_daftar DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Ponpes Darussalam</title>
    <style>
        /* Gaya dasar sama dengan sebelumnya */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { display: flex; background-color: #f4f6f9; }
        .sidebar { width: 250px; height: 100vh; background-color: #1b5e20; color: white; padding: 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; }
        .sidebar a { display: block; color: #e8f5e9; text-decoration: none; padding: 12px 15px; margin-bottom: 10px; border-radius: 4px; font-weight: 500; }
        .sidebar a.active, .sidebar a:hover { background-color: #2e7d32; color: white; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); }
        .header { margin-bottom: 30px; }
        .table-container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background-color: #f1f8e9; color: #1b5e20; font-weight: bold; }
        
        /* Tambahan Gaya untuk Status dan Tombol Aksi */
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; color: white;}
        .badge-waiting { background-color: #f57f17; } /* Oranye */
        .badge-accepted { background-color: #2e7d32; } /* Hijau */
        .badge-rejected { background-color: #c62828; } /* Merah */
        
        .btn-aksi { padding: 6px 12px; color: white; text-decoration: none; border-radius: 4px; font-size: 0.85rem; margin-right: 5px; }
        .btn-terima { background-color: #4caf50; }
        .btn-tolak { background-color: #f44336; }
        .btn-aksi:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Ponpes</h2>
        <a href="dashboard.php" class="active">Daftar Pendaftar</a>
        <a href="kelola_santri.php">Kelola Data Santri</a>
        <a href="kelola_berita.php">Kelola Berita</a>
        <a href="../public/index.php" target="_blank">Lihat Website</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff8a80;">Keluar (Logout)</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Data Pendaftaran Santri Baru</h1>
            <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Asal Sekolah</th>
                        <th>Status</th>
                        <th>Aksi Pengurus</th> </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) { 
                            // Menentukan warna badge status
                            $status_class = "badge-waiting";
                            if ($row['status_pendaftaran'] == 'Diterima') $status_class = "badge-accepted";
                            if ($row['status_pendaftaran'] == 'Ditolak') $status_class = "badge-rejected";
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                            <td><?= htmlspecialchars($row['sekolah']); ?></td>
                            <td>
                                <span class="badge <?= $status_class; ?>"><?= htmlspecialchars($row['status_pendaftaran']); ?></span>
                            </td>
                            <td>
                                <a href="detail_santri.php?id=<?= $row['id']; ?>" class="btn-aksi" style="background-color: #2196F3;">Detail</a>
                                <a href="update_status.php?id=<?= $row['id']; ?>&status=Diterima" class="btn-aksi btn-terima" onclick="return confirm('Terima pendaftar ini?');">Terima</a>
                                <a href="update_status.php?id=<?= $row['id']; ?>&status=Ditolak" class="btn-aksi btn-tolak" onclick="return confirm('Tolak pendaftar ini?');">Tolak</a>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else { echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data.</td></tr>"; } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>