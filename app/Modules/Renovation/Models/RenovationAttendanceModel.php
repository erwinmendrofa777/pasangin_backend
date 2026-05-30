<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationAttendanceModel extends Model
{
    protected $table = 'renovation_attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_renovation', 'type', 'file', 'jumlah_tukang', 'longitude', 'latitude', 'waktu', 'deskripsi'];
    protected $useTimestamps = false;
}
