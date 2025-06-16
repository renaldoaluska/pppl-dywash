<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'item_id' => ['type' => 'SERIAL'],
            'order_id' => ['type' => 'INT'],
            'service_id' => ['type' => 'INT'],
            'quantity' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('item_id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'service_id');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
