# 📖 API Documentation: Supplier Referral & Sales Assistance

## Dokumen ini menyediakan spesifikasi teknis API lengkap untuk mengintegrasikan fitur **Supplier Referral** (mobile app)

## 🔑 Metodologi Autentikasi

1. **API Mobile (Supplier & Sales Mobile)**: Menggunakan **JWT Bearer Token** pada HTTP Header:
   ```http
   Authorization: Bearer <JWT_TOKEN>
   ```

---

## 🗂️ Daftar Endpoint API

### 1. Generate Kode Referal (Oleh Supplier)

Digunakan oleh Supplier untuk mendapatkan kode referal dinamis 10 menit yang akan di-scan oleh Sales.

- **URL**: `/api/supplier/referral/generate`
- **Method**: `POST`
- **Autentikasi**: JWT (Role: `supplier`)
- **Headers**:
  ```http
  Authorization: Bearer <JWT_TOKEN_SUPPLIER>
  Accept: application/json
  ```
- **Request Body**: _Kosong (Empty)_

#### Response Sukses (200 OK)

```json
{
  "status": true,
  "message": "Kode referal berhasil dibuat.",
  "data": {
    "code": "SUP-B8F3C9",
    "expires_at": "2026-07-01 13:50:00"
  }
}
```

#### Response Gagal

- **401 Unauthorized** (Token tidak valid / bukan role supplier):
  ```json
  {
    "status": 401,
    "error": 401,
    "messages": {
      "error": "Akses ditolak. Hanya supplier yang dapat menghasilkan kode referal."
    }
  }
  ```

---

### 2. Klaim Supplier Menggunakan Kode (Oleh Sales - API JSON)

Digunakan oleh Sales (baik via aplikasi Mobile Sales atau AJAX Dashboard) untuk menghubungkan akun supplier ke dirinya.

- **URL**: `/admin/sales/claim-supplier` _(API)_
- **Method**: `POST`
- **Autentikasi**: Session Cookie / JWT (Role: `sales`)
- **Headers**:
  ```http
  Accept: application/json
  Content-Type: application/json
  ```
- **Request Body (JSON)**:
  ```json
  {
    "code": "SUP-B8F3C9"
  }
  ```

#### Request Parameter

| Field  | Tipe   | Wajib | Keterangan                                                   |
| :----- | :----- | :---: | :----------------------------------------------------------- |
| `code` | String |  Ya   | Kode referal dinamis dari HP Supplier (Contoh: `SUP-B8F3C9`) |

#### Response Sukses (200 OK)

```json
{
  "status": true,
  "message": "Supplier berhasil dihubungkan.",
  "data": {
    "supplier_id": 12,
    "supplier_name": "Toko Besi Sejahtera"
  }
}
```

#### Response Gagal

- **400 Bad Request** (Kode kedaluwarsa atau sudah terpakai):
  ```json
  {
    "status": 400,
    "error": 400,
    "messages": {
      "error": "Kode referal telah kedaluwarsa."
    }
  }
  ```
- **404 Not Found** (Kode tidak valid/tidak terdaftar):
  ```json
  {
    "status": 404,
    "error": 404,
    "messages": {
      "error": "Kode referal tidak ditemukan."
    }
  }
  ```

---

### 3. Tambah Produk Baru (Oleh Supplier atau Sales Terhubung)

Digunakan untuk menginput produk. Sales yang sudah terhubung bisa memanggil endpoint ini dengan menyertakan `supplier_id`.

- **URL**: `/api/products`
- **Method**: `POST`
- **Autentikasi**: JWT / Session Cookie (Role: `supplier` atau `sales`)
- **Content-Type**: `multipart/form-data`
- **Request Body (Form-Data)**:

| Parameter              | Tipe        |  Wajib   | Keterangan                                                      |
| :--------------------- | :---------- | :------: | :-------------------------------------------------------------- |
| `supplier_id`          | Integer     | Opsional | **Wajib jika diinput oleh Sales**. Diisi ID Supplier terkelola. |
| `name`                 | String      |    Ya    | Nama produk (Min 3, Max 255 karakter).                          |
| `price`                | Numeric     |    Ya    | Harga jual produk (angka positif).                              |
| `stock`                | Integer     |    Ya    | Jumlah stok produk.                                             |
| `supplier_category_id` | Integer     | Opsional | ID Kategori toko supplier.                                      |
| `unit`                 | String      | Opsional | Satuan jual (default: `'pcs'`).                                 |
| `min_order`            | Integer     | Opsional | Batas minimal order pembelian (default: `1`).                   |
| `quantity`             | Numeric     | Opsional | Volume produk dalam satuan $m^3$ (default: `0`).                |
| `description`          | String      | Opsional | Informasi deskripsi produk.                                     |
| `photo`                | File (Blob) | Opsional | Gambar produk (JPG, JPEG, PNG, WEBP. Maks 2MB).                 |

#### Response Sukses (201 Created)

```json
{
  "status": true,
  "message": "Produk berhasil ditambahkan."
}
```

#### Response Gagal

- **403 Forbidden** (Sales mencoba menambah produk ke Supplier yang tidak ia kelola):
  ```json
  {
    "status": 403,
    "error": 403,
    "messages": {
      "error": "Akses ditolak."
    }
  }
  ```
- **422 Unprocessable Entity** (Validasi input gagal):
  ```json
  {
    "status": 422,
    "error": 422,
    "messages": {
      "name": "Nama produk wajib diisi.",
      "price": "Harga produk harus berupa angka."
    }
  }
  ```

---

### 4. Edit / Update Produk (Oleh Supplier atau Sales Terhubung)

Digunakan untuk mengupdate data produk terdaftar.

- **URL**: `/api/products/{id}` _(Contoh: `/api/products/42`)_
- **Method**: `POST` _(Direkomendasikan POST multipart form-data untuk pembaruan foto)_
- **Autentikasi**: JWT / Session Cookie (Role: `supplier` atau `sales`)
- **Content-Type**: `multipart/form-data`
- **Request Body (Form-Data)**:

| Parameter              | Tipe        |  Wajib   | Keterangan                                                       |
| :--------------------- | :---------- | :------: | :--------------------------------------------------------------- |
| `supplier_id`          | Integer     | Opsional | **Wajib jika diupdate oleh Sales**. Diisi ID Supplier terkelola. |
| `name`                 | String      | Opsional | Nama produk baru.                                                |
| `price`                | Numeric     | Opsional | Harga jual baru.                                                 |
| `stock`                | Integer     | Opsional | Jumlah stok baru.                                                |
| `supplier_category_id` | Integer     | Opsional | ID Kategori baru.                                                |
| `unit`                 | String      | Opsional | Satuan baru.                                                     |
| `min_order`            | Integer     | Opsional | Minimal order baru.                                              |
| `quantity`             | Numeric     | Opsional | Volume baru.                                                     |
| `description`          | String      | Opsional | Deskripsi baru.                                                  |
| `photo`                | File (Blob) | Opsional | File gambar produk baru jika ingin diganti.                      |

#### Response Sukses (200 OK)

```json
{
  "status": true,
  "message": "Data produk diperbarui."
}
```
