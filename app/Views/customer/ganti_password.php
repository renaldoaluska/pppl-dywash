<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>Ganti Password<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <form action="/customer/profil/ganti-password-process" method="post" class="bg-white p-6 rounded-lg shadow-md border border-slate-200">
        <?= csrf_field() ?>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Ganti Password</h2>
        
        <?php if (session()->getFlashdata('toast') && session()->getFlashdata('toast')['type'] === 'error'): ?>
             <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <?= esc(session()->getFlashdata('toast')['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
             <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label for="password_lama" class="block text-sm font-medium text-slate-600 mb-1">Password Lama</label>
            <input type="password" name="password_lama" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label for="password_baru" class="block text-sm font-medium text-slate-600 mb-1">Password Baru</label>
            <input type="password" name="password_baru" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label for="konfirmasi_password" class="block text-sm font-medium text-slate-600 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi_password" class="w-full border border-slate-300 rounded-lg p-2" required>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="/customer/profil" class="bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-200">Batal</a>
            <button type="submit" class="bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-slate-800">Ganti Password</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>