<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Sistem Klinik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style> body { background-color: #f4f4f4; } </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Panel Admin Klinik</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3">Halo, <?php echo htmlspecialchars($user['nama']); ?>!</span>
                <a href="index.php?action=reservations" class="btn btn-sm btn-info me-2">Semua Reservasi</a>
                <a href="index.php?action=logout" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <a href="index.php?action=add" class="btn btn-success mb-3">Tambah Dokter</a>

        <div class="table-responsive">
            <table class="table table-striped bg-white rounded shadow-sm">
                <thead>
                    <tr>
                        <th>No</th><th>Nama Dokter</th><th>Spesialisasi</th><th>Status</th>
                        <?php foreach (DoctorModel::DAYS as $day): ?>
                            <th><?php echo $day; ?></th>
                        <?php endforeach; ?>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doctors)): ?>
                        <tr><td colspan="12" class="text-center">Belum ada data dokter.</td></tr>
                    <?php else: ?>
                        <?php foreach ($doctors as $doc): ?>
                            <?php $isAvailable = ($doc['status'] ?? '') === DoctorModel::STATUS_AVAILABLE; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($doc['no']); ?></td>
                                <td><?php echo htmlspecialchars($doc['nama_dokter']); ?></td>
                                <td><?php echo htmlspecialchars($doc['spesialisasi']); ?></td>
                                <td>
                                    <span class="badge <?php echo $isAvailable ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $isAvailable ? 'Available' : 'Not Available'; ?>
                                    </span>
                                </td>
                                <?php foreach (DoctorModel::DAYS as $day): ?>
                                    <?php $jam = $doc['jadwal'][$day] ?? ''; ?>
                                    <td class="text-nowrap"><?php echo $jam !== '' ? htmlspecialchars($jam) : '<span class="text-muted">-</span>'; ?></td>
                                <?php endforeach; ?>
                                <td class="text-nowrap">
                                    <a href="index.php?action=edit&id=<?php echo $doc['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="index.php?action=delete&id=<?php echo $doc['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>