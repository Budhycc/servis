<?php
session_start();
include('includes/db.php'); // Pastikan db.php tidak output apa pun sebelum header
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Query diurutkan berdasarkan tanggal servis terbaru
$data_riwayat = $conn->query("SELECT r.id, k.nama_kendaraan, k.nomor_polisi, r.tanggal, r.km_servis, r.jenis_servis, r.deskripsi, r.biaya
                             FROM riwayat_servis r
                             JOIN kendaraan k ON r.kendaraan_id = k.id
                             WHERE k.user_id = $user_id
                             ORDER BY r.tanggal DESC, r.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Servis - Aplikasi Servis Kendaraan</title>
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
                        <a class="nav-link" href="tambah_servis.php">Tambah Servis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="riwayat.php">Riwayat Servis</a>
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
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 h5">Riwayat Servis Kendaraan</h2>
                <a href="tambah_servis.php" class="btn btn-success btn-sm">Tambah Servis Baru</a>
            </div>
            <div class="card-body p-0">
                <?php if ($data_riwayat && $data_riwayat->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kendaraan</th>
                                <th>Tanggal</th>
                                <th>KM Servis</th>
                                <th>Jenis Servis</th>
                                <th>Deskripsi</th>
                                <th class="text-end">Biaya (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $data_riwayat->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_kendaraan']) ?> <small class="text-muted d-block"><?= htmlspecialchars($row['nomor_polisi']) ?></small></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal']))) ?></td>
                                <td><?= htmlspecialchars(number_format($row['km_servis'])) ?> KM</td>
                                <td><?= htmlspecialchars($row['jenis_servis']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                                <td class="text-end"><?= htmlspecialchars(number_format($row['biaya'], 0, ',', '.')) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p class="text-center p-4">Belum ada riwayat servis yang tercatat. <a href="tambah_servis.php">Tambahkan sekarang</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>
