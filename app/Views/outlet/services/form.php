<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
    <!-- Judul dinamis: "Tambah" atau "Edit" -->
    <?= $service ? 'Edit Layanan' : 'Tambah Layanan Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white p-4 sm:p-6 rounded-xl shadow-md max-w-2xl mx-auto">
    
    <!-- Header Form -->
    <div class="pb-4 mb-6 border-b">
        <h3 class="text-lg font-semibold text-gray-700">
            <?= $service ? 'Edit Layanan' : 'Tambah Layanan Baru' ?>
        </h3>
        <!-- Menampilkan nama outlet yang layanannya sedang dikelola -->
        <p class="text-sm text-gray-500 mt-1">
            Untuk outlet: <strong><?= esc($current_outlet['name']) ?></strong>
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
    <form action="/outlet/services/store" method="post">
        <?= csrf_field() ?>
        
        <!-- Hidden input untuk ID layanan (penting untuk proses update) -->
        <input type="hidden" name="service_id" value="<?= $service['service_id'] ?? '' ?>">
        <!-- Hidden input untuk ID outlet agar controller tahu layanan ini milik siapa -->
        <input type="hidden" name="outlet_id" value="<?= $current_outlet['outlet_id'] ?>">

        <div class="space-y-6">
            <!-- Nama Layanan -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700">Nama Layanan</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="Contoh: Cuci Kering Setrika" value="<?= old('name', $service['name'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-bold text-gray-700">Harga</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="price" id="price" class="block w-full pl-8 pr-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="7000" value="<?= old('price', $service['price'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Satuan -->
            <div>
                <label for="unit" class="block text-sm font-bold text-gray-700">Satuan</label>
                <div class="mt-1">
                    <input type="text" name="unit" id="unit" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm caret-blue-600 p-2.5" placeholder="Contoh: kg, pcs, pasang, meter" value="<?= old('unit', $service['unit'] ?? '') ?>" required>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="border-t mt-8 pt-6 flex justify-end gap-3">
            <a href="/outlet/services/manage/<?= $current_outlet['outlet_id'] ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                <?= $service ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
