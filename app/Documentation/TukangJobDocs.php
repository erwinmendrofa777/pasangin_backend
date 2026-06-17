<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class TukangJobDocs
{
    #[OA\Get(
        path: "/api/tukang/jobs/construction",
        summary: "Ambil Daftar Lowongan Proyek Konstruksi",
        description: "Mengambil semua daftar lowongan pekerjaan konstruksi fisik aktif beserta lokasi geografis (latitude, longitude) dan alamat klien.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lowongan konstruksi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getConstructionJobs() {}

    #[OA\Get(
        path: "/api/tukang/jobs/renovation",
        summary: "Ambil Daftar Lowongan Proyek Renovasi",
        description: "Mengambil semua daftar lowongan pekerjaan renovasi fisik aktif beserta koordinat lokasi dan alamat klien.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar lowongan renovasi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getRenovationJobs() {}

    #[OA\Get(
        path: "/api/tukang/application-status/{tukang_id}",
        summary: "Ambil Status Pendaftaran Akun Tukang",
        description: "Mengambil status verifikasi berkas pendaftaran tukang saat ini.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang (tukang.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Status pendaftaran ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "status", type: "string", example: "Berkas Diproses")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getApplicationStatus() {}

    #[OA\Get(
        path: "/api/tukang/my-applications/{tukang_id}",
        summary: "Ambil Riwayat Lamaran Pekerjaan Tukang",
        description: "Mengambil seluruh riwayat lamaran pengerjaan proyek konstruksi maupun renovasi yang pernah diajukan oleh tukang beserta statusnya.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Riwayat lamaran berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getMyApplications() {}

    #[OA\Get(
        path: "/api/tukang/my-targets/{tukang_id}",
        summary: "Ambil Target Tugas Proyek Tukang",
        description: "Mengambil daftar seluruh target pengerjaan mingguan (aktivitas RAB) baik proyek konstruksi maupun renovasi yang menjadi tanggung jawab tukang tersebut.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar target pengerjaan berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getMyTargets() {}

    #[OA\Post(
        path: "/api/tukang/submit-progress",
        summary: "Kirim Laporan Progres Proyek Global",
        description: "Mengirimkan laporan foto dan persentase perkembangan mingguan proyek konstruksi secara umum ke admin dan klien.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "construction_id", type: "integer", example: 2),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Foto fisik progress"),
                        new OA\Property(property: "week_number", type: "integer", example: 1, default: 1),
                        new OA\Property(property: "percentage", type: "integer", example: 10, default: 0),
                        new OA\Property(property: "description", type: "string", example: "Pemasangan pondasi cakar ayam.")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Progress berhasil dilaporkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Laporan progress berhasil dikirim !")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Gagal menyimpan progress.")
        ]
    )]
    public function submitProgress() {}

    #[OA\Get(
        path: "/api/tukang/progress/{tukang_id}",
        summary: "Ambil Target & Riwayat Progres Proyek Konstruksi",
        description: "Mengambil daftar target mingguan proyek konstruksi beserta detail progress laporan harian yang diajukan oleh tukang ini.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "construction_id",
                in: "query",
                description: "ID Proyek Konstruksi (Jika kosong, mengambil dari proyek yang berstatus Siap Kerja)",
                required: false,
                schema: new OA\Schema(type: "integer", example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Progres target proyek konstruksi berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil data target proyek"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getConstructionProgress() {}

    #[OA\Post(
        path: "/api/tukang/progress",
        summary: "Laporkan Progres Target Konstruksi",
        description: "Mengirimkan laporan harian/mingguan untuk target aktivitas konstruksi tertentu (RAB) lengkap dengan foto fisik dan bobot pengerjaan.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "id_construction_targets", type: "integer", example: 1, description: "ID Target Tugas"),
                        new OA\Property(property: "construction_id", type: "integer", example: 2),
                        new OA\Property(property: "bobot", type: "number", format: "float", example: 2.5, description: "Bobot perkembangan yang dilaporkan"),
                        new OA\Property(property: "description", type: "string", example: "Plester dinding kamar utama."),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Foto pengerjaan fisik")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Laporan progres konstruksi dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Laporan progress berhasil dikirim")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Internal Server Error.")
        ]
    )]
    public function createConstructionProgress() {}

    #[OA\Get(
        path: "/api/tukang/renovation/progress/{tukang_id}",
        summary: "Ambil Target & Riwayat Progres Proyek Renovasi",
        description: "Mengambil daftar target mingguan proyek renovasi beserta detail progress laporan harian yang telah diajukan oleh tukang ini.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "renovation_id",
                in: "query",
                description: "ID Proyek Renovasi (Jika kosong, mengambil dari proyek yang berstatus Siap Kerja)",
                required: false,
                schema: new OA\Schema(type: "integer", example: 3)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Progres target proyek renovasi berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil data target proyek"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getRenovationProgress() {}

    #[OA\Post(
        path: "/api/tukang/renovation/progress",
        summary: "Laporkan Progres Target Renovasi",
        description: "Mengirimkan laporan harian/mingguan untuk target aktivitas renovasi tertentu (RAB) lengkap dengan foto fisik dan bobot pengerjaan.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "id_renovation_targets", type: "integer", example: 1, description: "ID Target Tugas"),
                        new OA\Property(property: "renovation_id", type: "integer", example: 3),
                        new OA\Property(property: "bobot", type: "number", format: "float", example: 1.5),
                        new OA\Property(property: "description", type: "string", example: "Pengecatan kusen jendela."),
                        new OA\Property(property: "photo", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Laporan progres renovasi dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Laporan progress berhasil dikirim")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized."),
            new OA\Response(response: 500, description: "Internal Server Error.")
        ]
    )]
    public function createRenovationProgress() {}

    #[OA\Get(
        path: "/api/construction/attendance-projects/{tukang_id}",
        summary: "Ambil Proyek Konstruksi Aktif Untuk Absensi",
        description: "Mengambil daftar proyek konstruksi fisik aktif tukang beserta lokasi proyek, radius batas absen (meter), dan status kehadiran masuk/keluar hari ini.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar proyek konstruksi absensi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil data proyek"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getProjectListForAttendance() {}

    #[OA\Get(
        path: "/api/renovation/attendance-projects/{tukang_id}",
        summary: "Ambil Proyek Renovasi Aktif Untuk Absensi",
        description: "Mengambil daftar proyek renovasi aktif tukang beserta koordinat proyek, radius batas absen (meter), dan status kehadiran masuk/keluar hari ini.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar proyek renovasi absensi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil data proyek renovasi"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 400, description: "ID Tukang dibutuhkan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function getRenovationListForAttendance() {}
}
