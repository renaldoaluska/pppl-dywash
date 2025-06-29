<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\ReviewModel;
use App\Models\PaymentModel;
use App\Models\UserModel;
use App\Models\AddressModel;

class CustomerController extends BaseController
{
    /**
     * Fitur: Melihat/Mencari Outlet
     * Menampilkan halaman dengan daftar semua outlet yang tersedia.
     */
/**
     * Fitur: Menampilkan Halaman Utama/Dashboard Customer yang Dinamis
     * - Menampilkan outlet terdekat berdasarkan alamat utama user.
     * - Menampilkan outlet dengan rating tertinggi.
     */
public function dashboard()
{
    helper('text');

    $outletModel = new \App\Models\OutletModel();
    $addressModel = new \App\Models\AddressModel();
    $userId = session()->get('user_id');

    $data = [
        'nearestLaundries' => [],
        'topRatedLaundries' => [],
        'allUserAddresses' => [],
        'activeAddress'    => null,
        'userLat'          => null,
        'userLon'          => null,
    ];

    if (!$userId) {
        return view('dashboard/customer', $data);
    }

    // Ambil semua alamat user
    $data['allUserAddresses'] = $addressModel
        ->where('user_id', $userId)
        ->orderBy('is_primary', 'DESC')
        ->findAll();

    // Tentukan alamat aktif
    $selectedAddressId = $this->request->getGet('address_id');
    $activeAddress = null;

    if ($selectedAddressId) {
        $activeAddress = $addressModel
            ->where('user_id', $userId)
            ->where('address_id', $selectedAddressId)
            ->first();
    } else {
        $activeAddress = $addressModel
            ->where('user_id', $userId)
            ->where('is_primary', true)
            ->first();
    }

    $data['activeAddress'] = $activeAddress;

    if ($activeAddress && !empty($activeAddress['latitude']) && !empty($activeAddress['longitude'])) {
        $userLat = $activeAddress['latitude'];
        $userLon = $activeAddress['longitude'];

        $data['userLat'] = $userLat;
        $data['userLon'] = $userLon;

        $distanceQuery = "( 6371 * acos( cos( radians({$userLat}) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians({$userLon}) ) + sin( radians({$userLat}) ) * sin( radians( latitude ) ) ) )";

        $data['nearestLaundries'] = $outletModel
            ->where('status', 'verified')
            ->select("outlets.*, {$distanceQuery} AS distance")
            ->orderBy('distance', 'ASC')
            ->findAll(5);
    }

    $data['topRatedLaundries'] = $outletModel
        ->where('status', 'verified')
        ->select('outlets.*, AVG(reviews.rating) as average_rating')
        ->join('reviews', 'reviews.outlet_id = outlets.outlet_id', 'left')
        ->groupBy('outlets.outlet_id')
        ->orderBy('average_rating', 'DESC NULLS LAST')
        ->findAll(5);

    return view('dashboard/customer', $data);
}
public function listOutlet()
{
    
    helper('text');
    $outletModel = new \App\Models\OutletModel();
    $addressModel = new \App\Models\AddressModel();
    $userId = session()->get('user_id');

    $keyword = $this->request->getGet('search');
    $maxDistance = $this->request->getGet('max_distance') ?? 10;
    $addressId = $this->request->getGet('address_id');

    $outletModel->where('status', 'verified');

    if ($keyword) {
        $keyword = strtolower($keyword);
        $outletModel->groupStart()
            ->where('LOWER(name) LIKE', '%' . $keyword . '%')
            ->orWhere('LOWER(address) LIKE', '%' . $keyword . '%')
            ->groupEnd();
    }

    $activeAddress = null;
    if ($userId) {
        if ($addressId) {
            $activeAddress = $addressModel
                ->where('user_id', $userId)
                ->where('address_id', $addressId)
                ->first();
        } else {
            $activeAddress = $addressModel
                ->where('user_id', $userId)
                ->where('is_primary', true)
                ->first();
        }
    }

    if ($activeAddress && !empty($activeAddress['latitude']) && !empty($activeAddress['longitude'])) {
        $userLat = $activeAddress['latitude'];
        $userLon = $activeAddress['longitude'];

        $distanceExpr = "(6371 * acos(cos(radians({$userLat})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$userLon})) + sin(radians({$userLat})) * sin(radians(latitude))))";

        $outletModel->select("outlets.*, {$distanceExpr} AS distance")
                    ->where("{$distanceExpr} <= {$maxDistance}")
                    ->orderBy('distance', 'ASC');
    }

    $data['outlets'] = $outletModel->findAll();
    $data['keyword'] = $keyword;
    $data['userLat'] = $activeAddress['latitude'] ?? null;
    $data['userLon'] = $activeAddress['longitude'] ?? null;
    $data['maxDistance'] = $maxDistance;
    $data['activeAddress'] = $activeAddress;
    $data['allUserAddresses'] = $userId ? $addressModel->where('user_id', $userId)->findAll() : [];

    return view('customer/list_outlet', $data);
}


