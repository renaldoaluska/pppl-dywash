<?php // app/Models/OrderItemModel.php
// Model ini berinteraksi dengan tabel 'order_items'.

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'item_id';
    protected $allowedFields    = [
        'order_id', 
        'service_id', 
        'quantity', 
        'subtotal'
    ];

    // Tabel ini tidak memiliki kolom timestamps
    protected $useTimestamps    = false;
}
