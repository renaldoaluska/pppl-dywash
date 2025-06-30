<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BAGIAN OUTLET -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-4">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Outlet</div>
                <div class="text-4xl font-bold text-gray-800 mt-1">
                    <?= ($total_outlets_count ?? 0) + ($pending_outlets_count ?? 0) ?>
                </div>
            </div>
            <div class="bg-green-100 text-green-600 rounded-full p-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <!-- LIST OUTLET -->
        <?php
        $outlet_statuses = [
            'verified' => ['label' => 'Terverifikasi', 'count' => $total_outlets_count ?? 0],
            'pending'  => ['label' => 'Menunggu', 'count' => $pending_outlets_count ?? 0],
        ];
        foreach ($outlet_statuses as $status => $data):
        ?>
        <a href="/admin/outlets?status=<?= $status ?>" class="flex justify-between items-center py-2 border-t border-b border-gray-200 hover:bg-gray-50">
            <div class="flex items-center">
                <span class="text-md font-bold text-gray-700 w-6"><?= $data['count'] ?></span>
                <span class="ml-4 text-gray-600"><?= $data['label'] ?></span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <?php endforeach; ?>
    </div>
</div>


<!-- BAGIAN PESANAN (AKTIF & SELESAI) -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</div>
                <div class="text-4xl font-bold text-gray-800 mt-1">
                    <?= $total_orders_count ?? 0 ?>
                </div>
            </div>
            <div class="bg-purple-100 text-purple-800 rounded-full p-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
    </div>

    <hr class="border-gray-200">

    <!-- PESANAN AKTIF -->
    <div class="p-5">
        <div class="text-lg font-semibold text-gray-800 mb-3">Aktif</div>
        <?php
        $aktif_statuses = ['diterima', 'diambil', 'dicuci', 'dikirim'];
        foreach ($aktif_statuses as $status):
            $count_var = $status . '_orders_count';
            $count = $$count_var ?? 0;
        ?>
        <a href="/admin/orders?status=<?= $status ?>" class="flex justify-between items-center py-2 border-t border-b border-gray-200 hover:bg-gray-50">
            <div class="flex items-center">
                <span class="text-md font-bold text-gray-700 w-6"><?= $count ?></span>
                <span class="ml-4 text-gray-600 capitalize"><?= $status ?></span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <?php endforeach; ?>
    </div>

    <hr class="border-gray-200">

    <!-- PESANAN SELESAI -->
    <div class="p-5">
        <div class="text-lg font-semibold text-gray-800 mb-3">Selesai</div>
        <?php
        $selesai_statuses = ['belum diulas', 'diulas'];
        foreach ($selesai_statuses as $status):
            $status_key = str_replace(' ', '_', $status);
            $count_var = $status_key . '_orders_count';
            $count = $$count_var ?? 0;
        ?>
        <a href="/admin/orders?status=<?= $status_key ?>" class="flex justify-between items-center py-2 border-t border-b border-gray-200 hover:bg-gray-50">
    <div class="flex items-center">
                <span class="text-md font-bold text-gray-700 w-6"><?= $count ?></span>
                <span class="ml-4 text-gray-600 capitalize"><?= $status ?></span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <?php endforeach; ?>
    </div>
    
<!-- BAGIAN PEMBAYARAN -->
    <hr class="border-gray-200">
        <div class="p-5">
        <div class="text-lg font-semibold text-gray-800 mb-3">Pembayaran</div>
        <?php
        $payment_statuses = ['pending', 'lunas', 'gagal', 'cod'];
        foreach ($payment_statuses as $status):
            $count_var = $status . '_payments_count';
            $count = $$count_var ?? 0;
        ?>
       <a href="/admin/orders?status=<?= $status ?>" class="flex justify-between items-center py-2 border-t border-b border-gray-200 hover:bg-gray-50">
    <div class="flex items-center">
                <span class="text-md font-bold text-gray-700 w-6"><?= $count ?></span>
                <span class="ml-4 text-gray-600 capitalize"><?= $status ?></span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <?php endforeach; ?>
    </div>
</div>


<?= $this->endSection() ?>
