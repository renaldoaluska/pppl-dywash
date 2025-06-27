<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Outlet Laundry</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; color: #333; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1, h2 { color: #0056b3; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        .outlet-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background-color: #fafafa; }
        .outlet-card h3 { margin-top: 0; }
        .button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; display: inline-block; }
        .button:hover { background-color: #0056b3; }
        .search-form { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-form input[type="text"] { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Cari Outlet Laundry</h1>
        <p><a href="/dashboard">Kembali ke Dashboard</a> | <a href="/logout">Logout</a></p>
        <hr>

        <!-- Form Pencarian -->
        <form action="/customer/outlet" method="get" class="search-form">
            <input type="text" name="search" placeholder="Ketik nama outlet untuk mencari..." value="<?= esc($keyword ?? '', 'attr') ?>">
            <button type="submit" class="button">Cari</button>
        </form>
        
        <h2>Daftar Outlet Tersedia</h2>

        <!-- Cek apakah ada outlet yang ditemukan -->
        <?php if (!empty($outlets)): ?>
            <!-- Loop untuk setiap outlet -->
            <?php foreach ($outlets as $outlet): ?>
                <div class="outlet-card">
                    <h3><?= esc($outlet['name']) ?></h3>
                    <p><strong>Alamat:</strong> <?= esc($outlet['address']) ?></p>
                    <p><strong>Kontak:</strong> <?= esc($outlet['contact_phone']) ?></p>
                    <p><strong>Jam Buka:</strong> <?= esc($outlet['operating_hours']) ?></p>
                    <br>
                    <a href="/customer/order/create/<?= $outlet['outlet_id'] ?>" class="button">Pesan di Outlet Ini</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Pesan jika tidak ada outlet -->
            <p>Tidak ada outlet yang ditemukan atau sesuai dengan pencarian Anda.</p>
        <?php endif; ?>
    </div>

</body>
</html>
