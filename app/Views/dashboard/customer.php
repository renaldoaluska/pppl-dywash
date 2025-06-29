<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Beranda
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="p-4">

    <section class="mb-8">
        <div class="swiper h-48 rounded-lg">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="https://placehold.co/600x400/3B82F6/white?text=Promo+1" alt="Promo 1" class="w-full h-full object-cover"/>
                </div>
                <div class="swiper-slide">
                    <img src="https://placehold.co/600x400/10B981/white?text=Diskon+Akhir+Pekan" alt="Promo 2" class="w-full h-full object-cover"/>
                </div>
                <div class="swiper-slide">
                    <img src="https://placehold.co/600x400/F59E0B/white?text=Gratis+Cuci" alt="Promo 3" class="w-full h-full object-cover"/>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Laundry Terdekat</h2>
            <a href="<?= site_url('laundry/terdekat') ?>" class="text-sm font-semibold text-blue-500">See more</a>
        </div>

        <div class="flex overflow-x-auto space-x-4 pb-4">
            <a href="<?= site_url('laundry/detail/1') ?>" class="block w-40 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-md p-3">
                    <div class="bg-gray-200 rounded-md h-24 mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1-1a2 2 0 010-2.828l1-1a2 2 0 012.828 0l2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-sm text-gray-800 truncate">Laundry ABC Keputih</h3>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        <span>300m</span>
                    </div>
                </div>
            </a>

            <a href="<?= site_url('laundry/detail/2') ?>" class="block w-40 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-md p-3">
                    <div class="bg-gray-200 rounded-md h-24 mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1-1a2 2 0 010-2.828l1-1a2 2 0 012.828 0l2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-sm text-gray-800 truncate">YUK Laundry Mulyosari</h3>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <svg class="w-3 h-3 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        <span>450m</span>
                    </div>
                </div>
            </a>
            
             </div>
    </section>

    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Penilaian Terbaik</h2>
            <a href="<?= site_url('laundry/terbaik') ?>" class="text-sm font-semibold text-blue-500">See more</a>
        </div>

        <div class="flex overflow-x-auto space-x-4 pb-4">
            <a href="<?= site_url('laundry/detail/3') ?>" class="block w-40 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-md p-3">
                    <div class="bg-gray-200 rounded-md h-24 mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1-1a2 2 0 010-2.828l1-1a2 2 0 012.828 0l2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3z"></path></svg>
                    </div>
                    <h3 class="font-semibold text-sm text-gray-800 truncate">Super Laundry</h3>
                     <div class="flex items-center text-xs text-yellow-500 mt-1">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span>4.9</span>
                    </div>
                </div>
            </a>
             </div>
    </section>
</main>

<?= $this->include('layout/bottom_nav') ?>
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
</script>

<?= $this->endSection() ?>