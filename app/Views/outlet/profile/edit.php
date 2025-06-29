<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Edit Profil
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <!-- Form Edit Profil -->
    <form action="/outlet/profile/update" method="post" class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
        <?= csrf_field() ?>
        <h2 class="text-xl font-bold text-slate-800 mb-6 pb-4 border-b">Edit Informasi Profil</h2>

        <!-- Menampilkan error validasi jika ada -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li class="list-disc ml-4"><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Field Nama Lengkap -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="<?= old('name', $user['name']) ?>" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" required>
        </div>

        <!-- Field Email (Read-only) -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Alamat Email</label>
            <input type="email" value="<?= esc($user['email']) ?>" class="w-full border-gray-300 rounded-xl p-2.5 bg-slate-100 cursor-not-allowed" readonly>
            <p class="text-xs text-slate-500 mt-1">Email tidak dapat diubah.</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-end gap-3">
            <a href="/outlet/profile" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
