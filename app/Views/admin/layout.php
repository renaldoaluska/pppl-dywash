<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - DyWash Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 antialiased">

<div class="flex flex-col min-h-screen">

    <!-- Header Atas (Sticky) -->
    <header class="sticky top-0 bg-white shadow-sm z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Placeholder Kiri (untuk mendorong logo ke tengah) -->
                <div class="w-1/3">
                    <!-- Kosong -->
                </div>

                <!-- Logo di Tengah -->
                <div class="w-1/3 flex justify-center">
                    <a href="/dashboard">
                        <img src="<?= base_url('assets/img/logo.png') ?>" alt="DyWash Logo" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Placeholder Kanan (untuk mendorong logo ke tengah) -->
                <div class="w-1/3">
                    <!-- Kosong -->
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <!-- Diberi padding bawah agar tidak tertutup navbar -->
    <main class="flex-grow p-4 md:p-6 lg:p-8 pb-24">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Navbar Bawah (Fixed) -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 shadow-lg z-10">
        <div class="flex justify-around h-16">
            <a href="/dashboard" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (uri_string() == 'dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="/admin/verify" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'admin/verify') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-medium">Outlet</span>
            </a>
            <a href="/admin/payments/verify" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'admin/payments/verify') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                 <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
                <span class="text-xs font-medium">Pesanan</span>
            </a>
             <a href="/logout" class="flex flex-col items-center justify-center w-full text-gray-500 hover:text-red-600 transition-colors duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="text-xs font-medium">Logout</span>
            </a>
        </div>
    </nav>

</div>

</body>
</html>
