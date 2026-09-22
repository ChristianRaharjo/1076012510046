<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Edit Dokter</title>
    <style>
        body {
            background-color: #f4f4f4;
        }

        .form-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php /** @var array{id: int, nama_dokter: string, spesialisasi: string, status: string, jadwal: array<string, string>} $doctor */ ?>
    <?php /** @var array{nama_dokter: string, spesialisasi: string, status: string, jadwal: array<string, string>} $form */ ?>
    <div class="container">
        <div class="form-wrapper">
            <h2 class="mb-4">Edit Dokter</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=edit&id=<?php echo $doctor['id']; ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Dokter</label>
                    <input type="text" class="form-control" name="nama_dokter" value="<?php echo htmlspecialchars($form['nama_dokter']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Spesialisasi</label>
                    <select class="form-select" name="spesialisasi" required>
                        <option value="">-- Pilih Spesialisasi --</option>
                        <?php foreach (DoctorModel::SPECIALTIES as $spesialisasi): ?>
                            <option value="<?php echo htmlspecialchars($spesialisasi); ?>" <?php echo $form['spesialisasi'] === $spesialisasi ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($spesialisasi); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Praktik</label>
                    <select class="form-select" name="status" required>
                        <?php foreach (DoctorModel::STATUSES as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>" <?php echo $form['status'] === $status ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam Praktik</label>
                    <div class="form-text mb-2">Kosongkan jika dokter tidak praktik di hari tersebut.</div>
                    <?php foreach (DoctorModel::DAYS as $day): ?>
                        <div class="row mb-2 align-items-center">
                            <label class="col-3 col-form-label"><?php echo $day; ?></label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="jadwal[<?php echo $day; ?>]" value="<?php echo htmlspecialchars($form['jadwal'][$day]); ?>" placeholder="Contoh: 08:00 - 12:00">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>

</html>