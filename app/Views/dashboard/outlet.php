<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Kartu Ringkasan -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    
    <!-- Kartu Pesanan Baru -->
    <a href="/outlet/orders?status=diterima" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-blue-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Pesanan Baru</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $new_orders_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>
    </a>
    
    <!-- Kartu Sedang Diproses -->
    <a href="/outlet/orders?status=diproses" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:ring-2 hover:ring-yellow-400 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500">Sedang Diproses</div>
                <!-- Menampilkan data dinamis dari controller -->
                <div class="text-3xl font-bold text-gray-800 mt-1">
                    <?= $processing_orders_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-yellow-100 text-yellow-600 rounded-full p-3">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </a>
</div>

<!-- Daftar Pesanan Terbaru -->
<div class="mt-8 bg-white p-4 sm:p-6 rounded-xl shadow-md">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Pesanan Terbaru</h3>
    <div class="space-y-4">

        <!-- Cek apakah ada aktivitas terbaru -->
        <?php if (!empty($recent_orders)): ?>
            <!-- Loop untuk setiap pesanan terbaru -->
            <?php foreach ($recent_orders as $order): ?>
                <div class="flex items-center p-3 rounded-lg hover:bg-gray-50">
                    <?php
                        // Logika untuk menentukan ikon dan warna berdasarkan status
                        $icon_bg = 'bg-gray-100 text-gray-600';
                        $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
                        if ($order['status'] == 'selesai' || $order['status'] == 'diulas') {
                            $icon_bg = 'bg-green-100 text-green-600';
                            $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                        } elseif ($order['status'] == 'diproses') {
                            $icon_bg = 'bg-yellow-100 text-yellow-600';
                            $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6.5-13.5l1.414 1.414M5.636 18.364L7.05 16.95m12.02-12.02l-1.414 1.414M12 20.5V19M4 12H2m18.5 6.5l-1.414-1.414M7.05 7.05L5.636 5.636" />';
                        } elseif ($order['status'] == 'diterima') {
                            $icon_bg = 'bg-blue-100 text-blue-600';
                            $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />';
                        }
                    ?>
                    <div class="<?= $icon_bg ?> p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $icon_svg ?></svg>
                    </div>
                    <div class="ml-4 flex-grow">
                        <p class="font-medium text-gray-800">
                            Pesanan #<?= esc($order['order_id']) ?> oleh <strong><?= esc($order['customer_name']) ?></strong>
                        </p>
                        <!-- Menampilkan pesan status dinamis -->
                        <p class="text-sm text-gray-500">
                           Status diubah menjadi '<?= esc(ucfirst($order['status'])) ?>' pada <?= date('d M Y', strtotime($order['updated_at'])) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Pesan jika tidak ada aktivitas -->
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada aktivitas pesanan terbaru.</p>
            </div>
        <?php endif; ?>

    </div>
    <div class="mt-4 text-center">
        <a href="/outlet/orders" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua Pesanan</a>
    </div>
</div>

<?= $this->endSection() ?>
