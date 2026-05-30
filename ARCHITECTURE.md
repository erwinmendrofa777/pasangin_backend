# 🏗️ Arsitektur & Konteks AI — Pasangin Backend

> **Tujuan file ini**: Memberikan konteks lengkap kepada AI assistant agar setiap sesi chat baru, AI langsung memahami project tanpa perlu menganalisis ulang dari awal.
> **Terakhir diperbarui**: 2026-05-20

---

## 1. Identitas Project

| Key | Value |
|---|---|
| **Nama** | Pasangin Backend |
| **Composer name** | `pasangin/backend` |
| **Deskripsi** | Backend API & Admin Panel untuk Aplikasi Pasangin — platform yang menghubungkan klien dengan tukang/kontraktor untuk jasa konstruksi, renovasi, dan desain interior |
| **Website** | https://pasangin.co.id |
| **Framework** | CodeIgniter 4 (CI4) |
| **PHP** | ^8.1 |
| **Database** | MySQL (MySQLi) — DB name: `stuh8812_pasangin_db`, User: `stuh8812_pasangin_db` |
| **Charset DB** | `utf8mb4`, Collation: `utf8mb4_general_ci` |
| **Timezone App** | `Asia/Jakarta` |
| **Locale** | `en` |
| **Lisensi** | Proprietary |
| **baseURL Produksi** | `https://backend.pasangin.co.id/` |
| **baseURL Lokal** | `http://localhost:8080` (via `.env`) |
| **Environment aktif** | `development` (via `.env`) |
| **indexPage** | `''` (tidak ada `index.php` di URL) |

---

## 2. Tech Stack & Dependencies

### Core (composer.json)
- **CodeIgniter 4** (`codeigniter4/framework: ^4.0`) — MVC Framework
- **CodeIgniter Shield** (`codeigniter4/shield: ^1.3`) — Library auth (dipakai untuk admin panel session-based)
- **Firebase PHP** (`kreait/firebase-php: ^7.0`) — Push Notification via FCM v1 HTTP API
- **DomPDF** (`dompdf/dompdf: ^3.1`) — Generate PDF (kontrak, surat, RAB)

### ThirdParty (dimuat manual)
- **Firebase JWT** (`app/ThirdParty/php-jwt/src/`) — JWT encode/decode untuk API mobile auth
  - Namespace: `Firebase\JWT` → di-map ke `APPPATH . 'ThirdParty/php-jwt/src'`
  - Di-require manual di `Auth.php` filter: `require_once APPPATH . '../app/ThirdParty/php-jwt/src/JWT.php'`
- **Midtrans** (`app/ThirdParty/Midtrans/`) — Payment gateway
- **vendor_manual** — Library tambahan manual

### Dev Dependencies
- PHPUnit `^9.1`, FakerPHP `^1.9`, VfsStream `^1.6`

---

## 3. Arsitektur Aplikasi

### 3.1 Dua Sisi Aplikasi

Project ini melayani **DUA sisi** yang berbeda secara arsitektur:

```
┌────────────────────────────────────────────┐
│           ADMIN PANEL (Web)                │
│  Session-based auth (AdminAuthFilter)      │
│  View menggunakan PHP native (view())      │
│  Routes: /admin/*                          │
│  Controller: Modules/*/Controllers/Admin/* │
└────────────────────────────────────────────┘

┌────────────────────────────────────────────┐
│           REST API (Mobile App)            │
│  JWT-based auth (Auth filter)              │
│  Response: JSON                            │
│  Routes: /api/*                            │
│  Controller: app/Controllers/Api/*         │
└────────────────────────────────────────────┘
```

### 3.2 Pola Arsitektur per Module

Setiap modul di `app/Modules/` mengikuti pola **Repository-Service-Controller**:

```
app/Modules/{NamaModul}/
├── Config/
│   ├── Routes.php              # Route admin panel modul (dimuat via require di Config/Routes.php)
│   └── Validations/            # Trait validasi, di-use oleh Config/Validation.php
├── Controllers/
│   ├── Admin/                  # Controller untuk admin panel (session auth)
│   └── Api/                    # Controller untuk REST API (kosong di beberapa modul)
├── Models/                     # CI4 Model (extends CodeIgniter\Model), returnType = 'array'
├── Repositories/
│   ├── Contracts/              # Interface (XxxRepositoryInterface)
│   └── XxxRepository.php       # Implementasi data access
├── Services/                   # Business logic layer
└── Views/                      # View PHP native untuk admin panel
```

**Alur Request:**
```
Route → Filter (auth/login) → Controller → Service → Repository → Model → DB
```

### 3.3 Namespace Convention

```php
// Modules
App\Modules\{ModuleName}\Controllers\Admin\*
App\Modules\{ModuleName}\Controllers\Api\*
App\Modules\{ModuleName}\Models\*
App\Modules\{ModuleName}\Repositories\*
App\Modules\{ModuleName}\Repositories\Contracts\*
App\Modules\{ModuleName}\Services\*

// Main API Controllers (bukan di dalam Modules!)
App\Controllers\Api\*

// Config
Config\*
```

---

## 4. Detail Konfigurasi (app/Config/)

> ⚠️ **Penting**: Ada dua file dengan nama `Pager.php` isinya sebenarnya adalah duplikat `Filters` (bug/salah simpan). Gunakan `app/Config/Filters.php` sebagai sumber kebenaran filter.

### 4.1 App.php
```php
$baseURL        = 'https://backend.pasangin.co.id/'  // Di-override .env saat dev
$allowedHostnames = ['backend.pasangin.co.id']
$indexPage      = ''          // Tidak ada index.php di URL
$appTimezone    = 'Asia/Jakarta'
$charset        = 'UTF-8'
$defaultLocale  = 'en'
$forceGlobalSecureRequests = false
$CSPEnabled     = false
// Cookie settings (untuk menghindari redirect Firefox):
$cookieSecure   = true        // HTTPS only
$cookieHTTPOnly = true
$cookieSameSite = 'Lax'
```

### 4.2 Auth.php (Shield Config)
- Default authenticator: **`session`** (untuk admin panel)
- Login field: **email** (username di-comment)
- `$allowRegistration = true`
- `$allowMagicLinkLogins = true` (magic link lifetime: 1 HOUR)
- Password hash: `PASSWORD_DEFAULT` (bcrypt, cost 12)
- Password validators: `CompositionValidator`, `NothingPersonalValidator`, `DictionaryValidator`
- Password min length: **8 karakter**
- Max similarity username-password: **50%**
- Remember me: **30 hari**
- Session field name: `'user'`
- Tabel Shield (default, tidak di-rename):
  - `users`, `auth_identities`, `auth_logins`, `auth_token_logins`, `auth_remember_tokens`, `auth_groups_users`, `auth_permissions_users`
- **Redirect setelah login/register/logout**: ke `'/'` (Shield default, tetapi admin panel menggantinya sendiri)

### 4.3 AuthGroups.php — Roles & Permissions

**Groups:**
```
superadmin, finance, drafter, surveyor, designer, design_interior, arsitek, estimator, konten_kreator
```

**Permissions per group:**
| Group | Permissions |
|---|---|
| `superadmin` | `admin.access`, `admin.settings`, `users.manage`, `finance.manage`, `project.manage`, `content.manage`, `survey.manage` |
| `finance` | `finance.access`, `finance.view`, `finance.invoice` |
| `drafter` | `drafter.access`, `drafter.create`, `drafter.edit` |
| `surveyor` | `surveyor.access`, `surveyor.collect` |
| `designer` | `design.access`, `design.create`, `design.edit` |
| `design_interior` | `design_interior.access`, `design_interior.create`, `design_interior.edit` |
| `arsitek` | `arsitek.access`, `arsitek.design` |
| `estimator` | `estimator.access`, `estimator.rab`, `estimator.price` |
| `konten_kreator` | `content.create`, `content.manage`, `content.social`, `content.draft` |

