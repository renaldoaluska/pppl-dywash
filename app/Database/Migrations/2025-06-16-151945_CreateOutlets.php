<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOutlets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'outlet_id' => ['type' => 'SERIAL'],
            'owner_id' => ['type' => 'INT'],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'address' => ['type' => 'TEXT'],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'operating_hours' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'outlet_status', 'default' => 'pending'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('outlet_id', true);
        $this->forge->addForeignKey('owner_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('outlets');
    }

    public function down()
    {
        $this->forge->dropTable('outlets');
    }
}
