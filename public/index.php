<?php 
// Panggil koneksi untuk mengambil data berita
include '../config/database.php';
include '../components/header.php'; 

// Mengambil 3 berita terbaru untuk dipajang di beranda
$query_berita = "SELECT * FROM berita ORDER BY waktu_post DESC LIMIT 3";
$result_berita = mysqli_query($conn, $query_berita);
?>

    <section class="hero">
        <div class="hero-content">
            <h1>Selamat Datang di Pondok Pesantren Darussalam</h1>
            <p>Mencetak generasi Islami yang berakhlak mulia, mandiri, dan berprestasi.</p>
            <a href="pendaftaran.php" class="btn-daftar">Daftar Sekarang</a>
        </div>
    </section>

    <section class="info-section">
        <h2>Program Pendidikan</h2>
        <div class="cards">
            <div class="card">
                <h3>Tahfidz Al-Qur'an</h3>
                <p>Program hafalan Al-Qur'an dengan target mutqin bersanad.</p>
            </div>
            <div class="card">
                <h3>Kitab Kuning</h3>
                <p>Kajian kitab salaf sebagai pondasi keilmuan fiqih dan akidah.</p>
            </div>
            <div class="card">
                <h3>Pendidikan Formal</h3>
                <p>Menyelenggarakan pendidikan formal setingkat SMP dan SMA.</p>
            </div>
        </div>
    </section>

    <section class="info-section" style="background-color: #f9f9f9; border-top: 1px solid #e0e0e0;">
        <h2>Berita & Kegiatan Pesantren</h2>
        <div class="cards" style="margin-top: 20px;">
            <?php 
            if (mysqli_num_rows($result_berita) > 0) {
                while($berita = mysqli_fetch_assoc($result_berita)) {
            ?>
                <div class="card" style="background: white; text-align: left; padding: 0; overflow: hidden; border: 1px solid #e0e0e0; display: flex; flex-direction: column;">
                    
                    <?php if($berita['foto']): ?>
                        <img src="uploads/<?= $berita['foto']; ?>" style="width: 100%; height: 180px; object-fit: cover;" alt="Foto Kegiatan">
                    <?php else: ?>
                        <div style="width: 100%; height: 180px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: #aaa;">Tidak ada gambar</div>
                    <?php endif; ?>

                    <div style="padding: 20px;">
                        <span style="font-size: 0.85rem; color: #777;"><?= date('d F Y', strtotime($berita['waktu_post'])); ?></span>
                        <h3 style="margin: 10px 0 15px 0; color: #1b5e20; line-height: 1.3;"><?= htmlspecialchars($berita['judul']); ?></h3>
                        <p style="font-size: 0.95rem; color: #555; line-height: 1.5;"><?= nl2br(htmlspecialchars(substr($berita['isi'], 0, 120))); ?>...</p>
                    </div>
                </div>
            <?php 
                }
            } else { 
            ?>
                <p class="text-center" style="width: 100%; color: #777;">Belum ada berita kegiatan terbaru.</p>
            <?php } ?>
        </div>
    </section>

<?php include '../components/footer.php'; ?>