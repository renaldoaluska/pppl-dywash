<?php // file: app/Views/customer/order/history.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <style>
        body { font-family: sans-serif; }
        .order-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        .status { font-weight: bold; padding: 5px; border-radius: 5px; color: white; }
        .status-diterima { background-color: #007bff; }
        .status-diproses { background-color: #ffc107; color: black; }
        .status-selesai { background-color: #28a745; }
        .status-diulas { background-color: #6c757d; }
        .status-ditolak { background-color: #dc3545; }
        .button { background-color: #17a2b8; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }

    </style>
</head>
<body>
    <h1>Riwayat Pesanan Saya</h1>
    <p><a href="../">Kembali ke Home</a></p>
    <hr>
    
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <p><strong>Outlet:</strong> <?= esc($order['outlet_name']) ?></p>
                <p><strong>Tanggal:</strong> <?= date('d F Y H:i', strtotime($order['order_date'])) ?></p>
                <p><strong>Total Bayar:</strong> Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
                <p><strong>Status:</strong> <span class="status status-<?= esc($order['status']) ?>"><?= ucfirst(esc($order['status'])) ?></span></p>

                <?php if ($order['status'] == 'selesai'): ?>
                    <hr>
                    <h4>Beri Ulasan:</h4>
                    <form action="/customer/review/store" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <label for="rating_<?= $order['order_id'] ?>">Rating (1-5):</label>
                        <input type="number" name="rating" id="rating_<?= $order['order_id'] ?>" min="1" max="5" required>
                        <br><br>
                        <label for="comment_<?= $order['order_id'] ?>">Komentar:</label><br>
                        <textarea name="comment" id="comment_<?= $order['order_id'] ?>" rows="3" style="width: 90%;"></textarea>
                        <br><br>
                        <button type="submit" class="button">Kirim Ulasan</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Anda belum memiliki riwayat pesanan.</p>
    <?php endif; ?>

</body>
</html>