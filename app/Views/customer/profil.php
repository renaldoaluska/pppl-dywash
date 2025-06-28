<?php // file: app/Views/customer/payment/index.php (contoh nama file) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pembayaran Pesanan</title>
</head>
<body class="bg-slate-50">
<!-- app/Views/dashboard/customer.php -->
<h1>Selamat datang, <?= session('name') ?> (Customer)</h1>
<a href="/customer/outlet">Cari Outlet Laundry</a> <br>
<a href="/customer/monitor">Monitor Pesanan Saya</a> <br>

<a href="/logout">Logout</a>