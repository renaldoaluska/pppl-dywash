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

            <div>
    <label class="block text-sm font-bold text-gray-700">Tentukan Lokasi di Peta</label>
    <p class="mt-1 text-xs text-gray-500">Cari alamat atau klik & geser penanda untuk mendapatkan koordinat.</p>
    <div id="map" class="h-80 rounded-lg mt-2 border border-slate-300"></div>
<div class="flex gap-4 mt-2">
    <div class="w-1/2">
        <label for="latitude" class="block text-sm font-bold text-gray-700 mb-1">Latitude</label>
        <input type="text" name="latitude" id="latitude" value="<?= old('latitude', $outlet['latitude'] ?? '') ?>" class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 cursor-not-allowed p-2.5" readonly>
    </div>
    <div class="w-1/2">
        <label for="longitude" class="block text-sm font-bold text-gray-700 mb-1">Longitude</label>
        <input type="text" name="longitude" id="longitude" value="<?= old('longitude', $outlet['longitude'] ?? '') ?>" class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 cursor-not-allowed p-2.5" readonly>
    </div>
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
<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />
<?= $this->endSection() ?>
<?= $this->section('script') ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Tentukan koordinat awal
    // Jika mode edit, gunakan data outlet. Jika mode tambah, gunakan default (misal: Surabaya)
    const initialLat = <?= old('latitude', $outlet['latitude'] ?? -7.2575) ?>;
    const initialLng = <?= old('longitude', $outlet['longitude'] ?? 112.7521) ?>;

    // Ambil elemen input hidden
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    // 2. Inisialisasi Peta
    const map = L.map('map').setView([initialLat, initialLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3. Buat Penanda (Marker) yang bisa digeser
    const marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(map);

    // Fungsi untuk update nilai input dan posisi marker
    function updateMarkerAndInputs(lat, lng) {
        marker.setLatLng([lat, lng]);
        latInput.value = lat;
        lngInput.value = lng;
    }
    
     // ▼▼▼ TAMBAHKAN BARIS INI ▼▼▼
    // Panggil sekali saat load untuk mengisi nilai input awal
    updateMarkerAndInputs(initialLat, initialLng);
    // ▲▲▲ SAMPAI SINI ▲▲▲
    
    // 4. Event Listener untuk interaksi
    marker.on('dragend', function(e) {
        const { lat, lng } = e.target.getLatLng();
        updateMarkerAndInputs(lat, lng);
    });

    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        updateMarkerAndInputs(lat, lng);
    });
    
    // 5. Inisialisasi Fitur Pencarian (GeoSearch)
    const provider = new GeoSearch.OpenStreetMapProvider();
    const searchControl = new GeoSearch.GeoSearchControl({
        provider: provider,
        style: 'bar',
        showMarker: false,
        autoClose: true,
        keepResult: true
    });
    map.addControl(searchControl);

    map.on('geosearch/showlocation', function (e) {
        updateMarkerAndInputs(e.location.y, e.location.x);
    });
});
</script>
    <?= $this->endSection() ?>