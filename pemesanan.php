<?php
require_once 'koneksi.php';
session_start();

// Inisialisasi variabel kosong untuk form
$id = "";
$nama_penumpang = "";
$kereta = "";
$asal = "";
$tujuan = "";
$tanggal = "";
$kelas = "";

// === HANDLE SIMPAN ===
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama_penumpang = $_POST['nama_penumpang'];
    $kereta = $_POST['kereta'];
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal = $_POST['tanggal_berangkat'];
    $kelas = $_POST['kelas'];

    if ($id == "") {
        // INSERT
        $query = "INSERT INTO pemesanan (nama_penumpang, kereta, asal, tujuan, tanggal_berangkat, kelas)
                  VALUES ('$nama_penumpang', '$kereta', '$asal', '$tujuan', '$tanggal', '$kelas')";
    } else {
        // UPDATE
        $query = "UPDATE pemesanan SET 
                  nama_penumpang='$nama_penumpang', kereta='$kereta', asal='$asal', 
                  tujuan='$tujuan', tanggal_berangkat='$tanggal', kelas='$kelas' 
                  WHERE id=$id";
    }

    mysqli_query($koneksi, $query);
    header("Location: pemesanan.php");
    exit;
}

// === HANDLE HAPUS ===
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pemesanan WHERE id=$id");
    header("Location: pemesanan.php");
    exit;
}

// === HANDLE EDIT ===
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pemesanan WHERE id = $id"));

    // Isi variabel form
    $nama_penumpang = $data['nama_penumpang'];
    $kereta = $data['kereta'];
    $asal = $data['asal'];
    $tujuan = $data['tujuan'];
    $tanggal = $data['tanggal_berangkat'];
    $kelas = $data['kelas'];
}

// === AMBIL SEMUA DATA UNTUK TABEL ===
$result = mysqli_query($koneksi, "SELECT * FROM pemesanan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center">CRUD Pemesanan Tiket Kereta</h2>

    <!-- Form Tambah/Edit -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <?= $id == "" ? "Tambah Pemesanan" : "Edit Pemesanan" ?>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Penumpang</label>
                        <input type="text" name="nama_penumpang" class="form-control" value="<?= $nama_penumpang ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Kereta</label>
                        <input type="text" name="kereta" class="form-control" value="<?= $kereta ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Asal</label>
                        <input type="text" name="asal" class="form-control" value="<?= $asal ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tujuan</label>
                        <input type="text" name="tujuan" class="form-control" value="<?= $tujuan ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berangkat</label>
                        <input type="date" name="tanggal_berangkat" class="form-control" value="<?= $tanggal ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <select name="kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="Ekonomi" <?= $kelas == 'Ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
                            <option value="Bisnis" <?= $kelas == 'Bisnis' ? 'selected' : '' ?>>Bisnis</option>
                            <option value="Eksekutif" <?= $kelas == 'Eksekutif' ? 'selected' : '' ?>>Eksekutif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button name="simpan" class="btn btn-success">Simpan</button>
                    <a href="pemesanan.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-header bg-dark text-white">Daftar Pemesanan</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kereta</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_penumpang'] ?></td>
                        <td><?= $row['kereta'] ?></td>
                        <td><?= $row['asal'] ?></td>
                        <td><?= $row['tujuan'] ?></td>
                        <td><?= $row['tanggal_berangkat'] ?></td>
                        <td><?= $row['kelas'] ?></td>
                        <td>
                            <a href="pemesanan.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="pemesanan.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
