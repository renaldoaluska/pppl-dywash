<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.ico') ?>">
  <title>Selamat Datang di DyWash</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50">

  <div class="min-h-screen flex flex-col p-6">

    <main class="flex-1 flex flex-col justify-center">
      
      <div class="w-full max-w-xs mx-auto">
        <header class="mb-12 text-left">
          <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
            <span class="block">Do Your Wash</span>
            <span class="block text-2xl font-medium text-gray-500 mt-1">with</span>
            <span class="block">DyWash!</span>
          </h1>
        </header>
      </div>

      <div class="flex flex-col items-center text-center">
        <div class="bg-white rounded-full w-24 h-24 flex items-center justify-center shadow-md mb-12">
          <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
              <rect width="36" height="28" x="6" y="10" rx="4" fill="#f3f4f6"/>
              <path d="M6 34l8-10 7 9 7-11 8 12" stroke="#d1d5db" stroke-width="2" fill="none"/>
          </svg>
        </div>

        <div class="w-full max-w-xs text-center">
    <a href="<?= site_url('auth/login') ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition transform hover:scale-105">
        Masuk
    </a>
    
    <p class="text-sm text-gray-500 mt-4">
      Belum punya akun? 
      <button type="button" id="open-register-modal" class="font-semibold text-blue-600 hover:underline focus:outline-none">
        Daftar di sini
      </button>
    </p>
</div>
      </div>

    </main>

    <footer class="w-full max-w-xs mx-auto">
      <div class="flex items-center my-4">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-3 text-gray-500 text-xs font-medium">Atau lanjutkan dengan</span>
        <div class="flex-grow border-t border-gray-300"></div>
      </div>
      <div class="flex justify-center gap-4">
        <button class="bg-white border border-gray-300 rounded-full w-12 h-12 flex items-center justify-center shadow-sm hover:bg-gray-50 transition transform hover:scale-110">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" class="w-6 h-6">
        </button>
        <button class="bg-white border border-gray-300 rounded-full w-12 h-12 flex items-center justify-center shadow-sm hover:bg-gray-50 transition transform hover:scale-110">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook" class="w-6 h-6">
        </button>
      </div>
    </footer>

  </div>
<div id="register-role-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
        <div class="flex justify-end">
            <button id="close-register-modal" class="text-gray-400 hover:text-gray-700 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-2">Daftar Sebagai</h3>
        <p class="text-gray-500 mb-6">Pilih peran Anda untuk melanjutkan pendaftaran.</p>
        
        <div class="space-y-4">
            <a href="<?= site_url('auth/register?role=cust') ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition transform hover:scale-105">
                Saya Customer
            </a>
            <a href="<?= site_url('auth/register?role=outlet') ?>" class="block w-full bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-4 rounded-xl shadow-md border border-gray-200 transition transform hover:scale-105">
                Saya Pemilik Laundry
            </a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('register-role-modal');
        const openBtn = document.getElementById('open-register-modal');
        const closeBtn = document.getElementById('close-register-modal');

        // Fungsi untuk membuka modal
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Fungsi untuk menutup modal
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Fungsi untuk menutup modal jika klik di luar area kotak
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>