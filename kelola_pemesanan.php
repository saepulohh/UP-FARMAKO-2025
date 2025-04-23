<?php
session_start();
require_once 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Hapus pemesanan jika tombol hapus ditekan
if (isset($_GET['hapus'])) {
    $id_pemesanan = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pemesanan WHERE id = '$id_pemesanan'");
    header("Location: kelola_pemesanan.php");
    exit;
}

// Ambil data pemesanan
$data_pemesanan = mysqli_query($koneksi, "SELECT * FROM pemesanan ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemesanan - Aplikasi Pemesanan Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h3 class="text-light mb-4">Kelola Pemesanan Tiket</h3>
        
        <!-- Tabel Pemesanan -->
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($data_pemesanan)) : ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['nama_penumpang']); ?></td>
                            <td><?= htmlspecialchars($row['kereta']); ?></td>
                            <td><?= htmlspecialchars($row['asal']); ?></td>
                            <td><?= htmlspecialchars($row['tujuan']); ?></td>
                            <td><?= $row['tanggal_berangkat']; ?></td>
                            <td><?= htmlspecialchars($row['kelas']); ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['status'] === 'sudah bayar' ? '<span class="badge bg-success">Sudah Bayar</span>' : '<span class="badge bg-warning text-dark">Belum Bayar</span>'; ?></td>
                            <td>
                                <a href="edit_pemesanan.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pemesanan ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <button onclick="window.print()" class="btn btn-success">Cetak Daftar Pengguna</button>

        <!-- Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
