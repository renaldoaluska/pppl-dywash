<?= $this->extend('outlet/layout') ?>

<?= $this->section('title') ?>
Ulasan untuk <?= esc($outlet['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>



<div class="mt-8 space-y-6">
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-slate-800 text-lg"><?= esc($review['customer_name']) ?></p>
                        <p class="text-xs text-slate-500 mt-1">
                            Diulas pada: <?= date('d F Y', strtotime($review['review_date'])) ?>
                        </p>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0 ml-4 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                        <svg class="w-5 h-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="font-bold text-sm"><?= esc(number_format($review['rating'], 1)) ?></span>
                    </div>
                </div>

                <?php if (!empty($review['comment'])): ?>
                    <div class="mt-4 pl-4 border-l-4 border-slate-200">
                        <p class="text-slate-700 italic">"<?= nl2br(esc($review['comment'])) ?>"</p>
                    </div>
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