<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class AlamatUserDocs
{
    #[OA\Post(
        path: "/api/alamat",
        summary: "Tambah Alamat User",
        description: "Menambahkan data alamat baru untuk user yang sedang login menggunakan token JWT.",
        tags: ["User Address"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "alamat", type: "string", example: "Jl. Jenderal Sudirman No. 123, Jakarta", description: "Alamat lengkap"),
                    new OA\Property(property: "label", type: "string", example: "Rumah", description: "Label alamat (misal: Rumah, Kantor, Kos)"),
                    new OA\Property(property: "latitude", type: "number", format: "float", example: -6.2088, description: "Koordinat latitude"),
                    new OA\Property(property: "longitude", type: "number", format: "float", example: 106.8456, description: "Koordinat longitude")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Alamat berhasil ditambahkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Alamat berhasil ditambahkan."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "alamat", type: "string", example: "Jl. Jenderal Sudirman No. 123, Jakarta"),
                            new OA\Property(property: "label", type: "string", example: "Rumah"),
                            new OA\Property(property: "latitude", type: "number", example: -6.2088),
                            new OA\Property(property: "longitude", type: "number", example: 106.8456)
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (parameter tidak valid atau kurang)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token tidak valid atau tidak ditemukan."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function create()
    {
    }

    #[OA\Get(
        path: "/api/alamat",
        summary: "Daftar Alamat User",
        description: "Mengambil semua daftar alamat milik user yang sedang login.",
        tags: ["User Address"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar alamat ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Alamat ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "5"),
                                new OA\Property(property: "id_user", type: "string", example: "10"),
                                new OA\Property(property: "alamat", type: "string", example: "Jl. Jenderal Sudirman No. 123, Jakarta"),
                                new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                                new OA\Property(property: "longitude", type: "string", example: "106.8456"),
                                new OA\Property(property: "label", type: "string", example: "Rumah"),
                                new OA\Property(property: "is_active", type: "string", example: "1")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token tidak valid atau tidak ditemukan."
            )
        ]
    )]
    public function get()
    {
    }

    #[OA\Put(
        path: "/api/alamat/{id}",
        summary: "Update Alamat User",
        description: "Mengubah data alamat berdasarkan ID alamat. Pengubahan hanya bisa dilakukan jika alamat tersebut merupakan milik user yang login.",
        tags: ["User Address"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID Alamat yang akan diupdate",
                schema: new OA\Schema(type: "integer", example: 5)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "alamat", type: "string", example: "Jl. Gatot Subroto No. 45, Jakarta", description: "Alamat lengkap baru"),
                    new OA\Property(property: "label", type: "string", example: "Kantor", description: "Label alamat baru"),
                    new OA\Property(property: "latitude", type: "number", format: "float", example: -6.2198, description: "Latitude baru (-90 s/d 90)"),
                    new OA\Property(property: "longitude", type: "number", format: "float", example: 106.8123, description: "Longitude baru (-180 s/d 180)"),
                    new OA\Property(property: "is_active", type: "integer", example: 1, description: "Status aktif (1 / 0)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Berhasil Mengubah Alamat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Berhasil Mengubah Alamat.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (koordinat diluar batas atau parameter salah)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token tidak valid."
            ),
            new OA\Response(
                response: 404,
                description: "Alamat tidak ditemukan atau Anda tidak memiliki akses ke alamat tersebut."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function put()
    {
    }

    #[OA\Patch(
        path: "/api/alamat/{id}",
        summary: "Set Alamat Utama",
        description: "Mengubah status alamat terpilih menjadi aktif/alamat utama (is_active = 1) dan menonaktifkan alamat user lainnya (is_active = 0).",
        tags: ["User Address"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID Alamat yang akan dijadikan alamat utama",
                schema: new OA\Schema(type: "integer", example: 5)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Alamat utama berhasil diubah.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Alamat utama berhasil diubah.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token tidak valid."
            ),
            new OA\Response(
                response: 404,
                description: "Alamat tidak ditemukan atau bukan milik Anda."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function patch()
    {
    }

    #[OA\Delete(
        path: "/api/alamat/{id}",
        summary: "Hapus Alamat User",
        description: "Menghapus data alamat berdasarkan ID alamat.",
        tags: ["User Address"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID Alamat yang akan dihapus",
                schema: new OA\Schema(type: "integer", example: 5)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Alamat berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "Alamat berhasil dihapus.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function delete()
    {
    }
}
