<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nopol = $_POST['nopol'];
    $tahun = $_POST['tahun'];
    $km = $_POST['km'];
    $stmt = $conn->prepare("INSERT INTO kendaraan (user_id, nama_kendaraan, nomor_polisi, tahun, km_awal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $nama, $nopol, $tahun, $km);
    $stmt->execute();
}
$data = $conn->query("SELECT * FROM kendaraan WHERE user_id = $user_id");
?>
<h2>Kendaraan Anda</h2>
<form method="POST">
    <input name="nama" placeholder="Nama Kendaraan" required><br>
    <input name="nopol" placeholder="Nomor Polisi" required><br>
    <input name="tahun" type="number" placeholder="Tahun" required><br>
    <input name="km" type="number" placeholder="KM Awal" required><br>
    <button type="submit">Tambah</button>
</form>
<table border="1">
    <tr><th>Nama</th><th>Plat</th><th>Tahun</th><th>KM Awal</th></tr>
    <?php while ($row = $data->fetch_assoc()): ?>
    <tr>
        <td><?= $row['nama_kendaraan'] ?></td>
        <td><?= $row['nomor_polisi'] ?></td>
        <td><?= $row['tahun'] ?></td>
        <td><?= $row['km_awal'] ?></td>
    </tr>
    <?php endwhile ?>
</table>
