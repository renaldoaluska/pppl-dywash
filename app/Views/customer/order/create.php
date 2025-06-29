<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Buat Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-2xl shadow-lg p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800"><?= esc($outlet['name']) ?></h1>
        <p class="text-lg text-blue-600 font-semibold">Pilih layanan</p>
        <a href="/customer/outlet" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 transition-colors text-slate-700 text-sm font-semibold py-2 px-4 rounded-lg no-underline mt-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Daftar Outlet</span>
        </a>
    </div>

    <form action="/customer/order/store" method="post" id="order-form">
        <?= csrf_field() ?>
        <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?>">
        <input type="hidden" name="address_id" id="address_id" value="<?= $primary_address ? $primary_address['address_id'] : '' ?>" required>

        <!-- ================================
             BLOK PILIH ALAMAT PENGIRIMAN
             ================================ -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2">Alamat Pengiriman</h3>

            <div id="selected-address" class="border rounded-lg p-4 transition border-blue-500 bg-blue-50">
                <?php if ($primary_address): ?>
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold text-slate-800" id="selected-label"><?= esc($primary_address['label']) ?></span>
                        <span class="text-xs bg-green-500 text-white rounded-full px-2 py-0.5">UTAMA</span>
                    </div>
                    <div class="text-sm text-slate-700" id="selected-recipient"><?= esc($primary_address['recipient_name']) ?> (<?= esc($primary_address['phone_number']) ?>)</div>
                    <div class="text-sm text-slate-500" id="selected-detail"><?= esc($primary_address['address_detail']) ?></div>
                <?php else: ?>
                    <p class="text-slate-500">Kamu belum punya alamat utama.</p>
                <?php endif; ?>
            </div>

            <div class="flex justify-center mt-4 gap-4">
                <?php if (!empty($other_addresses)): ?>
                    <button type="button" id="show-modal-address" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z"></path>
                            <circle cx="12" cy="9" r="2.5"></circle>
                        </svg>
                        <span>Ganti</span>
                    </button>
                <?php endif; ?>

                <a href="/customer/profil/alamat/tambah?redirect_to=/customer/order/create/<?= $outlet['outlet_id'] ?>" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah</span>
                </a>
            </div>
        </div>

        <!-- ================================
             BLOK PILIH LAYANAN
             ================================ -->
        <h3 class="text-lg font-semibold text-slate-700 mb-4 border-b pb-2">Pilih Layanan</h3>

        <div class="space-y-3">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg">
                        <div>
                            <strong class="text-slate-800 font-medium"><?= esc($service['name']) ?></strong><br>
                            <span class="text-sm text-slate-500">Rp <?= number_format($service['price'], 0, ',', '.') ?> / <?= esc($service['unit']) ?></span>
                        </div>
                        <div class="flex items-center">
                            <button type="button" data-action="decrement" class="bg-slate-200 text-slate-700 hover:bg-slate-300 h-8 w-8 rounded-l-lg cursor-pointer font-bold text-lg transition-colors">-</button>
                            <input type="number" name="services[<?= $service['service_id'] ?>]" id="service_<?= $service['service_id'] ?>" min="0" value="0" class="w-14 h-8 text-center border-t border-b border-slate-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none">
                            <button type="button" data-action="increment" class="bg-slate-200 text-slate-700 hover:bg-slate-300 h-8 w-8 rounded-r-lg cursor-pointer font-bold text-lg transition-colors">+</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 text-center py-4">Outlet ini belum memiliki layanan yang tersedia.</p>
            <?php endif; ?>
        </div>

        <div class="mt-8">
            <label for="customer_notes" class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan (opsional)</label>
            <textarea name="customer_notes" id="customer_notes" rows="4" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition" placeholder="Contoh: Tolong jangan pakai pewangi..."></textarea>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors text-base shadow-md hover:shadow-lg">
                Buat Pesanan
            </button>
        </div>
    </form>
</div>

<!-- ================================
     MODAL GANTI ALAMAT
     ================================ -->
