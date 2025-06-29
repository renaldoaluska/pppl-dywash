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
    <?php
    if ($order['status'] == 'diterima') {
    echo 'bg-blue-100 text-blue-800';
} elseif ($order['status'] == 'ditolak') {
    echo 'bg-red-100 text-red-800';
} elseif ($order['status'] == 'diambil') {
    echo 'bg-purple-100 text-purple-800';
} elseif ($order['status'] == 'dicuci') {
    echo 'bg-yellow-100 text-yellow-800';
} elseif ($order['status'] == 'dikirim') {
    echo 'bg-teal-100 text-teal-800';
} elseif ($order['status'] == 'selesai') {
    echo 'bg-green-100 text-green-800';
} elseif ($order['status'] == 'diulas') {
    echo 'bg-green-200 text-green-900';
} else {
    echo 'bg-gray-200 text-gray-800';
}
    ?>">
    <?= ucfirst($order['status']) ?>
</span>

        </div>
        <div>
<p class="text-slate-500">Status Pembayaran</p>
<span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
    <?php
        if ($order['payment_status'] == 'pending') {
            echo 'bg-yellow-100 text-yellow-800';
        } elseif ($order['payment_status'] == 'lunas') {
            echo 'bg-green-100 text-green-800';
        } elseif ($order['payment_status'] == 'gagal') {
            echo 'bg-red-100 text-red-800';
        } elseif ($order['payment_status'] == 'cod') {
            echo 'bg-blue-100 text-blue-800';
        } else {
            echo 'bg-gray-200 text-gray-800';
        }
    ?>">
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
        <?php 
        $totalBiaya = 0;
        foreach ($items as $item): 
            $totalBiaya += $item['subtotal'];
        ?>
        <li class="flex justify-between items-center p-3 text-sm">
            <span><?= esc($item['service_name']) ?> (<?= $item['quantity'] ?> <?= esc($item['unit']) ?>)</span>
            <span class="font-semibold">Rp <?= number_format($item['subtotal'],0,',','.') ?></span>
        </li>
        <?php endforeach; ?>
        <li class="flex justify-between items-center p-3 text-sm font-bold text-slate-700">
            <span>Total Biaya</span>
            <span>Rp <?= number_format($totalBiaya,0,',','.') ?></span>
        </li>
    </ul>
</div>


<div class="flex justify-between items-center mt-4">
    <?php if (empty($order['payment_status'])): ?>
        <a href="/customer/payment/<?= $order['order_id'] ?>"
            class="px-4 py-1.5 bg-white border border-emerald-400 text-emerald-600 text-sm rounded-full shadow-sm hover:bg-emerald-50 transition">
            Konfirmasi Bayar
        </a>
    <?php else: ?>
        <div></div> <!-- Spacer agar tombol kanan tetap di kanan -->
    <?php endif; ?>

    <a href="/customer/monitor"
        class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm rounded-full shadow-sm transition">
        Kembali
    </a>
</div>

</div>

<?= $this->endSection() ?>
