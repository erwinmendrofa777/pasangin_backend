# Multiple Orders in Single Payment - Implementation Guide

## Overview

Sistem ini memungkinkan user untuk melakukan checkout beberapa produk dari cart dalam satu pembayaran tunggal melalui Midtrans. Fitur ini dirancang untuk:

- ✅ User bisa pilih beberapa produk di cart (checkbox)
- ✅ Checkout semua produk terpilih dalam satu transaksi pembayaran
- ✅ Satu transaction bisa mengelola multiple orders
- ✅ Semua orders dikirim ke alamat yang sama
- ✅ Payment tracking & status update otomatis via Midtrans webhook

## Database Schema

### Tabel: `transactions` (NEW)

Mengelola & menghubungkan multiple orders dengan satu pembayaran.

```sql
CREATE TABLE `transactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` VARCHAR(100) NOT NULL UNIQUE,
  `user_id` INT(11) NOT NULL,
  `total_amount` DECIMAL(12, 2) NOT NULL,
  `status` ENUM('PENDING', 'PAID', 'FAILED') DEFAULT 'PENDING',
  `payment_method` VARCHAR(50) DEFAULT 'MIDTRANS',
  `order_count` INT(11) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `transactions_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

**Columns:**
- `id` - Primary key auto-increment
- `transaction_id` - Unique ID untuk pembayaran (format: TRX-{timestamp}-{user_id})
- `user_id` - Link ke user yang membayar
- `total_amount` - Total nominal pembayaran
- `status` - PENDING → PAID/FAILED
- `payment_method` - Metode pembayaran (MIDTRANS)
- `order_count` - Jumlah orders dalam transaksi ini
- `created_at` / `updated_at` - Timestamps

### Tabel: `orders` (MODIFIED)

Tambahkan field untuk link ke transaction.

```sql
ALTER TABLE `orders` 
ADD COLUMN `transaction_id` VARCHAR(100) NULL,
ADD CONSTRAINT `orders_transaction_id_foreign` 
  FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) 
  ON DELETE SET NULL ON UPDATE CASCADE;
```

**New Column:**
- `transaction_id` - Link ke transaction_id (nullable, bisa multiple orders dengan transaction_id sama)

### Database Relationships Diagram

```
users (1)
  ↓
  └── transactions (1:N via user_id)
       ├── order_count: jumlah orders
       ├── total_amount: total dari semua orders
       └── status: PENDING/PAID/FAILED
             ↓
             └── orders (N via transaction_id) [Multiple orders, same transaction]
                  ├── order_id: unik per order
                  ├── user_id: link ke user
                  ├── total_price: per order
                  ├── status: UNPAID/PAID/SHIPPED/etc
                  └── order_items (1:N via order_id)
                       ├── product_id
                       ├── quantity
                       └── price
```

## API Endpoints

### 1. POST `/api/checkout`

**Checkout multiple orders dalam satu pembayaran**

Request harus authenticated (JWT token dalam header).

**Request Payload:**

```json
{
  "recipient_name": "Nama Penerima",
  "recipient_phone": "081234567890",
  "shipping_address": "Jl. Contoh No. 123",
  "latitude": "-6.2088",
  "longitude": "106.8456",
  "total_price": 150000,
  "shipping_fee": 20000,
  "app_fee": 2500,
  "tax_amount": 5000,
  "voucher_code": "VOUCHER123",
  "discount_amount": 10000,
  "selected_cart_ids": [1, 2, 5]
}
```

**Request Fields:**
- `recipient_name` - Nama penerima barang
- `recipient_phone` - Nomor HP penerima
- `shipping_address` - Alamat pengiriman lengkap
- `latitude` - Koordinat latitude (untuk peta)
- `longitude` - Koordinat longitude (untuk peta)
- `total_price` - Total harga final (sudah include fee & diskon)
- `shipping_fee` - Biaya pengiriman
- `app_fee` - Biaya aplikasi
- `tax_amount` - Pajak
- `voucher_code` - Kode voucher (opsional)
- `discount_amount` - Jumlah diskon
- `selected_cart_ids` - Array ID cart items yang dipilih user (via checkbox)

**Response Success (200):**

```json
{
  "status": true,
  "transaction_id": "TRX-1713184800-123",
  "order_count": 1,
  "orders": [45],
  "redirect_url": "https://app.sandbox.midtrans.com/snap/v2/redirection/..."
}
```

**Response Fields:**
- `status` - true = sukses
- `transaction_id` - ID transaksi yg bisa digunakan untuk tracking
- `order_count` - Jumlah orders yang dibuat
- `orders` - Array database ID orders
- `redirect_url` - URL redirect ke Midtrans untuk pembayaran

