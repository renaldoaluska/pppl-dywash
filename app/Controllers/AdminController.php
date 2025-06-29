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
        /**
     * FUNGSI YANG DIPERBARUI: Menampilkan semua outlet, dengan fitur pencarian.
     */
    public function listAllOutlets()
    {
        $outletModel = new OutletModel();
        
        // 1. Ambil kata kunci pencarian dari URL (contoh: /admin/outlets?search=...)
        $searchKeyword = $this->request->getGet('search');

        // 2. Siapkan query builder. Kita mulai dengan model dasarnya.
        $query = $outletModel;

        // 3. JIKA ADA kata kunci pencarian, tambahkan filter 'like' ke query.
        if ($searchKeyword) {
            $query = $outletModel->groupStart() // Mulai grup kondisi OR
                                 ->like('name', $searchKeyword)      // Cari di kolom 'name'
                                 ->orLike('address', $searchKeyword) // ATAU cari di kolom 'address'
                                 ->groupEnd();   // Selesai grup kondisi OR
        }

        // 4. Eksekusi query yang sudah difilter (atau query semua data jika tidak ada pencarian)
        $data['outlets'] = $query->findAll();

        // 5. Tampilkan view dengan data yang sudah siap
        return view('admin/list_outlets', $data);
    }


    /**
     * FUNGSI BARU: Menampilkan semua pesanan dari semua customer.
     */
        /**
     * FUNGSI BARU: Menampilkan semua pesanan dengan fitur pencarian.
     */
    public function listAllOrders()
    {
        $orderModel = new OrderModel();
        
        // Ambil kata kunci pencarian dari URL
        $searchKeyword = $this->request->getGet('search');

        // Siapkan query builder. Kita mulai dengan JOIN ke tabel lain
        // untuk mendapatkan nama customer dan nama outlet.
        $query = $orderModel
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name');

        // Jika ada kata kunci pencarian, tambahkan filter
        if ($searchKeyword) {
            $query->groupStart()
                  ->like('users.name', $searchKeyword)      // Cari berdasarkan nama customer
                  ->orLike('orders.order_id', $searchKeyword) // ATAU berdasarkan ID Pesanan
                  ->groupEnd();
        }

        // Urutkan dari yang terbaru dan ambil semua hasilnya
        $data['orders'] = $query->orderBy('orders.created_at', 'DESC')->findAll();
        
        // Tampilkan view
        return view('admin/list_orders', $data);
    }

}