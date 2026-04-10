<?php

namespace App\Models;

use CodeIgniter\Model;

class TermsOfAgreementModel extends Model{
    protected $table            = 'terms_of_agreement';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title', 'description'];
    protected $useTimestamps    = false;
}
