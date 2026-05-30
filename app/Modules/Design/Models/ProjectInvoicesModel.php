<?php

namespace App\Modules\Design\Models;

use CodeIgniter\Model;

class ProjectInvoicesModel extends Model
{
    protected $table = 'project_invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'voucher_code',
        'midtrans_order_id',
        'payment_status',
        'design_request_id',
        'description',
        'amount',
        'due_date',
        'status',
        'snap_token',
        'payment_url',
        'payment_type',
        'proof_file'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';  // tabel tidak memiliki kolom updated_at

}