**Example cURL:**
```bash
curl -X POST http://localhost:8080/api/checkout \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "recipient_name": "John Doe",
    "recipient_phone": "081234567890",
    "shipping_address": "Jl. Sudirman 123",
    "latitude": "-6.2088",
    "longitude": "106.8456",
    "total_price": 150000,
    "shipping_fee": 20000,
    "app_fee": 2500,
    "tax_amount": 5000,
    "voucher_code": "",
    "discount_amount": 0,
    "selected_cart_ids": [1, 2, 5]
  }'
```

---

### 2. GET `/api/orders/transaction-detail/{transactionId}`

**Lihat detail transaction + semua orders + items**

Header: Requires JWT token

**Response Success (200):**

```json
{
  "status": true,
  "transaction": {
    "id": 1,
    "transaction_id": "TRX-1713184800-123",
    "user_id": 15,
    "total_amount": "150000.00",
    "status": "PAID",
    "payment_method": "MIDTRANS",
    "order_count": 1,
    "created_at": "2024-04-15 10:00:00",
    "updated_at": "2024-04-15 10:05:00"
  },
  "orders": [
    {
      "id": 45,
      "order_id": "PASANGIN-1713184800-123",
      "user_id": 15,
      "transaction_id": "TRX-1713184800-123",
      "recipient_name": "John Doe",
      "recipient_phone": "081234567890",
      "total_price": "150000.00",
      "shipping_fee": "20000.00",
      "app_fee": "2500.00",
      "tax_amount": "5000.00",
      "status": "PAID",
      "shipping_address": "Jl. Sudirman 123",
      "latitude": "-6.2088",
      "longitude": "106.8456",
      "voucher_code": "",
      "discount_amount": "0.00",
      "created_at": "2024-04-15 10:00:00",
      "items": [
        {
          "order_id": 45,
          "product_id": 10,
          "quantity": 2,
          "price": "50000.00",
          "name": "Produk A",
          "photo": "produk-a.jpg",
          "image_url": "http://domain.com/uploads/products/produk-a.jpg"
        },
        {
          "order_id": 45,
          "product_id": 15,
          "quantity": 1,
          "price": "50000.00",
          "name": "Produk B",
          "photo": "produk-b.jpg",
          "image_url": "http://domain.com/uploads/products/produk-b.jpg"
        }
      ]
    }
  ]
}
```

---

### 3. GET `/api/orders/transaction-history`

**Ambil semua transaction history user**

Header: Requires JWT token

**Response Success (200):**

```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "transaction_id": "TRX-1713184800-123",
      "user_id": 15,
      "total_amount": "150000.00",
      "status": "PAID",
      "payment_method": "MIDTRANS",
      "order_count": 1,
      "created_at": "2024-04-15 10:00:00",
      "updated_at": "2024-04-15 10:05:00",
      "orders_detail": [
        {
          "id": 45,
          "order_id": "PASANGIN-1713184800-123",
          "user_id": 15,
          "status": "PAID",
          "total_price": "150000.00",
          "created_at": "2024-04-15 10:00:00"
        }
      ]
    },
    {
      "id": 2,
      "transaction_id": "TRX-1713100000-123",
      "user_id": 15,
      "total_amount": "250000.00",
      "status": "PENDING",
      "payment_method": "MIDTRANS",
      "order_count": 2,
      "created_at": "2024-04-14 15:00:00",
      "orders_detail": [
        {
          "id": 44,
          "order_id": "PASANGIN-1713100000-123",
          "status": "PENDING"
        }
      ]
    }
  ]
}
```

---

### 4. POST `/api/orders/webhook-midtrans`

**Webhook callback dari Midtrans untuk update payment status**

⚠️ **IMPORTANT:** Route ini **TIDAK memerlukan JWT token** karena dipanggil oleh Midtrans server.

**Request dari Midtrans:**

```json
{
  "order_id": "TRX-1713184800-123",
  "transaction_status": "settlement",
  "fraud_status": "accept",
  "payment_type": "credit_card",
  "transaction_id": "0511101110505008",
  "status_code": "200",
  "gross_amount": "150000.00"
}
```

**Response:**

```json
{
  "status": true,
  "message": "Webhook processed successfully"
}
```

**Backend Actions:**
- Jika `transaction_status` = `capture` atau `settlement` → Update transaction status = `PAID`, update semua orders = `PAID`
- Jika `transaction_status` = `deny`, `cancel`, `expire` → Update transaction status = `FAILED`, update semua orders = `CANCELLED`

