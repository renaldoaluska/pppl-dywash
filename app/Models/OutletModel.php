<?php // app/Models/OutletModel.php
// Model ini berinteraksi dengan tabel 'outlets'.

namespace App\Models;

use CodeIgniter\Model;

class OutletModel extends Model
{
    protected $table            = 'outlets';
    protected $primaryKey       = 'outlet_id';
    protected $allowedFields    = [
        'owner_id', 
        'name', 
        'address', 
        'contact_phone', 
        'operating_hours', 
        'status'
    ];

    // Mengaktifkan penggunaan created_at dan updated_at secara otomatis
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}