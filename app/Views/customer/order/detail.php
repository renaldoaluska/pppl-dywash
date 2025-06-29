<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Detail Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl p-6 shadow space-y-4">
    <h1 class="text-xl font-bold mb-4">Detail Pesanan</h1>

    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-slate-500">Outlet</p>
            <p class="font-semibold text-slate-700"><?= esc($order['outlet_name']) ?></p>
            <p class="text-slate-500 mt-1"><?= esc($order['outlet_address']) ?></p>
        </div>
        <div>
            <p class="text-slate-500">Tanggal</p>
            <p class="font-semibold text-slate-700"><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></p>
        </div>
        <div>
            <p class="text-slate-500">Total Bayar</p>
            <p class="font-bold text-lg text-red-600">Rp <?= number_format($order['total_amount'],0,',','.') ?></p>
        </div>
        <div>
            <p class="text-slate-500">Status Pesanan</p>
            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                <?= ($order['status'] == 'selesai' || $order['status'] == 'diulas') ? 'bg-green-100 text-green-800' :
                    (($order['status'] == 'diproses' || $order['status'] == 'dijemput' || $order['status'] == 'dikirim') ? 'bg-yellow-100 text-yellow-800' :
                    ($order['status'] == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-800')) ?>">
                <?= ucfirst($order['status']) ?>
            </span>
        </div>
        <div>
            <p class="text-slate-500">Status Pembayaran</p>
            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                <?= ($order['payment_status'] == 'lunas') ? 'bg-green-100 text-green-800' :
                    (($order['payment_status'] == 'pending') ? 'bg-yellow-100 text-yellow-800' :
                    ($order['payment_status'] == 'gagal' ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-800')) ?>">
                <?= ucfirst($order['payment_status'] ?? '-') ?>
            </span>
        </div>
        <div>
            <p class="text-slate-500">Metode Pembayaran</p>
            <p class="font-semibold text-slate-700"><?= ucfirst($order['payment_method'] ?? '-') ?></p>
        </div>
    </div>

    <?php if (!empty($order['customer_notes'])): ?>
    <div>
        <p class="text-slate-500">Catatan Pelanggan</p>
        <p class="text-slate-700"><?= esc($order['customer_notes']) ?></p>
    </div>
    <?php endif; ?>

    <div>
        <h2 class="font-semibold text-base mb-1">Alamat Pengiriman</h2>
        <div class="bg-gray-50 rounded-lg p-3 text-sm text-slate-700">
            <p><?= esc($order['recipient_name']) ?> (<?= esc($order['label']) ?>)</p>
            <p><?= esc($order['phone_number']) ?></p>
            <p><?= esc($order['address_detail']) ?></p>
        </div>
    </div>

    <div>
        <h2 class="font-semibold text-base mb-1">Layanan</h2>
        <ul class="bg-gray-50 rounded-lg divide-y">
            <?php foreach ($items as $item): ?>
            <li class="flex justify-between items-center p-3 text-sm">
                <span><?= esc($item['service_name']) ?> (<?= $item['quantity'] ?> <?= esc($item['unit']) ?>)</span>
                <span class="font-semibold">Rp <?= number_format($item['subtotal'],0,',','.') ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="/customer/monitor" class="inline-block mt-4 px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-full text-center">
        Kembali ke Riwayat
    </a>
</div>

<?= $this->endSection() ?>
