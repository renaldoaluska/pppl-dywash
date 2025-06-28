<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Customer - Dywash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="container max-w-4xl mx-auto my-8 px-4">
        
        <header class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Ulasan dari Customer</h1>
                <p class="mt-1 text-slate-500">Lihat feedback untuk meningkatkan kualitas layanan Anda.</p>
            </div>
            <a href="/dashboard" class="w-full sm:w-auto text-center bg-white border border-slate-300 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                Kembali ke Dashboard
            </a>
        </header>

        <div class="mt-8 space-y-6">

            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-slate-800 text-lg"><?= esc($review['customer_name']) ?></p>
                                <p class="text-sm text-slate-500">Ulasan untuk: <?= esc($review['outlet_name']) ?></p>
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
                        
                        <?php if(isset($review['created_at'])): ?>
                            <p class="text-right text-xs text-slate-400 mt-4">
                                Diulas pada: <?= date('d F Y', strtotime($review['created_at'])) ?>
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
                    <p class="mt-1 text-sm text-gray-500">Saat ini belum ada customer yang memberikan ulasan untuk outlet Anda.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>