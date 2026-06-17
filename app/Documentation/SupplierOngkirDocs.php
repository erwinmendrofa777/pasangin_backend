<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierOngkirDocs
{
    #[OA\Post(
        path: "/api/supplier/ongkir/create",
        summary: "Buat Konfigurasi Ongkir Supplier Baru",
        description: "Menambahkan aturan/konfigurasi tarif ongkos kirim (ongkir) supplier berdasarkan jarak tertentu.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "ongkir", type: "number", example: 15000, description: "Tarif ongkos kirim (Rp)"),
                    new OA\Property(property: "min_distance", type: "number", format: "float", example: 0.0, description: "Jarak minimum (km)"),
                    new OA\Property(property: "max_distance", type: "number", format: "float", example: 10.0, description: "Jarak maksimum (km)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ongkir berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier berhasil dibuat"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 401, description: "Unauthorized / Supplier tidak ditemukan."),
            new OA\Response(response: 500, description: "Gagal membuat ongkir supplier.")
        ]
    )]
    public function create() {}

    #[OA\Post(
        path: "/api/supplier/ongkir/update/{id}",
        summary: "Perbarui Konfigurasi Ongkir Supplier",
        description: "Memperbarui data tarif ongkir, jarak minimum, atau jarak maksimum pada baris data ongkir yang ditentukan.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Baris Ongkir (supplier_ongkir.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "ongkir", type: "number", example: 20000, description: "Tarif ongkir baru"),
                    new OA\Property(property: "min_distance", type: "number", format: "float", example: 0.0),
                    new OA\Property(property: "max_distance", type: "number", format: "float", example: 15.0)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ongkir berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier berhasil diupdate"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 500, description: "Gagal mengupdate ongkir.")
        ]
    )]
    public function update() {}

    #[OA\Post(
        path: "/api/supplier/ongkir/delete/{id}",
        summary: "Hapus Konfigurasi Ongkir Supplier",
        description: "Menghapus data baris aturan tarif ongkir supplier berdasarkan ID.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Baris Ongkir",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ongkir berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier berhasil dihapus.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Ongkir supplier tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function delete() {}

    #[OA\Get(
        path: "/api/supplier/ongkir/get",
        summary: "Ambil Ongkir Supplier Aktif",
        description: "Mengambil konfigurasi tarif ongkir milik supplier yang sedang login saat ini.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ongkir supplier ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier ditemukan."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Ongkir supplier tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getOngkirByIdSupplier() {}

    #[OA\Get(
        path: "/api/supplier/ongkir/show/{id_supplier}",
        summary: "Ambil Ongkir Berdasarkan ID Supplier",
        description: "Mengambil data konfigurasi tarif ongkir untuk supplier spesifik berdasarkan ID Supplier.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id_supplier",
                in: "path",
                description: "ID Supplier",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ongkir supplier ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier ditemukan."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Ongkir supplier tidak ditemukan.")
        ]
    )]
    public function showOngkirByIdSupplier() {}

    #[OA\Get(
        path: "/api/supplier/ongkir",
        summary: "Ambil Seluruh Daftar Ongkir Supplier",
        description: "Mengambil daftar seluruh konfigurasi tarif ongkir supplier yang terdaftar di dalam sistem.",
        tags: ["Supplier Shipping Fee"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar ongkir ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Ongkir supplier ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Ongkir supplier tidak ditemukan.")
        ]
    )]
    public function getAllOngkir() {}
}