**Permission helper cek:**
```php
can('dashboard_view')           // → Super admin dashboard
can('dashboard_kadiv_desainer') // → Kadiv desain dashboard
can('dashboard_desainer')       // → Desainer dashboard
// Super admin bypass via: 'super_admin_override' dalam session permissions
```

### 4.4 AuthToken.php
- Record login attempts: hanya **failure** yang dicatat
- Auth header: `Authorization`
- Unused token lifetime: **1 YEAR**
- HMAC encryption driver: **OpenSSL**, digest: **SHA512**

### 4.5 Autoload.php
```php
$psr4 = [
    'App'          => APPPATH,                    // app/
    'App\Modules'  => APPPATH . 'Modules',         // app/Modules/
    'Config'       => APPPATH . 'Config',           // app/Config/
    'Firebase\JWT' => APPPATH . 'ThirdParty/php-jwt/src', // JWT lib
];
// Global helpers (auto-load di semua request):
$helpers = ['url', 'form', 'array', 'setting', 'permission'];
```

> **Catatan**: `permission` helper di-autoload global → fungsi `can()` dan `canAny()` tersedia di mana saja.

### 4.6 Database.php
```php
// Production / default config:
'hostname' => 'localhost'
'username' => 'stuh8812_pasangin_db'
'password' => 'fXBIC{LN-G$VRw(w'
'database' => 'stuh8812_pasangin_db'
'DBDriver' => 'MySQLi'
'DBPrefix' => ''            // TIDAK ada prefix tabel
'DBDebug'  => true
'charset'  => 'utf8mb4'
'DBCollat' => 'utf8mb4_general_ci'
'port'     => 3306

// Lokal (via .env override):
'username' => 'root'
'password' => ''

// Testing: SQLite3 in-memory
```

### 4.7 Filters.php
```php
$aliases = [
    'login' => AdminAuthFilter::class,  // Session auth untuk admin panel
    'auth'  => Auth::class,             // JWT auth untuk API mobile
];
// Global: filter 'login' berjalan di semua route KECUALI 'api/*' dan 'login/*'
// (Ada dua versi Filters.php — aktif/yang dipakai adalah yang di-list di Autoload)
```

> **PENTING**: Ada **dua versi** `Filters.php`:
> 1. **`app/Config/Filters.php`** (yang sederhana) — hanya register alias, tidak ada globals
> 2. **`app/Config/Pager.php`** (nama file salah) — versi lama dengan global filter `login` + `except ['api/*', 'login/*']`
>
> Versi yang **aktif dipakai** adalah `app/Config/Filters.php` (tanpa global), filter dipasang **per-route di Routes.php**.

### 4.8 Routes.php
- Auto routing: **DISABLED** (`setAutoRoute(false)`)
- Default controller: `Home`
- Default method: `index`
- Module routes di-load via `require` dari `Modules/*/Config/Routes.php`
- **Struktur routing:**
  ```
  /admin/*         → filter 'login' (AdminAuthFilter / session)
  /api/*           → public (tanpa auth)
  /api/*           → filter 'auth' (JWT) untuk private endpoints
  ```

### 4.9 Routing.php
```php
$autoRoute              = false          // Auto routing MATI
$translateUriToCamelCase = true         // URI → CamelCase controller name
$translateURIDashes     = false
$defaultNamespace       = 'App\Controllers'
$defaultController      = 'Home'
$defaultMethod          = 'index'
```

### 4.10 Validation.php
Aturan validasi di-split per modul menggunakan **PHP Traits**. File ini hanya mengimport dan `use` semua trait:
```php
use UserRules, SupplierRules, ProductRules, AdminRules, AuthRules,
    BannerRules, ConstructionRules, DesignRules, NotificationRules,
    OrderRules, PriceEstimateRules, PromoRules, RenovationRules,
    RoleRules, SyaratKetentuanRules, TipsRules, TukangRules,
    VoucherRules, WalletRules;
```
Trait ada di: `Modules/{Modul}/Config/Validations/{Modul}Rules.php`

Custom rule set: `App\Validation\UserRules::class`

> **Cara menambah validasi baru**: Buat trait di `Modules/{Modul}/Config/Validations/`, lalu `use` di `Config/Validation.php`.
> **FileRules** menggunakan **non-strict** (`CodeIgniter\Validation\FileRules`) agar file upload tidak error.

### 4.11 Security.php (CSRF)
```php
$csrfProtection = 'session'     // CSRF pakai session (bukan cookie)
$tokenName      = 'csrf_test_name'
$headerName     = 'X-CSRF-TOKEN'
$expires        = 7200          // 2 jam
$regenerate     = true
$redirect       = (ENVIRONMENT === 'production')  // Redirect saat gagal di prod
```
> **CSRF tidak aktif global** — tidak ada di `$globals['before']` di Filters.php.

### 4.12 Session.php
```php
$driver     = FileHandler::class      // Disimpan di file (bukan database/redis)
$cookieName = 'ci_session'
$expiration = 7200                    // 2 jam
$savePath   = WRITEPATH . 'session'  // writable/session/
$matchIP    = false
$timeToUpdate = 300                   // Regenerate session ID tiap 5 menit
```

### 4.13 Email.php
Konfigurasi dasar kosong di file ini. Aktual dikonfigurasi via `.env`:
```
email.protocol   = 'smtp'
email.SMTPHost   = 'smtp.gmail.com'
email.SMTPUser   = 'pasanginapp@gmail.com'
email.SMTPPass   = 'yxzr wdtb ipzh mgrt'   // Google App Password
email.SMTPPort   = 465
email.SMTPCrypto = 'ssl'
email.mailType   = 'html'
email.charset    = 'utf-8'
```

### 4.14 Encryption.php
```php
$driver  = 'OpenSSL'
$cipher  = 'AES-256-CTR'
$digest  = 'SHA512'
// Key dari .env: hex2bin:6b0112d842f94859704e1196062db1c2d7d98093...
```

### 4.15 Constants.php
Konstanta waktu tersedia global:
```php
SECOND = 1, MINUTE = 60, HOUR = 3600, DAY = 86400,
WEEK = 604800, MONTH = 2_592_000, YEAR = 31_536_000, DECADE = 315_360_000
```

### 4.16 Cache.php
```php
$handler       = 'file'           // File-based cache (default)
$backupHandler = 'dummy'
$storePath     = WRITEPATH . 'cache/'
$ttl           = 60               // Default 60 detik
```

### 4.17 Logger.php
```php
$threshold = (production ? 4 : 9)   // Dev: semua log, Prod: error ke atas
// Handler: FileHandler → writable/logs/
// Level: critical, alert, emergency, debug, error, info, notice, warning
```

### 4.18 Images.php
```php
$defaultHandler = 'gd'   // GDHandler (PHP GD Library)
// Alternatif: imagick (ImageMagickHandler)
```

### 4.19 Migrations.php
```php
$enabled         = true
$table           = 'migrations'
$timestampFormat = 'Y-m-d-His_'   // Format: 2024_04_15_...
```

### 4.20 Format.php (API Response)
```php
$supportedResponseFormats = ['application/json', 'application/xml', 'text/xml']
// JSON response: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
```
> **Penting**: `JSON_UNESCAPED_UNICODE` → karakter Unicode (misal bahasa Indonesia) tidak di-escape. `JSON_UNESCAPED_SLASHES` → slash tidak di-escape.

### 4.21 Settings.php
```php
$handlers      = ['array']      // Pakai in-memory array, bukan database
$databaseTable = 'settings'
```

