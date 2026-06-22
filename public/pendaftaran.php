<?php 
include '../config/database.php'; 
include '../components/header.php'; 

$pesan_sukses = "";
$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama    = $_POST['nama'];
    $nik     = $_POST['nik'];
    $alamat  = $_POST['alamat'];
    $sekolah = $_POST['sekolah'];
    $nohp    = $_POST['nohp'];

    // LOGIKA UPLOAD FOTO
    $nama_file = $_FILES['foto']['name'];
    $ukuran_file = $_FILES['foto']['size'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    
    // Ekstensi yang diperbolehkan
    $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));

    // Membuat nama file unik agar tidak bentrok
    $nama_file_baru = time() . '-' . $nama_file;
    $target_dir = "uploads/" . $nama_file_baru;

    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        if ($ukuran_file < 2000000) { // Maksimal 2MB
            if (move_uploaded_file($tmp_file, $target_dir)) {
                // Jika upload file sukses, simpan data ke database
                $query = "INSERT INTO santri (nama, nik, alamat, sekolah, nohp, foto) 
                          VALUES ('$nama', '$nik', '$alamat', '$sekolah', '$nohp', '$nama_file_baru')";

                if (mysqli_query($conn, $query)) {
                    $pesan_sukses = "Alhamdulillah, pendaftaran atas nama <strong>$nama</strong> beserta berkas foto berhasil dikirim!";
                } else {
                    $pesan_error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
                }
            } else {
                $pesan_error = "Gagal mengunggah foto ke server.";
            }
        } else {
            $pesan_error = "Ukuran foto terlalu besar, maksimal 2MB.";
        }
    } else {
        $pesan_error = "Ekstensi file tidak diperbolehkan! Hanya JPG, JPEG, dan PNG.";
    }
}
?>

    <section class="page-header">
        <h1>Pendaftaran Santri Baru</h1>
    </section>

    <section class="content-section">
        <div class="container">
            <p class="text-center" style="margin-bottom: 30px;">Silakan lengkapi formulir di bawah ini dengan data yang benar.</p>
            
            <?php if($pesan_sukses != ""): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?= $pesan_sukses; ?>
                </div>
            <?php endif; ?>

            <?php if($pesan_error != ""): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?= $pesan_error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="form-pendaftaran">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" id="nik" name="nik" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="sekolah">Asal Sekolah</label>
                    <input type="text" id="sekolah" name="sekolah" required>
                </div>
                <div class="form-group">
                    <label for="nohp">Nomor WhatsApp / HP Orang Tua</label>
                    <input type="tel" id="nohp" name="nohp" required>
                </div>
                <div class="form-group">
                    <label for="foto">Upload Pas Foto Santri (Format: JPG/PNG, Maks 2MB)</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>
                <button type="submit" class="btn-submit">Kirim Formulir Pendaftaran</button>
            </form>
        </div>
    </section>

<?php include '../components/footer.php'; ?>