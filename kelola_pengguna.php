<?php
session_start();
require_once 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'petugas')) {
    header("Location: login.php");
    exit;
}

// Menampilkan informasi pengguna yang sedang login
$username_user = $_SESSION['username'];
$role_user = $_SESSION['role'];

// Menambahkan pengguna baru
if (isset($_POST['tambah_pengguna'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = $_POST['role']; // Mengambil role yang dipilih dari form

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menambah pengguna baru
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
    
    // Insert ke database
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_pengguna.php");
        exit;
    } else {
        $error_message = "Gagal menambahkan pengguna. Silakan coba lagi.";
    }
}

// Edit pengguna jika tombol edit ditekan
if (isset($_POST['edit_pengguna'])) {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = $_POST['role']; // Ambil role yang dipilih saat edit

    $password_query = $password ? ", password = '" . password_hash($password, PASSWORD_DEFAULT) . "'" : "";
    
    // Update data pengguna
    $query = "UPDATE users SET username = '$username', email = '$email', role = '$role' $password_query WHERE id = '$id'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_pengguna.php");
        exit;
    } else {
        $error_message = "Gagal memperbarui data pengguna.";
    }
}

// Hapus pengguna jika tombol hapus ditekan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus pemesanan yang terkait dengan pengguna ini terlebih dahulu (ubah nama kolom jika diperlukan)
    // Pastikan kolom penghubung dengan tabel pemesanan benar, misalnya 'user_id'
    mysqli_query($koneksi, "DELETE FROM pemesanan WHERE user_id = '$id'");  // Ganti 'user_id' jika nama kolom berbeda

    // Hapus pengguna
    mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");

    header("Location: kelola_pengguna.php");
    exit;
}

// Proses pencarian
$search_query = "";
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($koneksi, $_POST['search']);
    $search_query = "WHERE username LIKE '%$search_term%' OR email LIKE '%$search_term%' OR role LIKE '%$search_term%'";
}

// Ambil data pengguna dengan pencarian
$data_pengguna = mysqli_query($koneksi, "SELECT * FROM users $search_query ORDER BY id ASC");

// Jika edit pengguna dipilih, ambil data pengguna untuk diubah
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id' LIMIT 1");
    $user = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Aplikasi Pemesanan Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h3 class="text-light mb-4">Kelola Pengguna</h3>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Menampilkan informasi pengguna yang sedang login -->
        <div class="alert alert-info">
            <p><strong>Selamat datang, <?= htmlspecialchars($username_user); ?>!</strong></p>
            <p>Role Anda: <?= htmlspecialchars($role_user); ?></p>
        </div>

        <!-- Form Pencarian -->
        <form method="POST" action="" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari pengguna..." value="<?= isset($search_term) ? htmlspecialchars($search_term) : ''; ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <!-- Form Tambah Pengguna -->
        <?php if (!isset($_GET['id'])): ?>
        <form method="POST" action="">
            <h4 class="mb-3">Tambah Pengguna Baru</h4>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="penumpang">Penumpang</option>
                </select>
            </div>
            <button type="submit" name="tambah_pengguna" class="btn btn-primary">Tambah Pengguna</button>
        </form>
        <hr>
        <?php endif; ?>

        <!-- Form Edit Pengguna -->
        <?php if (isset($_GET['id'])): ?>
        <form method="POST" action="">
            <h4 class="mb-3">Edit Pengguna</h4>
            <input type="hidden" name="id" value="<?= $user['id']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengganti)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                    <option value="penumpang" <?= $user['role'] === 'penumpang' ? 'selected' : ''; ?>>Penumpang</option>
                </select>
            </div>
            <button type="submit" name="edit_pengguna" class="btn btn-primary">Update Pengguna</button>
        </form>
        <hr>
        <?php endif; ?>

        <!-- Tabel Pengguna -->
        <h4 class="mb-3">Daftar Pengguna</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($data_pengguna)) : ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['role']); ?></td>
                            <td>
                                <a href="?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol untuk Mencetak -->
        <button onclick="window.print()" class="btn btn-success">Cetak Daftar Pengguna</button>

        <!-- Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
