<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{

    /**
     * Menampilkan dan memproses halaman login.
     */
    public function login()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Siapkan array data untuk dikirim ke view
        $data = [];

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            // Pengecekan password (sebaiknya gunakan password_verify() di production)
            if ($user && $password === $user['password']) {
                
                // Set session
                $this->setUserSession($user);
                
                // Arahkan ke dashboard
                return redirect()->to('/dashboard');

            } else {
                // JIKA LOGIN GAGAL:
                // Kita set key 'error_login' di dalam array $data.
                // Inilah yang akan memicu kotak merah di view.
                $data['error_login'] = true;
            }
        }

        // Kirim array $data ke view.
        // Jika tidak ada error, $data akan menjadi array kosong.
        // Jika login gagal, $data akan berisi ['error_login' => true].
        return view('auth/login', $data);
    }

    // ... sisa method lainnya tidak perlu diubah ...

    /**
     * Helper untuk mengatur data session.
     */
    private function setUserSession($user)
    {
        $sessionData = [
            'isLoggedIn' => true,
            'user_id'    => $user['user_id'],
            'name'       => $user['name'],
            'role'       => $user['role'],
        ];
        session()->set($sessionData);
    }

    /**
     * Menampilkan dashboard yang sesuai dengan role user.
     */
    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        $role = session()->get('role');

        switch ($role) {
            case 'admin':
                return view('dashboard/admin');
            case 'outlet':
                return view('dashboard/outlet');
            case 'cust':
                return view('dashboard/customer');
            default:
                return redirect()->to('/logout');
        }
    }

    /**
     * Menampilkan dan memproses halaman register.
     */
    /**
     * Menampilkan dan memproses halaman register.
     */
    public function register()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // --- Blok untuk menangani method GET (saat halaman pertama kali dibuka) ---
        $data = [];
        // 1. Ambil 'role' dari query parameter di URL (contoh: /register?role=pemilik_laundry)
        $role_from_url = $this->request->getGet('role');

        // 2. Validasi role. Jika tidak valid atau kosong, default-nya adalah 'customer'
        //    Ini untuk keamanan, agar tidak ada role aneh yang masuk.
        if ($role_from_url === 'outlet') {
            $data['role'] = 'outlet';
        } else {
            $data['role'] = 'cust';
        }
        // Variabel $data['role'] ini akan dikirim ke view untuk mengisi hidden input.

        // --- Blok untuk menangani method POST (saat form disubmit) ---
        if ($this->request->getMethod() === 'POST') {
            
            // Ambil semua data dari form yang disubmit
            $name     = $this->request->getPost('name');
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            // 3. Ambil 'role' dari hidden input di dalam form
            $role_from_form = $this->request->getPost('role');

            $userModel = new UserModel();

            // Cek apakah email sudah terdaftar
            if ($userModel->where('email', $email)->first()) {
                // Jika email ada, redirect kembali dengan pesan error spesifik via Flashdata.
                return redirect()->back()
                                 ->withInput()
                                 ->with('error_email_exists', true);
            } else {
                // Jika email belum ada, validasi lagi role dari form untuk keamanan
                if ($role_from_form === 'outlet') {
                    $role_to_save = 'outlet';
                } else {
                    $role_to_save = 'cust';
                }

                // Simpan user baru dengan data yang lengkap
                $userModel->save([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role_to_save // 4. Gunakan 'role' dari form saat menyimpan
                ]);
                
                // Redirect ke halaman login dengan pesan sukses
                return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
            }
        }

        // Jika method adalah GET, tampilkan view dengan membawa data 'role'
        return view('auth/register', $data);
    }

    /**
     * Menghapus sesi dan mengarahkan ke halaman login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}