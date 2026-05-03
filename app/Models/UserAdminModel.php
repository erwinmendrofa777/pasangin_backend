<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAdminModel extends Model
{
    protected $table            = 'user_admin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['full_name', 'email', 'password', 'role', 'phone_number', 'photo'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
