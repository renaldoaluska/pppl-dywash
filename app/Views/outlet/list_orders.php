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

<div class="mb-4">
    <!--<label for="filter-outlet" class="block mb-1 text-sm font-medium text-gray-700">Filter berdasarkan Outlet</label>-->
    <select id="filter-outlet" class="w-full sm:w-64 px-3 py-2 border rounded-lg">
        <option value="all">Semua Outlet</option>
        <?php foreach ($outlets as $outlet): ?>
            <option value="<?= esc($outlet['outlet_id']) ?>"><?= esc($outlet['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Bagian Pesanan Aktif -->
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-4 border-b">Pesanan Aktif</h3>
    <div class="flex flex-wrap gap-2 mb-4" id="active-filter">
    <?php 
    $activeStatuses = ['semua', 'diterima', 'diambil', 'dicuci', 'dikirim'];
    foreach ($activeStatuses as $status): ?>
        <button type="button" onclick="applyActiveFilter('<?= $status ?>', this)"
            class="active-filter-btn px-3 py-1 rounded-full border text-sm bg-gray-100 text-gray-700 border-gray-300 hover:bg-blue-600 hover:text-white transition"
            <?= $status === 'semua' ? 'id="default-active-filter"' : '' ?>>
            <?= ucfirst($status) ?>
        </button>
    <?php endforeach; ?>
</div>

    <div class="space-y-4">
         <p id="no-active-msg" class="text-center py-8 text-gray-500">
        Tidak ada pesanan aktif saat ini.
    </p>
        <?php if (!empty($pending_orders)): ?>
            <?php foreach ($pending_orders as $order): ?>
                <!-- Kartu sekarang menjadi link ke halaman detail -->
                 <a href="/outlet/orders/detail/<?= $order['order_id'] ?>" 
   data-outlet-id="<?= $order['outlet_id'] ?>"
   class="order-card block bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:shadow-lg hover:border-blue-400">
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
                        <!-- PERBAIKAN: Menambahkan onclick="event.stopPropagation();" untuk mencegah klik merambat ke <a> induk -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2" onclick="event.stopPropagation(); event.preventDefault();">
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
                                    <button type="button" class="update-btn w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Kirim ke Customer</button>
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
<?php endif; ?>

    </div>
</div>

<!-- Bagian Riwayat Pesanan -->
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-4 border-b">Riwayat Pesanan</h3>
    <div class="flex flex-wrap gap-2 mb-4" id="history-filter">
    <?php 
    $statuses = ['semua','selesai', 'diulas', 'ditolak'];
    ?>
    <?php foreach ($statuses as $status): ?>
        <button type="button" onclick="applyHistoryFilter('<?= $status ?>', this)"
            class="history-filter-btn px-3 py-1 rounded-full border text-sm bg-gray-100 text-gray-700 border-gray-300 hover:bg-blue-600 hover:text-white transition"
            <?= $status === 'semua' ? 'id="default-history-filter"' : '' ?>>
            <?= ucfirst($status) ?>
        </button>
    <?php endforeach; ?>
</div>

    <div class="space-y-4">
    <?php if (!empty($history_orders)): ?>
        <?php foreach ($history_orders as $order): ?>
            <a href="/outlet/orders/detail/<?= $order['order_id'] ?>" 
   data-outlet-id="<?= $order['outlet_id'] ?>"
   class="history-order-card block bg-white border border-gray-200 rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:border-blue-400" data-status="<?= esc($order['status']) ?>">

                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-gray-500">ID #<?= esc($order['order_id']) ?></p>
                        <h4 class="text-md font-medium text-gray-800"><?= esc($order['customer_name']) ?></h4>
                        <p class="text-sm text-gray-500"><?= esc($order['outlet_name']) ?></p>
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
    <?php endif; ?>

    <!-- Pesan selalu ada di DOM, default hidden -->
    <p id="no-history-msg" class="text-center py-8 text-gray-500 hidden">Tidak ada pesanan dengan filter ini.</p>
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
document.addEventListener('DOMContentLoaded', () => {
    // Auto trigger filter 'semua' saat load
    const defaultBtn = document.getElementById('default-history-filter');
    if (defaultBtn) {
        applyHistoryFilter('semua', defaultBtn);
    }
});

function applyHistoryFilter(status, btn) {
    console.log('Filter riwayat status:', status);

    // Highlight button terpilih
    document.querySelectorAll('.history-filter-btn').forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white');
        b.classList.add('bg-gray-100', 'text-gray-700');
    });
    btn.classList.remove('bg-gray-100', 'text-gray-700');
    btn.classList.add('bg-blue-600', 'text-white');

    const selectedOutlet = document.getElementById('filter-outlet').value;
    const cards = document.querySelectorAll('.history-order-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const matchStatus = (status === 'semua' || card.dataset.status === status);
        const matchOutlet = (selectedOutlet === 'all' || card.dataset.outletId === selectedOutlet);

        if (matchStatus && matchOutlet) {
            card.classList.remove('hidden');
            visibleCount++;
        } else {
            card.classList.add('hidden');
        }
    });

    const msg = document.getElementById('no-history-msg');
    if (visibleCount === 0) {
        msg.classList.remove('hidden');
        msg.textContent = `Belum ada pesanan dengan filter "${status}" untuk outlet ini.`;
    } else {
        msg.classList.add('hidden');
    }

    const riwayat = document.getElementById('riwayat');
    if (riwayat) {
        riwayat.scrollIntoView({ behavior: 'smooth' });
    }
}

