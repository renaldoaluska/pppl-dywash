<?php // file: app/Views/customer/order/history.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Riwayat Pesanan</title>
</head>
<body class="bg-slate-50">

<?= $this->include('layout/top_nav') ?>

<main class="max-w-3xl mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-700">Riwayat Pesanan</h2>
    </div>

    <?php
        // Array untuk memetakan status ke kelas warna Tailwind
        // Cara ini membuat kode di dalam loop lebih bersih
        $statusColors = [
            'diterima' => 'bg-blue-100 text-blue-800',
            'diproses' => 'bg-yellow-100 text-yellow-800',
            'selesai'  => 'bg-green-100 text-green-800',
            'diulas'   => 'bg-slate-200 text-slate-800',
            'ditolak'  => 'bg-red-100 text-red-800',
        ];
    ?>

    <div class="space-y-4">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-lg shadow-md p-5 border border-slate-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-500">Outlet</p>
                            <p class="font-semibold text-slate-700"><?= esc($order['outlet_name']) ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tanggal</p>
                            <p class="font-semibold text-slate-700"><?= date('d F Y, H:i', strtotime($order['order_date'])) ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Total Bayar</p>
                            <p class="font-bold text-lg text-red-600">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Status</p>
                            <p>
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded-full <?= $statusColors[esc($order['status'])] ?? 'bg-gray-200 text-gray-800' ?>">
                                    <?= ucfirst(esc($order['status'])) ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <?php if ($order['status'] == 'selesai'): ?>
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <h4 class="text-md font-semibold text-slate-700 mb-3">Beri Ulasan:</h4>
                            <form action="/customer/review/store" method="post" class="space-y-4">
                                <?= csrf_field() ?>
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <div>
                                    <label for="rating_<?= $order['order_id'] ?>" class="block text-sm font-medium text-slate-600 mb-1">Rating (1-5)</label>
                                    <input type="number" name="rating" id="rating_<?= $order['order_id'] ?>" min="1" max="5" required class="w-24 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label for="comment_<?= $order['order_id'] ?>" class="block text-sm font-medium text-slate-600 mb-1">Komentar</label>
                                    <textarea name="comment" id="comment_<?= $order['order_id'] ?>" rows="3" class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Bagaimana pengalaman Anda?"></textarea>
                                </div>
                                <div>
                                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Kirim Ulasan</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-lg p-8 text-center text-slate-500">
                <p>Anda belum memiliki riwayat pesanan.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?= $this->include('layout/bottom_nav') ?>
<?= $this->include('layout/footer') ?>