    /**
     * Fitur: Melakukan Pesanan (Langkah 1)
     * Menampilkan form untuk membuat pesanan.
     * @param int $outlet_id
     */
public function createOrder($outlet_id)
{
    $outletModel = new OutletModel();
    $serviceModel = new ServiceModel();
    $addressModel = new AddressModel();

    $data['outlet'] = $outletModel->where(['outlet_id' => $outlet_id, 'status' => 'verified'])->first();

    if (!$data['outlet']) {
        return redirect()->to('/customer/outlet')->with('error', 'Outlet tidak ditemukan.');
    }

    $data['services'] = $serviceModel->where('outlet_id', $outlet_id)->findAll();

    // Ambil primary address
    $data['primary_address'] = $addressModel
        ->where('user_id', session()->get('user_id'))
        ->where('is_primary', true)
        ->first();

    // Ambil other addresses (selain primary)
    $data['other_addresses'] = $addressModel
        ->where('user_id', session()->get('user_id'))
        ->where('is_primary', false)
        ->findAll();

    return view('customer/order/create', $data);
}




    /**
     * Fitur: Melakukan Pesanan (Langkah 2)
     * Menyimpan data pesanan baru.
     */
public function storeOrder()
{
    $orderModel = new OrderModel();
    $orderItemModel = new OrderItemModel();
    $serviceModel = new ServiceModel();
    $addressModel = new AddressModel();
    $db = \Config\Database::connect();

    $outlet_id = $this->request->getPost('outlet_id');
    $services = $this->request->getPost('services');
    $customer_notes = $this->request->getPost('customer_notes');
    $address_id = $this->request->getPost('address_id');

    // 🔥 Cek jika user belum punya alamat
    if (!$address_id) {
        return redirect()->to('/customer/profil/tambah-alamat')
            ->with('error', 'Tambah alamat pengiriman dulu sebelum membuat pesanan.');
    }

    // 🔥 Cek minimal ada 1 layanan
    if (empty($services)) {
        return redirect()->back()->withInput()->with('error', 'Pilih minimal satu layanan.');
    }

    $totalAmount = 0;
    $orderItemsData = [];

    foreach ($services as $service_id => $quantity) {
        if ($quantity > 0) {
            $service = $serviceModel->find($service_id);
            if (!$service) continue;
            $subtotal = $service['price'] * $quantity;
            $totalAmount += $subtotal;
            $orderItemsData[] = [
                'service_id' => $service_id,
                'quantity'   => $quantity,
                'subtotal'   => $subtotal
            ];
        }
    }

    $db->transStart();

    // Insert orders (tanpa orders_address_id dulu)
    $orderData = [
        'customer_id'    => session()->get('user_id'),
        'outlet_id'      => $outlet_id,
        'total_amount'   => $totalAmount,
        'customer_notes' => $customer_notes,
    ];
    $orderModel->insert($orderData);
    $order_id = $orderModel->getInsertID();

    if (!$order_id) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Gagal membuat pesanan.');
    }

