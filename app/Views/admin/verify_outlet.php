<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Verifikasi Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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
            <div class="bg-white rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
                <div class="p-4 sm:p-6">
                    <!-- Informasi Outlet -->
                    <div>
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

                    <!-- Detail Tambahan -->
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <div class="flex items-center text-sm text-gray-600">
                             <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span><?= esc($outlet['contact_phone']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Bagian Aksi (Tombol) -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3 rounded-b-xl">
                    <a href="/admin/verify/action/<?= $outlet['outlet_id'] ?>/rejected" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Tolak
                    </a>
                    <a href="/admin/verify/action/<?= $outlet['outlet_id'] ?>/verified" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        Setujui
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
