<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Verifikasi Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header: Jumlah Outlet Menunggu + Tombol Lihat Semua -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <h2 class="text-xl font-bold text-gray-800">
        <?= count($outlets) ?> Outlet Pending
    </h2>
    <a href="/admin/outlets?from=verif" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-green-700 transition">
        Lihat Semua Outlet Terdaftar
    </a>
</div>



<!-- Menampilkan pesan sukses jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p class="font-bold">Sukses</p>
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>


<!-- Container untuk daftar kartu outlet -->
<div class="space-y-4">

    <?php if (!empty($outlets)): ?>
        <?php foreach ($outlets as $outlet): ?>
            <!-- Kartu Individual untuk Setiap Outlet -->
            <div class="bg-white rounded-xl shadow-md transition-all duration-300">
                <div class="p-4 sm:p-6">
                    <!-- Informasi Outlet -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">
                            <?= esc($outlet['name']) ?>
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Menunggu
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <?= esc($outlet['address']) ?>
                    </p>
                </div>

                <!-- PERUBAHAN: Menambahkan bagian footer dengan tombol aksi -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end rounded-b-xl">
                    <a href="/admin/outlets/detail/<?= $outlet['outlet_id'] ?>" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <!-- Tampilan jika tidak ada outlet untuk diverifikasi -->
        <div class="text-center bg-white p-8 rounded-xl shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Semua Terverifikasi</h3>
            <p class="mt-1 text-sm text-gray-500">Tidak ada outlet baru yang menunggu verifikasi saat ini.</p>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
