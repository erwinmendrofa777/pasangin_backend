<?php
// FILE: backend/app/Models/RenovationModel.php (KODE LENGKAP & FINAL - DIPERBAIKI)

namespace App\Models;

use CodeIgniter\Model;

class RenovationModel extends Model
{
    protected $table = 'renovation_requests';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'full_name',
        'phone',
        'renovation_type',
        'description',
        'survey_date',
        'address',
        'latitude',
        'longitude',
        'location_photo',
        'voucher_code',
        'survey_cost',
        'discount_amount',
        'total_payment',
        'status',
        'created_at',
        'gambar1',
        'gambar2',
        'gambar3',
        'gambar4',
        'gambar5'
    ];

    /**
     * Menggunakan auto-increment untuk primary key.
     */
    protected $useAutoIncrement = true;

    /**
     * Menentukan tipe data yang dikembalikan (array atau object).
     */
    protected $returnType = 'array';


    // =====================================================================
    // === FUNGSI-FUNGSI CUSTOM ANDA (SUDAH DISESUAIKAN) =================
    // =====================================================================

    /**
     * Mengambil daftar proyek renovasi untuk satu user.
     * Disesuaikan agar tidak mengambil `full_name` dan `phone` dari tabel `renovation_requests`.
     * Fungsi ini sekarang bisa digantikan dengan: $this->where('user_id', $user_id)->findAll();
     * Tapi kita biarkan saja agar tidak merusak kode yang mungkin sudah ada.
     */
    public function get_projects_by_user($user_id)
    {
        return $this->db->table($this->table) // Menggunakan $this->table agar konsisten
            ->select('id, renovation_type, address, status, created_at')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Mengambil detail lengkap satu proyek, join dengan tabel user untuk nama.
     * Disesuaikan agar cocok dengan tabel `renovation_requests` Anda.
     */
    public function get_project_detail($project_id)
    {
        return $this->db->table($this->table . ' as rr') // Menggunakan $this->table agar konsisten
            ->select('rr.id, rr.user_id, rr.address, rr.latitude, rr.longitude, rr.survey_date, rr.renovation_type, rr.description, rr.status, rr.survey_cost, rr.discount_amount, rr.total_payment, rr.voucher_code, rr.created_at, u.full_name, u.phone_number') // Ganti 'u.phone' menjadi 'u.phone_number'
            ->join('users u', 'u.id = rr.user_id', 'left')
            ->where('rr.id', $project_id)
            ->get()->getRowArray();
    }

    /**
     * Fungsi generik untuk mengambil data dari tabel pendukung (surveys, designs, dll).
     * Disesuaikan, 'request_id' diubah menjadi 'renovation_id' agar lebih spesifik jika diperlukan.
     */
    public function get_related_data($table_name, $project_id)
{
    // Seharusnya nama kolomnya 'request_id' sesuai pola Anda
    return $this->db->table($table_name)
        ->where('request_id', $project_id) // <-- BENAR
        ->orderBy('created_at', 'DESC')
        ->get()->getResultArray();
}

    /**
     * Menyimpan pengajuan renovasi baru.
     * Fungsi ini sekarang bisa digantikan dengan: $this->insert($data);
     * Tapi kita biarkan saja untuk konsistensi.
     */
    public function insert_renovation_request($data)
    {
        $this->db->table($this->table)->insert($data); // Menggunakan $this->table agar konsisten
        return $this->db->insertID();
    }
}
