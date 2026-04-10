<?php

namespace App\Models;

use CodeIgniter\Model;

class RenovationRabsModel extends Model
{
    // Nama tabel sesuai dengan struktur database terbaru kawan
    protected $table            = 'renovation_rabs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    /**
     * allowedFields harus mencakup semua kolom yang kita gunakan di Controller
     * Termasuk kolom is_locked untuk fitur kunci RAB kawan
     */
    protected $allowedFields    = [
        'renovation_id', 
        'roman_number', 
        'group_name', 
        'sub_group_name',
        'section_group', 
        'section_name', 
        'activity_name', 
        'volume', 
        'unit', 
        'selected_material_id',
        'current_unit_price',
        'total_price',
        'is_locked' // Tambahkan ini agar fitur Lock RAB berfungsi kawan
    ];

    // Aktifkan timestamps sesuai kolom yang kita tambahkan di SQL tadi
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fungsi helper untuk mengambil data RAB per proyek
     * Diurutkan berdasarkan Roman dan ID agar sinkron dengan Flutter kawan
     */
    public function getRabWithMaterials($renovation_id)
    {
        return $this->where('renovation_id', $renovation_id)
                    ->orderBy('roman_number', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Fungsi opsional untuk mengecek status lock sebuah baris kawan
     */
    public function isLocked($id)
    {
        $row = $this->select('is_locked')->find($id);
        return $row ? (bool)$row['is_locked'] : false;
    }
}