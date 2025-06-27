<?php // file: app/Controllers/OutletController.php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
use App\Models\ServiceModel;

class OutletController extends BaseController
{
    // ====================================================================
    // === FITUR BARU: MANAJEMEN BANYAK OUTLET ============================
    // ====================================================================

    /**
     * Menampilkan daftar semua outlet yang dimiliki oleh owner yang login.
     * Ini menjadi halaman utama untuk manajemen outlet.
     */
    public function listMyOutlets()
    {
        $outletModel = new OutletModel();
        $data['outlets'] = $outletModel->where('owner_id', session()->get('user_id'))->findAll();
        
        return view('outlet/my_outlets/index', $data);
    }

    

    /**
     * Menampilkan form KOSONG untuk mendaftarkan outlet baru.
     */
    public function createOutletForm()
    {
        // Selalu menampilkan form kosong dengan melewatkan data outlet sebagai null
        return view('outlet/my_outlets/form', ['outlet' => null]);
    }
    
    /**
     * Menampilkan form yang sudah terisi untuk mengedit outlet yang ada.
     * @param int $outlet_id
     */
    public function editOutletForm($outlet_id)
    {
        $outletModel = new OutletModel();
        $outlet = $outletModel->find($outlet_id);

        // Validasi keamanan: Pastikan outlet ini milik owner yang sedang login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses ke outlet ini.');
        }
        
