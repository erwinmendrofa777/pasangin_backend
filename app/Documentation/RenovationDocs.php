<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class RenovationDocs
{
    #[OA\Post(
        path: "/api/renovation/submit",
        summary: "Kirim Pengajuan Renovasi Baru",
        description: "Mengajukan proyek renovasi baru lengkap dengan data jenis renovasi, jadwal survey, dan lampiran foto kondisi lapangan.",
        tags: ["Renovation (Renovasi)"],
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
                        new OA\Property(property: "full_name", type: "string", example: "Budi Utomo", description: "Nama Lengkap"),
                        new OA\Property(property: "phone_number", type: "string", example: "081234567890", description: "Nomor Telepon"),
                        new OA\Property(property: "renovation_type", type: "string", example: "Renovasi Kamar Mandi", description: "Jenis Renovasi"),
                        new OA\Property(property: "description", type: "string", example: "Pengecatan ulang dan penggantian keramik lantai.", description: "Deskripsi Renovasi"),
                        new OA\Property(property: "survey_date", type: "string", example: "2026-06-15", description: "Tanggal Rencana Survey"),
                        new OA\Property(property: "address", type: "string", example: "Jl. Merdeka No. 123", description: "Alamat Lengkap"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088", description: "Latitude"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456", description: "Longitude"),
                        new OA\Property(property: "voucher_code", type: "string", example: "DISKON50", nullable: true, description: "Kode Voucher (Opsional)"),
                        new OA\Property(property: "survey_cost", type: "string", example: "200000", description: "Biaya Survey"),
                        new OA\Property(property: "discount_amount", type: "string", example: "50000", description: "Potongan Diskon"),
                        new OA\Property(property: "total_payment", type: "string", example: "150000", description: "Total Pembayaran Akhir"),
                        new OA\Property(property: "design_requests_id", type: "integer", nullable: true, example: 5, description: "ID Permohonan Desain yang dipilih (Opsional)"),
                        new OA\Property(property: "images[]", type: "array", items: new OA\Items(type: "string", format: "binary"), description: "Lampiran foto lokasi (Maksimal 5 foto, ukuran maks 5MB per file)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permohonan berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permohonan renovasi berhasil dikirim"),
                        new OA\Property(property: "data", type: "integer", example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (gambar kosong, terlalu besar, format salah, atau lebih dari 5 file)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function submit() {}

    #[OA\Get(
        path: "/api/renovation/projects/{userId}",
        summary: "Ambil Riwayat Proyek Renovasi User",
        description: "Mengambil daftar seluruh proyek renovasi milik user tertentu berdasarkan ID user.",
        tags: ["Renovation (Renovasi)"],
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
                description: "Proyek renovasi ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "data proyek renovasi ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function project() {}

    #[OA\Get(
        path: "/api/renovation/detail/{projectId}",
        summary: "Ambil Detail Proyek Renovasi",
        description: "Mengambil data detail proyek renovasi tertentu berdasarkan ID proyek.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi (Requests ID)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail proyek ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail proyek renovasi ditemukan"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function detail() {}

    #[OA\Get(
        path: "/api/renovation/surveys/{projectId}",
        summary: "Ambil Hasil Survey Proyek Renovasi",
        description: "Mengambil semua daftar hasil survey lapangan untuk proyek renovasi terpilih.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
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
                        new OA\Property(property: "message", type: "string", example: "Data survey ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function surveys() {}

    #[OA\Patch(
        path: "/api/renovation/surveys/send_comment/{surveyId}",
        summary: "Kirim Komentar Hasil Survey Renovasi",
        description: "Mengirimkan komentar atau persetujuan klien terkait hasil survey lapangan proyek renovasi.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "surveyId",
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
                    new OA\Property(property: "comment", type: "string", example: "Sudah sesuai, lanjut ke tahap RAB.")
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
        path: "/api/renovation/designs/{projectId}",
        summary: "Ambil Hasil Desain Proyek Renovasi",
        description: "Mengambil semua daftar file gambar/dokumen desain arsitektural untuk proyek renovasi terpilih.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Hasil desain ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data desain ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function designs() {}

    #[OA\Patch(
        path: "/api/renovation/designs/send_comment/{designId}",
        summary: "Kirim Komentar Hasil Desain Renovasi",
        description: "Mengirimkan komentar atau persetujuan klien terkait file desain arsitek yang dikirim untuk proyek renovasi.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "designId",
                in: "path",
                description: "ID Desain Proyek (renovation_designs.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "comment", type: "string", example: "Saya setuju dengan desain layout ini.")
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
    public function sendCommentDesign() {}

    #[OA\Get(
        path: "/api/renovation/progress/{projectId}",
        summary: "Ambil Progress Pembangunan Proyek Renovasi",
        description: "Mengambil detail progres pekerjaan fisik proyek renovasi berdasarkan aktivitas RAB, bobot, persentase penyelesaian, rating kerja tukang, dan lampiran foto progres harian.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data progres ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data progress ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function progress() {}

    #[OA\Get(
        path: "/api/renovation/progressByUser",
        summary: "Ambil Progress Proyek Renovasi Milik User",
        description: "Mengambil daftar ringkasan progres minggu berjalan dan realisasi bobot proyek renovasi aktif milik user yang sedang login.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Progres proyek user ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Daftar Proyek renovasi ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function progressByUser() {}

    #[OA\Get(
        path: "/api/renovation/invoices/{projectId}",
        summary: "Ambil Tagihan (Invoices) Proyek Renovasi",
        description: "Mengambil semua tagihan pembayaran termin renovasi beserta potongan voucher diskon untuk proyek renovasi terpilih.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
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
                        new OA\Property(property: "message", type: "string", example: "Data invoice ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function invoices() {}

    #[OA\Get(
        path: "/api/renovation/rabs/{projectId}",
        summary: "Ambil RAB Proyek Renovasi",
        description: "Mengambil Rencana Anggaran Biaya (RAB) proyek renovasi lengkap dengan daftar bahan material yang disarankan beserta material terpilih (selected material).",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data RAB ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail RAB Proyek renovasi ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function rabs() {}

    #[OA\Get(
        path: "/api/renovation/targets/{projectId}",
        summary: "Ambil Target Pekerjaan Lapangan Renovasi",
        description: "Mengambil daftar target pengerjaan proyek renovasi (timeline minggu pengerjaan).",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Target ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail Target Proyek renovasi ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function targets() {}

    #[OA\Get(
        path: "/api/renovation/targetsByUser",
        summary: "Ambil Target Proyek Klien Renovasi",
        description: "Mengambil daftar seluruh target pengerjaan, realisasi, laporan progres tertunda, serta status persetujuan laporan mingguan milik klien renovasi yang sedang login.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Target user ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Target Proyek renovasi ditemukan untuk pengguna."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function targetsByUser() {}

    #[OA\Patch(
        path: "/api/renovation/select-material",
        summary: "Pilih Material Untuk RAB Renovasi",
        description: "Memperbarui material/produk yang dipilih dalam item RAB Renovasi. Harga unit dan total harga item RAB otomatis dikalkulasikan ulang sesuai harga produk terkini.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "rab_id", type: "integer", example: 15, description: "ID baris RAB (renovation_rabs.id)"),
                    new OA\Property(property: "product_id", type: "integer", example: 3, description: "ID produk material baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Material RAB berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Material berhasil diperbarui!"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            )
        ]
    )]
    public function select_material() {}

    #[OA\Patch(
        path: "/api/renovation/finalize-rab",
        summary: "Kunci / Finalisasi RAB & Surat Kontrak Renovasi",
        description: "Mengunci data RAB Renovasi agar tidak bisa diubah lagi oleh klien, sekaligus secara otomatis membuatkan (*generate*) file Surat Perjanjian Kontrak PDF di server.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "project_id", type: "integer", example: 1, description: "ID Proyek Renovasi")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "RAB dikunci dan kontrak PDF dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "RAB berhasil dikunci!")
                    ]
                )
            )
        ]
    )]
    public function finalize_rab() {}

    #[OA\Post(
        path: "/api/renovation/send-checkin-attendance/{projectId}",
        summary: "Kirim Absen Masuk Tukang Renovasi (Check-In)",
        description: "Mengirimkan video absensi masuk harian beserta data lokasi koordinat dan jumlah pekerja di lapangan untuk proyek renovasi.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
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
                        new OA\Property(property: "file", type: "string", format: "binary", description: "File video absen (Format MP4/MOV dsb, maks 30MB)"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456", description: "Longitude GPS"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088", description: "Latitude GPS"),
                        new OA\Property(property: "waktu", type: "string", example: "2026-06-08 08:00:00", description: "Waktu absen harian"),
                        new OA\Property(property: "jumlah_tukang", type: "integer", example: 4, description: "Jumlah pekerja hadir"),
                        new OA\Property(property: "deskripsi", type: "string", example: "Mulai pekerjaan pembongkaran dinding.", nullable: true, description: "Catatan pengerjaan (Opsional)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Absen masuk berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Absen masuk berhasil dikirim."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal.")
        ]
    )]
    public function SendAttendance() {}

    #[OA\Post(
        path: "/api/renovation/send-checkout-attendance/{projectId}",
        summary: "Kirim Absen Keluar Tukang Renovasi (Check-Out)",
        description: "Mengirimkan video laporan absen keluar harian beserta data lokasi koordinat dan jumlah pekerja di akhir jam kerja untuk proyek renovasi.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "projectId",
                in: "path",
                description: "ID Proyek Renovasi",
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
                        new OA\Property(property: "file", type: "string", format: "binary", description: "File video absen"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                        new OA\Property(property: "waktu", type: "string", example: "2026-06-08 17:00:00"),
                        new OA\Property(property: "jumlah_tukang", type: "integer", example: 4),
                        new OA\Property(property: "deskripsi", type: "string", example: "Plester dinding selesai.", nullable: true)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Absen keluar berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Absen keluar berhasil dikirim.")
                    ]
                )
            )
        ]
    )]
    public function SendCheckoutAttendance() {}

    #[OA\Get(
        path: "/api/renovation/material-submissions",
        summary: "Ambil Daftar Pengajuan Bahan & Alat Renovasi",
        description: "Mengambil daftar pengajuan kebutuhan bahan material atau alat pengerjaan proyek renovasi dari tukang.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(name: "renovation_id", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Filter ID Proyek"),
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Filter status pengajuan (pending, approved, dsb)"),
            new OA\Parameter(name: "type", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Filter tipe pengajuan ('bahan' atau 'alat')")
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pengajuan berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data pengajuan bahan/alat berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            )
        ]
    )]
    public function getMaterialSubmissions() {}

    #[OA\Get(
        path: "/api/renovation/material-submissions/{id}",
        summary: "Ambil Detail Pengajuan Bahan/Alat Renovasi",
        description: "Mengambil detail data pengajuan bahan material atau alat pengerjaan proyek renovasi berdasarkan ID pengajuan.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan (renovation_material_submissions.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail pengajuan bahan/alat berhasil diambil."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Pengajuan tidak ditemukan.")
        ]
    )]
    public function getMaterialSubmission() {}

    #[OA\Post(
        path: "/api/renovation/material-submissions",
        summary: "Kirim Pengajuan Bahan / Alat Baru Renovasi",
        description: "Mengajukan kebutuhan bahan bangunan (semen, bata, kayu, dll) atau alat pengerjaan proyek renovasi dari tukang ke pihak admin.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "renovation_id", type: "integer", example: 1, description: "ID Proyek Renovasi"),
                        new OA\Property(property: "job_applications_id", type: "integer", example: 4, nullable: true, description: "ID lamaran/kontrak kerja tukang (Opsional)"),
                        new OA\Property(property: "type", type: "string", example: "bahan", enum: ["bahan", "alat"], description: "Tipe pengajuan"),
                        new OA\Property(property: "title", type: "string", example: "Beli Cat Tembok", nullable: true, description: "Judul ringkas (Opsional)"),
                        new OA\Property(property: "description", type: "string", example: "Cat Nippon Paint 5kg, Kuas 3 inch.", description: "Daftar list item detail pengajuan"),
                        new OA\Property(property: "photo", type: "string", format: "binary", nullable: true, description: "Foto bukti/kebutuhan (Opsional)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Pengajuan berhasil terkirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pengajuan bahan/alat berhasil dikirim."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Validasi gagal.")
        ]
    )]
    public function createMaterialSubmission() {}

    #[OA\Post(
        path: "/api/renovation/material-submissions/{id}",
        summary: "Update Pengajuan Bahan / Alat Renovasi",
        description: "Memperbarui isi pengajuan bahan/alat pengerjaan proyek renovasi sebelum diproses oleh admin. (Hanya bisa diedit ketika statusnya masih 'pending').",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan",
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
                        new OA\Property(property: "type", type: "string", example: "bahan", enum: ["bahan", "alat"]),
                        new OA\Property(property: "title", type: "string", example: "Beli Cat Tambahan"),
                        new OA\Property(property: "description", type: "string", example: "Cat Nippon Paint 10kg."),
                        new OA\Property(property: "photo", type: "string", format: "binary", nullable: true)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Pengajuan diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data pengajuan berhasil diperbarui."),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Pengajuan sudah diproses atau tidak valid.")
        ]
    )]
    public function updateMaterialSubmission() {}

    #[OA\Delete(
        path: "/api/renovation/material-submissions/{id}",
        summary: "Hapus Pengajuan Bahan / Alat Renovasi",
        description: "Menghapus data pengajuan bahan material atau alat pengerjaan proyek renovasi berdasarkan ID pengajuan.",
        tags: ["Renovation (Renovasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pengajuan",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pengajuan dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data pengajuan berhasil dihapus.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Pengajuan tidak ditemukan.")
        ]
    )]
    public function deleteMaterialSubmission() {}
}
