<?php
 
namespace App\Modules\Tukang\Models;
 
use CodeIgniter\Model;
 
class GroupTransactionsModel extends Model
{
    protected $table            = 'group_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
 
    protected $allowedFields = [
        'group_id',
        'amount',
        'type',
        'status',
        'source_project_type',
        'source_invoice_id',
        'description',
        'distributions_data',
        'created_at'
    ];
 
    // Timestamps handled manually in code
    protected $useTimestamps = false;
}
