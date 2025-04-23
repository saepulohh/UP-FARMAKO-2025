<?php
session_start();
require_once 'koneksi.php';

// Proses login langsung di halaman ini
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_user'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect sesuai role
            if ($row['role'] === 'admin' || $row['role'] === 'petugas') {
                header("Location: dashboard.php");
                exit;
            } elseif ($row['role'] === 'penumpang') {
                header("Location: dashboard_user.php");
                exit;
            }
        } else {
            $_SESSION['error'] = 'Password salah!';
        }
    } else {
        $_SESSION['error'] = 'Username tidak ditemukan!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tiket Kereta</title>
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
                    <h3 class="text-center mb-4">Login</h3>
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
    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>

<div class="text-center mt-3">
    <span class="text-light">Belum punya akun?</span><br>
    <a href="register.php" class="btn btn-outline-light mt-2">Daftar Sekarang</a>
</div>

            </div>
        </div>
    </div>
</body>
</html>
