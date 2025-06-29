<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Manajemen Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Definisikan warna status baru yang konsisten
$statusColors = [
    'diterima' => 'bg-blue-100 text-blue-800',
    'diambil'  => 'bg-purple-100 text-purple-800',
    'dicuci'   => 'bg-yellow-100 text-yellow-800',
    'dikirim'  => 'bg-slate-200 text-slate-800',
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
                <!-- PERUBAHAN: Kartu sekarang dibungkus dengan tag <a> -->
                <a href="/outlet/orders/detail/<?= $order['order_id'] ?>" class="block bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:shadow-lg hover:border-blue-400">
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
                    <!-- Form Aksi Update Status dengan Tombol Kontekstual -->
                    <div class="bg-gray-50 px-4 py-3 rounded-b-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2" onclick="event.stopPropagation();">
                            <?php if ($order['status'] == 'diterima'): ?>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="w-full">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="diambil">
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tandai Diambil</button>
                                </form>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="w-full">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">Tolak</button>
                                </form>
                            <?php elseif ($order['status'] == 'diambil'): ?>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="w-full">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="dicuci">
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">Mulai Cuci</button>
                                </form>
                            <?php elseif ($order['status'] == 'dicuci'): ?>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="w-full">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="dikirim">
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-black bg-slate-600 rounded-lg hover:bg-slate-700">Kirim ke Customer</button>
                                </form>
                            <?php elseif ($order['status'] == 'dikirim'): ?>
                                 <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="w-full">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Selesaikan Pesanan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
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
                <!-- PERUBAHAN: Kartu riwayat juga dibungkus tag <a> -->
                <a href="/outlet/orders/detail/<?= $order['order_id'] ?>" class="block bg-white border border-gray-200 rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:border-blue-400">
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
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center py-8 text-gray-500">Belum ada riwayat pesanan.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
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
                modal.classList.add('flex');
                modal.classList.remove('hidden');
            });
        });
        function hideModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
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
