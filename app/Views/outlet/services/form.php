<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $service ? 'Edit' : 'Tambah' ?> Layanan - Dywash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="container max-w-2xl mx-auto my-8 px-4">
        
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-slate-800"><?= $service ? 'Edit Layanan' : 'Tambah Layanan Baru' ?></h1>
                <p class="mt-1 text-slate-500">Isi detail layanan pada form di bawah ini.</p>
                <a href="/outlet/services" class="text-sm text-blue-600 hover:underline mt-4 inline-block">&larr; Kembali ke Daftar Layanan</a>
            </header>

            <hr class="my-6 border-t border-slate-200">

            <form action="/outlet/services/store" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="service_id" value="<?= $service['service_id'] ?? '' ?>">

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-slate-700">Nama Layanan</label>
                        <input type="text" id="name" name="name" value="<?= esc($service['name'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Cuci Kering Kiloan" required>
                    </div>

                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-slate-700">Harga</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-slate-900 bg-slate-200 border border-r-0 border-slate-300 rounded-l-md">
                                Rp
                            </span>
                            <input type="number" id="price" name="price" value="<?= esc($service['price'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 7000" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="unit" class="block mb-2 text-sm font-medium text-slate-700">Satuan</label>
                        <input type="text" id="unit" name="unit" value="<?= esc($service['unit'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Kg, Pcs, Setel, m2" required>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 text-right">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-6 py-3 text-center transition-colors duration-200">
                        Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>