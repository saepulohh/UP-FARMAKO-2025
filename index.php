<?php
include 'koneksi.php';
session_start();

// Cek kalau belum login, redirect
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM jadwal");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-TIKET.COM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🚇 E-TIKET.COM</h1>
    </header>

    <main>
        <div class="login-container">
            <h2 style="text-align: center;">Data Jadwal Kereta</h2>
            <div style="text-align: center; margin-bottom: 15px;">
                <a href="tambah_jadwal.php" class="btn-login">+ Tambah Jadwal</a>
                <a href="logout.php" class="btn-login" style="background-color: crimson;">Logout</a>
            </div>

            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: #fff; border-collapse: collapse;">
                <thead style="background-color: #ddd;">
                    <tr>
                        <th>No</th>
                        <th>Nama Kereta</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama_kereta'] ?></td>
                            <td><?= $row['asal'] ?></td>
                            <td><?= $row['tujuan'] ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['jam'] ?></td>
                            <td>
                                <a href="edit_jadwal.php?id=<?= $row['id'] ?>" class="btn-login" style="padding: 5px 10px;">Edit</a>
                                <a href="hapus_jadwal.php?id=<?= $row['id'] ?>" class="btn-login" style="background-color: crimson; padding: 5px 10px;" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>© 2025 Aplikasi Tiket Kereta. Semua hak dilindungi.</p>
    </footer>
</body>
</html>
