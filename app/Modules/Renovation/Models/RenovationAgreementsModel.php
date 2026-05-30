<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationAgreementsModel extends Model{
    protected $table            = 'renovation_agreements';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['renovation_id', 'agreement_id', 'is_checked'];
    protected $useTimestamps    = false;
}