---

## Implementation Flow

### User Checkout Journey

```
1. FRONTEND - User ke Cart
   - User lihat produktnya di cart
   - Ada checkbox untuk setiap product
   - User pilih (checklist) produk yg mau dibeli (multiple selection)

2. FRONTEND - Checkout Form
   - User isi alamat pengiriman
   - Frontend hitung total_price (product + shipping + tax - diskon)
   - Frontend kumpulkan selected_cart_ids (yang ter-checklist)
   - Frontend POST ke /api/checkout

3. BACKEND - Checkout Processing
   ┌─ Generate transaction_id (TRX-timestamp-userid)
   ├─ Generate order_id (PASANGIN-timestamp-userid)
   ├─ Insert order ke tabel orders + linking transaction_id
   ├─ Foreach selected cart item → insert order_items
   ├─ Delete selected items dari cart
   ├─ Insert transaction record
   ├─ Create Midtrans Snap transaction
   └─ Return transaction_id + redirect_url

4. FRONTEND - Payment
   - Redirect user ke Midtrans Snap (via redirect_url)
   - User bayar di Midtrans
   - Midtrans callback ke /api/orders/webhook-midtrans

5. BACKEND - Webhook Processing
   ┌─ Validasi signature
   ├─ Update transaction status (PENDING → PAID/FAILED)
   ├─ Update ALL orders status dalam transaction yg sama
   └─ Log webhook event

6. FRONTEND - Order Tracking
   - User bisa lihat transaction history
   - Per transaction: lihat semua orders + items
   - Track shipping status per order
```

---

## Database Migration Instructions

### Step 1: Backup Database (Optional but Recommended)

```bash
# Backup existing database
mysqldump -u root -p backend_core > backup_before_migration.sql
```

### Step 2: Run CodeIgniter Migrations

```bash
cd /path/to/backend_core
php spark migrate
```

This akan jalankan semua migration files:
- `2024_04_15_create_transactions_table.php` → Buat tabel transactions
- `2024_04_15_add_transaction_id_to_orders.php` → Ubah orders table

### Step 3: Verify Schema

**Check transactions table:**
```sql
DESCRIBE transactions;
```

**Check orders table modification:**
```sql
DESCRIBE orders;
-- Should see transaction_id column

SHOW KEYS FROM orders;
-- Should see foreign key: orders_transaction_id_foreign
```

**Check example data:**
```sql
SELECT * FROM transactions LIMIT 5;
SELECT * FROM orders WHERE transaction_id IS NOT NULL LIMIT 5;
```

---

## Key Changes vs Old Implementation

| Feature | Sebelumnya | Sekarang |
|---------|-----------|---------|
| Orders per checkout | 1 order | 1 atau lebih orders |
| Cart selection | Semua items | Selected items via checkbox |
| Payment linking | Direct ke order | Via transaction (1 txn : N orders) |
| Request payload | Basic | Tambah `selected_cart_ids` |
| Response | `order_id` | `transaction_id` + `order_count` + `orders[]` |
| Webhook update | Update 1 order | Update 1 transaction + N orders |
| History view | Per order | Per transaction (grouped) |

---

## Transaction States & Status Flow

```
┌─────────────────────────────────────────────────────┐
│ USER MELAKUKAN CHECKOUT                             │
└─────────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────────┐
│ TRANSACTION CREATED                                 │
│ Status: PENDING                                     │
│ Orders: UNPAID                                      │
└─────────────────────────────────────────────────────┘
                    ↓
         ┌──────────┴──────────┐
         ↓ (Payment Sukses)    ↓ (Payment Gagal)
┌──────────────────────┐   ┌──────────────────────┐
│ TRANSACTION: PAID    │   │ TRANSACTION: FAILED  │
│ ORDERS: PAID         │   │ ORDERS: CANCELLED    │
│ Status: SETTLED      │   │ Status: DENIED       │
│ Shipping: ACTIVE     │   │ Cart: RESTORED (opt) │
│ Stock: REDUCED       │   │ (User bisa retry)    │
└──────────────────────┘   └──────────────────────┘
         ↓
┌──────────────────────┐
│ SHIPPED (by courier) │
│ DELIVERED            │
└──────────────────────┘
```

**Transaction Statuses:**
- `PENDING` - Menunggu pembayaran dari user
- `PAID` - Pembayaran berhasil, orders siap dikirim
- `FAILED` - Pembayaran gagal, transaction bisa di-retry

