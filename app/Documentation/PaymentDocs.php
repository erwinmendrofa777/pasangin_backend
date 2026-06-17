<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class PaymentDocs
{
    #[OA\Get(
        path: "/api/payment/token/{id}",
        summary: "Ambil Token Pembayaran Belanja Produk (TRX-...)",
        description: "Mengambil token/redirect URL Midtrans Snap untuk pembayaran transaksi belanja produk/material proyek Pasangin berdasarkan ID transaksi.",
        tags: ["Payments (Pembayaran)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Transaksi (tabel transactions.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 8)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Token Midtrans berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "transaction_id", type: "string", example: "TRX-1780900321-5"),
                        new OA\Property(property: "midtrans_order_id", type: "string", example: "TRX-1780900321-5-1780900325"),
                        new OA\Property(property: "gross_amount", type: "integer", example: 350000),
                        new OA\Property(property: "redirect_url", type: "string", example: "https://app.sandbox.midtrans.com/snap/v2/vtweb/abcdefgh"),
                        new OA\Property(property: "order_id", type: "string", example: "TRX-1780900321-5-1780900325")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Transaksi atau User tidak ditemukan."
            ),
            new OA\Response(
                response: 500,
                description: "Gagal Midtrans."
            )
        ]
    )]
    public function getPaymentToken()
    {
    }

    #[OA\Get(
        path: "/api/payment/token/design/{invoiceId}/{voucherCode}",
        summary: "Ambil Token Pembayaran Tagihan Desain",
        description: "Mengambil token/redirect URL Midtrans Snap untuk pembayaran invoice termin pengerjaan desain/survey.",
        tags: ["Payments (Pembayaran)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "invoiceId",
                in: "path",
                description: "ID Tagihan Desain (tabel project_invoices.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 5)
            ),
            new OA\Parameter(
                name: "voucherCode",
                in: "path",
                description: "Kode voucher diskon (opsional)",
                required: false,
                schema: new OA\Schema(type: "string", example: "PROMOHEMAT")
            ),
            new OA\Parameter(
                name: "voucher_code",
                in: "query",
                description: "Kode voucher diskon (opsional, jika tidak dikirim via path)",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Token Midtrans berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "invoice_amount", type: "integer", example: 200000),
                        new OA\Property(property: "discount_amount", type: "integer", example: 50000),
                        new OA\Property(property: "gross_amount", type: "integer", example: 150000),
                        new OA\Property(property: "redirect_url", type: "string", example: "https://app.sandbox.midtrans.com/snap/v2/vtweb/abcdefgh"),
                        new OA\Property(property: "order_id", type: "string", example: "project_invoices-5-1780891827")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Kode voucher tidak valid atau sudah kedaluwarsa."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Tagihan Desain tidak ditemukan."
            )
        ]
    )]
    public function getDesignPaymentToken()
    {
    }

    #[OA\Get(
        path: "/api/payment/token/construction/{invoiceId}/{voucherCode}",
        summary: "Ambil Token Pembayaran Tagihan Konstruksi",
        description: "Mengambil token/redirect URL Midtrans Snap untuk pembayaran invoice termin pengerjaan proyek konstruksi fisik.",
        tags: ["Payments (Pembayaran)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "invoiceId",
                in: "path",
                description: "ID Tagihan Konstruksi (tabel construction_invoices.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 3)
            ),
            new OA\Parameter(
                name: "voucherCode",
                in: "path",
                description: "Kode voucher diskon (opsional)",
                required: false,
                schema: new OA\Schema(type: "string", example: "PROMOHEMAT")
            ),
            new OA\Parameter(
                name: "voucher_code",
                in: "query",
                description: "Kode voucher diskon (opsional, jika tidak dikirim via path)",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Token Midtrans berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "invoice_amount", type: "integer", example: 1500000),
                        new OA\Property(property: "discount_amount", type: "integer", example: 100000),
                        new OA\Property(property: "gross_amount", type: "integer", example: 1400000),
                        new OA\Property(property: "redirect_url", type: "string", example: "https://app.sandbox.midtrans.com/snap/v2/vtweb/abcdefgh"),
                        new OA\Property(property: "order_id", type: "string", example: "construction_invoices-3-1780891827")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Kode voucher tidak valid."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Tagihan Konstruksi tidak ditemukan."
            )
        ]
    )]
    public function getConstructionPaymentToken()
    {
    }

    #[OA\Get(
        path: "/api/payment/token/renovation/{invoiceId}/{voucherCode}",
        summary: "Ambil Token Pembayaran Tagihan Renovasi",
        description: "Mengambil token/redirect URL Midtrans Snap untuk pembayaran invoice termin pengerjaan proyek renovasi.",
        tags: ["Payments (Pembayaran)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "invoiceId",
                in: "path",
                description: "ID Tagihan Renovasi (tabel renovation_invoices.id)",
                required: true,
                schema: new OA\Schema(type: "integer", example: 4)
            ),
            new OA\Parameter(
                name: "voucherCode",
                in: "path",
                description: "Kode voucher diskon (opsional)",
                required: false,
                schema: new OA\Schema(type: "string", example: "PROMOHEMAT")
            ),
            new OA\Parameter(
                name: "voucher_code",
                in: "query",
                description: "Kode voucher diskon (opsional, jika tidak dikirim via path)",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Token Midtrans berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "invoice_amount", type: "integer", example: 800000),
                        new OA\Property(property: "discount_amount", type: "integer", example: 50000),
                        new OA\Property(property: "gross_amount", type: "integer", example: 750000),
                        new OA\Property(property: "redirect_url", type: "string", example: "https://app.sandbox.midtrans.com/snap/v2/vtweb/abcdefgh"),
                        new OA\Property(property: "order_id", type: "string", example: "renovation_invoices-4-1780891827")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Kode voucher tidak valid."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Tagihan Renovasi tidak ditemukan."
            )
        ]
    )]
    public function getRenovationPaymentToken()
    {
    }

    #[OA\Get(
        path: "/api/payment/check_status/{orderId}",
        summary: "Cek Status Pembayaran Midtrans Manual",
        description: "Mengecek status transaksi secara manual ke server Midtrans API untuk memperbarui status transaksi di database lokal (untuk disinkronkan ke aplikasi mobile).",
        tags: ["Payments (Pembayaran)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "orderId",
                in: "path",
                description: "Order ID lengkap yang terdaftar di Midtrans (e.g. project_invoices-5-1780891827)",
                required: true,
                schema: new OA\Schema(type: "string", example: "project_invoices-5-1780891827")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Status berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Status Updated")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Order ID kosong."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function checkStatus()
    {
    }

    #[OA\Post(
        path: "/api/payment/notification",
        summary: "Midtrans Payment Webhook Notification",
        description: "Menerima notifikasi webhook instan dari Midtrans untuk memperbarui status pembayaran termin tagihan/invoice di database (project_invoices).",
        tags: ["Payment Webhook (Notifikasi Pembayaran)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "transaction_status", type: "string", example: "settlement", description: "Status transaksi (capture, settlement, pending, deny, expire, cancel)"),
                    new OA\Property(property: "payment_type", type: "string", example: "bank_transfer", description: "Metode pembayaran"),
                    new OA\Property(property: "order_id", type: "string", example: "project_invoices-5-1780891827", description: "Order ID unik dari sistem"),
                    new OA\Property(property: "fraud_status", type: "string", nullable: true, example: "accept", description: "Status fraud deteksi"),
                    new OA\Property(property: "gross_amount", type: "string", example: "150000.00", description: "Total pembayaran bruto"),
                    new OA\Property(property: "signature_key", type: "string", example: "8ce95015b6d5abc74e1...", description: "Signature key validasi dari Midtrans")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Notifikasi berhasil diproses.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(property: "message", type: "string", example: "Notification processed")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Invoice tidak ditemukan di database.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Invoice not found")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan server.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Library not found")
                    ]
                )
            )
        ]
    )]
    public function notification()
    {
    }
}
