<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class AuthApiDocs
{
    #[OA\Post(
        path: "/api/forgot-password",
        summary: "Request OTP Lupa Password",
        description: "Mengirimkan permintaan kode OTP ke nomor WhatsApp/SMS melalui Verihubs untuk memulihkan password.",
        tags: ["Authentication & Recovery"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "msisdn", type: "string", example: "628123456789", description: "Nomor HP dengan format angka saja (gunakan 62)"),
                    new OA\Property(property: "role", type: "string", example: "user", description: "Tipe akun (user, tukang, atau supplier)")
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
            new OA\Response(
                response: 400,
                description: "Validasi gagal (parameter msisdn atau role salah)."
            ),
            new OA\Response(
                response: 404,
                description: "Nomor HP tidak terdaftar sebagai role yang dipilih."
            ),
            new OA\Response(
                response: 500,
                description: "Gagal terhubung ke layanan Verihubs."
            )
        ]
    )]
    public function requestOtp()
    {
    }

    #[OA\Post(
        path: "/api/verify-otp",
        summary: "Verifikasi Kode OTP",
        description: "Memverifikasi apakah kode OTP yang dimasukkan oleh pengguna cocok dan masih valid melalui Verihubs.",
        tags: ["Authentication & Recovery"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "msisdn", type: "string", example: "628123456789", description: "Nomor HP terdaftar"),
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Kode OTP 6-digit yang diterima"),
                    new OA\Property(property: "role", type: "string", example: "user", description: "Tipe akun (user, tukang, atau supplier)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kode OTP valid.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP valid. Silakan lanjutkan ke pembuatan password baru."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "msisdn", type: "string", example: "628123456789"),
                            new OA\Property(property: "role", type: "string", example: "user")
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi input gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Kode OTP salah atau sudah kadaluarsa."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function verifyOtp()
    {
    }

    #[OA\Post(
        path: "/api/reset-password",
        summary: "Reset Password Baru",
        description: "Mengubah password akun dengan password baru setelah proses validasi OTP database selesai.",
        tags: ["Authentication & Recovery"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com", description: "Alamat email terdaftar"),
                    new OA\Property(property: "otp", type: "string", example: "1234", description: "Kode token reset 4-digit dari database"),
                    new OA\Property(property: "role", type: "string", example: "user", description: "Tipe akun (user, tukang, atau supplier)"),
                    new OA\Property(property: "new_password", type: "string", example: "newpassword123", description: "Password baru minimal 6 karakter")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Password berhasil diubah.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Password untuk user berhasil diubah. Silakan login dengan password baru.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi input gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Kode OTP tidak valid/salah, atau sesi telah kadaluarsa (melewati 15 menit)."
            )
        ]
    )]
    public function resetPassword()
    {
    }

    #[OA\Post(
        path: "/api/forgot-password-email",
        summary: "Request OTP Lupa Password via Email",
        description: "Mengirimkan kode OTP 6-digit ke email pengguna untuk memulihkan password.",
        tags: ["Authentication & Recovery"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com", description: "Email terdaftar"),
                    new OA\Property(property: "role", type: "string", example: "user", description: "Tipe akun (user, tukang, atau supplier)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP berhasil dikirim via email.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP berhasil dikirim ke email Anda. Silakan periksa kotak masuk atau spam.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (parameter email atau role salah)."
            ),
            new OA\Response(
                response: 404,
                description: "Email tidak terdaftar sebagai role yang dipilih."
            ),
            new OA\Response(
                response: 500,
                description: "Gagal mengirim email OTP."
            )
        ]
    )]
    public function requestOtpByEmail()
    {
    }

    #[OA\Post(
        path: "/api/verify-otp-email",
        summary: "Verifikasi Kode OTP Email",
        description: "Memverifikasi apakah kode OTP email cocok dan belum kadaluarsa.",
        tags: ["Authentication & Recovery"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com", description: "Email terdaftar"),
                    new OA\Property(property: "otp", type: "string", example: "123456", description: "Kode OTP 6-digit yang diterima"),
                    new OA\Property(property: "role", type: "string", example: "user", description: "Tipe akun (user, tukang, atau supplier)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kode OTP valid.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Kode OTP valid. Silakan lanjutkan ke pembuatan password baru."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "email", type: "string", example: "user@example.com"),
                            new OA\Property(property: "role", type: "string", example: "user")
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi input gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Kode OTP salah atau sudah kadaluarsa."
            )
        ]
    )]
    public function verifyOtpByEmail()
    {
    }
}
