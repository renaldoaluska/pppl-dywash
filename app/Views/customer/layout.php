<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.ico') ?>">
    <title><?= $this->renderSection('title') ?> - DyWash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">
<?php
    // PERUBAHAN DI SINI: Definisikan kondisi untuk menyembunyikan bar
    $hideBars = strpos(uri_string(), 'customer/payment') !== false || strpos(uri_string(), 'customer/order/create') !== false || strpos(uri_string(),'customer/order/detail') !== false || strpos(uri_string(),'customer/profil/alamat') !== false;
?>
<div class="flex flex-col min-h-screen">
    <?php if (!$hideBars): // JIKA BUKAN HALAMAN PAYMENT/CREATE, TAMPILKAN TOP BAR ?>
<header class="sticky top-0 bg-white shadow-sm z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 relative">
                
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-gray-800"><?= $this->renderSection('title') ?></h1>
                </div>
                
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                    <a href="<?= site_url('/') ?>">
                        <img src="<?= base_url('assets/img/logo.png') ?>" alt="DyWash Logo" class="h-12 w-auto object-contain">
                    </a>
                </div>

                <div class="flex-1 flex justify-end">
                    <div class="flex items-center">
                        <span class="mr-3 text-sm font-medium hidden sm:block"><?= esc(session('name')) ?></span>
                        <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow">
                            C
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>
    
    <?php endif; ?>
    <!-- Konten Utama -->
    <!-- Diberi padding bawah agar tidak tertutup navbar -->
    <main class="flex-grow p-4 md:p-6 lg:p-8 pb-24">
        <?= $this->renderSection('content') ?>
    </main>
    <?= $this->renderSection('script') ?>

    <?php if (!$hideBars): // JIKA BUKAN HALAMAN PAYMENT/CREATE, TAMPILKAN BOTTOM BAR ?>
    <!-- Navbar Bawah (Fixed) -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 shadow-lg z-10">
        <div class="flex justify-around h-16">
            <a href="/" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (uri_string() == 'customer/dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-xs font-medium">Home</span>
            </a><a href="/customer/outlet" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'customer/outlet') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                <span class="text-xs font-medium">Outlet</span>
            </a>
            
            <a href="/customer/monitor" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'customer/monitor') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-xs font-medium">Pesanan</span>
            </a>

            <a href="/customer/profil" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'customer/profil') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                <span class="text-xs font-medium">Profil</span>
            </a>
        </div>
    </nav>
    <?php endif; ?>

</div>
        
</body>
</html>