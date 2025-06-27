<?php // file: app/Views/outlet/services/form.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $service ? 'Edit' : 'Tambah' ?> Layanan</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $service ? 'Edit' : 'Tambah' ?> Layanan Baru</h1>
        <p><a href="/outlet/services">Kembali ke Daftar Layanan</a></p>
        <hr>
        
        <form action="/outlet/services/store" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="service_id" value="<?= $service['service_id'] ?? '' ?>">

            <div class="form-group">
                <label for="name">Nama Layanan (Contoh: Cuci Kering Kiloan)</label>
                <input type="text" id="name" name="name" value="<?= esc($service['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" id="price" name="price" value="<?= esc($service['price'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="unit">Satuan (Contoh: Kg, Pcs, Setel)</label>
                <input type="text" id="unit" name="unit" value="<?= esc($service['unit'] ?? '') ?>" required>
            </div>

            <button type="submit" class="button">Simpan Layanan</button>
        </form>
    </div>
</body>
</html>