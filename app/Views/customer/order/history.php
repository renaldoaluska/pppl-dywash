<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Riwayat Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <style>
        .rating {
            display: inline-flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            align-items: center;
            gap: 0.1em;
        }

        .rating > input {
            display: none;
        }

        .rating > label {
            position: relative;
            width: 1.5em;
            font-size: 1.8rem;
            line-height: 1;
            color: #CBD5E1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rating > label::before {
            content: "\2605";
            position: relative;
            opacity: 1;
        }

        .rating > label:hover:before,
        .rating > label:hover ~ label:before,
        .rating > input:checked ~ label:before {
            color: #FBBF24;
        }
    </style>

    <?php
        $statuses = ['diterima', 'diproses', 'selesai', 'diulas', 'ditolak'];
        $statusColors = [
            'diterima' => 'bg-blue-100 text-blue-800',
            'diproses' => 'bg-yellow-100 text-yellow-800',
            'selesai'  => 'bg-green-100 text-green-800',
            'diulas'   => 'bg-slate-200 text-slate-800',
            'ditolak'  => 'bg-red-100 text-red-800',
        ];
    ?>

<!-- SORT -->
<div class="flex items-center mb-4 space-x-2 overflow-x-auto pb-1">
  <span class="text-sm text-slate-600 flex-shrink-0">Urut:</span>
  <a href="/customer/monitor?sort=newest<?= $filter_status ? '&status='.$filter_status : '' ?>"
     class="flex-shrink-0 px-3 py-1 rounded-full border <?= ($sort != 'oldest') ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-600' ?>">
     Terbaru
  </a>
  <a href="/customer/monitor?sort=oldest<?= $filter_status ? '&status='.$filter_status : '' ?>"
     class="flex-shrink-0 px-3 py-1 rounded-full border <?= ($sort == 'oldest') ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-600' ?>">
     Terlama
  </a>
</div>

<!-- FILTER -->
<div class="flex items-center mb-6 space-x-2 overflow-x-auto pb-1">
  <span class="text-sm text-slate-600 flex-shrink-0">Filter:</span>
  <a href="/customer/monitor"
     class="flex-shrink-0 px-3 py-1 rounded-full border <?= empty($filter_status) ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-600' ?>">Semua</a>
  <?php foreach ($statuses as $s): ?>
    <a href="/customer/monitor?status=<?= $s ?>"
       class="flex-shrink-0 px-3 py-1 rounded-full border <?= ($filter_status == $s) ? ($statusColors[$s] . ' font-bold') : 'bg-gray-100 text-gray-600' ?>">
       <?= ucfirst($s) ?>
    </a>
  <?php endforeach; ?>
</div>


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

                    <!-- REVIEW -->
                    <?php if ($order['status'] == 'diulas' && isset($order['review_rating'])): ?>
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <h4 class="text-md font-semibold text-slate-700 mb-2">Ulasan Anda:</h4>
                            <div class="flex items-center mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-2xl <?= $i <= $order['review_rating'] ? 'text-yellow-400' : 'text-slate-300' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <?php if (!empty($order['review_comment'])): ?>
                                <p class="text-slate-600 bg-slate-50 p-3 rounded-lg text-sm">"<?= esc($order['review_comment']) ?>"</p>
                            <?php endif; ?>
                        </div>

                    <?php elseif ($order['status'] == 'selesai'): ?>
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <details>
                                <summary class="cursor-pointer text-md font-semibold text-cyan-700 hover:text-cyan-800">
                                    Beri Ulasan
                                </summary>
                                <form action="/customer/review/store" method="post" class="mt-4 space-y-4 review-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

                                    <div>
                                        <label class="block text-sm font-medium text-slate-600 mb-2">Rating</label>
                                        <div class="rating">
                                            <input type="radio" id="star5_<?= $order['order_id'] ?>" name="rating" value="5" /><label for="star5_<?= $order['order_id'] ?>" title="Luar Biasa"></label>
                                            <input type="radio" id="star4_<?= $order['order_id'] ?>" name="rating" value="4" /><label for="star4_<?= $order['order_id'] ?>" title="Bagus"></label>
                                            <input type="radio" id="star3_<?= $order['order_id'] ?>" name="rating" value="3" /><label for="star3_<?= $order['order_id'] ?>" title="Cukup"></label>
                                            <input type="radio" id="star2_<?= $order['order_id'] ?>" name="rating" value="2" /><label for="star2_<?= $order['order_id'] ?>" title="Kurang"></label>
                                            <input type="radio" id="star1_<?= $order['order_id'] ?>" name="rating" value="1" /><label for="star1_<?= $order['order_id'] ?>" title="Buruk"></label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="comment_<?= $order['order_id'] ?>" class="block text-sm font-medium text-slate-600 mb-1">Komentar</label>
                                        <textarea name="comment" id="comment_<?= $order['order_id'] ?>" rows="3" class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Bagaimana pengalaman Anda?"></textarea>
                                    </div>
                                    <div>
                                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </details>
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

    <div id="page-toast" class="fixed bottom-24 right-5 w-auto max-w-sm p-4 rounded-lg shadow-lg flex items-center z-50 transition-all duration-300 transform opacity-0 translate-y-2 hidden">
    <div id="page-toast-icon" class="mr-3 flex-shrink-0"></div>
    <p id="page-toast-message" class="font-semibold"></p>
    <button onclick="hidePageToast()" class="ml-auto pl-3 text-2xl font-bold leading-none">&times;</button>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Definisi elemen dan fungsi Toast
    const pageToastElement = document.getElementById('page-toast');
    const pageToastMessageElement = document.getElementById('page-toast-message');
    const pageToastIconContainer = document.getElementById('page-toast-icon');
    let pageToastTimeout;

    function showPageToast(type, message) {
        if (!pageToastElement) return;
        const icons = {
            success: `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
            error: `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
        };
        const colors = {
            success: 'bg-green-600 text-white',
            error: 'bg-red-600 text-white'
        };

        pageToastElement.className = pageToastElement.className.replace(/bg-\w+-\d+/g, '').replace(/text-\w+/, '');
        pageToastMessageElement.textContent = message;
        pageToastIconContainer.innerHTML = icons[type] || '';
        pageToastElement.classList.add(...(colors[type] || 'bg-blue-500 text-white').split(' '));
        pageToastElement.classList.remove('hidden');
        setTimeout(() => pageToastElement.classList.remove('opacity-0', 'translate-y-2'), 10);
        clearTimeout(pageToastTimeout);
        pageToastTimeout = setTimeout(hidePageToast, 3000);
    }

    function hidePageToast() {
        if (!pageToastElement) return;
        pageToastElement.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => pageToastElement.classList.add('hidden'), 300);
    }

    // Logika untuk validasi rating form (sekarang memanggil toast baru)
    const reviewForms = document.querySelectorAll('.review-form');
    reviewForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const selectedRating = form.querySelector('input[name="rating"]:checked');
            if (!selectedRating) {
                event.preventDefault();
                showPageToast('error', 'Rating tidak boleh kosong!');
            }
        });
    });

    // Logika untuk menangkap pesan dari controller
    <?php
        $success_message = session()->getFlashdata('success');
        if ($success_message):
    ?>
        showPageToast('success', '<?= esc($success_message, 'js') ?>');
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>