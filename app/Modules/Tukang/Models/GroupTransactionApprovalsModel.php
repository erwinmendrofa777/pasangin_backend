<?php
 
namespace App\Modules\Tukang\Models;
 
use CodeIgniter\Model;
 
class GroupTransactionApprovalsModel extends Model
{
    protected $table            = 'group_transaction_approvals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
 
    protected $allowedFields = [
        'group_transaction_id',
        'tukang_id',
        'vote',
        'created_at'
    ];
 
    protected $useTimestamps = false;
}
