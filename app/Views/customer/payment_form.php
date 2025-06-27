<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Pesanan</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #0056b3; }
        .order-summary { padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #fafafa; margin-bottom: 20px; }
        .payment-method { margin-bottom: 10px; }
        .button { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
    </style>
</head>
<body>
<div class="container">
    <h1>Konfirmasi Pembayaran</h1>
    
    <div class="order-summary">
        <h3>Ringkasan Pesanan #<?= $order['order_id'] ?></h3>
        <p><strong>Total Tagihan:</strong></p>
        <h2 style="color: #dc3545;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></h2>
    </div>

    <form action="/customer/payment/process" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
        <input type="hidden" name="amount" value="<?= $order['total_amount'] ?>">
        
        <h3>Pilih Metode Pembayaran:</h3>
        
        <div class="payment-method">
            <input type="radio" id="cod" name="payment_method" value="cod" required>
            <label for="cod"><strong>COD (Cash On Delivery)</strong><br><small>Bayar di tempat saat kurir tiba.</small></label>
        </div>
        
        <div class="payment-method">
            <input type="radio" id="transfer" name="payment_method" value="transfer" required>
            <label for="transfer"><strong>Transfer Bank</strong><br><small>Pembayaran akan diverifikasi oleh Admin. No. Rek: 123-456-7890 (Bank ABC)</small></label>
        </div>

        <div class="payment-method">
            <input type="radio" id="ewallet" name="payment_method" value="ewallet" required>
            <label for="ewallet"><strong>E-Wallet</strong><br><small>Pembayaran akan diverifikasi oleh Admin. Kirim ke: 081234567890 (GoPay/OVO)</small></label>
        </div>

        <br>
        <button type="submit" class="button">Konfirmasi Pembayaran</button>
    </form>

</div>
</body>
</html>
