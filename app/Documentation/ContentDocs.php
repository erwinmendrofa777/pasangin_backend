<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class ContentDocs
{
    #[OA\Get(
        path: "/api/content/tips",
        summary: "Ambil Data Tips Pembangunan",
        description: "Mengambil daftar artikel/tips pembangunan rumah aktif terbaru untuk ditampilkan di aplikasi.",
        tags: ["General Content (Konten Umum)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data tips berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data tips berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Tips Memilih Semen Berkualitas"),
                                new OA\Property(property: "content", type: "string", example: "Semen merupakan elemen perekat utama dalam bangunan..."),
                                new OA\Property(property: "image_url", type: "string", nullable: true, example: "http://localhost:8080/uploads/tips/semen.jpg"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function tips()
    {
    }

    #[OA\Get(
        path: "/api/content/banners",
        summary: "Ambil Data Banner Promosi",
        description: "Mengambil daftar promo banner aktif terbaru untuk slide beranda aplikasi.",
        tags: ["General Content (Konten Umum)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data banner berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data banner berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 3),
                                new OA\Property(property: "image_url", type: "string", nullable: true, example: "http://localhost:8080/uploads/banners/banner_diskon.jpg")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function banners()
    {
    }

    #[OA\Get(
        path: "/api/content/priceEstimate",
        summary: "Ambil Estimasi Harga Konsep Desain",
        description: "Mengambil list estimasi harga pembangunan per meter persegi berdasarkan konsep desain (Minimalis, Klasik, Modern) beserta kualitas bahan bangunan.",
        tags: ["General Content (Konten Umum)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Data estimasi harga berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data estimasi harga berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "concept", type: "string", example: "Minimalis"),
                                new OA\Property(property: "qualities", type: "array", items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "concept_id", type: "integer", example: 1),
                                        new OA\Property(property: "name", type: "string", example: "Kualitas Standar"),
                                        new OA\Property(property: "min_price", type: "integer", example: 3500000),
                                        new OA\Property(property: "max_price", type: "integer", example: 4500000),
                                        new OA\Property(property: "description", type: "string", example: "Menggunakan semen standar, ubin keramik biasa, cat tembok standar."),
                                        new OA\Property(property: "created_at", type: "string", nullable: true, example: "2026-06-08 14:00:00"),
                                        new OA\Property(property: "updated_at", type: "string", nullable: true, example: "2026-06-08 14:00:00")
                                    ]
                                ))
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function price_estimate()
    {
    }
}
