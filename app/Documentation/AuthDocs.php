<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class AuthDocs
{
    #[OA\Post(
        path: "/api/login",
        summary: "Login Client",
        description: "Autentikasi akun client menggunakan nomor telepon dan password untuk mendapatkan token JWT.",
        tags: ["Authentication (Client)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "08123456789", description: "Nomor telepon terdaftar"),
                    new OA\Property(property: "password", type: "string", example: "password123", description: "Password akun"),
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_value", description: "Token FCM (Opsional, untuk push notification)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login berhasil, mengembalikan data user beserta JWT token.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Login berhasil."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal (nomor telepon/password kosong)."),
            new OA\Response(response: 401, description: "Password salah atau tidak sah."),
            new OA\Response(response: 403, description: "Akun belum disetujui (status bukan 'approved')."),
            new OA\Response(response: 404, description: "Nomor telepon tidak terdaftar.")
        ]
    )]
    public function login()
    {
    }

    #[OA\Post(
        path: "/api/register",
        summary: "Registrasi Client Baru",
        description: "Mendaftarkan akun client baru ke sistem.",
        tags: ["Authentication (Client)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe", description: "Nama lengkap"),
                    new OA\Property(property: "email", type: "string", example: "johndoe@example.com", description: "Alamat email unik"),
                    new OA\Property(property: "phone_number", type: "string", example: "081234567890", description: "Nomor telepon unik"),
                    new OA\Property(property: "password", type: "string", example: "password123", description: "Password minimal 8 karakter")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Registrasi berhasil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Registrasi berhasil.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal (email/nomor telepon sudah digunakan, atau format tidak valid)."),
            new OA\Response(response: 500, description: "Internal Server Error saat menyimpan data.")
        ]
    )]
    public function register()
    {
    }

    #[OA\Post(
        path: "/api/otp/request",
        summary: "Request OTP Registrasi/Login",
        description: "Meminta pengiriman OTP ke nomor HP via Verihubs untuk alur login/registrasi.",
        tags: ["Authentication (Client)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nomor_telepon", type: "string", example: "081234567890", description: "Nomor telepon tujuan"),
                    new OA\Property(property: "role", type: "string", example: "users", description: "Role target: users, tukang, suppliers"),
                    new OA\Property(property: "challenge", type: "string", example: "registrasi", description: "Tujuan pengiriman: login, registrasi")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "OTP berhasil dikirim ke nomor WhatsApp/SMS Anda.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 404, description: "Nomor HP tidak ditemukan (challenge = login)."),
            new OA\Response(response: 409, description: "Nomor HP sudah terdaftar (challenge = registrasi)."),
            new OA\Response(response: 500, description: "Koneksi ke Verihubs bermasalah.")
        ]
    )]
    public function requestOtp()
    {
    }

    #[OA\Post(
        path: "/api/otp/verify",
        summary: "Verifikasi OTP Registrasi/Login",
        description: "Memverifikasi kode OTP yang diterima pengguna via Verihubs.",
        tags: ["Authentication (Client)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nomor_telepon", type: "string", example: "081234567890", description: "Nomor telepon"),
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Kode OTP 6 digit"),
                    new OA\Property(property: "role", type: "string", example: "users", description: "Role target: users, tukang, suppliers"),
                    new OA\Property(property: "challenge", type: "string", example: "registrasi", description: "Tujuan pengiriman: login, registrasi")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP valid.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP valid."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "nomor_telepon", type: "string", example: "081234567890"),
                            new OA\Property(property: "role", type: "string", example: "users")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 401, description: "Kode OTP salah atau kadaluarsa.")
        ]
    )]
    public function verifyOtp()
    {
    }

    #[OA\Post(
        path: "/api/verify-email",
        summary: "Cek Ketersediaan Email",
        description: "Memeriksa apakah email sudah terdaftar di database sesuai rolenya.",
        tags: ["Authentication (Client)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "test@example.com", description: "Email yang ingin didaftarkan"),
                    new OA\Property(property: "role", type: "string", example: "users", description: "Target role: users, tukang, suppliers")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Email tersedia.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Email tersedia untuk digunakan.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 409, description: "Email sudah terdaftar.")
        ]
    )]
    public function verifyEmail()
    {
    }

    #[OA\Post(
        path: "/api/user/update-fcm",
        summary: "Update FCM Token",
        description: "Memperbarui FCM token milik user yang login untuk keperluan push notification.",
        tags: ["Authentication (Client)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_xyz_123", description: "Token FCM perangkat baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "FCM token berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "FCM token berhasil diperbarui.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "FCM token kosong."),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid.")
        ]
    )]
    public function updateFcmToken()
    {
    }
}
