# 📑 Panduan & Dokumentasi Integrasi API (Frontend)

_Terakhir Diperbarui: 30 Juni 2026_

Dokumentasi ini merangkum seluruh perubahan rute, parameter, aturan validasi, dan penanganan status produk setelah pemisahan hak akses antara **Supplier** (mengelola status penjualan/stok) dan **Admin** (mengelola persetujuan kelayakan produk).

---

## 📌 Rangkuman Perubahan Utama

1. **Kategori Toko Supplier**: Seluruh endpoint kategori toko supplier dipindahkan dari `/api/categories` ke **`/api/supplier/categories`**. Field parameter `category_id` diubah namanya menjadi **`supplier_category_id`**.
2. **Kategori Aplikasi (Global)**: Supplier **tidak perlu** mengirimkan parameter `app_category_id` saat menambah/mengedit produk. Bidang ini sepenuhnya ditentukan oleh Admin melalui Dashboard Panel saat menyetujui produk.
3. **Aturan Status Awal**: Setiap produk baru yang ditambahkan oleh supplier otomatis berstatus **`tidak aktif`** (hidden) dengan **`approval_status` = `pending`**.
4. **Aturan Aktivasi Produk**: Supplier **tidak dapat mengaktifkan** produk (mengubah status menjadi `'aktif'`) jika produk tersebut belum disetujui (`approval_status` masih `'pending'` atau `'rejected'`).

---

## 📂 1. Endpoint Kategori Toko Supplier (Supplier Category)

Gunakan endpoint ini untuk mengelola kategori toko internal milik supplier.

### A. List Kategori Toko

