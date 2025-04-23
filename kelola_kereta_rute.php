<?php
session_start();
require_once 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Menambahkan kereta baru
if (isset($_POST['tambah_kereta'])) {
    $nama_kereta = mysqli_real_escape_string($koneksi, $_POST['nama_kereta']);
    $kelas = $_POST['kelas'];

    $query = "INSERT INTO kereta (nama_kereta, kelas) VALUES ('$nama_kereta', '$kelas')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_kereta_rute.php");
        exit;
    } else {
        $error_message = "Gagal menambahkan kereta. Silakan coba lagi.";
    }
}

// Menambahkan rute baru
if (isset($_POST['tambah_rute'])) {
    $asal = mysqli_real_escape_string($koneksi, $_POST['asal']);
    $tujuan = mysqli_real_escape_string($koneksi, $_POST['tujuan']);
    $harga = $_POST['harga']; // Ambil harga dari form

    $query = "INSERT INTO rute (asal, tujuan, harga) VALUES ('$asal', '$tujuan', '$harga')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_kereta_rute.php");
        exit;
    } else {
        $error_message = "Gagal menambahkan rute. Silakan coba lagi.";
    }
}

// Menghapus kereta
if (isset($_GET['hapus_kereta'])) {
    $id_kereta = $_GET['hapus_kereta'];
    mysqli_query($koneksi, "DELETE FROM kereta WHERE id = '$id_kereta'");
    header("Location: kelola_kereta_rute.php");
    exit;
}

// Menghapus rute
if (isset($_GET['hapus_rute'])) {
    $id_rute = $_GET['hapus_rute'];
    mysqli_query($koneksi, "DELETE FROM rute WHERE id = '$id_rute'");
    header("Location: kelola_kereta_rute.php");
    exit;
}

// Update Kereta
if (isset($_POST['update_kereta'])) {
    $id_kereta = $_POST['id_kereta'];
    $nama_kereta = mysqli_real_escape_string($koneksi, $_POST['nama_kereta']);
    $kelas = $_POST['kelas'];

    $query = "UPDATE kereta SET nama_kereta='$nama_kereta', kelas='$kelas' WHERE id='$id_kereta'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_kereta_rute.php");
        exit;
    } else {
        $error_message = "Gagal memperbarui kereta. Silakan coba lagi.";
    }
}

// Update Rute
if (isset($_POST['update_rute'])) {
    $id_rute = $_POST['id_rute'];
    $asal = mysqli_real_escape_string($koneksi, $_POST['asal']);
    $tujuan = mysqli_real_escape_string($koneksi, $_POST['tujuan']);
    $harga = $_POST['harga']; // Ambil harga dari form

    $query = "UPDATE rute SET asal='$asal', tujuan='$tujuan', harga='$harga' WHERE id='$id_rute'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_kereta_rute.php");
        exit;
    } else {
        $error_message = "Gagal memperbarui rute. Silakan coba lagi.";
    }
}

// Ambil data kereta
$kereta_query = mysqli_query($koneksi, "SELECT * FROM kereta ORDER BY id ASC");

