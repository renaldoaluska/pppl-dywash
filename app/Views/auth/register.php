<?= $this->include('layout/header') ?>
    <div class="flex-1 flex flex-col justify-center py-12">
        <!-- Header Ilustrasi -->
        <div class="flex flex-col items-center bg-gradient-to-br from-sky-500 to-indigo-600 py-12">
            <div class="bg-white/30 backdrop-blur-sm rounded-xl w-24 h-24 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48">
                    <rect width="36" height="28" x="6" y="10" rx="4" fill="none"/>
                    <path d="M6 34l8-10 7 9 7-11 8 12" stroke-width="2" fill="none"/>
                </svg>
            </div>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-t-3xl px-6 sm:px-8 pt-8 pb-8 shadow-2xl max-w-md w-full mx-auto -mt-10 border border-slate-200">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Buat Akun Baru</h1>
            <p class="text-slate-500 mb-6">Isi data di bawah untuk memulai.</p>
            
            <!-- Di sini tempat untuk menampilkan error jika ada -->
            <!-- <?php if (!empty($error)): ?> ... <?php endif; ?> -->

            <form method="post" action="<?= site_url('auth/register/') ?>" autocomplete="off">
                <?= csrf_field() ?>
                <div class="space-y-4">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="text-sm font-medium text-slate-600 mb-1 block">Nama</label>
                        <div class="flex items-center border rounded-lg px-3 bg-slate-50 focus-within:ring-2 focus-within:ring-indigo-400 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <input type="text" id="name" name="name" required class="outline-none bg-transparent flex-1 py-3 text-slate-700">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                         <label for="email" class="text-sm font-medium text-slate-600 mb-1 block">Email</label>
                        <div class="flex items-center border rounded-lg px-3 bg-slate-50 focus-within:ring-2 focus-within:ring-indigo-400 transition-all duration-300">
                           <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                              <path d="M16 4H8a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2z" /><path d="M22 6l-10 7L2 6" />
                            </svg>
                            <input type="email" id="email" name="email" required class="outline-none bg-transparent flex-1 py-3 text-slate-700">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="text-sm font-medium text-slate-600 mb-1 block">Password</label>
                        <div class="flex items-center border rounded-lg px-3 bg-slate-50 focus-within:ring-2 focus-within:ring-indigo-400 transition-all duration-300">
                            <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                              <path d="M12 17a2 2 0 100-4 2 2 0 000 4z" /><path d="M17 11V7a5 5 0 00-10 0v4" /><rect width="20" height="12" x="2" y="11" rx="2" />
                            </svg>
                            <input type="password" id="password" name="password" required class="outline-none bg-transparent flex-1 py-3 text-slate-700">
                        </div>
                    </div>
                    
                    <!-- Pilihan Role -->
                    <div>
                        <label for="role" class="text-sm font-medium text-slate-600 mb-1 block">Daftar sebagai</label>
                        <div class="relative">
                            <select id="role" name="role" required class="appearance-none w-full border rounded-lg px-3 py-3 bg-slate-50 focus:ring-2 focus:ring-indigo-400 transition-all duration-300 outline-none text-slate-700">
                                <option value="">-- Pilih Role --</option>
                                <option value="cust">Customer</option>
                                <option value="outlet">Outlet</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition-all duration-300 shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:scale-105">Register</button>
            </form>
            
             <div class="text-center text-sm text-slate-500 mt-6">
                Sudah punya akun? <a href="<?= site_url('/auth/login') ?>" class="text-indigo-600 font-medium hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>
<?= $this->include('layout/footer') ?>