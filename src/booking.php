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

// Ambil ID Lapangan dari URL, default ke 21 (sesuai database)
$id_lapangan = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : 21;

// Ambil data lapangan
$query_lapangan = mysqli_query($koneksi, "SELECT * FROM lapangan WHERE id_lapangan = '$id_lapangan'");
$lapangan = mysqli_fetch_assoc($query_lapangan);

if (!$lapangan) {
    $query_fallback = mysqli_query($koneksi, "SELECT * FROM lapangan ORDER BY id_lapangan ASC LIMIT 1");
    $lapangan = mysqli_fetch_assoc($query_fallback);
    $id_lapangan = $lapangan['id_lapangan'];
}

$error = '';

// Proses Pemesanan (Dua Opsi Tombol)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_reservasi = mysqli_real_escape_string($koneksi, $_POST['tanggal_reservasi']);
    $durasi_jam = intval($_POST['durasi_jam']);
    
    // PERBAIKAN DI SINI: Menghitung total harga dengan benar (durasi dikali harga per jam)
    $subtotal_harga = $durasi_jam * $lapangan['harga_per_jam'];

    if (!empty($tanggal_reservasi) && $durasi_jam > 0) {
        // Cek tombol mana yang diklik
        if (isset($_POST['aksi_bayar'])) {
            $status = 'Sukses'; // Jika klik Bayar Sekarang, status langsung Sukses
            $redirect_url = "bayar.php";
        } else {
            $status = 'Pending'; // Jika klik Booking Saja, status tetap Pending
            $redirect_url = "riwayat.php?status=booking_success";
        }

        // 1. Simpan ke tabel 'reservasi'
        $q1 = "INSERT INTO reservasi (id_pengguna, id_lapangan, tanggal_reservasi, status) 
               VALUES ('$id_pengguna', '$id_lapangan', '$tanggal_reservasi', '$status')";
        
        if (mysqli_query($koneksi, $q1)) {
            $id_reservasi_baru = mysqli_insert_id($koneksi);

            // 2. Simpan ke tabel 'detail_reservasi'
            $q2 = "INSERT INTO detail_reservasi (id_reservasi, durasi_jam, subtotal_harga) 
                   VALUES ('$id_reservasi_baru', '$durasi_jam', '$subtotal_harga')";
            
            if (mysqli_query($koneksi, $q2)) {
                if (isset($_POST['aksi_bayar'])) {
                    header("Location: " . $redirect_url . "?id=" . $id_reservasi_baru);
                } else {
                    header("Location: " . $redirect_url);
                }
                exit;
            } else {
                $error = "Gagal menyimpan detail reservasi: " . mysqli_error($koneksi);
            }
        } else {
            $error = "Gagal menyimpan data reservasi: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Silakan isi tanggal reservasi dan durasi jam dengan benar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Booking Lapangan - Kukusan Sport Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(rgba(30, 8, 64, 0.75), rgba(15, 3, 33, 0.85)), url('https://images.unsplash.com/photo-1518605368461-1ee7e1617511?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .card { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); width: 100%; max-width: 500px; }
        h2 { color: #4a148c; text-align: center; margin-bottom: 8px; font-weight: 700; }
        .subtitle { text-align: center; color: #6b7280; font-size: 13px; margin-bottom: 25px; }
        .info-box { background: #f3e8ff; padding: 15px; border-left: 4px solid #6a1b9a; border-radius: 8px; margin-bottom: 20px; font-size: 14px; color: #374151; }
        .info-box p { margin-bottom: 5px; }
        .info-box p:last-child { margin-bottom: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 13px; }
        input { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb; transition: all 0.3s; }
        input:focus { border-color: #7c3aed; background-color: #fff; outline: none; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1); }
        .btn-group { display: flex; gap: 12px; margin-top: 25px; }
        .btn-booking { flex: 1; padding: 14px; background-color: #6a1b9a; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; }
        .btn-bayar { flex: 1; padding: 14px; background-color: #059669; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s; }
        .btn-booking:hover { background-color: #4a148c; transform: translateY(-1px); }
        .btn-bayar:hover { background-color: #047857; transform: translateY(-1px); }
        .alert-error { background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5; font-size: 13px; text-align: center; font-weight: 500; }
        .nav-links { display: flex; justify-content: space-between; margin-top: 25px; padding-top: 20px; border-top: 1px solid #f3f4f6; }
        .nav-links a { color: #6a1b9a; text-decoration: none; font-size: 13px; font-weight: 600; }
        .nav-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">
    <h2>Form Reservasi Lapangan</h2>
    <p class="subtitle">Kukusan Sport Center</p>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error; ?></div>
    <?php endif; ?>

    <div class="info-box">
        <p><strong>Lapangan:</strong> <?= htmlspecialchars($lapangan['nama_lapangan']); ?></p>
        <p><strong>Harga:</strong> Rp <?= number_format($lapangan['harga_per_jam'], 0, ',', '.'); ?> / jam</p>
        <p><strong>Penyewa:</strong> <?= htmlspecialchars($nama_lengkap); ?></p>
    </div>

    <form action="" method="POST">
        <div class="form-group">
            <label for="tanggal_reservasi">Tanggal Sewa</label>
            <input type="date" name="tanggal_reservasi" id="tanggal_reservasi" required>
        </div>

        <div class="form-group">
            <label for="durasi_jam">Durasi (Jam)</label>
            <input type="number" name="durasi_jam" id="durasi_jam" value="1" min="1" required>
        </div>

        <div class="btn-group">
            <button type="submit" name="aksi_booking" class="btn-booking">
                📌 Booking Saja
            </button>
            <button type="submit" name="aksi_bayar" class="btn-bayar">
                💳 Bayar Sekarang
            </button>
        </div>
    </form>

    <div class="nav-links">
        <a href="index.php">← Kembali ke Katalog</a>
        <a href="riwayat.php">Lihat Riwayat 📂</a>
    </div>
</div>

</body>
</html>