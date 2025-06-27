<?php // file: app/Views/outlet/my_outlets/index.php (VIEW BARU) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Outlet Saya</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #495057; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #e9ecef; }
        .button { padding: 8px 12px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-add { background-color: #28a745; margin-bottom: 20px; display: inline-block; }
        .btn-edit { background-color: #ffc107; color: black; }
        .status { font-weight: bold; padding: 5px; border-radius: 5px; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-verified { background-color: #28a745; }
        .status-rejected { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola Outlet Saya</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <a href="/outlet/create" class="button btn-add">Tambah Outlet Baru</a>
        <hr>
        
        <h2>Daftar Outlet Anda</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Outlet</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($outlets)): ?>
                    <?php foreach ($outlets as $outlet): ?>
                        <tr>
                            <td><?= esc($outlet['name']) ?></td>
                            <td><?= esc($outlet['address']) ?></td>
                            <td><span class="status status-<?= esc($outlet['status']) ?>"><?= ucfirst(esc($outlet['status'])) ?></span></td>
                            <td>
                                <a href="/outlet/edit/<?= $outlet['outlet_id'] ?>" class="button btn-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Anda belum mendaftarkan outlet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>