        return view('outlet/my_outlets/form', ['outlet' => $outlet]);
    }

    /**
     * Menyimpan data outlet (bisa untuk membuat baru atau update yang sudah ada).
     */
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
            'status'          => 'pending' // Setiap perubahan data akan mengembalikan status ke pending untuk diverifikasi ulang
        ];

        if ($outlet_id) { // Jika ada ID, berarti ini adalah proses UPDATE
            // Validasi keamanan sebelum update
            $existing = $outletModel->find($outlet_id);
            if ($existing && $existing['owner_id'] == session()->get('user_id')) {
                $outletModel->update($outlet_id, $data);
            }
        } else { // Jika tidak ada ID, ini adalah proses INSERT (buat baru)
            $outletModel->insert($data);
        }

        return redirect()->to('/outlet/my-outlets')->with('success', 'Data outlet berhasil dikirim dan menunggu verifikasi.');
    }


    // --- FUNGSI KELOLA PESANAN (DIPERBARUI) ---
    public function listOrders()
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        
        $outlets = $outletModel->where(['owner_id' => session()->get('user_id'), 'status' => 'verified'])->findAll();

        if (empty($outlets)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki outlet terverifikasi untuk melihat pesanan.');
        }

        $outletIds = array_column($outlets, 'outlet_id');
        
        // Ambil pesanan dari SEMUA outlet yang dimiliki
        $data['orders'] = $orderModel
            // PERBAIKAN: Sebutkan nama tabel secara eksplisit untuk menghindari ambiguitas
            ->whereIn('orders.outlet_id', $outletIds)
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name')
            ->findAll();
        
        return view('outlet/list_orders', $data);
    }
    
    /**
     * Fitur: Update Status Pesanan (Langkah 2) - DIPERBARUI
     * Memproses perubahan status pesanan dengan validasi kepemilikan.
     * @param int $order_id
     */
    public function updateOrderStatus($order_id)
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel(); // Diperlukan untuk validasi
        $status = $this->request->getPost('status');
        
        // 1. Validasi input status
        if (!in_array($status, ['diproses', 'selesai', 'ditolak'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        // 2. === VALIDASI KEPEMILIKAN PESANAN (BAGIAN PENTING YANG DITAMBAHKAN) ===
        // Ambil ID dari semua outlet yang dimiliki oleh user yang login
        $ownedOutletIds = $outletModel->where('owner_id', session()->get('user_id'))->findColumn('outlet_id');
        // Ambil data pesanan yang akan diupdate
        $order = $orderModel->find($order_id);

        // Jika pesanan tidak ditemukan ATAU outlet_id dari pesanan tidak ada di dalam daftar outlet yang dimiliki
        if (!$order || !in_array($order['outlet_id'], $ownedOutletIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk mengubah pesanan ini.');
        }
        // === AKHIR VALIDASI ===

        // 3. Jika validasi lolos, lakukan update
        if ($orderModel->update($order_id, ['status' => $status])) {
            return redirect()->to('/outlet/orders')->with('success', 'Status pesanan berhasil diperbarui.');
        }
        
        return redirect()->back()->with('error', 'Gagal memperbarui status pesanan.');
    }
    // --- FUNGSI LIHAT ULASAN (DIPERBARUI) ---
     public function listReviews()
    {
        $reviewModel = new ReviewModel();
        $outletModel = new OutletModel();

        $outlets = $outletModel->where('owner_id', session()->get('user_id'))->findAll();
        if (empty($outlets)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki outlet untuk melihat ulasan.');
        }

        $outletIds = array_column($outlets, 'outlet_id');

        $data['reviews'] = $reviewModel
            // PERBAIKAN: Sebutkan nama tabel secara eksplisit untuk menghindari ambiguitas
            ->whereIn('reviews.outlet_id', $outletIds)
            ->join('users', 'users.user_id = reviews.customer_id')
            ->join('outlets', 'outlets.outlet_id = reviews.outlet_id')
            ->select('reviews.*, users.name as customer_name, outlets.name as outlet_name')
            ->findAll();

        return view('outlet/list_reviews', $data);
    }

    // --- FUNGSI KELOLA LAYANAN (SERVICES) ---
    
    /**
     * Menampilkan daftar layanan yang dimiliki oleh outlet.
     * Logika ini perlu disesuaikan karena satu owner bisa punya banyak outlet.
     * Untuk saat ini, kita akan fokus pada satu outlet yang dipilih atau outlet pertama.
     */
    public function listServices()
    {
        $serviceModel = new ServiceModel();
        $outletModel = new OutletModel();

        // Sementara, kita ambil outlet pertama yang terverifikasi untuk dikelola layanannya
        $outlet = $outletModel->where(['owner_id' => session()->get('user_id'), 'status' => 'verified'])->first();
        if (!$outlet) {
            return redirect()->to('/dashboard')->with('error', 'Anda harus memiliki setidaknya satu outlet terverifikasi untuk mengelola layanan.');
        }

        $data['services'] = $serviceModel->where('outlet_id', $outlet['outlet_id'])->findAll();
        // Kirim juga data outlet agar tahu layanan ini milik outlet mana
        $data['current_outlet'] = $outlet; 
        
        return view('outlet/services/index', $data);
    }

    /**
     * Menampilkan form untuk menambah layanan baru.
     */
    public function createService()
    {
        return view('outlet/services/form', ['service' => null]);
    }

    /**
     * Menampilkan form untuk mengedit layanan yang ada.
     * @param int $service_id
     */
    public function editService($service_id)
    {
        $serviceModel = new ServiceModel();
        $data['service'] = $serviceModel->find($service_id);
        
        return view('outlet/services/form', $data);
    }

    /**
     * Menyimpan data layanan (baik baru maupun update).
     */
    public function storeService()
    {
        $serviceModel = new ServiceModel();
        $outletModel = new OutletModel();

        // Karena layanan terikat pada satu outlet, kita perlu tahu outlet mana yang layanannya akan ditambah/diubah.
        // Untuk sementara, kita asumsikan layanan ditambahkan ke outlet terverifikasi pertama.
        $outlet = $outletModel->where(['owner_id' => session()->get('user_id'), 'status' => 'verified'])->first();
        if (!$outlet) {
            return redirect()->to('/dashboard')->with('error', 'Tidak ada outlet terverifikasi untuk ditambahkan layanan.');
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
            'outlet_id' => $outlet['outlet_id'], // Tambahkan ke outlet yang aktif
            'name'      => $this->request->getPost('name'),
            'price'     => $this->request->getPost('price'),
            'unit'      => $this->request->getPost('unit'),
        ];

        if ($service_id) { // Update
            $serviceModel->update($service_id, $data);
        } else { // Buat baru
            $serviceModel->insert($data);
        }

        return redirect()->to('/outlet/services')->with('success', 'Layanan berhasil disimpan.');
    }

    /**
     * Menghapus layanan.
     * @param int $service_id
     */
    public function deleteService($service_id)
    {
        $serviceModel = new ServiceModel();
        $serviceModel->delete($service_id);
        return redirect()->to('/outlet/services')->with('success', 'Layanan berhasil dihapus.');
    }
}
