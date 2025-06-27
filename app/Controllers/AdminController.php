<?php // file: app/Controllers/AdminController.php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

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
    /**
     * FUNGSI BARU: Menampilkan daftar pembayaran yang perlu diverifikasi
     */
    public function listPendingPayments()
    {
        $paymentModel = new PaymentModel();
        
        $data['payments'] = $paymentModel
            ->where('payments.status', 'pending')
            ->join('orders', 'orders.order_id = payments.order_id')
            ->join('users', 'users.user_id = orders.customer_id')
            ->select('payments.*, orders.total_amount, users.name as customer_name')
            ->findAll();
        
        return view('admin/verify_payment', $data);
    }

    /**
     * FUNGSI BARU: Memproses verifikasi pembayaran
     * @param int $payment_id
     */
    public function verifyPayment($payment_id)
    {
        $paymentModel = new PaymentModel();
        $orderModel = new OrderModel();

        // 1. Ubah status pembayaran menjadi 'lunas'
        $paymentModel->update($payment_id, ['status' => 'lunas']);

        // 2. Dapatkan order_id dari pembayaran yang baru diverifikasi
        $payment = $paymentModel->find($payment_id);
        
        // 3. Ubah status pesanan menjadi 'diterima' agar bisa diproses outlet
        if ($payment) {
            $orderModel->update($payment['order_id'], ['status' => 'diterima']);
        }
        
        return redirect()->to('/admin/payments/verify')->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}