<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Daftar Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$back = '/dashboard';
if (request()->getGet('from') == 'verif') {
    $back = '/admin/outlets/verify';
}
?>
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
    
    <!-- Header dengan Fitur Pencarian -->
    <div class="flex flex-col sm:flex-row items-center justify-between pb-4 border-b">
        <!-- PERBAIKAN: Menghapus kelas 'hidden' dan 'sm:block' dari tombol kembali -->
        <div class="flex items-center">
            <a href="<?= $back ?>" class="p-2 mr-2 rounded-full hover:bg-gray-200 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Semua Outlet Terdaftar</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola semua outlet yang ada di platform Anda.</p>
            </div>
        </div>
        <div class="w-full sm:w-auto flex items-center space-x-2 mt-4 sm:mt-0">
             <form action="/admin/outlets" method="get" class="w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari outlet..." value="<?= esc(request()->getGet('search'), 'attr') ?>">
                </div>
             </form>
        </div>
    </div>

    <!-- Daftar Kartu Outlet -->
    <div class="mt-6 space-y-4">
        <?php if (!empty($outlets)): ?>
            <?php foreach ($outlets as $outlet): ?>
                <!-- Kartu Individual untuk Setiap Outlet -->
                <div class="bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:shadow-lg hover:border-blue-400">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-md font-bold text-gray-800 leading-tight">
                                <?= esc($outlet['name']) ?>
                            </h4>
                             <?php
                                $status = $outlet['status'];
                                $badgeColor = 'bg-gray-100 text-gray-800';
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
                         <div class="flex items-center text-sm text-gray-600 mt-3 pt-3 border-t">
                             <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span><?= esc($outlet['contact_phone']) ?></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 rounded-b-xl flex justify-end">
                        <a href="/admin/outlets/view/<?= $outlet['outlet_id'] ?>" class="text-sm font-medium text-blue-600 hover:underline">Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center border-2 border-dashed border-gray-300 p-8 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada outlet yang cocok dengan kata kunci pencarian Anda.</p>
                <div class="mt-6">
                    <a href="/admin/outlets" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Lihat Semua Outlet
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Paginasi -->
    <div class="flex items-center justify-between border-t border-gray-200 mt-6 px-4 py-3 sm:px-0">
        <!-- ... (kode paginasi tetap sama) ... -->
    </div>
</div>

<?= $this->endSection() ?>
