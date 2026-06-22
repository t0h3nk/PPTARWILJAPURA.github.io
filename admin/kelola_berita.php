<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}
include '../config/database.php';

$pesan_error = "";

// Proses Tambah Berita
if (isset($_POST['tambah_berita'])) {
    $judul = $_POST['judul'];
    $isi   = $_POST['isi'];
    
    // Logika Upload Foto Berita
    $nama_file = $_FILES['foto_berita']['name'];
    $ukuran_file = $_FILES['foto_berita']['size'];
    $tmp_file = $_FILES['foto_berita']['tmp_name'];
    
    if ($nama_file != "") {
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'webp'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        
        $nama_file_baru = 'news-' . time() . '.' . $ekstensi;
        $target_dir = "../public/uploads/" . $nama_file_baru;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran_file < 2000000) { // Maksimal 2MB
                if (move_uploaded_file($tmp_file, $target_dir)) {
                    $query = "INSERT INTO berita (judul, isi, foto) VALUES ('$judul', '$isi', '$nama_file_baru')";
                    mysqli_query($conn, $query);
                    header("Location: kelola_berita.php");
                    exit;
                } else {
                    $pesan_error = "Gagal mengunggah gambar ke server.";
                }
            } else {
                $pesan_error = "Ukuran gambar terlalu besar, maksimal 2MB.";
            }
        } else {
            $pesan_error = "Format gambar tidak valid. Hanya diperbolehkan JPG, JPEG, PNG, dan WEBP.";
        }
    } else {
        // Jika berita dibuat tanpa foto
        $query = "INSERT INTO berita (judul, isi) VALUES ('$judul', '$isi')";
        mysqli_query($conn, $query);
        header("Location: kelola_berita.php");
        exit;
    }
}

// Proses Hapus Berita beserta Fotonya
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // Ambil nama file foto terlebih dahulu untuk dihapus dari folder
    $cari_foto = mysqli_query($conn, "SELECT foto FROM berita WHERE id = '$id_hapus'");
    $data_foto = mysqli_fetch_assoc($cari_foto);
    
    if ($data_foto['foto'] != NULL && file_exists("../public/uploads/" . $data_foto['foto'])) {
        unlink("../public/uploads/" . $data_foto['foto']); // Menghapus file fisik gambar
    }
    
    mysqli_query($conn, "DELETE FROM berita WHERE id = '$id_hapus'");
    header("Location: kelola_berita.php");
    exit;
}

// Ambil semua berita
$result_berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY waktu_post DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background-color: #f4f6f9; }
        .sidebar { width: 250px; height: 100vh; background-color: #1b5e20; color: white; padding: 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; }
        .sidebar a { display: block; color: #e8f5e9; text-decoration: none; padding: 12px 15px; margin-bottom: 10px; border-radius: 4px; }
        .sidebar a:hover, .active { background-color: #2e7d32; color: white; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); }
        .form-box, .table-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 15px; background: #1b5e20; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f1f8e9; color: #1b5e20; }
        .btn-hapus { color: #c62828; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Ponpes</h2>
        <a href="dashboard.php">Daftar Pendaftar</a>
        <a href="kelola_santri.php">Kelola Data Santri</a>
        <a href="kelola_berita.php" class="active">Kelola Berita</a>
        <a href="../public/index.php" target="_blank">Lihat Website</a>
        <a href="logout.php" style="margin-top: 50px; color: #ff8a80;">Keluar (Logout)</a>
    </div>

    <div class="main-content">
        <h1>Manajemen Berita & Kegiatan Pesantren</h1>
        <p style="margin-bottom: 20px;">Tulis artikel berita dan sertakan foto dokumentasi kegiatan pesantren.</p>

        <?php if($pesan_error != ""): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?= $pesan_error; ?>
            </div>
        <?php endif; ?>

        <div class="form-box">
            <h3>Tambah Berita Baru</h3>
            <form action="" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                <div class="form-group">
                    <label>Judul Berita</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group">
                    <label>Isi Artikel Berita</label>
                    <textarea name="isi" rows="6" required></textarea>
                </div>
                <div class="form-group">
                    <label>Foto Dokumentasi (Maks 2MB)</label>
                    <input type="file" name="foto_berita" accept="image/*">
                </div>
                <button type="submit" name="tambah_berita" class="btn">Publish Berita</button>
            </form>
        </div>

        <div class="table-box">
            <h3>Daftar Berita Terpublis</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Foto</th>
                        <th>Judul Berita</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_berita)): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($row['waktu_post'])); ?></td>
                        <td>
                            <?php if($row['foto']): ?>
                                <img src="../public/uploads/<?= $row['foto']; ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <span style="color:#999; font-size:0.85rem;">Tanpa Foto</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($row['judul']); ?></strong></td>
                        <td>
                            <a href="edit_berita.php?id=<?= $row['id']; ?>" style="color: #1976d2; margin-right: 10px; text-decoration: none; font-weight: bold;">Edit</a>
                            <a href="kelola_berita.php?hapus=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus berita ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>