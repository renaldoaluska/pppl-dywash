<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Daftar Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
    
    <!-- Header dengan Fitur Pencarian -->
    <div class="flex flex-col sm:flex-row items-center justify-between pb-4 border-b">
        <!-- PERUBAHAN: Menambahkan wrapper dan tombol kembali -->
        <div class="flex items-center">
            <a href="/dashboard" class="p-2 mr-2 rounded-full hover:bg-gray-200 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Semua Pesanan Customer</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua pesanan yang masuk.</p>
            </div>
        </div>
        <div class="w-full sm:w-auto flex items-center space-x-2 mt-4 sm:mt-0">
             <form action="/admin/orders" method="get" class="w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari ID pesanan atau nama..." value="<?= esc(request()->getGet('search'), 'attr') ?>">
                </div>
             </form>
        </div>
    </div>

    <!-- Daftar Kartu Pesanan -->
    <div class="mt-6 space-y-4">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <!-- Kartu Individual untuk Setiap Pesanan -->
                <div class="bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:shadow-lg hover:border-blue-400">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-500">ID #<?= esc($order['order_id']) ?></p>
                                <h4 class="text-md font-bold text-gray-800 leading-tight">
                                    <?= esc($order['customer_name']) ?>
                                </h4>
                            </div>
                            <?php
                                $status = $order['status'];
                                $badgeColor = 'bg-gray-100 text-gray-800';
                                if ($status == 'diterima') $badgeColor = 'bg-blue-100 text-blue-800';
                                elseif ($status == 'selesai') $badgeColor = 'bg-green-100 text-green-800';
                                elseif ($status == 'ditolak') $badgeColor = 'bg-red-100 text-red-800';
                            ?>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize <?= $badgeColor ?>">
                                <?= esc($status) ?>
                            </span>
                        </div>
                        
                         <!-- Detail Pesanan -->
                         <div class="mt-3 pt-3 border-t text-sm space-y-2">
                             <div class="flex justify-between">
                                 <span class="text-gray-500">Tanggal Pesan:</span>
                                 <span class="font-medium text-gray-700"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></span>
                             </div>
                             <div class="flex justify-between">
                                 <span class="text-gray-500">Outlet:</span>
                                 <span class="font-medium text-gray-700"><?= esc($order['outlet_name']) ?></span>
                             </div>
                             <div class="flex justify-between items-center mt-2 pt-2 border-t">
                                 <span class="text-gray-900 font-bold">Total Bayar:</span>
                                 <span class="font-bold text-lg text-blue-600">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                             </div>
                         </div>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="bg-gray-50 px-4 py-2 rounded-b-xl flex justify-end">
                        <!-- PERUBAHAN: Link sekarang mengarah ke detail pesanan admin -->
                        <a href="/admin/orders/detail/<?= $order['order_id'] ?>" class="text-sm font-medium text-blue-600 hover:underline">Lihat Detail Pesanan</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center border-2 border-dashed border-gray-300 p-8 rounded-xl">
                 <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Pesanan</h3>
                <p class="mt-1 text-sm text-gray-500">Saat ini belum ada pesanan yang masuk ke sistem.</p>
            </div>
        <?php endif; ?>
    </div>
    
</div>

<?= $this->endSection() ?>
