<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class TukangAuthDocs
{
    #[OA\Post(
        path: "/api/tukang/login",
        summary: "Login Tukang",
        description: "Melakukan otentikasi akun Tukang menggunakan nomor telepon dan kata sandi. Mengembalikan token JWT untuk autentikasi API privat.",
        tags: ["Authentication (Tukang)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "081234567890", description: "Nomor telepon Tukang"),
                    new OA\Property(property: "password", type: "string", example: "password123", description: "Kata sandi"),
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_example_123", nullable: true, description: "Token FCM perangkat untuk notifikasi push (Opsional)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login berhasil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Login Tukang berhasil."),
                        new OA\Property(property: "data", type: "object", description: "Data detail Tukang beserta token JWT"),
                        new OA\Property(property: "is_notification_enabled", type: "integer", example: 1, description: "Status notifikasi aktif")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 401, description: "Password salah."),
            new OA\Response(response: 404, description: "Nomor telepon Tukang tidak terdaftar.")
        ]
    )]
    public function login() {}

    #[OA\Post(
        path: "/api/tukang/register",
        summary: "Registrasi Tukang Baru",
        description: "Mendaftarkan akun Tukang baru ke dalam sistem. Status awal akun akan diatur menjadi 'Berkas Diproses'.",
        tags: ["Authentication (Tukang)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Joko Susilo", description: "Nama Lengkap"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "joko@example.com", description: "Alamat email unik"),
                    new OA\Property(property: "phone", type: "string", example: "081234567890", description: "Nomor telepon unik"),
                    new OA\Property(property: "password", type: "string", example: "securepassword123", description: "Kata sandi minimal 8 karakter"),
                    new OA\Property(property: "agent_code", type: "string", example: "AGT001", nullable: true, description: "Kode Agen (Opsional)"),
                    new OA\Property(property: "gender", type: "string", example: "Laki-laki", nullable: true),
                    new OA\Property(property: "dob", type: "string", format: "date", example: "1990-05-15", nullable: true, description: "Tanggal lahir"),
                    new OA\Property(property: "ktp_address", type: "string", example: "Jl. Mawar No. 12", nullable: true),
                    new OA\Property(property: "domicile_address", type: "string", example: "Jl. Melati No. 8", nullable: true),
                    new OA\Property(property: "specialization", type: "string", example: "Tukang Kayu", nullable: true, description: "Spesialisasi keahlian")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Pendaftaran berhasil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Pendaftaran berhasil. Silakan login.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal (email/phone sudah terdaftar)."),
            new OA\Response(response: 500, description: "Gagal memproses pendaftaran.")
        ]
    )]
    public function register() {}

    #[OA\Post(
        path: "/api/tukang/verify",
        summary: "Verifikasi Biometrik & Ekstrak KTP (Verihubs)",
        description: "Membandingkan foto wajah selfie dengan foto di KTP menggunakan API Verihubs (Face Match). Jika cocok, data teks di KTP akan diekstraksi secara otomatis (OCR KTP).",
        tags: ["Authentication (Tukang)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "ktp_image", type: "string", format: "binary", description: "Foto KTP fisik"),
                        new OA\Property(property: "face_image", type: "string", format: "binary", description: "Foto wajah selfie terbaru")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Verifikasi wajah cocok dan data KTP berhasil diekstraksi.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Wajah cocok dan KTP berhasil diekstrak."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "compare_status", type: "integer", example: 200),
                            new OA\Property(property: "is_match", type: "boolean", example: true),
                            new OA\Property(property: "ktp_data", type: "object", description: "Data hasil ekstraksi KTP OCR")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Verifikasi wajah gagal (tidak cocok) atau file input tidak valid."),
            new OA\Response(response: 500, description: "Gagal terhubung ke API Verihubs / Kesalahan server.")
        ]
    )]
    public function extractSync() {}

    #[OA\Post(
        path: "/api/tukang/update-fcm",
        summary: "Perbarui Token FCM Tukang",
        description: "Memperbarui token Firebase Cloud Messaging (FCM) pada akun Tukang yang sedang login untuk menerima push notifikasi.",
        tags: ["Authentication (Tukang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_example_123", description: "Token FCM baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Token FCM berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Token FCM Tukang berhasil diperbarui."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Token FCM kosong."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function updateFcmToken() {}

    #[OA\Post(
        path: "/api/tukang/update-profile",
        summary: "Perbarui Profil Dasar Tukang",
        description: "Memperbarui informasi nama, nomor telepon, email, spesialisasi, kata sandi, serta file foto profil Tukang.",
        tags: ["Authentication (Tukang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Joko Susilo Updated"),
                        new OA\Property(property: "phone", type: "string", example: "081234567890"),
                        new OA\Property(property: "email", type: "string", format: "email", example: "joko_new@example.com"),
                        new OA\Property(property: "specialization", type: "string", example: "Tukang Cat"),
                        new OA\Property(property: "password", type: "string", nullable: true, description: "Kata sandi baru (opsional)"),
                        new OA\Property(property: "profile_photo", type: "string", format: "binary", nullable: true, description: "File foto profil baru")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profil berhasil diperbarui!")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan perubahan profil.")
        ]
    )]
    public function updateProfile() {}

    #[OA\Post(
        path: "/api/tukang/update-ktp",
        summary: "Perbarui Profil Tukang Lewat Verifikasi KTP",
        description: "Memperbarui NIK, Nama sesuai KTP, Tanggal Lahir, Alamat lengkap berdasarkan data KTP, serta mengunggah foto selfie verifikasi.",
        tags: ["Authentication (Tukang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "nik", type: "string", example: "3275012345678901", description: "Nomor NIK"),
                        new OA\Property(property: "nama", type: "string", example: "Joko Susilo"),
                        new OA\Property(property: "tanggal_lahir", type: "string", format: "date", example: "1990-05-15"),
                        new OA\Property(property: "jalan", type: "string", example: "Jl. Mawar No. 12"),
                        new OA\Property(property: "rt_rw", type: "string", example: "002/005"),
                        new OA\Property(property: "kelurahan", type: "string", example: "Margahayu"),
                        new OA\Property(property: "kecamatan", type: "string", example: "Bekasi Timur"),
                        new OA\Property(property: "kabupaten", type: "string", example: "Bekasi"),
                        new OA\Property(property: "provinsi", type: "string", example: "Jawa Barat"),
                        new OA\Property(property: "selfie_photo", type: "string", format: "binary", nullable: true, description: "Foto selfie verifikasi")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil KTP berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profil berhasil diperbarui")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan perubahan data KTP.")
        ]
    )]
    public function updateProfileByKtp() {}

    #[OA\Get(
        path: "/api/tukang/profile/{id}",
        summary: "Ambil Profil Tukang",
        description: "Mengambil data profil Tukang yang terdaftar di sistem berdasarkan ID Tukang.",
        tags: ["Authentication (Tukang)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Tukang (tukang.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil Tukang ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", description: "Informasi profil lengkap Tukang")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Tukang tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getProfile() {}
}
