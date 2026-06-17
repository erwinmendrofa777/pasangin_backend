<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class ChatDocs
{
    #[OA\Get(
        path: "/api/chat/all/{userId}",
        summary: "Ambil Semua Daftar Obrolan User",
        description: "Mengambil semua daftar percakapan/obrolan (conversations) milik pengguna (client/tukang/supplier) berdasarkan ID user.",
        tags: ["Chat (Obrolan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userId",
                in: "path",
                description: "ID User pemilik obrolan",
                required: true,
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar obrolan ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "conversations", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "client_id", type: "integer", example: 10),
                                new OA\Property(property: "client_type", type: "string", example: "client"),
                                new OA\Property(property: "admin_id", type: "integer", nullable: true, example: null),
                                new OA\Property(property: "title", type: "string", example: "Tanya estimasi renovasi"),
                                new OA\Property(property: "category", type: "string", example: "general"),
                                new OA\Property(property: "status", type: "string", example: "open"),
                                new OA\Property(property: "unread_by_admin_count", type: "integer", example: 2),
                                new OA\Property(property: "unread_by_client_count", type: "integer", example: 0),
                                new OA\Property(property: "last_message_preview", type: "string", example: "Kira-kira berapa biayanya?"),
                                new OA\Property(property: "last_message_at", type: "string", example: "2026-06-08 13:50:00"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 13:40:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 13:50:00"),
                                new OA\Property(property: "sort_time", type: "string", example: "2026-06-08 13:50:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token tidak valid atau user ID tidak sesuai dengan token."
            ),
            new OA\Response(
                response: 500,
                description: "Database error."
            )
        ]
    )]
    public function getAllConversationsForUser()
    {
    }

    #[OA\Post(
        path: "/api/chat/create_or_get",
        summary: "Buat Tiket/Obrolan Baru",
        description: "Membuat percakapan/tiket bantuan baru dengan judul dan kategori tertentu.",
        tags: ["Chat (Obrolan)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Pertanyaan seputar RAB", description: "Judul/topik obrolan"),
                    new OA\Property(property: "category", type: "string", example: "technical", enum: ["technical", "accounting", "general"], description: "Kategori bantuan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Obrolan baru berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Obrolan baru berhasil dibuat."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 2),
                            new OA\Property(property: "client_id", type: "integer", example: 10),
                            new OA\Property(property: "client_type", type: "string", example: "client"),
                            new OA\Property(property: "title", type: "string", example: "Pertanyaan seputar RAB"),
                            new OA\Property(property: "status", type: "string", example: "open"),
                            new OA\Property(property: "category", type: "string", example: "technical"),
                            new OA\Property(property: "created_at", type: "string", example: "2026-06-08 13:51:00"),
                            new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 13:51:00")
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Server error."
            )
        ]
    )]
    public function createOrGetConversation()
    {
    }

    #[OA\Get(
        path: "/api/chat/messages/{conversationId}",
        summary: "Ambil Riwayat Pesan Obrolan",
        description: "Mengambil semua daftar pesan dalam suatu obrolan berdasarkan ID percakapan, sekaligus menandai semua pesan admin yang belum dibaca menjadi terbaca.",
        tags: ["Chat (Obrolan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "conversationId",
                in: "path",
                description: "ID Percakapan (Conversation ID)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pesan berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "conversation_id", type: "integer", example: 1),
                            new OA\Property(property: "messages", type: "array", items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 15),
                                    new OA\Property(property: "conversation_id", type: "integer", example: 1),
                                    new OA\Property(property: "sender_id", type: "integer", example: 10),
                                    new OA\Property(property: "sender_type", type: "string", example: "client"),
                                    new OA\Property(property: "body", type: "string", example: "Halo, saya ingin bertanya."),
                                    new OA\Property(property: "file_url", type: "string", nullable: true, example: null),
                                    new OA\Property(property: "message_type", type: "string", example: "text"),
                                    new OA\Property(property: "latitude", type: "number", format: "double", nullable: true, example: null),
                                    new OA\Property(property: "longitude", type: "number", format: "double", nullable: true, example: null),
                                    new OA\Property(property: "is_read_by_admin", type: "integer", example: 0),
                                    new OA\Property(property: "is_read_by_client", type: "integer", example: 1),
                                    new OA\Property(property: "created_at", type: "string", example: "2026-06-08 13:45:00")
                                ]
                            ))
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Server error."
            )
        ]
    )]
    public function getMessages()
    {
    }

    #[OA\Post(
        path: "/api/chat/send",
        summary: "Kirim Pesan",
        description: "Mengirimkan pesan baru ke obrolan tertentu. Mendukung teks biasa, koordinat lokasi, maupun unggahan berkas gambar/video/file biasa (menggunakan multipart/form-data).",
        tags: ["Chat (Obrolan)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "conversation_id", type: "integer", example: 1, description: "ID percakapan tujuan"),
                        new OA\Property(property: "body", type: "string", example: "Ini pesan saya", description: "Isi pesan teks"),
                        new OA\Property(property: "attachment_type", type: "string", example: "image", enum: ["image", "video", "file"], description: "Tipe lampiran (opsional jika mengunggah file)"),
                        new OA\Property(property: "latitude", type: "string", example: "-6.2088", description: "Latitude lokasi (untuk tipe pesan lokasi)"),
                        new OA\Property(property: "longitude", type: "string", example: "106.8456", description: "Longitude lokasi (untuk tipe pesan lokasi)"),
                        new OA\Property(property: "file", type: "string", format: "binary", description: "File lampiran gambar/video/berkas biasa (opsional)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Pesan berhasil terkirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 16),
                            new OA\Property(property: "conversation_id", type: "integer", example: 1),
                            new OA\Property(property: "sender_id", type: "integer", example: 10),
                            new OA\Property(property: "sender_type", type: "string", example: "client"),
                            new OA\Property(property: "body", type: "string", example: "Ini pesan saya"),
                            new OA\Property(property: "file_url", type: "string", nullable: true, example: null),
                            new OA\Property(property: "message_type", type: "string", example: "text"),
                            new OA\Property(property: "latitude", type: "number", format: "double", nullable: true, example: null),
                            new OA\Property(property: "longitude", type: "number", format: "double", nullable: true, example: null),
                            new OA\Property(property: "is_read_by_admin", type: "integer", example: 0),
                            new OA\Property(property: "is_read_by_client", type: "integer", example: 1),
                            new OA\Property(property: "created_at", type: "string", example: "2026-06-08 13:51:30")
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Obrolan telah ditutup atau parameter tidak lengkap (missing conversation_id)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Server error."
            )
        ]
    )]
    public function sendMessage()
    {
    }
}