### 4.22 Cors.php
```php
// Semua CORS setting default (kosong) — tidak dikonfigurasi khusus
$allowedOrigins  = []
$allowedHeaders  = []
$allowedMethods  = []
$maxAge          = 7200
```
> CORS belum dikonfigurasi. Jika mobile app butuh CORS, perlu tambahkan allowed origins.

### 4.23 Events.php
- Hanya setup standard CI4: output buffering dan debug toolbar
- Tidak ada custom event yang didaftarkan

### 4.24 Services.php
- Kosong (tidak ada custom service yang di-override)
- Menggunakan semua CI4 default services

### 4.25 Variabel .env Penting
```ini
CI_ENVIRONMENT          = development
JWT_SECRET              = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma'
VERIHUBS_APP_ID         = "6d84134a-e9e8-4059-87b0-15111de7e5be"
VERIHUBS_API_KEY        = "Mt7Dw8BECMSevke0wO4hQK/En4yVvtxl"
FIREBASE_AUTH_JSON      = 'writable/firebase_key.json'
encryption.key          = hex2bin:6b0112d842...
```

---

## 5. Sistem Autentikasi
### 5.1 Admin Panel (Session-based)
- **Filter alias**: `login` → `AdminAuthFilter::class`
- **Mekanisme**: Cek `session()->get('isLoggedIn')`
- **Session keys**: `isLoggedIn`, `user_id`, `full_name`, `role`, `permissions[]`
- **Super Admin**: `'super_admin_override'` dalam array `permissions`
- **Bypass otomatis** (tidak cek permission modul): `dashboard`, `logout`, `profile`, `notification`
- **Login URL**: `/admin/login`
- **Redirect saat tidak punya akses**: ke `/admin/dashboard` dengan flash error

### 5.2 API Mobile (JWT-based)
- **Filter alias**: `auth` → `Auth::class`
- **Mekanisme**: Header `Authorization: Bearer {token}`
- **Library**: `Firebase\JWT\JWT` dari `ThirdParty/php-jwt/src/`
- **Algorithm**: `HS256`
- **Secret Key**: `JWT_SECRET` dari `.env` = `'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma'`
- **Decoded data**: disimpan ke `service('request')->user`
- **Error response**: HTTP 401 JSON `{"status":"error","message":"..."}`

### 5.3 Tiga Jenis User API
| User Type | Login Endpoint | Register Endpoint |
|---|---|---|
| **Client** | `POST api/login` | `POST api/register` |
| **Tukang** | `POST api/tukang/login` | `POST api/tukang/register` |
| **Supplier** | `POST api/supplier/login` | `POST api/supplier/register` |

### 5.4 OTP & Verifikasi
- OTP request/verify: `api/otp/request`, `api/otp/verify`
- Verifikasi email: `api/verify-email`
- Forgot password flow: `api/forgot-password` → `api/verify-otp` → `api/reset-password`
- Verifikasi identitas via **Verihubs** (KTP/e-KTP)

---

## 6. Helpers Global

Helper berikut di-autoload untuk **semua request** (via `Autoload.php`):
`url`, `form`, `array`, `setting`, `permission`

Helper tambahan di-load di `BaseController.$helpers`:
`form`, `url`, `auth`, `activity_log`

### Fungsi Helper Kustom

**`permission_helper.php`** (global via Autoload):
```php
can(string $module): bool           // Cek akses admin ke modul
canAny(array $modules): bool        // Cek akses ke salah satu modul
```

**`notification_helper.php`**:
```php
sendFCMNotification(string $token, string $title, string $body, array $data = [], ?string $imageUrl = null): bool
sendFCMToMultiple(array $tokens, string $title, string $body, array $data = [], ?string $imageUrl = null): array
// Firebase Service Account: writable/pasangin-c8050-firebase-adminsdk-fbsvc-547edad397.json
```

**`activity_log_helper.php`**: Logging aktivitas admin

**`terbilang_helper.php`**: Konversi angka ke teks Indonesia (untuk dokumen formal)

---

## 7. Sistem Validasi

Aturan validasi menggunakan **Trait** yang di-split per modul. Setiap modul punya trait-nya sendiri di:
```
Modules/{Modul}/Config/Validations/{Modul}Rules.php
```

Semua trait di-`use` di `Config/Validation.php`. Modul yang sudah punya validation rules:
`Users`, `Supplier`, `Products`, `Admin`, `Autentications`, `Banners`, `Construction`, `Design`, `Notifications`, `Orders`, `PriceEstimate`, `Supplier (PromoRules)`, `Renovation`, `Admin (RoleRules)`, `SyaratKetentuan`, `Tips`, `Tukang`, `Vouchers`, `Wallets`

Custom validation class: `App\Validation\UserRules` (di `app/Validation/UserRules.php`)

---

## 8. Daftar Modul (app/Modules/)

| Modul | Deskripsi Bisnis |
|---|---|
| `AboutApplication` | Halaman tentang aplikasi Pasangin |
| `Admin` | Manajemen admin user, roles, activity log |
| `Autentications` | Login/register/logout admin panel |
| `Banners` | Manajemen banner promosi untuk klien |
| `Chat` | Sistem pesan 1-on-1 antar user |
| `Construction` | Manajemen proyek konstruksi (paling kompleks — 16 model, 14+ repo) |
| `Dashboard` | Dashboard multi-role (Super Admin, Kadiv Desainer, Desainer) |
| `Design` | Manajemen request desain interior/arsitektur |
| `Notifications` | Sistem notifikasi push (FCM) admin-side |
| `Orders` | Manajemen pesanan marketplace material |
| `PriceEstimate` | Estimasi harga layanan |
| `Products` | Manajemen produk marketplace |
| `Renovation` | Manajemen proyek renovasi (struktur mirip Construction) |
| `Supplier` | Manajemen supplier/toko material |
| `SyaratKetentuan` | Terms & conditions per jenis layanan |
| `Tips` | Konten tips & artikel |
| `Tukang` | Manajemen tukang/kontraktor |
| `Users` | Manajemen user/klien |
| `Vouchers` | Manajemen voucher diskon |
| `Wallets` | Dompet digital tukang (saldo + penarikan) |

---

## 9. API Controllers (app/Controllers/Api/)

> **Total file**: 36 controller + 1 file backup (`ChatController_lama.php`)
> **Base class**: sebagian besar `extends ResourceController` (CI4 RESTful), sebagian `extends BaseController` + `use ResponseTrait`

### 9.1 Pola Base Class per Controller

| Base Class | Dipakai oleh | Karakteristik |
|---|---|---|
| `ResourceController` | `AuthController`, `TukangAuthController`, `SupplierAuthController`, `ProductApi`, `ContentController`, `NotificationController`, `ChatController`, `TukangJobApi`, dll | `$format = 'json'`, punya method helper `$this->respond()`, `$this->failUnauthorized()`, dll bawaan CI4 |
| `BaseController` + `ResponseTrait` | `UserController`, `ConstructionApi`, `OrderApi`, `DesignController`, `RenovationApi` | Butuh `use ResponseTrait` agar punya helper response. Bisa akses `$this->request->user` dari JWT filter |
| `Controller` + `ResponseTrait` | `AuthAPI` | Paling bare-minimum |

### 9.2 Cara JWT Auth Dipakai di Controller

Ada **DUA cara** yang berbeda digunakan di project ini:

**Cara A: Manual decode dari header (di controller sendiri)**
```php
// Dipakai di: AuthController, TukangAuthController, SupplierAuthController, ProductApi, ChatController
private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

private function getSupplierId() {
    $token = str_replace('Bearer ', '', $this->request->getHeaderLine('Authorization'));
    $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
    return $payload['uid'] ?? null;
}
```

**Cara B: Dari filter Auth (lewat `$this->request->user`)**
```php
// Dipakai di: ConstructionApi, RenovationApi, OrderApi, DesignController
// Filter Auth.php sudah decode JWT dan simpan ke request->user
$userId = $this->request->user->uid;
$role   = $this->request->user->role ?? 'client';
```

