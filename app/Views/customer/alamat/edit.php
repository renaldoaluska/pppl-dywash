<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Edit Alamat
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl p-6 shadow space-y-4 max-w-lg mx-auto">

    <div class="mb-4">
        <a href="<?= esc($redirect_to ?? '/customer/profil/alamat') ?>" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 transition-colors text-slate-700 text-sm font-semibold py-2 px-4 rounded-lg no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <h1 class="text-xl font-bold mb-4">Edit Alamat</h1>

    <?php if (session('errors')): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded-lg">
            <?php foreach (session('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/customer/profil/alamat/update/<?= $address['address_id'] ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= esc($redirect_to ?? '') ?>">

        <div>
            <label for="label" class="block text-sm font-medium text-slate-700">Label</label>
            <input type="text" name="label" id="label" class="w-full border border-slate-300 rounded-lg p-2" placeholder="Contoh: Rumah, Kantor" required value="<?= old('label', $address['label']) ?>">
        </div>

        <div>
            <label for="recipient_name" class="block text-sm font-medium text-slate-700">Nama Penerima</label>
            <input type="text" name="recipient_name" id="recipient_name" class="w-full border border-slate-300 rounded-lg p-2" required value="<?= old('recipient_name', $address['recipient_name']) ?>">
        </div>

        <div>
            <label for="phone_number" class="block text-sm font-medium text-slate-700">Nomor HP</label>
            <input type="text" name="phone_number" id="phone_number" class="w-full border border-slate-300 rounded-lg p-2" required value="<?= old('phone_number', $address['phone_number']) ?>">
        </div>

        <div>
            <label for="address_detail" class="block text-sm font-medium text-slate-700">Detail Alamat</label>
            <textarea name="address_detail" id="address_detail" rows="3" class="w-full border border-slate-300 rounded-lg p-2" required><?= old('address_detail', $address['address_detail']) ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
    <div>
        <label for="latitude" class="block text-sm font-medium text-slate-700">Latitude</label>
        <input readonly type="text" name="latitude" id="latitude" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-100 text-slate-500 opacity-80" required value="<?= old('latitude', $address['latitude']) ?>">
    </div>
    <div>
        <label for="longitude" class="block text-sm font-medium text-slate-700">Longitude</label>
        <input readonly type="text" name="longitude" id="longitude" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-100 text-slate-500 opacity-80" required value="<?= old('longitude', $address['longitude']) ?>">
    </div>
</div>

<!-- PETA -->
<div id="map" class="h-64 rounded-lg mt-4 border border-slate-300"></div>

        <div>
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition-colors">Perbarui Alamat</button>
        </div>
    </form>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Control Geocoder CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const inputLat = document.getElementById('latitude');
    const inputLng = document.getElementById('longitude');

    const defaultLatStr = '-7.250445';
    const defaultLngStr = '112.768845';

    let defaultLat = parseFloat(defaultLatStr);
    let defaultLng = parseFloat(defaultLngStr);

    if (inputLat && inputLat.value) defaultLat = parseFloat(inputLat.value);
    if (inputLng && inputLng.value) defaultLng = parseFloat(inputLng.value);

    const map = L.map('map').setView([defaultLat, defaultLng], 15);
    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // ✅ Tambahkan geocoder search bar
    L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        const latlng = e.geocode.center;
        map.setView(latlng, 16);
        marker.setLatLng(latlng);
        inputLat.value = latlng.lat.toFixed(6);
        inputLng.value = latlng.lng.toFixed(6);
    })
    .addTo(map);

    marker.on('dragend', function () {
        const { lat, lng } = marker.getLatLng();
        inputLat.value = lat.toFixed(6);
        inputLng.value = lng.toFixed(6);
    });

    // Jalankan geolocation HANYA kalau nilai input masih default
    if (
        (inputLat.value === defaultLatStr && inputLng.value === defaultLngStr) &&
        navigator.geolocation
    ) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                map.setView([latitude, longitude], 16);
                marker.setLatLng([latitude, longitude]);
                inputLat.value = latitude.toFixed(6);
                inputLng.value = longitude.toFixed(6);
            },
            () => console.warn('Akses lokasi ditolak oleh pengguna.')
        );
    } else {
        console.warn('Geolocation tidak dijalankan karena input sudah terisi atau browser tidak mendukung.');
    }
});
</script>

<?= $this->endSection() ?>
