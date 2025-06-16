<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'order_id' => 1,
                'customer_id' => 4,
                'outlet_id' => 1,
                'rating' => 5,
                'comment' => 'Hasilnya bersih dan wangi. Pengirimannya juga tepat waktu. Sangat direkomendasikan!'
            ],
        ];
        $this->db->table('reviews')->insertBatch($data);
    }
}
