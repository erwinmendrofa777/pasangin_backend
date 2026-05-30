<?php

namespace App\Modules\Banners\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{protected $table            = 'banners';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title', 'image', 'target_app', 'is_active'];
    protected $useTimestamps    = true;
}
