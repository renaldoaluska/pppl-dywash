<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Detail Pesanan #<?= esc($order['order_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <!-- Header Halaman -->
    <div class="flex items-center mb-6">
        <a href="/outlet/orders" class="p-2 mr-2 rounded-full hover:bg-gray-200">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Detail Pesanan #<?= esc($order['order_id']) ?></h3>
            <p class="text-sm text-gray-500">Dipesan pada <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Detail Pengiriman dan Rincian Item -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Alamat Pengiriman -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="text-md font-bold text-gray-800 mb-3">Alamat Penjemputan</h4>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-semibold"><?= esc($address['recipient_name']) ?></p>
                    <p><?= esc($address['phone_number']) ?></p>
                    <p><?= esc($address['address_detail']) ?></p>
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

        <!-- Kolom Kanan: Ringkasan dan Aksi -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Ringkasan Pesanan -->
            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <h4 class="text-md font-bold text-gray-800">Ringkasan</h4>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status Pesanan</span>
                    <span class="font-semibold text-gray-800 capitalize"><?= str_replace('_', ' ', esc($order['status'])) ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium text-gray-700">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
                 <div class="flex justify-between items-center border-t pt-4">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-xl font-bold text-blue-600">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- PERUBAHAN BARU: Blok Aksi Update Status -->
            <?php
                $active_statuses = ['diterima', 'diambil', 'dicuci', 'dikirim'];
                if (in_array($order['status'], $active_statuses)):
            ?>
            <div class="bg-white p-6 rounded-xl shadow-md">
                 <h4 class="text-md font-bold text-gray-800 mb-4">Update Status</h4>
                 <div class="flex flex-col gap-2">
                    <?php if ($order['status'] == 'diterima'): ?>
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="status" value="diambil">
                            <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tandai Diambil</button>
                        </form>
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="status" value="ditolak">
                            <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">Tolak</button>
                        </form>
                    <?php elseif ($order['status'] == 'diambil'): ?>
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="status" value="dicuci">
                            <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">Mulai Cuci</button>
                        </form>
                    <?php elseif ($order['status'] == 'dicuci'): ?>
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="status" value="dikirim">
                            <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-black bg-slate-600 rounded-lg hover:bg-slate-700">Kirim ke Customer</button>
                        </form>
                    <?php elseif ($order['status'] == 'dikirim'): ?>
                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="status" value="selesai">
                            <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Selesaikan Pesanan</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
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

<?= $this->endSection() ?>

<?= $this->section('script') ?>
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
