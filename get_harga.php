<?php
require_once 'koneksi.php';

if (isset($_GET['asal']) && isset($_GET['tujuan']) && isset($_GET['kelas'])) {
    $asal = mysqli_real_escape_string($koneksi, $_GET['asal']);
    $tujuan = mysqli_real_escape_string($koneksi, $_GET['tujuan']);
    $kelas = mysqli_real_escape_string($koneksi, $_GET['kelas']);

    // Query untuk mendapatkan harga berdasarkan asal, tujuan, dan kelas
    $query = mysqli_query($koneksi, "SELECT harga FROM rute WHERE asal = '$asal' AND tujuan = '$tujuan' AND kelas = '$kelas'");

    if ($row = mysqli_fetch_assoc($query)) {
        echo json_encode(['harga' => $row['harga']]);
    } else {
        echo json_encode(['harga' => 0]); // Jika tidak ada harga, kembalikan 0
    }
} else {
    echo json_encode(['harga' => 0]); // Jika parameter tidak lengkap
}
?>
