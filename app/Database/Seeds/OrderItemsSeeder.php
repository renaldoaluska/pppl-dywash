<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderItemsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['order_id' => 1, 'service_id' => 1, 'quantity' => 3, 'subtotal' => 21000],
            ['order_id' => 2, 'service_id' => 5, 'quantity' => 1, 'subtotal' => 25000],
            ['order_id' => 3, 'service_id' => 4, 'quantity' => 2.5, 'subtotal' => 20000],
        ];
        $this->db->table('order_items')->insertBatch($data);
    }
}
