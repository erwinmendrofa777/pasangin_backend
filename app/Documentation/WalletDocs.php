<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class WalletDocs
{
    #[OA\Get(
        path: "/api/tukang/wallet/{tukang_id}",
        summary: "Ambil Saldo & Riwayat Transaksi Tukang",
        description: "Mengambil informasi saldo dompet (balance) tukang beserta seluruh riwayat transaksi keuangan tukang berdasarkan ID Tukang. Endpoint ini terproteksi JWT.",
        tags: ["Tukang Wallet"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 3)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Saldo dan riwayat transaksi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "balance", type: "number", format: "float", example: 750000, description: "Saldo dompet tukang saat ini"),
                        new OA\Property(property: "history", type: "array", description: "Daftar riwayat transaksi", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 12),
                                new OA\Property(property: "tukang_id", type: "integer", example: 3),
                                new OA\Property(property: "type", type: "string", example: "credit", description: "Jenis transaksi (credit = masuk, debit = keluar)"),
                                new OA\Property(property: "amount", type: "number", format: "float", example: 500000, description: "Jumlah nominal transaksi"),
                                new OA\Property(property: "description", type: "string", example: "Pembayaran proyek konstruksi #45"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 10:00:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid."),
            new OA\Response(response: 500, description: "Internal Server Error.")
        ]
    )]
    public function getWalletInfo() {}

    #[OA\Get(
        path: "/api/tukang/withdrawal-requests/{tukang_id}",
        summary: "Ambil Saldo & Riwayat Permintaan Penarikan",
        description: "Mengambil informasi saldo dompet tukang beserta daftar seluruh riwayat permintaan penarikan dana (withdrawal requests) yang pernah diajukan oleh tukang berdasarkan ID Tukang. Endpoint ini terproteksi JWT.",
        tags: ["Tukang Wallet"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "tukang_id",
                in: "path",
                description: "ID Tukang",
                required: true,
                schema: new OA\Schema(type: "integer", example: 3)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Saldo dan riwayat permintaan penarikan berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "balance", type: "number", format: "float", example: 750000, description: "Saldo dompet tukang saat ini"),
                        new OA\Property(property: "history", type: "array", description: "Daftar riwayat permintaan penarikan", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 5),
                                new OA\Property(property: "tukang_id", type: "integer", example: 3),
                                new OA\Property(property: "amount", type: "number", format: "float", example: 250000, description: "Jumlah dana yang diminta untuk ditarik"),
                                new OA\Property(property: "bank_name", type: "string", example: "BCA"),
                                new OA\Property(property: "account_number", type: "string", example: "1234567890"),
                                new OA\Property(property: "account_name", type: "string", example: "Budi Tukang"),
                                new OA\Property(property: "status", type: "string", example: "pending", enum: ["pending", "approved", "rejected"], description: "Status permintaan penarikan"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-07 09:30:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid."),
            new OA\Response(response: 500, description: "Internal Server Error.")
        ]
    )]
    public function getWithdrawalRequests() {}

    #[OA\Post(
        path: "/api/tukang/withdraw",
        summary: "Ajukan Permintaan Penarikan Dana",
        description: "Mengajukan permintaan penarikan saldo dompet tukang ke rekening bank yang ditentukan. Sistem akan memverifikasi kecukupan saldo sebelum mencatat permintaan. Status awal permintaan adalah 'pending' hingga disetujui oleh admin. Endpoint ini terproteksi JWT.",
        tags: ["Tukang Wallet"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["tukang_id", "amount", "bank_name", "account_number", "account_name"],
                properties: [
                    new OA\Property(property: "tukang_id", type: "integer", example: 3, description: "ID Tukang yang mengajukan penarikan"),
                    new OA\Property(property: "amount", type: "number", format: "float", example: 250000, description: "Jumlah dana yang ingin ditarik (harus ≤ saldo)"),
                    new OA\Property(property: "bank_name", type: "string", example: "BCA", description: "Nama bank tujuan transfer"),
                    new OA\Property(property: "account_number", type: "string", example: "1234567890", description: "Nomor rekening bank tujuan"),
                    new OA\Property(property: "account_name", type: "string", example: "Budi Tukang", description: "Nama pemilik rekening bank tujuan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permintaan penarikan berhasil dikirim.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permintaan penarikan berhasil dikirim!")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Saldo tidak mencukupi."),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid."),
            new OA\Response(response: 500, description: "Internal Server Error.")
        ]
    )]
    public function requestWithdrawal() {}
}
