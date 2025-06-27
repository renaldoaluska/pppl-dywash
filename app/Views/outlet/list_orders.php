<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan Masuk</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; vertical-align: middle; }
        th { background-color: #e9ecef; }
        .status-diterima { background-color: #cfe2ff; }
        .status-diproses { background-color: #fff3cd; }
        .status-selesai { background-color: #d1e7dd; }
        .status-diulas { background-color: #e2e3e5; }
        .status-ditolak { background-color: #f8d7da; }
        select, button { padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
        button { cursor: pointer; background-color: #007bff; color: white; border: none; }
        button:hover { background-color: #0056b3; }
        .update-form { display: flex; gap: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Pesanan Masuk</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <hr>
        
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Nama Outlet</th>
                    <th>Nama Customer</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="status-<?= esc($order['status']) ?>">
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= esc($order['outlet_name']) ?></td>
                            <td><?= esc($order['customer_name']) ?></td>
                            <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                            <td><strong><?= ucfirst(esc($order['status'])) ?></strong></td>
                            <td>
                                <?php // Form hanya ditampilkan jika statusnya belum final (bukan diulas atau ditolak) ?>
                                <?php if (!in_array($order['status'], ['diulas', 'ditolak'])): ?>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="update-form">
                                    <?= csrf_field() ?>
                                    <select name="status">
                                        <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="ditolak" <?= $order['status'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada pesanan yang masuk di outlet terverifikasi Anda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
