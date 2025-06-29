<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.ico') ?>">
    <title>Registrasi - DyWash</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white">

  <div class="container mx-auto p-6 md:p-8 max-w-lg">
    
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-800">Registrasi</h1>
      <p class="text-gray-500 mt-1">Buat akun untuk memulai</p>
    </div>

    <form id="register-form" method="post" action="<?= site_url('auth/register/') ?>" autocomplete="off" novalidate>
      <input type="hidden" name="role" value="<?= esc($role, 'attr') ?>">
      
      <div class="mb-5">
        <label for="name-input" class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap</label>
        <div id="name-container" class="border rounded-xl focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <input type="text" id="name-input" name="name" placeholder="Masukkan nama lengkap Anda" required class="outline-none bg-white rounded-xl px-3 flex-1 py-2.5 text-gray-700 w-full caret-blue-600" value="<?= old('name') ?>">
        </div>
      </div>

      <div class="mb-5">
        <label for="email-input" class="block mb-2 text-sm font-bold text-gray-700">Alamat Email</label>
        <div id="email-container" class="border rounded-xl focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <input type="email" id="email-input" name="email" placeholder="contoh@email.com" required class="outline-none bg-white rounded-xl px-3 flex-1 py-2.5 text-gray-700 w-full caret-blue-600" value="<?= old('email') ?>">
        </div>
      </div>

      <div class="mb-5">
        <label for="password-input" class="block mb-2 text-sm font-bold text-gray-700">Kata Sandi</label>
        <div id="password-container" class="border rounded-xl focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <input type="password" id="password-input" name="password" placeholder="minimal 8 karakter" required class="outline-none bg-white rounded-xl px-3 flex-1 py-2.5 text-gray-700 w-full caret-blue-600">
        </div>
      </div>

      <div class="mb-6">
        <label for="confirm-password-input" class="block mb-2 text-sm font-bold text-gray-700">Konfirmasi Kata Sandi</label>
        <div id="confirm-password-container" class="border rounded-xl focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <input type="password" id="confirm-password-input" name="confirm_password" placeholder="Ulangi kata sandi Anda" required class="outline-none bg-white rounded-xl px-3 flex-1 py-2.5 text-gray-700 w-full caret-blue-600">
        </div>
      </div>

      <div class="flex items-start mb-6">
          <div class="flex items-center h-5">
              <input id="terms-checkbox" type="checkbox" name="terms" value="agree" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
          </div>
          <label for="terms-checkbox" class="ml-3 text-sm font-normal text-gray-600">
              Saya sudah membaca dan setuju dengan 
              <a href="/syarat-ketentuan" class="text-blue-600 hover:underline font-medium">Syarat & Ketentuan</a> serta
              <a href="/kebijakan-privasi" class="text-blue-600 hover:underline font-medium">Kebijakan Privasi</a>.
          </label>
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300">
        Daftar
      </button>

    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
      Sudah punya akun? 
      <a href="<?= site_url('auth/login') ?>" class="font-semibold text-blue-600 hover:underline">
        Masuk di sini
      </a>
    </p>

  </div>

<!-- Kumpulan Pop-up Alert -->
<?php if (session()->getFlashdata('error_email_exists')): ?>
<div id="server-error-popup" 
     class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Email yang digunakan sudah terdaftar.</p>
</div>
<?php endif; ?>

<div id="empty-fields-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Harap lengkapi semua kolom yang wajib diisi.</p>
</div>

<div id="email-format-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Form data yang diisi tidak valid.</p>
</div>

<div id="password-length-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Password minimal harus 8 karakter.</p>
</div>

<div id="password-mismatch-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Konfirmasi password tidak cocok.</p>
</div>

<div id="terms-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Registrasi Gagal!</p>
    <p class="text-sm">Harap setujui Syarat & Ketentuan serta Kebijakan Privasi.</p>
</div>

<!-- Kumpulan Script -->
<script>
    function showPopup(popupId) {
        const popupElement = document.getElementById(popupId);
        if (!popupElement) return;
        document.querySelectorAll('.fixed.bottom-5').forEach(p => {
            if(p.id !== popupId) p.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
        });
        popupElement.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
        setTimeout(() => {
            popupElement.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
        }, 7000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const serverErrorPopup = document.getElementById('server-error-popup');
        if(serverErrorPopup) {
            showPopup('server-error-popup');
            document.getElementById('email-container').classList.add('border-red-500', 'border-2');
        }

        const registerForm = document.getElementById('register-form');
        if (!registerForm) return;

        const nameInput = document.getElementById('name-input');
        const emailInput = document.getElementById('email-input');
        const passwordInput = document.getElementById('password-input');
        const confirmInput = document.getElementById('confirm-password-input');
        const termsCheckbox = document.getElementById('terms-checkbox');
        
        const nameContainer = document.getElementById('name-container');
        const emailContainer = document.getElementById('email-container');
        const passwordContainer = document.getElementById('password-container');
        const confirmContainer = document.getElementById('confirm-password-container');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const nameValue = nameInput.value.trim();
            const emailValue = emailInput.value.trim();
            const passwordValue = passwordInput.value.trim();
            const confirmValue = confirmInput.value.trim();

            [nameContainer, emailContainer, passwordContainer, confirmContainer].forEach(container => {
                container.classList.remove('border-red-500', 'border-2');
            });
            
            if (!termsCheckbox.checked) {
                showPopup('terms-popup');
                return;
            }
            if (nameValue === '' || emailValue === '' || passwordValue === '' || confirmValue === '') {
                if (nameValue === '') nameContainer.classList.add('border-red-500', 'border-2');
                if (emailValue === '') emailContainer.classList.add('border-red-500', 'border-2');
                if (passwordValue === '') passwordContainer.classList.add('border-red-500', 'border-2');
                if (confirmValue === '') confirmContainer.classList.add('border-red-500', 'border-2');
                showPopup('empty-fields-popup');
                return;
            }
            if (!emailRegex.test(emailValue)) {
                emailContainer.classList.add('border-red-500', 'border-2');
                showPopup('email-format-popup');
                return;
            }
            if (passwordValue.length < 8) {
                passwordContainer.classList.add('border-red-500', 'border-2');
                showPopup('password-length-popup');
                return;
            }
            if (passwordValue !== confirmValue) {
                passwordContainer.classList.add('border-red-500', 'border-2');
                confirmContainer.classList.add('border-red-500', 'border-2');
                showPopup('password-mismatch-popup');
                return;
            }

            registerForm.submit();
        });
    });
</script>
</body>
</html>
