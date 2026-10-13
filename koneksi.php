<?php
$host     = "localhost";
$user     = "root";      
$password = "";          
$database = "db_kukusan_sport_center";

// Pastikan menggunakan mysqli_connect, bukan mysqli_query
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>