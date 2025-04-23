<?php
session_start();
require_once 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil total pendapatan
$total_pendapatan_query = mysqli_query($koneksi, "SELECT SUM(harga) AS total FROM pemesanan WHERE status='sudah bayar'");
$total_pendapatan = mysqli_fetch_assoc($total_pendapatan_query)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pendapatan - Aplikasi Pemesanan Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h3 class="text-light mb-4">Kelola Pendapatan</h3>

        <!-- Total Pendapatan -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title">Total Pendapatan</h5>
            </div>
            <div class="card-body text-center">
                <h3 class="text-success">Rp<?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-success">Cetak Daftar Pengguna</button>
        <!-- Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
