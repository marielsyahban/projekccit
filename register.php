<?php
// Memulai sesi PHP
session_start();

// Panggil file koneksi database
include 'koneksi.php';

$error = '';
$sukses = '';

// PROSES REGISTRASI AKUN BARU
if (isset($_POST['daftar'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = mysqli_real_escape_string($koneksi, $_POST['password']);
    $no_hp        = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);

    // Cek apakah username sudah terdaftar di database
    $cek_user = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah digunakan, silakan pilih username yang lain!";
    } else {
        // Query untuk menyimpan data ke tabel pengguna
        $query_insert = "INSERT INTO pengguna (nama_lengkap, username, password, no_hp, email) 
                         VALUES ('$nama_lengkap', '$username', '$password', '$no_hp', '$email')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $sukses = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftarkan akun: " . mysqli_error($koneksi);
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        
        body {
            background: linear-gradient(rgba(30, 8, 64, 0.75), rgba(15, 3, 33, 0.85)), 
                        url('https://images.unsplash.com/photo-1518605368461-1ee7e1617511?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat;
            background-color: #4a148c; 
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-card {
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 16px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 450px;
        }

        .register-header { text-align: center; margin-bottom: 25px; }
        .register-header h2 { color: #4a148c; font-weight: 700; font-size: 24px; letter-spacing: -0.5px; }
        .register-header p { color: #6b7280; font-size: 13px; margin-top: 5px; font-weight: 400; }

        .alert {
            padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: center; font-weight: 500;
        }
        .alert-error { background-color: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }
        .alert-sukses { background-color: #ecfdf5; color: #059669; border: 1px solid #6ee7b7; }

        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-group input { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb; color: #111827; }
        .input-group input:focus { border-color: #7c3aed; background-color: #ffffff; outline: none; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1); }

        .btn-daftar { 
            background-color: #6a1b9a; color: white; border: none; width: 100%; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-top: 10px;
        }
        .btn-daftar:hover { background-color: #4a148c; transform: translateY(-1px); }

        .login-box { margin-top: 25px; padding-top: 20px; border-top: 1px solid #f3f4f6; text-align: center; }
        .login-text { color: #6b7280; font-size: 13px; margin-bottom: 12px; font-weight: 500; }
        .btn-login-link { display: inline-block; width: 100%; padding: 12px; background-color: #f3e8ff; color: #6a1b9a; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid #e9d5ff; transition: all 0.3s ease; }
        .btn-login-link:hover { background-color: #6a1b9a; color: #ffffff; border-color: #6a1b9a; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="register-header">
            <h2>Daftar Akun Baru</h2>
            <p>Kukusan Sport Center</p>
        </div>

        <?php if(!empty($error)) : ?>
            <div class='alert alert-error'><?= $error; ?></div>
        <?php endif; ?>

        <?php if(!empty($sukses)) : ?>
            <div class='alert alert-sukses'>
                <?= $sukses; ?> 
                <br><a href="login.php" style="color: #047857; font-weight: bold; text-decoration: underline;">Klik di sini untuk Login</a>
            </div>
        <?php endif; ?>
        
        <!-- Action dikosongkan agar diproses langsung di file ini sendiri -->
        <form action="" method="POST">
            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required autocomplete="off" value="<?= isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : '' ?>">
            </div>

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat username unik" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="input-group">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required autocomplete="off" value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : '' ?>">
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com" required autocomplete="off" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <button type="submit" name="daftar" class="btn-daftar">Daftar Sekarang</button>
        </form>
        
        <div class="login-box">
            <p class="login-text">Sudah memiliki akun?</p>
            <a href="login.php" class="btn-login-link">Masuk ke Akun Anda</a>
        </div>
    </div>

</body>
</html>