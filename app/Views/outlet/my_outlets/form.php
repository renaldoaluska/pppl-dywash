<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
    <!-- Judul dinamis: "Tambah" atau "Edit" -->
    <?= $outlet ? 'Edit Outlet' : 'Tambah Outlet Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md max-w-2xl mx-auto">
    
    <!-- Header Form -->
    <div class="pb-4 mb-6 border-b">
        <h3 class="text-lg font-semibold text-gray-700">
            <?= $outlet ? 'Edit Informasi Outlet' : 'Daftarkan Outlet Baru Anda' ?>
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            <?= $outlet ? 'Perbarui detail outlet Anda di bawah ini.' : 'Isi detail di bawah ini untuk mendaftarkan outlet baru ke platform.' ?>
        </p>
    </div>

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

    <!-- Form -->
    <form action="/outlet/my-outlets/store" method="post">
        <?= csrf_field() ?>
        
        <!-- Hidden input untuk ID outlet (penting untuk proses update) -->
        <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?? '' ?>">

        <div class="space-y-6">
            <!-- Nama Outlet -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700">Nama Outlet</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="Contoh: DyWash Cabang Kertajaya" value="<?= old('name', $outlet['name'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="address" class="block text-sm font-bold text-gray-700">Alamat Lengkap</label>
                <div class="mt-1">
                    <textarea id="address" name="address" rows="3" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="Jl. Raya Kertajaya Indah No. 1, Surabaya" required><?= old('address', $outlet['address'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Nomor Telepon / Kontak -->
            <div>
                <label for="contact_phone" class="block text-sm font-bold text-gray-700">Nomor Telepon</label>
                <div class="mt-1">
                    <input type="tel" name="contact_phone" id="contact_phone" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="081234567890" value="<?= old('contact_phone', $outlet['contact_phone'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div>
                <label for="operating_hours" class="block text-sm font-bold text-gray-700">Jam Operasional</label>
                <div class="mt-1">
                    <input type="text" name="operating_hours" id="operating_hours" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="Contoh: Senin - Sabtu, 08:00 - 20:00" value="<?= old('operating_hours', $outlet['operating_hours'] ?? '') ?>">
                </div>
                 <p class="mt-2 text-xs text-gray-500">Kosongkan jika tidak ada.</p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="border-t mt-8 pt-6 flex justify-end gap-3">
            <a href="/outlet/my-outlets" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                <?= $outlet ? 'Simpan Perubahan' : 'Daftarkan Outlet' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
