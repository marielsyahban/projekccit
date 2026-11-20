<?php
$host     = getenv('DB_HOST') ?: 'db';
$user     = getenv('DB_USER') ?: 'dev_user';
$password = getenv('DB_PASS') ?: 'dev_password123';
$database = getenv('DB_NAME') ?: 'db_kukusan_sport_center';

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>