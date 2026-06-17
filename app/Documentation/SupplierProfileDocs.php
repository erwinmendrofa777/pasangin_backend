<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierProfileDocs
{
    #[OA\Get(
        path: "/api/supplier/public-profile/{id}",
        summary: "Ambil Profil Publik Supplier (Tanpa Login)",
        description: "Mengambil data profil publik dari Supplier berdasarkan ID Supplier untuk konsumsi publik (tanpa token autentikasi), menggabungkan tahun berdiri, total produk, jumlah pesanan, rata-rata rating, dan total ulasan.",
        tags: ["Authentication (Supplier)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Supplier (suppliers.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil publik supplier ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profil publik supplier ditemukan"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Toko Material Abadi"),
                            new OA\Property(property: "email", type: "string", example: "abadi@example.com"),
                            new OA\Property(property: "phone", type: "string", example: "081234567890"),
                            new OA\Property(property: "contact_person", type: "string", example: "Budi Utomo"),
                            new OA\Property(property: "address", type: "string", example: "Jl. Raya Industri No. 45"),
                            new OA\Property(property: "province", type: "string", example: "Jawa Barat"),
                            new OA\Property(property: "city", type: "string", example: "Bekasi"),
                            new OA\Property(property: "district", type: "string", example: "Cikarang Selatan"),
                            new OA\Property(property: "latitude", type: "string", example: "-6.2088", nullable: true),
                            new OA\Property(property: "longitude", type: "string", example: "106.8456", nullable: true),
                            new OA\Property(property: "logo_url", type: "string", example: "random_logo.png", nullable: true),
                            new OA\Property(property: "is_active", type: "integer", example: 1),
                            new OA\Property(property: "is_verify", type: "integer", example: 1),
                            new OA\Property(property: "status", type: "string", example: "approved"),
                            new OA\Property(property: "nik", type: "string", example: "3275012345678901", nullable: true),
                            new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                            new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 12:00:00", nullable: true),
                            new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/supplier/random_logo.png", nullable: true),
                            new OA\Property(property: "tahun_berdiri", type: "string", example: "2026"),
                            new OA\Property(property: "total_produk", type: "integer", example: 10),
                            new OA\Property(property: "jumlah_pesanan", type: "integer", example: 15),
                            new OA\Property(property: "rata_rata_rating", type: "number", format: "float", example: 4.8),
                            new OA\Property(property: "total_ulasan", type: "integer", example: 12)
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Supplier tidak boleh kosong / format salah."),
            new OA\Response(response: 404, description: "Supplier tidak ditemukan.")
        ]
    )]
    public function index() {}
}
