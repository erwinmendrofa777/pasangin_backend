<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangSkillMapModel extends Model
{
    protected $table      = 'tukang_skill_map';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = ['tukang_id', 'tukang_skill_id'];

    /**
     * Mengambil daftar keahlian berdasarkan ID Tukang
     */
    public function getSkillsByTukangId(int $tukangId): array
    {
        return $this->db->table($this->table)
            ->select('tukang_skill.id, tukang_skill.skill_name')
            ->join('tukang_skill', 'tukang_skill.id = tukang_skill_map.tukang_skill_id')
            ->where('tukang_skill_map.tukang_id', $tukangId)
            ->get()
            ->getResultArray();
    }

    /**
     * Menyinkronkan daftar keahlian untuk Tukang
     *
     * @param int $tukangId ID Tukang
     * @param array|string $skillsInput Array ID keahlian, array nama keahlian, atau string pisahan koma/garis miring
     */
    public function syncSkills(int $tukangId, $skillsInput): void
    {
        if (empty($skillsInput)) {
            // Hapus semua relasi keahlian untuk tukang ini
            $this->where('tukang_id', $tukangId)->delete();
            return;
        }

        $skillIds = [];
        $skillModel = new TukangSkillModel();

        if (is_array($skillsInput)) {
            // Tentukan apakah input berupa array ID numerik atau array string nama keahlian
            $isNumericArray = true;
            foreach ($skillsInput as $item) {
                if (!is_numeric($item)) {
                    $isNumericArray = false;
                    break;
                }
            }

            if ($isNumericArray) {
                $skillIds = array_map('intval', $skillsInput);
            } else {
                foreach ($skillsInput as $name) {
                    $name = trim($name);
                    if ($name === '') continue;

                    $existing = $skillModel->where('skill_name', $name)->first();
                    if ($existing) {
                        $skillIds[] = (int) $existing['id'];
                    } else {
                        // Daftarkan ke master jika belum ada
                        $skillModel->insert(['skill_name' => $name]);
                        $skillIds[] = (int) $skillModel->getInsertID();
                    }
                }
            }
        } elseif (is_string($skillsInput)) {
            // Pemisahan teks lama (koma atau garis miring)
            $names = preg_split('/[,|\/]/', $skillsInput);
            foreach ($names as $name) {
                $name = trim($name);
                if ($name === '') continue;

                $existing = $skillModel->where('skill_name', $name)->first();
                if ($existing) {
                    $skillIds[] = (int) $existing['id'];
                } else {
                    // Daftarkan ke master jika belum ada
                    $skillModel->insert(['skill_name' => $name]);
                    $skillIds[] = (int) $skillModel->getInsertID();
                }
            }
        }

        // Jalankan transaksi database
        $this->db->transStart();

        // 1. Hapus relasi yang lama
        $this->where('tukang_id', $tukangId)->delete();

        // 2. Masukkan relasi yang baru
        $skillIds = array_unique($skillIds);
        foreach ($skillIds as $skillId) {
            $this->insert([
                'tukang_id'       => $tukangId,
                'tukang_skill_id' => $skillId,
            ]);
        }

        $this->db->transComplete();
    }
}
