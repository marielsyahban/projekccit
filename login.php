<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password === $row['password'] || password_verify($password, $row['password']) || md5($password) === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['id_pengguna'] = $row['id_pengguna'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['username'] = $row['username'];

            header("Location: index.php");
            exit;
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kukusan Sport Center</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f3e5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: #ffffff; width: 100%; max-width: 400px; padding: 30px; border-radius: 8px; border-top: 5px solid #6a1b9a; box-shadow: 0 4px 15px rgba(106, 27, 154, 0.15); }
        .login-card h2 { color: #6a1b9a; margin-bottom: 8px; text-align: center; }
        .login-card p { color: #666; font-size: 14px; margin-bottom: 25px; text-align: center; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: bold; color: #4a148c; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #e1bee7; border-radius: 4px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: #6a1b9a; }
        .btn-login { width: 100%; padding: 11px; background-color: #6a1b9a; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-login:hover { background-color: #4a148c; }
        .alert-error { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 18px; text-align: center; }
        .register-link { text-align: center; margin-top: 15px; font-size: 13px; color: #666; }
        .register-link a { color: #6a1b9a; text-decoration: none; font-weight: bold; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Kukusan Sport Center</h2>
        <p>Silakan masuk ke akun Anda</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Masuk (Login)</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Buat akun baru</a>
        </div>
    </div>

</body>
</html>