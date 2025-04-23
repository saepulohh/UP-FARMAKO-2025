<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'penumpang') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Ambil data kereta dan rute dari database
$kereta_query = mysqli_query($koneksi, "SELECT * FROM kereta ORDER BY id ASC");
$rute_query = mysqli_query($koneksi, "SELECT * FROM rute ORDER BY asal, tujuan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penumpang = mysqli_real_escape_string($koneksi, $_POST['nama_penumpang']);
    $kereta = mysqli_real_escape_string($koneksi, $_POST['kereta']);
    $asal = mysqli_real_escape_string($koneksi, $_POST['asal']);
    $tujuan = mysqli_real_escape_string($koneksi, $_POST['tujuan']);
    $tanggal = $_POST['tanggal_berangkat'];
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $harga = intval($_POST['harga']);

    $insert = mysqli_query($koneksi, "INSERT INTO pemesanan 
        (user_id, nama_penumpang, kereta, asal, tujuan, tanggal_berangkat, kelas, harga, status, created_at)
        VALUES ('$user_id', '$nama_penumpang', '$kereta', '$asal', '$tujuan', '$tanggal', '$kelas', '$harga', 'belum bayar', NOW())");

    $message = $insert ? "Tiket berhasil dipesan!" : "Gagal memesan tiket.";
}

$data_pemesanan = mysqli_query($koneksi, "SELECT * FROM pemesanan WHERE user_id='$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penumpang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #373B44, #4286f4); min-height: 100vh; }
        .card { border-radius: 1rem; margin-top: 30px; animation: slideIn 1s ease; }
        @keyframes slideIn { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .table thead { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="card shadow p-4">
        <h3 class="text-center text-primary">Dashboard Penumpang</h3>
        <p>Selamat datang, <strong><?php echo $username; ?></strong>!</p>

        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-light">Nama Penumpang</label>
                    <input type="text" name="nama_penumpang" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Kereta</label>
                    <select name="kereta" class="form-select" required>
                        <option value="">Pilih Kereta</option>
                        <?php while ($kereta = mysqli_fetch_assoc($kereta_query)) : ?>
                            <option value="<?= $kereta['nama_kereta']; ?>"><?= htmlspecialchars($kereta['nama_kereta']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Asal</label>
                    <select name="asal" class="form-select" id="asal" required>
                        <option value="">Pilih Asal</option>
                        <?php mysqli_data_seek($rute_query, 0); while ($rute = mysqli_fetch_assoc($rute_query)) : ?>
                            <option value="<?= $rute['asal']; ?>"><?= htmlspecialchars($rute['asal']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Tujuan</label>
                    <select name="tujuan" class="form-select" id="tujuan" required>
                        <option value="">Pilih Tujuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Tanggal Berangkat</label>
                    <input type="date" name="tanggal_berangkat" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Kelas</label>
                    <input type="text" name="kelas" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-light">Harga</label>
                    <input type="number" name="harga" class="form-control" required min="0">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Pesan Tiket</button>
                </div>
            </div>
        </form>

        <hr class="my-4">

        <h5 class="text-light">Daftar Pemesanan Anda</h5>
        <div class="table-responsive mt-3">
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
                            <td>
                                <?= $row['status'] === 'sudah bayar' ? '<span class="badge bg-success">Sudah Bayar</span>' : '<span class="badge bg-warning text-dark">Belum Bayar</span>'; ?>
                            </td>
                            <td><?= $row['created_at']; ?></td>
                            <td>
                                <?php if ($row['status'] !== 'sudah bayar') : ?>
                                    <form method="GET" action="bayar.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Lanjut ke halaman pembayaran?')">Bayar</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

<script>
    // Mengupdate tujuan berdasarkan asal
    document.getElementById('asal').addEventListener('change', function() {
        var asal = this.value;
        var tujuanSelect = document.getElementById('tujuan');
        tujuanSelect.innerHTML = '<option value="">Pilih Tujuan</option>';

        fetch('get_tujuan.php?asal=' + encodeURIComponent(asal))
            .then(response => response.json())
            .then(data => {
                data.forEach(function(tujuan) {
                    var option = document.createElement('option');
                    option.value = tujuan;
                    option.text = tujuan;
                    tujuanSelect.appendChild(option);
                });
            });
    });
</script>
</body>
</html>
