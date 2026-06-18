<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionInvoicesModel extends Model
{
    protected $table            = 'construction_invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'voucher_code',
        'construction_id',
        'rab_id',
        'user_id',
        'description',
        'amount',
        'due_date',
        'status',
        'midtrans_order_id',
        'payment_url',
        'snap_token',
        'order_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tabel tidak memiliki kolom updated_at
}
