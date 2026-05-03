<?php

namespace App\Models;

use CodeIgniter\Model;

class TukangTransactionsModel extends Model
{
    protected $table = 'tukang_transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'tukang_id',
        'amount',
        'type',
        'description'
    ];
    protected $useTimestamps = true;
}