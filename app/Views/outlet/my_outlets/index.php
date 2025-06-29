<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Outlet Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Kelola Outlet Anda</h3>
        <p class="text-sm text-gray-500 mt-1">Tambah, edit, dan lihat ulasan untuk setiap outlet.</p>
    </div>
    <a href="/outlet/create" class="w-full sm:w-auto mt-4 sm:mt-0 px-4 py-2 text-sm text-center font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
        Tambah Outlet Baru
    </a>
</div>

<!-- Menampilkan pesan sukses/error jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>


<!-- Daftar Kartu Outlet -->
<div class="space-y-4">
    <?php if (!empty($outlets)): ?>
        <?php foreach ($outlets as $outlet): ?>
            <!-- Kartu Individual untuk Setiap Outlet -->
            <div class="bg-white rounded-xl shadow-md transition-all duration-300">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">
                            <?= esc($outlet['name']) ?>
                        </h3>
                        <?php
                            $status = $outlet['status'];
                            $badgeColor = 'bg-gray-100 text-gray-800'; // Default
                            if ($status == 'verified') $badgeColor = 'bg-green-100 text-green-800';
                            elseif ($status == 'pending') $badgeColor = 'bg-yellow-100 text-yellow-800';
                            elseif ($status == 'rejected') $badgeColor = 'bg-red-100 text-red-800';
                        ?>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $badgeColor ?>">
                            <?= ucfirst(esc($status)) ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <?= esc($outlet['address']) ?>
                    </p>
                </div>
                <!-- Bagian Aksi (Tombol) -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row justify-end gap-3 rounded-b-xl">
                    <a href="/outlet/edit/<?= $outlet['outlet_id'] ?>" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        Kelola Layanan
                    </a>
                    <!-- Tombol Lihat Ulasan yang mengarah ke halaman ulasan spesifik -->
                    <a href="/outlet/<?= $outlet['outlet_id'] ?>/reviews" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Lihat Ulasan
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center bg-white p-8 rounded-xl shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Anda Belum Memiliki Outlet</h3>
            <p class="mt-1 text-sm text-gray-500">Daftarkan outlet pertama Anda untuk memulai.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
