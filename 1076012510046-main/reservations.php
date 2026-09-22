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
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID Booking</th>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Jadwal Terpilih</th>
                        <th>Waktu Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr><td colspan="5" class="text-center">Belum ada data reservasi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td>#BK-<?php echo str_pad($r['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($r['nama_pasien']); ?></td>
                                <td><?php echo htmlspecialchars($r['nama_dokter']); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($r['hari']); ?></span> <?php echo htmlspecialchars($r['jam']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($r['tanggal_booking']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>