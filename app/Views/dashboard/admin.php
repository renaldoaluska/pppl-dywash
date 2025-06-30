<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Kartu Statistik yang Bisa Diklik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6">
    
    <!-- Kartu Verifikasi Outlet -->
    <a href="/admin/verify" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-blue-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Outlet Menunggu</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $pending_outlets_count ?? 0 ?> 
                </div>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </a>
    
    <!-- Kartu Verifikasi Pembayaran -->
    <a href="/admin/payments/verify" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-blue-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Pembayaran Menunggu</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $pending_payments_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
            </div>
        </div>
    </a>
    
    <!-- Kartu Total Outlet -->
    <a href="/admin/outlets" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-blue-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Outlet Terverifikasi</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $total_outlets_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
    </a>

    <!-- Kartu Total Pesanan -->
    <a href="/admin/orders" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-blue-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Total Pesanan</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $total_orders_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>
    </a>
</div>

<?= $this->endSection() ?>
