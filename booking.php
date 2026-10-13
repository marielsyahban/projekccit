<?php
// Panggil file koneksi database
include 'koneksi.php';

// Ambil id_lapangan dari URL, pastikan ada nilainya
$id_lapangan = isset($_GET['id_lapangan']) ? intval($_GET['id_lapangan']) : 0;

// Ambil data lapangan yang dipilih beserta harganya
$query_lapangan = "SELECT * FROM lapangan WHERE id_lapangan = $id_lapangan";
$result_lapangan = mysqli_query($koneksi, $query_lapangan);
$lapangan = mysqli_fetch_assoc($result_lapangan);

// Jika lapangan tidak ditemukan, tampilkan pesan peringatan alih-alih redirect looping
if (!$lapangan) {
    echo "<div style='font-family: Arial; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color: #c0392b;'>Error: Lapangan tidak dipilih atau tidak ditemukan!</h2>";
    echo "<p>Silakan kembali ke halaman utama dan pilih lapangan terlebih dahulu.</p>";
    echo "<a href='index.php' style='background: #2980b9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Kembali ke Beranda</a>";
    echo "</div>";
    exit();
}

$pesan = "";

// Jika tombol 'pesan_sekarang' ditekan
if (isset($_POST['pesan_sekarang'])) {
    $id_pengguna = intval($_POST['id_pengguna']);
    $tanggal_reservasi = mysqli_real_escape_string($koneksi, $_POST['tanggal_reservasi']);
    $durasi_jam = intval($_POST['durasi_jam']);
    $harga_per_jam = $lapangan['harga_per_jam'];
    
    // Hitung subtotal harga otomatis
    $subtotal_harga = $durasi_jam * $harga_per_jam;
    $status = 'Pending';

    // 1. Simpan data ke tabel induk (reservasi)
    $query_reservasi = "INSERT INTO reservasi (id_pengguna, id_lapangan, tanggal_reservasi, status) 
                        VALUES ('$id_pengguna', '$id_lapangan', '$tanggal_reservasi', '$status')";
    
    if (mysqli_query($koneksi, $query_reservasi)) {
        // Ambil ID reservasi yang baru saja digenerate
        $id_reservasi_baru = mysqli_insert_id($koneksi);

        // 2. Simpan data ke tabel anak (detail_reservasi)
        $query_detail = "INSERT INTO detail_reservasi (id_reservasi, durasi_jam, subtotal_harga) 
                         VALUES ('$id_reservasi_baru', '$durasi_jam', '$subtotal_harga')";
        
        if (mysqli_query($koneksi, $query_detail)) {
            $pesan = "<p style='color: green;'>Booking berhasil dilakukan! Data tersimpan di database.</p>";
        } else {
            $pesan = "<p style='color: red;'>Gagal menyimpan detail reservasi: " . mysqli_error($koneksi) . "</p>";
        }
    } else {
        $pesan = "<p style='color: red;'>Gagal menyimpan reservasi: " . mysqli_error($koneksi) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking - Kukusan Sport Center</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f7f6; color: #333; }
        .container { max-width: 500px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin: auto; }
        h2 { color: #2c3e50; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #27ae60; color: white; padding: 10px 15px; border: none; width: 100%; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .btn-submit:hover { background-color: #219653; }
        .btn-back { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #2980b9; }
        .info-box { background: #e8f8f5; padding: 10px; border-left: 4px solid #27ae60; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Booking Lapangan</h2>
        
        <div class="info-box">
            <p><strong>Lapangan:</strong> <?= htmlspecialchars($lapangan['nama_lapangan']); ?></p>
            <p><strong>Harga:</strong> Rp <?= number_format($lapangan['harga_per_jam'], 0, ',', '.'); ?> / jam</p>
        </div>

        <?= $pesan; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="id_pengguna">ID Pengguna (Penyewa):</label>
                <input type="number" name="id_pengguna" id="id_pengguna" placeholder="Masukkan ID Pengguna (1-10)" required min="1" max="10">
                <small style="color: #666;">Masukkan angka antara 1 sampai 10 sesuai data pengguna.</small>
            </div>

            <div class="form-group">
                <label for="tanggal_reservasi">Tanggal Sewa:</label>
                <input type="date" name="tanggal_reservasi" id="tanggal_reservasi" required>
            </div>

            <div class="form-group">
                <label for="durasi_jam">Durasi (Jam):</label>
                <input type="number" name="durasi_jam" id="durasi_jam" value="1" min="1" max="12" required>
            </div>

            <button type="submit" name="pesan_sekarang" class="btn-submit">Konfirmasi Booking</button>
        </form>

        <a href="index.php" class="btn-back">&larr; Kembali ke Daftar Lapangan</a>
    </div>

</body>
</html>