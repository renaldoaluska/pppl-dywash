<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Detail Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <a href="/outlet/my-outlets" class="p-2 mr-2 rounded-full hover:bg-gray-200 transition-colors">
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
            <label class="text-sm font-bold text-gray-600">Status Verifikasi</label>
            <p class="text-gray-800 capitalize"><?= esc($outlet['status']) ?></p>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="border-t mt-6 pt-6 flex flex-col sm:flex-row justify-end gap-3">
        <button type="button" data-outlet-id="<?= $outlet['outlet_id'] ?>" class="delete-outlet-btn w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200">
            Hapus
        </button>
        <a href="/outlet/my-outlets/edit/<?= $outlet['outlet_id'] ?>" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Edit Outlet
        </a>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Outlet -->
<div id="deleteOutletModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <p class="text-lg font-medium text-gray-800 mt-4">Anda yakin ingin menghapus outlet ini?</p>
        <p class="text-sm text-gray-500 mt-2">Semua data terkait (layanan, pesanan, ulasan) juga akan terhapus. Aksi ini tidak dapat dibatalkan.</p>
        <form id="deleteOutletForm" action="" method="post" class="mt-6 flex justify-center gap-4">
            <?= csrf_field() ?>
            <button type="button" id="cancelDeleteBtn" class="w-full px-6 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tidak, Batal</button>
            <button type="submit" class="w-full px-6 py-2 font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Ya, Hapus</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteOutletModal');
    if (!modal) return;
    const deleteForm = document.getElementById('deleteOutletForm');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    document.querySelectorAll('.delete-outlet-btn').forEach(button => {
        button.addEventListener('click', function() {
            const outletId = this.dataset.outletId;
            deleteForm.action = `/outlet/my-outlets/delete/${outletId}`;
            modal.classList.remove('hidden');
        });
    });
    function hideModal() { modal.classList.add('hidden'); }
    cancelBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', (event) => { if (event.target === modal) hideModal(); });
});
</script>
<?= $this->endSection() ?>
