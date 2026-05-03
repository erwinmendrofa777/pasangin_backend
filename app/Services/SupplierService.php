<?php

namespace App\Services;

use App\Models\SupplierModel;
use RuntimeException;

/**
 * SupplierService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Supplier.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 * Operasi database murni tetap dikelola oleh SupplierModel.
 */
class SupplierService
{
    protected SupplierModel $supplierModel;

    // Daftar status yang sah
    private const ALLOWED_STATUSES = ['approved', 'rejected', 'banned', 'pending'];

    // Path direktori upload logo
    private const UPLOAD_PATH = 'uploads/supplier/';

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua supplier, diurutkan berdasarkan nama A-Z.
     */
    public function getAllSuppliers(): array
    {
        return $this->supplierModel
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Ambil satu supplier berdasarkan ID.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findSupplierOrFail(int $id): array
    {
        $supplier = $this->supplierModel->find($id);

        if (!$supplier) {
            throw new RuntimeException('Supplier tidak ditemukan.');
        }

        return $supplier;
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    /**
     * Simpan supplier baru ke database.
     *
     * Logika bisnis yang ditangani:
     * - Hash password sebelum disimpan
     * - Upload logo jika ada
     * - Wrap dalam database transaction
     *
     * @param array                                     $postData  Data POST dari request
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $logoFile  File logo dari request
     * @throws RuntimeException
     */
    public function createSupplier(array $postData, $logoFile = null): void
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $data = [
                'name'           => $postData['name'],
                'email'          => $postData['email'],
                'password'       => password_hash($postData['password'], PASSWORD_DEFAULT),
                'contact_person' => $postData['contact_person'],
                'phone'          => $postData['phone'],
                'address'        => $postData['address'],
                'district'       => $postData['district'],
                'city'           => $postData['city'],
                'province'       => $postData['province'],
                'is_active'      => isset($postData['is_active']) ? $postData['is_active'] : 1,
                'status'         => 'approved',
            ];

            // Upload logo jika ada
            if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
                $newName = $logoFile->getRandomName();
                $logoFile->move(FCPATH . self::UPLOAD_PATH, $newName);
                $data['logo_url'] = $newName;
            }

            if (!$this->supplierModel->insert($data)) {
                throw new RuntimeException('Gagal menyimpan data supplier ke database.');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new RuntimeException('Gagal menyimpan ke database.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            throw new RuntimeException($e->getMessage());
        }
    }

    // =========================================================================
    // UPDATE PROFIL
    // =========================================================================

    /**
     * Perbarui data supplier, termasuk handle upload logo.
     *
     * Logika bisnis yang ditangani:
     * - Hash password baru jika diisi
     * - Upload logo baru & hapus logo lama
     * - Wrap dalam database transaction
     *
     * @param int                                       $id
     * @param array                                     $postData  Data POST dari request
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $logoFile  File logo dari request
     * @throws RuntimeException
     */
    public function updateSupplier(int $id, array $postData, $logoFile = null): void
    {
        // Pastikan supplier ada
        $supplier = $this->findSupplierOrFail($id);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Field wajib
            $data = [
                'name'           => $postData['name'],
                'contact_person' => $postData['contact_person'],
                'phone'          => $postData['phone'],
                'address'        => $postData['address'],
                'is_active'      => isset($postData['is_active']) ? $postData['is_active'] : 1,
            ];

            // Field opsional — hanya update jika ada nilainya
            if (!empty($postData['email'])) {
                $data['email'] = $postData['email'];
            }
            if (!empty($postData['password'])) {
                $data['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
            }
            if (!empty($postData['district'])) {
                $data['district'] = $postData['district'];
            }
            if (!empty($postData['city'])) {
                $data['city'] = $postData['city'];
            }
            if (!empty($postData['province'])) {
                $data['province'] = $postData['province'];
            }

            // Handle upload logo baru
            if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
                $newName = $logoFile->getRandomName();
                $logoFile->move(FCPATH . self::UPLOAD_PATH, $newName);
                $data['logo_url'] = $newName;

                // Hapus logo lama
                $this->deleteLogoFile($supplier['logo_url'] ?? null);
            }

            if (!$this->supplierModel->update($id, $data)) {
                throw new RuntimeException('Gagal memperbarui data supplier di database.');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new RuntimeException('Gagal mengubah data supplier dalam database.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            throw new RuntimeException($e->getMessage());
        }
    }

    // =========================================================================
    // UPDATE STATUS
    // =========================================================================

    /**
     * Ubah status supplier dan sinkronkan field is_active secara otomatis.
     *
     * Logika bisnis yang ditangani:
     * - Validasi status yang diizinkan
     * - Status 'approved' → is_active = 1; status lain → is_active = 0
     *
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new RuntimeException('Status tidak valid: ' . $status);
        }

        // Pastikan supplier ada
        $this->findSupplierOrFail($id);

        if (!$this->supplierModel->save([
            'id'        => $id,
            'status'    => $status,
            'is_active' => $status === 'approved' ? 1 : 0,
        ])) {
            throw new RuntimeException('Gagal mengubah status supplier di database.');
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus supplier beserta file logo-nya.
     *
     * Logika bisnis yang ditangani:
     * - Pastikan supplier ada sebelum menghapus
     * - Hapus file fisik logo
     * - Wrap dalam database transaction
     *
     * @throws RuntimeException
     */
    public function deleteSupplier(int $id): void
    {
        $supplier = $this->findSupplierOrFail($id);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus file fisik logo
            $this->deleteLogoFile($supplier['logo_url'] ?? null);

            $this->supplierModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new RuntimeException('Gagal menghapus data supplier.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            throw new RuntimeException($e->getMessage());
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Hapus file logo dari filesystem secara aman.
     * Tidak melakukan apa-apa jika file tidak ada atau merupakan URL eksternal.
     */
    private function deleteLogoFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        // Jangan hapus URL eksternal
        if (str_starts_with($filename, 'http')) {
            return;
        }

        $filePath = FCPATH . self::UPLOAD_PATH . $filename;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
