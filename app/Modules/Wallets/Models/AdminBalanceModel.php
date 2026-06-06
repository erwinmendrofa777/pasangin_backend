<?php

namespace App\Modules\Wallets\Models;

use CodeIgniter\Model;

class AdminBalanceModel extends Model
{
    protected $table            = 'admin_balance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['balance'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
