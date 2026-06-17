<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class TukangContentDocs
{
    #[OA\Get(
        path: "/api/tukang/banners",
        summary: "Ambil Data Banner Khusus Tukang",
        description: "Mengambil daftar banner promosi atau pengumuman aktif yang ditargetkan khusus untuk aplikasi Tukang.",
        tags: ["Tukang Content"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Banner tukang berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Banner tukang berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Promo Layanan Tukang Baru"),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/banners/banner_tukang.jpg", nullable: true)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal mengambil data banner.")
        ]
    )]
    public function banners() {}

    #[OA\Get(
        path: "/api/tukang/tips",
        summary: "Ambil Data Tips Khusus Tukang",
        description: "Mengambil daftar artikel, petunjuk, atau tips konstruksi dan pengerjaan fisik aktif terbaru untuk dipelajari oleh Tukang.",
        tags: ["Tukang Content"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tips tukang berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tips tukang berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Teknik Plester Tembok Sempurna"),
                                new OA\Property(property: "content", type: "string", example: "Berikut langkah-langkah plester tembok agar rata dan halus..."),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/tips/plester_tembok.jpg", nullable: true)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal mengambil data tips.")
        ]
    )]
    public function tips() {}
}
