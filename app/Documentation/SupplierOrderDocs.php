<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SupplierOrderDocs
{
    #[OA\Get(
        path: "/api/supplier/stats",
        summary: "Ambil Statistik Dashboard Supplier",
        description: "Mengambil data ringkasan statistik untuk dashboard toko supplier, termasuk total saldo aktif yang dapat ditarik, pesanan hari ini, total semua pesanan, dan total semua produk.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Statistik berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "total_saldo", type: "number", format: "float", example: 4500000.0, description: "Saldo bersih yang siap ditarik (pendapatan dikurangi withdraw)"),
                            new OA\Property(property: "today_orders", type: "integer", example: 3, description: "Jumlah pesanan masuk hari ini"),
                            new OA\Property(property: "total_orders", type: "integer", example: 48, description: "Jumlah seluruh pesanan sepanjang waktu"),
                            new OA\Property(property: "total_products", type: "integer", example: 12, description: "Total produk terdaftar milik supplier")
                        ])
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function stats() {}

    #[OA\Get(
        path: "/api/supplier/sales-analytics",
        summary: "Ambil Analitik Penjualan Supplier",
        description: "Mengambil data analitik penjualan terperinci, termasuk ringkasan total pendapatan, total produk terjual, jumlah pembeli unik, dan data grafik penjualan harian selama 7 hari terakhir.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Analitik penjualan berhasil dimuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "summary", type: "object", properties: [
                                new OA\Property(property: "total_revenue", type: "number", format: "float", example: 25000000.0),
                                new OA\Property(property: "total_orders", type: "integer", example: 45),
                                new OA\Property(property: "total_products_sold", type: "integer", example: 120),
                                new OA\Property(property: "total_buyers", type: "integer", example: 38)
                            ]),
                            new OA\Property(property: "sales_chart", type: "array", items: new OA\Items(type: "object"))
                        ])
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function salesAnalytics() {}

    #[OA\Get(
        path: "/api/supplier/orders",
        summary: "Ambil Daftar Pesanan Masuk Supplier",
        description: "Mengambil daftar seluruh pesanan yang masuk ke supplier beserta item produk detailnya.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar pesanan berhasil dimuat.",
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
    public function index() {}

    #[OA\Post(
        path: "/api/supplier/orders/update-status/{id}",
        summary: "Perbarui Status Pesanan",
        description: "Memperbarui status transaksi pesanan berdasarkan ID Pesanan, dan mengirimkan push notifikasi pemberitahuan ke aplikasi klien.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Pesanan (orders.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status", type: "string", example: "PROCESSED", description: "Status pesanan baru (misal: PAID, PROCESSED, SHIPPED, COMPLETED, dsb)")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Status pesanan berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Status pesanan berhasil diperbarui")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Pesanan tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function updateStatus() {}

    #[OA\Post(
        path: "/api/supplier/withdraw",
        summary: "Ajukan Penarikan Dana (Withdraw)",
        description: "Mengajukan penarikan dana/saldo aktif supplier ke rekening bank tertentu. Saldo supplier akan langsung didebit secara otomatis saat pengajuan disetujui.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "amount", type: "number", example: 500000, description: "Jumlah nominal dana yang ingin ditarik"),
                    new OA\Property(property: "bank_name", type: "string", example: "BCA", description: "Nama Bank tujuan"),
                    new OA\Property(property: "account_number", type: "string", example: "1234567890", description: "Nomor rekening tujuan"),
                    new OA\Property(property: "account_name", type: "string", example: "Budi Utomo", description: "Nama pemilik rekening")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Penarikan berhasil disetujui otomatis.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Penarikan dana berhasil disetujui secara otomatis dan saldo telah dipotong.")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Gagal memproses penarikan / Saldo tidak cukup."),
            new OA\Response(response: 404, description: "Data supplier tidak ditemukan."),
            new OA\Response(response: 401, description: "Unauthorized.")
        ]
    )]
    public function withdraw() {}

    #[OA\Get(
        path: "/api/supplier/withdrawals",
        summary: "Ambil Riwayat Penarikan Dana",
        description: "Mengambil daftar seluruh riwayat pengajuan penarikan dana (withdraw) beserta status persetujuannya.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar riwayat penarikan dimuat.",
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
    public function withdrawalHistory() {}

    #[OA\Get(
        path: "/api/supplier/transactions",
        summary: "Ambil Riwayat Transaksi Keuangan",
        description: "Mengambil daftar seluruh mutasi riwayat transaksi kredit/debit keuangan toko supplier.",
        tags: ["Supplier Orders"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar riwayat transaksi keuangan dimuat.",
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
    public function transactionHistory() {}
}
