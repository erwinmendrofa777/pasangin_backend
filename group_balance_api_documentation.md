# Dokumentasi API: Saldo Pekerjaan Kelompok & Persetujuan Mayoritas (Multi-Sig)

Dokumen ini menjelaskan spesifikasi API Endpoints yang disediakan untuk fitur Saldo Grup (Group Balance) berdasarkan pekerjaan/progress, usulan pembagian tertunda, sistem persetujuan mayoritas (voting), dan riwayat transaksi.

---

## 1. Detail Kelompok (Mandor Only)
* **Endpoint:** `GET /api/tukang/group/detail`
* **Headers:** `Authorization: Bearer {JWT_TOKEN}`
* **Response (200 OK):**
  ```json
  {
      "status": true,
      "message": "Detail grup berhasil diambil.",
      "data": {
          "id": 1,
          "name_group": "Grup Berkah Abadi",
          "tukang_id": 1,
          "referral_code": "BERKAH9999",
          "created_at": "2026-06-23 10:20:00",
          "group_balance": 5000000.0,
          "members": [
              {
                  "member_record_id": 1,
                  "tukang_id": 3,
                  "status": "approved",
                  "joined_at": "2026-06-23 10:20:00",
                  "name": "Yana Tukang 1",
                  "email": "yana1@tukang.com",
                  "phone": "082218695302"
              }
          ]
      }
  }
  ```

---

## 2. Status Keanggotaan Anggota (Tukang Only)
* **Endpoint:** `GET /api/tukang/group/my-status`
* **Headers:** `Authorization: Bearer {JWT_TOKEN}`
* **Response (200 OK):**
  ```json
  {
      "status": true,
      "message": "Status keanggotaan grup berhasil diambil.",
      "data": {
          "member_record_id": 1,
          "status": "approved",
          "joined_at": "2026-06-23 10:20:00",
          "group_id": 1,
          "name_group": "Grup Berkah Abadi",
          "referral_code": "BERKAH9999",
          "group_balance": 5000000.0,
          "mandor_name": "Budi Mandor",
          "mandor_phone": "081234567890"
      }
  }
  ```

---

## 3. Daftar Saldo Pekerjaan & Progress (Mandor Only)
Mengambil daftar target pekerjaan beserta nominal upah total, upah masuk (inflow), upah dibagikan (outflow), saldo belum dibagikan, dan sisa upah target yang belum diterima dari client.

* **Endpoint:** `GET /api/tukang/group/job-balances`
* **Headers:** `Authorization: Bearer {JWT_TOKEN}`
* **Response (200 OK):**
  ```json
  {
      "status": true,
      "message": "Daftar saldo pekerjaan kelompok berhasil diambil.",
      "data": [
          {
              "target_id": 5,
              "construction_id": 12,
              "job_name": "Pasangan Bata Merah",
              "volume": 100.0,
              "unit": "m2",
              "total_target_wages": 2500000.0,
              "wages_received": 1250000.0,
              "wages_distributed": 500000.0,
              "wages_pending": 400000.0,
              "undistributed_balance": 350000.0,
              "unreceived_balance": 1250000.0,
              "progress_reports": [
                  {
                      "progress_id": 12,
                      "volume": 20.0,
                      "description": "Progress galian dan pasang bata minggu ke-3",
                      "created_at": "2026-06-25 10:24:00",
                      "wages_received": 500000.0,
                      "wages_distributed": 500000.0,
                      "wages_pending": 0.0,
                      "undistributed_balance": 0.0
                  },
                  {
                      "progress_id": 15,
                      "volume": 30.0,
                      "description": "Progress pasang bata minggu ke-4",
                      "created_at": "2026-06-26 09:12:00",
                      "wages_received": 750000.0,
                      "wages_distributed": 0.0,
                      "wages_pending": 400000.0,
                      "undistributed_balance": 350000.0
                  }
              ]
          }
      ]
  }
  ```

---

## 4. Ajukan Distribusi Saldo Kelompok (Mandor Only)
Digunakan oleh Mandor untuk mengajukan usulan pembagian saldo kelompok spesifik per progress pengerjaan. Jika kelompok memiliki anggota aktif lain, status transaksi di-set `pending` dan butuh persetujuan mayoritas anggota. Jika mandor bekerja sendiri (anggota = 0), langsung otomatis disetujui (`approved`).

* **Endpoint:** `POST /api/tukang/group/distribute-bulk`
* **Headers:**
  * `Authorization: Bearer {JWT_TOKEN}`
  * `Content-Type: application/json`
