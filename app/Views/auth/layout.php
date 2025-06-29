<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.ico') ?>">
    <title><?= $this->renderSection('title') ?> - DyWash</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 antialiased">

<div class="flex flex-col min-h-screen">

    <!-- Header Atas (Sticky) -->
    <header class="sticky top-0 bg-white shadow-sm z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Judul Halaman -->
                <h1 class="text-xl font-semibold text-gray-800"><?= $this->renderSection('title') ?></h1>
                
                    <div class="flex justify-around h-16">
        <a href="<?= site_url('/') ?>" class="flex flex-col items-center justify-center w-full text-gray-500 hover:text-blue-600 transition-colors">
            
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Ilustrasi Login" class="w-32 h-32 object-contain">
        </a>
    </div>
                <!-- Profil Admin -->
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-medium hidden sm:block"></span>
                    <!--<div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow">
                        A
                    </div>-->
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <!-- Diberi padding bawah agar tidak tertutup navbar -->
    <main class="flex-grow p-4 md:p-6 lg:p-8 pb-24">
        <?= $this->renderSection('content') ?>
    </main>
</div>

</body>
</html>
