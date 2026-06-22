<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM santri WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar - <?= htmlspecialchars($data['nama']); ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333; padding: 20px; }
        .cetak-container { background-color: white; max-width: 800px; margin: 0 auto; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .kop-surat { text-align: center; border-bottom: 3px solid #1b5e20; padding-bottom: 20px; margin-bottom: 30px; }
        .kop-surat h2 { color: #1b5e20; margin-bottom: 5px; }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table th, .detail-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .detail-table th { width: 30%; color: #555; }
        .no-print { margin-top: 30px; text-align: center; }
        .btn { padding: 10px 20px; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; border: none; cursor: pointer; margin: 0 5px; }
        .btn-kembali { background-color: #757575; }
        .btn-cetak { background-color: #1b5e20; }
        
        /* CSS Khusus saat di-print */
        @media print {
            body { background-color: white; padding: 0; }
            .cetak-container { box-shadow: none; border: none; padding: 0; }
            .no-print { display: none; } /* Sembunyikan tombol saat dicetak */
        }
    </style>
</head>
<body>

    <div class="cetak-container">
        <div class="kop-surat">
            <h2>PONDOK PESANTREN DARUSSALAM</h2>
            <p>Formulir Pendaftaran Santri Baru</p>
        </div>

        <table class="detail-table">
            <tr>
                <th>Nomor Pendaftaran</th>
                <td>: REG-<?= date('Ym', strtotime($data['waktu_daftar'])) . str_pad($data['id'], 4, '0', STR_PAD_LEFT); ?></td>
            </tr>
            <tr>
                <th>Tanggal Daftar</th>
                <td>: <?= date('d F Y, H:i', strtotime($data['waktu_daftar'])); ?></td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>: <strong><?= htmlspecialchars($data['nama']); ?></strong></td>
            </tr>
            <tr>
                <th>NIK</th>
                <td>: <?= htmlspecialchars($data['nik']); ?></td>
            </tr>
            <tr>
                <th>Asal Sekolah</th>
                <td>: <?= htmlspecialchars($data['sekolah']); ?></td>
            </tr>
            <tr>
                <th>Alamat Lengkap</th>
                <td>: <?= nl2br(htmlspecialchars($data['alamat'])); ?></td>
            </tr>
            <tr>
                <th>Nomor WhatsApp / HP</th>
                <td>: <?= htmlspecialchars($data['nohp']); ?></td>
            </tr>
            <tr>
                <th>Status Seleksi</th>
                <td>: <strong><?= strtoupper(htmlspecialchars($data['status_pendaftaran'])); ?></strong></td>
            </tr>
        </table>
            <tr>
                <th>Pas Foto</th>
                <td>: 
                    <?php if($data['foto']): ?>
                        <br><img src="../public/uploads/<?= $data['foto']; ?>" alt="Foto Santri" style="max-width: 150px; border-radius: 4px; border: 1px solid #ccc; margin-top: 5px;">
                    <?php else: ?>
                        Tidak ada foto.
                    <?php endif; ?>
                </td>
            </tr>
        <div class="no-print">
            <a href="dashboard.php" class="btn btn-kembali">Kembali</a>
            <button onclick="window.print()" class="btn btn-cetak">🖨️ Cetak Formulir</button>
        </div>
    </div>

</body>
</html>