> ⚠️ **Catatan penting**: Cara A dan B **tidak konsisten** di project ini. Beberapa controller yang seharusnya pakai filter `auth` malah decode sendiri (misalnya `ChatController` pakai `Firebase\JWT\JWT::decode()`). **Selalu cek bagaimana controller masing-masing mengambil user ID.**

### 9.3 JWT Payload Structure

```php
// Client token (AuthController)
['iss' => 'https://backend.pasangin.co.id', 'iat' => time(), 'exp' => now+7hari, 'uid' => $user['id']]

// Tukang token (TukangAuthController)
['iss' => '...', 'iat' => ..., 'exp' => now+7hari, 'uid' => $user['id'], 'role' => 'tukang']

// Supplier token (SupplierAuthController)
['iss' => '...', 'iat' => ..., 'exp' => now+7hari, 'uid' => $user['id'], 'role' => 'supplier']
```

> **Perhatikan**: Token client tidak punya field `role`, sedangkan tukang dan supplier punya `role`.

### 9.4 Enkripsi NIK User

`UserController::update()` dan `AuthController::login()` menangani enkripsi/dekripsi NIK:
```php
// ENKRIPSI (saat update):
$encrypter = \Config\Services::encrypter();
$nik_rahasia = $encrypter->encrypt($nik);
$updateData['nik'] = base64_encode($nik_rahasia);

// DEKRIPSI (saat login):
$ciphertext = base64_decode($user['nik']);
$nik_asli = $encrypter->decrypt($ciphertext);
```
NIK di-encrypt dengan `AES-256-CTR` via `Encryption.php`, key dari `.env`.

### 9.5 Inventaris Lengkap Semua API Controller

#### 🔐 Auth Controllers

| File | Namespace | Base | Method Utama |
|---|---|---|---|
| `AuthController.php` | `Api` | `ResourceController` | `login()` — login client via nomor telepon (status MUST='approved'), `register()` — simpan user baru, `requestOtp()` — kirim OTP Verihubs per role, `verifyOtp()` — verifikasi OTP Verihubs, `verifyEmail()` — cek email tersedia, `updateFcmToken()` |
| `AuthAPI.php` | `Api` | `Controller`+ResponseTrait | `requestOtp()` — lupa password (kirim OTP ke HP), `verifyOtp()` — verif OTP lupa password, `resetPassword()` — reset via tabel `password_reset_tokens` (expire 15 menit) |
| `TukangAuthController.php` | `Api` | `ResourceController` | `login()`, `register()` (status='Berkas Diproses'), `updateFcmToken()`, `updateProfile()`, `updateProfileByKtp()`, `getProfile($id)`, `extractSync()` — verifikasi KTP+Selfie via Verihubs (face compare + KTP extract) |
| `SupplierAuthController.php` | `Api` | `ResourceController` | `login()` (cek is_active & status), `register()`, `updateProfile()` (upload logo), `changePassword()`, `getProfile($id)` (public: total produk, pesanan, rating), `updateFcmToken()` |

#### 👤 User Controllers

| File | Base | Method Utama |
|---|---|---|
| `UserController.php` | `BaseController`+RT | `update()` — update profil+foto+NIK+password, `requestOtp()` — kirim OTP delete-account via EMAIL, `verifyOtp()` — verif OTP delete-account, `confirmInactivateAccount()` — set status='nonaktif', `confirmActivateAccount()` — set status='approved', `confirmDeleteAccount()` — hapus akun CASCADE (semua proyek, order, chat, dll) |
| `AlamatUserController.php` | (CI4) | CRUD alamat user (`alamat_user`) |

#### 🏗️ Proyek Jasa Controllers

| File | Base | Method Utama |
|---|---|---|
| `ConstructionApi.php` (1070 baris) | `BaseController`+RT | `submit()` — submit request+upload gambar (maks 5), `project($userId)` — riwayat proyek, `detail($id)` — detail proyek, `surveys($id)`, `designs($id)`, `progress($id)` — progress dengan grouping per target+kalkulasi persentase, `invoices($id)`, `rabs($id)` — RAB beserta materials, `select_material()` — pilih material, `finalize_rab()` — lock RAB + **generate kontrak PDF via DomPDF**, `targets()`, `targetsByUser()`, `progressByUser()`, `absensi*`, `agreements*` |
| `RenovationApi.php` (42595 bytes) | (mirip Construction) | Struktur sama dengan Construction, tabel prefix `renovation_*` |
| `DesignController.php` | `BaseController`+RT | Submit design request, history, detail, surveys, designs, targets, progress, invoices |
| `ProjectApi.php` | ResourceController | Endpoint umum lintas tipe proyek |
| `ContractApi.php` | (CI4) | Generate & view kontrak PDF |
| `AgreementController.php` | (CI4) | Batch approval agreement |
| `JobApplicationController.php` | (CI4) | Tukang melamar job |

#### 🛒 Marketplace Controllers

| File | Base | Method Utama |
|---|---|---|
| `ProductApi.php` (503 baris) | `ResourceController` | `index()` — list produk publik (search, filter region, pagination), `show($id)` — list kategori, `regions()` — list kota (normalize nama kota), `myProducts()`, `create()` (supplier only, status='approved'), `update($id)`, `delete($id)`, `getBySupplier($id)`, `detailProduct($id)`, `showrating($id)`, `createRating()` — upload max 5 foto rating |
| `CategoryApi.php` | ResourceController | CRUD kategori produk |
| `CartApi.php` | ResourceController | Add to cart, remove, list |
| `OrderApi.php` (500 baris) | `BaseController`+RT | `checkout()` — multi-supplier order, buat transaction, buat orders per supplier, insert order_items, hapus cart, generate Midtrans Snap token; `history()`, `detail($id)`, `delete($id)`, `transactionDetail($txnId)`, `transactionHistory()`, `webhookMidtrans()` — update status PAID/FAILED |
| `PaymentApi.php` | (CI4) | Token payment, notification webhook Midtrans |
| `VoucherController.php` | ResourceController | Validasi & apply voucher |
| `PromoApi.php` | ResourceController | List promo supplier |

#### 🔧 Supplier Controllers

| File | Base | Method Utama |
|---|---|---|
| `SupplierOrderApi.php` | ResourceController | List order masuk, update status, konfirmasi pengiriman |
| `SupplierOngkirApi.php` | ResourceController | CRUD ongkos kirim per area |
| `SupplierBannerController.php` | ResourceController | CRUD banner toko supplier |
| `SupplierProfileApi.php` | ResourceController | Get profil publik supplier |
| `SuppliersRatingController.php` | ResourceController | Rating dari client ke supplier |

#### 👷 Tukang Controllers

| File | Base | Method Utama |
|---|---|---|
| `TukangJobApi.php` (806 baris) | `ResourceController` | `getConstructionJobs()`, `getRenovationJobs()`, `getApplicationStatus($id)`, `getMyApplications($id)`, `getMyTargets($id)` — UNION query construction+renovation targets, `getActiveProjects($id)`, `submitProgress()` + `createConstructionProgress()` + `createRenovationProgress()`, `getConstructionProgress($id)`, `getRenovationProgress($id)`, `getProjectListForAttendance($id)` — absensi checkin/checkout dengan geolocation+radius, `getRenovationListForAttendance($id)` |
| `TukangRatingController.php` | ResourceController | Rating tukang dari client |
| `TukangContentController.php` | ResourceController | Konten/banner khusus tukang |
| `WalletController.php` | ResourceController | Saldo wallet tukang, request withdraw |

#### 🔔 Notifikasi & Konten Controllers

