<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateReviews extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'review_id' => ['type' => 'SERIAL'],
            'order_id' => ['type' => 'INT'],
            'customer_id' => ['type' => 'INT'],
            'outlet_id' => ['type' => 'INT'],
            'rating' => ['type' => 'INT', 'constraint' => 1],
            'comment' => ['type' => 'TEXT', 'null' => true],
            'review_date' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('review_id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('customer_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('outlet_id', 'outlets', 'outlet_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
