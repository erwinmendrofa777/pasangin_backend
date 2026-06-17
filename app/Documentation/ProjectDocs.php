<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class ProjectDocs
{
    #[OA\Get(
        path: "/api/design/requests",
        summary: "Ambil Daftar Semua Pengajuan Proyek Desain",
        description: "Mengambil seluruh daftar pengajuan proyek desain arsitektur dari tabel design_requests.",
        tags: ["Projects (Proyek)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pengajuan desain berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "1"),
                                new OA\Property(property: "full_name", type: "string", example: "Budi Santoso"),
                                new OA\Property(property: "phone_number", type: "string", example: "081234567890"),
                                new OA\Property(property: "land_area", type: "string", example: "150"),
                                new OA\Property(property: "building_area", type: "string", example: "80"),
                                new OA\Property(property: "design_concept", type: "string", example: "Minimalis Modern"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada server."
            )
        ]
    )]
    public function designRequests()
    {
    }

    #[OA\Post(
        path: "/api/project/design/submit",
        summary: "Kirim Pengajuan Proyek Desain",
        description: "Mengirim permohonan pengajuan proyek desain arsitektur baru.",
        tags: ["Projects (Proyek)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "full_name", type: "string", example: "Budi Santoso", description: "Nama lengkap pengaju"),
                    new OA\Property(property: "phone_number", type: "string", example: "081234567890", description: "Nomor telepon pengaju"),
                    new OA\Property(property: "land_area", type: "string", example: "150", description: "Luas tanah"),
                    new OA\Property(property: "building_area", type: "string", example: "80", description: "Luas bangunan"),
                    new OA\Property(property: "design_concept", type: "string", example: "Minimalis Modern", description: "Konsep desain"),
                    new OA\Property(property: "location_address", type: "string", example: "Jl. Merdeka No. 10", description: "Alamat lokasi"),
                    new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                    new OA\Property(property: "longitude", type: "string", example: "106.8456")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Pengajuan desain berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pengajuan desain berhasil dikirim."),
                        new OA\Property(property: "id", type: "integer", example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (nama atau nomor telepon kosong)."
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada server."
            )
        ]
    )]
    public function submitDesignRequest()
    {
    }

    #[OA\Get(
        path: "/api/project-details/{id}",
        summary: "Ambil Detail Proyek Desain",
        description: "Mengambil data detail satu proyek pengajuan desain arsitek berdasarkan ID.",
        tags: ["Projects (Proyek)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Proyek Pengajuan Desain",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail proyek berhasil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "ID proyek tidak valid atau data tidak ditemukan."
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada server."
            )
        ]
    )]
    public function projectDetails()
    {
    }

    #[OA\Get(
        path: "/api/project/surveys/{id}",
        summary: "Ambil Hasil Survey Lapangan Proyek",
        description: "Mengambil daftar laporan hasil survey lapangan dari surveyor untuk proyek tertentu.",
        tags: ["Projects (Proyek)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan Desain (design_request_id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Hasil survey proyek berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "ID Proyek tidak valid."
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada database."
            )
        ]
    )]
    public function getProjectSurveys()
    {
    }

    #[OA\Get(
        path: "/api/project/designs/{id}",
        summary: "Ambil Daftar File Hasil Desain Proyek",
        description: "Mengambil daftar gambar alternatif / hasil desain rancangan arsitektural untuk proyek tertentu.",
        tags: ["Projects (Proyek)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan Desain (design_request_id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar hasil desain berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "ID Proyek tidak valid."
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada database."
            )
        ]
    )]
    public function getProjectDesigns()
    {
    }

    #[OA\Get(
        path: "/api/project/invoices/{id}",
        summary: "Ambil Daftar Tagihan Proyek",
        description: "Mengambil daftar seluruh tagihan (invoices) pembayaran termin yang terkait dengan proyek tertentu.",
        tags: ["Projects (Proyek)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan Desain (design_request_id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar tagihan berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "ID Proyek tidak valid."
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan pada database."
            )
        ]
    )]
    public function getProjectInvoices()
    {
    }
}
