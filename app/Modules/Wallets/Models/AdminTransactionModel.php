<?php

namespace App\Modules\Wallets\Models;

use CodeIgniter\Model;

class AdminTransactionModel extends Model
{
    protected $table            = 'admin_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'amount',
        'type',
        'source',
        'reference_id',
        'description',
        'created_at'
    ];

    protected $useTimestamps = false;
}