* **Request Body (JSON):**
  ```json
  {
      "source_project_type": "construction",
      "source_invoice_id": 15,
      "distributions": [
          {
              "tukang_id": 3,
              "amount": 250000
          },
          {
              "tukang_id": 1,
              "amount": 150000
          }
      ]
  }
  ```
* **Response (200 OK - Ada Anggota Kelompok):**
  ```json
  {
      "status": true,
      "message": "Usulan pembagian saldo berhasil dikirim. Menunggu persetujuan dari mayoritas anggota kelompok."
  }
  ```
* **Response (200 OK - Hanya Mandor Sendiri / Anggota = 0):**
  ```json
  {
      "status": true,
      "message": "Berhasil mendistribusikan saldo grup (langsung cair karena tidak ada anggota lain)."
  }
  ```

---

## 5. Daftar Usulan Pembagian Tertunda (Mandor & Tukang)
Melihat daftar usulan pembagian kas kelompok yang sedang menunggu persetujuan (voting) mayoritas anggota.

* **Endpoint:** `GET /api/tukang/group/pending-distributions`
* **Headers:** `Authorization: Bearer {JWT_TOKEN}`
* **Response (200 OK):**
  ```json
  {
      "status": true,
      "message": "Daftar usulan pembagian saldo tertunda berhasil diambil.",
      "data": [
          {
              "id": 12,
              "amount": 400000.0,
              "source_project_type": "construction",
              "source_invoice_id": 15,
              "description": "Usulan distribusi saldo untuk progress #15 ke 2 penerima.",
              "created_at": "2026-06-26 09:20:00",
              "distributions": [
                  {
                      "tukang_id": 3,
                      "amount": 250000.0,
                      "member_name": "Yana Tukang 1"
                  },
                  {
                      "tukang_id": 1,
                      "amount": 150000.0,
                      "member_name": "Budi Mandor"
                  }
              ],
              "voting": {
                  "total_voters": 2,
                  "majority_required": 2,
                  "approved_votes": 1,
                  "rejected_votes": 0,
                  "has_voted": true,
                  "user_vote": "approved"
              }
          }
      ]
  }
  ```

---

## 6. Berikan Suara Persetujuan (Tukang Members Only)
Merekam suara setuju (`approved`) atau tolak (`rejected`) dari anggota kelompok aktif atas usulan pembagian kas tertentu. 

* **Endpoint:** `POST /api/tukang/group/vote-distribution`
* **Headers:**
  * `Authorization: Bearer {JWT_TOKEN}`
  * `Content-Type: application/json`
* **Request Body (JSON):**
  ```json
  {
      "group_transaction_id": 12,
      "vote": "approved"
  }
  ```
* **Response (200 OK - Menunggu Suara Lain):**
  ```json
  {
      "status": true,
      "message": "Suara Anda berhasil direkam. Menunggu suara dari anggota lain."
  }
  ```
* **Response (200 OK - Disetujui Mayoritas & Cair):**
  ```json
  {
      "status": true,
      "message": "Suara berhasil disimpan. Transaksi disetujui mayoritas dan dana telah dicairkan ke wallet anggota."
  }
  ```
* **Response (200 OK - Ditolak Mayoritas):**
  ```json
  {
      "status": true,
      "message": "Suara berhasil disimpan. Transaksi resmi ditolak oleh kelompok."
  }
  ```

---

## 7. Riwayat Transaksi Kelompok
Mengembalikan daftar mutasi kas kelompok dengan kolom status tambahan (`pending`, `approved`, `rejected`).

* **Endpoint:** `GET /api/tukang/group/transactions`
* **Headers:** `Authorization: Bearer {JWT_TOKEN}`
* **Response (200 OK):**
  ```json
  {
      "status": true,
      "message": "Riwayat transaksi kelompok berhasil diambil.",
      "data": {
          "transactions": [
              {
                  "id": 12,
                  "group_id": 1,
                  "amount": 400000.0,
                  "type": "outflow",
                  "status": "approved",
                  "source_project_type": "construction",
                  "source_invoice_id": 15,
                  "description": "Usulan distribusi saldo untuk progress #15 ke 2 penerima.",
                  "created_at": "2026-06-26 09:20:00",
                  "distributions": [
                      {
                          "tukang_id": 3,
                          "amount": 250000.0,
                          "member_name": "Yana Tukang 1"
                      },
                      {
                          "tukang_id": 1,
                          "amount": 150000.0,
                          "member_name": "Budi Mandor"
                      }
                  ]
              }
          ],
          "pagination": {
              "current_page": 1,
              "limit": 20,
              "total_records": 1,
              "total_pages": 1
          }
      }
  }
  ```
