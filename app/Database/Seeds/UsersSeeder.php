<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Admin Dywash', 'email' => 'admin@dywash.com', 'password' => 'password123', 'role' => 'admin'],
            ['name' => 'Budi Laundry', 'email' => 'budi.laundry@mail.com', 'password' => 'password123', 'role' => 'outlet'],
            ['name' => 'Siti Laundry', 'email' => 'siti.laundry@mail.com', 'password' => 'password123', 'role' => 'outlet'],
            ['name' => 'Andi Pratama', 'email' => 'andi@mail.com', 'password' => 'password123', 'role' => 'cust'],
            ['name' => 'Citra Lestari', 'email' => 'citra@mail.com', 'password' => 'password123', 'role' => 'cust'],
            ['name' => 'Dewi Anggraini', 'email' => 'dewi@mail.com', 'password' => 'password123', 'role' => 'cust'],
        ];
        $this->db->table('users')->insertBatch($data);
    }
}
