<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class SupplierReferralModel extends Model
{
    protected $table            = 'supplier_referral_codes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['supplier_id', 'code', 'expires_at', 'is_used'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
