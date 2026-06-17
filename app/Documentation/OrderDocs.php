<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class OrderDocs
{
    #[OA\Post(
        path: "/api/checkout",
        summary: "Checkout Pesanan / Buat Transaksi Baru",
        description: "Melakukan checkout produk-produk di keranjang belanja user dan membuat transaksi pembayaran Midtrans tunggal. Mendukung mode multi-supplier (dengan pembagian fee dan pesanan) maupun legacy single-supplier.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "recipient_name", type: "string", example: "Budi Santoso", description: "Nama penerima paket"),
                    new OA\Property(property: "recipient_phone", type: "string", example: "081234567890", description: "Nomor telepon penerima"),
                    new OA\Property(property: "shipping_address", type: "string", example: "Jl. Merdeka No. 10, Jakarta Pusat", description: "Alamat pengiriman lengkap"),
                    new OA\Property(property: "latitude", type: "string", example: "-6.2088", description: "Garis lintang alamat"),
                    new OA\Property(property: "longitude", type: "string", example: "106.8456", description: "Garis bujur alamat"),
                    new OA\Property(property: "total_price", type: "number", format: "float", example: 350000.00, description: "Total harga keseluruhan (produk + ongkir + fee - diskon)"),
                    new OA\Property(property: "voucher_code", type: "string", nullable: true, example: "PROMOHEMAT", description: "Kode voucher yang digunakan"),
                    new OA\Property(property: "discount_amount", type: "number", format: "float", example: 50000.00, description: "Jumlah potongan harga diskon"),
                    new OA\Property(
                        property: "selected_cart_ids", 
                        type: "array", 
                        items: new OA\Items(type: "integer"), 
                        example: [1, 2],
                        description: "Daftar ID keranjang (cart) yang akan dicheckout. Jika kosong, akan mencheckout seluruh isi keranjang."
                    ),
                    new OA\Property(
                        property: "shipping_fees", 
                        type: "object", 
                        example: ["2" => 15000, "5" => 20000],
                        description: "Map biaya pengiriman per supplier_id untuk multi-supplier mode."
                    ),
                    new OA\Property(
                        property: "app_fees", 
                        type: "object", 
                        example: ["2" => 2000, "5" => 2000],
                        description: "Map biaya aplikasi per supplier_id untuk multi-supplier mode."
                    ),
                    new OA\Property(
                        property: "tax_amounts", 
                        type: "object", 
                        example: ["2" => 1500, "5" => 2000],
                        description: "Map jumlah pajak per supplier_id untuk multi-supplier mode."
                    ),
                    new OA\Property(property: "shipping_fee", type: "number", format: "float", example: 15000.00, description: "Legacy/Flat ongkir (hanya digunakan jika shipping_fees tidak dikirim)"),
                    new OA\Property(property: "app_fee", type: "number", format: "float", example: 2000.00, description: "Legacy/Flat biaya aplikasi"),
                    new OA\Property(property: "tax_amount", type: "number", format: "float", example: 1000.00, description: "Legacy/Flat jumlah pajak")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Checkout berhasil dan transaksi Midtrans berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "transaction_id", type: "string", example: "TRX-1780900321-5", description: "ID Transaksi unik di database"),
                        new OA\Property(property: "midtrans_order_id", type: "string", example: "TRX-1780900321-5-1780900325", description: "Order ID yang dikirim ke Midtrans"),
                        new OA\Property(property: "order_count", type: "integer", example: 2, description: "Jumlah order/pesanan terbuat (karena multi-supplier)"),
                        new OA\Property(property: "orders", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "db_id", type: "integer", example: 15),
                                new OA\Property(property: "order_id", type: "string", example: "PASANGIN-1780900321-5-1"),
                                new OA\Property(property: "supplier_id", type: "integer", example: 2),
                                new OA\Property(property: "total_price", type: "number", example: 150000.00)
                            ]
                        )),
                        new OA\Property(property: "redirect_url", type: "string", example: "https://app.sandbox.midtrans.com/snap/v2/vtweb/12345678", description: "Link pembayaran Snap Midtrans")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Keranjang kosong atau validasi stok gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error / Gagal Midtrans."
            )
        ]
    )]
    public function checkout()
    {
    }

    #[OA\Get(
        path: "/api/orders/history",
        summary: "Ambil Riwayat Pesanan User",
        description: "Mengambil daftar seluruh pesanan (orders) milik user yang saat ini sedang login beserta item di dalamnya.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar riwayat pesanan berhasil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "15"),
                                new OA\Property(property: "transaction_id", type: "string", example: "TRX-1780900321-5"),
                                new OA\Property(property: "order_id", type: "string", example: "PASANGIN-1780900321-5-1"),
                                new OA\Property(property: "status", type: "string", example: "UNPAID", description: "Status pesanan (UNPAID, PAID, COMPLETED, CANCELLED)"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:10:00"),
                                new OA\Property(property: "recipient_name", type: "string", example: "Budi Santoso"),
                                new OA\Property(property: "recipient_phone", type: "string", example: "081234567890"),
                                new OA\Property(property: "shipping_address", type: "string", example: "Jl. Merdeka No. 10"),
                                new OA\Property(property: "total_price", type: "string", example: "150000.00"),
                                new OA\Property(property: "discount_amount", type: "string", example: "0.00"),
                                new OA\Property(property: "tax_amount", type: "string", example: "1500.00"),
                                new OA\Property(property: "app_fee", type: "string", example: "2000.00"),
                                new OA\Property(property: "shipping_fee", type: "string", example: "15000.00"),
                                new OA\Property(property: "id_transaction", type: "string", example: "8"),
                                new OA\Property(property: "transaction_total_amount", type: "string", example: "350000.00"),
                                new OA\Property(property: "items", type: "array", items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "quantity", type: "string", example: "1"),
                                        new OA\Property(property: "price", type: "string", example: "131500.00"),
                                        new OA\Property(property: "name", type: "string", example: "Kabel Eterna NYM 2x1.5"),
                                        new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/kabel.png")
                                    ]
                                ))
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function history()
    {
    }

    #[OA\Get(
        path: "/api/orders/detail/{orderId}",
        summary: "Ambil Rincian Item Pesanan",
        description: "Mengambil daftar item produk yang berada di dalam satu pesanan (order) tertentu.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "orderId",
                in: "path",
                description: "ID Pesanan (tabel orders.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Rincian item berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "34"),
                                new OA\Property(property: "order_id", type: "string", example: "15"),
                                new OA\Property(property: "product_id", type: "string", example: "7"),
                                new OA\Property(property: "quantity", type: "string", example: "1"),
                                new OA\Property(property: "price", type: "string", example: "131500.00"),
                                new OA\Property(property: "voucher_code", type: "string", example: "PROMOHEMAT"),
                                new OA\Property(property: "discount_amount", type: "string", example: "0.00"),
                                new OA\Property(property: "tax_amount", type: "string", example: "1500.00"),
                                new OA\Property(property: "app_fee", type: "string", example: "2000.00"),
                                new OA\Property(property: "shipping_fee", type: "string", example: "15000.00"),
                                new OA\Property(property: "name", type: "string", example: "Kabel Eterna NYM 2x1.5"),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/kabel.png")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function detail()
    {
    }

    #[OA\Delete(
        path: "/api/orders/delete/{transactionId}",
        summary: "Batalkan Transaksi / Pesanan",
        description: "Membatalkan seluruh pesanan di dalam satu transaksi tertentu. Jika statusnya masih UNPAID/PENDING, stok barang akan dikembalikan ke database.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "transactionId",
                in: "path",
                description: "ID Transaksi (tabel transactions.transaction_id)",
                required: true,
                schema: new OA\Schema(type: "string", example: "TRX-1780900321-5")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Transaksi berhasil dibatalkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Transaksi berhasil dibatalkan.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Transaksi atau pesanan tidak ditemukan."
            )
        ]
    )]
    public function delete()
    {
    }

    #[OA\Get(
        path: "/api/orders/transaction-detail/{transactionId}",
        summary: "Ambil Detail Transaksi & Pesanan Terkait",
        description: "Mengambil data detail transaksi pembayaran tunggal beserta seluruh daftar pesanan (orders) dan item produk di dalamnya.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "transactionId",
                in: "path",
                description: "ID Transaksi (tabel transactions.transaction_id)",
                required: true,
                schema: new OA\Schema(type: "string", example: "TRX-1780900321-5")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail transaksi berhasil ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "transaction", type: "object", description: "Detail transaksi tunggal"),
                        new OA\Property(property: "orders", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "15"),
                                new OA\Property(property: "order_id", type: "string", example: "PASANGIN-1780900321-5-1"),
                                new OA\Property(property: "total_price", type: "string", example: "150000.00"),
                                new OA\Property(property: "items", type: "array", items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "product_id", type: "string", example: "7"),
                                        new OA\Property(property: "quantity", type: "string", example: "1"),
                                        new OA\Property(property: "price", type: "string", example: "131500.00"),
                                        new OA\Property(property: "name", type: "string", example: "Kabel Eterna NYM 2x1.5"),
                                        new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/kabel.png")
                                    ]
                                ))
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Transaksi tidak ditemukan."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function transactionDetail()
    {
    }

    #[OA\Post(
        path: "/api/orders/webhook-midtrans",
        summary: "Midtrans Webhook Callback untuk Pesanan Produk",
        description: "Menerima notifikasi instan dari Midtrans untuk memperbarui status transaksi/orders belanja produk Pasangin di database.",
        tags: ["Orders (Pesanan)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "order_id", type: "string", example: "TRX-1780900321-5-1780900325", description: "Order ID dari Midtrans"),
                    new OA\Property(property: "transaction_status", type: "string", example: "settlement", description: "Status transaksi Midtrans")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Webhook berhasil diproses.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Webhook processed")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Error webhook."
            )
        ]
    )]
    public function webhookMidtrans()
    {
    }

    #[OA\Get(
        path: "/api/orders/transaction-history",
        summary: "Ambil Riwayat Seluruh Transaksi",
        description: "Mengambil daftar seluruh transaksi pembayaran (tabel transactions) beserta daftar pesanan (orders) terkait di dalamnya.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Riwayat transaksi berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "8"),
                                new OA\Property(property: "transaction_id", type: "string", example: "TRX-1780900321-5"),
                                new OA\Property(property: "total_amount", type: "string", example: "350000.00"),
                                new OA\Property(property: "status", type: "string", example: "PAID"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:10:00"),
                                new OA\Property(property: "orders_detail", type: "array", items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "id", type: "string", example: "15"),
                                        new OA\Property(property: "order_id", type: "string", example: "PASANGIN-1780900321-5-1"),
                                        new OA\Property(property: "total_price", type: "string", example: "150000.00")
                                    ]
                                ))
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function transactionHistory()
    {
    }

    #[OA\Post(
        path: "/api/orders/complete/{orderId}",
        summary: "Konfirmasi Pesanan Diterima (Selesai)",
        description: "Mengonfirmasi bahwa pesanan (order) telah diterima oleh pembeli. Ini akan memperbarui status menjadi COMPLETED, meneruskan dana penjualan ke saldo supplier terkait, dan mencatat profit/tax ke saldo admin.",
        tags: ["Orders (Pesanan)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "orderId",
                in: "path",
                description: "ID Pesanan (tabel orders.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pesanan berhasil diselesaikan dan saldo telah diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pesanan berhasil diselesaikan dan saldo supplier telah diperbarui.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden. Bukan pemilik pesanan ini."
            ),
            new OA\Response(
                response: 404,
                description: "Pesanan tidak ditemukan."
            )
        ]
    )]
    public function complete()
    {
    }
}
