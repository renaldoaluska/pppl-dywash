<?php // app/Views/outlet/list_orders.php ?>

<?= $this->include('Views/layout/header') ?>

<!-- KONTEN UTAMA HALAMAN MULAI DI SINI -->

<!-- BAGIAN 1: PESANAN AKTIF -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-6">
    <h2 class="text-xl font-bold text-slate-700 mb-5">Pesanan Aktif</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                <tr>
                    <th scope="col" class="p-4">ID Pesanan</th>
                    <th scope="col" class="p-4">Outlet</th>
                    <th scope="col" class="p-4">Customer</th>
                    <th scope="col" class="p-4">Tanggal</th>
                    <th scope="col" class="p-4">Status</th>
                    <th scope="col" class="p-4">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pending_orders)): ?>
                    <?php foreach ($pending_orders as $order): ?>
                        <tr class="bg-white border-b hover:bg-slate-50">
                            <td class="p-4 font-bold text-slate-700">#<?= $order['order_id'] ?></td>
                            <td class="p-4"><?= esc($order['outlet_name']) ?></td>
                            <td class="p-4"><?= esc($order['customer_name']) ?></td>
                            <td class="p-4"><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                            <td class="p-4">
                                <?php
                                    $status = esc($order['status']);
                                    $colors = [
                                        'diterima' => 'bg-blue-100 text-blue-800',
                                        'diproses' => 'bg-yellow-100 text-yellow-800',
                                        'selesai'  => 'bg-green-100 text-green-800',
                                        'diulas'   => 'bg-gray-100 text-gray-800',
                                        'ditolak'  => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $colorClass ?> capitalize">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="flex items-center gap-2">
                                    <?= csrf_field() ?>
                                    <select name="status" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="ditolak" <?= $order['status'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                    </select>
                                    <button type="button" class="update-btn whitespace-nowrap px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-10 text-slate-500">Tidak ada pesanan aktif yang perlu diproses saat ini. 🎉</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- BAGIAN 2: RIWAYAT PESANAN -->
<div class="bg-white rounded-xl shadow-lg p-6 mt-6">
    <h2 class="text-xl font-bold text-slate-700 mb-5">Riwayat Pesanan</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
             <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                <tr>
                    <th scope="col" class="p-4">ID Pesanan</th>
                    <th scope="col" class="p-4">Outlet</th>
                    <th scope="col" class="p-4">Customer</th>
                    <th scope="col" class="p-4">Tanggal</th>
                    <th scope="col" class="p-4">Status Final</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($history_orders)): ?>
                    <?php foreach ($history_orders as $order): ?>
                        <tr class="bg-white border-b hover:bg-slate-50">
                            <td class="p-4 font-bold text-slate-700">#<?= $order['order_id'] ?></td>
                            <td class="p-4"><?= esc($order['outlet_name']) ?></td>
                            <td class="p-4"><?= esc($order['customer_name']) ?></td>
                            <td class="p-4"><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                            <td class="p-4">
                                <?php
                                    $status = esc($order['status']);
                                    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $colorClass ?> capitalize">
                                    <?= $status ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center p-10 text-slate-500">Belum ada riwayat pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP/MODAL dengan TAILWIND -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md text-center transform transition-all duration-300 scale-95 opacity-0">
        <p class="text-lg font-medium text-slate-800 mb-6">Apakah Anda yakin ingin mengupdate status pesanan ini?</p>
        <div class="flex justify-center gap-4">
            <button id="confirmBtnTidak" class="px-6 py-2 font-semibold text-white bg-gray-500 rounded-lg hover:bg-gray-600">Tidak</button>
            <button id="confirmBtnYa" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Ya, Update</button>
        </div>
    </div>
</div>

<!-- KONTEN UTAMA HALAMAN SELESAI -->

<?= $this->include('Views/layout/footer') ?>