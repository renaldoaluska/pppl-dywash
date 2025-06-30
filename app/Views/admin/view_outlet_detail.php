<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Detail Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <a href="/admin/outlets" class="p-2 mr-2 rounded-full hover:bg-gray-200">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Detail Outlet</h3>
        <p class="text-sm text-gray-500 mt-1"><?= esc($outlet['name']) ?></p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-md">
    <!-- Informasi Detail Outlet -->
    <div class="space-y-4">
        <div>
            <label class="text-sm font-bold text-gray-600">Nama Outlet</label>
            <p class="text-gray-800"><?= esc($outlet['name']) ?></p>
        </div>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Alamat</label>
            <p class="text-gray-800"><?= esc($outlet['address']) ?></p>
        </div>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Kontak</label>
            <p class="text-gray-800"><?= esc($outlet['contact_phone']) ?></p>
        </div>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Jam Operasional</label>
            <p class="text-gray-800"><?= esc($outlet['operating_hours'] ?: '-') ?></p>
        </div>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Pemilik Outlet</label>
            <p class="text-gray-800"><?= esc($outlet['owner_name']) ?> (<?= esc($outlet['owner_email']) ?>)</p>
        </div>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Status Verifikasi</label>
            <p class="text-gray-800 capitalize font-medium"><?= esc($outlet['status']) ?></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
