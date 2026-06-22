<?php 
include '../config/database.php'; 
include '../components/header.php'; 

$data_ditemukan = null;
$pesan_error = "";

// Jika form pencarian dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik_cari = $_POST['nik'];

    $query = "SELECT * FROM santri WHERE nik = '$nik_cari'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data_ditemukan = mysqli_fetch_assoc($result);
    } else {
        $pesan_error = "Maaf, data dengan NIK <strong>$nik_cari</strong> tidak ditemukan. Pastikan Anda sudah mendaftar dan NIK yang dimasukkan benar.";
    }
}
?>

    <section class="page-header">
        <h1>Cek Status Pendaftaran</h1>
    </section>

    <section class="content-section">
        <div class="container">
            <p class="text-center" style="margin-bottom: 30px;">Masukkan NIK yang Anda gunakan saat mendaftar untuk melihat hasil seleksi.</p>
            
            <form action="" method="POST" class="form-pendaftaran" style="max-width: 500px; margin: 0 auto; margin-bottom: 40px;">
                <div class="form-group">
                    <label for="nik">Masukkan NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="Contoh: 3201234567890001" required>
                </div>
                <button type="submit" class="btn-submit" style="background-color: #f57f17;">Periksa Status</button>
            </form>

            <?php if ($pesan_error != ""): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; text-align: center;">
                    <?= $pesan_error; ?>
                </div>
            <?php endif; ?>

            <?php if ($data_ditemukan != null): ?>
                <div style="background-color: white; border: 1px solid #e0e0e0; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h3 style="color: #1b5e20; margin-bottom: 20px; border-bottom: 2px solid #e0e0e0; padding-bottom: 10px;">Hasil Pencarian</h3>
                    <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($data_ditemukan['nama']); ?></p>
                    <p><strong>Asal Sekolah:</strong> <?= htmlspecialchars($data_ditemukan['sekolah']); ?></p>
                    <p><strong>Waktu Daftar:</strong> <?= date('d-m-Y', strtotime($data_ditemukan['waktu_daftar'])); ?></p>
                    
                    <div style="margin-top: 20px; padding: 15px; border-radius: 5px; text-align: center; font-size: 1.2rem; font-weight: bold;
                        <?php 
                            if ($data_ditemukan['status_pendaftaran'] == 'Diterima') echo 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;';
                            elseif ($data_ditemukan['status_pendaftaran'] == 'Ditolak') echo 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;';
                            else echo 'background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;';
                        ?>">
                        Status Saat Ini: <?= strtoupper(htmlspecialchars($data_ditemukan['status_pendaftaran'])); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>

<?php include '../components/footer.php'; ?>