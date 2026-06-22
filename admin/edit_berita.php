<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}
include '../config/database.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update_berita'])) {
    $judul = $_POST['judul'];
    $isi   = $_POST['isi'];
    
    // Logika Ganti Foto
    if ($_FILES['foto_berita']['name'] != "") {
        $nama_file = $_FILES['foto_berita']['name'];
        $tmp_file = $_FILES['foto_berita']['tmp_name'];
        $nama_file_baru = 'news-' . time() . '.' . pathinfo($nama_file, PATHINFO_EXTENSION);
        
        // Hapus foto lama
        if ($data['foto'] != NULL && file_exists("../public/uploads/" . $data['foto'])) {
            unlink("../public/uploads/" . $data['foto']);
        }
        
        move_uploaded_file($tmp_file, "../public/uploads/" . $nama_file_baru);
        mysqli_query($conn, "UPDATE berita SET judul='$judul', isi='$isi', foto='$nama_file_baru' WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE berita SET judul='$judul', isi='$isi' WHERE id='$id'");
    }
    header("Location: kelola_berita.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; padding: 40px; }
        .box { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 15px; background: #1b5e20; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Edit Berita</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label>Isi Berita</label>
                <textarea name="isi" rows="6" required><?= htmlspecialchars($data['isi']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Ganti Foto (Kosongkan jika tidak ingin ganti)</label>
                <?php if($data['foto']): ?>
                    <p><img src="../public/uploads/<?= $data['foto']; ?>" width="100"></p>
                <?php endif; ?>
                <input type="file" name="foto_berita" accept="image/*">
            </div>
            <button type="submit" name="update_berita" class="btn">Simpan Perubahan</button>
            <a href="kelola_berita.php">Batal</a>
        </form>
    </div>
</body>
</html>