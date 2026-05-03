<?php

namespace App\Services;

use App\Models\TukangModel;
use App\Models\TukangRatingModel;
use RuntimeException;

/**
 * TukangService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Tukang/Mitra.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 */
class TukangService
{
    protected TukangModel       $tukangModel;
    protected TukangRatingModel $ratingModel;

    // Path direktori upload foto tukang
    private const UPLOAD_PATH = 'uploads/tukang/';

    public function __construct()
    {
        $this->tukangModel = new TukangModel();
        $this->ratingModel = new TukangRatingModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua tukang beserta rata-rata skill & behavior score dari rating.
     * Menggunakan COALESCE agar tukang tanpa rating tetap muncul (nilai 0).
     */
    public function getAllTukangWithRating(): array
    {
        return $this->tukangModel
            ->select('tukang.*, COALESCE(ROUND(AVG(tukang_rating.skill_score), 1), 0) as skill_score, COALESCE(ROUND(AVG(tukang_rating.behavior_score), 1), 0) as behavior_score, COALESCE(tukang.rata_rata_rating, 0) as rata_rata_rating')
            ->join('tukang_rating', 'tukang.id = tukang_rating.id_tukang', 'left')
            ->groupBy('tukang.id')
            ->orderBy('tukang.id', 'DESC')
            ->findAll();
    }

    /**
     * Ambil satu tukang beserta riwayat rating-nya.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findTukangWithRatings(int $id): array
    {
        $tukang = $this->tukangModel->find($id);

        if (!$tukang) {
            throw new RuntimeException('Mitra Tukang tidak ditemukan.');
        }

        $tukang['ratings'] = $this->ratingModel
            ->where('id_tukang', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $tukang;
    }

    /**
     * Ambil satu tukang berdasarkan ID.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findTukangOrFail(int $id): array
    {
        $tukang = $this->tukangModel->find($id);

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
     * Logika bisnis yang ditangani:
     * - Upload tiga file foto ke direktori yang tepat
     * - Set nilai default (status, balance, rating, dll.)
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

        $this->tukangModel->save([
            'name'             => $postData['name'],
            'email'            => $postData['email'],
            'phone'            => $postData['phone'],
            'specialization'   => $postData['specialization'],
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
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    /**
     * Update status verifikasi tukang.
     *
     * @throws RuntimeException
     */
    public function updateVerify(int $id, int $isVerify): void
    {
        $this->findTukangOrFail($id);
        $this->tukangModel->update($id, ['is_verify' => $isVerify]);
    }

    /**
     * Update status tukang (Aktif / Nonaktif / Berkas Diproses / dll.).
     *
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        $this->findTukangOrFail($id);
        $this->tukangModel->update($id, ['status' => $status]);
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus tukang beserta ketiga file foto-nya.
     *
     * Logika bisnis yang ditangani:
     * - Pastikan tukang ada sebelum menghapus
     * - Hapus file fisik profile_photo, ktp_photo, selfie_photo
     *
     * @throws RuntimeException
     */
    public function deleteTukang(int $id): void
    {
        $tukang = $this->findTukangOrFail($id);

        $this->deletePhotoFile($tukang['profile_photo'] ?? null);
        $this->deletePhotoFile($tukang['ktp_photo'] ?? null);
        $this->deletePhotoFile($tukang['selfie_photo'] ?? null);

        $this->tukangModel->delete($id);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Upload satu file foto ke direktori tukang.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @return string Nama file hasil upload
     * @throws RuntimeException
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
