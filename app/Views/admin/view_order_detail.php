<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Detail Pesanan #<?= esc($order['order_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $back = '/admin/orders';
if (request()->getGet('from') == 'verif') {
    $back = '/admin/orders?from=verif';
} 
?>
<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <a href="<?= $back ?>" class="p-2 mr-2 rounded-full hover:bg-gray-200">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Detail Pesanan #<?= esc($order['order_id']) ?></h3>
        <p class="text-sm text-gray-500">Dipesan pada <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Info Customer & Rincian Item -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Informasi Customer & Outlet -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-md font-bold text-gray-800 mb-2">Info Customer</h4>
                    <p class="text-sm text-gray-600 font-semibold"><?= esc($order['customer_name']) ?></p>
                </div>
                 <div>
                    <h4 class="text-md font-bold text-gray-800 mb-2">Dikerjakan oleh Outlet</h4>
                    <p class="text-sm text-gray-600 font-semibold"><?= esc($order['outlet_name']) ?></p>
                </div>
            </div>
        </div>

        <!-- Rincian Item Pesanan -->
        <div class="bg-white rounded-xl shadow-md">
            <h4 class="text-md font-bold text-gray-800 p-6 pb-0">Rincian Layanan</h4>
            <div class="divide-y divide-gray-200">
                <?php foreach($order_items as $item): ?>
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800"><?= esc($item['service_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= esc($item['quantity']) ?> <?= esc($item['unit']) ?></p>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Kolom Kanan: Ringkasan Pembayaran -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h4 class="text-md font-bold text-gray-800">Ringkasan Pembayaran</h4>
            <div class="flex justify-between text-sm pt-4 border-t">
                <span class="text-gray-500">Status Pesanan</span>
                <span class="font-semibold text-gray-800 capitalize"><?= str_replace('_', ' ', esc($order['status'])) ?></span>
            </div>
            <div class="flex justify-between text-sm pt-4 border-t">
                <span class="text-gray-500">Metode Pembayaran</span>
                <span class="font-semibold text-gray-800 capitalize"><?= esc($payment['payment_method']) ?></span>
            </div>
            <div class="flex justify-between text-sm pt-4 border-t">
                <span class="text-gray-500">Status Pembayaran</span>
                <span class="font-semibold text-gray-800 capitalize"><?= esc($payment['status']) ?></span>
            </div>
             <div class="flex justify-between items-center border-t pt-4">
                <span class="text-lg font-bold text-gray-900">Total</span>
                <span class="text-xl font-bold text-blue-600">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
             </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
