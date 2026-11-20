<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$nama_lengkap = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Penyewa';

// Ambil ID Reservasi dari URL
$id_reservasi = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data reservasi, detail reservasi, dan lapangan sekaligus
$query = mysqli_query($koneksi, "
    SELECT r.*, d.durasi_jam, d.subtotal_harga, l.nama_lapangan, l.harga_per_jam 
    FROM reservasi r 
    JOIN detail_reservasi d ON r.id_reservasi = d.id_reservasi 
    JOIN lapangan l ON r.id_lapangan = l.id_lapangan 
    WHERE r.id_reservasi = '$id_reservasi' AND r.id_pengguna = '$id_pengguna'
");

$data = mysqli_fetch_assoc($query);

// Jika data reservasi tidak ditemukan
if (!$data) {
    header("Location: riwayat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Sukses - Kukusan Sport Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { 
            background: linear-gradient(rgba(30, 8, 64, 0.75), rgba(15, 3, 33, 0.85)), 
                        url('https://images.unsplash.com/photo-1518605368461-1ee7e1617511?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat; 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 20px; 
        }
        .card { 
            background: white; 
            padding: 40px 35px; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); 
            width: 100%; 
            max-width: 480px; 
            text-align: center; 
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Lingkaran Ikon Sukses */
        .success-icon-container {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-size: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        h2 { color: #111827; font-weight: 700; margin-bottom: 5px; font-size: 24px; }
        .subtitle { color: #6b7280; font-size: 13px; margin-bottom: 25px; }

        .bill-box { 
            background: #f9fafb; 
            border: 1px solid #e5e7eb;
            padding: 20px; 
            border-radius: 12px; 
            text-align: left; 
            margin-bottom: 20px; 
        }
        .bill-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            color: #374151;
        }
        .bill-row:last-child {
            margin-bottom: 0;
            padding-top: 12px;
            border-top: 1px dashed #d1d5db;
            font-weight: 700;
            color: #4a148c;
            font-size: 16px;
        }

        .instruction {
            background: #f3e8ff;
            color: #581c87;
            padding: 15px;
            border-radius: 10px;
            font-size: 13px;
            text-align: left;
            margin-bottom: 25px;
            border-left: 4px solid #6a1b9a;
            line-height: 1.5;
        }

        .btn-riwayat { 
            display: block;
            width: 100%;
            background-color: #6a1b9a; 
            color: white; 
            padding: 14px; 
            border-radius: 10px; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 15px;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(106, 27, 154, 0.2);
        }
        .btn-riwayat:hover { 
            background-color: #4a148c; 
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(106, 27, 154, 0.3);
        }
    </style>
</head>
<body>

    <div class="card">
        <!-- Ikon Centang Sukses -->
        <div class="success-icon-container">
            ✓
        </div>

        <h2>Reservasi Berhasil!</h2>
        <p class="subtitle">Data pemesanan lapangan Anda telah tercatat</p>

        <div class="bill-box">
            <div class="bill-row">
                <span>ID Pemesanan:</span>
                <strong>#<?= $data['id_reservasi']; ?></strong>
            </div>
            <div class="bill-row">
                <span>Lapangan:</span>
                <strong><?= htmlspecialchars($data['nama_lapangan']); ?></strong>
            </div>
            <div class="bill-row">
                <span>Tanggal Sewa:</span>
                <strong><?= date('d-m-Y', strtotime($data['tanggal_reservasi'])); ?></strong>
            </div>
            <div class="bill-row">
                <span>Durasi Main:</span>
                <strong><?= $data['durasi_jam']; ?> Jam</strong>
            </div>
            <div class="bill-row">
                <span>Total Biaya:</span>
                <span style="color: #059669;">Rp <?= number_format($data['subtotal_harga'], 0, ',', '.'); ?></span>
            </div>
        </div>

        <div class="instruction">
            💡 <strong>Info:</strong> Silakan lakukan pembayaran tunai sebesar total di atas kepada petugas di lokasi <strong>Kukusan Sport Center</strong> sebelum jadwal bermain dimulai.
        </div>

        <a href="riwayat.php" class="btn-riwayat">📂 Lihat Riwayat Pemesanan</a>
    </div>

</body>
</html>