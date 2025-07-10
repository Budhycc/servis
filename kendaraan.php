<?php
session_start();
include('includes/db.php'); // Pastikan db.php tidak output apa pun sebelum header
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ''; // Untuk pesan sukses atau error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nopol = $_POST['nopol'];
    $tahun = $_POST['tahun'];
    $km = $_POST['km'];

    $stmt = $conn->prepare("INSERT INTO kendaraan (user_id, nama_kendaraan, nomor_polisi, tahun, km_awal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $nama, $nopol, $tahun, $km);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success" role="alert">Kendaraan berhasil ditambahkan!</div>';
    } else {
        $message = '<div class="alert alert-danger" role="alert">Gagal menambahkan kendaraan.</div>';
    }
    $stmt->close();
}
$data_kendaraan = $conn->query("SELECT id, nama_kendaraan, nomor_polisi, tahun, km_awal FROM kendaraan WHERE user_id = $user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan - Aplikasi Servis Kendaraan</title>
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
                        <a class="nav-link active" aria-current="page" href="kendaraan.php">Kendaraan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah_servis.php">Tambah Servis</a>
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
        <?php echo $message; // Tampilkan pesan sukses/error ?>
        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h2 class="mb-0 h5">Tambah Kendaraan Baru</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Kendaraan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Avanza, Beat" required>
                            </div>
                            <div class="mb-3">
                                <label for="nopol" class="form-label">Nomor Polisi</label>
                                <input type="text" class="form-control" id="nopol" name="nopol" placeholder="Contoh: B 1234 XYZ" required>
                            </div>
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Contoh: 2020" required min="1900" max="<?php echo date('Y'); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="km" class="form-label">KM Awal</label>
                                <input type="number" class="form-control" id="km" name="km" placeholder="Kilometer saat ini" required min="0">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Tambah Kendaraan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="mb-0 h5">Daftar Kendaraan Anda</h2>
                    </div>
                    <div class="card-body p-0">
                        <?php if ($data_kendaraan && $data_kendaraan->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Kendaraan</th>
                                        <th>Nomor Polisi</th>
                                        <th>Tahun</th>
                                        <th>KM Awal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $data_kendaraan->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nama_kendaraan']) ?></td>
                                        <td><?= htmlspecialchars($row['nomor_polisi']) ?></td>
                                        <td><?= htmlspecialchars($row['tahun']) ?></td>
                                        <td><?= htmlspecialchars(number_format($row['km_awal'])) ?> KM</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <p class="text-center p-3">Anda belum menambahkan kendaraan.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>
