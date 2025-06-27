<?php // app/Models/ReviewModel.php
// Model ini berinteraksi dengan tabel 'reviews'.

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'review_id';
    protected $allowedFields    = [
        'order_id', 
        'customer_id', 
        'outlet_id', 
        'rating', 
        'comment', 
        'review_date'
    ];

    // Tabel ini tidak memiliki kolom timestamps (created_at/updated_at)
    protected $useTimestamps    = false; 
}
