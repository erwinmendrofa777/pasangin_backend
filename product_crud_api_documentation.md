# Dokumentasi API: Tambah & Edit Produk (Khusus Supplier)

Dokumentasi ini mencakup endpoint untuk menambahkan produk baru dan memperbarui informasi produk yang sudah ada oleh Supplier yang telah terautentikasi dan disetujui (status `approved`).

> [!IMPORTANT]
> **Kebijakan Kualifikasi Kategori**:
> Supplier **tidak perlu mengisi** field `app_category_id` (Kategori Aplikasi Global). Saat produk baru didaftarkan oleh supplier, status produk akan bernilai `'tidak aktif'`. Admin di Web Panel yang akan meninjau kelayakan produk tersebut, mengklasifikasikannya ke dalam Kategori Aplikasi Global yang sesuai, dan menyetujuinya (mengubah status menjadi `'aktif'`).

---

## 📌 1. Tambah Produk Baru

Endpoint ini digunakan oleh Supplier untuk mengunggah produk baru ke katalog toko mereka.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/products` |
| **Method** | `POST` |
| **Content-Type** | `multipart/form-data` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Headers
```http
Authorization: Bearer <your_jwt_token_here>
```

### Parameter Request (Body)
Kirimkan data dalam format **Form Data** (karena mendukung unggahan file foto produk):

| Field | Tipe | Status | Deskripsi & Aturan |
| :--- | :--- | :--- | :--- |
| `supplier_category_id` | Integer | *Opsional* | ID Kategori Toko khusus supplier (didapat dari `GET /api/supplier/categories`). |
| `category_id` | Integer | *Opsional* | **(Kompatibilitas Mundur)** Jika frontend belum diganti, parameter lama ini tetap diterima dan dipetakan otomatis ke `supplier_category_id`. |
| `name` | String | **Wajib (Required)** | Nama produk.<br>• Minimal: 3 karakter<br>• Maksimal: 255 karakter |
| `price` | Numeric | **Wajib (Required)** | Harga satuan produk (angka desimal/bulat). |
| `stock` | Integer | **Wajib (Required)** | Jumlah stok produk yang tersedia. |
| `description` | String | *Opsional* | Keterangan/deskripsi lengkap detail produk. |
| `unit` | String | *Opsional* | Satuan unit barang (contoh: `sak`, `batang`, `pcs`, `box`).<br>• Default: `pcs`<br>• Maksimal: 50 karakter |
| `min_order` | Integer | *Opsional* | Minimal jumlah pembelian produk.<br>• Default: `1` |
| `photo` | File | *Opsional* | Gambar/foto produk.<br>• Format: `jpg`, `jpeg`, `png`, `webp`<br>• Ukuran maks: `2MB` |

### Contoh Response

#### A. Respons Berhasil (201 Created)
```json
{
  "status": true,
  "message": "Produk berhasil ditambahkan."
}
```

#### B. Respons Gagal Validasi (400 Bad Request)
```json
{
  "status": 400,
  "error": 400,
  "messages": {
    "name": "Nama produk wajib diisi.",
    "price": "Harga produk wajib diisi."
  }
}
```

---

## 📌 2. Edit / Update Produk

Endpoint ini digunakan untuk memperbarui data produk yang sudah terdaftar.

> [!NOTE]
> Meskipun operasinya adalah update (biasanya menggunakan method `PUT`), endpoint ini menggunakan method **`POST`** agar server PHP dapat memproses kiriman file gambar (`photo`) baru via `multipart/form-data` dengan lancar.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/products/{id}` |
| **Method** | `POST` |
| **Content-Type** | `multipart/form-data` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Path Parameter
*   `{id}`: ID produk yang ingin diubah (contoh: `/api/products/12`)

### Headers
```http
Authorization: Bearer <your_jwt_token_here>
```

### Parameter Request (Body)
Semua parameter bersifat opsional (kirimkan hanya field yang ingin diubah saja):

| Field | Tipe | Status | Deskripsi |
| :--- | :--- | :--- | :--- |
| `supplier_category_id` | Integer | *Opsional* | ID Kategori Toko baru jika ingin diganti. |
| `category_id` | Integer | *Opsional* | **(Kompatibilitas Mundur)** Tetap diterima jika dikirimkan oleh frontend versi lama. |
| `name` | String | *Opsional* | Nama produk baru (min 3, maks 255 karakter). |
| `price` | Numeric | *Opsional* | Harga produk baru. |
| `stock` | Integer | *Opsional* | Jumlah stok baru. |
| `description` | String | *Opsional* | Keterangan produk baru. |
| `unit` | String | *Opsional* | Satuan unit baru (maks 50 karakter). |
| `min_order` | Integer | *Opsional* | Minimal pembelian baru. |
| `photo` | File | *Opsional* | File gambar produk baru (akan menghapus gambar lama secara otomatis di server). |

### Contoh Response

#### A. Respons Berhasil (200 OK)
```json
{
  "status": true,
  "message": "Data produk diperbarui."
}
```

#### B. Respons Produk Tidak Ditemukan (404 Not Found)
Terjadi jika ID produk salah atau produk tersebut bukan milik supplier yang sedang login.
```json
{
  "status": 404,
  "error": 404,
  "messages": {
    "error": "Produk tidak ditemukan."
  }
}
```
