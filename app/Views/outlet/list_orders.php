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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="container max-w-7xl mx-auto my-8 px-4">
        <header class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Manajemen Pesanan</h1>
                <p class="mt-1 text-slate-500">Kelola pesanan aktif dan lihat riwayat.</p>
            </div>
        <a href="/dashboard" class="w-full sm:w-auto text-center text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
            Kembali ke Dashboard
        </a>
        </header>

        <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
            <h2 class="text-xl font-bold text-slate-700 mb-5">Pesanan Aktif</h2>

            <div class="hidden md:block">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                        <tr>
                            <th class="p-4">Detail Customer</th>
                            <th class="p-4">Outlet</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if (!empty($pending_orders)): ?>
                            <?php foreach ($pending_orders as $order): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4">
                                        <p class="font-bold text-slate-800">#<?= $order['order_id'] ?> - <?= esc($order['customer_name']) ?></p>
                                        <p class="text-xs text-slate-500"><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></p>
                                    </td>
                                    <td class="p-4 text-slate-600"><?= esc($order['outlet_name']) ?></td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusColors[esc($order['status'])] ?? 'bg-gray-100' ?> capitalize"><?= esc($order['status']) ?></span>
                                    </td>
                                    <td class="p-4">
                                        <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="flex items-center gap-2">
                                            <?= csrf_field() ?>
                                            <select name="status" class="w-36 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                                                <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                                <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                                <option value="ditolak" <?= $order['status'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                            </select>
                                            <button type="button" class="update-btn px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center p-10 text-slate-500">Tidak ada pesanan aktif.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden space-y-4">
                <?php if (!empty($pending_orders)): ?>
                    <?php foreach ($pending_orders as $order): ?>
                        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-slate-800">#<?= $order['order_id'] ?> - <?= esc($order['customer_name']) ?></p>
                                    <p class="text-sm text-slate-500"><?= esc($order['outlet_name']) ?></p>
                                </div>
                                <span class="flex-shrink-0 px-3 py-1 text-xs font-semibold rounded-full <?= $statusColors[esc($order['status'])] ?? 'bg-gray-100' ?> capitalize"><?= esc($order['status']) ?></span>
                            </div>
                            <p class="text-xs text-slate-500 mb-4">Tgl: <?= date('d M Y, H:i', strtotime($order['order_date'])) ?></p>
                            <div class="bg-slate-50 p-3 rounded-lg mt-2">
                                <p class="text-xs text-slate-500 mb-2 font-semibold">Update Status:</p>
                                <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="flex items-center gap-2">
                                    <?= csrf_field() ?>
                                    <select name="status" class="w-full p-2 text-sm border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500">
                                        <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="ditolak" <?= $order['status'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                    </select>
                                    <button type="button" class="update-btn px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="bg-white rounded-lg p-10 text-center text-slate-500 shadow-sm">Tidak ada pesanan aktif.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
            <h2 class="text-xl font-bold text-slate-700 mb-5">Riwayat Pesanan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width: 640px;">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                        <tr>
                            <th class="p-4">ID</th>
                            <th class="p-4">Customer</th>
                            <th class="p-4">Outlet</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Status Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if (!empty($history_orders)): ?>
                            <?php foreach ($history_orders as $order): ?>
                                <tr>
                                    <td class="p-4 font-bold text-slate-700">#<?= $order['order_id'] ?></td>
                                    <td class="p-4 text-slate-600"><?= esc($order['customer_name']) ?></td>
                                    <td class="p-4 text-slate-600"><?= esc($order['outlet_name']) ?></td>
                                    <td class="p-4 text-slate-600"><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusColors[esc($order['status'])] ?? 'bg-gray-100' ?> capitalize"><?= esc($order['status']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center p-10 text-slate-500">Belum ada riwayat pesanan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md text-center">
            <p class="text-lg font-medium text-slate-800 mb-6">Apakah Anda yakin ingin mengupdate status pesanan ini?</p>
            <div class="flex justify-center gap-4">
                <button id="confirmBtnTidak" class="px-6 py-2 font-semibold text-white bg-gray-500 rounded-lg hover:bg-gray-600">Tidak</button>
                <button id="confirmBtnYa" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Ya, Update</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmationModal');
            if (modal) {
                const confirmBtnYa = document.getElementById('confirmBtnYa');
                const confirmBtnTidak = document.getElementById('confirmBtnTidak');
                const updateButtons = document.querySelectorAll('.update-btn');
                let formToSubmit = null;

                updateButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        formToSubmit = event.target.closest('form');
                        // Cara yang benar untuk menampilkan modal di Tailwind
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                function hideModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    formToSubmit = null;
                }

                // Cek jika tombol 'Ya' ada sebelum menambahkan listener
                if (confirmBtnYa) {
                    confirmBtnYa.addEventListener('click', () => { 
                        if (formToSubmit) {
                            formToSubmit.submit();
                        }
                    });
                }
                
                // Cek jika tombol 'Tidak' ada sebelum menambahkan listener
                if (confirmBtnTidak) {
                    confirmBtnTidak.addEventListener('click', hideModal);
                }

                // Listener untuk menutup modal jika klik di luar area
                modal.addEventListener('click', (event) => { 
                    if (event.target === modal) {
                        hideModal();
                    }
                });
            }
        });
    </script>
</body>
</html>