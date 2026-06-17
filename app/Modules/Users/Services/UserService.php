<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Repositories\Contracts\UserRepositoryInterface;
use RuntimeException;

/**
 * UserService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen User.
 * Service ini TIDAK tahu cara query database — itu urusan UserRepository.
 *
 * LAPISAN ARSITEKTUR:
 *   Controller → UserService (logika bisnis) → UserRepository (query DB) → UserModel (struktur tabel)
 *
 * TANGGUNG JAWAB:
 *   - Aturan bisnis: siapa yang boleh dihapus, status apa saja yang valid, dll.
 *   - Koordinasi antar operasi: cek user ada → hapus file → hapus record
 *   - Sanitasi data sebelum disimpan
 *
 * BUKAN TANGGUNG JAWAB:
 *   - Query SQL (ada di UserRepository)
 *   - Validasi request HTTP (ada di Controller)
 */
class UserService
{
    protected UserRepositoryInterface $userRepository;

    // Daftar status yang sah — satu tempat, mudah diubah
    private const ALLOWED_STATUSES = ['approved', 'rejected', 'banned', 'pending'];

    // Path direktori upload avatar
    private const UPLOAD_PATH = 'uploads/profile/';

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua user dengan role 'client'.
     * Service hanya meneruskan perintah ke Repository.
     * Jika di masa depan butuh filter tambahan, cukup tambah method di Repository.
     */
    public function getAllClients(): array
    {
        return $this->userRepository->findAllClients();
    }

    /**
     * Ambil semua client dengan hitungan pesanan & proyek.
     */
    public function getAllClientsWithCounts(): array
    {
        return $this->userRepository->findAllClientsWithCounts();
    }

    /**
     * Ambil satu user berdasarkan ID.
     * Melempar RuntimeException jika tidak ditemukan, sehingga Controller
     * tidak perlu menulis logika pengecekan sendiri.
     *
     * @throws RuntimeException
     */
    public function findUserOrFail(int $id): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new RuntimeException('User tidak ditemukan.');
        }

        return $user;
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus user beserta file avatar-nya.
     *
     * Logika bisnis yang ditangani:
     * - Mencegah user menghapus akun sendiri
     * - Melindungi akun Super Admin (ID 1)
     * - Menghapus file fisik avatar sebelum menghapus record
     *
     * @throws RuntimeException Jika aturan bisnis dilanggar atau terjadi error
     */
    public function deleteUser(int $id, int $currentUserId): void
    {
        // Aturan bisnis: tidak boleh hapus diri sendiri
        if ($id === $currentUserId) {
            throw new RuntimeException('Anda tidak bisa menghapus akun sendiri.');
        }

        // Aturan bisnis: proteksi Super Admin
        if ($id === 1) {
            throw new RuntimeException('Akun Super Admin tidak boleh dihapus.');
        }

        $user = $this->findUserOrFail($id);

        // Hapus file fisik avatar jika ada
        $this->deleteAvatarFile($user['avatar'] ?? null, self::UPLOAD_PATH);

        $this->userRepository->delete($id);
    }

    // =========================================================================
    // UPDATE STATUS
    // =========================================================================

    /**
     * Ubah status user (approved, rejected, banned, pending).
     *
     * Logika bisnis yang ditangani:
     * - Validasi bahwa status yang dikirim adalah nilai yang sah
     *
     * @throws RuntimeException Jika status tidak valid
     */
    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new RuntimeException('Status tidak valid: ' . $status);
        }

        // Pastikan user ada sebelum mengupdate
        $this->findUserOrFail($id);

        if (!$this->userRepository->save(['id' => $id, 'status' => $status])) {
            $errors = implode(' ', $this->userRepository->errors());
            throw new RuntimeException('Gagal mengubah status: ' . $errors);
        }
    }

    // =========================================================================
    // UPDATE PROFIL
    // =========================================================================

    /**
     * Perbarui data profil user, termasuk handle upload avatar.
     *
     * Logika bisnis yang ditangani:
     * - Sanitasi data input (mencegah mass assignment)
     * - Proses upload avatar baru & hapus avatar lama
     * - Simpan data ke database melalui Repository
     *
     * @param int                                       $id
     * @param array                                     $postData   Data POST dari request
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $avatarFile File avatar dari request
     * @throws RuntimeException
     */
    public function updateUser(int $id, array $postData, $avatarFile = null): void
    {
        $user = $this->findUserOrFail($id);

        // Sanitasi data — hanya ambil field yang diizinkan (mencegah mass assignment)
        $data = [
            'id'           => $id,
            'full_name'    => esc($postData['full_name'] ?? ''),
            'email'        => filter_var($postData['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'phone_number' => esc($postData['phone_number'] ?? ''),
            'nik'          => ($postData['nik'] ?? '') === '' ? null : esc($postData['nik']),
            'gender'       => $postData['gender'] ?? '',
            'birth_date'   => $postData['birth_date'] ?? '',
            'address'      => esc($postData['address'] ?? ''),
        ];

        // Handle upload avatar baru jika ada
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $newName = $avatarFile->getRandomName();
            $avatarFile->move(FCPATH . self::UPLOAD_PATH, $newName);
            $data['avatar'] = $newName;

            // Hapus avatar lama (kecuali URL eksternal atau default)
            $this->deleteAvatarFile($user['avatar'] ?? null, self::UPLOAD_PATH);
        }

        if (!$this->userRepository->save($data)) {
            $errors = implode(' ', $this->userRepository->errors());
            throw new RuntimeException('Gagal memperbarui data user: ' . $errors);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Hapus file avatar dari filesystem secara aman.
     * Tidak melakukan apa-apa jika file tidak ada, URL eksternal, atau default.
     */
    private function deleteAvatarFile(?string $filename, string $uploadPath): void
    {
        if (empty($filename)) {
            return;
        }

        // Jangan hapus avatar URL eksternal (misal dari OAuth)
        if (str_starts_with($filename, 'http')) {
            return;
        }

        // Jangan hapus avatar default
        if ($filename === 'default.jpg') {
            return;
        }

        $filePath = FCPATH . $uploadPath . $filename;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
