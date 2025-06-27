<?php // file: app/Views/dashboard/admin.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        a { display: inline-block; margin: 5px 0; color: #007bff; }
    </style>
</head>
<body>
    <h1>Selamat datang, <?= session('name') ?> (Admin)</h1>
    <p>Gunakan menu di bawah untuk mengelola aplikasi.</p>
    <hr>
    
    <a href="/admin/verify">Verifikasi Pendaftaran Outlet</a><br>
    <a href="/admin/payments/verify">Verifikasi Pembayaran Customer</a><br>

    <br>
    <a href="/logout">Logout</a>
</body>
</html>
