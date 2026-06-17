<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class DesignDocs
{
    #[OA\Post(
        path: "/api/design/submit",
        summary: "Kirim Pengajuan Desain Baru",
        description: "Mengirim permohonan rancangan desain arsitektur baru beserta data spesifikasi bangunan, tanggal rencana survey lokasi, dan data lokasi GPS.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "user_id", type: "integer", example: 10, description: "ID User Pengaju"),
                        new OA\Property(property: "full_name", type: "string", example: "Budi Utomo"),
                        new OA\Property(property: "phone_number", type: "string", example: "081234567890"),
                        new OA\Property(property: "land_area", type: "string", example: "150"),
                        new OA\Property(property: "building_area", type: "string", example: "80"),
                        new OA\Property(property: "design_concept", type: "string", example: "Minimalis"),
                        new OA\Property(property: "survey_date", type: "string", example: "2026-06-15"),
                        new OA\Property(property: "location_address", type: "string", example: "Jl. Merdeka No. 123"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456"),
                        new OA\Property(property: "voucher_code", type: "string", nullable: true, example: "DISKON50"),
                        new OA\Property(property: "survey_cost", type: "string", example: "200000"),
                        new OA\Property(property: "discount_amount", type: "string", example: "50000"),
                        new OA\Property(property: "total_payment", type: "string", example: "150000")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Pengajuan desain berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pengajuan desain berhasil dikirim!")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan data ke database.")
        ]
    )]
    public function submit() {}

    #[OA\Get(
        path: "/api/design/history/{userId}",
        summary: "Ambil Riwayat Pengajuan Desain User",
        description: "Mengambil daftar riwayat permohonan desain arsitektur yang pernah diajukan oleh user tertentu.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userId",
                in: "path",
                description: "ID User",
                required: true,
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data riwayat ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data riwayat desain ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "User ID tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function history() {}

    #[OA\Get(
        path: "/api/design/requests/detail/{id}",
        summary: "Ambil Detail Pengajuan Desain",
        description: "Mengambil detail satu pengajuan permohonan desain arsitek berdasarkan ID permohonan.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Permohonan Desain (design_requests.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail permohonan desain ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail permohonan desain ditemukan"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Permohonan tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: "/api/design/surveys/{designRequestId}",
        summary: "Ambil Hasil Survey Lapangan Desain",
        description: "Mengambil daftar laporan hasil survey lapangan dari surveyor untuk pengajuan desain tertentu.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "designRequestId",
                in: "path",
                description: "ID Pengajuan Desain",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Hasil survey ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail permohonan survey ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function surveys() {}

    #[OA\Get(
        path: "/api/design/surveys/detail/{id}",
        summary: "Ambil Detail Hasil Survey Desain",
        description: "Mengambil detail satu laporan hasil survey lapangan berdasarkan ID survey.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Laporan Survey (project_surveys.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail laporan survey ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail permohonan survey ditemukan"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            )
        ]
    )]
    public function detailSurveys() {}

    #[OA\Patch(
        path: "/api/design/surveys/send_comment/{id}",
        summary: "Kirim Komentar Survey Desain",
        description: "Mengirimkan persetujuan atau komentar balik dari klien terhadap hasil survey lapangan yang dilaporkan.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Survey",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "comment", type: "string", example: "Lokasi sudah sesuai, silakan lanjut desain.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Komentar berhasil ditambahkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Komentar berhasil ditambahkan.")
                    ]
                )
            )
        ]
    )]
    public function sendCommentSurvey() {}

    #[OA\Get(
        path: "/api/design/targets/{designRequestId}",
        summary: "Ambil Target & Pengerjaan Desain",
        description: "Mengambil daftar target pengerjaan desain arsitek (milestones), perkiraan tanggal mulai, dan status revisi.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "designRequestId",
                in: "path",
                description: "ID Pengajuan Desain",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar target desain ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data target desain ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function targets() {}

    #[OA\Get(
        path: "/api/design/progress/{id}",
        summary: "Ambil Progres File Desain",
        description: "Mengambil daftar file alternatif/progres gambar desain arsitektural berdasarkan ID target desain (design_targets_id).",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Target Desain (design_targets.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Progres desain ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data progress desain ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function progress() {}

    #[OA\Post(
        path: "/api/design/progress/{id}",
        summary: "Kirim Persetujuan / Catatan Revisi Desain",
        description: "Mengirimkan status persetujuan (APPROVED/REJECTED) beserta catatan perbaikan/revisi rancangan desain arsitek.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID File Progres Desain (project_designs.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 15)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string", example: "APPROVED", enum: ["PENDING", "REJECTED", "APPROVED"], description: "Status persetujuan"),
                    new OA\Property(property: "revision_note", type: "string", nullable: true, example: "Tolong posisi pintu digeser ke sebelah kanan.", description: "Catatan perbaikan jika ditolak")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Status persetujuan berhasil disimpan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Progress desain berhasil diperbarui"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            )
        ]
    )]
    public function updateProgress() {}

    #[OA\Get(
        path: "/api/design/invoices/{designRequestId}",
        summary: "Ambil Riwayat Invoices Desain",
        description: "Mengambil daftar termin tagihan biaya pengerjaan desain beserta potongan diskon voucher.",
        tags: ["Design (Desain)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "designRequestId",
                in: "path",
                description: "ID Pengajuan Desain",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar invoice ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail permohonan invoice ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function invoices() {}
}