| File | Base | Method Utama |
|---|---|---|
| `NotificationController.php` (236 baris) | `ResourceController` | `index($userType,$userId)` — list notif dengan subquery is_read + exclude deleted, `markAsRead($userType)`, `markAllAsRead($userType)`, `deleteNotification($userType,$notifId,$userId)` — soft delete via `notification_deletes`, `unreadCount($userType,$userId)`, `toggleNotification($userType)` — ON/OFF per FCM token |
| `NotificationApi.php` | (CI4) | Push notifikasi dari admin |
| `Notification.php` | (CI4) | Entitas/model notifikasi |
| `ContentController.php` | `ResourceController` | `tips()`, `banners()`, `price_estimate()` — ambil data konten publik |
| `AboutApplicationPasanginControllerApi.php` | ResourceController | Konten halaman "Tentang Pasangin" |

#### 💬 Chat Controller

| File | Base | Method Utama |
|---|---|---|
| `ChatController.php` | `ResourceController` | `getAllConversationsForUser($userId)` — list conversation user, `createOrGetConversation()` — selalu buat conversation BARU (tidak reuse), `getMessages($convId)` — list pesan, `sendMessage()` — simpan pesan + update preview |
| `ChatController_lama.php` | — | File backup lama, TIDAK DIPAKAI |

### 9.6 Pola Khusus yang Penting

#### Upload File
```php
// Single file
$file = $this->request->getFile('photo');
if ($file && $file->isValid() && !$file->hasMoved()) {
    $newName = $file->getRandomName();
    $file->move('uploads/{folder}/', $newName);
}

// Multiple files (maks 5 gambar)
$images = $this->request->getFileMultiple('images');
foreach ($images as $img) { ... }
```

**Folder upload yang ada:**
```
public/uploads/
├── construction/          # Foto pengajuan konstruksi (gambar1-5)
│   ├── survey/            # Foto hasil survei
│   ├── designs/           # File desain
│   ├── rab/               # File RAB
│   └── progress/          # Foto progress
├── renovation/            # Foto renovation (struktur sama)
├── products/              # Foto produk (default: default.png)
│   └── rating/            # Foto review produk (maks 5)
├── tukang/                # Foto profil tukang
│   └── selfie/            # Foto selfie KTP tukang
├── supplier/              # Logo supplier
├── surat_kontrak/         # PDF kontrak yang di-generate
├── profile/               # Foto profil client
├── banners/               # Foto banner
└── tips/                  # Foto artikel tips
```

#### Generate PDF Kontrak (DomPDF)
```php
// Hanya di: ConstructionApi::finalize_rab(), RenovationApi (serupa)
helper('terbilang');
$html = view('admin/surat/kontrak_template', $data);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('f4', 'portrait');
$dompdf->render();
$output = $dompdf->output();
file_put_contents(FCPATH . 'uploads/surat_kontrak/' . $fileName, $output);
// Juga update kolom 'rab_file' di construction_requests
```

#### Notifikasi di Controller
```php
// Inisiasi di constructor
$this->notifService = new \App\Modules\Notifications\Services\NotificationService();

// Kirim ke user spesifik
$this->notifService->sendPersonal('client', $userId, 'Judul', 'Pesan');
$this->notifService->sendPersonal('tukang', $tukangId, ...);

// Kirim ke semua admin yang punya permission
$this->notifService->sendToPermission('construction_detail', 'Judul', 'Pesan');
// Permission yang digunakan: 'construction_detail', 'construction_progress', 
// 'construction_rab', 'tukang_create', 'tukang_verify', 'renovation_progress'
```

#### Midtrans (Payment Gateway)
```php
require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
\Midtrans\Config::$serverKey = 'Mid-server-faF45iiQSTC4Edaxkxo51vqn';
\Midtrans\Config::$isProduction = false;  // ⚠️ MASIH SANDBOX!
\Midtrans\Snap::createTransaction($params);  // Returns snap_token + redirect_url
```
> ⚠️ **Midtrans masih mode sandbox** (`isProduction = false`). Server key hardcoded di `OrderApi.php` dan `PaymentApi.php`.

#### Absensi Tukang (Geolocation)
- Radius absensi: diambil dari `construction_requests.land_area` sebagai meter (atau default 150m)
- Tipe: `masuk` / `keluar`
- Status: `belumAbsen` / `sudahAbsenMasuk` / `sudahAbsenKeluar`
- Cek absensi hari ini: `DATE(waktu) = CURDATE()`

#### Checkout Multi-Supplier
```
OrderApi::checkout():
1. Buat satu transaction (TRX-{time}-{userId})
2. Group cart items per supplier_id
3. Buat satu order per supplier (PASANGIN-{time}-{userId}-{index})
4. Insert order_items per order
5. Hapus cart
6. Panggil Midtrans Snap untuk satu transaksi total
7. Webhook Midtrans update semua orders jadi PAID/CANCELLED
```

36 file controller API:

**Auth**: `AuthController`, `TukangAuthController`, `SupplierAuthController`, `AuthAPI`
**Proyek**: `ConstructionApi`, `RenovationApi`, `DesignController`, `ProjectApi`, `ContractApi`, `AgreementController`
**Marketplace**: `ProductApi`, `CategoryApi`, `CartApi`, `OrderApi`, `PaymentApi`
**Supplier**: `SupplierOrderApi`, `SupplierOngkirApi`, `SupplierBannerController`, `PromoApi`, `SupplierProfileApi`, `SuppliersRatingController`
**Tukang**: `TukangJobApi`, `TukangAuthController`, `TukangContentController`, `TukangRatingController`, `JobApplicationController`, `WalletController`
**User**: `UserController`, `AlamatUserController`, `VoucherController`
**Notifikasi**: `NotificationController`, `NotificationApi`, `Notification`
**Konten**: `ContentController`, `AboutApplicationPasanginControllerApi`
**Chat**: `ChatController`

---

## 11. HMVC Modules (app/Modules/)

Aplikasi ini menggunakan pola **Hierarchical Model-View-Controller (HMVC)** khusus untuk bagian panel admin dan logika backend inti yang lebih terstruktur. Terdapat **20 modul** di dalam direktori `app/Modules/`. 

Daftar modul yang ada:
`AboutApplication`, `Admin`, `Autentications`, `Banners`, `Chat`, `Construction`, `Dashboard`, `Design`, `Notifications`, `Orders`, `PriceEstimate`, `Products`, `Renovation`, `Supplier`, `SyaratKetentuan`, `Tips`, `Tukang`, `Users`, `Vouchers`, `Wallets`.

Setiap modul umumnya memiliki struktur internal sebagai berikut:
- **`Config/`**: Berisi konfigurasi spesifik modul (misal: `Routes.php` untuk routing panel admin).
- **`Controllers/`**: Controller yang menangani request, dibagi lagi ke dalam sub-folder (contoh: `Admin/` untuk web view, `Api/` untuk endpoint API jika ada).
- **`Models/`**: Model CodeIgniter 4 standar yang merepresentasikan struktur tabel database.
- **`Repositories/`**: Tempat logika query SQL disimpan. Terdiri dari `Contracts/` (Interface) dan implementasi konkrit.
- **`Services/`**: Tempat logika bisnis aplikasi diimplementasikan.
- **`Views/`**: *(Tidak dipakai secara langsung dalam modul)* — Sebagian besar file view PHP untuk panel Admin diletakkan secara terpusat di `app/Views/admin/{nama_modul}/`.

## 12. Repository-Service Pattern

Modul-modul di dalam project ini menerapkan *Repository-Service Pattern* yang ketat (misalnya pada modul `Users` dan `Admin`), dengan pembagian tanggung jawab yang jelas:

### Alur Data:
`Controller` ➔ `Service` ➔ `Repository` ➔ `Model CodeIgniter`