// Ambil data rute
$rute_query = mysqli_query($koneksi, "SELECT * FROM rute ORDER BY id ASC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kereta dan Rute - Aplikasi Pemesanan Tiket Kereta</title>
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
    </style>
</head>
<body>
    <div class="container py-5">
        <h3 class="text-light mb-4">Kelola Kereta dan Rute</h3>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Form Tambah Kereta -->
        <form method="POST" action="" class="mb-4">
            <h4 class="mb-3">Tambah Kereta Baru</h4>
            <div class="mb-3">
                <label for="nama_kereta" class="form-label">Nama Kereta</label>
                <input type="text" class="form-control" id="nama_kereta" name="nama_kereta" required>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="kelas" name="kelas" required>
            </div>
            <button type="submit" name="tambah_kereta" class="btn btn-primary">Tambah Kereta</button>
        </form>

        <!-- Form Tambah Rute -->
        <form method="POST" action="" class="mb-4">
            <h4 class="mb-3">Tambah Rute Baru</h4>
            <div class="mb-3">
                <label for="asal" class="form-label">Asal</label>
                <input type="text" class="form-control" id="asal" name="asal" required>
            </div>
            <div class="mb-3">
                <label for="tujuan" class="form-label">Tujuan</label>
                <input type="text" class="form-control" id="tujuan" name="tujuan" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="harga" name="harga" required>
            </div>
            <button type="submit" name="tambah_rute" class="btn btn-success">Tambah Rute</button>
        </form>

        <hr class="my-4">

        <!-- Daftar Kereta -->
        <h3 class="text-light mb-4">Daftar Kereta</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kereta</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($kereta = mysqli_fetch_assoc($kereta_query)) : ?>
                        <tr>
                            <td><?= $kereta['id']; ?></td>
                            <td><?= htmlspecialchars($kereta['nama_kereta']); ?></td>
                            <td><?= htmlspecialchars($kereta['kelas']); ?></td>
                            <td>
                                <!-- Form Update Kereta -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateKeretaModal<?= $kereta['id']; ?>">Update</button>
                                <a href="?hapus_kereta=<?= $kereta['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kereta ini?')">Hapus</a>

                                <!-- Modal Update Kereta -->
                                <div class="modal fade" id="updateKeretaModal<?= $kereta['id']; ?>" tabindex="-1" aria-labelledby="updateKeretaModalLabel<?= $kereta['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateKeretaModalLabel<?= $kereta['id']; ?>">Update Kereta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_kereta" value="<?= $kereta['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="nama_kereta" class="form-label">Nama Kereta</label>
                                                        <input type="text" class="form-control" id="nama_kereta" name="nama_kereta" value="<?= htmlspecialchars($kereta['nama_kereta']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kelas" class="form-label">Kelas</label>
                                                        <input type="text" class="form-control" id="kelas" name="kelas" value="<?= htmlspecialchars($kereta['kelas']); ?>" required>
                                                    </div>
                                                    <button type="submit" name="update_kereta" class="btn btn-primary">Update Kereta</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <hr class="my-4">

        <!-- Daftar Rute -->
        <h3 class="text-light mb-4">Daftar Rute</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Harga</th> <!-- Kolom harga -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rute = mysqli_fetch_assoc($rute_query)) : ?>
                        <tr>
                            <td><?= $rute['id']; ?></td>
                            <td><?= htmlspecialchars($rute['asal']); ?></td>
                            <td><?= htmlspecialchars($rute['tujuan']); ?></td>
                            <td>Rp <?= number_format($rute['harga'], 0, ',', '.'); ?></td> <!-- Menampilkan harga -->
                            <td>
                                <!-- Form Update Rute -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateRuteModal<?= $rute['id']; ?>">Update</button>
                                <a href="?hapus_rute=<?= $rute['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus rute ini?')">Hapus</a>

                                <!-- Modal Update Rute -->
                                <div class="modal fade" id="updateRuteModal<?= $rute['id']; ?>" tabindex="-1" aria-labelledby="updateRuteModalLabel<?= $rute['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateRuteModalLabel<?= $rute['id']; ?>">Update Rute</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_rute" value="<?= $rute['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="asal" class="form-label">Asal</label>
                                                        <input type="text" class="form-control" id="asal" name="asal" value="<?= htmlspecialchars($rute['asal']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tujuan" class="form-label">Tujuan</label>
                                                        <input type="text" class="form-control" id="tujuan" name="tujuan" value="<?= htmlspecialchars($rute['tujuan']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga" class="form-label">Harga</label>
                                                        <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($rute['harga']); ?>" required>
                                                    </div>
                                                    <button type="submit" name="update_rute" class="btn btn-primary">Update Rute</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <button onclick="window.print()" class="btn btn-success">Cetak Daftar Pengguna</button>
    <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
