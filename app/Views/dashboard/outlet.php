<?php // file: app/Views/dashboard/outlet.php (DIPERBARUI) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Outlet</title>
     <style>
        body { font-family: sans-serif; padding: 20px; }
        a { display: inline-block; margin: 5px 0; color: #007bff; }
    </style>
</head>
<body>
    <h1>Selamat datang, <?= session('name') ?> (Outlet)</h1>
    <p>Gunakan menu di bawah untuk mengelola outlet Anda.</p>
    <hr>
    
    <!-- DIUBAH: Mengarah ke halaman daftar outlet milik owner -->
    <a href="/outlet/my-outlets">Kelola Outlet Saya</a><br>
    <a href="/outlet/services">Kelola Layanan Laundry</a><br>
    <a href="/outlet/orders">Kelola Pesanan Masuk</a><br>
    <a href="/outlet/reviews">Lihat Ulasan Customer</a><br>
    
    <br>
    <a href="/logout">Logout</a>
</body>
</html>
