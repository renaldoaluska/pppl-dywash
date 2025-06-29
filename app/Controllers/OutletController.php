<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
use App\Models\ServiceModel;

class OutletController extends BaseController
{
    /**
     * FUNGSI DASHBOARD YANG DIPERBARUI: Mengambil data ringkasan untuk view.
     */
    public function dashboard()
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        $owner_id = session()->get('user_id');

        // Langkah Kunci: Ambil semua ID outlet yang dimiliki oleh user ini.
        // Jika user ini tidak memiliki satu pun outlet di tabel 'outlets',
        // maka array ini akan kosong, dan semua hitungan pesanan akan menjadi 0.
        $ownedOutletIds = $outletModel->where('owner_id', $owner_id)->findColumn('outlet_id') ?? [];

        // Siapkan data default
        $data = [
            'new_orders_count'        => 0,
            'processing_orders_count' => 0,
            'recent_orders'           => []
        ];

        // Hanya jalankan query jika owner terbukti memiliki outlet
        if (!empty($ownedOutletIds)) {
            // Hitung jumlah pesanan dengan status 'diterima'
            $data['new_orders_count'] = $orderModel
                ->whereIn('outlet_id', $ownedOutletIds)
                ->where('status', 'diterima')
                ->countAllResults();

            // Hitung jumlah pesanan dengan status 'diproses'
            $data['processing_orders_count'] = $orderModel
                ->whereIn('outlet_id', $ownedOutletIds)
                ->where('status', 'diproses')
                ->countAllResults();

            // PERUBAHAN DI SINI:
            // Ambil 3 aktivitas pesanan terbaru berdasarkan kapan statusnya terakhir diubah.
            $data['recent_orders'] = $orderModel
                ->whereIn('orders.outlet_id', $ownedOutletIds)
                ->join('users', 'users.user_id = orders.customer_id')
                ->select('orders.*, users.name as customer_name')
                ->orderBy('orders.updated_at', 'DESC') // Diurutkan berdasarkan 'updated_at'
                ->limit(3)
                ->findAll();
        }

