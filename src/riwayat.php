<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Query mengambil data reservasi
$query = "SELECT r.*, d.durasi_jam, d.subtotal_harga, l.nama_lapangan 
          FROM reservasi r 
          JOIN detail_reservasi d ON r.id_reservasi = d.id_reservasi
          JOIN lapangan l ON r.id_lapangan = l.id_lapangan 
          WHERE r.id_pengguna = '$id_pengguna' 
          ORDER BY r.id_reservasi DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemesanan - Kukusan Sport Center</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f0fa; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 1000px; margin: auto; }
        h2 { color: #5b21b6; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border: 1px solid #e9d5ff; text-align: left; }
        th { background-color: #7c3aed; color: white; }
        .status-pending { background-color: #fef08a; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 13px; }
        .status-sukses { background-color: #bbf7d0; color: #166534; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 13px; }
        .status-batal { background-color: #fecaca; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 13px; }
        .btn-bayar { background-color: #15803d; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .btn-batal { background-color: #dc2626; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; margin-left: 5px; }
        .btn-bayar:hover { background-color: #166534; }
        .btn-batal:hover { background-color: #b91c1c; }
        .btn-kembali { display: inline-block; margin-top: 20px; color: #6b21a8; text-decoration: none; font-weight: bold; }
        .alert-success { background: #dcfce7; color: #15803d; padding: 10px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Riwayat Pemesanan Lapangan</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'booking_success'): ?>
        <div class="alert-success">✅ Booking berhasil disimpan! Silakan lakukan pembayaran jika sudah siap.</div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'cancelled'): ?>
        <div class="alert-success">❌ Reservasi berhasil dibatalkan.</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Lapangan</th>
                <th>Tanggal Sewa</th>
                <th>Durasi</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($result && mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_lapangan']); ?></td>
                    <td><?= $row['tanggal_reservasi']; ?></td>
                    <td><?= $row['durasi_jam']; ?> Jam</td>
                    <td>Rp <?= number_format($row['subtotal_harga'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending'): ?>
                            <span class="status-pending">Pending</span>
                        <?php elseif ($row['status'] == 'Sukses' || $row['status'] == 'Lunas'): ?>
                            <span class="status-sukses">Sukses</span>
                        <?php else: ?>
                            <span class="status-batal"><?= htmlspecialchars($row['status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'Pending'): ?>
                            <a href="bayar.php?id=<?= $row['id_reservasi']; ?>" class="btn-bayar">💳 Bayar</a>
                            <a href="batalkan.php?id=<?= $row['id_reservasi']; ?>" class="btn-batal" onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">❌ Batalkan</a>
                        <?php else: ?>
                            <span style="color: #6b7280; font-size: 13px;">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                endwhile;
            else: 
            ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada riwayat pemesanan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn-kembali-modern">Kembali ke Dashboard</a>
</div>
<style>
    .btn-kembali-modern { 
        display: inline-block; 
        background-color: #f3e8ff; 
        color: #6a1b9a; 
        padding: 10px 18px; 
        border-radius: 8px; 
        text-decoration: none; 
        font-size: 14px; 
        font-weight: 600; 
        border: 1px solid #e9d5ff; 
        transition: all 0.3s ease; 
        margin-bottom: 20px; 
    } 
    .btn-kembali-modern:hover { 
        background-color: #6a1b9a; 
        color: #ffffff; 
        border-color: #6a1b9a; 
    }
</style>

</body>
</html>