1. **Controllers (Controller Layer)**:
   - Bertugas mengelola request HTTP.
   - Melakukan pengecekan *permissions* menggunakan helper `can('permission_name')`.
   - Menangani validasi Input (menggunakan fitur Validation CI4).
   - Memanggil `Service` untuk memproses data.
   - Mengembalikan Response (View untuk Admin Panel, atau JSON untuk API).

2. **Services (Business Logic Layer)**:
   - Menampung seluruh *Aturan Bisnis* (Business Logic).
   - Melakukan validasi logika (misal: "Admin SuperAdmin tidak boleh dihapus", "User tidak bisa menghapus diri sendiri").
   - Menangani proses file upload (memindahkan foto, menghapus foto fisik lama via filesystem).
   - Melempar `RuntimeException` jika logika bisnis gagal, yang nantinya akan ditangkap (try-catch) oleh Controller.
   - *Sama sekali tidak tahu menahu cara query SQL*.

3. **Repositories (Data Access Layer)**:
   - Satu-satunya layer yang melakukan kueri database (menggunakan Query Builder / Model CI4).
   - Mengimplementasikan antarmuka dari `Contracts/{Name}Interface.php`.
   - Mengembalikan array data dasar tanpa memproses file/upload.

4. **Models (Database Blueprint)**:
   - Hanya sebagai representasi tabel (`protected $table = 'nama_tabel'`).
   - Mendefinisikan kolom, auto-increment, timestamps, namun meminimalkan query kustom di dalamnya (karena sudah ditangani oleh Repository).

> ⚠️ **CATATAN KRITIKAL: BUG SOFT DELETES CI4**
> Pada `UserModel.php`, terdapat catatan krusial yang menyatakan bahwa fitur **Soft Deletes bawaan CI4 (`protected $useSoftDeletes = true;`) menjadi sumber Error 500 utama.** Oleh karena itu, *soft deletes* dinonaktifkan (`false`) dan baris `protected $deletedField` di-komentar agar sistem bisa berjalan normal. Hati-hati jika ingin mengaktifkan kembali soft deletes pada model lain.

## 13. Admin Panel & Views

Bagian *Front-End* Admin Panel dibangun dengan pendekatan *Server-Side Rendering (SSR)* menggunakan view bawaan CodeIgniter 4.

- **Lokasi Terpusat**: Seluruh tampilan dashboard admin disimpan di direktori `app/Views/admin/`.
- **Layouting**: Menggunakan file template utama `layout/app.php` yang kemudian di-*extend* (mekanisme `<?= $this->extend('layout/app') ?>`).
- **UI & Libraries**: 
  - Menggunakan Bootstrap (kelas-kelas Bootstrap standar).
  - Kustomisasi CSS langsung dimasukkan dalam *section* `style`.
  - Menggunakan **DataTables** (via jQuery) untuk menampilkan data tabular lengkap dengan pencarian, paginasi, dan *custom styling*.
  - Notifikasi interaktif (Flash messages sukses/error) ditampilkan menggunakan *library* **iziToast**.
  - Menggunakan helper `log_admin_activity()` secara konsisten dalam Controller untuk merekam setiap aksi *Create, Update, Delete* (CRUD) admin ke dalam database.

### 10.1 API Publik `POST /api/*` (tanpa token)
```
POST api/login                          → AuthController::login
POST api/register                       → AuthController::register
POST api/tukang/login                   → TukangAuthController::login
POST api/tukang/register                → TukangAuthController::register
POST api/tukang/verify                  → TukangAuthController::extractSync
POST api/supplier/login                 → SupplierAuthController::login
POST api/supplier/register              → SupplierAuthController::register
POST api/otp/request                    → AuthController::requestOtp
POST api/otp/verify                     → AuthController::verifyOtp
POST api/verify-email                   → AuthController::verifyEmail
POST api/user/request-otp               → UserController::requestOtp
POST api/user/verify-otp                → UserController::verifyOtp
POST api/user/activate-account/confirm  → UserController::confirmActivateAccount
GET  api/products                       → ProductApi::index
GET  api/products/show                  → ProductApi::show
GET  api/suppliers/regions              → ProductApi::regions
GET  api/content/banners                → ContentController::banners
GET  api/content/tips                   → ContentController::tips
GET  api/content/priceEstimate          → ContentController::price_estimate
POST api/payment/notification           → PaymentApi::notification (webhook Midtrans)
POST api/forgot-password                → AuthAPI::requestOtp
POST api/verify-otp                     → AuthAPI::verifyOtp
POST api/reset-password                 → AuthAPI::resetPassword
```

### 10.2 API Private (wajib JWT) `GET/POST /api/*` + filter `auth`
**Supplier**: stats, products, orders, promo, ratings, ongkir, banner, profile, withdraw
**Design**: submit, history, detail, surveys, designs, targets, progress, invoices
**Construction**: submit, detail, surveys, designs, progress, invoices, rabs, targets, absensi, select-material, finalize-rab, agreements
**Renovation**: submit, detail, surveys, designs, progress, invoices, rabs, targets, absensi, select-material, finalize-rab, agreements
**User**: update, update-fcm, inactivate, delete
**Alamat**: CRUD alamat user
**Marketplace**: cart, checkout, order history/detail, payment token
**Tukang**: jobs, applications, progress, wallet, withdraw, ratings, absensi, banners, update KTP
**Chat**: conversations, messages, send, create_or_get
**Notifikasi**: universal route `(:any)/notifications/(:num)` — support semua role
**Kontrak**: `construction/contract/(:num)`, `renovation/contract/(:num)`
**Syarat**: `syarat-ketentuan/(:any)`, agreements batch

### 10.3 Admin Panel `/admin/*` + filter `login`
Setiap modul mendefinisikan route-nya di `Modules/{Modul}/Config/Routes.php` menggunakan:
```php
$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\{Modul}\Controllers\Admin'], function($routes) {
    // route definitions
});
```

---

## 11. Pola Koding

### 11.1 Model (extends CodeIgniter\Model)
```php
class ExampleModel extends Model {
    protected $table            = 'table_name';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';     // SELALU array, bukan object!
    protected $allowedFields    = ['field1', 'field2'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
```

### 11.2 Repository (implements Interface)
```php
class ExampleRepository implements ExampleRepositoryInterface {
    protected ExampleModel $model;

    public function __construct() {
        $this->model = new ExampleModel();
    }

    public function findById(int $id): ?array {
        return $this->model->find($id) ?: null;
    }

    public function update(int $id, array $data): bool {
        return (bool) $this->model->update($id, $data);
    }
}
```

### 11.3 Service
```php
class ExampleService {
    protected ExampleRepository $repo;

    public function __construct() {
        $this->repo = new ExampleRepository();
    }
    // Business logic di sini
}
```

### 11.4 API Response Pattern
```php
// Sukses
return $this->response->setJSON([
    'status'  => 'success',
    'message' => 'Data berhasil diambil.',
    'data'    => $result
]);

// Error
return $this->response->setJSON([
    'status'  => 'error',
    'message' => 'Terjadi kesalahan.'
])->setStatusCode(400);

// Unauthorized (dari filter)
return Services::response()
    ->setJSON(['status' => 'error', 'message' => 'Akses ditolak.'])
    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
```

### 11.5 Mengakses User di Controller API (dari JWT)
```php
$user   = service('request')->user;    // stdClass dari JWT decoded
$userId = $user->sub;                  // atau sesuai payload yang di-set saat generate token
```

### 11.6 BaseController
```php
// app/Controllers/BaseController.php
protected $helpers = ['form', 'url', 'auth', 'activity_log'];
// Semua controller extends BaseController
```

### 11.7 Validasi di Controller
```php
// Rules diambil dari Validation config (via trait modul)
if (!$this->validate('ruleName')) {
    return $this->response->setJSON(['errors' => $this->validator->getErrors()])->setStatusCode(422);
}
```

