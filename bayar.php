<?php
session_start();
require_once 'koneksi.php';

// Cek login dan role
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'penumpang') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Ambil ID dari URL
$id_pemesanan = $_GET['id'] ?? '';

// Ambil data pemesanan sesuai ID
$result = mysqli_query($koneksi, "SELECT * FROM pemesanan WHERE id='$id_pemesanan' AND user_id='$user_id'");
$data = mysqli_fetch_assoc($result);

// Cek apakah data ditemukan dan milik user
if (!$data) {
    echo "<h3 class='text-center text-light mt-5'>Data tidak ditemukan atau Anda tidak memiliki akses.</h3>";
    exit;
}

// Jika form disubmit (konfirmasi pembayaran)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);

    // Update status dan simpan metode pembayaran
    $update = mysqli_query($koneksi, "UPDATE pemesanan SET status='sudah bayar', metode_pembayaran='$metode_pembayaran' WHERE id='$id_pemesanan' AND user_id='$user_id'");

    if ($update) {
        echo "<script>alert('Pembayaran berhasil menggunakan metode: $metode_pembayaran'); window.location='dashboard_user.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memproses pembayaran.</div>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bayar Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #4b6cb7, #182848); min-height: 100vh; color: white; }
        .card { margin-top: 50px; border-radius: 1rem; }
        label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4 shadow-lg">
        <h3 class="text-center text-primary">Pembayaran Tiket</h3>
        <hr>
        <p><strong>Nama Penumpang:</strong> <?= htmlspecialchars($data['nama_penumpang']); ?></p>
        <p><strong>Kereta:</strong> <?= htmlspecialchars($data['kereta']); ?></p>
        <p><strong>Asal:</strong> <?= htmlspecialchars($data['asal']); ?></p>
        <p><strong>Tujuan:</strong> <?= htmlspecialchars($data['tujuan']); ?></p>
        <p><strong>Tanggal Berangkat:</strong> <?= $data['tanggal_berangkat']; ?></p>
        <p><strong>Kelas:</strong> <?= htmlspecialchars($data['kelas']); ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($data['harga'], 0, ',', '.'); ?></p>
        <p><strong>Waktu Pemesanan:</strong> <?= $data['created_at']; ?></p>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="metode_pembayaran" class="form-label">Pilih Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Transfer Bank">Transfer Bank (BCA, BRI, Mandiri)</option>
                    <option value="E-Wallet">E-Wallet (OVO, GoPay, Dana)</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Konfirmasi & Bayar</button>
        </form>

        <div class="text-end mt-3">
            <a href="dashboard_user.php" class="btn btn-light">Kembali</a>
        </div>
    </div>
</div>
</body>
</html>
