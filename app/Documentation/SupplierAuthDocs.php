<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierAuthDocs
{
    #[OA\Post(
        path: "/api/supplier/login",
        summary: "Login Supplier",
        description: "Melakukan otentikasi akun Supplier menggunakan nomor telepon dan kata sandi. Mengembalikan token JWT untuk autentikasi API privat.",
        tags: ["Authentication (Supplier)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "081234567890", description: "Nomor telepon Supplier"),
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
                        new OA\Property(property: "message", type: "string", example: "Login Supplier berhasil."),
                        new OA\Property(property: "data", type: "object", description: "Data detail supplier beserta token JWT")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Password salah."
            ),
            new OA\Response(
                response: 403,
                description: "Akun dinonaktifkan/banned, atau pendaftaran ditolak oleh admin."
            ),
            new OA\Response(
                response: 404,
                description: "Nomor telepon Supplier tidak terdaftar."
            )
        ]
    )]
    public function login() {}

    #[OA\Post(
        path: "/api/supplier/register",
        summary: "Registrasi Supplier Baru",
        description: "Mendaftarkan akun Supplier/Toko baru. Status awal akun akan menjadi 'pending' dan memerlukan persetujuan dari Admin.",
        tags: ["Authentication (Supplier)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Toko Material Abadi", description: "Nama Toko / Supplier"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "abadi@example.com", description: "Alamat email unik"),
                    new OA\Property(property: "phone", type: "string", example: "081234567890", description: "Nomor telepon unik"),
                    new OA\Property(property: "password", type: "string", example: "securepassword123", description: "Kata sandi minimal 8 karakter"),
                    new OA\Property(property: "contact_person", type: "string", example: "Budi Utomo", description: "Nama Penanggung Jawab / Kontak Person"),
                    new OA\Property(property: "address", type: "string", example: "Jl. Raya Industri No. 45", description: "Alamat lengkap"),
                    new OA\Property(property: "province", type: "string", example: "Jawa Barat", description: "Provinsi"),
                    new OA\Property(property: "city", type: "string", example: "Bekasi", description: "Kota/Kabupaten"),
                    new OA\Property(property: "district", type: "string", example: "Cikarang Selatan", description: "Kecamatan")
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
                        new OA\Property(property: "message", type: "string", example: "Pendaftaran Supplier berhasil. Silakan login.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (email/phone sudah terdaftar, panjang input dsb)."
            )
        ]
    )]
    public function register() {}

    #[OA\Post(
        path: "/api/supplier/change-password",
        summary: "Ubah Kata Sandi Supplier",
        description: "Mengubah kata sandi akun Supplier yang sedang masuk.",
        tags: ["Authentication (Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "old_password", type: "string", example: "passwordlama123", description: "Kata sandi saat ini"),
                    new OA\Property(property: "new_password", type: "string", example: "passwordbaru123", description: "Kata sandi baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kata sandi berhasil diubah.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Password berhasil diubah!")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Kata sandi lama salah."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function changePassword() {}

    #[OA\Get(
        path: "/api/supplier/profile/{id}",
        summary: "Ambil Profil Publik Supplier",
        description: "Mengambil data profil publik dari Supplier berdasarkan ID, termasuk statistik total produk, jumlah pesanan, dan rating ulasan.",
        tags: ["Authentication (Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Supplier",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profil publik supplier ditemukan"),
                        new OA\Property(property: "data", type: "object", description: "Informasi publik toko supplier")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Supplier tidak ditemukan."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function getProfile() {}

    #[OA\Post(
        path: "/api/supplier/update-profile",
        summary: "Perbarui Profil Supplier",
        description: "Memperbarui data detail profil toko/supplier termasuk nama toko, kontak person, nomor telepon, alamat lengkap, koordinat peta, data NIK, kata sandi, serta berkas logo toko.",
        tags: ["Authentication (Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Toko Material Abadi Baru", description: "Nama Toko / Supplier"),
                        new OA\Property(property: "contact_person", type: "string", example: "Budi Utomo", description: "Contact person"),
                        new OA\Property(property: "phone", type: "string", example: "081234567890"),
                        new OA\Property(property: "email", type: "string", format: "email", example: "abadi@example.com"),
                        new OA\Property(property: "address", type: "string", example: "Jl. Raya Industri No. 45"),
                        new OA\Property(property: "province", type: "string", example: "Jawa Barat"),
                        new OA\Property(property: "city", type: "string", example: "Bekasi"),
                        new OA\Property(property: "district", type: "string", example: "Cikarang Selatan"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456"),
                        new OA\Property(property: "is_verify", type: "integer", example: 1, description: "Status verifikasi dokumen"),
                        new OA\Property(property: "nik", type: "string", example: "3275012345678901", description: "Nomor Induk Kependudukan"),
                        new OA\Property(property: "password", type: "string", nullable: true, description: "Kata sandi baru (kosongkan jika tidak diganti)"),
                        new OA\Property(property: "logo_url", type: "string", format: "binary", nullable: true, description: "File foto logo toko baru")
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
                        new OA\Property(property: "message", type: "string", example: "Profil Toko berhasil diperbarui!"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized / Token tidak valid."
            )
        ]
    )]
    public function updateProfile() {}

    #[OA\Post(
        path: "/api/supplier/update-fcm",
        summary: "Perbarui Token FCM Supplier",
        description: "Memperbarui token Firebase Cloud Messaging (FCM) pada akun Supplier yang aktif untuk menerima push notifikasi.",
        tags: ["Authentication (Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_token_example_123", description: "Token FCM Baru")
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
                        new OA\Property(property: "message", type: "string", example: "Token FCM diperbarui."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "FCM Token kosong."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function updateFcmToken() {}
}
