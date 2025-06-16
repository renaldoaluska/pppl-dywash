<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'payment_id' => ['type' => 'SERIAL'],
            'order_id' => ['type' => 'INT'],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'payment_method' => ['type' => 'payment_method_enum'],
            'status' => ['type' => 'payment_status_enum', 'default' => 'pending'],
            'payment_date' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('payment_id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
