<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class JobApplicationDocs
{
    #[OA\Post(
        path: "/api/tukang/job-submit",
        summary: "Kirim Lamaran Kerja Tukang",
        description: "Mengirimkan data permohonan kesiapan kerja / lamaran dari tukang untuk proyek konstruksi atau renovasi tertentu.",
        tags: ["Tukang Jobs & Progress"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "tukang_id", type: "integer", example: 5, description: "ID Tukang yang melamar"),
                    new OA\Property(property: "project_id", type: "integer", example: 2, description: "ID Proyek tujuan lamaran"),
                    new OA\Property(property: "project_type", type: "string", example: "construction", enum: ["construction", "renovation"], description: "Tipe proyek yang dilamar"),
                    new OA\Property(property: "name", type: "string", example: "Supriadi", description: "Nama lengkap tukang"),
                    new OA\Property(property: "email", type: "string", example: "supriadi@example.com", description: "Email aktif"),
                    new OA\Property(property: "phone", type: "string", example: "081298765432", description: "Nomor telepon aktif"),
                    new OA\Property(property: "dob", type: "string", example: "1990-05-15", description: "Tanggal lahir (YYYY-MM-DD)"),
                    new OA\Property(property: "address", type: "string", example: "Kec. Sukasari, Bandung", description: "Alamat domisili"),
                    new OA\Property(property: "specialization", type: "string", example: "Tukang Kayu & Plafon", deprecated: true, description: "Keahlian spesifik tukang (Deprecated: diabaikan, data ditarik otomatis dari keahlian profil)"),
                    new OA\Property(property: "construction_job_id", type: "integer", example: 12, description: "ID Lowongan konstruksi target (Opsional, atau gunakan job_id)"),
                    new OA\Property(property: "job_id", type: "integer", example: 12, description: "ID Lowongan target (Alternatif dari construction_job_id)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permohonan kesiapan kerja berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permohonan kesiapan kerja berhasil dikirim  !")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Gagal menyimpan data ke database."
            )
        ]
    )]
    public function submit()
    {
    }
}
