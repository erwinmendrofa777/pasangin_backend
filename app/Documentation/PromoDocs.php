<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class PromoDocs
{
    #[OA\Get(
        path: "/api/supplier/promos",
        summary: "Ambil Daftar Promo Saya (Khusus Supplier)",
        description: "Mengambil daftar seluruh promo yang dibuat oleh supplier yang saat ini sedang login.",
        tags: ["Promos (Promo)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar promo supplier berhasil ditemukan.",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "string", example: "5"),
                            new OA\Property(property: "supplier_id", type: "string", example: "2"),
                            new OA\Property(property: "title", type: "string", example: "Promo Diskon Besi 10%"),
                            new OA\Property(property: "description", type: "string", nullable: true, example: "Diskon untuk seluruh jenis produk besi beton."),
                            new OA\Property(property: "discount_type", type: "string", example: "percentage"),
                            new OA\Property(property: "discount_value", type: "string", example: "10"),
                            new OA\Property(property: "promo_code", type: "string", nullable: true, example: "BESI10"),
                            new OA\Property(property: "start_date", type: "string", example: "2026-06-01"),
                            new OA\Property(property: "end_date", type: "string", example: "2026-06-30"),
                            new OA\Property(property: "status", type: "string", example: "active"),
                            new OA\Property(property: "photo", type: "string", nullable: true, example: "promo_iron.png"),
                            new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00"),
                            new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 14:00:00")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function index()
    {
    }

    #[OA\Get(
        path: "/api/supplier/promos/show/{supplier_id}",
        summary: "Ambil Daftar Promo Supplier Tertentu",
        description: "Mengambil daftar promo yang dibuat oleh supplier tertentu berdasarkan ID supplier.",
        tags: ["Promos (Promo)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "supplier_id",
                in: "path",
                description: "ID Supplier",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar promo supplier berhasil ditemukan.",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Promo supplier tidak ditemukan."
            )
        ]
    )]
    public function show()
    {
    }

    #[OA\Get(
        path: "/api/supplier/promos/all",
        summary: "Ambil Semua Daftar Promo Di Sistem",
        description: "Mengambil seluruh daftar promo dari semua supplier yang terdaftar di sistem.",
        tags: ["Promos (Promo)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Seluruh daftar promo berhasil diambil.",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function getAllPromo()
    {
    }

    #[OA\Post(
        path: "/api/supplier/promos/create",
        summary: "Tambah Promo Baru (Khusus Supplier)",
        description: "Membuat promo baru dengan menyertakan banner promosi (photo).",
        tags: ["Promos (Promo)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["title", "discount_value"],
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Promo Gajian", description: "Judul Promo"),
                        new OA\Property(property: "description", type: "string", example: "Promo gajian khusus akhir bulan.", description: "Keterangan detail promo"),
                        new OA\Property(property: "discount_type", type: "string", enum: ["percentage", "nominal"], default: "percentage", description: "Tipe diskon (persentase / nilai nominal)"),
                        new OA\Property(property: "discount_value", type: "number", format: "float", example: 10, description: "Besar nilai diskon"),
                        new OA\Property(property: "promo_code", type: "string", example: "GAJIAN10", description: "Kode promo u/ klaim (opsional)"),
                        new OA\Property(property: "start_date", type: "string", format: "date", example: "2026-06-25", description: "Tanggal mulai promo (YYYY-MM-DD)"),
                        new OA\Property(property: "end_date", type: "string", format: "date", example: "2026-06-30", description: "Tanggal akhir promo (YYYY-MM-DD)"),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Gambar banner promo")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Promo berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Promo berhasil dibuat.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function create()
    {
    }

    #[OA\Post(
        path: "/api/supplier/promos/delete/{id}",
        summary: "Hapus Promo (Khusus Supplier)",
        description: "Menghapus promo yang dibuat oleh supplier berdasarkan ID promo.",
        tags: ["Promos (Promo)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Promo",
                required: true,
                schema: new OA\Schema(type: "integer", example: 5)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Promo berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Promo dihapus.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Promo tidak ditemukan atau bukan milik supplier yang sedang login."
            )
        ]
    )]
    public function delete()
    {
    }
}
