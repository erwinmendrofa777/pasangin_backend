Warna 0xFFFF5C5C (dalam format web/CSS ditulis sebagai #FF5C5C) adalah warna merah coral yang cerah, modern, dan sangat energik.

Karena warna ini cukup mencolok, pendekatan terbaik untuk mendesain antarmuka dashboard adalah menjadikannya sebagai warna aksen utama (untuk tombol, indikator aktif, atau grafik penting) dan memadukannya dengan warna-warna netral agar mata pengguna tidak cepat lelah saat melihat data.

Berikut adalah 2 rekomendasi kombinasi skema warna yang bisa Anda terapkan:

1. Skema "Clean & Minimalist" (Paling disarankan untuk Dashboard)
   Skema ini menggunakan latar belakang terang agar warna #FF5C5C terlihat menonjol dan data mudah dibaca.

Primary: #FF5C5C (Gunakan untuk tombol utama, toggle aktif, atau ikon penting)

Background: #F8F9FA atau #F3F4F6 (Abu-abu sangat muda untuk latar belakang utama aplikasi)

Surface / Card: #FFFFFF (Putih bersih untuk latar belakang tabel, form, dan panel grafik)

Text Primary: #1F2937 (Abu-abu gelap kebiruan untuk teks utama, lebih lembut dari hitam pekat)

Text Secondary: #6B7280 (Abu-abu medium untuk label, teks bantuan, atau placeholder)

2. Skema "Semantic / Status"
   Dalam sebuah sistem manajemen, Anda pasti membutuhkan warna untuk status (sukses, peringatan, dll). Karena warna utama Anda sudah bernuansa merah (yang biasanya identik dengan error atau bahaya), Anda perlu pendamping yang pas agar pengguna tidak bingung:

Primary / Brand: #FF5C5C (Merah Coral)

Success (Sukses/Selesai): #10B981 (Hijau Zamrud - terlihat segar dan kontras dengan merah)

Warning (Peringatan/Proses): #F59E0B (Kuning Amber)

Info (Informasi Umum): #3B82F6 (Biru Terang)

Danger / Error (Kritis): #DC2626 (Merah yang lebih gelap dan pekat dari warna utama Anda, agar bisa dibedakan antara tombol aksi utama dan peringatan error).

Tips Desain Tambahan:
Untuk bayangan (shadow) pada elemen berwarna #FF5C5C, Anda bisa menggunakan kode shadow transparan seperti rgba(255, 92, 92, 0.3) agar tampilannya menyatu dan modern, alih-alih menggunakan bayangan hitam biasa.

---

## Panduan Arsitektur Views (Dashboard Admin & Supplier)

Mengingat folder `app/Views/admin/` yang lama telah dihapus untuk memulai lembaran yang lebih bersih, berikut adalah **Blueprint Arsitektur Modular** yang direkomendasikan untuk membangun kembali tampilan (views) aplikasi. 

Arsitektur ini didesain agar sangat *scalable*, rapi, dan mudah dikelola, terutama karena aplikasi ini akan memiliki puluhan modul di *sidebar* (seperti Pesan, Users, Suppliers, Produk, Orders, Wallet, Tukang, Konstruksi, Renovasi, dll).

### 1. Struktur Folder Berbasis Fitur (Feature-Based Modular)

Jangan menggabungkan semua file ke dalam satu folder utama. Pisahkan setiap menu/fitur ke dalam sub-foldernya masing-masing.

```text
app/Views/
├── layout/                 # Master Templates (Sudah ada)
│   ├── app.php             # Master layout dengan sidebar
│   ├── main.php            # Layout polos tanpa sidebar
│   └── components/         # Komponen UI yang dipisah (Rekomendasi)
│       ├── _sidebar.php    # Potong kode sidebar dari app.php ke sini
│       └── _navbar.php     # Potong kode navbar dari app.php ke sini
│
├── auth/                   # Khusus Tampilan Autentikasi (Publik)
│   ├── admin_login.php     # Login untuk Admin
│   ├── supplier_login.php  # Login untuk Supplier
│   └── supplier_register.php 
│
├── admin/                  # Modul Utama Aplikasi (Butuh Login)
│   │
│   ├── dashboard/          # Modul Dashboard
│   │   ├── index.php       # Dashboard Admin Utama
│   │   └── supplier.php    # Dashboard khusus Supplier
│   │
│   ├── produk/             # Modul Manajemen Produk
│   │   ├── index.php       # Menampilkan list/tabel produk
│   │   ├── create.php      # Form tambah produk
│   │   ├── edit.php        # Form edit produk
│   │   └── show.php        # Detail produk (opsional)
│   │
│   ├── supplier/           # Modul Manajemen Data Supplier
│   │   ├── index.php       # List data supplier
│   │   ├── create.php      # Form tambah supplier manual dari admin
│   │   └── edit.php        # Form edit supplier
│   │
│   ├── orders/             # Modul Manajemen Pesanan
│   │   ├── index.php       # List pesanan masuk
│   │   └── detail.php      # Detail pesanan
│   │
│   └── [modul_lainnya]/    # Buat folder baru untuk setiap menu di sidebar (chat, users, wallet, dll)
```

### 2. Standar Penamaan File (Naming Convention)

Untuk menjaga konsistensi tim, gunakan pola penamaan standar **CRUD / RESTful** di dalam setiap folder modul:
- `index.php` : Untuk halaman utama modul (biasanya berisi tabel data).
- `create.php` : Untuk halaman formulir penambahan data.
- `edit.php` : Untuk halaman formulir pengubahan data.
- `show.php` atau `detail.php` : Untuk halaman detail suatu data.

*(Hindari penamaan file deskriptif yang berulang seperti `produk_form.php` atau `produk_list.php`. Cukup gunakan `produk/create.php` dan `produk/index.php`).*

### 3. Keuntungan Pendekatan Ini:
1. **Separation of Concerns (SoC):** Tampilan publik (*auth*) benar-benar terpisah dari panel admin.
2. **Kemudahan Navigasi (*Code Maintainability*):** Jika terjadi *error* pada saat menambahkan produk, Anda langsung tahu *file* yang harus diperbaiki adalah `app/Views/admin/produk/create.php`.
3. **Mencegah Folder Bloating:** Dengan puluhan fitur, folder `admin/` tidak akan berisi puluhan file *PHP* yang berserakan, melainkan folder-folder rapi yang merepresentasikan setiap fungsionalitas.
4. **Layout Lebih Rapi (*Clean Templates*):** Dengan memisahkan `_sidebar.php` dan `_navbar.php` ke folder `components/`, file layout utama `app.php` akan terbebas dari ratusan baris kode HTML statis, sehingga jauh lebih mudah dibaca.
