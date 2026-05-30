<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionAgreementsModel extends Model{
    protected $table            = 'construction_agreements';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['construction_id', 'agreement_id', 'is_checked'];
    protected $useTimestamps    = false;
}
