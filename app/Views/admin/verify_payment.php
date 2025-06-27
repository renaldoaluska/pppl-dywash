<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pembayaran</title>
    <style>
        /* ... styling sama seperti view admin lainnya ... */
        body { font-family: sans-serif; background-color: #f8f9fa; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #e9ecef; }
        .action-link-approve { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verifikasi Pembayaran Masuk</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <hr>

        <h2>Daftar Pembayaran Menunggu Konfirmasi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Nama Customer</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td>#<?= $payment['payment_id'] ?></td>
                            <td><?= esc($payment['customer_name']) ?></td>
                            <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
                            <td><?= esc($payment['payment_method']) ?></td>
                            <td>
                                <a href="/admin/payments/verify/action/<?= $payment['payment_id'] ?>" class="action-link-approve">Verifikasi Pembayaran Ini</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada pembayaran yang menunggu verifikasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
