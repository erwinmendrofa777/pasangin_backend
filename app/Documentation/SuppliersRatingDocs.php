<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SuppliersRatingDocs
{
    #[OA\Get(
        path: "/api/supplier/ratings/{id}",
        summary: "Ambil Daftar Ulasan/Rating Supplier",
        description: "Mengambil daftar ulasan dan penilaian bintang (rating) yang diberikan oleh pelanggan kepada supplier tertentu berdasarkan ID Supplier.",
        tags: ["Supplier Ratings"],
        security: [
            ["bearerAuth" => []]
        ],
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
                description: "Data ulasan ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Data rating untuk supplier 1 ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "id_supplier", type: "integer", example: 1),
                                new OA\Property(property: "rating", type: "string", example: "5"),
                                new OA\Property(property: "comment", type: "string", example: "Pelayanan sangat cepat dan barang original."),
                                new OA\Property(property: "gambar1", type: "string", example: "http://localhost:8080/uploads/rating/rating_img1.jpg", nullable: true),
                                new OA\Property(property: "gambar2", type: "string", example: null, nullable: true),
                                new OA\Property(property: "gambar3", type: "string", example: null, nullable: true),
                                new OA\Property(property: "gambar4", type: "string", example: null, nullable: true),
                                new OA\Property(property: "gambar5", type: "string", example: null, nullable: true),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 12:00:00", nullable: true)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Supplier tidak boleh kosong."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal mengambil data rating supplier.")
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/supplier/ratings/create",
        summary: "Kirim Ulasan & Rating Supplier Baru",
        description: "Mengirimkan ulasan berupa penilaian rating (1-5 bintang), komentar, serta maksimal 5 gambar pendukung ulasan untuk supplier tertentu.",
        tags: ["Supplier Ratings"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "id_supplier", type: "integer", example: 1, description: "ID Supplier yang dinilai"),
                        new OA\Property(property: "rating", type: "integer", enum: [1, 2, 3, 4, 5], example: 5, description: "Bintang rating (1 s/d 5)"),
                        new OA\Property(property: "comment", type: "string", example: "Kualitas semen sangat bagus, pengiriman cepat.", description: "Komentar ulasan"),
                        new OA\Property(
                            property: "images[]",
                            type: "array",
                            description: "Gambar-gambar bukti/pendukung ulasan (Maksimal 5 file gambar)",
                            items: new OA\Items(type: "string", format: "binary")
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ulasan berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Rating supplier berhasil dibuat"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 4)
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal / melebihi 5 gambar."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan ulasan di database.")
        ]
    )]
    public function create() {}
}
