<?php // file: app/Views/customer/order/create.php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Buat Pesanan Baru</title>
</head>
<body class="bg-slate-50">

<?= $this->include('layout/top_nav') ?>

<main class="max-w-2xl mx-auto p-4 md:p-6">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800"><?= esc($outlet['name']) ?></h1>
            <p class="text-lg text-blue-600 font-semibold">Pilih layanan</p>
            <a href="/customer/outlet" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 transition-colors text-slate-700 text-sm font-semibold py-2 px-4 rounded-lg no-underline mt-4">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    <span>Kembali ke Daftar Outlet</span>
</a>
        </div>
        
        <form action="/customer/order/store" method="post" id="order-form">
            <?= csrf_field() ?>
            <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?>">
            
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
</main>

<div id="toast-notification" class="fixed bottom-5 right-5 flex items-center gap-3 bg-red-600 text-white py-3 px-5 rounded-lg shadow-lg opacity-0 translate-y-10 pointer-events-none transition-all duration-300 ease-out z-50">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <p id="toast-message" class="font-medium text-sm">Pesan error di sini.</p>
</div>

<script>
    // =========================================================================
    // FUNGSI GLOBAL: Menampilkan Toast Notification
    // =========================================================================
    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');

        if (toast && toastMessage) {
            toastMessage.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-10');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-10');
            }, 3000);
        }
    }


    // =========================================================================
    // EVENT LISTENER GLOBAL: Menangani Tombol +/- (Stepper)
    // =========================================================================
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-action]');
        if (!button) return;

        const input = button.parentElement.querySelector('input[type="number"]');
        if (!input) return;

        let currentValue = parseInt(input.value, 10) || 0;
        const minValue = parseInt(input.min, 10) || 0;

        if (button.dataset.action === 'increment') {
            currentValue++;
        } else if (button.dataset.action === 'decrement') {
            currentValue--;
        }

        input.value = (currentValue < minValue) ? minValue : currentValue;
    });


    // =========================================================================
    // VALIDASI FORM PEMESANAN (order-form)
    // =========================================================================
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
                showToast('Pilih layanan dulu');
            }
        });
    }


    // =========================================================================
    // VALIDASI FORM PEMBAYARAN (payment-form)
    // =========================================================================
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function (event) {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                event.preventDefault(); 
                showToast('Pilih metode pembayaran terlebih dahulu!');
            }
        });
    }

</script>
<?= $this->include('layout/footer') ?>