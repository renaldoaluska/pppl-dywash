<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'latitude' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'role',
            ],
            'longitude' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'latitude',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['latitude', 'longitude']);
    }
}