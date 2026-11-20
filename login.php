<?php
// Memulai sesi PHP
session_start();

// Panggil file koneksi database
include 'koneksi.php';

$error = false;

// PROSES LOGIN
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cek data ke tabel pengguna
    $cek_database = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($cek_database) > 0) {
        $data = mysqli_fetch_assoc($cek_database);
        
        // Hapus session lama supaya akun sebelumnya tidak nyangkut
        session_unset();
        session_destroy();
        
        // Mulai session baru yang bersih khusus untuk akun ini
        session_start();
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['id_pengguna'] = $data['id_pengguna']; // ID Pengguna diambil langsung dari database akun aktif
        
        // Alihkan ke dashboard
        header("Location: index.php");
        exit;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kukusan Sport Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        
        body {
            background: linear-gradient(rgba(30, 8, 64, 0.75), rgba(15, 3, 33, 0.85)), 
                        url('https://images.unsplash.com/photo-1518605368461-1ee7e1617511?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
            background-color: #4a148c; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 420px;
        }

        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h2 { color: #4a148c; font-weight: 700; font-size: 24px; letter-spacing: -0.5px; }
        .login-header p { color: #6b7280; font-size: 13px; margin-top: 5px; font-weight: 400; }

        .alert {
            padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: center; font-weight: 500;
        }
        .alert-error { background-color: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }

        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .input-group input { width: 100%; padding: 14px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb; color: #111827; }
        .input-group input:focus { border-color: #7c3aed; background-color: #ffffff; outline: none; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1); }

        .btn-login { 
            background-color: #6a1b9a; color: white; border: none; width: 100%; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-top: 10px;
        }
        .btn-login:hover { background-color: #4a148c; transform: translateY(-1px); }

        .register-box { margin-top: 30px; padding-top: 25px; border-top: 1px solid #f3f4f6; text-align: center; }
        .register-text { color: #6b7280; font-size: 13px; margin-bottom: 15px; font-weight: 500; }
        .btn-daftar { display: inline-block; width: 100%; padding: 12px; background-color: #f3e8ff; color: #6a1b9a; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid #e9d5ff; transition: all 0.3s ease; }
        .btn-daftar:hover { background-color: #6a1b9a; color: #ffffff; border-color: #6a1b9a; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Kukusan Sport Center</h2>
            <p>Silakan masuk untuk mulai sewa lapangan</p>
        </div>

        <?php if($error) : ?>
            <div class='alert alert-error'>Username atau Password salah!</div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username Anda" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">Masuk ke Dashboard</button>
        </form>
        
        <div class="register-box">
            <p class="register-text">Belum mempunyai akun?</p>
            <a href="register.php" class="btn-daftar">Daftar Pengguna Sekarang</a>
        </div>
    </div>

</body>
</html>