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
    .rating > input { display: none; }
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
    $statuses = ['diterima', 'diambil', 'dikirim', 'selesai', 'diulas', 'ditolak'];
    $statusColors = [
        'diterima' => 'bg-blue-100 text-blue-800',
        'diambil'  => 'bg-purple-100 text-purple-800',
        'dikirim'  => 'bg-purple-100 text-purple-800',
        'selesai'  => 'bg-green-100 text-green-800',
        'diulas'   => 'bg-green-100 text-green-800',
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

<!-- STATUS PEMBAYARAN (Kotak Abu di Atas) -->
<div class="bg-gray-50 px-4 py-2 rounded-t-xl border-b border-slate-200 -mx-5 -mt-5 mb-2">
  <?php if ($order['payment_status'] == 'pending'): ?>
    <p class="text-yellow-600 text-sm font-medium">Menunggu pembayaran</p>
  <?php elseif ($order['payment_status'] == 'lunas'): ?>
    <p class="text-green-600 text-sm font-medium">Lunas</p>
  <?php elseif ($order['payment_status'] == 'gagal'): ?>
    <p class="text-red-600 text-sm font-medium">Pembayaran gagal</p>
  <?php elseif ($order['payment_status'] == 'cod'): ?>
    <p class="text-blue-600 text-sm font-medium">COD</p>
  <?php else: ?>
    <p class="text-gray-500 text-sm font-medium">Belum konfirmasi</p>
  <?php endif; ?>
</div>


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

  <!-- LINK LIHAT DETAIL & ULASAN -->
   
  <div class="bg-gray-50 px-4 py-3 rounded-b-xl flex flex-wrap gap-2 justify-between mt-4 -mx-5 -mb-5 border-t border-slate-200">
  <div class="flex flex-wrap gap-2">
    <?php if ($order['status'] == 'diulas' && isset($order['review_rating'])): ?>
      <button onclick="showReviewModal(<?= $order['review_rating'] ?>, '<?= esc($order['review_comment']) ?>')"
        class="px-4 py-1.5 bg-white border border-slate-300 text-slate-700 text-sm rounded-full shadow-sm hover:bg-slate-50 transition">
        Lihat Ulasan
      </button>
    <?php elseif ($order['status'] == 'selesai'): ?>
      <button onclick="showReviewFormModal(<?= $order['order_id'] ?>)"
        class="px-4 py-1.5 bg-white border border-amber-400 text-amber-600 text-sm rounded-full shadow-sm hover:bg-amber-50 transition">
        Beri Ulasan
      </button>
    <?php endif; ?>

    <?php if (empty($order['payment_method'])): ?>
      <a href="/customer/payment/<?= $order['order_id'] ?>"
        class="px-4 py-1.5 bg-white border border-emerald-400 text-emerald-600 text-sm rounded-full shadow-sm hover:bg-emerald-50 transition">
        Konfirmasi Bayar
      </a>
    <?php endif; ?>
  </div>

  <a href="/customer/order/detail/<?= $order['order_id'] ?>"
    class="px-4 py-1.5 bg-white border border-slate-300 text-slate-700 text-sm rounded-full shadow-sm hover:bg-cyan-50 transition">
    Lihat Detail
  </a>
</div>

</div>
<?php endforeach; ?>
<?php else: ?>
<div class="bg-white rounded-lg p-8 text-center text-slate-500">
  <p>Anda belum memiliki riwayat pesanan.</p>
</div>
<?php endif; ?>
</div>

<!-- TOAST -->
<div id="page-toast" class="fixed bottom-24 right-5 w-auto max-w-sm p-4 rounded-lg shadow-lg flex items-center z-50 transition-all duration-300 transform opacity-0 translate-y-2 hidden bg-red-600 text-white">
  <p id="page-toast-message" class="font-semibold"></p>
</div>

<!-- MODAL LIHAT ULASAN -->
<div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-lg font-bold mb-4">Ulasan Anda</h3>
    <div id="reviewStars" class="flex mb-3"></div>
    <p id="reviewComment" class="text-gray-700"></p>
    <button onclick="closeReviewModal()" class="mt-4 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded">Tutup</button>
  </div>
</div>

<!-- MODAL FORM ULASAN -->
<div id="reviewFormModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-lg font-bold mb-4">Beri Ulasan</h3>
    <form action="/customer/review/store" method="post" class="space-y-4 review-form" onsubmit="return validateReviewForm();">
      <?= csrf_field() ?>
      <input type="hidden" name="order_id" id="reviewOrderId">
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-2">Rating</label>
        <div class="rating">
          <?php for ($i=5; $i>=1; $i--): ?>
            <input type="radio" id="star<?= $i ?>_modal" name="rating" value="<?= $i ?>" /><label for="star<?= $i ?>_modal"></label>
          <?php endfor; ?>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Komentar</label>
        <textarea name="comment" rows="3" class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-300" placeholder="Bagaimana pengalaman Anda?"></textarea>
      </div>
      <div class="flex justify-end">
        <button type="button" onclick="closeReviewFormModal()" class="mr-2 px-4 py-2 rounded border">Batal</button>
        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded">Kirim</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
function showPageToast(message) {
  const toast = document.getElementById('page-toast');
  const msg = document.getElementById('page-toast-message');
  msg.textContent = message;
  toast.classList.remove('hidden', 'opacity-0', 'translate-y-2');
  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-y-2');
    setTimeout(() => toast.classList.add('hidden'), 300);
  }, 3000);
}

function validateReviewForm() {
  const selectedRating = document.querySelector('#reviewFormModal input[name="rating"]:checked');
  if (!selectedRating) {
    showPageToast('Rating tidak boleh kosong!');
    return false; // prevent submit
  }
  return true;
}

function showReviewModal(rating, comment) {
  const modal = document.getElementById('reviewModal');
  const starsContainer = document.getElementById('reviewStars');
  const commentContainer = document.getElementById('reviewComment');
  starsContainer.innerHTML = '';
  for (let i = 1; i <= 5; i++) {
    const star = document.createElement('span');
    star.textContent = '★';
    star.className = 'text-2xl ' + (i <= rating ? 'text-yellow-400' : 'text-slate-300');
    starsContainer.appendChild(star);
  }
  commentContainer.textContent = comment || '-';
  modal.classList.remove('hidden');
}
function closeReviewModal() {
  document.getElementById('reviewModal').classList.add('hidden');
}
function showReviewFormModal(orderId) {
  document.getElementById('reviewOrderId').value = orderId;
  document.getElementById('reviewFormModal').classList.remove('hidden');
}
function closeReviewFormModal() {
  document.getElementById('reviewFormModal').classList.add('hidden');
}
</script>
<?= $this->endSection() ?>
