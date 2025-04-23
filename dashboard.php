<?php
session_start();
require_once 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil jumlah pemesanan dan jumlah pembayaran
$total_pemesanan_query = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM pemesanan");
$total_pemesanan = mysqli_fetch_assoc($total_pemesanan_query)['total'];

$total_pendapatan_query = mysqli_query($koneksi, "SELECT SUM(harga) AS total FROM pemesanan WHERE status='sudah bayar'");
$total_pendapatan = mysqli_fetch_assoc($total_pendapatan_query)['total'];

$total_belum_bayar_query = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM pemesanan WHERE status='belum bayar'");
$total_belum_bayar = mysqli_fetch_assoc($total_belum_bayar_query)['total'];

// Ambil data pengguna
$total_pengguna_query = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM users WHERE role='user'");
$total_pengguna = mysqli_fetch_assoc($total_pengguna_query)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aplikasi Pemesanan Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #34495e);
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            margin-top: 30px;
        }
        .card-body {
            padding: 2rem;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .card-header {
            background-color: #2980b9;
            color: white;
        }
        .table thead {
            background-color: #2980b9;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .statistics .col {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
        }
        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <!-- Statistik Dashboard -->
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Total Pemesanan</h5>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-primary"><?= $total_pemesanan ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Pemesanan Belum Dibayar</h5>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-warning"><?= $total_belum_bayar ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Pendapatan</h5>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-success">Rp<?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">Admin</h5>
                    </div>
                    <div class="card-body text-center">
                        <h4 class="text-secondary"><?= $username ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Kelola Pemesanan -->
        <h3 class="text-light mb-4">Kelola Pemesanan</h3>
        <div class="row btn-container">
            <div class="col-md-4">
                <a href="kelola_pemesanan.php" class="btn btn-primary w-100">Kelola Pemesanan</a>
            </div>

            <!-- Kelola Pendapatan -->
            <div class="col-md-4">
                <a href="kelola_pendapatan.php" class="btn btn-success w-100">Kelola Pendapatan</a>
            </div>

            <!-- Kelola Data Pengguna -->
            <div class="col-md-4">
                <a href="kelola_pengguna.php" class="btn btn-warning w-100">Kelola Data Pengguna</a>
            </div>
        </div>

        <hr class="my-4">

        <!-- Kelola Kereta dan Rute -->
        <h3 class="text-light mb-4">Kelola Kereta dan Rute</h3>
        <div class="row btn-container">
            <div class="col-md-4">
                <a href="kelola_kereta_rute.php" class="btn btn-info w-100">Kelola Kereta dan Rute</a>
            </div>

        
        <hr class="my-4">

        <!-- Daftar Pemesanan -->
        <h3 class="text-light mb-4">Daftar Pemesanan Tiket</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kereta</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Waktu Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data pemesanan
                    $data_pemesanan = mysqli_query($koneksi, "SELECT * FROM pemesanan ORDER BY created_at DESC");
                    while ($row = mysqli_fetch_assoc($data_pemesanan)) :
                    ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['nama_penumpang']); ?></td>
                            <td><?= htmlspecialchars($row['kereta']); ?></td>
                            <td><?= htmlspecialchars($row['asal']); ?></td>
                            <td><?= htmlspecialchars($row['tujuan']); ?></td>
                            <td><?= $row['tanggal_berangkat']; ?></td>
                            <td><?= htmlspecialchars($row['kelas']); ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?= $row['status'] === 'sudah bayar' ? '<span class="badge bg-success">Sudah Bayar</span>' : '<span class="badge bg-warning text-dark">Belum Bayar</span>'; ?>
                            </td>
                            <td><?= $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Logout Button -->
        <div class="text-end mt-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
