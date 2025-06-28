<?php

namespace App\Controllers;

class PageController extends BaseController
{
    /**
     * Menampilkan halaman landing utama.
     */
    public function landing()
    {
        // Fungsi ini juga butuh 'penjaga'.
        // Jika user yang sudah login mencoba akses landing page,
        // langsung arahkan ke dashboard.
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Jika user belum login, tampilkan view landing_page.
        return view('landing_page');
    }
}