# Dokumentasi API: Pendaftaran (Register) Supplier Baru

Endpoint ini digunakan untuk mendaftarkan akun Supplier/Toko baru ke dalam sistem. Akun yang baru didaftarkan akan memiliki status `pending` dan memerlukan persetujuan dari Administrator sebelum dapat masuk.

---

## 📌 Informasi Endpoint

| Detail | Deskripsi |
| :--- | :--- |
| **URL** | `/api/supplier/register` |
| **Method** | `POST` |
| **Content-Type** | `multipart/form-data` (Direkomendasikan apabila mengunggah file logo)<br>`application/json` (Apabila `logo_url` dikirim sebagai teks biasa) |
| **Authentication** | None (Public Endpoint) |

---

## 📥 Parameter Request (Body)

Berikut adalah daftar field yang dikirimkan dalam request body:

| Field | Tipe Data | Status | Deskripsi & Aturan Validasi |
| :--- | :--- | :--- | :--- |
| `name` | String | **Wajib (Required)** | Nama Toko / Supplier.<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |
| `email` | String | **Wajib (Required)** | Alamat email unik untuk login.<br>• Harus berupa format email valid<br>• Harus unik (belum pernah digunakan sebelumnya) |
| `phone` | String | **Wajib (Required)** | Nomor telepon unik toko.<br>• Harus berupa angka<br>• Minimal: 10 digit<br>• Maksimal: 15 digit<br>• Harus unik (belum pernah digunakan sebelumnya) |
| `password` | String | **Wajib (Required)** | Kata sandi akun.<br>• Minimal: 8 karakter<br>• Maksimal: 255 karakter |
| `contact_person` | String | **Wajib (Required)** | Nama orang/pihak penghubung (contact person).<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |
| `address` | String | **Wajib (Required)** | Alamat fisik lengkap toko.<br>• Minimal: 3 karakter<br>• Maksimal: 255 karakter |
| `province` | String | **Wajib (Required)** | Nama provinsi lokasi toko.<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |
| `city` | String | **Wajib (Required)** | Nama kota atau kabupaten lokasi toko.<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |
| `district` | String | **Wajib (Required)** | Nama kecamatan lokasi toko.<br>• Minimal: 3 karakter<br>• Maksimal: 100 karakter |
| `latitude` | Numeric | *Opsional* | Titik koordinat garis lintang (latitude) untuk lokasi maps.<br>• Harus bernilai numerik / angka desimal |
| `longitude` | Numeric | *Opsional* | Titik koordinat garis bujur (longitude) untuk lokasi maps.<br>• Harus bernilai numerik / angka desimal |
| `logo_url` | File / String | *Opsional* | File gambar logo toko atau nama file/URL logo.<br>• Jika dikirim sebagai **file/gambar**: format valid adalah `jpg`, `jpeg`, `png`, `webp` dengan ukuran maks `2MB`.<br>• Jika dikirim sebagai **string biasa**: Minimal 3 karakter, maksimal 255 karakter. |

---

## 📤 Contoh Request

### A. Menggunakan `application/json` (tanpa file upload langsung)

```json
{
  "name": "Toko Besi Jaya",
  "email": "besijaya@gmail.com",
  "phone": "081234567890",
  "password": "securepassword123",
  "contact_person": "Budi Santoso",
  "address": "Jl. Raya Cikarang No. 12",
  "province": "Jawa Barat",
  "city": "Bekasi",
  "district": "Cikarang Barat",
  "latitude": -6.2625,
  "longitude": 107.1428,
  "logo_url": "toko_besi_logo.png"
}
```

### B. Menggunakan `multipart/form-data` (dengan file upload langsung)

```http
POST /api/supplier/register HTTP/1.1
Host: localhost:8080
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="name"

Toko Besi Jaya
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="email"

besijaya@gmail.com
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="phone"

081234567890
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="password"

securepassword123
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="contact_person"

Budi Santoso
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="address"

Jl. Raya Cikarang No. 12
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="province"

Jawa Barat
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="city"

Bekasi
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="district"

Cikarang Barat
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="latitude"

-6.2625
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="longitude"

107.1428
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="logo_url"; filename="logo_toko.png"
Content-Type: image/png

(data biner gambar...)
------WebKitFormBoundary7MA4YWxkTrZu0gW--
```

---

## 📥 Respons Response

### 1. Respons Berhasil (201 Created)

```json
{
  "status": "success",
  "message": "Pendaftaran Supplier berhasil. Silakan login."
}
```

### 2. Validasi Gagal (400 Bad Request)

Contoh respons jika field wajib tidak diisi atau tipe data tidak sesuai aturan.

```json
{
  "status": "error",
  "message": {
    "email": "Email sudah terdaftar.",
    "phone": "Nomor telepon harus berupa angka.",
    "latitude": "Latitude harus berupa koordinat valid.",
    "logo_url": "Format file logo harus berupa JPG, JPEG, PNG, atau WEBP."
  }
}
```

### 3. Error Server Internal (500 Server Error)

```json
{
  "status": 500,
  "error": 500,
  "messages": {
    "error": "Gagal registrasi: [Detail pesan kesalahan server]"
  }
}
```
