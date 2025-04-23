include 'koneksi.php';
$id = $_GET['id'];
$conn->query("DELETE FROM jadwal_kereta WHERE id = $id");
header("Location: jadwal.php");
