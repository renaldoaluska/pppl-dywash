<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Detail Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Menambahkan CSS untuk Peta Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<!-- Header Halaman -->
<div class="flex items-center mb-6">
    <!-- PERUBAHAN: Link kembali sekarang dinamis menggunakan previous_url() -->
    <!-- Ini akan secara otomatis mengarahkan kembali ke halaman sebelumnya, baik itu /admin/outlets atau /admin/verify -->
    <a href="<?= previous_url() ?? '/admin/dashboard' ?>" class="p-2 mr-2 rounded-full hover:bg-gray-200 transition-colors">
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
        
        <?php if (!empty($outlet['latitude']) && !empty($outlet['longitude'])): ?>
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Lokasi Peta</label>
            <div id="map" class="relative z-0 mt-2 h-64 w-full rounded-lg shadow-inner border"></div>
        </div>
        <?php endif; ?>
        
        <div class="pt-4 border-t">
            <label class="text-sm font-bold text-gray-600">Status Verifikasi</label>
            <p class="text-gray-800 capitalize font-medium"><?= esc($outlet['status']) ?></p>
        </div>
    </div>

    <!-- Tombol aksi telah dihapus dari halaman ini -->
</div>

<!-- Menambahkan JavaScript untuk Peta Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Script dipindahkan ke dalam section content -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logika untuk Peta
    const mapElement = document.getElementById('map');
    if (mapElement) {
        const lat = <?= json_encode($outlet['latitude'] ?? null) ?>;
        const lng = <?= json_encode($outlet['longitude'] ?? null) ?>;
        if(lat && lng) {
            const map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b><?= esc($outlet['name'], 'js') ?></b>').openPopup();
        }
    }
});
</script>

<?= $this->endSection() ?>
