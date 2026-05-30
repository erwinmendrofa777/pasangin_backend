<?php

namespace App\Modules\Users\Repositories;

use App\Modules\Users\Models\UserModel;
use App\Modules\Users\Repositories\Contracts\UserRepositoryInterface;

/**
 * UserRepository
 *
 * Implementasi konkrit dari UserRepositoryInterface menggunakan CodeIgniter 4 Model.
 * Ini adalah "penjaga gerbang" database untuk entitas User.
 *
 * TANGGUNG JAWAB KELAS INI:
 *   - Menyimpan semua query SQL yang berkaitan dengan tabel 'users'
 *   - Menjadi satu-satunya tempat yang "tahu" cara berkomunikasi dengan database User
 *
 * BUKAN TANGGUNG JAWAB KELAS INI:
 *   - Logika bisnis (itu ada di UserService)
 *   - Validasi input (itu ada di Controller / Validation)
 *   - Mengurus file/upload (itu ada di UserService)
 */
class UserRepository implements UserRepositoryInterface
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    // =========================================================================
    // READ OPERATIONS
    // =========================================================================

    /**
     * Ambil semua user dengan role 'client', diurutkan dari ID terbesar (terbaru).
     *
     * Query ini bisa dipakai di halaman Admin, laporan PDF, export Excel, dsb.
     * Cukup tulis sekali di sini.
     */
    public function findAllClients(): array
    {
        return $this->model
            ->where('role', 'client')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function countClients(): int
    {
        return $this->model->where('role', 'client')->countAllResults();
    }

    /**
     * Ambil satu user berdasarkan ID.
     * Mengembalikan null (bukan exception) — keputusan "apa yang dilakukan jika null"
     * adalah urusan Service, bukan Repository.
     */
    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findWithFcmToken(): array
    {
        return $this->model->db->table('user_fcm_tokens')
            ->select('user_id as id, fcm_token')
            ->where('user_type', 'client')
            ->get()
            ->getResultArray();
    }

    public function searchForDropdown(string $term): array
    {
        $builder = $this->model->builder();
        $builder->select('id, full_name as name, phone_number as phone')
            ->where('role', 'client');

        if (!empty($term)) {
            $builder->groupStart()
                ->like('full_name', $term)
                ->orLike('phone_number', $term)
                ->groupEnd();
        }

        $query = $builder->limit(20)->get()->getResultArray();

        $results = [];
        foreach ($query as $row) {
            $results[] = ['id' => $row['id'], 'text' => $row['name'] . ' (' . $row['phone'] . ')'];
        }

        return $results;
    }

    // =========================================================================
    // WRITE OPERATIONS
    // =========================================================================

    /**
     * Simpan data user.
     * Jika array berisi 'id', maka ini adalah UPDATE.
     * Jika tidak ada 'id', maka ini adalah INSERT.
     */
    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }

    /**
     * Hapus user berdasarkan ID.
     * Mengembalikan true jika berhasil, false jika gagal.
     */
    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    // =========================================================================
    // UTILITY
    // =========================================================================

    /**
     * Ambil error validasi dari operasi save() terakhir.
     * Diteruskan dari Model agar Service bisa membaca error dengan tepat.
     */
    public function errors(): array
    {
        return $this->model->errors();
    }
}
