<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <section class="mb-4">
        <div class="swiper h-48 rounded-lg">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="https://placehold.co/600x400/3B82F6/white?text=Promo+1" alt="Promo 1" class="w-full h-full object-cover"/>
                </div>
                <div class="swiper-slide">
                    <img src="https://placehold.co/600x400/10B981/white?text=Diskon+Akhir+Pekan" alt="Promo 2" class="w-full h-full object-cover"/>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
  <?php if (!empty($allUserAddresses)): ?>
    <section class="mb-4">
        <form action="<?= current_url() ?>" method="get" id="address-filter-form">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>

                <select name="address_id" id="address_id" class="w-full bg-white border border-gray-300 rounded-lg shadow-sm py-3 pl-10 pr-3 text-sm focus:ring-blue-500 focus:border-blue-500 appearance-none">
                    <?php foreach ($allUserAddresses as $address): ?>
                        <option value="<?= $address['address_id'] ?>" <?= ($activeAddress && $activeAddress['address_id'] == $address['address_id']) ? 'selected' : '' ?>>
                            <?= esc($address['label']) ?>
                            <?= ($address['is_primary'] === true || $address['is_primary'] === 't') ? ' (Utama)' : '' ?>
                            - <?= word_limiter(esc($address['address_detail']), 5, '…') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </form>
    </section>
    <?php else: ?>
    <section class="mb-6">
        <div class="text-center p-4 border-2 border-dashed rounded-lg bg-slate-50">
            <p class="text-sm text-gray-600">Anda belum memiliki alamat tersimpan.</p>
            <a href="<?= site_url('customer/profil/alamat/tambah') ?>" class="mt-2 inline-block bg-blue-500 text-white text-sm font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors no-underline">
                + Tambah Alamat
            </a>
        </div>
    </section>
    <?php endif; ?>
    <?php if (!empty($nearestLaundries)): ?>
<section class="mb-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Laundry Terdekat</h2>
            <a href="<?= site_url('laundry/terdekat') ?>" class="text-sm font-semibold text-blue-500">See more</a>
        </div>

        <?php if (!empty($nearestLaundries)): ?>
            <div class="flex overflow-x-auto space-x-4 pb-4">
                <?php foreach ($nearestLaundries as $laundry): ?>
                <a href="javascript:void(0);" onclick='showLaundryModal(
    <?= json_encode($laundry['name']) ?>,
    <?= json_encode($laundry['profile_image_url'] ?? 'https://placehold.co/200x200/E5E7EB/A9A9A9?text=Laundry') ?>,
    <?= json_encode($laundry['address']) ?>,
    <?= json_encode($laundry['contact_phone']) ?>,
    <?= json_encode($laundry['operating_hours']) ?>,
    <?= $laundry['latitude'] ?>,
    <?= $laundry['longitude'] ?>
)' class="block w-40 flex-shrink-0">

                    <div class="bg-white rounded-lg shadow-md p-3">
                        <div class="bg-gray-200 rounded-md h-24 mb-3 flex items-center justify-center">
                            <img src="<?= esc($laundry['profile_image_url'] ?? 'https://placehold.co/200x200/E5E7EB/A9A9A9?text=Laundry') ?>" alt="<?= esc($laundry['name']) ?>" class="w-full h-full object-cover rounded-md">
                        </div>
                        <h3 class="font-semibold text-sm text-gray-800 truncate"><?= esc($laundry['name']) ?></h3>
                        <div class="flex items-center text-xs text-gray-500 mt-1">
                            <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            <?php if (isset($laundry['distance'])): ?>
                                <span><?= number_format($laundry['distance'], 1) ?> km</span>
                            <?php else: ?>
                                <span>- km</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center p-4 border-2 border-dashed rounded-lg bg-slate-50">
                <p class="text-sm text-gray-600">Pilih atau tambah alamat terlebih dahulu untuk melihat laundry terdekat.</p>
            </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>
        <!--
    <?php if (!empty($topRatedLaundries)): ?>
    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Penilaian Terbaik</h2>
            <a href="<?= site_url('laundry/terbaik') ?>" class="text-sm font-semibold text-blue-500">See more</a>
        </div>
        <div class="flex overflow-x-auto space-x-4 pb-4">
            <?php foreach ($topRatedLaundries as $laundry): ?><a href="javascript:void(0);" onclick='showLaundryModal(
    <?= json_encode($laundry['name']) ?>,
    <?= json_encode($laundry['profile_image_url'] ?? 'https://placehold.co/200x200/E5E7EB/A9A9A9?text=Laundry') ?>,
    <?= json_encode($laundry['address']) ?>,
    <?= json_encode($laundry['contact_phone']) ?>,
    <?= json_encode($laundry['operating_hours']) ?>,,
    <?= $laundry['latitude'] ?>,
    <?= $laundry['longitude'] ?>
)' class="block w-40 flex-shrink-0">

                <div class="bg-white rounded-lg shadow-md p-3">
                    <div class="bg-gray-200 rounded-md h-24 mb-3 flex items-center justify-center">
                         <img src="<?= esc($laundry['profile_image_url'] ?? 'https://placehold.co/200x200/E5E7EB/A9A9A9?text=Laundry') ?>" alt="<?= esc($laundry['name']) ?>" class="w-full h-full object-cover rounded-md">
                    </div>
                    <h3 class="font-semibold text-sm text-gray-800 truncate"><?= esc($laundry['name']) ?></h3>
                    <div class="flex items-center text-xs text-yellow-500 mt-1">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span><?= number_format($laundry['average_rating'] ?? 0, 1) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
            -->
    
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // Inisialisasi Swiper
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    // =============================================
    // SCRIPT BARU: AUTO-SUBMIT FORM SAAT DROPDOWN DIGANTI
    // =============================================
    document.getElementById('address_id').addEventListener('change', function() {
        document.getElementById('address-filter-form').submit();
    });

    let currentMap = null;

