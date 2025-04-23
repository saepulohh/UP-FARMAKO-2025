<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tiket"; // Ganti sesuai nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
