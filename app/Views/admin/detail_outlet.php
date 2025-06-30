<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Detail Verifikasi Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Menambahkan CSS untuk Peta Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <a href="/admin/verify" class="p-2 mr-2 rounded-full hover:bg-gray-200 transition-colors">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Detail Verifikasi Outlet</h3>
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
        
        <!-- PERUBAHAN BARU: Menampilkan Peta Lokasi -->
        <?php if (!empty($outlet['latitude']) && !empty($outlet['longitude'])): ?>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Lokasi Peta</label>
            <!-- Elemen div ini akan menjadi container untuk peta -->
            <div id="map" class="mt-2 h-64 w-full rounded-lg shadow-inner border"></div>
        </div>
        <?php endif; ?>
        
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Status Saat Ini</label>
            <p class="text-gray-800 capitalize font-medium text-yellow-600"><?= esc($outlet['status']) ?></p>
        </div>
    </div>

    <!-- Tombol Aksi Verifikasi -->
    <div class="border-t mt-6 pt-6 flex flex-col sm:flex-row justify-end gap-3">
        <button type="button" 
                class="action-btn w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                data-action-url="/admin/verify/action/<?= $outlet['outlet_id'] ?>/verified"
                data-modal-title="Setujui Outlet"
                data-modal-message="Anda yakin ingin menyetujui pendaftaran outlet ini?"
                data-confirm-text="Ya, Setujui"
                data-confirm-color="bg-green-600 hover:bg-green-700">
            Setujui Outlet
        </button>
        <button type="button" 
                class="action-btn w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200"
                data-action-url="/admin/verify/action/<?= $outlet['outlet_id'] ?>/rejected"
                data-modal-title="Tolak Outlet"
                data-modal-message="Anda yakin ingin menolak pendaftaran outlet ini?"
                data-confirm-text="Ya, Tolak"
                data-confirm-color="bg-red-600 hover:bg-red-700">
            Tolak
        </button>
        
    </div>
</div>

<!-- Modal Konfirmasi Aksi -->
<div id="actionConfirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <h3 id="modalTitle" class="text-lg font-bold text-gray-900"></h3>
        <p id="modalMessage" class="text-sm text-gray-500 mt-2"></p>
        <div class="mt-6 flex justify-center gap-4">
            <button type="button" id="cancelActionBtn" class="w-full px-6 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
            <a href="#" id="confirmActionBtn" class="w-full text-center px-6 py-2 font-semibold text-white rounded-lg"></a>
        </div>
    </div>
</div>

<!-- Menambahkan JavaScript untuk Peta Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Script untuk mengontrol modal dan peta -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bagian Logika Modal (tetap sama)
    const modal = document.getElementById('actionConfirmationModal');
    if (modal) {
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const confirmBtn = document.getElementById('confirmActionBtn');
        const cancelBtn = document.getElementById('cancelActionBtn');

        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.dataset.actionUrl;
                const title = this.dataset.modalTitle;
                const message = this.dataset.modalMessage;
                const confirmText = this.dataset.confirmText;
                const confirmColor = this.dataset.confirmColor;
                
                modalTitle.textContent = title;
                modalMessage.textContent = message;
                confirmBtn.href = url;
                confirmBtn.textContent = confirmText;
                confirmBtn.className = 'w-full text-center px-6 py-2 font-semibold text-white rounded-lg ' + confirmColor;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        function hideModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        cancelBtn.addEventListener('click', hideModal);
        modal.addEventListener('click', (event) => { 
            if (event.target === modal) hideModal();
        });
    }

    // PERUBAHAN BARU: Logika untuk menampilkan peta
    const mapElement = document.getElementById('map');
    if (mapElement) {
        // Ambil data latitude dan longitude dari PHP
        const lat = <?= json_encode($outlet['latitude'] ?? null) ?>;
        const lng = <?= json_encode($outlet['longitude'] ?? null) ?>;
        
        // Inisialisasi peta
        const map = L.map('map').setView([lat, lng], 15); // Level zoom 15

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker (penanda) di lokasi outlet
        L.marker([lat, lng]).addTo(map)
            .bindPopup('<b><?= esc($outlet['name'], 'js') ?></b><br><?= esc($outlet['address'], 'js') ?>')
            .openPopup();
    }
});
</script>

<?= $this->endSection() ?>
