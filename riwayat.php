<?php
// Panggil file koneksi database
include 'koneksi.php';

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

// Menggunakan LEFT JOIN agar data reservasi tetap tampil meskipun ada relasi yang kosong
$query = "SELECT r.id_reservasi, 
                 IFNULL(p.nama_lengkap, 'Pengguna Tidak Dikenal') AS nama_lengkap, 
                 IFNULL(l.nama_lapangan, 'Lapangan Tidak Dikenal') AS nama_lapangan, 
                 r.tanggal_reservasi, 
                 IFNULL(d.durasi_jam, 1) AS durasi_jam, 
                 IFNULL(l.harga_per_jam, 0) AS harga_per_jam, 
                 IFNULL(d.subtotal_harga, 0) AS subtotal_harga, 
                 r.status 
          FROM reservasi r
          LEFT JOIN detail_reservasi d ON r.id_reservasi = d.id_reservasi
          LEFT JOIN pengguna p ON r.id_pengguna = p.id_pengguna
          LEFT JOIN lapangan l ON r.id_lapangan = l.id_lapangan
          ORDER BY r.id_reservasi DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Kukusan Sport Center</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 1050px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        th, td { padding: 12px 10px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; vertical-align: middle; }
        th { background-color: #2c3e50; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .badge { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { text-decoration: none; background-color: #2980b9; color: white; padding: 8px 15px; border-radius: 4px; margin-right: 10px; font-size: 14px; }
        .nav-links a:hover { background-color: #1f618d; }
        /* Style untuk foto lapangan di tabel riwayat */
        .img-lapangan { width: 70px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Rekap Riwayat Pemesanan Lapangan</h2>
        
        <div class="nav-links">
            <a href="index.php">&larr; Beranda (Daftar Lapangan)</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Penyewa</th>
                    <th>Nama Lapangan</th>
                    <th>Tanggal Sewa</th>
                    <th>Durasi</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                        // Ambil foto sesuai jenis lapangan menggunakan fungsi
                        $gambar = getFotoLapangan($row['nama_lapangan']);
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <img src="<?= $gambar; ?>" alt="Foto Lapangan" class="img-lapangan">
                        </td>
                        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?= htmlspecialchars($row['nama_lapangan']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_reservasi']); ?></td>
                        <td><?= $row['durasi_jam']; ?> Jam</td>
                        <td>Rp <?= number_format($row['subtotal_harga'], 0, ',', '.'); ?></td>
                        <td><span class="badge"><?= htmlspecialchars($row['status']); ?></span></td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>Belum ada data riwayat pemesanan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>