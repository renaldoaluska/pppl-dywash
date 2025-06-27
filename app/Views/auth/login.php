<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Dywash</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <div class="flex-1 flex flex-col justify-center">
    <!-- Logo/Ilustrasi -->
    <div class="flex flex-col items-center bg-blue-50 py-10">
      <div class="bg-white rounded-lg w-20 h-20 flex items-center justify-center shadow">
        <!-- Placeholder logo -->
        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
          <rect width="36" height="28" x="6" y="10" rx="4" fill="#f3f4f6"/>
          <path d="M6 34l8-10 7 9 7-11 8 12" stroke="#d1d5db" stroke-width="2" fill="none"/>
        </svg>
      </div>
    </div>
    <!-- Login Form -->
    <div class="bg-white rounded-t-3xl px-6 pt-8 pb-6 shadow-lg max-w-md w-full mx-auto -mt-8">
      <h1 class="text-2xl font-bold mb-4">Selamat Datang!</h1>
      <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 px-3 py-2 rounded mb-3 text-sm">
          <?= esc($error) ?>
        </div>
      <?php endif; ?>
      <form method="post" action="<?= site_url('auth/login/') ?>" autocomplete="off">
        <!-- Email -->
        <div class="mb-3">
          <div class="flex items-center border rounded-lg px-3 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-300">
            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M16 4H8a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2z" />
              <path d="M22 6l-10 7L2 6" />
            </svg>
            <input type="email" name="email" placeholder="Alamat Email" required class="outline-none bg-transparent flex-1 py-2 text-gray-700">
          </div>
        </div>
        <!-- Password -->
        <div class="mb-1">
          <div class="flex items-center border rounded-lg px-3 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-300">
            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 17a2 2 0 100-4 2 2 0 000 4z" />
              <path d="M17 11V7a5 5 0 00-10 0v4" />
              <rect width="20" height="12" x="2" y="11" rx="2" />
            </svg>
            <input type="password" name="password" placeholder="Kata sandi" required class="outline-none bg-transparent flex-1 py-2 text-gray-700">
          </div>
        </div>
        <div class="flex justify-end mb-4">
          <a href="#" class="text-xs text-blue-500 hover:underline">Lupa kata sandi?</a>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg mb-3 transition">Masuk</button>
      </form>
      <div class="text-center text-sm mb-3">
        Belum anggota? <a href="/auth/register" class="text-blue-600 font-medium hover:underline">Daftar sekarang</a>
      </div>
      <div class="flex items-center my-4">
        <div class="flex-grow border-t border-gray-200"></div>
        <span class="mx-2 text-gray-400 text-xs">Atau lanjut dengan</span>
        <div class="flex-grow border-t border-gray-200"></div>
      </div>
      <div class="flex justify-center gap-3">
        <button class="bg-white border border-gray-200 rounded-full w-10 h-10 flex items-center justify-center shadow hover:bg-gray-50 transition">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" class="w-5 h-5">
        </button>
        <button class="bg-white border border-gray-200 rounded-full w-10 h-10 flex items-center justify-center shadow hover:bg-gray-50 transition">
          <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 17a5 5 0 0 0 5-5h-2a3 3 0 0 1-6 0H7a5 5 0 0 0 5 5z" fill="#fff"/>
          </svg>
        </button>
        <button class="bg-white border border-gray-200 rounded-full w-10 h-10 flex items-center justify-center shadow hover:bg-gray-50 transition">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook" class="w-5 h-5">
        </button>
      </div>
    </div>
  </div>
</body>
</html>
