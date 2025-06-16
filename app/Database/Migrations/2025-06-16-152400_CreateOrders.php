<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOrders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'order_id' => ['type' => 'SERIAL'],
            'customer_id' => ['type' => 'INT'],
            'outlet_id' => ['type' => 'INT'],
            'order_date' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'status' => ['type' => 'order_status_enum', 'default' => 'diterima'],
            'customer_notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('order_id', true);
        $this->forge->addForeignKey('customer_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('outlet_id', 'outlets', 'outlet_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
