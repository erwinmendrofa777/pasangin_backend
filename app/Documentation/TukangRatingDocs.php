<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class TukangRatingDocs
{
    #[OA\Get(
        path: "/api/tukang/ratings/{id}",
        summary: "Ambil Daftar Ulasan/Rating Tukang",
        description: "Mengambil daftar seluruh ulasan, skor keahlian (skill score), dan skor perilaku (behavior score) yang diberikan oleh pelanggan kepada tukang tertentu berdasarkan ID Tukang.",
        tags: ["Tukang Ratings"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Tukang (tukang.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data ulasan berhasil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Data rating untuk tukang ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "id_tukang", type: "integer", example: 1),
                                new OA\Property(property: "target_id", type: "integer", example: 10, description: "ID Target Tugas/RAB terkait"),
                                new OA\Property(property: "project_type", type: "string", example: "construction", enum: ["construction", "renovation"]),
                                new OA\Property(property: "skill_score", type: "string", example: "5", description: "Nilai keahlian kerja (1-5)"),
                                new OA\Property(property: "behavior_score", type: "string", example: "4", description: "Nilai perilaku/sopan santun (1-5)"),
                                new OA\Property(property: "comment", type: "string", example: "Kerja sangat rapi dan orangnya sopan."),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 12:00:00", nullable: true)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang tidak boleh kosong."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal mengambil data rating tukang.")
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/tukang/ratings/create",
        summary: "Kirim Ulasan Tukang Proyek Konstruksi",
        description: "Mengirimkan ulasan dan nilai bintang untuk Tukang pada proyek konstruksi, menghitung ulang rating akumulasi pada profil Tukang, dan mengirim push notification pemberitahuan.",
        tags: ["Tukang Ratings"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id_tukang", type: "integer", example: 1, description: "ID Tukang yang diberi ulasan"),
                    new OA\Property(property: "target_id", type: "integer", example: 10, description: "ID Target Tugas/RAB konstruksi"),
                    new OA\Property(property: "skill_score", type: "integer", enum: [1, 2, 3, 4, 5], example: 5, description: "Skor keahlian (1-5)"),
                    new OA\Property(property: "behavior_score", type: "integer", enum: [1, 2, 3, 4, 5], example: 5, description: "Skor perilaku (1-5)"),
                    new OA\Property(property: "comment", type: "string", example: "Sangat ahli dalam memasang dinding bata merah.", description: "Komentar ulasan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ulasan konstruksi berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Rating tukang berhasil dibuat"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 2)
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi parameter input gagal."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan ulasan tukang.")
        ]
    )]
    public function createRatingTukangConstruction() {}

    #[OA\Post(
        path: "/api/tukang/ratings/create-renovation",
        summary: "Kirim Ulasan Tukang Proyek Renovasi",
        description: "Mengirimkan ulasan dan nilai bintang untuk Tukang pada proyek renovasi, menghitung ulang rating akumulasi pada profil Tukang, dan mengirim push notification pemberitahuan.",
        tags: ["Tukang Ratings"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id_tukang", type: "integer", example: 1, description: "ID Tukang yang diberi ulasan"),
                    new OA\Property(property: "target_id", type: "integer", example: 12, description: "ID Target Tugas/RAB renovasi"),
                    new OA\Property(property: "skill_score", type: "integer", enum: [1, 2, 3, 4, 5], example: 4, description: "Skor keahlian (1-5)"),
                    new OA\Property(property: "behavior_score", type: "integer", enum: [1, 2, 3, 4, 5], example: 5, description: "Skor perilaku (1-5)"),
                    new OA\Property(property: "comment", type: "string", example: "Pekerjaan cat rapi, bersih, dan cepat selesai.", description: "Komentar ulasan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ulasan renovasi berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Rating tukang berhasil dibuat"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 3)
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi parameter input gagal."),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan ulasan tukang.")
        ]
    )]
    public function createRatingTukangRenovation() {}
}
