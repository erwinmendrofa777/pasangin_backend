<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class ContractDocs
{
    #[OA\Get(
        path: "/api/construction/contract/{id}",
        summary: "Ambil Data Kontrak Konstruksi",
        description: "Mengambil data detail surat perjanjian kontrak pembangunan/konstruksi fisik berdasarkan ID pengajuan konstruksi, termasuk link file PDF kontrak yang digenerate dan rincian pekerjaan termin RAB.",
        tags: ["Contract (Kontrak)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Proyek Konstruksi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data kontrak konstruksi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "contract_number", type: "string", example: "KTR-2026-1"),
                            new OA\Property(property: "status", type: "string", example: "complete", enum: ["complete", "pending"]),
                            new OA\Property(property: "client_name", type: "string", example: "Budi Utomo"),
                            new OA\Property(property: "client_phone", type: "string", example: "081234567890"),
                            new OA\Property(property: "client_address", type: "string", example: "Jl. Merdeka No. 123"),
                            new OA\Property(property: "file_url", type: "string", example: "http://localhost:8080/uploads/surat_kontrak/Kontruksi_kontrak_Budi_Utomo_1.pdf"),
                            new OA\Property(property: "project_name", type: "string", example: "Projek 1"),
                            new OA\Property(property: "project_location", type: "string", example: "Jl. Merdeka No. 123"),
                            new OA\Property(property: "contract_date", type: "string", nullable: true, example: "2026-06-08T14:00:00+07:00"),
                            new OA\Property(property: "grand_total", type: "number", example: 450000000),
                            new OA\Property(property: "total_pekerjaan", type: "integer", example: 5),
                            new OA\Property(property: "work_items", type: "array", items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "roman_number", type: "string", example: "I"),
                                    new OA\Property(property: "group_name", type: "string", example: "Pekerjaan Pondasi"),
                                    new OA\Property(property: "total_price", type: "string", example: "45000000")
                                ]
                            ))
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function construction_contract()
    {
    }

    #[OA\Get(
        path: "/api/renovation/contract/{id}",
        summary: "Ambil Data Kontrak Renovasi",
        description: "Mengambil data detail surat perjanjian kontrak renovasi rumah/bangunan berdasarkan ID pengajuan renovasi, termasuk link file PDF kontrak yang digenerate dan rincian pekerjaan termin RAB.",
        tags: ["Contract (Kontrak)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Proyek Renovasi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data kontrak renovasi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "contract_number", type: "string", example: "KTR-2026-1"),
                            new OA\Property(property: "status", type: "string", example: "complete", enum: ["complete", "pending"]),
                            new OA\Property(property: "client_name", type: "string", example: "Budi Utomo"),
                            new OA\Property(property: "client_phone", type: "string", example: "081234567890"),
                            new OA\Property(property: "client_address", type: "string", example: "Jl. Merdeka No. 123"),
                            new OA\Property(property: "file_url", type: "string", example: "http://localhost:8080/uploads/surat_kontrak/Renovasi_kontrak_Budi_Utomo_1.pdf"),
                            new OA\Property(property: "project_name", type: "string", example: "Projek 1"),
                            new OA\Property(property: "project_location", type: "string", example: "Jl. Merdeka No. 123"),
                            new OA\Property(property: "contract_date", type: "string", nullable: true, example: "2026-06-08T14:00:00+07:00"),
                            new OA\Property(property: "grand_total", type: "number", example: 120000000),
                            new OA\Property(property: "total_pekerjaan", type: "integer", example: 3),
                            new OA\Property(property: "work_items", type: "array", items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "roman_number", type: "string", example: "I"),
                                    new OA\Property(property: "group_name", type: "string", example: "Pekerjaan Atap"),
                                    new OA\Property(property: "total_price", type: "string", example: "25000000")
                                ]
                            ))
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function renovation_contract()
    {
    }
}
