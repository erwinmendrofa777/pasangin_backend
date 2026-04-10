<?php
// FILE: backend/app/Models/ConversationModel.php
// VERSI LENGKAP DAN BENAR

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model ini bertanggung jawab untuk semua operasi database
 * yang berkaitan dengan tabel 'conversations'.
 */
class ConversationModel extends Model
{
    // Konfigurasi dasar Model
    protected $table            = 'conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Kolom yang diizinkan untuk diisi melalui metode insert/update
    protected $allowedFields    = [
        'client_id',
        'admin_id',
        'client_type',
        'last_message_preview',
        'last_message_at',
        'unread_by_admin_count',
    ];

    // Mengaktifkan penggunaan timestamp (created_at, updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // Kita set updated_at menjadi 'last_message_at' agar otomatis ter-update
    // saat ada perubahan pada record percakapan.
    protected $updatedField  = 'last_message_at';

    /**
     * Fungsi utama untuk mengambil daftar semua percakapan untuk admin.
     * Fungsi ini menggabungkan data dari tabel 'users' dan 'tukang'
     * untuk mendapatkan nama dan avatar klien, lalu mengurutkannya
     * berdasarkan pesan terbaru.
     *
     * @return array Daftar percakapan.
     */
    public function getConversationsWithClientDetails(): array
    {
        // Mendapatkan koneksi database
        $db = \Config\Database::connect();

        // Query Bagian 1: Mengambil percakapan dengan klien dari tabel 'users'
        $queryClient = $db->table('conversations c')
            ->select('
                c.id, 
                c.client_id, 
                c.client_type, 
                c.last_message_preview, 
                c.last_message_at, 
                u.full_name as client_name, 
                u.avatar as client_avatar, 
                c.unread_by_admin_count
            ')
            ->join('users u', 'u.id = c.client_id', 'left') // LEFT JOIN untuk keamanan jika data user terhapus
            ->where('c.client_type', 'client')
            ->getCompiledSelect(false); // Dapatkan string query tanpa menjalankan

        // Query Bagian 2: Mengambil percakapan dengan klien dari tabel 'tukang'
        $queryTukang = $db->table('conversations c')
            ->select('
                c.id, 
                c.client_id, 
                c.client_type, 
                c.last_message_preview, 
                c.last_message_at, 
                t.name as client_name, 
                "default.png" as client_avatar, 
                c.unread_by_admin_count
            ')
            ->join('tukang t', 't.id = c.client_id', 'left') // LEFT JOIN untuk keamanan jika data tukang terhapus
            ->where('c.client_type', 'tukang')
            ->getCompiledSelect(false); // Dapatkan string query tanpa menjalankan

        // Gabungkan kedua hasil query menggunakan UNION ALL, lalu urutkan hasilnya
        // Ini adalah cara paling efisien untuk menggabungkan dan mengurutkan
        $finalQueryString = "({$queryClient}) UNION ALL ({$queryTukang}) ORDER BY last_message_at DESC";

        // Jalankan query gabungan dan kembalikan hasilnya sebagai array
        return $db->query($finalQueryString)->getResultArray();
    }
}
