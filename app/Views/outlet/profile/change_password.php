<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Ganti Password
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <form action="/outlet/profile/update-password" method="post" class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
        <?= csrf_field() ?>
        <h2 class="text-xl font-bold text-slate-800 mb-6 pb-4 border-b">Ubah Password Anda</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul>
                    <?php foreach (session('errors') as $error) : ?>
                        <li class="list-disc ml-4"><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label for="password_lama" class="block text-sm font-bold text-slate-700 mb-1">Password Lama</label>
            <div class="flex items-center border border-gray-300 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
                <input type="password" name="password_lama" id="password_lama" class="outline-none bg-transparent flex-1 py-2.5 text-gray-700 w-full caret-blue-600" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="password_baru" class="block text-sm font-bold text-slate-700 mb-1">Password Baru</label>
            <div class="flex items-center border border-gray-300 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
                <input type="password" name="password_baru" id="password_baru" class="outline-none bg-transparent flex-1 py-2.5 text-gray-700 w-full caret-blue-600" required>
            </div>
        </div>

        <div class="mb-6">
            <label for="konfirmasi_password" class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
            <div class="flex items-center border border-gray-300 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="outline-none bg-transparent flex-1 py-2.5 text-gray-700 w-full caret-blue-600" required>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="/outlet/profile" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                Ganti Password
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
