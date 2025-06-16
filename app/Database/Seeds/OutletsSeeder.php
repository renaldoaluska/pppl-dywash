<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OutletsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['owner_id' => 2, 'name' => 'KlinKlin Laundry', 'address' => 'Jl. Mawar No. 10, Surabaya', 'contact_phone' => '081234567890', 'operating_hours' => 'Senin - Sabtu, 08:00 - 20:00', 'status' => 'verified'],
            ['owner_id' => 3, 'name' => 'BersihWangi Laundry', 'address' => 'Jl. Melati No. 25, Surabaya', 'contact_phone' => '081223344556', 'operating_hours' => 'Setiap Hari, 07:00 - 21:00', 'status' => 'verified'],
            ['owner_id' => 2, 'name' => 'Cemerlang Laundry', 'address' => 'Jl. Anggrek No. 5, Surabaya', 'contact_phone' => '081987654321', 'operating_hours' => 'Senin - Jumat, 09:00 - 17:00', 'status' => 'pending'],
        ];
        $this->db->table('outlets')->insertBatch($data);
    }
}
