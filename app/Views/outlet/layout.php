<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - DyWash Outlet</title>
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
                
                <!-- Profil Pengguna -->
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-medium hidden sm:block"><?= esc(session('name')) ?></span>
                    <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow">
                        O
                    </div>
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
            <a href="/outlet/orders" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'outlet/orders') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="text-xs font-medium">Pesanan</span>
            </a>
            <a href="/outlet/manage" class="flex flex-col items-center justify-center w-full transition-colors duration-200 <?= (strpos(uri_string(), 'outlet/manage') !== false) ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' ?>">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="text-xs font-medium">Outlet Saya</span>
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
