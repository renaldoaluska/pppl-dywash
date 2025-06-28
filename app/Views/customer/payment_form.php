<?php // file: app/Views/customer/payment/index.php (contoh nama file) ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= $this->include('layout/isian') ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pembayaran Pesanan</title>
</head>
<body class="bg-slate-50">

<?= $this->include('layout/top_nav') ?>

<main class="max-w-lg mx-auto p-4 md:p-6">
    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
        <h1 class="text-2xl font-bold text-slate-800 text-center">Konfirmasi Pembayaran</h1>
        
        <div class="bg-slate-50 rounded-xl p-5 my-6 text-center">
            <h3 class="text-sm font-medium text-slate-500">Total Tagihan untuk Pesanan #<?= esc($order['order_id']) ?></h3>
            <p class="text-4xl font-bold text-red-600 mt-1">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
        </div>

        <form action="/customer/payment/process" method="post" id="payment-form" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="order_id" value="<?= esc($order['order_id']) ?>">
            <input type="hidden" name="amount" value="<?= esc($order['total_amount']) ?>">
            
            <h3 class="text-lg font-semibold text-slate-700 mb-4">Pilih Metode Pembayaran:</h3>
            
            <div class="space-y-3">
                
                <label for="cod" class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500 has-[:checked]:border-blue-500 transition-all">
                    <input type="radio" id="cod" name="payment_method" value="cod" class="hidden peer" required>
                    <div class="flex-grow">
                        <strong class="text-slate-800">COD (Cash On Delivery)</strong>
                        <small class="block text-slate-500">Bayar di tempat saat kurir tiba.</small>
                    </div>
                    <svg class="w-6 h-6 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </label>

                <label for="transfer" class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500 has-[:checked]:border-blue-500 transition-all">
                    <input type="radio" id="transfer" name="payment_method" value="transfer" class="hidden peer" required>
                    <div class="flex-grow">
                        <strong class="text-slate-800">Transfer Bank</strong>
                        <small class="block text-slate-500">No. Rek: 123-456-7890 (Bank ABC)</small>
                    </div>
                    <svg class="w-6 h-6 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </label>

                <label for="ewallet" class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500 has-[:checked]:border-blue-500 transition-all">
                    <input type="radio" id="ewallet" name="payment_method" value="ewallet" class="hidden peer" required>
                    <div class="flex-grow">
                        <strong class="text-slate-800">E-Wallet</strong>
                        <small class="block text-slate-500">Kirim ke: 081234567890 (GoPay/OVO)</small>
                    </div>
                    <svg class="w-6 h-6 text-blue-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </label>

            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition-colors text-base shadow-md hover:shadow-lg">
                    Konfirmasi Pembayaran
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
    // Fungsi untuk menampilkan toast
    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');

        if (toast && toastMessage) {
            // Set pesan dan tampilkan toast
            toastMessage.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-10');

            // Sembunyikan toast setelah 3 detik
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-10');
            }, 3000);
        }
    }

    // Cari form pembayaran berdasarkan ID yang tadi kita buat
    const paymentForm = document.getElementById('payment-form');

    // Cek hanya jika form tersebut ada di halaman ini
    if (paymentForm) {
        paymentForm.addEventListener('submit', function (event) {
            // Cek apakah ada radio button yang dipilih
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            // Jika tidak ada yang dipilih...
            if (!selectedPayment) {
                // 1. Hentikan pengiriman form
                event.preventDefault(); 
                
                // 2. Tampilkan toast error
                showToast('Pilih metode pembayaran');
            }
            // Jika sudah ada yang dipilih, form akan lanjut dikirim seperti biasa.
        });
    }
</script>
<?= $this->include('layout/footer') ?>