<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $konfirmasi = mysqli_real_escape_string($koneksi, $_POST['konfirmasi']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Cek username sudah dipakai atau belum
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Username sudah digunakan!';
    } elseif ($password !== $konfirmasi) {
        $_SESSION['error'] = 'Konfirmasi password tidak cocok!';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed', '$role')";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
            header("Location: login.php");
            exit;
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat registrasi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right,rgb(229, 225, 233), #2575fc);
            height: 100vh;
        }
        .card {
            border-radius: 1rem;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center h-100">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">Registrasi</h3>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Daftar Sebagai</label>
                        <select name="role" class="form-select" required>
                            <option value="" selected disabled>Pilih Role</option>
                            <option value="penumpang">Penumpang</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Daftar</button>
                </form>
                <p class="text-center mt-3 text-muted">
                    Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
