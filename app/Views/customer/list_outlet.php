<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Daftar Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="/customer/outlet" method="get" class="flex items-center gap-3 mb-2">
    <div class="relative flex-grow">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" name="search" placeholder="Ketik nama atau alamat outlet..." value="<?= esc($keyword ?? '', 'attr') ?>" class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition">
    </div>
    <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors flex-shrink-0">Cari</button>

    <?php if (!empty($keyword)): ?>
    <a href="/customer/outlet" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors flex-shrink-0">Reset</a>
    <?php endif; ?>
</form>

<form action="/customer/outlet" method="get" class="flex items-center gap-3 mb-4">
<div class="flex flex-col w-full">
    <label for="max_distance" class="text-sm text-slate-700 mb-1">Jarak Maksimal (km)</label>
    <input type="range" name="max_distance" id="max_distance"
           min="1" max="50"
           value="<?= esc($maxDistance ?? 50, 'attr') ?>"
           <?= empty($userLat) || empty($userLon) ? 'disabled class="opacity-50 cursor-not-allowed"' : '' ?>
           oninput="document.getElementById('distanceLabel').innerText = this.value + ' km'" />
    <div class="text-sm text-slate-600 mt-1">
        Sampai <span id="distanceLabel"><?= esc($maxDistance ?? 50) ?> km</span>
        <?php if (empty($userLat) || empty($userLon)): ?>
            <span class="text-red-500 ml-2">(Alamat utama belum tersedia)</span>
        <?php endif; ?>
    </div>
</div>
</form>


    
    <div class="space-y-4">
        <?php if (!empty($outlets)): ?>
            <?php foreach ($outlets as $outlet): ?>
                <div class="bg-white rounded-lg shadow-md p-5 border border-slate-100">
                    <?php if (isset($outlet['distance'])): ?>
    <div class="bg-gray-50 px-4 py-2 rounded-t-xl border-b border-slate-200 -mx-5 -mt-5 mb-2">
        <div class="flex items-center text-sm text-slate-600">
            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 10s7-6.5 7-11a7 7 0 10-14 0c0 4.5 7 11 7 11z"/>
            </svg>
            <?= number_format($outlet['distance'], 1) ?> km dari lokasi Anda
        </div>
    </div>
<?php endif; ?>

                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-slate-800"><?= esc($outlet['name']) ?></h3>
                            <p class="text-sm text-slate-600 mt-1"><?= esc($outlet['address']) ?></p>
                            <div class="text-xs text-slate-500 mt-3 space-y-1">
                                <p><strong>Kontak:</strong> <?= esc($outlet['contact_phone']) ?></p>
                                <p><strong>Jam Buka:</strong> <?= esc($outlet['operating_hours']) ?></p>
                            </div>
                        </div>
<div class="flex justify-between items-center mt-4">
    <!-- Tombol Laundry Sekarang -->
    <a href="/customer/order/create/<?= $outlet['outlet_id'] ?>"
       class="bg-blue-100 text-blue-700 font-bold py-2 px-4 rounded-lg hover:bg-blue-200 transition-colors text-sm">
        + Laundry Sekarang
    </a>

    <!-- Tombol Lihat Peta -->
<button onclick="openMapModal(<?= $outlet['latitude'] ?>, <?= $outlet['longitude'] ?>, '<?= esc(addslashes($outlet['name'])) ?>')"
    class="bg-white border border-blue-300 text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 transition-colors text-sm">
    Lihat Peta
</button>



</div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-lg p-8 text-center text-slate-500">
                <p>Tidak ada outlet yang ditemukan atau sesuai dengan pencarian Anda.</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4">
        <h2 class="text-lg font-bold mb-2">Lokasi Outlet</h2>
        <div id="mapContainer" class="w-full h-64 rounded-md border mb-4"></div>

        <!-- Tombol Kembali -->
        <div class="flex justify-end">
            <button onclick="closeMapModal()"
                class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm">
                Kembali
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    let map;
    let marker;
    let routeLine;

    const userLat = <?= json_encode($userLat) ?>;
    const userLon = <?= json_encode($userLon) ?>;

    function openMapModal(lat, lon, name = 'Outlet') {
        document.getElementById('mapModal').classList.remove('hidden');

        setTimeout(() => {
            if (!map) {
                map = L.map('mapContainer').setView([lat, lon], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                marker = L.marker([lat, lon]).addTo(map).bindPopup(name).openPopup();
            } else {
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]).bindPopup(name).openPopup();
            }

            // Tambahkan marker lokasi user
            if (userLat && userLon) {
                L.marker([userLat, userLon], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/64/64113.png',
                        iconSize: [25, 25],
                        iconAnchor: [12, 25],
                        popupAnchor: [0, -25]
                    })
                }).addTo(map).bindPopup("Lokasi Saya");

                // Hapus garis sebelumnya jika ada
                if (routeLine) {
                    map.removeLayer(routeLine);
                }

                // Tambahkan garis putus-putus dari lokasi saya ke outlet
                routeLine = L.polyline(
                    [
                        [userLat, userLon],
                        [lat, lon]
                    ],
                    {
                        color: 'blue',
                        weight: 2,
                        dashArray: '5, 10',
                        opacity: 0.7
                    }
                ).addTo(map);
            }
        }, 100);
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }
</script>
<script>
    const distanceSlider = document.getElementById('max_distance');
    const form = distanceSlider.closest('form');
    let debounceTimer;

    distanceSlider.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 500);
    });
</script>


<?= $this->endSection() ?>
