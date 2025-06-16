<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'service_id' => ['type' => 'SERIAL'],
            'outlet_id' => ['type' => 'INT'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('service_id', true);
        $this->forge->addForeignKey('outlet_id', 'outlets', 'outlet_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}
