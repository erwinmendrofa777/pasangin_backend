<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionAttendanceModel extends Model
{
    protected $table = 'construction_attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_construction', 'type', 'file', 'jumlah_tukang', 'longitude', 'latitude', 'waktu', 'deskripsi'];
    protected $useTimestamps = false;
}
