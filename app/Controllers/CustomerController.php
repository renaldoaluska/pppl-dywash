<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\ReviewModel;
use App\Models\PaymentModel;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    /**
     * Fitur: Melihat/Mencari Outlet
     * Menampilkan halaman dengan daftar semua outlet yang tersedia.
     */
    public function listOutlet()
    {
        $outletModel = new OutletModel();
        
        $keyword = $this->request->getGet('search');
        
        $outletModel->where('status', 'verified');

        if ($keyword) {
            $outletModel->like('name', $keyword);
        }

        $data['outlets'] = $outletModel->findAll();

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

        $data['outlet'] = $outletModel->where(['outlet_id' => $outlet_id, 'status' => 'verified'])->first();

        if (!$data['outlet']) {
            return redirect()->to('/customer/outlet')->with('error', 'Outlet tidak ditemukan.');
        }

        $data['services'] = $serviceModel->where('outlet_id', $outlet_id)->findAll();

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

        $outlet_id = $this->request->getPost('outlet_id');
        $services = $this->request->getPost('services');
        $customer_notes = $this->request->getPost('customer_notes');

        $totalAmount = 0;
        $orderItemsData = [];

        if (empty($services)) {
             return redirect()->back()->withInput()->with('error', 'Pilih minimal satu layanan.');
        }

        foreach ($services as $service_id => $quantity) {
            if ($quantity > 0) {
                $service = $serviceModel->find($service_id);
                $subtotal = $service['price'] * $quantity;
                $totalAmount += $subtotal;
                $orderItemsData[] = ['service_id' => $service_id, 'quantity' => $quantity, 'subtotal' => $subtotal];
            }
        }
        
        $orderData = [
            'customer_id'    => session()->get('user_id'),
            'outlet_id'      => $outlet_id,
            'total_amount'   => $totalAmount,
            // STATUS AWAL BARU
            // 'status'         => 'pending', 
            'customer_notes' => $customer_notes,
        ];
        $order_id = $orderModel->insert($orderData);

        foreach ($orderItemsData as &$item) {
            $item['order_id'] = $order_id;
        }
        $orderItemModel->insertBatch($orderItemsData);

        // Arahkan ke halaman pembayaran, bukan riwayat
        return redirect()->to('/customer/payment/' . $order_id)->with('success', 'Pesanan dibuat. Silakan lakukan pembayaran.');
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
            $paymentData['status'] = 'lunas';
            // Langsung ubah status pesanan menjadi 'diterima'
            $orderModel->update($order_id, ['status' => 'diterima']);
        } else { // Untuk 'transfer' dan 'ewallet'
            $paymentData['status'] = 'pending';
            // Status pesanan tetap 'menunggu_pembayaran' sampai admin verifikasi
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
        ->select('orders.*, outlets.name as outlet_name, reviews.rating as review_rating, reviews.comment as review_comment');

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
    
}
