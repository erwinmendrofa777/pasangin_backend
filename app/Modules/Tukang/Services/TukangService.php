<?php

namespace App\Modules\Tukang\Services;

use App\Modules\Tukang\Repositories\TukangRepository;
use App\Modules\Tukang\Repositories\TukangRatingRepository;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;
use App\Modules\Tukang\Repositories\Contracts\TukangRatingRepositoryInterface;
use RuntimeException;

/**
 * TukangService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Tukang/Mitra.
 * Sekarang menggunakan Repository Pattern untuk memisahkan logika bisnis dari akses data.
 */
class TukangService
{
    protected TukangRepositoryInterface       $tukangRepository;
    protected TukangRatingRepositoryInterface $ratingRepository;

    // Path direktori upload foto tukang
    private const UPLOAD_PATH = 'uploads/tukang/';

    public function __construct()
    {
        $this->tukangRepository = new TukangRepository();
        $this->ratingRepository = new TukangRatingRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua tukang beserta rata-rata skill & behavior score dari rating.
     */
    public function getAllTukangWithRating(): array
    {
        return $this->tukangRepository->findAllWithRatings();
    }

    /**
     * Ambil satu tukang beserta riwayat rating-nya.
     * @throws RuntimeException
     */
    public function findTukangWithRatings(int $id): array
    {
        $tukang = $this->tukangRepository->findById($id);

        if (!$tukang) {
            throw new \RuntimeException('Mitra Tukang tidak ditemukan.');
        }

        $tukang['ratings'] = $this->ratingRepository->findByTukangId($id);

        // Ambil data keahlian dari tabel junction
        $skillMapModel = new \App\Modules\Tukang\Models\TukangSkillMapModel();
        $tukang['skills'] = $skillMapModel->getSkillsByTukangId($id);

        return $tukang;
    }

    /**
     * Ambil satu tukang berdasarkan ID atau lempar exception.
     * @throws RuntimeException
     */
    public function findTukangOrFail(int $id): array
    {
        $tukang = $this->tukangRepository->findById($id);

        if (!$tukang) {
            throw new RuntimeException('Mitra Tukang tidak ditemukan.');
        }

        return $tukang;
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    /**
     * Daftarkan tukang baru beserta upload tiga foto (profile, KTP, selfie).
     *
     * @param array $postData Data POST dari request
     * @param array $files    Array file: ['profile_photo' => ..., 'ktp_photo' => ..., 'selfie_photo' => ...]
     * @throws RuntimeException
     */
    public function createTukang(array $postData, array $files): void
    {
        $profileName = $this->uploadPhoto($files['profile_photo']);
        $ktpName     = $this->uploadPhoto($files['ktp_photo']);
        $selfieName  = $this->uploadPhoto($files['selfie_photo']);

        $db = \Config\Database::connect();
        $db->transStart();

        $this->tukangRepository->save([
            'name'             => $postData['name'],
            'email'            => $postData['email'],
            'phone'            => $postData['phone'],
            'nik'              => $postData['nik'],
            'gender'           => $postData['gender'],
            'dob'              => $postData['dob'],
            'ktp_address'      => $postData['ktp_address'],
            'domicile_address' => $postData['domicile_address'],
            'profile_photo'    => $profileName,
            'ktp_photo'        => $ktpName,
            'selfie_photo'     => $selfieName,
            // Nilai default domain bisnis
            'status'           => 'Berkas Diproses',
            'is_verify'        => 0,
            'balance'          => 0,
            'rata_rata_rating' => 0,
            'total_ulasan'     => 0,
        ]);

        $tukangId = $this->tukangRepository->getInsertID();
        if ($tukangId) {
            $skills = $postData['skills'] ?? [];
            $skillMapModel = new \App\Modules\Tukang\Models\TukangSkillMapModel();
            $skillMapModel->syncSkills($tukangId, $skills);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Gagal mendaftarkan mitra tukang.');
        }
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    /**
     * Update status verifikasi tukang.
     */
    public function updateVerify(int $id, int $isVerify): void
    {
        $this->findTukangOrFail($id);
        $this->tukangRepository->update($id, ['is_verify' => $isVerify]);
    }

    /**
     * Update status tukang (Aktif / Nonaktif / Berkas Diproses / dll.).
     */
    public function updateStatus(int $id, string $status): void
    {
        $this->findTukangOrFail($id);
        $this->tukangRepository->update($id, ['status' => $status]);
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus tukang beserta ketiga file foto-nya secara fisik.
     * @throws RuntimeException
     */
    public function deleteTukang(int $id): void
    {
        $tukang = $this->findTukangOrFail($id);

        $this->deletePhotoFile($tukang['profile_photo'] ?? null);
        $this->deletePhotoFile($tukang['ktp_photo'] ?? null);
        $this->deletePhotoFile($tukang['selfie_photo'] ?? null);

        $this->tukangRepository->delete($id);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Upload satu file foto ke direktori tukang.
     */
    private function uploadPhoto($file): string
    {
        if (!$file || !$file->isValid()) {
            throw new RuntimeException('File foto tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . self::UPLOAD_PATH, $newName);

        return $newName;
    }

    /**
     * Hapus file foto dari filesystem secara aman.
     */
    private function deletePhotoFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $filePath = FCPATH . self::UPLOAD_PATH . $filename;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
