<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$kendaraan = $conn->query("SELECT * FROM kendaraan WHERE user_id = $user_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kendaraan_id = $_POST['kendaraan_id'];
    $tgl = $_POST['tanggal'];
    $km = $_POST['km'];
    $desc = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO jadwal_servis (kendaraan_id, tanggal_servis, km_servis, deskripsi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $kendaraan_id, $tgl, $km, $desc);
    $stmt->execute();
}
?>
<h2>Tambah Jadwal Servis</h2>
<form method="POST">
    <select name="kendaraan_id">
        <?php while ($row = $kendaraan->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= $row['nama_kendaraan'] ?> - <?= $row['nomor_polisi'] ?></option>
        <?php endwhile ?>
    </select><br>
    <input type="date" name="tanggal" required><br>
    <input type="number" name="km" placeholder="KM Servis" required><br>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br>
    <button type="submit">Simpan</button>
</form>
