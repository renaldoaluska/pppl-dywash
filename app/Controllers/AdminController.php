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
 /**
     * FUNGSI DASHBOARD (VERSI LENGKAP)
     * Menampilkan semua statistik dari status order, payment, dan outlet.
     */
public function dashboard()
    {
        $outletModel  = new \App\Models\OutletModel();
        $paymentModel = new \App\Models\PaymentModel();
        $orderModel   = new \App\Models\OrderModel();

        // Inisialisasi semua data ke 0
        $data = [
            'total_outlets_count'   => 0, 'pending_outlets_count' => 0,
            'total_orders_count'      => 0, 'aktif_orders_count'    => 0, 
            'diterima_orders_count' => 0, 'diambil_orders_count'  => 0,
            'dicuci_orders_count'   => 0, 'dikirim_orders_count'  => 0,
            'selesai_orders_count'  => 0, 'diulas_orders_count'   => 0,
            'ditolak_orders_count'  => 0, 'pending_payments_count' => 0,
            'lunas_payments_count'   => 0, 'gagal_payments_count'   => 0,
            'cod_payments_count'     => 0,
        ];

        // 1. STATISTIK OUTLET
        $data['total_outlets_count']   = $outletModel->where('status', 'verified')->countAllResults();
        $data['pending_outlets_count'] = $outletModel->where('status', 'pending')->countAllResults();

        // 2. STATISTIK PESANAN (1x Query)
        $orderCounts = $orderModel->select('status, COUNT(order_id) as count')
                                  ->groupBy('status')
                                  ->findAll();
        
        foreach ($orderCounts as $row) {
            if (isset($data[$row['status'] . '_orders_count'])) {
                $data[$row['status'] . '_orders_count'] = $row['count'];
            }
        }

        // 3. STATISTIK PEMBAYARAN (1x Query)
        $paymentCounts = $paymentModel->select('status, COUNT(payment_id) as count')
                                      ->groupBy('status')
                                      ->findAll();

        foreach ($paymentCounts as $row) {
            if (isset($data[$row['status'] . '_payments_count'])) {
                $data[$row['status'] . '_payments_count'] = $row['count'];
            }
        }
        
        // 4. KALKULASI TOTAL & AKTIF
        // total_orders_count sekarang adalah grand total semua pesanan
        $data['total_orders_count'] = $orderModel->countAllResults();
        
        // aktif_orders_count adalah jumlah pesanan yang sedang berjalan
        $data['aktif_orders_count'] = $data['diterima_orders_count'] +
                                      $data['diambil_orders_count'] +
                                      $data['dicuci_orders_count'] +
                                      $data['dikirim_orders_count'];

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
            ->join('outlets', 'orders.outlet_id = outlets.outlet_id') // ✅ JOIN OUTLETS
            ->select('payments.*, orders.total_amount, users.name as customer_name, outlets.name as outlet_name') // ✅ SELECT OUTLET NAME
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
        
        $searchKeyword = $this->request->getGet('search');

        $query = $outletModel;

        // PERUBAHAN: Menambahkan filter untuk hanya mengambil outlet dengan status 'verified'
        $query->where('status', 'verified');

        if ($searchKeyword) {
            $query->groupStart()
                  ->like('name', $searchKeyword)
                  ->orLike('address', $searchKeyword)
                  ->groupEnd();
        }

        $data['outlets'] = $query->orderBy('outlet_id', 'DESC')->findAll();

        
    $status = $this->request->getGet('status');

    // Redirect jika status = pending
    if ($status == 'pending') {
        return redirect()->to('/admin/outlets/verify');
    }
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
        
    $status = $this->request->getGet('status');
            // Redirect jika status = pending
    if ($status == 'pending') {
        return redirect()->to('/admin/payments/verify');
    }

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
    public function showPaymentDetail($payment_id)
    {
        $paymentModel = new PaymentModel();
        
        // 1. Ambil data pembayaran utama, SEKARANG DENGAN JOIN KE OUTLETS
        $payment = $paymentModel
            ->where('payments.payment_id', $payment_id)
            ->join('orders', 'orders.order_id = payments.order_id')
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id') // <-- JOIN ke tabel outlets
            ->select('payments.*, orders.customer_notes, users.name as customer_name, outlets.name as outlet_name') // <-- Ambil nama outlet
            ->first();
            
        if (!$payment) {
            return redirect()->to('/admin/payments/verify')->with('error', 'Pembayaran tidak ditemukan.');
        }

        // 2. Ambil rincian item dari pesanan terkait
        $orderItemModel = new \App\Models\OrderItemModel();
        $order_items = $orderItemModel
            ->where('order_items.order_id', $payment['order_id'])
            ->join('services', 'services.service_id = order_items.service_id')
            ->select('order_items.*, services.name as service_name, services.unit')
            ->findAll();

        $data = [
            'payment'     => $payment,
            'order_items' => $order_items
        ];

        return view('admin/detail_payment', $data);
    }
    public function failOrRefundPayment($payment_id)
    {
        $paymentModel = new PaymentModel();
        $orderModel = new OrderModel();

        // 1. Dapatkan data pembayaran untuk mendapatkan order_id
        $payment = $paymentModel->find($payment_id);
        if (!$payment) {
            return redirect()->to('/admin/payments/verify')->with('error', 'Pembayaran tidak ditemukan.');
        }

        // 2. Ubah status pembayaran menjadi 'gagal'
        $paymentModel->update($payment_id, ['status' => 'gagal']);

        // 3. Ubah status pesanan menjadi 'ditolak'
        $orderModel->update($payment['order_id'], ['status' => 'ditolak']);
        
        return redirect()->to('/admin/payments/verify')->with('success', 'Pembayaran berhasil dibatalkan/digagalkan.');
    }
    public function viewOutletDetail($outlet_id)
    {
        $outletModel = new OutletModel();
        
        $outlet = $outletModel
            ->join('users', 'users.user_id = outlets.owner_id')
            ->where('outlets.outlet_id', $outlet_id)
            ->select('outlets.*, users.name as owner_name, users.email as owner_email')
            ->first();

        if (!$outlet) {
            return redirect()->to('/admin/outlets')->with('error', 'Outlet tidak ditemukan.');
        }

        $data['outlet'] = $outlet;
        return view('admin/view_outlet_detail', $data); // Memanggil view read-only
    }
    public function viewOrderDetail($order_id)
    {
        $orderModel = new OrderModel();
        
        // 1. Ambil data pesanan utama
        $order = $orderModel
            ->where('orders.order_id', $order_id)
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name')
            ->first();

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        // 2. Ambil data pembayaran
        $paymentModel = new \App\Models\PaymentModel();
        $payment = $paymentModel->where('order_id', $order_id)->first();

        if (!$payment) {
    $payment = [
        'payment_method' => '-',
        'status' => '-',
    ];
}

        // 3. Ambil rincian item pesanan
        $orderItemModel = new \App\Models\OrderItemModel();
        $order_items = $orderItemModel
            ->where('order_items.order_id', $order_id)
            ->join('services', 'services.service_id = order_items.service_id')
            ->select('order_items.*, services.name as service_name, services.unit')
            ->findAll();

        // 4. Kirim semua data ke view
        $data = [
            'order'       => $order,
            'payment'     => $payment,
            'order_items' => $order_items
        ];

        return view('admin/view_order_detail', $data);
    }

}
