<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $outlet ? 'Edit' : 'Tambah' ?> Outlet - Dywash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="container max-w-3xl mx-auto my-8 px-4">
        
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-slate-800"><?= $outlet ? 'Edit Outlet' : 'Tambah Outlet Baru' ?></h1>
                <p class="mt-1 text-slate-500">Isi detail outlet Anda pada form di bawah ini.</p>
                <a href="/outlet/my-outlets" class="text-sm text-blue-600 hover:underline mt-4 inline-block">&larr; Kembali ke Daftar Outlet</a>
            </header>

            <hr class="my-6 border-t border-slate-200">

            <form action="/outlet/store-update" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="outlet_id" value="<?= $outlet['outlet_id'] ?? '' ?>">

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-slate-700">Nama Outlet</label>
                        <input type="text" id="name" name="name" value="<?= esc($outlet['name'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Dywash Cabang Sudirman" required>
                    </div>

                    <div>
                        <label for="address" class="block mb-2 text-sm font-medium text-slate-700">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="4" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Jl. Jenderal Sudirman No. 123, Jakarta Pusat" required><?= esc($outlet['address'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label for="contact_phone" class="block mb-2 text-sm font-medium text-slate-700">Nomor Telepon (WhatsApp)</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="<?= esc($outlet['contact_phone'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="0812xxxxxxxx" required>
                    </div>

                    <div>
                        <label for="operating_hours" class="block mb-2 text-sm font-medium text-slate-700">Jam Operasional</label>
                        <input type="text" id="operating_hours" name="operating_hours" value="<?= esc($outlet['operating_hours'] ?? '') ?>" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Senin - Sabtu, 08:00 - 20:00">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 text-right">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-6 py-3 text-center transition-colors duration-200">
                        Simpan Data Outlet
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>