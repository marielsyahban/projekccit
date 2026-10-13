<?php
session_start();
include 'koneksi.php';

$pesan = '';
$error = '';

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    // Cek apakah username sudah terdaftar
    $cek_user = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = 'Username sudah digunakan, silakan pilih yang lain!';
    } else {
        // Simpan data ke database
        $query = "INSERT INTO pengguna (nama_lengkap, username, password, no_hp, email) 
                  VALUES ('$nama_lengkap', '$username', '$password', '$no_hp', '$email')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = 'Akun berhasil dibuat! Silakan login.';
        } else {
            $error = 'Terjadi kesalahan, gagal mendaftar.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Kukusan Sport Center</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f3e5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .register-card { background: #ffffff; width: 100%; max-width: 420px; padding: 30px; border-radius: 8px; border-top: 5px solid #6a1b9a; box-shadow: 0 4px 15px rgba(106, 27, 154, 0.15); }
        .register-card h2 { color: #6a1b9a; margin-bottom: 8px; text-align: center; }
        .register-card p { color: #666; font-size: 14px; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 13px; font-weight: bold; color: #4a148c; }
        .form-group input { width: 100%; padding: 9px 12px; border: 1px solid #e1bee7; border-radius: 4px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: #6a1b9a; }
        .btn-register { width: 100%; padding: 11px; background-color: #6a1b9a; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer; transition: 0.2s; margin-top: 5px; }
        .btn-register:hover { background-color: #4a148c; }
        .alert-error { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .alert-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .login-link { text-align: center; margin-top: 15px; font-size: 13px; color: #666; }
        .login-link a { color: #6a1b9a; text-decoration: none; font-weight: bold; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="register-card">
        <h2>Daftar Akun Baru</h2>
        <p>Kukusan Sport Center</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($pesan)): ?>
            <div class="alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Buat username unik" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Buat password" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No. Handphone</label>
                <input type="text" name="no_hp" id="no_hp" placeholder="Contoh: 08123456789" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="email@domain.com" required>
            </div>
            <button type="submit" name="register" class="btn-register">Daftar Sekarang</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>

</body>
</html>