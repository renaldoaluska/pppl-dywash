<?= $this->extend('outlet/layout') // Menggunakan layout utama untuk outlet ?>

<?= $this->section('title') // Mengisi bagian 'title' di layout ?>
<?= $outlet ? 'Edit Outlet' : 'Tambah Outlet Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') // Memulai bagian konten utama ?>

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 mb-6 border-b border-gray-200">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">
            <?= $outlet ? 'Edit Outlet' : 'Tambah Outlet Baru' ?>
        </h3>
        <p class="text-sm text-gray-500 mt-1">Isi detail outlet Anda pada form di bawah ini.</p>
    </div>
    <a href="/outlet/my-outlets" class="w-full sm:w-auto mt-4 sm:mt-0 px-4 py-2 text-sm text-center font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-all duration-200">
        &larr; Kembali ke Daftar Outlet
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p class="font-bold">Terdapat Kesalahan</p>
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li class="ml-4 list-disc"><?= esc($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<div class="bg-white p-6 sm:p-8 rounded-xl shadow-md">
    
    <form action="/outlet/store-update" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?? '' ?>">

        <div class="space-y-6">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama Outlet</label>
                <input type="text" id="name" name="name" value="<?= old('name', $outlet['name'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Dywash Cabang Surabaya" required>
            </div>

            <div>
                <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Jl. Raya Prapen No. 123" required><?= old('address', $outlet['address'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="contact_phone" class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon (WhatsApp)</label>
                <input type="tel" id="contact_phone" name="contact_phone" value="<?= old('contact_phone', $outlet['contact_phone'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="08123456789" required>
            </div>

            <div>
                <label for="operating_hours" class="block mb-2 text-sm font-medium text-gray-700">Jam Operasional</label>
                <input type="text" id="operating_hours" name="operating_hours" value="<?= old('operating_hours', $outlet['operating_hours'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Senin - Sabtu, 08:00 - 20:00">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-6 py-3 text-center transition-colors duration-200">
                Simpan Data Outlet
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() // Mengakhiri bagian konten utama ?>