<?php
// 1. CEK SESSION LOGIN (Bagian Penting #1)
// Memulai sesi PHP untuk mengecek apakah pengguna sudah melakukan login
session_start();

// Jika belum ada session 'login', paksa arahkan kembali ke halaman login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Panggil file koneksi database
include 'koneksi.php';

// Ambil data dari tabel lapangan
$query = "SELECT * FROM lapangan";
$result = mysqli_query($koneksi, $query);

// Fungsi untuk menentukan foto lokal berdasarkan nama lapangan
function getFotoLapangan($nama) {
    $nama = strtolower($nama);
    
    if (strpos($nama, 'badminton a') !== false) {
        return "img/badminton-a.jpg"; 
    } elseif (strpos($nama, 'badminton b') !== false) {
        return "img/badminton-b.jpg"; 
    } elseif (strpos($nama, 'futsal indoor') !== false) {
        return "img/futsal-indor.jpg"; 
    } elseif (strpos($nama, 'futsal') !== false) {
        return "img/futsal-outdor.jpg"; 
    } elseif (strpos($nama, 'mini soccer 2') !== false || strpos($nama, 'minsoc 2') !== false) {
        return "img/minsoc-2.jpg"; 
    } elseif (strpos($nama, 'mini soccer') !== false || strpos($nama, 'minsoc') !== false) {
        return "img/minsoc-1.jpg"; 
    } elseif (strpos($nama, 'tenis 2') !== false) {
        return "img/tenis-2.jpg"; 
    } elseif (strpos($nama, 'tenis') !== false) {
        return "img/tenis-1.jpg"; 
    } elseif (strpos($nama, 'basket') !== false) {
        return "img/basket.jpg"; 
    } else {
        return "img/voli.jpg"; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Sewa Lapangan - Kukusan Sport Center</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #ffffff; color: #333333; }
        
        /* Navbar Atas - Ungu Utama & Latar Putih */
        .navbar-top { background-color: #6a1b9a; color: #ffffff; padding: 15px 25px; font-size: 16px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .navbar-menu { background-color: #ffffff; padding: 12px 25px; border-bottom: 2px solid #f3e5f5; display: flex; justify-content: space-between; align-items: center; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a { text-decoration: none; color: #6a1b9a; font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
        .nav-links a:hover { color: #4a148c; }

        /* Style Sambutan Nama User & Tombol Logout (Bagian Penting #2) */
        .user-info { font-size: 13px; color: #4a148c; font-weight: bold; }
        .btn-logout { background-color: #c62828; color: white !important; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; }
        .btn-logout:hover { background-color: #b71c1c !important; }

        /* Container Utama Grid Card */
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }

        /* Card Lapangan Ungu & Putih */
        .card { background: #ffffff; border: 1px solid #e1bee7; border-top: 4px solid #6a1b9a; border-radius: 6px; box-shadow: 0 4px 12px rgba(106, 27, 154, 0.06); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; }
        .card-header { padding: 15px 20px 10px 20px; }
        .card-header h3 { font-size: 15px; color: #6a1b9a; text-transform: uppercase; margin-bottom: 5px; font-weight: bold; }
        .card-header .harga { font-size: 14px; font-weight: bold; color: #4a148c; }

        /* Gambar Card */
        .card-img-container { width: 100%; height: 200px; overflow: hidden; background: #f3e5f5; }
        .card-img-container img { width: 100%; height: 100%; object-fit: cover; }

        /* Tombol Aksi Khas Ungu & Putih */
        .card-actions { padding: 15px 20px; background: #ffffff; display: flex; gap: 10px; }
        .btn { padding: 9px 16px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; text-align: center; cursor: pointer; transition: 0.2s; }
        
        .btn-detail { background-color: #f3e5f5; color: #6a1b9a; border: 1px solid #e1bee7; flex: 1; }
        .btn-detail:hover { background-color: #e1bee7; }
        
        .btn-sewa { background-color: #6a1b9a; color: #ffffff; flex: 1; }
        .btn-sewa:hover { background-color: #4a148c; }
    </style>
</head>
<body>

    <!-- Header / Navbar Atas (Menampilkan Sapaan Nama User) -->
    <div class="navbar-top">
        <span>📍 App Sewa Lapangan - Kukusan Sport Center</span>
        <span class="user-info">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?> 👋</span>
    </div>
    
    <div class="navbar-menu">
        <div class="nav-links">
            <a href="index.php">🏠 Dashboard</a>
            <a href="riwayat.php">📂 Riwayat Pemesanan</a>
        </div>
        <div class="nav-links">
            <!-- Tombol Keluar / Logout (Bagian Penting #3) -->
            <a href="logout.php" class="btn-logout">Logout 🚪</a>
        </div>
    </div>

    <!-- Konten Daftar Lapangan (Card Grid Ungu-Putih) -->
    <div class="container">
        <div class="card-grid">
            <?php 
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
                    $gambar = getFotoLapangan($row['nama_lapangan']); 
            ?>
                <div class="card">
                    <div>
                        <div class="card-header">
                            <h3><?= htmlspecialchars($row['nama_lapangan']); ?></h3>
                            <div class="harga">Rp. <?= number_format($row['harga_per_jam'], 2, ',', '.'); ?> / JAM</div>
                        </div>
                        <div class="card-img-container">
                            <img src="<?= $gambar; ?>" alt="<?= htmlspecialchars($row['nama_lapangan']); ?>">
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="#" class="btn btn-detail">Lihat detail</a>
                        <a href="booking.php?id_lapangan=<?= $row['id_lapangan']; ?>" class="btn btn-sewa">Sewa</a>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='text-align: center; grid-column: 1 / -1;'>Belum ada data lapangan yang tersedia.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>