function showLaundryModal(name, imageUrl, address, phone, hours, lat, lng, distance) {
    document.getElementById('modalLaundryName').textContent = name;
    document.getElementById('modalLaundryImage').src = imageUrl;
    document.getElementById('modalLaundryImage').alt = name;
    document.getElementById('modalLaundryAddress').textContent = address;
    document.getElementById('modalLaundryPhone').textContent = phone;
    document.getElementById('modalLaundryHours').textContent = hours;

    document.getElementById('laundryModal').classList.remove('hidden');

    setTimeout(() => {
        const mapContainer = document.getElementById('modalMap');

        // Hapus instance map sebelumnya
        if (currentMap) {
            currentMap.remove();
            currentMap = null;
        }

        currentMap = L.map(mapContainer).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(currentMap);
        L.marker([lat, lng]).addTo(currentMap).bindPopup(name).openPopup();
    }, 100);
}


function closeLaundryModal() {
    document.getElementById('laundryModal').classList.add('hidden');
}


</script>


<div id="laundryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-5 relative">
    
    <h2 id="modalLaundryName" class="text-xl font-bold mb-2"></h2>
    <img id="modalLaundryImage" src="" alt="" class="w-full h-40 object-cover rounded mb-3">
    
    <div class="text-sm text-gray-700 space-y-1 mb-3">
      <p><strong>Alamat:</strong> <span id="modalLaundryAddress"></span></p>
      <p><strong>Telepon:</strong> <span id="modalLaundryPhone"></span></p>
      <p><strong>Jam Operasional:</strong> <span id="modalLaundryHours"></span></p>
      
    </div>

    <div id="modalMap" class="w-full h-48 rounded overflow-hidden mb-4"></div>

    <div class="text-center">
<div class="mt-4">
  <button onclick="closeLaundryModal()"
    class="w-full px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm rounded-full shadow-sm transition">
    Kembali
  </button>
</div>

    </div>
  </div>
</div>


<?= $this->endSection() ?>