<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>Edit Profil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <form action="/customer/profil/update" method="post" class="bg-white p-6 rounded-lg shadow-md border border-slate-200">
        <?= csrf_field() ?>
        <h2 class="text-xl font-bold text-slate-800 mb-4">Edit Informasi Profil</h2>

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
            <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="<?= old('name', $user['name']) ?>" class="w-full border border-slate-300 rounded-lg p-2">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-600 mb-1">Alamat Email</label>
            <input type="email" value="<?= esc($user['email']) ?>" class="w-full border-slate-300 rounded-lg p-2 bg-slate-100 cursor-not-allowed" readonly>
            <p class="text-xs text-slate-500 mt-1">Email tidak dapat diubah.</p>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="/customer/profil" class="bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-200">Batal</a>
            <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>