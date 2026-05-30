<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class SupplierBannerModel extends Model
{
    protected $table = 'supplier_banner';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_supplier',
        'title',
        'image',
        'start_date',
        'end_date',
        'note',
        'status'
    ];
    protected $useTimestamps = true;
}