</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const outletFilter = document.getElementById('filter-outlet');
    if (outletFilter) {
        outletFilter.addEventListener('change', function () {
            const selectedOutlet = this.value;
            console.log('Filter outlet:', selectedOutlet);

            // Filter Pesanan Aktif
            const orderCards = document.querySelectorAll('.order-card');
            let activeVisible = 0;

            orderCards.forEach(card => {
                if (selectedOutlet === 'all' || card.dataset.outletId === selectedOutlet) {
                    card.classList.remove('hidden');
                    activeVisible++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Filter Riwayat Pesanan
            document.querySelectorAll('.history-order-card').forEach(card => {
                if (selectedOutlet === 'all' || card.dataset.outletId === selectedOutlet) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });

            // ✅ Reset filter status riwayat ke "semua"
            const defaultBtn = document.getElementById('default-history-filter');
            if (defaultBtn) {
                applyHistoryFilter('semua', defaultBtn);
            }

            // ✅ Reset filter status aktif ke "semua"
            const defaultActiveBtn = document.getElementById('default-active-filter');
            if (defaultActiveBtn) {
                applyActiveFilter('semua', defaultActiveBtn);
            }
        });
    }

    // Trigger filter default saat halaman pertama kali dibuka
    const defaultBtn = document.getElementById('default-history-filter');
    if (defaultBtn) {
        applyHistoryFilter('semua', defaultBtn);
    }

    const defaultActiveBtn = document.getElementById('default-active-filter');
    if (defaultActiveBtn) {
        applyActiveFilter('semua', defaultActiveBtn);
    }
});
</script>

<script>
    document.querySelectorAll('.update-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('form');
        if (form) {
            // Tampilkan modal konfirmasi dulu
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('hidden');

            // Tangani tombol "Ya, Update"
            const confirmBtn = document.getElementById('confirmBtnYa');
            const cancelBtn = document.getElementById('confirmBtnTidak');

            const confirmHandler = () => {
                form.submit();
                modal.classList.add('hidden');
                cleanup();
            };

            const cancelHandler = () => {
                modal.classList.add('hidden');
                cleanup();
            };

            const cleanup = () => {
                confirmBtn.removeEventListener('click', confirmHandler);
                cancelBtn.removeEventListener('click', cancelHandler);
            };

            confirmBtn.addEventListener('click', confirmHandler);
            cancelBtn.addEventListener('click', cancelHandler);
        }
    });
});

    </script>
    <script>
        function applyActiveFilter(status, btn) {
    console.log('Filter aktif status:', status);

    // Highlight tombol terpilih
    document.querySelectorAll('.active-filter-btn').forEach(b => {
        b.classList.remove('bg-blue-600', 'text-white');
        b.classList.add('bg-gray-100', 'text-gray-700');
    });
    btn.classList.remove('bg-gray-100', 'text-gray-700');
    btn.classList.add('bg-blue-600', 'text-white');

    const selectedOutlet = document.getElementById('filter-outlet').value;
    const cards = document.querySelectorAll('.order-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const matchStatus = (status === 'semua' || card.querySelector('span').textContent.trim().toLowerCase() === status);
        const matchOutlet = (selectedOutlet === 'all' || card.dataset.outletId === selectedOutlet);

        if (matchStatus && matchOutlet) {
            card.classList.remove('hidden');
            visibleCount++;
        } else {
            card.classList.add('hidden');
        }
    });

    const noActiveMsg = document.getElementById('no-active-msg');
if (noActiveMsg) {
    if (visibleCount === 0) {
        const outletName = document.getElementById('filter-outlet').options[
            document.getElementById('filter-outlet').selectedIndex
        ].text;
        const statusLabel = btn?.textContent?.trim() || 'Semua';
        noActiveMsg.textContent = `Tidak ada pesanan aktif untuk outlet "${outletName}" dengan status "${statusLabel}".`;
        noActiveMsg.classList.remove('hidden');
    } else {
        noActiveMsg.classList.add('hidden');
    }
}
}
document.addEventListener('DOMContentLoaded', () => {
    const defaultActiveBtn = document.getElementById('default-active-filter');
    if (defaultActiveBtn) {
        applyActiveFilter('semua', defaultActiveBtn);
    }
});

</script>
<?= $this->endSection() ?>
