<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class UserProfileDocs
{
    #[OA\Post(
        path: "/api/user/update",
        summary: "Perbarui Profil Pengguna",
        description: "Memperbarui informasi profil pengguna yang sedang login (nama, email, nomor telepon, alamat, foto avatar, NIK terenkripsi, ubah kata sandi, dan FCM token). Endpoint ini terproteksi JWT.",
        tags: ["User Profile & Account"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "full_name", type: "string", example: "Budi Santoso", description: "Nama lengkap baru"),
                        new OA\Property(property: "email", type: "string", example: "budi.santoso@example.com", description: "Alamat email baru"),
                        new OA\Property(property: "phone_number", type: "string", example: "081234567890", description: "Nomor telepon baru"),
                        new OA\Property(property: "address", type: "string", example: "Jl. Merdeka No. 10, Jakarta", description: "Alamat tempat tinggal"),
                        new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_client_123", description: "Token FCM baru untuk notifikasi"),
                        new OA\Property(property: "nik", type: "string", example: "3171012345670001", description: "NIK baru (akan dienkripsi di server)"),
                        new OA\Property(property: "old_password", type: "string", example: "passwordlama123", description: "Kata sandi lama (wajib jika ingin mengubah kata sandi)"),
                        new OA\Property(property: "new_password", type: "string", example: "passwordbaru123", description: "Kata sandi baru (minimal 8 karakter)"),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "File foto profil avatar (JPEG/PNG)")
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
                        new OA\Property(property: "message", type: "string", example: "Data berhasil diperbarui secara permanen."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 5),
                            new OA\Property(property: "nik", type: "string", example: "d3NhZGFzZGFzZA=="),
                            new OA\Property(property: "full_name", type: "string", example: "Budi Santoso"),
                            new OA\Property(property: "email", type: "string", example: "budi.santoso@example.com"),
                            new OA\Property(property: "phone_number", type: "string", example: "081234567890"),
                            new OA\Property(property: "gender", type: "string", example: "L", nullable: true),
                            new OA\Property(property: "birth_date", type: "string", example: "1995-10-12", nullable: true),
                            new OA\Property(property: "address", type: "string", example: "Jl. Merdeka No. 10, Jakarta"),
                            new OA\Property(property: "role", type: "string", example: "client"),
                            new OA\Property(property: "status", type: "string", example: "approved"),
                            new OA\Property(property: "avatar", type: "string", example: "http://localhost:8080/uploads/profile/random_name.png"),
                            new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_client_123"),
                            new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                            new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 15:30:00")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Token tidak ditemukan, atau kata sandi lama wajib diisi/salah."),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid."),
            new OA\Response(response: 404, description: "User tidak ditemukan."),
            new OA\Response(response: 500, description: "Gagal memperbarui data ke database.")
        ]
    )]
    public function update() {}

    #[OA\Post(
        path: "/api/user/request-otp",
        summary: "Minta OTP Hapus Akun",
        description: "Meminta pengiriman kode OTP 6 digit ke alamat email pengguna terdaftar untuk konfirmasi proses penghapusan akun.",
        tags: ["User Profile & Account"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "client@example.com", description: "Alamat email terdaftar")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kode OTP berhasil terkirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP telah dikirim ke email client@example.com. Berlaku selama 10 menit.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Akun tidak ditemukan."),
            new OA\Response(response: 500, description: "Gagal mengirim email OTP.")
        ]
    )]
    public function requestOtp() {}

    #[OA\Post(
        path: "/api/user/verify-otp",
        summary: "Verifikasi OTP Hapus Akun",
        description: "Memverifikasi apakah kode OTP yang dimasukkan untuk menghapus akun adalah kode yang valid dan belum kedaluwarsa.",
        tags: ["User Profile & Account"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "client@example.com", description: "Alamat email terdaftar"),
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Kode OTP 6 digit yang diterima di email")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kode OTP berhasil terverifikasi.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP berhasil terkonfirmasi.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Kode OTP tidak valid, salah, atau sudah kadaluarsa."),
            new OA\Response(response: 404, description: "Akun tidak ditemukan.")
        ]
    )]
    public function verifyOtp() {}

    #[OA\Post(
        path: "/api/user/inactivate-account/confirm",
        summary: "Nonaktifkan Akun Mandiri",
        description: "Menonaktifkan akun sendiri secara sementara dengan mengubah status akun menjadi 'nonaktif'. Endpoint ini terproteksi JWT.",
        tags: ["User Profile & Account"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Akun berhasil dinonaktifkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Akun Anda telah berhasil dinonaktifkan.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Token tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function confirmInactivateAccount() {}

    #[OA\Post(
        path: "/api/user/activate-account/confirm",
        summary: "Aktifkan Kembali Akun (Recovery)",
        description: "Mengaktifkan kembali akun yang dinonaktifkan dengan mengubah status akun menjadi 'approved'.",
        tags: ["User Profile & Account"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "client@example.com", description: "Alamat email terdaftar")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Akun berhasil diaktifkan kembali.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Akun Anda telah berhasil diaktifkan.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Akun tidak ditemukan.")
        ]
    )]
    public function confirmActivateAccount() {}

    #[OA\Post(
        path: "/api/user/delete-account/confirm",
        summary: "Hapus Akun Permanen (Execute)",
        description: "Menghapus akun secara permanen beserta seluruh data terkait secara bertingkat (cascade delete) seperti obrolan/pesan, alamat, notifikasi, proyek konstruksi, renovasi, desain, pesanan toko, dan data akun utama. Endpoint ini terproteksi JWT.",
        tags: ["User Profile & Account"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Akun dan semua data terkait berhasil dihapus secara permanen.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Akun Anda dan semua data terkait telah berhasil dihapus secara permanen.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Token tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 404, description: "Akun tidak ditemukan."),
            new OA\Response(response: 500, description: "Terjadi kesalahan saat menghapus data.")
        ]
    )]
    public function confirmDeleteAccount() {}
}
