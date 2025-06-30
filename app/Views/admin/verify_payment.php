<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Verifikasi Pembayaran
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Menampilkan pesan sukses jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p class="font-bold">Sukses</p>
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>

<!-- Container untuk daftar kartu pembayaran -->
<div class="space-y-4">

    <?php if (!empty($payments)): ?>
        <?php foreach ($payments as $payment): ?>
            <!-- Kartu Individual untuk Setiap Pembayaran -->
            <div class="bg-white rounded-xl shadow-md transition-all duration-300">
                <div class="p-4 sm:p-6">
                    <!-- Informasi Utama -->
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 leading-tight">
                                <?= esc($payment['customer_name']) ?>
                            </h3>
                            <p class="text-2xl font-semibold text-blue-600 mt-1">
                                Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                            </p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Menunggu
                        </span>
                    </div>

                    <!-- Detail Tambahan -->
                    <div class="mt-4 border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 8v5z"></path></svg>
                            <span>ID Pembayaran: <strong>#<?= $payment['payment_id'] ?></strong></span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span>Metode: <strong><?= esc($payment['payment_method']) ?></strong></span>
                        </div>
                    </div>
                </div>
                 <!-- PERUBAHAN: Menambahkan bagian footer dengan tombol aksi -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end rounded-b-xl">
                    <a href="/admin/payments/detail/<?= $payment['payment_id'] ?>" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="text-center bg-white p-8 rounded-xl shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Semua Pembayaran Terkonfirmasi</h3>
            <p class="mt-1 text-sm text-gray-500">Tidak ada pembayaran baru yang menunggu verifikasi saat ini.</p>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
