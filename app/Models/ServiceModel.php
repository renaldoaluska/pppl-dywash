<?php // app/Models/ServiceModel.php
// Model ini berinteraksi dengan tabel 'services'.

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'service_id';
    protected $allowedFields    = [
        'outlet_id', 
        'name', 
        'price', 
        'unit'
    ];

    // Tabel ini tidak memiliki kolom timestamps
    protected $useTimestamps    = false;
}