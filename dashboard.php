<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h2>Dashboard</h2>
<p>Selamat datang!</p>
<ul>
    <li><a href="kendaraan.php">Data Kendaraan</a></li>
    <li><a href="tambah_servis.php">Tambah Servis</a></li>
    <li><a href="riwayat.php">Riwayat Servis</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</body>
</html>
