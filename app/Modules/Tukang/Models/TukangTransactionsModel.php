<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangTransactionsModel extends Model
{
    protected $table = 'tukang_transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'tukang_id',
        'group_transaction_id',
        'amount',
        'type',
        'description'
    ];
    protected $useTimestamps = false;
}