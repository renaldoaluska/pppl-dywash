<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Profil Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md border border-slate-200 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl font-bold">
                <?= strtoupper(substr(session('name'), 0, 1)) ?>
            </div>
            <div>
                <p class="text-lg text-slate-600">Selamat mencuci,</p>
                <h2 class="text-2xl font-bold text-slate-800"><?= esc(session('name')) ?></h2>
                <p class="text-slate-500"><?= esc(session('email')) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-slate-200">
        <a href="/customer/profil/edit" class="flex justify-between items-center p-4 border-b border-slate-200 hover:bg-slate-50 transition-colors">
            <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span class="text-slate-700 font-medium">Edit Profil</span>
            </div>
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="/customer/profil/ganti-password" class="flex justify-between items-center p-4 border-b border-slate-200 hover:bg-slate-50 transition-colors">
            <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span class="text-slate-700 font-medium">Ganti Password</span>
            </div>
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="/logout" class="flex justify-between items-center p-4 hover:bg-red-50 transition-colors">
            <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="text-red-600 font-semibold">Logout</span>
            </div>
        </a>
    </div>
</div>

<?php $toast_data = session()->getFlashdata('toast'); ?>
<div id="page-toast" class="fixed bottom-24 right-5 w-auto max-w-sm p-4 rounded-lg shadow-lg flex items-center z-50 transition-all duration-300 transform opacity-0 translate-y-2 hidden">
    <div id="page-toast-icon" class="mr-3 flex-shrink-0"></div>
    <p id="page-toast-message" class="font-semibold"></p>
    <button onclick="hidePageToast()" class="ml-auto pl-3 text-2xl font-bold leading-none">&times;</button>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    // SCRIPT TOAST ANDA YANG SUDAH ADA, TIDAK DIHILANGKAN
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

        <?php if ($toast_data): ?>
            showPageToast('<?= esc($toast_data['type'], 'js') ?>', '<?= esc($toast_data['message'], 'js') ?>');
        <?php endif; ?>
    }
</script>
<?= $this->endSection() ?>