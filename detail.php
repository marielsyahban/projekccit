<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_lapangan = intval($_GET['id']);

// Ambil data lapangan dari database
$query = "SELECT * FROM lapangan WHERE id_lapangan = '$id_lapangan'";
$result = mysqli_query($koneksi, $query);
$lapangan = mysqli_fetch_assoc($result);

if (!$lapangan) {
    header("Location: index.php");
    exit;
}

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

// Fungsi helper untuk deskripsi spesifik
function getDeskripsiLapangan($nama) {
    $nama = strtolower($nama);
    if (strpos($nama, 'badminton') !== false) {
        return "Lapangan Badminton A menggunakan standar internasional dengan karpet interlock berkualitas tinggi, pencahayaan LED anti-silau, dan sirkulasi udara indoor yang sangat nyaman untuk pertandingan maupun latihan rutin.";
    } elseif (strpos($nama, 'futsal') !== false) {
        return "Lapangan futsal dengan rumput sintetis premium, jaring pengaman yang kuat, serta tata cahaya terang yang mendukung penuh kenyamanan bermain.";
    } elseif (strpos($nama, 'mini soccer') !== false || strpos($nama, 'minsoc') !== false) {
        return "Lapangan mini soccer berukuran luas dengan rumput sintetis standar tinggi yang empuk dan terawat secara berkala.";
    } elseif (strpos($nama, 'tenis') !== false) {
        return "Lapangan tenis dengan permukaan hard court berkualitas tinggi, lengkap dengan jaring standar turnamen dan area baseline yang luas.";
    } elseif (strpos($nama, 'basket') !== false) {
        return "Lapangan basket indoor dengan lantai parket kayu asli berstandar profesional, ring fleksibel, serta akustik ruangan yang nyaman.";
    } else {
        return "Lapangan olahraga berkualitas tinggi yang dirancang khusus untuk kenyamanan bermain Anda di Kukusan Sport Center.";
    }
}

// Panggil fungsi gambar berdasarkan nama lapangan dari database
$gambar = getFotoLapangan($lapangan['nama_lapangan']);
$deskripsi = getDeskripsiLapangan($lapangan['nama_lapangan']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?= htmlspecialchars($lapangan['nama_lapangan']); ?> - Kukusan Sport Center</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f9f5fc; color: #333333; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .card-detail { background: #ffffff; border: 1px solid #e1bee7; border-top: 5px solid #6a1b9a; border-radius: 8px; box-shadow: 0 6px 16px rgba(106, 27, 154, 0.08); width: 100%; max-width: 650px; padding: 30px; }
        
        h2 { color: #6a1b9a; font-size: 22px; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #f3e5f5; padding-bottom: 10px; font-weight: bold; }
        
        .img-container { width: 100%; height: 320px; border-radius: 6px; overflow: hidden; background: #f3e5f5; margin-bottom: 20px; border: 1px solid #e1bee7; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        .info-group { margin-bottom: 15px; font-size: 15px; color: #4a5568; line-height: 1.6; }
        .info-group strong { color: #6a1b9a; }
        
        .harga-badge { background: #f3e5f5; color: #6a1b9a; padding: 12px 18px; border-radius: 6px; font-weight: bold; font-size: 18px; display: inline-block; margin: 10px 0 20px 0; border: 1px solid #e1bee7; }
        
        .btn-container { display: flex; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; text-align: center; flex: 1; cursor: pointer; transition: 0.2s; }
        
        .btn-kembali { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-kembali:hover { background-color: #e2e8f0; }
        
        .btn-sewa { background-color: #6a1b9a; color: #ffffff; }
        .btn-sewa:hover { background-color: #4a148c; }
    </style>
</head>
<body>

<div class="card-detail">
    <h2><?= htmlspecialchars($lapangan['nama_lapangan']); ?></h2>
    
    <div class="img-container">
        <img src="<?= $gambar; ?>" alt="<?= htmlspecialchars($lapangan['nama_lapangan']); ?>">
    </div>

    <div class="harga-badge">
        Tarif: Rp. <?= number_format($lapangan['harga_per_jam'], 2, ',', '.'); ?> / JAM
    </div>

    <div class="info-group">
        <strong>Deskripsi & Fasilitas:</strong><br>
        <?= $deskripsi; ?>
    </div>

    <div class="btn-container">
        <a href="index.php" class="btn btn-kembali">Kembali</a>
        <a href="booking.php?id_lapangan=<?= $lapangan['id_lapangan']; ?>" class="btn btn-sewa">📅 Sewa Lapangan Ini</a>
    </div>
</div>

</body>
</html>