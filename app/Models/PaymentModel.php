<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    /**
     * Nama tabel di database
     * @var string
     */
    protected $table            = 'payments';

    /**
     * Primary key dari tabel
     * @var string
     */
    protected $primaryKey       = 'payment_id';

    /**
     * Kolom-kolom yang diizinkan untuk diisi
     * (Mass Assignment)
     * @var array
     */
    protected $allowedFields    = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'payment_date'
    ];

    /**
     * Menonaktifkan penggunaan kolom 'created_at' dan 'updated_at' secara otomatis
     * karena tidak ada di tabel 'payments'.
     * @var bool
     */
    protected $useTimestamps    = false;
}
