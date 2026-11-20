<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_reservasi = intval($_GET['id']);
    $id_pengguna = $_SESSION['id_pengguna'];

    // Ubah status reservasi menjadi 'Dibatalkan'
    $query = "UPDATE reservasi SET status = 'Dibatalkan' WHERE id_reservasi = '$id_reservasi' AND id_pengguna = '$id_pengguna'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: riwayat.php?status=cancelled");
        exit;
    } else {
        echo "Gagal membatalkan reservasi: " . mysqli_error($koneksi);
    }
} else {
    header("Location: riwayat.php");
    exit;
}
?>