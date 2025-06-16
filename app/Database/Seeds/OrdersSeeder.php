<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['customer_id' => 4, 'outlet_id' => 1, 'status' => 'diulas', 'customer_notes' => 'Tolong jangan dicampur dengan pakaian warna putih.', 'total_amount' => 21000],
            ['customer_id' => 5, 'outlet_id' => 2, 'status' => 'selesai', 'customer_notes' => 'Sepatu kanvas, tolong sikat bagian dalamnya juga.', 'total_amount' => 25000],
            ['customer_id' => 4, 'outlet_id' => 2, 'status' => 'diproses', 'customer_notes' => null, 'total_amount' => 20000],
        ];
        $this->db->table('orders')->insertBatch($data);
    }
}