**Order Statuses:**
- `UNPAID` - Order dibuat, belum bayar
- `PAID` - Pembayaran sukses
- `SHIPPED` - Barang dalam perjalanan
- `COMPLETED` - Barang diterima
- `CANCELLED` - Order dibatalkan (pembayaran gagal)

---

## Security Considerations

### 1. Webhook Validation
- Midtrans akan sign webhook dengan server key
- LAKUKAN VALIDASI signature sebelum process

### 2. JWT Token
- `/api/checkout` wajib JWT token (user teridentifikasi)
- `/api/orders/webhook-midtrans` TIDAK perlu JWT (Midtrans calling)

### 3. Foreign Key Constraints
- `orders.transaction_id` → `transactions.transaction_id` (CASCADE delete)
- `transactions.user_id` → `users.id` (CASCADE delete)

### 4. Data Validation
```php
// Contoh: Validasi selected_cart_ids milik user
$cartIds = $this->request->getVar('selected_cart_ids');
$validIds = $this->db->table('cart')
    ->select('id')
    ->where('user_id', $userId)
    ->whereIn('id', $cartIds)
    ->get()
    ->getResultArray();

if (count($validIds) !== count($cartIds)) {
    // Ada cart item yang bukan milik user!
    return $this->fail('Invalid cart items');
}
```

---

## Route Configuration

Routes sudah tersedia di [app/Config/Routes.php](app/Config/Routes.php):

```php
$routes->post('checkout', 'OrderApi::checkout');
$routes->get('orders/transaction-detail/(:any)', 'OrderApi::transactionDetail/$1');
$routes->get('orders/transaction-history', 'OrderApi::transactionHistory');
$routes->post('orders/webhook-midtrans', 'OrderApi::webhookMidtrans');
```

---

## Deployment Checklist

- [ ] Backup database
- [ ] Run migrations (`php spark migrate`)
- [ ] Verify schema (DESCRIBE tables)
- [ ] Update frontend to send `selected_cart_ids`
- [ ] Configure Midtrans webhook URL di dashboard Midtrans
- [ ] Test checkout flow (sandbox mode)
- [ ] Test webhook callback
- [ ] Verify payment status updates
- [ ] Test transaction history API
- [ ] Monitor logs untuk errors

---

## Troubleshooting

### Migration Error: "Foreign key incompatibility"
- Pastikan `transaction_id` di orders & transactions table sama: `VARCHAR(100)` dengan charset `utf8mb4`
- Hapus UNIQUE constraint pada `orders.transaction_id` (tidak boleh UNIQUE)

### Webhook tidak triggered
- Verify webhook URL di Midtrans dashboard
- Pastikan route `/api/orders/webhook-midtrans` accessible (tidak perlu auth)
- Check logs di `writable/logs/`

### Orders tidak ter-update setelah payment
- Pastikan transaction_id di orders match dengan transaction_id di transactions
- Verify foreign key constraint ada dan benar
- Check webhook response (should return 200)

---

## Performance Optimization

### Indexes sudah created:

```sql
CREATE INDEX idx_user_transaction ON orders(user_id, transaction_id);
CREATE INDEX idx_user_status ON transactions(user_id, status);
CREATE INDEX idx_created_at ON transactions(created_at);
CREATE INDEX idx_created_at_orders ON orders(created_at);
```

### Query Optimization:

```php
// ✅ BAIK: Ambil transaction + orders sekaligus
$transaction = $this->db->table('transactions')
    ->where('transaction_id', $txnId)
    ->get()->getRow();

$orders = $this->db->table('orders')
    ->where('transaction_id', $txnId)
    ->get()->getResultArray();

// ❌ BURUK: Query in loop (N+1 problem)
foreach ($orders as $order) {
    $items = $this->db->table('order_items')  // Setiap order query items
        ->where('order_id', $order['id'])
        ->get()->getResultArray();
}
```

---

## Future Enhancements

1. **Group Orders by Seller**
   - Satu transaction → multiple orders dari different sellers
   - Auto-calculate shipping per seller

2. **Partial Payment**
   - User bayar 50% dulu, 50% sisanya kemudian
   - Track partial payment status

3. **Order Bundling**
   - Offer discount untuk multiple orders bundled
   - Track bundle transaction

4. **Payment Retry**
   - User bisa retry pembayaran failed transaction
   - Generate new Snap token without duplicate

---

## References

- [Midtrans Documentation](https://docs.midtrans.com/)
- [CodeIgniter Database Transactions](https://codeigniter.com/user_guide/database/transactions.html)
- [CodeIgniter Forge](https://codeigniter.com/user_guide/database/forge.html)

---

**Last Updated:** April 15, 2024
**Version:** 1.0
