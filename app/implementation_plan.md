# Rencana Implementasi Pencatatan Aktivitas Admin (Activity Log)

Tujuan dari rencana ini adalah untuk membangun sistem pelacakan (audit trail) komprehensif yang merekam setiap aksi yang dilakukan oleh admin (seperti login, logout, tambah data, edit data, hapus data, dan ubah status) beserta detail waktunya. Ini penting untuk keamanan dan transparansi operasional sistem.

> [!IMPORTANT]
> Sistem ini akan mencatat aktivitas secara terpusat, sehingga siapa pun yang memiliki akses ke log dapat melihat siapa yang mengubah suatu data dan kapan perubahan tersebut terjadi.

## Keputusan Desain (Berdasarkan Review)

1. **Retensi Data:** Log akan dihapus secara otomatis jika sudah lebih dari **3 bulan (90 hari)**. Saya akan menambahkan logika pembersihan (cleanup) di Controller Activity Log atau melalui command spark.
2. **Cakupan Log:** Mencatat **IP Address** dan **User-Agent** (aktif) untuk kebutuhan audit keamanan.
3. **Menu Akses:** Menu log aktivitas hanya dapat diakses oleh role yang memiliki permission `activity_log_view` (biasanya Superadmin).

## Proposed Changes

---

### 1. Struktur Database

Pembuatan tabel baru untuk menampung data log aktivitas.

#### [NEW] Migration: CreateAdminActivityLogsTable

Tabel `admin_activity_logs` dengan struktur:

- `id` (INT, Primary Key)
- `admin_id` (INT, Foreign Key ke tabel user_admin)
- `action` (VARCHAR, contoh: 'login', 'logout', 'create', 'update', 'delete', 'update_status')
- `module` (VARCHAR, nama modul seperti 'Tips', 'Promo', 'Users')
- `description` (TEXT, detail aktivitas, cth: "Admin mengupdate status Tips ID 5 menjadi Draft")
- `ip_address` (VARCHAR)
- `user_agent` (VARCHAR)
- `created_at` (DATETIME)

---

### 2. Model & Service Layer

Membuat lapisan akses data untuk menyimpan dan membaca log.

#### [NEW] `app/Models/AdminActivityLogModel.php`

Model standar CodeIgniter 4 untuk tabel log.

#### [NEW] `app/Services/ActivityLogService.php`

Service pusat yang memiliki fungsi `logAction($adminId, $action, $module, $description)`. Service ini akan diinjeksikan secara otomatis dengan IP Address dan User-Agent dari _request_ saat ini. Fungsi statis (atau helper) bisa dipertimbangkan agar pemanggilannya sangat mudah dari _Controller_ manapun.

---

### 3. Integrasi Hook (Pencatatan Aktivitas)

Menyisipkan pemanggilan `ActivityLogService` ke titik-titik krusial di sistem.

#### [MODIFY] `app/Controllers/Admin/Login.php`

- Menambahkan log `'login'` saat otentikasi berhasil.
- Menambahkan log `'logout'` saat sesi dihancurkan.

#### [MODIFY] Controller Modul Utama (Contoh: Tips, Users, dll)

- Menyisipkan log pada _method_ `store`, `update`, `delete`, dan `update_status`.
- (Saya akan mengimplementasikannya pada beberapa modul penting terlebih dahulu sebagai contoh dan kerangka dasar).

---

### 4. Antarmuka Panel Admin (Dashboard)

Membuat halaman UI bagi Superadmin untuk meninjau log secara _real-time_.

#### [NEW] `app/Controllers/Admin/ActivityLogController.php`

Controller untuk mengambil data log dari database dan merendernya ke view.

#### [NEW] `app/Views/admin/activity_logs/index.php`

Halaman yang menampilkan tabel log aktivitas menggunakan DataTables. Akan dilengkapi dengan fitur:

- Filter berdasarkan Nama Admin.
- Filter berdasarkan Aksi (Login, Create, dsb).
- Filter rentang tanggal.

#### [MODIFY] `app/Views/layout/sidebar.php`

Menambahkan tautan menu "Log Aktivitas" di bawah grup Manajemen Sistem/Pengaturan.

#### [MODIFY] `app/Config/Routes.php`

Mendaftarkan _route_ baru `admin/activity-logs`.

## Verification Plan

### Automated/Manual Tests

- **Login/Logout Test:** Login sebagai admin, kemudian logout. Memastikan ada dua baris baru di tabel log yang mencatat login dan logout dari akun tersebut beserta IP dan perangkatnya.
- **CRUD Test:** Mengedit sebuah "Tips" (seperti yang baru saja kita kerjakan), lalu memverifikasi bahwa aktivitas pengeditan tercatat secara detail.
- **UI Verification:** Membuka menu Log Aktivitas di sidebar, memeriksa apakah data muncul di DataTables dan apakah filter (Search, Sorting) berfungsi dengan baik.
