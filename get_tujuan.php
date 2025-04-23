<?php
require_once 'koneksi.php';

if (isset($_GET['asal'])) {
    $asal = mysqli_real_escape_string($koneksi, $_GET['asal']);
    
    // Ambil tujuan berdasarkan asal yang dipilih
    $query = mysqli_query($koneksi, "SELECT DISTINCT tujuan FROM rute WHERE asal = '$asal'");
    
    $tujuan = [];
    
    while ($row = mysqli_fetch_assoc($query)) {
        $tujuan[] = $row['tujuan'];
    }
    
    // Mengembalikan data tujuan dalam format JSON
    echo json_encode($tujuan);
} else {
    // Jika asal tidak tersedia
    echo json_encode([]);
}
?>
