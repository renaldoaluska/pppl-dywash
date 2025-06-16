<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(OutletsSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(OrderItemsSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ReviewsSeeder::class);
    }
}
