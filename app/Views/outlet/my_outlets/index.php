<?php
// Definisikan warna status untuk konsistensi
$statusClasses = [
    'pending'  => 'bg-yellow-100 text-yellow-800',
    'verified' => 'bg-green-100 text-green-800',
    'rejected' => 'bg-red-100 text-red-800',
];
?>

<?= $this->include('layout/header', ['title' => 'Kelola Outlet Saya']) ?>

<div class="container max-w-7xl mx-auto my-8 px-4">
    <header class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Kelola Outlet Saya</h1>
            <p class="mt-1 text-slate-500">Tambah, lihat, atau ubah detail outlet Anda.</p>
        </div>
        <a href="/dashboard" class="w-full sm:w-auto text-center text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
            Kembali ke Dashboard
        </a>
        <a href="/outlet/create" class="w-full sm:w-auto text-center text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
            Tambah Outlet Baru
        </a>
    </header>

    <!-- ============================================== -->
    <!-- ==      KONTEN UTAMA (DESKTOP & MOBILE)     == -->
    <!-- ============================================== -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h2 class="text-xl font-bold text-slate-700 mb-5">Daftar Outlet Anda</h2>

        <!-- Tampilan Desktop: Tabel (Hanya terlihat di layar medium ke atas) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                    <tr>
                        <th class="p-4">Nama Outlet</th>
                        <th class="p-4">Alamat</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($outlets)): ?>
                        <?php foreach ($outlets as $outlet): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4 font-semibold text-slate-800"><?= esc($outlet['name']) ?></td>
                                <td class="p-4 text-slate-600"><?= esc($outlet['address']) ?></td>
                                <td class="p-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusClasses[esc($outlet['status'])] ?? 'bg-gray-100' ?> capitalize">
                                        <?= esc($outlet['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <a href="/outlet/edit/<?= $outlet['outlet_id'] ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center p-10 text-slate-500">Anda belum mendaftarkan outlet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile: Kartu (Hanya terlihat di layar kecil) -->
        <div class="block md:hidden space-y-4">
            <?php if (!empty($outlets)): ?>
                <?php foreach ($outlets as $outlet): ?>
                    <div class="border border-slate-200 rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-slate-800 pr-2"><?= esc($outlet['name']) ?></h3>
                            <span class="flex-shrink-0 px-3 py-1 text-xs font-semibold rounded-full <?= $statusClasses[esc($outlet['status'])] ?? 'bg-gray-100' ?> capitalize">
                                <?= esc($outlet['status']) ?>
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mb-4"><?= esc($outlet['address']) ?></p>
                        <a href="/outlet/edit/<?= $outlet['outlet_id'] ?>" class="block w-full text-center bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                            Edit Outlet
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center p-10 text-slate-500">Anda belum mendaftarkan outlet. Klik tombol di atas untuk memulai.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>