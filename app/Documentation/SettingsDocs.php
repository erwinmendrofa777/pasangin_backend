<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class SettingsDocs
{
    #[OA\Get(
        path: "/api/settings/tax-fee",
        summary: "Ambil Pengaturan Pajak dan Biaya Aplikasi",
        description: "Mengambil konfigurasi aktif mengenai persentase pajak (tax rate) dan tipe serta nominal biaya aplikasi (app fee) untuk kalkulasi harga di aplikasi seluler/klien.",
        tags: ["Settings (Pengaturan)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pengaturan berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil pengaturan pajak dan biaya aplikasi."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "tax_rate", type: "number", format: "float", example: 0.11, description: "Persentase Pajak (misal 0.11 untuk PPn 11%)"),
                            new OA\Property(property: "app_fee_type", type: "string", example: "flat", description: "Tipe biaya aplikasi ('flat' atau 'percentage')"),
                            new OA\Property(property: "app_fee_value", type: "number", format: "float", example: 2000, description: "Nilai/nominal biaya aplikasi")
                        ])
                    ]
                )
            )
        ]
    )]
    public function getTaxFeeSettings() {}
}