    // Insert orders_address snapshot
    $address = $addressModel->find($address_id);
    if ($address) {
        $db->table('orders_address')->insert([
            'order_id'       => $order_id,
            'label'          => $address['label'],
            'recipient_name' => $address['recipient_name'],
            'phone_number'   => $address['phone_number'],
            'address_detail' => $address['address_detail'],
            'latitude'       => $address['latitude'],
            'longitude'      => $address['longitude'],
        ]);
        $orders_address_id = $db->insertID();

        if (!$orders_address_id) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan alamat pesanan.');
        }

        // Update orders dengan orders_address_id
        // 🔥 Pastikan allowedFields di model orders sudah ada field ini
        $orderModel->update($order_id, ['orders_address_id' => $orders_address_id]);
    } else {
        $db->transRollback();
        return redirect()->back()->with('error', 'Alamat tidak ditemukan.');
    }

    // Insert order_items
    foreach ($orderItemsData as &$item) {
        $item['order_id'] = $order_id;
    }
    $orderItemModel->insertBatch($orderItemsData);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Gagal menyimpan pesanan.');
    }

    return redirect()->to('/customer/payment/' . $order_id)
        ->with('success', 'Pesanan dibuat. Silakan lakukan pembayaran.');
}



        /**
     * FUNGSI BARU: Menampilkan form pembayaran untuk sebuah pesanan.
     * @param int $order_id
     */
    public function paymentForm($order_id)
    {
        $orderModel = new OrderModel();
        $data['order'] = $orderModel->find($order_id);

        // Validasi: pastikan order ini milik customer yang login
        if (!$data['order'] || $data['order']['customer_id'] != session()->get('user_id')) {
            return redirect()->to('/customer/monitor')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        return view('customer/payment_form', $data);
    }

    /**
     * FUNGSI BARU: Memproses pembayaran yang dipilih oleh customer.
     */
    public function processPayment()
    {
        $paymentModel = new PaymentModel();
        $orderModel = new OrderModel();

        $order_id = $this->request->getPost('order_id');
        $amount = $this->request->getPost('amount');
        $payment_method = $this->request->getPost('payment_method');

        $paymentData = [
            'order_id'       => $order_id,
            'amount'         => $amount,
            'payment_method' => $payment_method,
            'payment_date'   => date('Y-m-d H:i:s'),
        ];
        
        // Logika berdasarkan metode pembayaran
        if ($payment_method === 'cod') {
            $paymentData['status'] = 'cod';
            // Langsung ubah status pesanan menjadi 'diterima'
            $orderModel->update($order_id, ['status' => 'diterima']);
        } else { // Untuk 'transfer' dan 'ewallet'
            $paymentData['status'] = 'pending';
            // Status pesanan tetap 'menunggu_pembayaran' sampai admin verifikasi
            // Langsung ubah status pesanan menjadi 'diterima'
            $orderModel->update($order_id, ['status' => 'diterima']);
        }

        $paymentModel->save($paymentData);

        return redirect()->to('/customer/monitor')->with('success', 'Proses pembayaran dicatat');
    }

    /**
     * Fitur: Monitor Pesanan
     * Menampilkan halaman riwayat pesanan.
     */
public function monitorOrder()
{
    $orderModel = new OrderModel();

    $filter_status = $this->request->getGet('status');
    $sort = $this->request->getGet('sort');

    $query = $orderModel
        ->where('orders.customer_id', session()->get('user_id'))
        ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
        ->join('reviews', 'reviews.order_id = orders.order_id', 'left')
        ->join('payments', 'payments.order_id = orders.order_id', 'left') // ✅ tambahkan join ini
        ->select('orders.*, outlets.name as outlet_name, reviews.rating as review_rating, reviews.comment as review_comment, payments.payment_method, payments.status as payment_status');


    if ($filter_status) {
        $query->where('orders.status', $filter_status);
    }

    if ($sort == 'oldest') {
        $query->orderBy('orders.order_date', 'ASC');
    } else { // default: newest
        $query->orderBy('orders.order_date', 'DESC');
    }

    $data['orders'] = $query->findAll();
    $data['filter_status'] = $filter_status;
    $data['sort'] = $sort;

    return view('customer/order/history', $data);
}



    /**
     * Fitur: Memberikan Review
     * Menyimpan ulasan untuk pesanan yang sudah selesai.
     */
    public function storeReview()
    {
        $reviewModel = new ReviewModel();
        $orderModel = new OrderModel();

        $order_id = $this->request->getPost('order_id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');

        $order = $orderModel->where([
            'order_id'    => $order_id,
            'customer_id' => session()->get('user_id'),
            'status'      => 'selesai'
        ])->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak valid untuk diberi ulasan.');
        }

        $data = [
            'order_id'    => $order_id,
            'customer_id' => session()->get('user_id'),
            'outlet_id'   => $order['outlet_id'],
            'rating'      => $rating,
            'comment'     => $comment
        ];

        if ($reviewModel->save($data)) {
            $orderModel->update($order_id, ['status' => 'diulas']);
            return redirect()->to('/customer/monitor')->with('success', 'Terima kasih atas ulasan Anda!');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan ulasan.');
    }

      /**
     * Menampilkan halaman profil utama.
     */
    public function cekProfil()
    {
        // Kode ini sudah benar, tidak perlu diubah.
        return view('customer/profil');
    }

    /**
     * Menampilkan form untuk mengubah data profil.
     */
    public function editProfil()
    {
        // Kode ini sudah benar, mengambil data user untuk ditampilkan di form.
        $userModel = new UserModel();
        $data['user'] = $userModel->find(session('user_id'));

        return view('customer/edit_profil', $data);
    }

    /**
     * Memproses pembaruan nama dari form edit profil.
     * Email tidak lagi diproses sesuai permintaan.
     */
    public function updateProfil()
    {
        $userModel = new UserModel();
        $userId = session('user_id');

        // Validasi hanya untuk 'name'.
        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kirim error ke view.
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data hanya 'name' untuk diupdate.
        $data = [
            'name'  => $this->request->getPost('name'),
        ];

        $userModel->update($userId, $data);

        // Perbarui session 'name'.
        session()->set(['name' => $data['name']]);

        return redirect()->to('/customer/profil')->with('toast', [
            'type' => 'success', 
            'message' => 'Profil berhasil diperbarui!'
        ]);
    }

    /**
     * Memproses form ganti password (tanpa hash).
     */
    
    // METHOD BARU: Untuk menampilkan halaman ganti password
    public function showChangePasswordForm()
    {
        return view('customer/ganti_password');
    }

    // NAMA METHOD DIUBAH: Untuk memproses form ganti password
// app/Controllers/CustomerController.php

public function processChangePassword()
{
    $rules = [
        'password_lama' => 'required',
        'password_baru' => 'required|min_length[8]',
        'konfirmasi_password' => 'required|matches[password_baru]',
    ];

    // Tambahkan pesan error kustom dalam Bahasa Indonesia
    $errors = [
        'password_baru' => [
            'min_length' => 'Password baru minimal harus 8 karakter.'
        ],
        'konfirmasi_password' => [
            'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
        ]
    ];

    // PERUBAHAN UTAMA DI SINI
    if (!$this->validate($rules, $errors)) {
        // Ambil pesan error pertama yang muncul
        $errorMsg = array_values($this->validator->getErrors())[0];

        // Redirect kembali dengan TOAST, bukan dengan array 'errors'
        return redirect()->back()->withInput()->with('toast', [
            'type' => 'error',
            'message' => $errorMsg
        ]);
    }

    $userModel = new \App\Models\UserModel();
    $userId = session('user_id');
    $user = $userModel->find($userId);

    if ($this->request->getPost('password_lama') !== $user['password']) {
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Password lama yang Anda masukkan salah.'
        ]);
    }
    
    $newPassword = $this->request->getPost('password_baru');
    $userModel->update($userId, ['password' => $newPassword]);

    return redirect()->to('/customer/profil')->with('toast', [
        'type' => 'success',
        'message' => 'Password berhasil diganti!'
    ]);
}
 
