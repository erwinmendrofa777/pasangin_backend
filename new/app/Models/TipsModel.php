<?php

namespace App\Models;

use CodeIgniter\Model;

class TipsModel extends Model{
    protected $table            = 'tips';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title', 'image', 'content', 'target_app', 'is_active'];
    protected $useTimestamps    = true;
}
