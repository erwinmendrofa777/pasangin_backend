<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table      = 'vouchers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name', 'description', 'discount_nominal', 'valid_until', 'image', 'is_active'];
    protected $useTimestamps = true;
}