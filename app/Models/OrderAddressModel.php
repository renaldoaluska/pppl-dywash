<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderAddressModel extends Model
{
    // Nama tabel di database
    protected $table            = 'orders_address';

    // Primary key dari tabel
    protected $primaryKey       = 'order_address_id';

    // Tipe data yang dikembalikan (array agar konsisten)
    protected $returnType       = 'array';

    // Kolom yang diizinkan untuk diisi/diupdate
    protected $allowedFields    = [
        'order_id',
        'label',
        'recipient_name',
        'phone_number',
        'address_detail',
        'latitude',
        'longitude',
    ];

    // Kita tidak menggunakan fitur timestamp (created_at, updated_at) otomatis dari CodeIgniter
    // karena di skema Anda hanya ada created_at.
    protected $useTimestamps = false;
}
