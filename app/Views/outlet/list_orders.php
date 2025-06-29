<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Manajemen Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Definisikan warna status untuk konsistensi
$statusColors = [
    'diterima' => 'bg-blue-100 text-blue-800',
    'diproses' => 'bg-yellow-100 text-yellow-800',
    'selesai'  => 'bg-green-100 text-green-800',
    'diulas'   => 'bg-gray-200 text-gray-800',
    'ditolak'  => 'bg-red-100 text-red-800'
];
?>

<!-- Menampilkan pesan sukses/error jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>


<!-- Bagian Pesanan Aktif -->
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-4 border-b">Pesanan Aktif</h3>
    <div class="space-y-4">
        <?php if (!empty($pending_orders)): ?>
            <?php foreach ($pending_orders as $order): ?>
                <!-- Kartu Pesanan Aktif -->
                <div class="bg-white border border-gray-200 rounded-xl">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-500">ID #<?= esc($order['order_id']) ?></p>
                                <h4 class="text-md font-bold text-gray-800 leading-tight"><?= esc($order['customer_name']) ?></h4>
                                <p class="text-sm text-gray-500"><?= esc($order['outlet_name']) ?></p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize <?= $statusColors[$order['status']] ?? 'bg-gray-100' ?>">
                                <?= str_replace('_', ' ', esc($order['status'])) ?>
                            </span>
                        </div>
                    </div>
                    <!-- Form Aksi Update Status -->
                    <div class="bg-gray-50 px-4 py-3 rounded-b-xl">
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <?= csrf_field() ?>
                            <select name="status" class="w-full sm:w-auto flex-grow p-2 text-sm border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="diproses" <?= $order['status'] == 'diterima' ? '' : 'disabled' ?>>Proses Pesanan</option>
                                <option value="selesai" <?= $order['status'] == 'diproses' ? '' : 'disabled' ?>>Selesaikan Pesanan</option>
                                <option value="ditolak">Tolak Pesanan</option>
                            </select>
                            <button type="button" class="update-btn w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center py-8 text-gray-500">Tidak ada pesanan aktif saat ini.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bagian Riwayat Pesanan -->
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-4 border-b">Riwayat Pesanan</h3>
    <div class="space-y-4">
        <?php if (!empty($history_orders)): ?>
            <?php foreach ($history_orders as $order): ?>
                <!-- Kartu Riwayat Pesanan -->
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs text-gray-500">ID #<?= esc($order['order_id']) ?></p>
                            <h4 class="text-md font-medium text-gray-800"><?= esc($order['customer_name']) ?></h4>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize <?= $statusColors[$order['status']] ?? 'bg-gray-100' ?>">
                             <?= str_replace('_', ' ', esc($order['status'])) ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t">
                        <p class="text-sm text-gray-500"><?= date('d M Y', strtotime($order['created_at'])) ?></p>
                        <p class="font-semibold text-gray-700">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center py-8 text-gray-500">Belum ada riwayat pesanan.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <p class="text-lg font-medium text-gray-800 mb-4">Anda yakin ingin mengupdate status pesanan ini?</p>
        <div class="flex justify-center gap-4">
            <button id="confirmBtnTidak" class="w-full px-6 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tidak</button>
            <button id="confirmBtnYa" class="w-full px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Ya, Update</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmationModal');
        if (!modal) return;

        const confirmBtnYa = document.getElementById('confirmBtnYa');
        const confirmBtnTidak = document.getElementById('confirmBtnTidak');
        let formToSubmit = null;

        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                formToSubmit = event.target.closest('form');
                modal.classList.remove('hidden');
            });
        });

        function hideModal() {
            modal.classList.add('hidden');
            formToSubmit = null;
        }

        confirmBtnYa.addEventListener('click', () => { 
            if (formToSubmit) formToSubmit.submit();
        });
        
        confirmBtnTidak.addEventListener('click', hideModal);

        modal.addEventListener('click', (event) => { 
            if (event.target === modal) hideModal();
        });
    });
</script>

<?= $this->endSection() ?>
