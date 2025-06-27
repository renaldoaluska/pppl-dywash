<?php // file: app/Views/outlet/services/index.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Layanan Outlet</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #495057; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #e9ecef; }
        .button { padding: 8px 12px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-add { background-color: #28a745; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola Layanan Anda</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <a href="/outlet/services/create" class="button btn-add">Tambah Layanan Baru</a>
        <hr>
        
        <table>
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?= esc($service['name']) ?></td>
                            <td>Rp <?= number_format($service['price'], 0, ',', '.') ?></td>
                            <td><?= esc($service['unit']) ?></td>
                            <td>
                                <a href="/outlet/services/edit/<?= $service['service_id'] ?>" class="button btn-edit">Edit</a>
                                <a href="/outlet/services/delete/<?= $service['service_id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')" class="button btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Anda belum memiliki layanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
