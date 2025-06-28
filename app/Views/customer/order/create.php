<?php // file: app/Views/customer/order/create.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Buat Pesanan Baru</title>
     <style>
        body { font-family: sans-serif; }
        .container { max-width: 600px; margin: auto; }
        .service-item { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee; }
        .button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
<?= $this->include('layout/top_nav') ?>
<div class="container">
    <h1>Pesan di Outlet: <?= esc($outlet['name']) ?></h1>
    <p><a href="../">Kembali ke daftar outlet</a></p>
    <hr>
    
    <form action="/customer/order/store" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?>">
        
        <h3>Pilih Layanan:</h3>
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <div class="service-item">
                    <div>
                        <strong><?= esc($service['name']) ?></strong><br>
                        <span>Rp <?= number_format($service['price'], 0, ',', '.') ?> / <?= esc($service['unit']) ?></span>
                    </div>
                    <div>
                        <label for="service_<?= $service['service_id'] ?>">Jumlah:</label>
                        <input type="number" name="services[<?= $service['service_id'] ?>]" id="service_<?= $service['service_id'] ?>" min="0" value="0" style="width: 60px;">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Outlet ini belum memiliki layanan.</p>
        <?php endif; ?>

        <br>
        <label for="customer_notes">Catatan Tambahan (opsional):</label><br>
        <textarea name="customer_notes" id="customer_notes" rows="4" style="width: 100%;"></textarea>
        <br><br>
        
        <button type="submit" class="button">Buat Pesanan</button>
    </form>
</div>
<?= $this->include('layout/bottom_nav') ?>
<?= $this->include('layout/footer') ?>