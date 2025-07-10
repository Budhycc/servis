<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$data = $conn->query("SELECT r.*, k.nama_kendaraan FROM riwayat_servis r JOIN kendaraan k ON r.kendaraan_id = k.id WHERE k.user_id = $user_id");
?>
<h2>Riwayat Servis</h2>
<table border="1">
<tr><th>Kendaraan</th><th>Tanggal</th><th>KM</th><th>Jenis</th><th>Deskripsi</th><th>Biaya</th></tr>
<?php while ($row = $data->fetch_assoc()): ?>
<tr>
    <td><?= $row['nama_kendaraan'] ?></td>
    <td><?= $row['tanggal'] ?></td>
    <td><?= $row['km_servis'] ?></td>
    <td><?= $row['jenis_servis'] ?></td>
    <td><?= $row['deskripsi'] ?></td>
    <td><?= $row['biaya'] ?></td>
</tr>
<?php endwhile ?>
</table>
