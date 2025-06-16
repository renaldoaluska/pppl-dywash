<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['order_id' => 1, 'amount' => 21000, 'payment_method' => 'ewallet', 'status' => 'lunas', 'payment_date' => '2025-06-13 10:30:00'],
            ['order_id' => 2, 'amount' => 25000, 'payment_method' => 'cod', 'status' => 'lunas', 'payment_date' => '2025-06-14 15:00:00'],
            ['order_id' => 3, 'amount' => 20000, 'payment_method' => 'transfer', 'status' => 'pending', 'payment_date' => null],
        ];
        $this->db->table('payments')->insertBatch($data);
    }
}
