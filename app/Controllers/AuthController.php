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

        $error = null;

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            // Menggunakan 'where' dan 'first' adalah praktik yang lebih baik dari method custom
            $user = $userModel->where('email', $email)->first(); 

            // Pengecekan password sesuai kode asli (plain text)
            if ($user && $password === $user['password']) {
                
                // Set session
                $this->setUserSession($user);
                
                // Arahkan ke method dashboard tunggal
                return redirect()->to('/dashboard');
            } else {
                $error = 'Email atau password salah!';
            }
        }

        return view('auth/login', ['error' => $error]);
    }

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
     * Menggantikan seluruh fungsi dari DashboardController.
     */
    public function dashboard()
    {
        // Pengecekan login sederhana di awal method
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        $role = session()->get('role');

        // Memilih view yang akan ditampilkan berdasarkan role dari session
        switch ($role) {
            case 'admin':
                return view('dashboard/admin');
            case 'outlet':
                return view('dashboard/outlet');
            case 'cust':
                return view('dashboard/customer');
            default:
                // Jika role tidak ada atau tidak valid, paksa logout
                return redirect()->to('/logout');
        }
    }
    /**
     * Menampilkan dan memproses halaman register.
     */
    public function register()
    {
        // Jika sudah login, arahkan ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $error = null;

        if ($this->request->getMethod() === 'POST') {
            $name     = $this->request->getPost('name');
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $role     = $this->request->getPost('role', FILTER_SANITIZE_STRING);

            $userModel = new UserModel();

            // Cek apakah email sudah terdaftar
            if ($userModel->where('email', $email)->first()) {
                $error = 'Email sudah terdaftar!';
            } else {
                // Simpan user baru (password masih plain text, sebaiknya gunakan hash di produksi)
                $userModel->save([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role ?? 'cust'
                ]);
                // Redirect ke login setelah register berhasil
                return redirect()->to('/auth/login');
            }
        }

        return view('auth/register', ['error' => $error]);
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