<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Daftar Outlet
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="max-w-3xl mx-auto p-4 md:p-6">

    <form action="/customer/outlet" method="get" class="flex items-center gap-3 mb-8">
        <div class="relative flex-grow">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" placeholder="Ketik nama atau alamat outlet..." value="<?= esc($keyword ?? '', 'attr') ?>" class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition">
        </div>
        <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors flex-shrink-0">Cari</button>
    </form>
    
    <h2 class="text-xl font-bold text-slate-700 mb-4">Daftar Outlet</h2>

    <div class="space-y-4">
        <?php if (!empty($outlets)): ?>
            <?php foreach ($outlets as $outlet): ?>
                <div class="bg-white rounded-lg shadow-md p-5 border border-slate-100">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-slate-800"><?= esc($outlet['name']) ?></h3>
                            <p class="text-sm text-slate-600 mt-1"><?= esc($outlet['address']) ?></p>
                            <div class="text-xs text-slate-500 mt-3 space-y-1">
                                <p><strong>Kontak:</strong> <?= esc($outlet['contact_phone']) ?></p>
                                <p><strong>Jam Buka:</strong> <?= esc($outlet['operating_hours']) ?></p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 mt-4 sm:mt-0 self-start sm:self-center">
                            <a href="/customer/order/create/<?= $outlet['outlet_id'] ?>" class="bg-blue-100 text-blue-700 font-bold py-2 px-4 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                + Laundry Sekarang
                            </a>
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
</main>

<?= $this->endSection() ?>