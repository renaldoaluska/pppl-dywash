<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Detail Pembayaran #<?= esc($payment['payment_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <a href="/admin/payments/verify" class="p-2 mr-2 rounded-full hover:bg-gray-200">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Detail Pembayaran</h3>
        <p class="text-sm text-gray-500 mt-1">Pesanan oleh: <?= esc($payment['customer_name']) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Rincian Pesanan -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h4 class="text-md font-bold text-gray-800 mb-3">Rincian Layanan</h4>
             <div class="divide-y divide-gray-200">
                <?php foreach($order_items as $item): ?>
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800"><?= esc($item['service_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= esc($item['quantity']) ?> <?= esc($item['unit']) ?></p>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ringkasan & Aksi -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
            <h4 class="text-md font-bold text-gray-800">Ringkasan Pembayaran</h4>
            
            <div class="flex justify-between text-sm pt-4 border-t">
                <span class="text-gray-500">Outlet Pesanan</span>
                <span class="font-semibold text-gray-800 text-right"><?= esc($payment['outlet_name']) ?></span>
            </div>
            
            <div class="flex justify-between text-sm pt-4 border-t">
                <span class="text-gray-500">Metode</span>
                <span class="font-semibold text-gray-800 capitalize"><?= esc($payment['payment_method']) ?></span>
            </div>
             <div class="flex justify-between items-center border-t pt-4">
                <span class="text-lg font-bold text-gray-900">Total</span>
                <span class="text-xl font-bold text-blue-600">Rp <?= number_format($payment['amount'], 0, ',', '.') ?></span>
             </div>
             <!-- Blok Aksi yang Diperbarui -->
             <div class="border-t mt-4 pt-4 flex gap-2">
                <!-- Tombol Aksi Utama -->
                <button type="button" 
                        class="action-btn flex-grow text-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                        data-action-url="/admin/payments/verify/action/<?= $payment['payment_id'] ?>"
                        data-modal-title="Konfirmasi Verifikasi"
                        data-modal-message="Anda yakin ingin memverifikasi pembayaran ini? Pesanan akan diteruskan ke outlet."
                        data-confirm-text="Ya, Verifikasi"
                        data-confirm-color="bg-green-600 hover:bg-green-700">
                    Verifikasi Pembayaran
                </button>
                <!-- Dropdown untuk Aksi Lainnya -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-md shadow-lg z-20" x-transition>
                        <button type="button" 
                                class="action-btn block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                data-action-url="/admin/payments/fail/<?= $payment['payment_id'] ?>"
                                data-modal-title="Konfirmasi Pembatalan"
                                data-modal-message="Anda yakin ingin membatalkan pembayaran ini? Status pesanan akan menjadi 'Ditolak'."
                                data-confirm-text="Ya, Batalkan"
                                data-confirm-color="bg-red-600 hover:bg-red-700">
                            Batalkan Pembayaran
                        </button>
                        <button type="button" 
                                class="action-btn block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                data-action-url="/admin/payments/fail/<?= $payment['payment_id'] ?>"
                                data-modal-title="Konfirmasi Refund"
                                data-modal-message="Anda yakin ingin memproses refund? Status pesanan akan menjadi 'Ditolak'."
                                data-confirm-text="Ya, Refund"
                                data-confirm-color="bg-red-600 hover:bg-red-700">
                            Proses Refund
                        </button>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Aksi (Dinamis) -->
<div id="actionConfirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <h3 id="modalTitle" class="text-lg font-bold text-gray-900"></h3>
        <p id="modalMessage" class="text-sm text-gray-500 mt-2"></p>
        <div class="mt-6 flex justify-center gap-4">
            <button type="button" id="cancelActionBtn" class="w-full px-6 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
            <a href="#" id="confirmActionBtn" class="w-full text-center px-6 py-2 font-semibold text-white rounded-lg"></a>
        </div>
    </div>
</div>

<!-- Menambahkan script Alpine.js untuk dropdown -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- PERBAIKAN: Memindahkan script ke dalam section 'content' -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('actionConfirmationModal');
    if (!modal) return;

    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const confirmBtn = document.getElementById('confirmActionBtn');
    const cancelBtn = document.getElementById('cancelActionBtn');

    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Ambil data dari atribut tombol yang diklik
            const url = this.dataset.actionUrl;
            const title = this.dataset.modalTitle;
            const message = this.dataset.modalMessage;
            const confirmText = this.dataset.confirmText;
            const confirmColor = this.dataset.confirmColor;
            
            // Isi konten modal secara dinamis
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            confirmBtn.href = url;
            confirmBtn.textContent = confirmText;
            confirmBtn.className = 'w-full text-center px-6 py-2 font-semibold text-white rounded-lg ' + confirmColor;
            
            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    cancelBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', (event) => { 
        if (event.target === modal) hideModal();
    });
});
</script>

<?= $this->endSection() ?>