---

## 12. Fitur Bisnis Utama

### 🏗️ Konstruksi & Renovasi (Alur Lengkap)
1. Client submit request → `api/construction/submit` atau `api/renovation/submit`
2. Admin jadwalkan & lakukan survei → upload hasil survei
3. Desainer upload desain
4. Estimator buat **RAB** (Rencana Anggaran Biaya) + input material + lock
5. Client pilih material (`select-material`) → finalize RAB (`finalize-rab`)
6. Generate kontrak PDF (`/contract/(:num)`)
7. Client setujui kontrak (`agreements/batch`)
8. Admin buat job posting → Tukang melamar → Admin pilih tukang
9. Tukang tracking **progress** mingguan + foto
10. **Absensi** checkin/checkout tukang
11. Admin buat **invoice** bertahap
12. Client bayar via Midtrans
13. Addendum (perubahan RAB setelah sepakat) — hanya konstruksi

### 🛒 Marketplace (Material/Produk)
- Supplier manage: produk, kategori, stok, promo, ongkir, banner
- Client: browse, cart, checkout (Midtrans), history
- Rating produk & supplier
- Voucher diskon

### 💰 Wallet Tukang
- Saldo tukang dari komisi proyek
- Request withdrawal → admin approve

### 📱 Push Notification (FCM)
- Single token: `sendFCMNotification()`
- Multicast: `sendFCMToMultiple()`
- Universal notification route: `api/(:any)/notifications/(:num)` — support semua role

### 💬 Chat
- 1-on-1 conversation
- `POST api/chat/create_or_get` → buat atau ambil conversation
- `POST api/chat/send` → kirim pesan

---

## 13. Database — Tabel Utama

### Shield/Auth Tables
`users`, `auth_identities`, `auth_logins`, `auth_token_logins`, `auth_remember_tokens`, `auth_groups_users`, `auth_permissions_users`

### User Types
`users` (klien), `tukang` (kontraktor), `suppliers`

### Construction
`construction_requests`, `construction_surveys`, `construction_designs`, `construction_rabs`, `construction_rab_materials`, `rab_material_options`, `construction_invoices`, `construction_progress`, `construction_targets`, `construction_jobs`, `job_applications`, `construction_attendance`, `construction_agreements`, `construction_addendum`, `construction_addendum_materials`, `transactions`

### Renovation
`renovation_*` (struktur mirip construction)

### Design
`design_*`

### Marketplace
`products`, `categories`, `carts`, `orders`, `transactions`, `vouchers`, `promos`, `supplier_ongkirs`, `supplier_banners`, `supplier_ratings`, `tukang_ratings`, `product_ratings`

### Konten & Lainnya
`banners`, `tips`, `price_estimates`, `conversations`, `messages`, `notifications`, `wallets`, `withdrawals`, `alamat_user`, `activity_logs`, `syarat_ketentuan`, `about_application`, `settings`, `migrations`

---

## 14. File & Folder Penting

```
d:\Program Files\backend_core\
├── .env                             # Konfigurasi lokal (DB, email, JWT, Firebase)
├── composer.json                    # Dependencies
├── spark                            # CI4 CLI
├── public/                          # Web root (index.php)
├── writable/
│   ├── logs/                        # Log file error/info
│   ├── cache/                       # Cache file
│   ├── session/                     # Session file
│   └── pasangin-c8050-firebase-...json  # Firebase Service Account
├── app/
│   ├── Config/
│   │   ├── Routes.php               # ⭐ SEMUA route (API + module loader)
│   │   ├── Filters.php              # Filter alias: 'login', 'auth'
│   │   ├── AuthGroups.php           # ⭐ Roles & permissions matrix
│   │   ├── Autoload.php             # ⭐ Namespace mapping + global helpers
│   │   ├── Validation.php           # Aggregator semua validation trait
│   │   ├── Database.php             # Koneksi DB (production credentials)
│   │   ├── App.php                  # baseURL, timezone, cookie settings
│   │   ├── Auth.php                 # Shield auth settings
│   │   ├── Session.php              # Session: file-based, 2 jam
│   │   ├── Security.php             # CSRF: session-based
│   │   ├── Email.php                # Email template (aktual config di .env)
│   │   ├── Logger.php               # Logging threshold & handler
│   │   ├── Format.php               # JSON response: unescaped unicode/slashes
│   │   └── ...lainnya
│   ├── Controllers/
│   │   ├── BaseController.php       # Base (helpers: form, url, auth, activity_log)
│   │   └── Api/                     # ⭐ 36 API controllers
│   ├── Filters/
│   │   ├── AdminAuthFilter.php      # Session auth + permission module check
│   │   └── Auth.php                 # JWT auth filter
│   ├── Helpers/
│   │   ├── permission_helper.php    # can(), canAny() — global via Autoload
│   │   ├── notification_helper.php  # sendFCMNotification(), sendFCMToMultiple()
│   │   ├── activity_log_helper.php
│   │   └── terbilang_helper.php
│   ├── Modules/                     # ⭐ 20 modul bisnis
│   │   └── {ModuleName}/
│   │       ├── Config/Routes.php          # Admin panel routes modul
│   │       ├── Config/Validations/*.php   # Validation trait
│   │       ├── Controllers/Admin/         # Admin panel controllers
│   │       ├── Models/
│   │       ├── Repositories/Contracts/    # Interfaces
│   │       ├── Repositories/
│   │       ├── Services/
│   │       └── Views/
│   ├── ThirdParty/
│   │   ├── php-jwt/src/             # JWT library (Firebase\JWT)
│   │   └── Midtrans/                # Payment gateway
│   ├── Validation/
│   │   └── UserRules.php            # Custom validation rules
│   └── Views/
│       ├── layout/
│       │   ├── app.php              # Layout utama admin panel (sidebar, navbar)
│       │   └── main.php             # Wrapper template
│       └── admin/                   # Semua view admin panel
└── system/                          # CI4 core (JANGAN DIUBAH)
```

---

## 15. Catatan Penting untuk AI

1. **`returnType = 'array'`** di semua Model — TIDAK pernah object
2. **JWT auth** bukan Shield JWT — pakai `ThirdParty/php-jwt` manual, secret key hardcoded di filter dan `.env`
3. **Auto-routing MATI** — semua route harus didefinisikan eksplisit
4. **`permission` helper global** via `Autoload.$helpers` — `can()` tersedia di mana saja tanpa load manual
5. **Validation trait** pattern — jangan tulis rule langsung di Validation.php, buat trait di modul
6. **FileRules non-strict** — pakai `CodeIgniter\Validation\FileRules` (bukan StrictRules) untuk upload
7. **Session 2 jam** — file-based, di `writable/session/`
8. **CSRF** — session-based, tidak aktif global (tidak ada di Filters globals)
9. **Construction** = modul paling kompleks — jadikan referensi struktur modul baru
10. **Format JSON response** — `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES` — karakter Indonesia aman
11. **CORS** belum dikonfigurasi — perlu tambahkan jika ada masalah cross-origin
12. **Ada file `Pager.php`** yang isinya class `Filters` (bukan Pager) — ini bug lama, abaikan dan gunakan `Filters.php`
13. **Firebase Service Account** ada di `writable/pasangin-c8050-firebase-adminsdk-fbsvc-547edad397.json`
14. **Database prefix = kosong** — nama tabel langsung tanpa prefix
15. **Tabel Shield tidak di-rename** — pakai nama default (`users`, `auth_identities`, dll.)
16. **Dua jalur controller** — admin panel di Modules, API di `app/Controllers/Api/`
17. **Email konfigurasi di `.env`** — `Email.php` hanya template kosong
18. **Translate URI ke CamelCase** aktif — URI `admin-controller` → class `AdminController`

---

## 16. Cara Menambah Fitur Baru