public function orderDetail($order_id)
{
    $orderModel = new OrderModel();
    $orderItemModel = new OrderItemModel();

    // Ambil data order + join outlet + join payments + join orders_address
    $order = $orderModel
        ->where('orders.order_id', $order_id)
        ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
        ->join('payments', 'payments.order_id = orders.order_id', 'left')
        ->join('orders_address', 'orders.orders_address_id = orders_address.order_address_id', 'left')
        ->select('orders.*, outlets.name as outlet_name, outlets.address as outlet_address,
                  payments.payment_method, payments.status as payment_status,
                  orders_address.label, orders_address.recipient_name, orders_address.phone_number, orders_address.address_detail')
        ->first();

    // Validasi apakah order ada dan milik user login
    if (!$order || $order['customer_id'] != session()->get('user_id')) {
        return redirect()->to('/customer/monitor')->with('error', 'Pesanan tidak ditemukan.');
    }

    // Ambil data item layanan
    $items = $orderItemModel
        ->where('order_id', $order_id)
        ->join('services', 'services.service_id = order_items.service_id')
        ->select('order_items.*, services.name as service_name, services.unit')
        ->findAll();

    return view('customer/order/detail', [
        'order' => $order,
        'items' => $items,
    ]);
}
public function paymentLater()
{
    return redirect()->to('/customer/monitor')
        ->with('success', 'Kamu bisa konfirmasi pembayaran nanti ya!');
}
public function createAlamat()
{
    $redirect_to = $this->request->getGet('redirect_to') ?? '/customer/profil/alamat';

    $addressModel = new \App\Models\AddressModel();
    $user_id = session()->get('user_id');
    $hasAnyAddress = $addressModel->where('user_id', $user_id)->countAllResults() > 0;

    return view('customer/alamat/tambah', [
        'redirect_to' => $redirect_to,
        'force_primary' => !$hasAnyAddress, // true jika belum punya alamat sama sekali
    ]);
}


public function storeAlamat()
{
    $validation = \Config\Services::validation();
    $data = $this->request->getPost();

    $validation->setRules([
        'label' => 'required|string|max_length[100]',
        'recipient_name' => 'required|string|max_length[255]',
        'phone_number' => 'required|string|max_length[20]',
        'address_detail' => 'required|string',
        'latitude' => 'required|decimal',
        'longitude' => 'required|decimal',
    ]);

    if (!$validation->run($data)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $addressModel = new \App\Models\AddressModel();
    $user_id = session()->get('user_id');
    $hasAnyAddress = $addressModel->where('user_id', $user_id)->countAllResults() > 0;

    $is_primary = !$hasAnyAddress;

    if ($is_primary) {
        // reset semua (just in case)
        $addressModel->where('user_id', $user_id)
                     ->set(['is_primary' => false])
                     ->update();
    }

    $insertData = [
        'user_id'        => $user_id,
        'label'          => $data['label'],
        'recipient_name' => $data['recipient_name'],
        'phone_number'   => $data['phone_number'],
        'address_detail' => $data['address_detail'],
        'latitude'       => $data['latitude'],
        'longitude'      => $data['longitude'],
        'is_primary'     => $is_primary,
    ];
    $addressModel->insert($insertData);

    $redirect_to = $this->request->getPost('redirect_to') ?? '/customer/profil/alamat';

    return redirect()->to($redirect_to)->with('success', 'Alamat berhasil ditambahkan.');
}




public function editAlamat($id)
{
    $addressModel = new \App\Models\AddressModel();
    $user_id = session()->get('user_id');
    $address = $addressModel->where('user_id', $user_id)->find($id);

    if (!$address) {
        return redirect()->to('/customer/profil/alamat')->with('error', 'Alamat tidak ditemukan.');
    }

    // Force cast boolean
    $address['is_primary'] = ($address['is_primary'] === 't' || $address['is_primary'] === true || $address['is_primary'] === 1);

    // Cek total alamat user
    $total = $addressModel->where('user_id', $user_id)->countAllResults();
    $force_primary = ($total == 1); // jika cuma 1 alamat, paksa jadi primary

    return view('customer/alamat/edit', [
        'address' => $address,
        'force_primary' => $force_primary,
    ]);
}

public function setPrimaryAlamat($id)
{
    $addressModel = new \App\Models\AddressModel();
    $userId = session()->get('user_id');

    // Ambil alamat milik user
    $address = $addressModel
        ->where('user_id', $userId)
        ->find($id);

    if (!$address) {
        return redirect()->to('/customer/profil/alamat')->with('error', 'Alamat tidak ditemukan.');
    }

    // Reset semua alamat user jadi non-primary
    $addressModel->where('user_id', $userId)
                 ->set(['is_primary' => false])
                 ->update();

    // Set alamat ini jadi primary
    $addressModel->update($id, ['is_primary' => true]);

    // Redirect dengan flashdata success untuk toast
    return redirect()->back()->with('success', 'Alamat berhasil dijadikan utama.');
}

public function updateAlamat($id)
{
    $addressModel = new \App\Models\AddressModel();
    $address = $addressModel->where('user_id', session()->get('user_id'))->find($id);

    if (!$address) {
        return redirect()->to('/customer/profil/alamat')->with('error', 'Alamat tidak ditemukan.');
    }

    $validation = \Config\Services::validation();
    $data = $this->request->getPost();

    // Validasi
    $validation->setRules([
        'label' => 'required|string|max_length[100]',
        'recipient_name' => 'required|string|max_length[255]',
        'phone_number' => 'required|string|max_length[20]',
        'address_detail' => 'required|string',
        'latitude' => 'required|decimal',
        'longitude' => 'required|decimal',
    ]);

    if (!$validation->run($data)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // HAPUS is_primary dari data update agar tidak ke-reset
    unset($data['is_primary']);

    $updateData = [
        'label'          => $data['label'],
        'recipient_name' => $data['recipient_name'],
        'phone_number'   => $data['phone_number'],
        'address_detail' => $data['address_detail'],
        'latitude'       => $data['latitude'],
        'longitude'      => $data['longitude'],
        // 'is_primary' dihapus agar tidak mengubah status utama
    ];

    $addressModel
    ->where('address_id', $id)
    ->where('user_id', session()->get('user_id'))
    ->set($updateData)
    ->update();


    return redirect()->to('/customer/profil/alamat')->with('success', 'Alamat berhasil diperbarui.');
}

public function deleteAlamat($id)
{
    $addressModel = new \App\Models\AddressModel();
    $address = $addressModel->where('user_id', session()->get('user_id'))->find($id);

    if (!$address) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Alamat tidak ditemukan.']);
        }
        return redirect()->to('/customer/profil/alamat')->with('error', 'Alamat tidak ditemukan.');
    }

    if (filter_var($address['is_primary'], FILTER_VALIDATE_BOOLEAN)) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Alamat utama tidak dapat dihapus.']);
        }
        return redirect()->to('/customer/profil/alamat')->with('error', 'Alamat utama tidak dapat dihapus.');
    }

    $addressModel->delete($id);

    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success' => 'Alamat berhasil dihapus.']);
    }
    return redirect()->to('/customer/profil/alamat')->with('success', 'Alamat berhasil dihapus.');
}




public function indexAlamat()
{
    $addressModel = new \App\Models\AddressModel();

    $addresses = $addressModel
        ->where('user_id', session()->get('user_id'))
        ->orderBy('is_primary', 'DESC') // 👉 tambah baris ini
        ->findAll();

    // FIX CASTING is_primary ke boolean
    foreach ($addresses as &$addr) {
        $addr['is_primary'] = ($addr['is_primary'] === 't' || $addr['is_primary'] === true || $addr['is_primary'] === 1 || $addr['is_primary'] === '1');
    }


    return view('customer/alamat/daftar', [
        'addresses' => $addresses
    ]);
}



}
