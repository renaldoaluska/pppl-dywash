<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Ganti Password
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <form action="/customer/profil/ganti-password-process" method="post" class="bg-white p-6 rounded-lg shadow-md border border-slate-200">
        <?= csrf_field() ?>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Ganti Password</h2>
        
        <div id="page-toast" class="fixed bottom-24 right-5 w-auto max-w-sm p-4 rounded-lg shadow-lg flex items-center z-50 transition-all duration-300 transform opacity-0 translate-y-2 hidden">
            <div id="page-toast-icon" class="mr-3 flex-shrink-0"></div>
            <p id="page-toast-message" class="font-semibold"></p>
            <button onclick="hidePageToast()" class="ml-auto pl-3 text-2xl font-bold leading-none">&times;</button>
        </div>

        <div class="mb-4">
            <label for="password_lama" class="block text-sm font-medium text-slate-600 mb-1">Password Lama</label>
            <input type="password" name="password_lama" id="password_lama" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label for="password_baru" class="block text-sm font-medium text-slate-600 mb-1">Password Baru</label>
            <input type="password" name="password_baru" id="password_baru" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label for="konfirmasi_password" class="block text-sm font-medium text-slate-600 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="/customer/profil" class="bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-200">Batal</a>
            <button type="submit" class="bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800">Ganti Password</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>


<?php // Bagian untuk script khusus halaman ini ?>
<?= $this->section('script') ?>
<script>
    // Ini adalah kode JavaScript yang dimaksud
    const pageToastElement = document.getElementById('page-toast');
    const pageToastMessageElement = document.getElementById('page-toast-message');
    const pageToastIconContainer = document.getElementById('page-toast-icon');

    if (pageToastElement) {
        let pageToastTimeout;

        function showPageToast(type, message) {
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
            setTimeout(() => {
                pageToastElement.classList.remove('opacity-0', 'translate-y-2');
            }, 10);

            clearTimeout(pageToastTimeout);
            pageToastTimeout = setTimeout(hidePageToast, 4000);
        }

        function hidePageToast() {
            pageToastElement.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                pageToastElement.classList.add('hidden');
            }, 300);
        }

        // Cek flashdata dan panggil toast jika ada
        <?php $toast_data = session()->getFlashdata('toast'); if ($toast_data): ?>
            showPageToast('<?= esc($toast_data['type'], 'js') ?>', '<?= esc($toast_data['message'], 'js') ?>');
        <?php endif; ?>
    }
</script>
<?= $this->endSection() ?>