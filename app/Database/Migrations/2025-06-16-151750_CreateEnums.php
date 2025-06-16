<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnums extends Migration
{
    public function up()
    {
        // ENUM PostgreSQL (gunakan raw SQL)
        $this->db->query("CREATE TYPE user_role AS ENUM ('admin', 'outlet', 'cust')");
        $this->db->query("CREATE TYPE outlet_status AS ENUM ('pending', 'verified', 'rejected')");
        $this->db->query("CREATE TYPE order_status_enum AS ENUM ('diterima', 'ditolak', 'diproses', 'selesai', 'diulas')");
        $this->db->query("CREATE TYPE payment_method_enum AS ENUM ('transfer', 'cod', 'ewallet')");
        $this->db->query("CREATE TYPE payment_status_enum AS ENUM ('pending', 'lunas', 'gagal')");
    }

    public function down()
    {
        $this->db->query("DROP TYPE IF EXISTS payment_status_enum CASCADE");
        $this->db->query("DROP TYPE IF EXISTS payment_method_enum CASCADE");
        $this->db->query("DROP TYPE IF EXISTS order_status_enum CASCADE");
        $this->db->query("DROP TYPE IF EXISTS outlet_status CASCADE");
        $this->db->query("DROP TYPE IF EXISTS user_role CASCADE");
    }
}
