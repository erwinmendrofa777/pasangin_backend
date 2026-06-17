<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class AgreementDocs
{
    #[OA\Get(
        path: "/api/syarat-ketentuan/{targetApp}",
        summary: "Mendapatkan Syarat & Ketentuan",
        description: "Mengambil data daftar syarat dan ketentuan (agreement) berdasarkan target aplikasi tertentu (misalnya 'client', 'tukang', atau 'supplier').",
        tags: ["Agreements & Terms"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "targetApp",
                in: "path",
                required: true,
                description: "Target aplikasi yang meminta syarat & ketentuan (contoh: client, tukang, supplier)",
                schema: new OA\Schema(type: "string", example: "client")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data syarat & ketentuan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "1"),
                                new OA\Property(property: "title", type: "string", example: "Kebijakan Privasi"),
                                new OA\Property(property: "description", type: "string", example: "Isi syarat dan ketentuan..."),
                                new OA\Property(property: "target_app", type: "string", example: "client")
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
    public function getTermsOfAgreement()
    {
    }

    #[OA\Post(
        path: "/api/construction/agreements/batch",
        summary: "Persetujuan Syarat & Ketentuan Konstruksi (Batch)",
        description: "Menyimpan persetujuan syarat & ketentuan untuk proyek konstruksi secara masal (batch).",
        tags: ["Agreements & Terms"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "construction_id", type: "integer", example: 12, description: "ID Proyek Konstruksi"),
                    new OA\Property(
                        property: "agreement_id", 
                        type: "array", 
                        items: new OA\Items(type: "integer"),
                        example: [1, 2, 3],
                        description: "Daftar ID syarat & ketentuan"
                    ),
                    new OA\Property(
                        property: "is_checked", 
                        type: "array", 
                        items: new OA\Items(type: "integer"),
                        example: [1, 1, 1],
                        description: "Daftar status centang persetujuan (1 untuk ya, 0 untuk tidak)"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Persetujuan batch berhasil ditambahkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data berhasil ditambahkan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "construction_id", type: "integer", example: 12),
                                new OA\Property(property: "agreement_id", type: "integer", example: 1),
                                new OA\Property(property: "is_checked", type: "integer", example: 1)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Format data tidak valid atau jumlah data tidak cocok."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token JWT tidak valid atau tidak disertakan."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error. Gagal memproses ke database."
            )
        ]
    )]
    public function constructionAgreementsBatch()
    {
    }

    #[OA\Post(
        path: "/api/renovation/agreements/batch",
        summary: "Persetujuan Syarat & Ketentuan Renovasi (Batch)",
        description: "Menyimpan persetujuan syarat & ketentuan untuk proyek renovasi secara masal (batch).",
        tags: ["Agreements & Terms"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "renovation_id", type: "integer", example: 5, description: "ID Proyek Renovasi"),
                    new OA\Property(
                        property: "agreement_id", 
                        type: "array", 
                        items: new OA\Items(type: "integer"),
                        example: [1, 2],
                        description: "Daftar ID syarat & ketentuan"
                    ),
                    new OA\Property(
                        property: "is_checked", 
                        type: "array", 
                        items: new OA\Items(type: "integer"),
                        example: [1, 1],
                        description: "Daftar status centang persetujuan (1 untuk ya, 0 untuk tidak)"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Persetujuan batch berhasil ditambahkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Data berhasil ditambahkan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "renovation_id", type: "integer", example: 5),
                                new OA\Property(property: "agreement_id", type: "integer", example: 1),
                                new OA\Property(property: "is_checked", type: "integer", example: 1)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Format data tidak valid atau jumlah data tidak cocok."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token JWT tidak valid atau tidak disertakan."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error. Gagal memproses ke database."
            )
        ]
    )]
    public function renovationAgreementsBatch()
    {
    }
}
