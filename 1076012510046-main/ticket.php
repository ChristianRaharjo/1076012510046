<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background-color: #f4f4f4;">
    <div class="container mt-5">
        <div class="card shadow mx-auto text-center" style="max-width: 400px; border-top: 5px solid #198754;">
            <div class="card-body p-5">
                <h5 class="text-muted mb-1">Klinik Sehat</h5>
                <h3 class="mb-4">Tiket Antrian</h3>
                
                <div class="display-3 fw-bold text-success mb-3">
                    <?php echo htmlspecialchars($ticket['nomor_antrian'] ?? '-'); ?>
                </div>
                
                <hr>
                
                <div class="text-start mt-4">
                    <p class="mb-1"><strong>Nama Pasien:</strong> <span class="float-end"><?php echo htmlspecialchars($ticket['nama_pasien']); ?></span></p>
                    <p class="mb-1"><strong>Dokter:</strong> <span class="float-end"><?php echo htmlspecialchars($ticket['nama_dokter']); ?></span></p>
                    <p class="mb-1"><strong>Jadwal:</strong> <span class="float-end"><?php echo htmlspecialchars($ticket['hari']); ?>, <?php echo htmlspecialchars($ticket['jam']); ?></span></p>
                    <p class="mb-1"><strong>Waktu Booking:</strong> <span class="float-end"><?php echo htmlspecialchars($ticket['tanggal_booking']); ?></span></p>
                </div>
                
                <div class="mt-5">
                    <a href="index.php?action=reservations" class="btn btn-outline-primary w-100 mb-2">Lihat Riwayat Reservasi</a>
                    <a href="index.php" class="btn btn-secondary w-100">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>