<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierBannerDocs
{
    #[OA\Get(
        path: "/api/supplier/banner",
        summary: "Ambil Daftar Banner Supplier",
        description: "Mengambil daftar seluruh banner iklan/promo yang diajukan oleh supplier yang sedang masuk.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar banner berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data banner berhasil dimuat."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/supplier/banner/create",
        summary: "Ajukan Banner Baru",
        description: "Supplier mengajukan banner promosi baru beserta unggahan file gambar banner (JPEG/PNG, maks 3MB). Status awal adalah 'PENDING'.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Promo Diskon Semen 10%", description: "Judul/Nama Banner"),
                        new OA\Property(property: "image", type: "string", format: "binary", description: "File gambar banner (JPEG, JPG, PNG, maks 3MB)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Banner berhasil diajukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Banner supplier berhasil diajukan."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function create() {}

    #[OA\Get(
        path: "/api/supplier/banner/show/{id}",
        summary: "Ambil Detail Banner Supplier",
        description: "Mengambil detail data satu banner berdasarkan ID banner.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Banner",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail banner ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Banner tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: "/api/supplier/banner/approved",
        summary: "Ambil Daftar Banner yang Disetujui (Client)",
        description: "Mengambil daftar seluruh banner supplier yang berstatus 'APPROVED' dan berada pada rentang waktu aktif (start_date s.d. end_date) untuk ditampilkan di aplikasi klien.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar banner disetujui ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Banner berhasil dimuat."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getApprovedBanners() {}

    #[OA\Post(
        path: "/api/supplier/banner/update/{id}",
        summary: "Perbarui Banner Supplier",
        description: "Memperbarui data judul banner atau file gambar banner. Status banner akan otomatis di-reset menjadi 'PENDING' kembali agar dapat di-review ulang oleh Admin.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Banner",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Promo Diskon Besi Beton 5%", description: "Judul Baru Banner"),
                        new OA\Property(property: "image", type: "string", format: "binary", nullable: true, description: "File gambar banner baru (Opsional)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Banner berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Banner supplier berhasil diperbarui."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Banner tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function update() {}

    #[OA\Post(
        path: "/api/supplier/banner/delete/{id}",
        summary: "Hapus Banner Supplier",
        description: "Menghapus data banner supplier beserta file gambar fisiknya dari server.",
        tags: ["Supplier Banner"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Banner",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Banner berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Banner supplier berhasil dihapus.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Banner tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function delete() {}
}