### Menambah Module Admin Baru:
1. Buat folder `app/Modules/{NamaModul}/` dengan struktur standar
2. Buat `Config/Routes.php`:
   ```php
   $routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\{NamaModul}\Controllers\Admin'], function($routes) {
       $routes->get('{modul}', '{Controller}::index');
   });
   ```
3. Daftarkan di `app/Config/Routes.php`:
   ```php
   if (file_exists(APPPATH . 'Modules/{NamaModul}/Config/Routes.php')) {
       require APPPATH . 'Modules/{NamaModul}/Config/Routes.php';
   }
   ```
4. Buat Validation trait di `Config/Validations/{NamaModul}Rules.php` → `use` di `Config/Validation.php`
5. Tambahkan permission baru di `AuthGroups.php` jika perlu

### Menambah API Endpoint Baru:
1. Buat/edit controller di `app/Controllers/Api/`
2. Tambahkan route di `app/Config/Routes.php`:
   - Tanpa auth → group `api` PERTAMA (sekitar baris 120)
   - Dengan auth → group `api` KEDUA dengan `'filter' => 'auth'` (sekitar baris 167)

### Menambah Model/Tabel Baru:
1. Buat migration di `app/Database/Migrations/` (format nama: `YYYY-MM-DD-HHmmss_{nama}.php`)
2. Buat `Model` di `Modules/{Modul}/Models/`
3. Buat `Interface` di `Modules/{Modul}/Repositories/Contracts/`
4. Buat `Repository` yang implements interface tersebut
5. Buat/update `Service` yang menggunakan Repository
6. Gunakan Service di Controller

### Menambah Validation Rule Baru:
1. Edit trait di `Modules/{Modul}/Config/Validations/{Modul}Rules.php`
   - Atau buat trait baru, lalu `use` di `Config/Validation.php`
2. Gunakan di controller: `$this->validate('ruleGroupName')`

---

## 17. Arsitektur Frontend & View Layer

Aplikasi backend ini memiliki *Admin Panel* dan antarmuka web yang terintegrasi langsung dengan core CodeIgniter 4 menggunakan sistem *Server-Side Rendering* (SSR). Semua file View berada di direktori `app/Views/` dan dipanggil dari Controller menggunakan `$this->view()` atau `view()`.

### 17.1 Templating System & Layouting
Aplikasi menggunakan **Stisla** (sebuah admin template open-source gratis berbasis Bootstrap 4, namun dalam project ini dikustomisasi menggunakan **Bootstrap 5.3.3**). Konsep layouting pada CodeIgniter 4 menggunakan *View Layouts* dengan `<?= $this->renderSection('content') ?>`.

Terdapat dua layout utama:
1. **`app/Views/layout/main.php`**
   - **Fungsi:** Layout dasar ("polos") untuk halaman publik, seperti laman *Login* atau *Register*.
   - **Komponen:** Tanpa Sidebar, tanpa Navbar kompleks. Hanya berisi *wrapper* aplikasi dan load script dasar.
2. **`app/Views/layout/app.php`** (atau `app/Views/admin/layout/app.php`)
   - **Fungsi:** Layout utama untuk *Dashboard Admin Panel*.
   - **Komponen:** Berisi kerangka *Navbar* atas, *Sidebar* menu navigasi kiri, logika Firebase Cloud Messaging (FCM) global untuk *Push Notification*, serta integrasi seluruh plugin JS dan file CSS kustom (`style.css` & `components.css`).

### 17.2 Struktur Folder View
Hierarki view dipecah secara rapi berdasarkan modul fungsional:
```text
app/Views/
├── admin/                     # Berisi semua view untuk antarmuka admin
│   ├── chat/                  # View untuk fitur perpesanan masuk
│   ├── construction/          # (index.php, absensi.php, rab.php, detail.php, dll)
│   ├── design/                # Serupa dengan construction, namun untuk fitur Desain
│   ├── renovation/            # Serupa dengan construction, untuk fitur Renovasi
│   ├── layout/                # Menyimpan layout master admin (app.php)
│   ├── notification/          # View untuk histori notifikasi admin
│   ├── orders/                # Manajemen pesanan e-commerce material
│   ├── products/              # Tabel produk, form tambah/edit barang
│   ├── supplier/              # Detail vendor, form pendaftaran (termasuk Auth)
│   ├── tukang/                # Manajemen database dan akun pekerja lapangan
│   └── users/                 # Tabel list pengguna akhir (klien)
├── errors/                    # Templating custom untuk halaman error (404, 500, dll)
├── layout/                    # Layout master non-admin (main.php)
```

### 17.3 Library Frontend & Integrasi JS/CSS

Aplikasi tidak menggunakan framework SPA seperti Vue atau React di bagian *admin panel*-nya, melainkan murni Vanilla JS dipadukan jQuery. Berikut daftar komponen eksternal yang esensial:

1. **Bootstrap 5.3.3 & jQuery 3.7.1**: Fondasi utama tata letak responsif dan DOM manipulation.
2. **FontAwesome 5.7.2**: Menangani icon pada navigasi sidebar dan tombol dashboard.
3. **DataTables (1.10.24 + Bootstrap 5 integration)**: Sangat krusial. Digunakan pada setiap halaman tipe `index.php` untuk menampilkan tabel dengan fitur *Client/Server-Side Pagination*, *Searching*, dan *Sorting*.
4. **Select2 (4.1.0-rc.0)**: Menyediakan elemen `<select>` dropdown interaktif dengan fitur pencarian teks di dalamnya (sangat berguna untuk dropdown pemilihan Kategori Produk atau Tukang yang datanya masif).
5. **SweetAlert2 11**: Menangani seluruh modal popup notifikasi aksi (misal: "Apakah Anda yakin ingin menghapus data ini?", atau alert berhasil simpan).
6. **iziToast 1.4.0**: Memunculkan notifikasi ringan *toast* di pojok layar (digunakan berbarengan dengan event FCM Notification).
7. **Chart.js (2.9.4)**: Untuk *rendering* diagram grafik dan statistik pada halaman *Dashboard Utama*.
8. **GLightbox**: Membantu fitur pembesaran gambar (lightbox slider) ketika user / admin mengeklik bukti pembayaran, foto profil, atau lampiran file survei.
9. **Ladda (1.0.6)**: Memodifikasi tombol Submit untuk memiliki indikator *loading spinner* agar form tidak diklik dua kali.
10. **Moment.js**: Melakukan pemformatan tanggal dan waktu yang manusiawi ("3 jam yang lalu", "Senin, 10 Mei 2026") di *frontend*, khususnya untuk logika antarmuka notifikasi navbar.

### 17.4 Integrasi Web Push Notification (Firebase FCM)
Bagian paling kompleks pada *view* ada di dalam script tag pada file layout admin (`app.php`). Secara native, file ini me-*load* **Firebase JS SDK (Compat version)**:
- Melakukan konfigurasi koneksi *real-time* ke project `pasangin-c8050`.
- Memiliki elemen `<div id="notif-force-overlay">` yang mencegah admin mengakses *dashboard* jika mereka belum memberikan otorisasi browser (`Notification.permission`).
- Mendaftarkan file `firebase-messaging-sw.js` (Service Worker) agar browser tetap bisa memunculkan popup OS native ketika *browser minimize*.
- Mengambil **FCM Token** dari *device* saat ini menggunakan fungsi `messaging.getToken()` dan mengirimkannya secara otomatis via AJAX ke endpoint backend (`/admin/notification/saveToken`).
- Mendengarkan event notifikasi secara *live* melalui `messaging.onMessage(payload)`. Saat pesan masuk, script akan memanggil `iziToast` dan me-*refresh* isi bel notifikasi (`#notif-badge`) di navbar menggunakan script asinkron `loadNavbarNotifications()`.

