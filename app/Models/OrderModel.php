<?php // app/Models/OrderModel.php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'order_id';
    protected $returnType       = 'array'; // Bisa juga 'object'
    protected $allowedFields    = [
        'customer_id', 
        'outlet_id', 
        'order_date', 
        'total_amount', 
        'status', 
        'customer_notes',
        'orders_address_id' // ✅ tambahkan ini
    ];
    
    // Mengaktifkan penggunaan created_at dan updated_at secara otomatis
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