- **URL**: `/api/supplier/categories`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer <token>`
- **Response (200 OK)**:
  ```json
  {
    "status": true,
    "message": "Daftar kategori supplier ditemukan.",
    "data": [
      {
        "id": 1,
        "supplier_id": 5,
        "name": "Bahan Semen & Mortar",
        "created_at": "2026-06-30 10:15:00",
        "updated_at": "2026-06-30 10:15:00"
      }
    ]
  }
  ```

---

## 📂 2. Endpoint CRUD Produk (Khusus Supplier)

### A. Tambah Produk Baru

Mengirim data produk baru. Produk yang baru dibuat akan otomatis berstatus `'tidak aktif'` (menunggu verifikasi admin).

- **URL**: `/api/products`
- **Method**: `POST`
- **Content-Type**: `multipart/form-data`
- **Headers**: `Authorization: Bearer <token>`
- **Body (Form-Data)**:

| Parameter              | Tipe    | Status     | Deskripsi                                                                                                                              |
| :--------------------- | :------ | :--------- | :------------------------------------------------------------------------------------------------------------------------------------- |
| `name`                 | String  | **Wajib**  | Nama produk (Min 3, maks 255 karakter).                                                                                                |
| `price`                | Numeric | **Wajib**  | Harga satuan produk.                                                                                                                   |
| `stock`                | Integer | **Wajib**  | Jumlah stok produk.                                                                                                                    |
| `supplier_category_id` | Integer | _Opsional_ | ID Kategori Toko Supplier.                                                                                                             |
| `category_id`          | Integer | _Opsional_ | **(Kompatibilitas Mundur)** Jika frontend belum diganti, parameter lama tetap diterima & dipetakan otomatis ke `supplier_category_id`. |
| `description`          | String  | _Opsional_ | Deskripsi/detail produk.                                                                                                               |
| `unit`                 | String  | _Opsional_ | Satuan barang (default: `pcs`).                                                                                                        |
| `min_order`            | Integer | _Opsional_ | Minimal pembelian (default: `1`).                                                                                                      |
| `photo`                | File    | _Opsional_ | Gambar produk (jpg/jpeg/png/webp, maks 2MB).                                                                                           |

- **Response Sukses (201 Created)**:
  ```json
  {
    "status": true,
    "message": "Produk berhasil ditambahkan."
  }
  ```

---

### B. Edit / Update Produk

Mengubah data produk yang sudah ada.

> [!WARNING]
> **Aturan Penting**: Jika frontend mengirimkan parameter `'status': 'aktif'` pada produk yang **belum disetujui** oleh admin (`approval_status` bernilai `'pending'` atau `'rejected'`), server akan menolak request dengan kode **400 Bad Request**.

- **URL**: `/api/products/{id}`
- **Method**: `POST` (Gunakan POST multipart untuk kelancaran unggah berkas foto baru)
- **Content-Type**: `multipart/form-data`
- **Headers**: `Authorization: Bearer <token>`
- **Body (Form-Data)**:
  - _Semua parameter di atas bersifat opsional (kirimkan hanya field yang ingin diubah)._
  - `status`: String (`'aktif'`, `'tidak aktif'`, `'habis'`).

- **Response Sukses (200 OK)**:

  ```json
  {
    "status": true,
    "message": "Data produk diperbarui."
  }
  ```

- **Response Gagal Validasi - Produk Belum Disetujui Admin (400 Bad Request)**:
  ```json
  {
    "status": 400,
    "error": 400,
    "messages": {
      "status": "Produk tidak dapat diaktifkan sebelum disetujui (approved) oleh Admin."
    }
  }
  ```

---

### C. List Produk Milik Supplier (My Products)

Digunakan oleh aplikasi Supplier untuk melihat daftar katalog barang mereka sendiri.

- **URL**: `/api/products/my-products`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer <token>`
- **Response (200 OK)**:
  Setiap item produk mengembalikan status ganda: `status` (kontrol supplier) dan `approval_status` (kontrol admin).
  ```json
  [
    {
      "id": 12,
      "supplier_id": 5,
      "supplier_category_id": 1,
      "app_category_id": 3,
      "name": "Besi Beton Polos 8mm",
      "price": "48000.00",
      "stock": 150,
      "status": "aktif",
      "approval_status": "approved",
      "photo": "1782786611_abc123.png",
      "image_url": "http://localhost:8080/uploads/products/1782786611_abc123.png"
    },
    {
      "id": 13,
      "supplier_id": 5,
      "supplier_category_id": null,
      "app_category_id": null,
      "name": "Semen Gresik 50kg",
      "price": "65000.00",
      "stock": 100,
      "status": "tidak aktif",
      "approval_status": "pending",
      "photo": "1782786650_xyz789.png",
      "image_url": "http://localhost:8080/uploads/products/1782786650_xyz789.png"
    }
  ]
  ```

---

## 📂 3. Endpoint Produk Publik (Aplikasi Client/Pembeli)

### A. List & Cari Produk (Public)

Endpoint ini digunakan untuk memuat produk yang akan dibeli oleh client/pembeli.

- **URL**: `/api/products`
- **Method**: `GET`
- **Query Parameters (Opsional)**:
  - `search`: Kata kunci pencarian nama produk.
  - `region`: Wilayah/kota supplier (misalnya `Semua Wilayah`, `Semarang`, dll).
  - `page`: Nomor halaman (default: `1`).
  - `limit`: Jumlah data per halaman (default: `10`).

> [!NOTE]
> Server secara otomatis hanya akan mengembalikan produk yang memiliki **`status` = `'aktif'`** dan **`approval_status` = `'approved'`**. Produk yang masih _pending_, _rejected_, atau dinonaktifkan oleh supplier tidak akan pernah muncul di sini.

- **Response (200 OK)**:
  ```json
  {
    "status": true,
    "data": [
      {
        "id": 12,
        "name": "Besi Beton Polos 8mm",
        "price": "48000.00",
        "stock": 150,
        "status": "aktif",
        "approval_status": "approved",
        "image_url": "http://localhost:8080/uploads/products/1782786611_abc123.png",
        "supplier_name": "Depo Jaya Makmur",
        "region": "Semarang",
        "sold_count": 25
      }
    ],
    "pagination": {
      "current_page": 1,
      "has_more_pages": false,
      "total_products": 1
    }
  }
  ```