<?php if ($primary_address || !empty($other_addresses)): ?>
<div id="modal-address" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg max-w-md w-full p-6">
    <h2 class="text-lg font-bold mb-4">Pilih Alamat</h2>
    <div class="space-y-3">

      <?php if ($primary_address): ?>
        <label class="block cursor-pointer">
          <input type="radio" name="modal_address_id" value="<?= $primary_address['address_id'] ?>" class="peer hidden">
          <div class="border rounded-lg p-4 transition border-slate-300 bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50">
            <div class="flex justify-between items-center mb-1">
              <span class="font-semibold text-slate-800"><?= esc($primary_address['label']) ?></span>
              <span class="text-xs bg-green-500 text-white rounded-full px-2 py-0.5">UTAMA</span>
            </div>
            <div class="text-sm text-slate-700"><?= esc($primary_address['recipient_name']) ?> (<?= esc($primary_address['phone_number']) ?>)</div>
            <div class="text-sm text-slate-500"><?= esc($primary_address['address_detail']) ?></div>
          </div>
        </label>
      <?php endif; ?>

      <?php foreach ($other_addresses as $address): ?>
        <label class="block cursor-pointer">
          <input type="radio" name="modal_address_id" value="<?= $address['address_id'] ?>" class="peer hidden">
          <div class="border rounded-lg p-4 transition border-slate-300 bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50">
            <div class="font-semibold text-slate-800"><?= esc($address['label']) ?></div>
            <div class="text-sm text-slate-700"><?= esc($address['recipient_name']) ?> (<?= esc($address['phone_number']) ?>)</div>
            <div class="text-sm text-slate-500"><?= esc($address['address_detail']) ?></div>
          </div>
        </label>
      <?php endforeach; ?>

    </div>
    <div class="mt-6 flex justify-end">
      <button type="button" id="close-modal-address" class="bg-gray-300 hover:bg-gray-400 text-slate-800 py-2 px-4 rounded">Tutup</button>
    </div>
  </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
const showModalAddress = document.getElementById('show-modal-address');
const modalAddress = document.getElementById('modal-address');
const closeModalAddress = document.getElementById('close-modal-address');
const addressInput = document.getElementById('address_id');

const selectedLabel = document.getElementById('selected-label');
const selectedRecipient = document.getElementById('selected-recipient');
const selectedDetail = document.getElementById('selected-detail');

if (showModalAddress && modalAddress && closeModalAddress) {
    showModalAddress.addEventListener('click', () => {
        const currentId = addressInput.value;
        modalAddress.querySelectorAll('input[name="modal_address_id"]').forEach(input => {
            input.checked = input.value === currentId;
        });
        modalAddress.classList.remove('hidden');
    });

    closeModalAddress.addEventListener('click', () => {
        modalAddress.classList.add('hidden');
    });

    modalAddress.querySelectorAll('input[name="modal_address_id"]').forEach(input => {
        input.addEventListener('change', () => {
            const parent = input.closest('label');
            const labelDiv = parent.querySelector('.font-semibold');

            addressInput.value = input.value;
            selectedLabel.textContent = labelDiv.textContent.replace('UTAMA', '').trim();
            selectedRecipient.textContent = parent.querySelector('.text-sm.text-slate-700').textContent;
            selectedDetail.textContent = parent.querySelector('.text-sm.text-slate-500').textContent;

            const badge = labelDiv.nextElementSibling && labelDiv.nextElementSibling.textContent.includes('UTAMA');
            const selectedBadge = selectedLabel.parentElement.querySelector('span.bg-green-500');
            if (badge) {
                if (!selectedBadge) {
                    selectedLabel.insertAdjacentHTML('afterend', '<span class="text-xs bg-green-500 text-white rounded-full px-2 py-0.5">UTAMA</span>');
                }
            } else {
                if (selectedBadge) selectedBadge.remove();
            }

            modalAddress.classList.add('hidden');
        });
    });
}

// Quantity increment decrement
document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-action]');
    if (!button) return;
    const input = button.parentElement.querySelector('input[type="number"]');
    if (!input) return;
    let currentValue = parseInt(input.value, 10) || 0;
    const minValue = parseInt(input.min, 10) || 0;
    if (button.dataset.action === 'increment') currentValue++;
    else if (button.dataset.action === 'decrement') currentValue--;
    input.value = (currentValue < minValue) ? minValue : currentValue;
});

// Form validation
const orderForm = document.getElementById('order-form');
if (orderForm) {
    orderForm.addEventListener('submit', function(event) {
        const quantityInputs = orderForm.querySelectorAll('input[type="number"]');
        let totalQuantity = 0;
        quantityInputs.forEach(input => {
            totalQuantity += parseInt(input.value, 10) || 0;
        });
        if (totalQuantity === 0) {
            event.preventDefault();
            alert('Pilih layanan dulu');
        }
    });
}
</script>
<?= $this->endSection() ?>
