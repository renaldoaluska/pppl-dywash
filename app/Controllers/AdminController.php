<?php // file: app/Controllers/AdminController.php

namespace App\Controllers;

use App\Models\OutletModel;

class AdminController extends BaseController
{
    /**
     * Fitur: Memverifikasi Outlet (Langkah 1)
     * Menampilkan daftar outlet yang statusnya masih 'pending'.
     */
    public function listPendingOutlets()
    {
        $outletModel = new OutletModel();
        
        // Ambil data outlet yang perlu diverifikasi
        $data['outlets'] = $outletModel->where('status', 'pending')->findAll();
        
        return view('admin/verify_outlet', $data);
    }

    /**
     * Fitur: Memverifikasi Outlet (Langkah 2)
     * Memproses aksi verifikasi (setujui atau tolak).
     * @param int $outlet_id
     * @param string $status ('verified' atau 'rejected')
     */
    public function verifyOutlet($outlet_id, $status)
    {
        $outletModel = new OutletModel();
        
        // Pastikan status yang diinput valid
        if (!in_array($status, ['verified', 'rejected'])) {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        if ($outletModel->update($outlet_id, ['status' => $status])) {
            return redirect()->to('/admin/verify')->with('success', 'Status outlet berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status outlet.');
    }
}