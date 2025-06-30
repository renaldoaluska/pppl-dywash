<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
use App\Models\ServiceModel;
use App\Models\OrderAddressModel; // Pastikan ini sesuai dengan nama model Anda
use App\Models\OrderItemModel; // Model untuk item dalam pesanan

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
                ->where('status', 'dicuci')
                ->orWhere('status', 'dikirim')
                ->orWhere('status', 'diambil')
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
        
        // PERUBAHAN UTAMA: Menambahkan orderBy dengan CASE untuk urutan custom
        $data['outlets'] = $outletModel
            ->where('owner_id', session()->get('user_id'))
            // Urutkan berdasarkan status: 'verified' dulu, lalu 'pending', lalu 'rejected'
            ->orderBy("CASE 
                            WHEN status = 'verified' THEN 1 
                            WHEN status = 'pending' THEN 2 
                            WHEN status = 'rejected' THEN 3 
                            ELSE 4 
                        END")
            // Lalu urutkan berdasarkan ID outlet yang paling baru
            ->orderBy('outlet_id', 'DESC')
            ->findAll();
        
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
            'contact_phone' => 'required',
    // ▼▼▼ TAMBAHKAN DUA BARIS INI ▼▼▼
    'latitude'      => 'required|numeric',
    'longitude'     => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $outlet_id = $this->request->getPost('outlet_id');
        $data = [
            'owner_id'        => session()->get('user_id'),
            'name'            => $this->request->getPost('name'),
            'address'         => $this->request->getPost('address'),
    // ▼▼▼ TAMBAHKAN DUA BARIS INI ▼▼▼
            'latitude'        => $this->request->getPost('latitude'),
            'longitude'       => $this->request->getPost('longitude'),
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
        
        // PERUBAHAN UTAMA: Query dibuat lebih eksplisit untuk setiap grup

        // Grup 1: Pesanan Aktif (yang belum selesai/ditolak)
        $data['pending_orders'] = $orderModel
            ->join('payments', 'payments.order_id = orders.order_id')
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->whereIn('orders.outlet_id', $outletIds)
            ->whereIn('payments.status', ['lunas', 'cod'])
            ->whereNotIn('orders.status', ['selesai', 'diulas', 'ditolak'])
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name') // Select eksplisit
            ->orderBy('orders.created_at', 'ASC')
            ->findAll();
        
        // Grup 2: Riwayat Pesanan (yang sudah selesai/ditolak)
        $data['history_orders'] = $orderModel
            ->join('payments', 'payments.order_id = orders.order_id')
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->whereIn('orders.outlet_id', $outletIds)
            ->whereIn('payments.status', ['lunas', 'cod'])
            ->whereIn('orders.status', ['selesai', 'diulas', 'ditolak'])
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name') // Select eksplisit
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();
        
            // Hitung jumlah pending pembayaran per outlet
// Hitung jumlah pending pembayaran per outlet
$data['pending_payments'] = $orderModel
    ->select('outlets.name as outlet_name, COUNT(*) as count')
    ->join('payments', 'payments.order_id = orders.order_id')
    ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
    ->whereIn('orders.outlet_id', $outletIds)
    ->where('payments.status', 'pending')
    ->groupBy('outlets.outlet_id, outlets.name')
    ->findAll();

        return view('outlet/list_orders', $data);
    }
    
    public function updateOrderStatus($order_id)
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        $newStatus = $this->request->getPost('status');
        
        // 1. Ambil data order saat ini dari database
        $order = $orderModel->find($order_id);
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        // 2. Validasi kepemilikan (pastikan outlet ini milik user yang login)
        $ownedOutletIds = $outletModel->where('owner_id', session()->get('user_id'))->findColumn('outlet_id');
        if (!in_array($order['outlet_id'], $ownedOutletIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk mengubah pesanan ini.');
        }

        // 3. Definisikan alur status yang diizinkan (urutan yang benar)
        $allowedTransitions = [
            'diterima' => ['diambil', 'ditolak'],
            'diambil'  => ['dicuci'],
            'dicuci'   => ['dikirim'],    // Setelah dicuci, harus dikirim
            'dikirim'  => ['selesai'],    // Setelah dikirim, baru bisa diselesaikan
        ];
        $currentStatus = $order['status'];

        // 4. Cek apakah perubahan status yang diminta sesuai dengan alur
        $isTransitionAllowed = isset($allowedTransitions[$currentStatus]) && in_array($newStatus, $allowedTransitions[$currentStatus]);
        
        // Pengecualian: 'ditolak' bisa dilakukan kapan saja sebelum dikirim
        $isRejectAllowed = ($newStatus === 'ditolak' && !in_array($currentStatus, ['selesai', 'dikirim', 'diulas']));
        
        if (!$isTransitionAllowed && !$isRejectAllowed) {
            return redirect()->back()->with('error', 'Perubahan status tidak diizinkan dari ' . ucfirst($currentStatus) . ' ke ' . ucfirst($newStatus) . '.');
        }

        // 5. Jika validasi lolos, lakukan update
        if ($orderModel->update($order_id, ['status' => $newStatus])) {
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
    public function listReviewsForOutlet($outlet_id)
    {
        $reviewModel = new ReviewModel();
        $outletModel = new OutletModel();

        // Validasi Keamanan (tetap sama)
        $outlet = $outletModel->find($outlet_id);
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke ulasan outlet ini.');
        }

        // Ambil semua ulasan yang memiliki outlet_id yang sama
        $reviews = $reviewModel
            ->where('reviews.outlet_id', $outlet_id)
            ->join('users', 'users.user_id = reviews.customer_id')
            // REVISI 1: Mengubah `created_at` menjadi `review_date` di SELECT
            ->select('reviews.rating, reviews.comment, reviews.review_date, users.name as customer_name')
            // REVISI 2: Mengubah `created_at` menjadi `review_date` di ORDER BY
            ->orderBy('reviews.review_date', 'DESC')
            ->findAll();
        
        // Kirim data ke view
        $data = [
            'reviews'           => $reviews,
            'outlet'    => $outlet
        ];

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
    public function profile()
    {
        return view('outlet/profile');
    }
    public function editProfile()
    {
        // Untuk form ini, kita perlu model User, bukan Outlet
        $userModel = new \App\Models\UserModel(); 
        
        // Ambil data user yang sedang login
        $data['user'] = $userModel->find(session()->get('user_id'));

        return view('outlet/profile/edit', $data);
    }

    /**
     * FUNGSI BARU: Memproses update data profil user outlet.
     */
    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();
        $user_id = session()->get('user_id');

        // Aturan validasi
        $rules = [
            'name' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data untuk diupdate
        $data = [
            'name' => $this->request->getPost('name'),
        ];

        // Lakukan update
        if ($userModel->update($user_id, $data)) {
            // Update juga data nama di session agar langsung berubah di tampilan
            session()->set('name', $data['name']);
            return redirect()->to('/outlet/profile')->with('success', 'Profil berhasil diperbarui!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
    }

    public function changePasswordForm()
    {
        return view('outlet/profile/change_password');
    }

    /**
     * FUNGSI BARU: Memproses update password.
     */
    public function updatePassword()
    {
        $userModel = new \App\Models\UserModel();
        $user_id = session()->get('user_id');
        $user = $userModel->find($user_id);

        // 1. Verifikasi password lama (tanpa hash)
        $old_password = $this->request->getPost('password_lama');
        // PERHATIAN: Pengecekan ini hanya berfungsi jika password lama di database TIDAK di-hash.
        if ($old_password !== $user['password']) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        // 2. Validasi password baru
        $rules = [
            'password_baru' => 'required|min_length[8]',
            'konfirmasi_password' => 'required|matches[password_baru]',
        ];
        
        $validationMessages = [
            'password_baru' => [
                'min_length' => 'Password baru minimal harus 8 karakter.'
            ],
            'konfirmasi_password' => [
                'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
            ]
        ];

        if (!$this->validate($rules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Jika semua validasi lolos, update password baru TANPA HASHING
        // PERINGATAN: Menyimpan password sebagai teks biasa adalah praktik yang sangat tidak aman.
        // Ini hanya untuk tujuan development. Di lingkungan produksi, selalu gunakan hashing.
        $newPassword = $this->request->getPost('password_baru');
        
        if ($userModel->update($user_id, ['password' => $newPassword])) {
            // Redirect dengan pesan sukses
            return redirect()->to('/outlet/profile')->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui password, silakan coba lagi.');
    }

    public function showOrderDetail($order_id)
    {
        $orderModel = new OrderModel();
        $outletModel = new OutletModel();
        
        // 1. Ambil data pesanan utama, join dengan semua data yang relevan
        $order = $orderModel
            ->where('orders.order_id', $order_id)
            ->join('users', 'users.user_id = orders.customer_id')
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, users.name as customer_name, outlets.name as outlet_name')
            ->first();

        // Jika pesanan tidak ditemukan, lempar kembali
        if (!$order) {
            return redirect()->to('/outlet/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        // 2. Validasi Keamanan: Pastikan pesanan ini milik salah satu outlet dari user yang login
        $ownedOutletIds = $outletModel->where('owner_id', session()->get('user_id'))->findColumn('outlet_id') ?? [];
        if (!in_array($order['outlet_id'], $ownedOutletIds)) {
            return redirect()->to('/outlet/orders')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // 3. Ambil data alamat dari tabel snapshot
        $addressModel = new \App\Models\OrderAddressModel(); // Ganti dengan nama model Anda
        $address = $addressModel->where('order_id', $order_id)->first();
        
        // 4. Ambil semua item/layanan dalam pesanan ini
        $orderItemModel = new \App\Models\OrderItemModel(); // Ganti dengan nama model Anda
        $order_items = $orderItemModel
            ->where('order_items.order_id', $order_id)
            ->join('services', 'services.service_id = order_items.service_id')
            ->select('order_items.*, services.name as service_name, services.unit')
            ->findAll();

        // 5. Kirim semua data ke view
        $data = [
            'order'       => $order,
            'address'     => $address,
            'order_items' => $order_items
        ];

        return view('outlet/order_detail', $data);
    }
    public function showOutletDetail($outlet_id)
    {
        $outletModel = new OutletModel();
        
        // 1. Ambil data outlet
        $outlet = $outletModel->find($outlet_id);

        // 2. Validasi Keamanan: Pastikan outlet ada & dimiliki oleh user yg login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Outlet tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // 3. Kirim data ke view
        $data['outlet'] = $outlet;
        return view('outlet/my_outlets/detail', $data);
    }

    /**
     * FUNGSI BARU: Menghapus outlet beserta data terkait.
     */
    public function deleteOutlet($outlet_id)
    {
        $outletModel = new OutletModel();

        // 1. Ambil data outlet yang akan dihapus untuk validasi
        $outlet = $outletModel->find($outlet_id);

        // 2. Validasi Keamanan: Pastikan outlet ada & dimiliki oleh user yg login
        if (!$outlet || $outlet['owner_id'] != session()->get('user_id')) {
            return redirect()->to('/outlet/my-outlets')->with('error', 'Anda tidak memiliki akses untuk menghapus outlet ini.');
        }

        // 3. Jika validasi lolos, hapus outlet.
        // Karena ada 'ON DELETE CASCADE' di database, data terkait (layanan, pesanan, ulasan)
        // seharusnya ikut terhapus secara otomatis.
        if ($outletModel->delete($outlet_id)) {
            return redirect()->to('/outlet/my-outlets')->with('success', 'Outlet berhasil dihapus.');
        }

        return redirect()->to('/outlet/my-outlets')->with('error', 'Gagal menghapus outlet.');
    }
}
