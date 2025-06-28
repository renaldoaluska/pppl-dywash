<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToOutlets extends Migration
{
    public function up()
    {
        $fields = [
            'latitude' => [
                'type' => ' Double PRECISION',
                'null' => true,
                'after' => 'address',
            ],
            'longitude' => [
                'type' => ' Double PRECISION',
                'null' => true,
                'after' => 'latitude',
            ],
        ];
        $this->forge->addColumn('outlets', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('outlets', ['latitude', 'longitude']);
    }
}