<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'address_id';
    protected $allowedFields = [
        'user_id',
        'label',
        'recipient_name',
        'phone_number',
        'address_detail',
        'latitude',
        'longitude',
        'is_primary',
    ];
    protected $useTimestamps = false;
}
