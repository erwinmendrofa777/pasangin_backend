<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class AboutApplicationDocs
{
    #[OA\Get(
        path: "/api/tentang-aplikasi",
        summary: "Mendapatkan Informasi Tentang Aplikasi Pasangin",
        description: "Mengambil data penjelasan/deskripsi mengenai aplikasi Pasangin yang disimpan di database.",
        tags: ["About Application"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data tentang aplikasi.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "1"),
                                new OA\Property(property: "description", type: "string", example: "<p>Tentang Aplikasi Pasangin...</p>"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 12:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token JWT tidak valid atau tidak disertakan."
            )
        ]
    )]
    public function getAboutApplicationPasangin()
    {
    }
}
