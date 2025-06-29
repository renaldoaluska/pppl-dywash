<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Profil Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md border border-slate-200 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-indigo-500 text-white flex items-center justify-center text-2xl font-bold">
                <?= strtoupper(substr(session('name'), 0, 1)) ?>
            </div>
            <div>
                <p class="text-lg text-slate-600">Selamat bekerja,</p>
                <h2 class="text-2xl font-bold text-slate-800"><?= esc(session('name')) ?></h2>
                <p class="text-slate-500"><?= esc(session('email')) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-slate-200 overflow-hidden">
        <a href="/outlet/profile/edit" class="flex justify-between items-center p-4 border-b border-slate-200 hover:bg-slate-50 transition-colors">
            <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span class="text-slate-700 font-medium">Edit Profil</span>
            </div>
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
        <a href="/outlet/profile/ganti-password" class="flex justify-between items-center p-4 border-b border-slate-200 hover:bg-slate-50 transition-colors">
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

<?= $this->endSection() ?>
