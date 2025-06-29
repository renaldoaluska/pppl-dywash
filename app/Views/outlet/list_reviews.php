<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Ulasan untuk <?= esc($outlet['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-6 border-b">
    <div>
        <!-- Menampilkan nama outlet yang sedang dilihat ulasannya -->
        <h3 class="text-lg font-semibold text-gray-700">Ulasan untuk <?= esc($outlet['name']) ?></h3>
        <p class="text-sm text-gray-500 mt-1">Lihat apa kata pelanggan tentang outlet ini.</p>
    </div>
    <a href="/outlet/my-outlets" class="w-full sm:w-auto mt-4 sm:mt-0 px-4 py-2 text-sm text-center font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
        Kembali ke Daftar Outlet
    </a>
</div>


<div class="mt-8 space-y-6">
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div>

                        <h4 class="font-bold text-gray-800"><?= esc($review['customer_name']) ?></h4>
                        <p class="text-xs text-gray-500 mt-1"><?= date('d M Y', strtotime($review['order_id'])) ?></p>

                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0 ml-4 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                        <svg class="w-5 h-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="font-bold text-sm"><?= esc(number_format($review['rating'], 1)) ?></span>
                    </div>
                </div>

                <?php if (!empty($review['comment'])): ?>
                <p class="text-gray-600 mt-4 text-sm italic">
                    "<?= esc($review['comment']) ?>"
                </p>

                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-lg p-10 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L2.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Ulasan</h3>
            <p class="mt-1 text-sm text-gray-500">Outlet ini belum menerima ulasan dari pelanggan.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>