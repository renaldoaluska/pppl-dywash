<?php // app/Controllers/Dashboard.php
namespace App\Controllers;

class DashboardController extends BaseController
{
    public function admin()
    {
        $this->cekRole('admin');
        return view('dashboard/admin');
    }

    public function outlet()
    {
        $this->cekRole('outlet');
        return view('dashboard/outlet');
    }

    public function customer()
    {
        $this->cekRole('cust');
        return view('dashboard/customer');
    }

    private function cekRole($role)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== $role) {
            return redirect()->to('/login')->send();
        }
    }
}
