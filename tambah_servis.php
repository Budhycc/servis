<?php
session_start();
include('includes/db.php'); // Pastikan db.php tidak output apa pun sebelum header
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ''; // Untuk pesan sukses atau error

// Ambil data kendaraan untuk dropdown
$kendaraan_result = $conn->query("SELECT id, nama_kendaraan, nomor_polisi FROM kendaraan WHERE user_id = $user_id ORDER BY nama_kendaraan ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kendaraan_id = $_POST['kendaraan_id'];
    $tgl = $_POST['tanggal'];
    $km = $_POST['km'];
    $jenis_servis = $_POST['jenis_servis']; // Kolom baru ditambahkan
    $deskripsi = $_POST['deskripsi'];
    $biaya = $_POST['biaya']; // Kolom baru ditambahkan

    // Validasi dasar
    if (empty($kendaraan_id) || empty($tgl) || empty($km) || empty($jenis_servis) || !is_numeric($biaya) || $biaya < 0) {
        $message = '<div class="alert alert-danger" role="alert">Semua field wajib diisi dan biaya harus angka positif.</div>';
    } else {
        // Query diganti ke riwayat_servis karena jadwal_servis tidak ada kolom biaya dan jenis_servis
        $stmt = $conn->prepare("INSERT INTO riwayat_servis (kendaraan_id, tanggal, km_servis, jenis_servis, deskripsi, biaya) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissi", $kendaraan_id, $tgl, $km, $jenis_servis, $deskripsi, $biaya);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success" role="alert">Data servis berhasil ditambahkan ke riwayat!</div>';
            // Kosongkan form atau redirect, sesuai kebutuhan
        } else {
            $message = '<div class="alert alert-danger" role="alert">Gagal menambahkan data servis: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Servis - Aplikasi Servis Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Aplikasi Servis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kendaraan.php">Kendaraan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="tambah_servis.php">Tambah Servis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">Riwayat Servis</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php echo $message; // Tampilkan pesan sukses/error ?>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="mb-0 h5">Tambah Riwayat Servis Baru</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="kendaraan_id" class="form-label">Pilih Kendaraan</label>
                                <select class="form-select" id="kendaraan_id" name="kendaraan_id" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    <?php
                                    if ($kendaraan_result && $kendaraan_result->num_rows > 0) {
                                        while ($row = $kendaraan_result->fetch_assoc()):
                                    ?>
                                    <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nama_kendaraan']) ?> - <?= htmlspecialchars($row['nomor_polisi']) ?></option>
                                    <?php
                                        endwhile;
                                    } else {
                                        echo '<option value="">Tidak ada kendaraan terdaftar</option>';
                                    }
                                    // Reset pointer jika mau dipakai lagi atau tutup koneksi jika sudah selesai
                                    if ($kendaraan_result) $kendaraan_result->data_seek(0);
                                    ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Servis</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="km" class="form-label">KM Servis</label>
                                    <input type="number" class="form-control" id="km" name="km" placeholder="KM saat servis" required min="0">
                                </div>
                            </div>
                             <div class="mb-3">
                                <label for="jenis_servis" class="form-label">Jenis Servis</label>
                                <input type="text" class="form-control" id="jenis_servis" name="jenis_servis" placeholder="Contoh: Ganti Oli, Servis Rutin" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi / Catatan Servis</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Catatan detail mengenai servis"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="biaya" class="form-label">Biaya Servis (Rp)</label>
                                <input type="number" class="form-control" id="biaya" name="biaya" placeholder="Contoh: 150000" required min="0">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Data Servis</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>