        return view('dashboard/outlet', $data);
    }
    
    // ... SISA METHOD LAINNYA TIDAK PERLU DIUBAH ...
    // (listMyOutlets, listOrders, dll. tetap sama)

    // ====================================================================
    // === FITUR BARU: MANAJEMEN BANYAK OUTLET ============================
    // ====================================================================

    public function listMyOutlets()
    {
        $outletModel = new OutletModel();
        $data['outlets'] = $outletModel->where('owner_id', session()->get('user_id'))->findAll();
        
        return view('outlet/my_outlets/index', $data);
    }

    public function createOutletForm()
    {
        return view('outlet/my_outlets/form', ['outlet' => null]);
    }
    
    public function editOutletForm($outlet_id)
    {
        $outletModel = new OutletModel();
        $outlet = $outletModel->find($outlet_id);

        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses ke outlet ini.');
        }
        
        return view('outlet/my_outlets/form', ['outlet' => $outlet]);
    }

    public function storeOrUpdateOutlet()
    {
        $outletModel = new OutletModel();
        $rules = [
            'name'          => 'required',
            'address'       => 'required',
            'contact_phone' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $outlet_id = $this->request->getPost('outlet_id');
        $data = [
            'owner_id'        => session()->get('user_id'),
            'name'            => $this->request->getPost('name'),
            'address'         => $this->request->getPost('address'),
            'contact_phone'   => $this->request->getPost('contact_phone'),
            'operating_hours' => $this->request->getPost('operating_hours'),
            'status'          => 'pending'
        ];

        if ($outlet_id) {
            $existing = $outletModel->find($outlet_id);
            if ($existing && $existing['owner_id'] == session()->get('user_id')) {
                $outletModel->update($outlet_id, $data);
            }
        } else {
            $outletModel->insert($data);
        }

        return redirect()->to('/outlet/my-outlets')->with('success', 'Data outlet berhasil dikirim dan menunggu verifikasi.');
    }


    // --- FUNGSI KELOLA PESANAN ---
    public function listOrders()
     {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        
        $outlets = $outletModel->where(['owner_id' => session()->get('user_id'), 'status' => 'verified'])->findAll();

        if (empty($outlets)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki outlet terverifikasi untuk melihat pesanan.');
        }

        $outletIds = array_column($outlets, 'outlet_id');
        
        $data['pending_orders'] = $orderModel
            ->whereIn('orders.outlet_id', $outletIds)
            ->whereNotIn('orders.status', ['selesai', 'diulas', 'ditolak'])
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name')
            ->orderBy('orders.created_at', 'ASC')
            ->findAll();
        
        $data['history_orders'] = $orderModel
            ->whereIn('orders.outlet_id', $outletIds)
            ->whereIn('orders.status', ['selesai', 'diulas', 'ditolak'])
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();
        
        return view('outlet/list_orders', $data);
    }
    
    public function updateOrderStatus($order_id)
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['diproses', 'selesai', 'ditolak'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $ownedOutletIds = $outletModel->where('owner_id', session()->get('user_id'))->findColumn('outlet_id');
        $order = $orderModel->find($order_id);

        if (!$order || !in_array($order['outlet_id'], $ownedOutletIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk mengubah pesanan ini.');
        }

        if ($orderModel->update($order_id, ['status' => $status])) {
            return redirect()->to('/outlet/orders')->with('success', 'Status pesanan berhasil diperbarui.');
        }
        
        return redirect()->back()->with('error', 'Gagal memperbarui status pesanan.');
    }
    
    // --- FUNGSI LIHAT ULASAN ---
    public function showReviewsForOutlet($outlet_id)
    {
        // Inisialisasi model yang kita perlukan
        $outletModel = new OutletModel();
        $reviewModel = new ReviewModel();

        // 1. Ambil data outlet untuk ditampilkan di header halaman
        $outlet = $outletModel->find($outlet_id);

        // 2. Validasi Keamanan (PENTING!): 
        //    Pastikan outlet ada & benar-benar dimiliki oleh user yang sedang login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            // Jika tidak, lempar kembali ke halaman daftar outlet dengan pesan error
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses ke halaman ulasan ini.');
        }

        // 3. Ambil semua ulasan untuk outlet ini, dan gabungkan (join) dengan data customer
        $reviews = $reviewModel
            ->where('reviews.outlet_id', $outlet_id)
            ->join('users', 'users.user_id = reviews.customer_id')
            ->select('reviews.*, users.name as customer_name')
            ->orderBy('reviews.order_id', 'DESC') // Urutkan dari ulasan terbaru
            ->findAll();

        // 4. Siapkan data untuk dikirim ke view
        $data = [
            'outlet'  => $outlet,
            'reviews' => $reviews
        ];

        // 5. Tampilkan view 'list_reviews' dan kirim datanya
        return view('outlet/list_reviews', $data);
    }

    // --- FUNGSI KELOLA LAYANAN ---
    
    public function listServices($outlet_id)
    {
        $serviceModel = new ServiceModel();
        $outletModel = new OutletModel();

        // 1. Ambil data outlet yang akan dikelola
        $outlet = $outletModel->find($outlet_id);

        // 2. Validasi Keamanan: Pastikan outlet ada & dimiliki oleh user yg login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 3. Ambil semua layanan untuk outlet ini
        $data['services'] = $serviceModel->where('outlet_id', $outlet_id)->findAll();
        // 4. Kirim juga data outlet agar tahu layanan ini milik outlet mana
        $data['current_outlet'] = $outlet; 
        
        return view('outlet/services/index', $data);
    }

    public function createService($outlet_id)
    {
        $outletModel = new OutletModel();
        $outlet = $outletModel->find($outlet_id);

        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Outlet tidak ditemukan.');
        }

        $data = [
            'service' => null,
            'current_outlet' => $outlet
        ];
        return view('outlet/services/form', $data);
    }

    public function storeService()
    {
        $serviceModel = new ServiceModel();
        $outlet_id = $this->request->getPost('outlet_id');

        $outletModel = new OutletModel();
        $outlet = $outletModel->find($outlet_id);
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Aksi tidak diizinkan.');
        }

        $rules = [
            'name'  => 'required',
            'price' => 'required|numeric',
            'unit'  => 'required',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $service_id = $this->request->getPost('service_id');
        $data = [
            'outlet_id' => $outlet_id,
            'name'      => $this->request->getPost('name'),
            'price'     => $this->request->getPost('price'),
            'unit'      => $this->request->getPost('unit'),
        ];

        if ($service_id) { // Update
            $serviceModel->update($service_id, $data);
        } else { // Buat baru
            $serviceModel->insert($data);
        }

        return redirect()->to('/outlet/services/manage/' . $outlet_id)->with('success', 'Layanan berhasil disimpan.');
    }

    public function editService($service_id)
    {
        $serviceModel = new ServiceModel();
        $outletModel = new OutletModel();

        // 1. Ambil data layanan yang akan diedit
        $service = $serviceModel->find($service_id);
        if (!$service) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Layanan tidak ditemukan.');
        }

        // 2. Ambil data outlet dari layanan tersebut untuk validasi & ditampilkan di view
        $outlet = $outletModel->find($service['outlet_id']);

        // 3. Validasi Keamanan: Pastikan outlet ini milik owner yang sedang login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses untuk mengedit layanan ini.');
        }
        
        // 4. Kirim data ke view form yang sama
        $data = [
            'service'        => $service,
            'current_outlet' => $outlet
        ];
        return view('outlet/services/form', $data);
    }
    
    public function deleteService($service_id)
    {
        $serviceModel = new ServiceModel();
        $outletModel = new OutletModel();
        $outlet_id_to_redirect = null;

        // 1. Ambil data layanan yang akan dihapus
        $service = $serviceModel->find($service_id);
        
        // Pastikan layanan ada
        if ($service) {
            $outlet_id_to_redirect = $service['outlet_id'];
            
            // 2. Ambil data outlet dari layanan tersebut untuk validasi
            $outlet = $outletModel->find($service['outlet_id']);

            // 3. Validasi Keamanan: Pastikan outlet ini milik owner yang sedang login
            if ($outlet && $outlet['owner_id'] == session()->get('user_id')) {
                // 4. Jika valid, hapus layanan
                $serviceModel->delete($service_id);
                // Redirect kembali ke halaman daftar layanan dengan pesan sukses
                return redirect()->to('/outlet/services/manage/' . $outlet_id_to_redirect)->with('success', 'Layanan berhasil dihapus.');
            }
        }
        
        // Jika validasi gagal atau layanan tidak ditemukan, redirect dengan pesan error
        return redirect()->to('/outlet/my-outlets')->with('error', 'Gagal menghapus layanan atau Anda tidak memiliki akses.');
    }
}
