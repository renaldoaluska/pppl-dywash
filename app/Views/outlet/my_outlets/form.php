<?php // file: app/Views/outlet/my_outlets/form.php (VIEW BARU - Menggantikan register_form.php) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $outlet ? 'Edit' : 'Tambah' ?> Outlet</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; }
        .container { max-width: 700px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $outlet ? 'Edit Outlet' : 'Tambah Outlet Baru' ?></h1>
        <p><a href="/outlet/my-outlets">Kembali ke Daftar Outlet</a></p>
        <hr>
        
        <form action="/outlet/store-update" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?? '' ?>">

            <div class="form-group">
                <label for="name">Nama Outlet:</label>
                <input type="text" id="name" name="name" value="<?= esc($outlet['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Alamat Lengkap:</label>
                <textarea id="address" name="address" rows="4" required><?= esc($outlet['address'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="contact_phone">Nomor Telepon:</label>
                <input type="text" id="contact_phone" name="contact_phone" value="<?= esc($outlet['contact_phone'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="operating_hours">Jam Operasional:</label>
                <input type="text" id="operating_hours" name="operating_hours" value="<?= esc($outlet['operating_hours'] ?? '') ?>">
            </div>

            <button type="submit" class="button">Simpan Data</button>
        </form>
    </div>
</body>
</html>