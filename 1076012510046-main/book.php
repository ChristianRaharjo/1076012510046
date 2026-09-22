<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background-color: #f4f4f4;">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-body p-4">
                <h3 class="mb-4">Buat Reservasi</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?action=book&id=<?php echo $doctor['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Pasien</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Dokter Pilihan</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($doctor['nama_dokter'] . ' - ' . $doctor['spesialisasi']); ?>" disabled>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Hari Praktik</label>
                        <select name="hari" class="form-select" required>
                            <option value="">-- Pilih Jadwal Tersedia --</option>
                            <?php foreach (DoctorModel::DAYS as $day): ?>
                                <?php if (!empty($doctor['jadwal'][$day])): ?>
                                    <option value="<?php echo $day; ?>">
                                        <?php echo $day; ?> (<?php echo htmlspecialchars($doctor['jadwal'][$day]); ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2">Konfirmasi Booking</button>
                    <a href="index.php" class="btn btn-secondary w-100">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>