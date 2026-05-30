<?php

namespace App\Modules\AboutApplication\Models;

use CodeIgniter\Model;

class AboutApplicationPasanginModel extends Model
{
    protected $table = 'about_application_pasangin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['description'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
