<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;

class ProductDocs
{
    #[OA\Get(
        path: "/api/products",
        summary: "Ambil Daftar Produk (Publik)",
        description: "Mengambil daftar seluruh produk yang berstatus aktif. Mendukung pencarian, filter berdasarkan wilayah kota supplier, dan paginasi.",
        tags: ["Products (Produk)"],
        parameters: [
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "Kata kunci pencarian nama produk",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "region",
                in: "query",
                description: "Filter wilayah kota supplier (e.g. Grobogan, atau 'Semua Wilayah')",
                required: false,
                schema: new OA\Schema(type: "string", default: "Semua Wilayah")
            ),
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "Jumlah data per halaman",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Halaman ke-n",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar produk berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "12"),
                                new OA\Property(property: "supplier_id", type: "string", example: "2"),
                                new OA\Property(property: "supplier_category_id", type: "string", example: "1", nullable: true),
                                new OA\Property(property: "app_category_id", type: "string", example: "2"),
                                new OA\Property(property: "name", type: "string", example: "Semen Tiga Roda 40kg"),
                                new OA\Property(property: "description", type: "string", example: "Semen berkualitas tinggi untuk konstruksi beton."),
                                new OA\Property(property: "price", type: "string", example: "65000.00"),
                                new OA\Property(property: "unit", type: "string", example: "sak"),
                                new OA\Property(property: "stock", type: "string", example: "150"),
                                new OA\Property(property: "min_order", type: "string", example: "5"),
                                new OA\Property(property: "status", type: "string", example: "aktif"),
                                new OA\Property(property: "photo", type: "string", example: "semen.png"),
                                new OA\Property(property: "rata_rata_rating", type: "string", nullable: true, example: "4.5"),
                                new OA\Property(property: "total_ulasan", type: "string", example: "12"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:00:00"),
                                new OA\Property(property: "updated_at", type: "string", example: "2026-06-08 14:00:00"),
                                new OA\Property(property: "supplier_name", type: "string", example: "TB Makmur"),
                                new OA\Property(property: "region", type: "string", example: "Grobogan"),
                                new OA\Property(property: "sold_count", type: "integer", example: 45),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.png")
                            ]
                        )),
                        new OA\Property(property: "pagination", type: "object", properties: [
                            new OA\Property(property: "current_page", type: "integer", example: 1),
                            new OA\Property(property: "has_more_pages", type: "boolean", example: true),
                            new OA\Property(property: "total_products", type: "integer", example: 25)
                        ])
                    ]
                )
            )
        ]
    )]
    public function index()
    {
    }

    #[OA\Get(
        path: "/api/products/show",
        summary: "Ambil Kategori Produk Unik Yang Tersedia",
        description: "Mengambil daftar seluruh kategori produk yang saat ini memiliki setidaknya satu produk aktif di sistem.",
        tags: ["Products (Produk)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Kategori berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "list kategori supplier"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "name", type: "string", example: "Semen & Mortar"),
                                new OA\Property(property: "id", type: "string", example: "1")
                            ]
                        ))
                    ]
                )
            )
        ]
    )]
    public function show()
    {
    }

    #[OA\Get(
        path: "/api/products/getBySupplier/{supplierId}",
        summary: "Ambil Produk Berdasarkan ID Supplier",
        description: "Mengambil daftar seluruh produk yang dijual oleh satu supplier tertentu berdasarkan ID supplier.",
        tags: ["Products (Produk)"],
        parameters: [
            new OA\Parameter(
                name: "supplierId",
                in: "path",
                description: "ID Supplier",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar produk supplier berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Produk berhasil diambil."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "12"),
                                new OA\Property(property: "name", type: "string", example: "Semen Tiga Roda 40kg"),
                                new OA\Property(property: "price", type: "string", example: "65000.00"),
                                new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.png")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "ID Supplier tidak boleh kosong."
            )
        ]
    )]
    public function getBySupplier()
    {
    }

    #[OA\Get(
        path: "/api/suppliers/regions",
        summary: "Ambil Daftar Wilayah Kota Supplier",
        description: "Mengambil seluruh daftar kota wilayah tempat tinggal/operasi supplier untuk opsi filter wilayah.",
        tags: ["Products (Produk)"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar kota berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "list kota supplier"),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "string"), example: ["Semua Wilayah", "Grobogan", "Semarang"])
                    ]
                )
            )
        ]
    )]
    public function regions()
    {
    }

    #[OA\Get(
        path: "/api/products/ratings/{id}",
        summary: "Ambil Rating / Ulasan Produk",
        description: "Mengambil daftar seluruh ulasan dan rating produk berdasarkan ID Produk.",
        tags: ["Products (Produk)"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Produk",
                required: true,
                schema: new OA\Schema(type: "integer", example: 12)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar ulasan ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Data rating untuk product 12 ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "5"),
                                new OA\Property(property: "id_product", type: "string", example: "12"),
                                new OA\Property(property: "rating", type: "string", example: "5"),
                                new OA\Property(property: "comment", type: "string", example: "Semennya sangat baik dan pengirimannya cepat."),
                                new OA\Property(property: "gambar1", type: "string", nullable: true, example: "http://localhost:8080/uploads/products/rating/review.png"),
                                new OA\Property(property: "created_at", type: "string", example: "2026-06-08 14:15:00")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "ID Produk tidak boleh kosong."
            )
        ]
    )]
    public function showrating()
    {
    }

    #[OA\Get(
        path: "/api/products/ratings/supplier",
        summary: "Ambil Rating Produk Saya (Khusus Supplier)",
        description: "Mengambil daftar seluruh ulasan rating produk milik supplier yang sedang login.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Ulasan produk supplier ditemukan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 200),
                        new OA\Property(property: "message", type: "string", example: "Data rating produk untuk supplier 2 ditemukan."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "string", example: "5"),
                                new OA\Property(property: "id_product", type: "string", example: "12"),
                                new OA\Property(property: "rating", type: "string", example: "5"),
                                new OA\Property(property: "comment", type: "string", example: "Semen berkualitas!"),
                                new OA\Property(property: "product_name", type: "string", example: "Semen Tiga Roda 40kg"),
                                new OA\Property(property: "product_image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.png")
                            ]
                        ))
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized / Bukan bertindak sebagai supplier."
            )
        ]
    )]
    public function showRatingBySupplier()
    {
    }

    #[OA\Post(
        path: "/api/products/ratings/create",
        summary: "Buat Rating / Ulasan Produk Baru",
        description: "Membuat rating dan ulasan ulasan baru untuk produk yang telah dibeli, dengan dukungan unggah hingga 5 gambar bukti.",
        tags: ["Products (Produk)"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["id_product", "rating", "comment"],
                    properties: [
                        new OA\Property(property: "id_product", type: "integer", example: 12, description: "ID Produk"),
                        new OA\Property(property: "rating", type: "integer", enum: [1, 2, 3, 4, 5], example: 5, description: "Rating angka 1-5"),
                        new OA\Property(property: "comment", type: "string", example: "Kualitas barang premium, recommended seller!", description: "Komentar ulasan"),
                        new OA\Property(
                            property: "images[]", 
                            type: "array", 
                            items: new OA\Items(type: "string", format: "binary"),
                            description: "Daftar file gambar bukti review (Maksimal 5 gambar)"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ulasan berhasil dibuat.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "integer", example: 201),
                        new OA\Property(property: "message", type: "string", example: "Rating products berhasil dibuat"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 6)
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validasi gagal atau jumlah gambar melebihi 5."
            ),
            new OA\Response(
                response: 500,
                description: "Internal Server Error."
            )
        ]
    )]
    public function createRating()
    {
    }

    #[OA\Get(
        path: "/api/supplier/my-products",
        summary: "Daftar Produk Saya (Khusus Supplier)",
        description: "Mengambil daftar seluruh produk yang terdaftar milik supplier yang saat ini sedang login.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil daftar produk saya.",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "string", example: "12"),
                            new OA\Property(property: "name", type: "string", example: "Semen Tiga Roda 40kg"),
                            new OA\Property(property: "price", type: "string", example: "65000.00"),
                            new OA\Property(property: "stock", type: "string", example: "150"),
                            new OA\Property(property: "status", type: "string", example: "aktif"),
                            new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.png")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            )
        ]
    )]
    public function myProducts()
    {
    }

    #[OA\Get(
        path: "/api/supplier/product/{id}",
        summary: "Detail Produk Saya (Khusus Supplier)",
        description: "Mengambil informasi detail ulasan/penjualan untuk satu produk tertentu milik supplier yang sedang login.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Produk",
                required: true,
                schema: new OA\Schema(type: "integer", example: 12)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail produk berhasil diambil.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Produk berhasil diambil."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "string", example: "12"),
                            new OA\Property(property: "name", type: "string", example: "Semen Tiga Roda 40kg"),
                            new OA\Property(property: "price", type: "string", example: "65000.00"),
                            new OA\Property(property: "sold_count", type: "integer", example: 45),
                            new OA\Property(property: "image_url", type: "string", example: "http://localhost:8080/uploads/products/semen.png")
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Produk tidak ditemukan."
            )
        ]
    )]
    public function detailProduct()
    {
    }

    #[OA\Post(
        path: "/api/products",
        summary: "Tambah Produk Baru (Khusus Supplier)",
        description: "Menambahkan produk baru yang akan dijual oleh supplier. Akun supplier harus memiliki status 'approved'.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["app_category_id", "name", "price", "stock"],
                    properties: [
                        new OA\Property(property: "supplier_category_id", type: "integer", example: 1, description: "ID Kategori Supplier (Opsional)"),
                        new OA\Property(property: "app_category_id", type: "integer", example: 2, description: "ID Kategori Aplikasi (Wajib)"),
                        new OA\Property(property: "name", type: "string", example: "Besi Beton Polos 8mm", description: "Nama produk"),
                        new OA\Property(property: "description", type: "string", example: "Besi beton polos ukuran standar SNI.", description: "Keterangan produk"),
                        new OA\Property(property: "price", type: "number", format: "float", example: 48000.00, description: "Harga produk per unit"),
                        new OA\Property(property: "unit", type: "string", example: "batang", default: "pcs", description: "Satuan unit barang"),
                        new OA\Property(property: "stock", type: "integer", example: 200, description: "Stok tersedia"),
                        new OA\Property(property: "min_order", type: "integer", example: 10, default: 1, description: "Minimal pemesanan"),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Foto produk")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Produk berhasil ditambahkan.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Produk berhasil ditambahkan.")
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
                response: 403,
                description: "Forbidden. Akun belum disetujui admin."
            )
        ]
    )]
    public function create()
    {
    }

    #[OA\Post(
        path: "/api/products/{id}",
        summary: "Update Produk (Khusus Supplier)",
        description: "Memperbarui informasi produk. Untuk mengirim file foto baru, gunakan HTTP POST dengan field method PATCH/PUT atau override form data.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Produk yang akan diupdate",
                required: true,
                schema: new OA\Schema(type: "integer", example: 12)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "supplier_category_id", type: "integer", example: 1, description: "ID Kategori Supplier"),
                        new OA\Property(property: "app_category_id", type: "integer", example: 2, description: "ID Kategori Aplikasi"),
                        new OA\Property(property: "name", type: "string", example: "Besi Beton Polos 8mm (Update)"),
                        new OA\Property(property: "description", type: "string", example: "Deskripsi terbaru."),
                        new OA\Property(property: "price", type: "number", format: "float", example: 49500.00),
                        new OA\Property(property: "unit", type: "string", example: "batang"),
                        new OA\Property(property: "stock", type: "integer", example: 180),
                        new OA\Property(property: "min_order", type: "integer", example: 5),
                        new OA\Property(property: "photo", type: "string", format: "binary", description: "Foto produk baru (opsional)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Data produk diperbarui.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Data produk diperbarui.")
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
                description: "Produk tidak ditemukan."
            )
        ]
    )]
    public function update()
    {
    }

    #[OA\Delete(
        path: "/api/products/{id}",
        summary: "Hapus Produk (Khusus Supplier)",
        description: "Menghapus produk dari sistem berdasarkan ID produk.",
        tags: ["Products (Produk)"],
        security: [
            ["bearerAuth" => []]
        ],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID Produk yang akan dihapus",
                required: true,
                schema: new OA\Schema(type: "integer", example: 12)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Produk berhasil dihapus.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Produk dihapus.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized."
            ),
            new OA\Response(
                response: 404,
                description: "Produk tidak ditemukan."
            )
        ]
    )]
    public function delete()
    {
    }
}
