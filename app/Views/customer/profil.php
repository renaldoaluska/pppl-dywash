<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Profil
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main>
<!-- app/Views/dashboard/customer.php -->
<h1>Selamat datang, <?= session('name') ?> (Customer)</h1>
<a href="/customer/outlet">Cari Outlet Laundry</a> <br>
<a href="/customer/monitor">Monitor Pesanan Saya</a> <br>

<a href="/logout">Logout</a>
</main>

<?= $this->endSection() ?>