<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierCategoryDocs
{
    #[OA\Get(
        path: "/api/supplier/categories",
        summary: "Ambil Daftar Kategori Supplier",
        description: "Mengambil seluruh kategori produk milik supplier yang saat ini login.",
        tags: ["Supplier Category (Kategori Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar kategori ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "list kategori supplier"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "1"),
                                new OA\Property(property: "supplier_id", type: "string", example: "10"),
                                new OA\Property(property: "name", type: "string", example: "Bahan Bangunan"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 13:44:54"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 13:44:54")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token JWT tidak valid atau bukan bertindak sebagai supplier."
            )
        ]
    )]
    public function index()
    {
    }

    #[OA\Post(
        path: "/api/supplier/categories",
        summary: "Tambah Kategori Baru",
        description: "Membuat kategori produk baru untuk supplier yang login.",
        tags: ["Supplier Category (Kategori Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Alat Pertukangan", description: "Nama kategori yang ingin dibuat")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Kategori berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kategori berhasil dibuat.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (Nama kategori kosong, terlalu pendek, atau terlalu panjang)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function create()
    {
    }

    #[OA\Put(
        path: "/api/supplier/categories/{id}",
        summary: "Update Kategori",
        description: "Memperbarui nama kategori produk milik supplier yang sedang login.",
        tags: ["Supplier Category (Kategori Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Kategori",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Kelistrikan & Kabel", description: "Nama kategori baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kategori berhasil diupdate.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kategori berhasil diupdate.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Kategori tidak ditemukan atau kategori bukan milik supplier yang sedang login."
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function update()
    {
    }

    #[OA\Delete(
        path: "/api/supplier/categories/{id}",
        summary: "Hapus Kategori",
        description: "Menghapus kategori produk milik supplier yang sedang login.",
        tags: ["Supplier Category (Kategori Supplier)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Kategori yang akan dihapus",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Kategori berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kategori berhasil dihapus.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Akses ditolak atau kategori tidak ditemukan."
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function delete()
    {
    }
}
