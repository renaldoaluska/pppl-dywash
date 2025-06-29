<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Ulasan Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-6 border-b">
    <div>
        <!-- Menampilkan nama outlet yang sedang dilihat ulasannya -->
        <h3 class="text-lg font-semibold text-gray-700">Ulasan untuk: <?= esc($outlet['name']) ?></h3>
        <p class="text-sm text-gray-500 mt-1">Lihat apa kata pelanggan tentang outlet ini.</p>
    </div>
    <a href="/outlet/my-outlets" class="w-full sm:w-auto mt-4 sm:mt-0 px-4 py-2 text-sm text-center font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
        Kembali ke Daftar Outlet
    </a>
</div>

<!-- Daftar Kartu Ulasan -->
<div class="space-y-4">
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <!-- Kartu Individual untuk Setiap Ulasan -->
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-bold text-gray-800"><?= esc($review['customer_name']) ?></h4>
                        <p class="text-xs text-gray-500 mt-1"><?= date('d M Y', strtotime($review['created_at'])) ?></p>
                    </div>
                    <!-- Tampilan Rating Bintang -->
                    <div class="flex items-center">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="w-5 h-5 <?= $i < $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                </div>
                <!-- Komentar Ulasan -->
                <?php if (!empty($review['comment'])): ?>
                <p class="text-gray-600 mt-4 text-sm">
                    "<?= esc($review['comment']) ?>"
                </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Tampilan jika belum ada ulasan -->
        <div class="text-center bg-white p-8 rounded-xl shadow-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Ulasan</h3>
            <p class="mt-1 text-sm text-gray-500">Outlet ini belum menerima ulasan dari pelanggan.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
