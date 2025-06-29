<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Kelola Layanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-6 border-b">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Layanan untuk: <?= esc($current_outlet['name']) ?></h3>
        <p class="text-sm text-gray-500 mt-1">Tambah, edit, atau hapus layanan yang tersedia di outlet ini.</p>
    </div>
    <a href="/outlet/services/create/<?= $current_outlet['outlet_id'] ?>" class="w-full sm:w-auto mt-4 sm:mt-0 px-4 py-2 text-sm text-center font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
        Tambah Layanan Baru
    </a>
</div>

<!-- Menampilkan pesan sukses/error jika ada -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow" role="alert">
        <p><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>

<!-- Daftar Kartu Layanan -->
<div class="space-y-4">
    <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
            <!-- Kartu Individual untuk Setiap Layanan -->
            <div class="bg-white rounded-xl shadow-md flex items-center justify-between p-4">
                <div>
                    <h4 class="font-bold text-gray-800"><?= esc($service['name']) ?></h4>
                    <p class="text-sm text-gray-500">
                        Rp <?= number_format($service['price'], 0, ',', '.') ?> / <?= esc($service['unit']) ?>
                    </p>
                </div>
                <!-- Tombol Aksi -->
                <div class="flex items-center gap-2">
                    <!-- Tombol Edit -->
                    <a href="/outlet/services/edit/<?= $service['service_id'] ?>" class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <!-- Tombol Hapus (Trigger untuk Modal) -->
                    <button type="button" data-service-id="<?= $service['service_id'] ?>" class="delete-btn p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center bg-white p-8 rounded-xl shadow-md">
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Layanan</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan layanan apapun untuk outlet ini.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteConfirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <p class="text-lg font-medium text-gray-800 mt-4">Anda yakin ingin menghapus layanan ini?</p>
        <p class="text-sm text-gray-500 mt-2">Aksi ini tidak dapat dibatalkan.</p>
        
        <!-- Form untuk mengirim request delete -->
        <form id="deleteForm" action="" method="post" class="mt-6 flex justify-center gap-4">
             <?= csrf_field() ?>
            <button type="button" id="confirmBtnTidak" class="w-full px-6 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Tidak, Batal</button>
            <button type="submit" class="w-full px-6 py-2 font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Ya, Hapus</button>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmationModal');
    if (!modal) return;

    const deleteForm = document.getElementById('deleteForm');
    const confirmBtnTidak = document.getElementById('confirmBtnTidak');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            const serviceId = this.dataset.serviceId;
            // Set action form dinamis berdasarkan serviceId yang diklik
            deleteForm.action = `/outlet/services/delete/${serviceId}`;
            modal.classList.remove('hidden');
        });
    });

    function hideModal() {
        modal.classList.add('hidden');
    }

    confirmBtnTidak.addEventListener('click', hideModal);
    modal.addEventListener('click', (event) => { 
        if (event.target === modal) hideModal();
    });
});
</script>

<?= $this->endSection() ?>
