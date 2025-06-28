<?php // file: app/Views/customer/order/history.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Riwayat Pesanan</title>

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
</head>
<body class="bg-slate-50">

<?= $this->include('layout/top_nav') ?>

<main class="max-w-3xl mx-auto p-4 md:p-6 pb-24">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-slate-700">Riwayat Pesanan</h2>
    </div>

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

    <!-- FLASH SUCCESS TOAST -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="fixed bottom-20 right-5 bg-green-600 text-white py-3 px-5 rounded-lg shadow-lg z-50">
            <p class="font-semibold"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

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
</main>

<!-- TOAST ERROR RATING -->
<div id="rating-toast" class="fixed bottom-20 right-5 bg-red-600 text-white py-3 px-5 rounded-lg shadow-lg transition-all duration-300 opacity-0 transform translate-y-2 hidden z-50">
    <p class="font-semibold">⚠️ Rating tidak boleh kosong!</p>
</div>

<?= $this->include('layout/bottom_nav') ?>
<?= $this->include('layout/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewForms = document.querySelectorAll('.review-form');
        const toastElement = document.getElementById('rating-toast');

        reviewForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const selectedRating = form.querySelector('input[name="rating"]:checked');
                if (!selectedRating) {
                    event.preventDefault();
                    toastElement.classList.remove('hidden');
                    setTimeout(() => {
                        toastElement.classList.remove('opacity-0', 'translate-y-2');
                    }, 10);
                    setTimeout(() => {
                        toastElement.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => {
                            toastElement.classList.add('hidden');
                        }, 300);
                    }, 3000);
                }
            });
        });
    });
</script>

</body>
</html>
