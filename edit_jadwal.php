include 'koneksi.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM jadwal_kereta WHERE id = $id");
$data = $result->fetch_assoc();
?>

<form method="post">
    <h2>Edit Jadwal</h2>
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <label>Nama Kereta:</label><br>
    <input type="text" name="nama_kereta" value="<?= $data['nama_kereta'] ?>"><br>
    <label>Asal:</label><br>
    <input type="text" name="asal" value="<?= $data['asal'] ?>"><br>
    <label>Tujuan:</label><br>
    <input type="text" name="tujuan" value="<?= $data['tujuan'] ?>"><br>
    <label>Tanggal:</label><br>
    <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>"><br>
    <label>Jam:</label><br>
    <input type="time" name="jam" value="<?= $data['jam'] ?>"><br><br>
    <button type="submit" name="update">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE jadwal_kereta SET nama_kereta=?, asal=?, tujuan=?, tanggal=?, jam=? WHERE id=?");
    $stmt->bind_param("sssssi", $_POST['nama_kereta'], $_POST['asal'], $_POST['tujuan'], $_POST['tanggal'], $_POST['jam'], $_POST['id']);
    $stmt->execute();
    header("Location: jadwal.php");
}
?>
