<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class CartDocs
{
    #[OA\Get(
        path: "/api/cart",
        summary: "Ambil Isi Keranjang",
        description: "Mengambil semua daftar item produk yang ada di dalam keranjang belanja milik user yang sedang login.",
        tags: ["Cart (Keranjang)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Keranjang ditemukan atau kosong.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Keranjang Pesanan ditemukan"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "1"),
                                new OA\Property(property: "user_id", type: "string", example: "10"),
                                new OA\Property(property: "product_id", type: "string", example: "5"),
                                new OA\Property(property: "quantity", type: "string", example: "2"),
                                new OA\Property(property: "name", type: "string", example: "Semen Tiga Roda 50kg"),
                                new OA\Property(property: "price", type: "string", example: "65000"),
                                new OA\Property(property: "photo", type: "string", example: "semen.jpg"),
                                new OA\Property(property: "supplier_name", type: "string", example: "Toko Bangunan Berkah"),
                                new OA\Property(property: "supplier_id", type: "string", example: "3"),
                                new OA\Property(property: "longitude", type: "string", example: "106.8456"),
                                new OA\Property(property: "latitude", type: "string", example: "-6.2088"),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.jpg")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized. Token JWT tidak valid atau tidak disertakan."
            )
        ]
    )]
    public function index()
    {
    }

    #[OA\Post(
        path: "/api/cart/add",
        summary: "Tambah Ke Keranjang",
        description: "Menambahkan produk tertentu ke keranjang belanja. Jika produk sudah ada di keranjang, kuantitas (quantity) akan bertambah otomatis.",
        tags: ["Cart (Keranjang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "product_id", type: "integer", example: 5, description: "ID Produk"),
                    new OA\Property(property: "quantity", type: "integer", example: 1, description: "Jumlah barang yang ditambahkan")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil ditambahkan ke keranjang.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Berhasil ditambahkan ke keranjang")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal (parameter product_id atau quantity salah)."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function add()
    {
    }

    #[OA\Post(
        path: "/api/cart/update",
        summary: "Update Kuantitas (Qty) Keranjang",
        description: "Mengubah/mengupdate jumlah kuantitas produk terpilih di dalam keranjang berdasarkan ID cart item.",
        tags: ["Cart (Keranjang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1, description: "ID dari item keranjang (cart.id)"),
                    new OA\Property(property: "quantity", type: "integer", example: 3, description: "Jumlah kuantitas baru")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Kuantitas berhasil diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Qty diperbarui")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal."
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "ID item tidak ditemukan."
            )
        ]
    )]
    public function update()
    {
    }

    #[OA\Post(
        path: "/api/cart/delete",
        summary: "Hapus Item Dari Keranjang",
        description: "Menghapus item produk tertentu dari keranjang belanja berdasarkan ID cart item.",
        tags: ["Cart (Keranjang)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1, description: "ID dari item keranjang (cart.id) yang ingin dihapus")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Item dihapus")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Item tidak ditemukan."
            )
        ]
    )]
    public function delete()
    {
    }
}
