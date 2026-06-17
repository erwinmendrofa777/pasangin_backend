<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class NotificationDocs
{
    #[OA\Get(
        path: "/api/chat/notifications",
        summary: "Ambil Riwayat Notifikasi Chat & HP",
        description: "Mengambil riwayat notifikasi untuk user (client, tukang, supplier) di HP/aplikasi berdasarkan target_type.",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "target",
                in: "query",
                description: "Tipe target penerima notifikasi (client, tukang, supplier)",
                required: false,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"], default: "client")
            ),
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "Jumlah limit data notifikasi yang ditarik",
                required: false,
                schema: new OA\Schema(type: "integer", default: 20)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Riwayat notifikasi berhasil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "target", type: "string", example: "client"),
                        new OA\Property(property: "count", type: "integer", example: 1),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "10"),
                                new OA\Property(property: "title", type: "string", example: "Pesanan Baru"),
                                new OA\Property(property: "message", type: "string", example: "Anda mendapatkan pesanan baru! Silakan cek notifikasi pesanan Anda."),
                                new OA\Property(property: "target_type", type: "string", example: "client"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 14:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Parameter target tidak valid."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function getChatNotifications()
    {
    }

    #[OA\Get(
        path: "/api/{userType}/notifications/{userId}",
        summary: "Ambil Daftar Notifikasi Dinamis",
        description: "Mengambil daftar notifikasi untuk tipe user tertentu dengan status keterbacaan (is_read).",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            ),
            new OA\Parameter(
                name: "userId",
                in: "path",
                description: "ID User",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Notifikasi berhasil ditarik.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Notifikasi client berhasil ditarik."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "12"),
                                new OA\Property(property: "title", type: "string", example: "Pembayaran Berhasil"),
                                new OA\Property(property: "message", type: "string", example: "Terima kasih! Pembayaran Anda untuk tagihan project_invoices-5-1780891827 telah kami terima."),
                                new OA\Property(property: "target_type", type: "string", example: "client"),
                                new OA\Property(property: "is_read", type: "integer", example: 0, description: "1 jika sudah dibaca, 0 jika belum dibaca"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Parameter tidak lengkap."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "User tidak ditemukan di database."
            )
        ]
    )]
    public function getUserNotifications()
    {
    }

    #[OA\Post(
        path: "/api/{userType}/notifications/mark-read",
        summary: "Tandai Satu Notifikasi Dibaca",
        description: "Menandai satu notifikasi tertentu sebagai sudah dibaca oleh user.",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user_id", type: "integer", example: 5, description: "ID User"),
                    new OA\Property(property: "notification_id", type: "integer", example: 12, description: "ID Notifikasi")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tanda baca berhasil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tanda baca berhasil.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Data tidak lengkap."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function markAsRead()
    {
    }

    #[OA\Post(
        path: "/api/{userType}/notifications/mark-all-read",
        summary: "Tandai Semua Notifikasi Dibaca",
        description: "Menandai seluruh notifikasi milik tipe user tertentu sebagai sudah dibaca.",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user_id", type: "integer", example: 5, description: "ID User")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Semua notifikasi berhasil ditandai dibaca.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Semua sudah dibaca.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "User ID dibutuhkan."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function markAllAsRead()
    {
    }

    #[OA\Delete(
        path: "/api/{userType}/notifications/delete/{notifId}/{userId}",
        summary: "Hapus Notifikasi Personal",
        description: "Menghapus notifikasi secara personal untuk user tertentu agar tidak muncul kembali.",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            ),
            new OA\Parameter(
                name: "notifId",
                in: "path",
                description: "ID Notifikasi yang dihapus",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "userId",
                in: "path",
                description: "ID User",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Notifikasi berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Dihapus.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Internal server error."
            )
        ]
    )]
    public function deleteNotification()
    {
    }

    #[OA\Get(
        path: "/api/{userType}/notifications/unread-count/{userId}",
        summary: "Hitung Notifikasi Belum Dibaca",
        description: "Menghitung jumlah notifikasi yang belum dibaca (untuk badge icon pada aplikasi).",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            ),
            new OA\Parameter(
                name: "userId",
                in: "path",
                description: "ID User",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Jumlah unread berhasil dihitung.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "unread", type: "integer", example: 3)
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function unreadCount()
    {
    }

    #[OA\Post(
        path: "/api/{userType}/notifications/toggle",
        summary: "Toggle Notifikasi ON/OFF Per-Device",
        description: "Mengaktifkan atau menonaktifkan penerimaan notifikasi FCM pada device tertentu.",
        tags: ["Notification (Notifikasi)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "userType",
                in: "path",
                description: "Tipe user (client, tukang, supplier)",
                required: true,
                schema: new OA\Schema(type: "string", enum: ["client", "tukang", "supplier"])
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "token", type: "string", example: "FCM_TOKEN_XYZ_123...", description: "Token FCM Device"),
                    new OA\Property(property: "status", type: "boolean", example: true, description: "true untuk aktif, false untuk dinonaktifkan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Pengaturan notifikasi berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Notifikasi berhasil diaktifkan.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Parameter tidak lengkap."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Token FCM tidak ditemukan atau tipe user tidak sesuai."
            )
        ]
    )]
    public function toggleNotification()
    {
    }
}
