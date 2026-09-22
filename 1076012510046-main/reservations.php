<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Reservasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background-color: #f4f4f4;">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistem Reservasi Klinik</a>
            <div class="d-flex text-white align-items-center">
                <a href="index.php" class="btn btn-sm btn-secondary me-2">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">Data Reservasi</h3>
        <div class="table-responsive bg-white rounded shadow-sm p-3">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Jadwal Terpilih</th>
                        <th>Waktu Transaksi</th>
                        <?php if ($user['role'] === 'pasien'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="<?php echo $user['role'] === 'pasien' ? '6' : '5'; ?>" class="text-center">Belum ada data reservasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td class="fw-bold text-success fs-5"><?php echo htmlspecialchars($r['nomor_antrian'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($r['nama_pasien']); ?></td>
                                <td><?php echo htmlspecialchars($r['nama_dokter']); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($r['hari']); ?></span> <?php echo htmlspecialchars($r['jam']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($r['tanggal_booking']); ?></td>
                                <?php if ($user['role'] === 'pasien'): ?>
                                    <td>
                                        <a href="index.php?action=ticket&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-info text-white">Lihat Tiket</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>