<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class VoucherDocs
{
    #[OA\Get(
        path: "/api/vouchers",
        summary: "Ambil Daftar Voucher Aktif",
        description: "Mengambil daftar seluruh voucher belanja/diskon yang berstatus aktif dan belum melewati tanggal masa berlaku (expired). Endpoint ini membutuhkan otorisasi JWT.",
        tags: ["Vouchers"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar voucher berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Daftar Voucher"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "code", type: "string", example: "DISKON50K", description: "Kode voucher unik untuk klaim"),
                                new OA\Property(property: "name", type: "string", example: "Voucher Diskon Pelanggan Baru"),
                                new OA\Property(property: "description", type: "string", example: "Diskon nominal Rp 50.000 untuk transaksi pertama."),
                                new OA\Property(property: "discount_nominal", type: "string", example: "50000", description: "Nominal potongan harga"),
                                new OA\Property(property: "valid_until", type: "string", format: "date", example: "2026-12-31", description: "Batas akhir masa berlaku voucher"),
                                new OA\Property(property: "image", type: "string", example: "voucher_new_user.png", description: "Nama file gambar voucher"),
                                new OA\Property(property: "is_active", type: "integer", example: 1, description: "Status aktif voucher (1 = Aktif, 0 = Nonaktif)"),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/vouchers/voucher_new_user.png", description: "URL lengkap gambar voucher untuk ditampilkan di aplikasi mobile/frontend"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 12:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 12:00:00", nullable: true)
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized. Token JWT tidak valid atau tidak disertakan.")
        ]
    )]
    public function index() {}
}
