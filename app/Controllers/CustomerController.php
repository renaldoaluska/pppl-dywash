<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\ReviewModel;

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

                $orderItemsData[] = [
                    'service_id' => $service_id,
                    'quantity'   => $quantity,
                    'subtotal'   => $subtotal,
                ];
            }
        }
        
        $orderData = [
            'customer_id'    => session()->get('user_id'),
            'outlet_id'      => $outlet_id,
            'total_amount'   => $totalAmount,
            'status'         => 'diterima',
            'customer_notes' => $customer_notes,
        ];
        $order_id = $orderModel->insert($orderData);

        foreach ($orderItemsData as &$item) {
            $item['order_id'] = $order_id;
        }
        $orderItemModel->insertBatch($orderItemsData);

        return redirect()->to('/customer/monitor')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Fitur: Monitor Pesanan
     * Menampilkan halaman riwayat pesanan.
     */
    public function monitorOrder()
    {
        $orderModel = new OrderModel();
        
        $data['orders'] = $orderModel
            ->where('customer_id', session()->get('user_id'))
            ->join('outlets', 'outlets.outlet_id = orders.outlet_id')
            ->select('orders.*, outlets.name as outlet_name')
            ->findAll();

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
}
