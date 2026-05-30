<?php

namespace App\Modules\Admin\Repositories\Contracts;

/**
 * AdminActivityLogRepositoryInterface
 */
interface AdminActivityLogRepositoryInterface
{
    /**
     * Mengambil semua log dengan data admin.
     */
    public function getLogsWithAdmin(int $limit = null, int $offset = 0): array;

    /**
     * Menghapus log yang lebih lama dari tanggal tertentu.
     */
    public function deleteOlderThan(string $date): bool;

    /**
     * Menyimpan log aktivitas baru.
     */
    public function insert(array $data): bool;
}
