<?= $this->extend('auth/layout') ?>

<?= $this->section('title') ?>
Login
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <div class="flex-1 flex flex-col justify-center">
    <!-- Logo/Ilustrasi -->
    <div class="flex flex-col items-center bg-blue-50 py-10">
      <div class="bg-white rounded-lg w-20 h-20 flex items-center justify-center shadow">
        <img src="<?= base_url('assets/img/icon.png') ?>" alt="Ilustrasi Login" class="w-16 h-16 object-contain">
      </div>
    </div>
  </div>

  <div class="bg-white rounded-t-3xl px-6 pt-8 pb-8 shadow-lg max-w-md w-full mx-auto -mt-8">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Selamat Datang!</h1>
    
    <form id="login-form" method="post" action="<?= site_url('auth/login/') ?>" autocomplete="off" novalidate>
      <div class="mb-4">
        <div id="email-container" class="flex items-center border rounded-lg px-3 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <svg class="w-5 h-5 text-gray-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
          <input id="email-input" type="email" name="email" placeholder="Alamat Email" required class="outline-none bg-transparent flex-1 py-3 text-gray-700">
        </div>
      </div>
      <div class="mb-2">
        <div id="password-container" class="flex items-center border rounded-lg px-3 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-400 transition-all duration-200">
          <svg class="w-5 h-5 text-gray-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
          <input id="password-input" type="password" name="password" placeholder="Kata Sandi" required class="outline-none bg-transparent flex-1 py-3 text-gray-700">
        </div>
      </div>
      <div class="flex justify-end mb-5"><a href="#" class="text-sm text-blue-600 hover:underline">Lupa kata sandi?</a></div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg mb-4 transition transform hover:scale-105 duration-300 ease-in-out">Masuk</button>
    </form>
    
    <div class="text-center text-sm text-gray-600">
      Belum punya akun? 
      <button type="button" id="open-register-modal" class="text-blue-600 font-semibold hover:underline focus:outline-none">
        Daftar sekarang
      </button>
    </div>
    <div class="flex items-center my-4"><div class="flex-grow border-t border-gray-200"></div><span class="mx-3 text-gray-400 text-xs font-medium">Atau lanjut dengan</span><div class="flex-grow border-t border-gray-200"></div></div>
    <div class="flex justify-center gap-4">
      <button class="bg-white border border-gray-300 rounded-full w-12 h-12 flex items-center justify-center shadow-sm hover:bg-gray-50 transition transform hover:scale-110"><img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" class="w-6 h-6"></button>
      <button class="bg-white border border-gray-300 rounded-full w-12 h-12 flex items-center justify-center shadow-sm hover:bg-gray-50 transition transform hover:scale-110"><img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook" class="w-6 h-6"></button>
    </div>
  </div>
  
  <script>
    function setViewportHeight() { let vh = window.innerHeight * 0.01; document.documentElement.style.setProperty('--vh', `${vh}px`); }
    setViewportHeight();
    window.addEventListener('orientationchange', setViewportHeight);
  </script>

  <?php if (!empty($error_login)): ?>
  <div id="login-alert-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Login Gagal!</p>
    <p class="text-sm">Email atau password yang Anda masukkan salah.</p>
  </div>
  <?php endif; ?>

  <div id="validation-alert-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Login Gagal!</p>
    <p class="text-sm">Harap lengkapi semua kolom.</p>
  </div>
  
  <div id="email-format-alert-popup" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-11/12 max-w-md bg-red-600 text-white py-3 px-4 rounded-lg shadow-xl text-center opacity-0 transform translate-y-10 transition-all duration-500 ease-out pointer-events-none">
    <p class="font-bold">Input Tidak Valid!</p>
    <p class="text-sm">Format email yang Anda masukkan tidak valid.</p>
  </div>

  <div id="register-role-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
        <div class="flex justify-end"><button id="close-register-modal" class="text-gray-400 hover:text-gray-700 focus:outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Daftar Sebagai</h3>
        <p class="text-gray-500 mb-6">Pilih peran Anda untuk melanjutkan pendaftaran.</p>
        <div class="space-y-4">
            <a href="<?= site_url('auth/register?role=cust') ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition transform hover:scale-105">Saya Customer</a>
            <a href="<?= site_url('auth/register?role=outlet') ?>" class="block w-full bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-4 rounded-xl shadow-md border border-gray-200 transition transform hover:scale-105">Saya Pemilik Laundry</a>
        </div>
    </div>
  </div>
  <script>
    // == FUNGSI POP-UP YANG BISA DIPAKAI ULANG ==
    function showPopup(popupId) {
        const popupElement = document.getElementById(popupId);
        if (!popupElement) return;
        popupElement.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
        setTimeout(() => {
            popupElement.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
        }, 7000);
    }

    // == KUMPULAN SEMUA LOGIKA HALAMAN LOGIN ==
    document.addEventListener('DOMContentLoaded', () => {
        
        // --- 1. Logika untuk Modal Registrasi ---
        const modal = document.getElementById('register-role-modal');
        const openBtn = document.getElementById('open-register-modal');
        const closeBtn = document.getElementById('close-register-modal');

        if(modal && openBtn && closeBtn) {
            openBtn.addEventListener('click', () => { modal.classList.remove('hidden'); });
            closeBtn.addEventListener('click', () => { modal.classList.add('hidden'); });
            modal.addEventListener('click', (event) => {
                if (event.target === modal) { modal.classList.add('hidden'); }
            });
        }
        
        // --- 2. Logika untuk Error dari Server ---
        const serverErrorPopup = document.getElementById('login-alert-popup');
        if(serverErrorPopup) {
            showPopup('login-alert-popup');
        }

        // --- 3. Logika untuk Validasi Form Login ---
        const loginForm = document.getElementById('login-form');
        if(!loginForm) return;

        const emailInput = document.getElementById('email-input');
        const passwordInput = document.getElementById('password-input');
        const emailContainer = document.getElementById('email-container');
        const passwordContainer = document.getElementById('password-container');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        emailInput.addEventListener('input', function() {
            const emailValue = emailInput.value.trim();
            if (emailValue === '' || emailRegex.test(emailValue)) {
                emailContainer.classList.remove('border-red-500', 'border-2');
            } else {
                emailContainer.classList.add('border-red-500', 'border-2');
            }
        });

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const emailValue = emailInput.value.trim();
            const passwordValue = passwordInput.value.trim();
            
            emailContainer.classList.remove('border-red-500', 'border-2');
            passwordContainer.classList.remove('border-red-500', 'border-2');

            if (emailValue === '' || passwordValue === '') {
                if (emailValue === '') { emailContainer.classList.add('border-red-500', 'border-2'); }
                if (passwordValue === '') { passwordContainer.classList.add('border-red-500', 'border-2'); }
                showPopup('validation-alert-popup');
                return;
            }

            if (!emailRegex.test(emailValue)) {
                emailContainer.classList.add('border-red-500', 'border-2');
                showPopup('email-format-alert-popup');
                return;
            }

            loginForm.submit();
        });
    });
  </script>

<?= $this->endSection() ?>
