<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Outlet Baru</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; color: #343a40; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #495057; }
        a { text-decoration: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #e9ecef; }
        .action-link-approve { color: #28a745; font-weight: bold; }
        .action-link-approve:hover { color: #218838; }
        .action-link-reject { color: #dc3545; font-weight: bold; }
        .action-link-reject:hover { color: #c82333; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: .25rem; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Verifikasi Pendaftaran Outlet</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <hr>

        <!-- Menampilkan pesan sukses jika ada -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <h2>Daftar Outlet Menunggu Persetujuan</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Outlet</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Cek apakah ada outlet yang perlu diverifikasi -->
                <?php if (!empty($outlets)): ?>
                    <!-- Loop untuk setiap outlet -->
                    <?php foreach ($outlets as $outlet): ?>
                        <tr>
                            <td><?= esc($outlet['name']) ?></td>
                            <td><?= esc($outlet['address']) ?></td>
                            <td><?= esc($outlet['contact_phone']) ?></td>
                            <td>
                                <!-- Link untuk menyetujui outlet -->
                                <a href="/admin/verify/action/<?= $outlet['outlet_id'] ?>/verified" class="action-link-approve">Setujui</a>
                                &nbsp;|&nbsp;
                                <!-- Link untuk menolak outlet -->
                                <a href="/admin/verify/action/<?= $outlet['outlet_id'] ?>/rejected" class="action-link-reject">Tolak</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Pesan jika tidak ada data -->
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada outlet baru yang menunggu verifikasi saat ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>