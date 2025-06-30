<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

class AdminController extends BaseController
{
    /**
     * FUNGSI BARU: Menampilkan dashboard utama admin dengan data ringkasan.
     * Method ini ditambahkan untuk menghitung data statistik di halaman dashboard.
     */
    public function dashboard()
    {
        // Inisialisasi model yang akan kita gunakan
        $outletModel = new OutletModel();
        $paymentModel = new PaymentModel();
        $orderModel = new OrderModel();

        // Siapkan array data untuk dikirim ke view
        $data = [
            'pending_outlets_count' => $outletModel->where('status', 'pending')->countAllResults(),
            'pending_payments_count' => $paymentModel->where('status', 'pending')->countAllResults(),
            'total_outlets_count' => $outletModel->countAllResults(), // Menghitung semua outlet
            'total_orders_count' => $orderModel->countAllResults()  // Menghitung semua pesanan
        ];

        // Tampilkan view dashboard dan kirim data yang sudah dihitung
        return view('dashboard/admin', $data);
    }

    /**
     * Fitur: Memverifikasi Outlet (Langkah 1)
     * Menampilkan daftar outlet yang statusnya masih 'pending'.
     */
    public function listPendingOutlets()
    {
        $outletModel = new OutletModel();
        
        // Ambil data outlet yang perlu diverifikasi
        $data['outlets'] = $outletModel
        ->where('status', 'pending')
        ->orderBy('updated_at', 'DESC')
        ->findAll();
        
        return view('admin/verify_outlet', $data);
    }

    /**
     * Fitur: Memverifikasi Outlet (Langkah 2)
     * Memproses aksi verifikasi (setujui atau tolak).
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
     * FUNGSI: Menampilkan daftar pembayaran yang perlu diverifikasi
     */
    public function listPendingPayments()
    {
        $paymentModel = new PaymentModel();
        
        $data['payments'] = $paymentModel
            ->where('payments.status', 'pending')
            ->join('orders', 'orders.order_id = payments.order_id')
            ->join('users', 'users.user_id = orders.customer_id')
            ->select('payments.*, orders.total_amount, users.name as customer_name')
            ->orderBy('payments.order_id', 'DESC')
            ->findAll();
        
        return view('admin/verify_payment', $data);
    }

    /**
     * FUNGSI: Memproses verifikasi pembayaran
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
     * FUNGSI: Menampilkan semua outlet, dengan fitur pencarian.
     */
    public function listAllOutlets()
    {
        $outletModel = new OutletModel();
        
        // Ambil kata kunci pencarian dari URL
        $searchKeyword = $this->request->getGet('search');

        $query = $outletModel;

        // Jika ada kata kunci pencarian, tambahkan filter 'like'
        if ($searchKeyword) {
            $query = $outletModel->groupStart()
                                 ->like('name', $searchKeyword)
                                 ->orLike('address', $searchKeyword)
                                 ->groupEnd();
        }

        // Eksekusi query
        $data['outlets'] = $query->findAll();

        // Tampilkan view
        return view('admin/list_outlets', $data);
    }


    /**
     * FUNGSI: Menampilkan semua pesanan dengan fitur pencarian.
     */
    public function listAllOrders()
    {
        $orderModel = new OrderModel();
        
        // Ambil kata kunci pencarian dari URL
        $searchKeyword = $this->request->getGet('search');

        // Siapkan query builder
        $query = $orderModel
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name');

        // Jika ada kata kunci pencarian, tambahkan filter
        if ($searchKeyword) {
            $query->groupStart()
                  ->like('users.name', $searchKeyword)
                  ->orLike('orders.order_id', $searchKeyword)
                  ->groupEnd();
        }

        // Urutkan dari yang terbaru dan ambil semua hasilnya
        $data['orders'] = $query->orderBy('orders.created_at', 'DESC')->findAll();
        
        // Tampilkan view
        return view('admin/list_orders', $data);
    }
    public function showOutletDetail($outlet_id)
    {
        $outletModel = new OutletModel();
        
        $outlet = $outletModel->find($outlet_id);

        // Validasi jika outlet tidak ditemukan
        if (!$outlet) {
            // Arahkan kembali ke halaman verifikasi dengan pesan error
            return redirect()->to('/admin/verify')->with('error', 'Outlet tidak ditemukan.');
        }

        $data['outlet'] = $outlet;
        return view('admin/detail_outlet', $data);
    }

}
