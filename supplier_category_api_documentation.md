# Dokumentasi API: Kategori Toko Supplier (Supplier Categories)

Dokumentasi ini mencakup seluruh endpoint untuk manajemen data Kategori Toko oleh Supplier yang telah terautentikasi.

---

## 📌 1. Ambil Daftar Kategori Toko Saya

Mengambil daftar semua kategori produk lokal yang dibuat oleh supplier yang saat ini login.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/supplier/categories` |
| **Method** | `GET` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Headers
```http
Authorization: Bearer <your_jwt_token_here>
```

### Contoh Response (200 OK)
```json
{
  "status": true,
  "message": "list kategori supplier",
  "data": [
    {
      "id": "1",
      "supplier_id": "10",
      "name": "Bahan Semen",
      "created_at": "2026-06-30 09:26:49",
      "updated_at": "2026-06-30 09:26:49"
    },
    {
      "id": "2",
      "supplier_id": "10",
      "name": "Peralatan Manual",
      "created_at": "2026-06-30 09:28:10",
      "updated_at": "2026-06-30 09:28:10"
    }
  ]
}
```

---

## 📌 2. Tambah Kategori Baru

Membuat kategori lokal baru yang dapat digunakan oleh supplier untuk mengelompokkan barang mereka sendiri.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/supplier/categories` |
| **Method** | `POST` |
| **Content-Type** | `application/json` atau `application/x-www-form-urlencoded` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Headers
```http
Authorization: Bearer <your_jwt_token_here>
```

### Request Body (JSON)
```json
{
  "name": "Cat Tembok Standar"
}
```

### Parameter Request
| Field | Tipe | Status | Deskripsi & Aturan |
| :--- | :--- | :--- | :--- |
| `name` | String | **Wajib (Required)** | Nama kategori.<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |

### Contoh Response
#### A. Respons Berhasil (201 Created)
```json
{
  "status": true,
  "message": "Kategori berhasil dibuat."
}
```
#### B. Respons Gagal Validasi (400 Bad Request)
```json
{
  "status": 400,
  "error": 400,
  "messages": {
    "name": "Nama kategori minimal 3 karakter."
  }
}
```

---

## 📌 3. Update Kategori Toko

Mengubah nama kategori toko milik supplier yang sedang login.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/supplier/categories/{id}` |
| **Method** | `PUT` |
| **Content-Type** | `application/json` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Path Parameter
*   `{id}`: ID Kategori Toko yang ingin diubah (contoh: `/api/supplier/categories/1`)

### Request Body (JSON)
```json
{
  "name": "Cat Tembok Premium"
}
```

### Contoh Response
#### A. Respons Berhasil (200 OK)
```json
{
  "status": true,
  "message": "Kategori berhasil diupdate."
}
```
#### B. Respons Kategori Tidak Ditemukan (404 Not Found)
Terjadi jika ID kategori salah atau kategori tersebut bukan milik supplier yang sedang login.
```json
{
  "status": 404,
  "error": 404,
  "messages": {
    "error": "supplier tidak memiliki kategori"
  }
}
```

---

## 📌 4. Hapus Kategori Toko

Menghapus kategori toko lokal milik supplier yang sedang login.

### Detail Endpoint
| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/supplier/categories/{id}` |
| **Method** | `DELETE` |
| **Autentikasi** | Wajib (Bearer Token JWT) |

### Path Parameter
*   `{id}`: ID Kategori Toko yang ingin dihapus (contoh: `/api/supplier/categories/1`)

### Contoh Response (200 OK)
```json
{
  "status": true,
  "message": "Kategori berhasil dihapus."
}
```
