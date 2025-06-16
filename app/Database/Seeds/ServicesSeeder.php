<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['outlet_id' => 1, 'name' => 'Cuci Kering Lipat', 'price' => 7000, 'unit' => 'kg'],
            ['outlet_id' => 1, 'name' => 'Setrika Saja', 'price' => 5000, 'unit' => 'kg'],
            ['outlet_id' => 1, 'name' => 'Cuci Selimut', 'price' => 15000, 'unit' => 'pcs'],
            ['outlet_id' => 2, 'name' => 'Cuci Kering Setrika', 'price' => 8000, 'unit' => 'kg'],
            ['outlet_id' => 2, 'name' => 'Cuci Sepatu', 'price' => 25000, 'unit' => 'pasang'],
            ['outlet_id' => 2, 'name' => 'Dry Cleaning Jas', 'price' => 35000, 'unit' => 'pcs'],
        ];
        $this->db->table('services')->insertBatch($data);
    }
}
