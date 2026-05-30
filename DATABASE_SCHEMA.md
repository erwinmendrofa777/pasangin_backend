# Database Schema (from SQL Dump)

> **Catatan:** Dokumentasi ini di-*generate* langsung dari file `stuh8812_pasangin_db.sql` sehingga mencakup tipe data asli dan nilai ENUM yang spesifik.

## Tabel: `about_application_pasangin`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `admin_activity_logs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `admin_id` | `int(10) UNSIGNED NOT NULL` |  |
| `action` | `varchar(50) NOT NULL` |  |
| `module` | `varchar(50) NOT NULL` |  |
| `description` | `text NOT NULL` |  |
| `ip_address` | `varchar(45) DEFAULT NULL` |  |
| `user_agent` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `alamat_user`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `id_user` | `int(10) UNSIGNED NOT NULL` |  |
| `alamat` | `text NOT NULL` |  |
| `latitude` | `double DEFAULT NULL` |  |
| `longitude` | `double DEFAULT NULL` |  |
| `label` | `varchar(50) DEFAULT NULL` |  |
| `is_active` | `tinyint(1) DEFAULT 1` |  |

## Tabel: `banners`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `title` | `varchar(100) DEFAULT NULL` |  |
| `image` | `varchar(255) NOT NULL` |  |
| `target_app` | `enum('client'` |  |
| `is_active` | `tinyint(1) DEFAULT 1` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `cart`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(11) NOT NULL` |  |
| `quantity` | `int(11) DEFAULT 1` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `categories`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `supplier_id` | `int(11) UNSIGNED NOT NULL` |  |
| `name` | `varchar(255) NOT NULL` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `construction_addendum`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `roman_number` | `varchar(5) DEFAULT 'I'` |  |
| `group_name` | `varchar(100) DEFAULT 'PEKERJAAN'` |  |
| `sub_group_name` | `varchar(100) DEFAULT 'Detail'` |  |
| `section_group` | `varchar(100) DEFAULT NULL` |  |
| `section_name` | `varchar(100) DEFAULT NULL` |  |
| `activity_name` | `varchar(255) NOT NULL` |  |
| `volume` | `decimal(15` |  |
| `unit` | `varchar(20) DEFAULT NULL` |  |
| `selected_material_id` | `int(11) DEFAULT NULL` |  |
| `current_unit_price` | `decimal(15` |  |
| `total_price` | `decimal(15` |  |
| `is_locked` | `tinyint(1) DEFAULT 0` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `construction_addendum_materials`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `addendum_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(10) UNSIGNED NOT NULL` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |

## Tabel: `construction_agreements`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `agreement_id` | `int(10) UNSIGNED NOT NULL` |  |
| `is_checked` | `enum('1'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `construction_attendance`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_construction` | `int(11) NOT NULL` |  |
| `type` | `enum('masuk'` |  |
| `file` | `varchar(255) DEFAULT NULL` |  |
| `jumlah_tukang` | `int(11) DEFAULT NULL` |  |
| `longitude` | `decimal(11` |  |
| `latitude` | `decimal(10` |  |
| `waktu` | `datetime DEFAULT NULL` |  |
| `deskripsi` | `text DEFAULT NULL` |  |

## Tabel: `construction_designs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `title` | `varchar(255) DEFAULT 'Gambar Kerja'` |  |
| `file` | `varchar(255) NOT NULL` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `construction_invoices`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) DEFAULT NULL` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `description` | `varchar(255) NOT NULL` |  |
| `amount` | `decimal(15` |  |
| `due_date` | `date DEFAULT NULL` |  |
| `status` | `enum('UNPAID'` |  |
| `midtrans_order_id` | `varchar(255) DEFAULT NULL` |  |
| `payment_url` | `text DEFAULT NULL` |  |
| `snap_token` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `order_id` | `varchar(50) DEFAULT NULL` |  |

## Tabel: `construction_jobs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `construction_id` | `int(11) UNSIGNED NOT NULL` |  |
| `detail_pekerjaan` | `text DEFAULT NULL` |  |
| `detail_lokasi` | `text DEFAULT NULL` |  |
| `tempat_tinggal` | `varchar(50) DEFAULT NULL` |  |
| `tanggal_mulai` | `date DEFAULT NULL` |  |
| `tanggal_akhir` | `date DEFAULT NULL` |  |
| `upah_per_hari` | `decimal(15` |  |
| `latitude` | `varchar(50) DEFAULT NULL` |  |
| `longitude` | `varchar(50) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `construction_progress`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_construction_targets` | `int(11) DEFAULT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `week_number` | `int(11) DEFAULT NULL` |  |
| `bobot` | `decimal(5` |  |
| `description` | `text DEFAULT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `photo_url` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `construction_rabs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `roman_number` | `varchar(5) DEFAULT 'I'` |  |
| `group_name` | `varchar(100) DEFAULT 'PEKERJAAN'` |  |
| `sub_group_name` | `varchar(100) DEFAULT 'Detail'` |  |
| `section_group` | `varchar(100) DEFAULT NULL` |  |
| `section_name` | `varchar(100) DEFAULT NULL` |  |
| `activity_name` | `varchar(255) NOT NULL` |  |
| `volume` | `decimal(15` |  |
| `unit` | `varchar(20) DEFAULT NULL` |  |
| `selected_material_id` | `int(11) DEFAULT NULL` |  |
| `current_unit_price` | `decimal(15` |  |
| `total_price` | `decimal(15` |  |
| `is_locked` | `tinyint(1) DEFAULT 0` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `construction_rab_materials`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `rab_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(11) NOT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `construction_requests`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `full_name` | `varchar(255) DEFAULT NULL` |  |
| `phone` | `varchar(20) DEFAULT NULL` |  |
| `land_area` | `int(11) DEFAULT 0` |  |
| `building_area` | `int(11) DEFAULT 0` |  |
| `survey_date` | `date DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `latitude` | `double DEFAULT NULL` |  |
| `longitude` | `double DEFAULT NULL` |  |
| `location_photo` | `varchar(255) DEFAULT NULL` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `survey_cost` | `decimal(15` |  |
| `discount_amount` | `decimal(15` |  |
| `total_payment` | `decimal(15` |  |
| `status` | `enum('PENDING'` |  |
| `start_date` | `date DEFAULT NULL` |  |
| `week` | `int(11) DEFAULT 1` |  |
| `workday` | `int(11) DEFAULT 0` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |
| `survey_notes` | `text DEFAULT NULL` |  |
| `survey_file` | `varchar(255) DEFAULT NULL` |  |
| `design_file` | `varchar(255) DEFAULT NULL` |  |
| `rab_file` | `varchar(255) DEFAULT NULL` |  |
| `rab_total` | `decimal(15` |  |
| `gambar1` | `varchar(255) DEFAULT NULL` |  |
| `gambar2` | `varchar(255) DEFAULT NULL` |  |
| `gambar3` | `varchar(255) DEFAULT NULL` |  |
| `gambar4` | `varchar(255) DEFAULT NULL` |  |
| `gambar5` | `varchar(255) DEFAULT NULL` |  |

## Tabel: `construction_surveys`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `survey_title` | `varchar(255) DEFAULT NULL` |  |
| `survey_notes` | `text DEFAULT NULL` |  |
| `survey_file` | `varchar(255) DEFAULT NULL` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `construction_targets`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_job_applications` | `int(11) NOT NULL` |  |
| `id_construction_rabs` | `int(11) DEFAULT NULL` |  |
| `id_construction_addendum` | `int(11) DEFAULT NULL` |  |
| `construction_id` | `int(11) NOT NULL` |  |
| `start_week` | `int(11) DEFAULT NULL` |  |
| `end_week` | `int(11) DEFAULT NULL` |  |
| `bobot` | `decimal(5` |  |
| `status` | `enum('Pending'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `conversations`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `title` | `varchar(255) DEFAULT 'Bantuan'` |  |
| `client_id` | `int(11) NOT NULL` |  |
| `client_type` | `varchar(100) NOT NULL` |  |
| `admin_id` | `int(11) DEFAULT NULL` |  |
| `last_message_preview` | `varchar(255) DEFAULT NULL` |  |
| `last_message_at` | `datetime DEFAULT NULL` |  |
| `unread_by_admin_count` | `int(11) NOT NULL DEFAULT 0` |  |
| `status` | `enum('open'` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL ON UPDATE current_timestamp()` |  |

## Tabel: `design_requests`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `user_id` | `int(11) DEFAULT NULL` |  |
| `full_name` | `varchar(150) NOT NULL` |  |
| `phone_number` | `varchar(20) NOT NULL` |  |
| `land_area` | `decimal(10` |  |
| `building_area` | `decimal(10` |  |
| `design_concept` | `varchar(50) NOT NULL` |  |
| `other_concept_desc` | `text DEFAULT NULL` |  |
| `survey_date` | `date NOT NULL` |  |
| `location_address` | `text NOT NULL` |  |
| `latitude` | `decimal(10` |  |
| `longitude` | `decimal(11` |  |
| `survey_fee` | `decimal(15` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `discount_amount` | `decimal(15` |  |
| `total_payment` | `decimal(15` |  |
| `status` | `enum('PENDING'` |  |
| `start_date` | `date DEFAULT NULL` |  |
| `target_date` | `date DEFAULT NULL` |  |
| `progress_percent` | `int(11) DEFAULT 0` |  |
| `num_weeks` | `int(11) NOT NULL DEFAULT 8` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `design_targets`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `design_request_id` | `int(11) NOT NULL` |  |
| `task_name` | `varchar(255) NOT NULL` |  |
| `start_week` | `int(11) DEFAULT NULL` |  |
| `end_week` | `int(11) DEFAULT NULL` |  |
| `keterangan` | `text DEFAULT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `created_at` | `datetime DEFAULT NULL` |  |

## Tabel: `job_applications`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `tukang_id` | `int(11) DEFAULT NULL` |  |
| `project_id` | `int(11) DEFAULT NULL` |  |
| `project_type` | `enum('construction'` |  |
| `tukang_name` | `varchar(255) NOT NULL` |  |
| `email` | `varchar(255) NOT NULL` |  |
| `phone` | `varchar(20) NOT NULL` |  |
| `dob` | `date DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `specialization` | `varchar(255) DEFAULT NULL` |  |
| `status` | `enum('Berkas Diproses'` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `messages`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `bigint(20) NOT NULL` |  |
| `conversation_id` | `int(11) NOT NULL` |  |
| `sender_id` | `int(11) NOT NULL` |  |
| `sender_type` | `varchar(100) NOT NULL` |  |
| `body` | `text NOT NULL` |  |
| `file_url` | `varchar(255) DEFAULT NULL` |  |
| `message_type` | `enum('text'` |  |
| `is_read_by_admin` | `tinyint(1) NOT NULL DEFAULT 0` |  |
| `is_read_by_client` | `tinyint(1) NOT NULL DEFAULT 0` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `notifications`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `target_id` | `int(11) DEFAULT NULL` |  |
| `target_type` | `enum('client'` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `message` | `text NOT NULL` |  |
| `image_url` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `notification_deletes`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `notification_id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `user_type` | `enum('tukang'` |  |

## Tabel: `notification_reads`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `notification_id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `user_type` | `enum('tukang'` |  |
| `read_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `orders`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `order_id` | `varchar(50) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `recipient_name` | `varchar(255) DEFAULT NULL` |  |
| `recipient_phone` | `varchar(20) DEFAULT NULL` |  |
| `total_price` | `decimal(15` |  |
| `shipping_fee` | `decimal(15` |  |
| `app_fee` | `decimal(15` |  |
| `tax_amount` | `decimal(15` |  |
| `status` | `enum('PENDING'` |  |
| `shipping_address` | `text DEFAULT NULL` |  |
| `latitude` | `double DEFAULT NULL` |  |
| `longitude` | `double DEFAULT NULL` |  |
| `midtrans_order_id` | `varchar(100) DEFAULT NULL` |  |
| `snap_token` | `varchar(255) DEFAULT NULL` |  |
| `payment_url` | `text DEFAULT NULL` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `discount_amount` | `decimal(15` |  |
| `transaction_id` | `varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |

## Tabel: `order_items`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `order_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(11) NOT NULL` |  |
| `quantity` | `int(11) NOT NULL` |  |
| `price` | `decimal(15` |  |

## Tabel: `password_reset_tokens`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `email` | `varchar(255) NOT NULL` |  |
| `role` | `varchar(50) NOT NULL` |  |
| `token` | `varchar(255) NOT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `price_estimate_concepts`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `bigint(20) UNSIGNED NOT NULL` |  |
| `name` | `varchar(50) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `price_estimate_qualities`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `bigint(20) UNSIGNED NOT NULL` |  |
| `concept_id` | `bigint(20) UNSIGNED NOT NULL` |  |
| `label` | `varchar(50) NOT NULL` |  |
| `min_price` | `decimal(15` |  |
| `max_price` | `decimal(15` |  |
| `description` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `products`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `supplier_id` | `int(11) UNSIGNED NOT NULL` |  |
| `category_id` | `int(11) UNSIGNED DEFAULT NULL` |  |
| `name` | `varchar(255) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `price` | `decimal(10` |  |
| `unit` | `varchar(50) DEFAULT 'pcs'` |  |
| `stock` | `int(11) NOT NULL DEFAULT 0` |  |
| `min_order` | `int(11) DEFAULT 1` |  |
| `weight` | `int(11) DEFAULT 0` |  |
| `status` | `enum('aktif'` |  |
| `photo` | `varchar(255) DEFAULT NULL` |  |
| `video` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL ON UPDATE current_timestamp()` |  |
| `rata_rata_rating` | `decimal(3` |  |
| `total_ulasan` | `int(11) DEFAULT 0` |  |

## Tabel: `products_rating`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_product` | `int(10) UNSIGNED NOT NULL` |  |
| `rating` | `enum('1'` |  |
| `comment` | `text DEFAULT NULL` |  |
| `gambar1` | `varchar(255) DEFAULT NULL` |  |
| `gambar2` | `varchar(255) DEFAULT NULL` |  |
| `gambar3` | `varchar(255) DEFAULT NULL` |  |
| `gambar4` | `varchar(255) DEFAULT NULL` |  |
| `gambar5` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `product_images`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `product_id` | `int(11) UNSIGNED NOT NULL` |  |
| `photo_url` | `varchar(255) NOT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `project_designs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `design_request_id` | `int(11) UNSIGNED NOT NULL` |  |
| `design_targets_id` | `int(11) DEFAULT NULL` |  |
| `revision_number` | `int(11) NOT NULL DEFAULT 1` |  |
| `design_name` | `varchar(255) NOT NULL` |  |
| `file` | `varchar(255) NOT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `revision_note` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `project_invoices`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `midtrans_order_id` | `varchar(50) DEFAULT NULL` |  |
| `payment_status` | `varchar(20) DEFAULT 'UNPAID'` |  |
| `design_request_id` | `int(11) UNSIGNED NOT NULL` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `description` | `varchar(255) NOT NULL` |  |
| `amount` | `decimal(15` |  |
| `due_date` | `date NOT NULL` |  |
| `status` | `enum('UNPAID'` |  |
| `snap_token` | `varchar(255) DEFAULT NULL` |  |
| `payment_url` | `varchar(255) DEFAULT NULL` |  |
| `payment_type` | `varchar(50) DEFAULT NULL` |  |
| `proof_file` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `project_surveys`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `design_request_id` | `int(11) UNSIGNED NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `note` | `text DEFAULT NULL` |  |
| `file` | `varchar(255) DEFAULT NULL` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `promos`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `supplier_id` | `int(11) UNSIGNED NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `discount_type` | `enum('percentage'` |  |
| `discount_value` | `decimal(15` |  |
| `promo_code` | `varchar(50) DEFAULT NULL` |  |
| `start_date` | `date DEFAULT NULL` |  |
| `end_date` | `date DEFAULT NULL` |  |
| `status` | `enum('active'` |  |
| `photo` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `rab_material_options`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `rab_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(11) NOT NULL` |  |
| `is_default` | `tinyint(1) DEFAULT 0` |  |

## Tabel: `renovation_agreements`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `renovation_id` | `int(11) NOT NULL` |  |
| `agreement_id` | `int(10) UNSIGNED NOT NULL` |  |
| `is_checked` | `enum('0'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `renovation_attendance`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_renovation` | `int(11) NOT NULL` |  |
| `type` | `enum('masuk'` |  |
| `file` | `varchar(255) DEFAULT NULL` |  |
| `jumlah_tukang` | `int(11) DEFAULT NULL` |  |
| `longitude` | `decimal(11` |  |
| `latitude` | `decimal(10` |  |
| `waktu` | `datetime DEFAULT NULL` |  |
| `deskripsi` | `text DEFAULT NULL` |  |

## Tabel: `renovation_designs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `request_id` | `int(11) NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `file_url` | `varchar(255) NOT NULL` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |

## Tabel: `renovation_invoices`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `renovation_id` | `int(11) NOT NULL COMMENT 'Merujuk ke id di tabel renovation_requests'` |  |
| `user_id` | `int(11) NOT NULL COMMENT 'Merujuk ke id di tabel users'` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `description` | `varchar(255) DEFAULT NULL` |  |
| `amount` | `decimal(15` |  |
| `due_date` | `date DEFAULT NULL` |  |
| `status` | `enum('UNPAID'` |  |
| `midtrans_order_id` | `varchar(255) DEFAULT NULL` |  |
| `payment_url` | `text DEFAULT NULL` |  |
| `snap_token` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime NOT NULL DEFAULT current_timestamp()` |  |
| `order_id` | `varchar(50) DEFAULT NULL` |  |

## Tabel: `renovation_jobs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `renovation_id` | `int(11) NOT NULL` |  |
| `detail_pekerjaan` | `text DEFAULT NULL` |  |
| `detail_lokasi` | `text DEFAULT NULL` |  |
| `tempat_tinggal` | `varchar(50) DEFAULT NULL` |  |
| `tanggal_mulai` | `date DEFAULT NULL` |  |
| `tanggal_akhir` | `date DEFAULT NULL` |  |
| `upah_per_hari` | `decimal(15` |  |
| `latitude` | `varchar(50) DEFAULT NULL` |  |
| `longitude` | `varchar(50) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `renovation_progress`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_renovation_targets` | `int(11) DEFAULT NULL` |  |
| `renovation_id` | `int(11) NOT NULL` |  |
| `week_number` | `int(11) DEFAULT NULL` |  |
| `bobot` | `decimal(5` |  |
| `description` | `text DEFAULT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `photo_url` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `renovation_rabs`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `renovation_id` | `int(11) NOT NULL` |  |
| `roman_number` | `varchar(5) DEFAULT 'I'` |  |
| `group_name` | `varchar(100) DEFAULT 'PEKERJAAN'` |  |
| `sub_group_name` | `varchar(100) DEFAULT 'Detail'` |  |
| `section_group` | `varchar(100) DEFAULT NULL` |  |
| `section_name` | `varchar(100) DEFAULT NULL` |  |
| `activity_name` | `varchar(255) NOT NULL` |  |
| `volume` | `decimal(15` |  |
| `unit` | `varchar(20) DEFAULT NULL` |  |
| `selected_material_id` | `int(11) DEFAULT NULL` |  |
| `current_unit_price` | `decimal(15` |  |
| `total_price` | `decimal(15` |  |
| `is_locked` | `tinyint(1) DEFAULT 0` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `renovation_rab_materials`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `rab_id` | `int(11) NOT NULL` |  |
| `product_id` | `int(11) NOT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `renovation_requests`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_id` | `int(11) NOT NULL` |  |
| `full_name` | `varchar(255) DEFAULT NULL` |  |
| `phone` | `varchar(20) DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `latitude` | `varchar(50) DEFAULT NULL` |  |
| `longitude` | `varchar(50) DEFAULT NULL` |  |
| `survey_date` | `date DEFAULT NULL` |  |
| `renovation_type` | `enum('Total'` |  |
| `description` | `text DEFAULT NULL` |  |
| `location_photo` | `varchar(255) DEFAULT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `start_date` | `date DEFAULT NULL` |  |
| `week` | `int(11) DEFAULT 1` |  |
| `workday` | `int(11) DEFAULT 0` |  |
| `rab_file` | `varchar(255) DEFAULT NULL` |  |
| `survey_cost` | `decimal(15` |  |
| `discount_amount` | `decimal(15` |  |
| `total_payment` | `decimal(15` |  |
| `voucher_code` | `varchar(50) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |
| `gambar1` | `varchar(255) DEFAULT NULL` |  |
| `gambar2` | `varchar(255) DEFAULT NULL` |  |
| `gambar3` | `varchar(255) DEFAULT NULL` |  |
| `gambar4` | `varchar(255) DEFAULT NULL` |  |
| `gambar5` | `varchar(255) DEFAULT NULL` |  |

## Tabel: `renovation_surveys`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `user_admin_id` | `int(10) UNSIGNED DEFAULT NULL` |  |
| `request_id` | `int(11) NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `file_url` | `varchar(255) NOT NULL` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |

## Tabel: `renovation_targets`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_job_applications` | `int(11) NOT NULL` |  |
| `id_renovation_rabs` | `int(11) DEFAULT NULL` |  |
| `renovation_id` | `int(11) NOT NULL` |  |
| `start_week` | `int(11) DEFAULT NULL` |  |
| `end_week` | `int(11) DEFAULT NULL` |  |
| `bobot` | `decimal(5` |  |
| `status` | `enum('Pending'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `roles`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `role_name` | `varchar(100) NOT NULL` |  |
| `permissions` | `text DEFAULT NULL` |  |

## Tabel: `suppliers`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `name` | `varchar(255) NOT NULL` |  |
| `email` | `varchar(100) NOT NULL` |  |
| `password` | `varchar(255) NOT NULL` |  |
| `contact_person` | `varchar(255) DEFAULT NULL` |  |
| `phone` | `varchar(20) DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `province` | `varchar(100) DEFAULT NULL` |  |
| `city` | `varchar(100) DEFAULT NULL` |  |
| `district` | `varchar(100) DEFAULT NULL` |  |
| `latitude` | `double DEFAULT NULL` |  |
| `longitude` | `double DEFAULT NULL` |  |
| `logo_url` | `varchar(255) DEFAULT NULL` |  |
| `is_active` | `tinyint(1) NOT NULL DEFAULT 1` |  |
| `is_verify` | `tinyint(1) DEFAULT 0` |  |
| `nik` | `varchar(255) DEFAULT NULL` |  |
| `status` | `enum('pending'` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |
| `fcm_token` | `text DEFAULT NULL` |  |
| `rata_rata_rating` | `decimal(3` |  |
| `total_ulasan` | `int(11) DEFAULT 0` |  |

## Tabel: `suppliers_rating`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_supplier` | `int(11) NOT NULL` |  |
| `rating` | `enum('1'` |  |
| `comment` | `text DEFAULT NULL` |  |
| `gambar1` | `varchar(255) DEFAULT NULL` |  |
| `gambar2` | `varchar(255) DEFAULT NULL` |  |
| `gambar3` | `varchar(255) DEFAULT NULL` |  |
| `gambar4` | `varchar(255) DEFAULT NULL` |  |
| `gambar5` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `supplier_banner`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_supplier` | `int(11) NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `image` | `varchar(255) NOT NULL` |  |
| `start_date` | `datetime DEFAULT NULL` |  |
| `end_date` | `datetime DEFAULT NULL` |  |
| `note` | `text DEFAULT NULL` |  |
| `status` | `enum('PENDING'` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `supplier_ongkir`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `id_suppliers` | `int(11) NOT NULL` |  |
| `ongkir` | `decimal(15` |  |
| `min_distance` | `decimal(10` |  |
| `max_distance` | `decimal(10` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `supplier_withdrawals`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `supplier_id` | `int(11) UNSIGNED NOT NULL` |  |
| `amount` | `decimal(15` |  |
| `bank_name` | `varchar(100) NOT NULL` |  |
| `account_number` | `varchar(50) NOT NULL` |  |
| `account_name` | `varchar(255) NOT NULL` |  |
| `status` | `enum('pending'` |  |
| `admin_note` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `terms_of_agreement`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `title` | `varchar(255) NOT NULL` |  |
| `description` | `text NOT NULL` |  |
| `target_app` | `enum('CLIENT'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `tips`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `title` | `varchar(200) NOT NULL` |  |
| `image` | `varchar(255) NOT NULL` |  |
| `content` | `text DEFAULT NULL` |  |
| `target_app` | `enum('client'` |  |
| `is_active` | `tinyint(1) DEFAULT 1` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `transactions`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `transaction_id` | `varchar(100) NOT NULL` |  |
| `user_id` | `int(11) UNSIGNED NOT NULL` |  |
| `total_amount` | `decimal(12` |  |
| `status` | `enum('PENDING'` |  |
| `payment_method` | `varchar(50) NOT NULL DEFAULT 'MIDTRANS'` |  |
| `order_count` | `int(11) NOT NULL DEFAULT 1` |  |
| `created_at` | `datetime NOT NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `tukang`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `agent_code` | `varchar(50) DEFAULT NULL` |  |
| `name` | `varchar(255) NOT NULL` |  |
| `email` | `varchar(255) NOT NULL` |  |
| `password` | `varchar(255) NOT NULL` |  |
| `phone` | `varchar(20) DEFAULT NULL` |  |
| `gender` | `enum('Laki-laki'` |  |
| `dob` | `date DEFAULT NULL` |  |
| `ktp_address` | `text DEFAULT NULL` |  |
| `domicile_address` | `text DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `profile_photo` | `varchar(255) DEFAULT NULL` |  |
| `ktp_photo` | `varchar(255) DEFAULT NULL` |  |
| `selfie_photo` | `varchar(255) DEFAULT NULL` |  |
| `specialization` | `varchar(100) DEFAULT NULL` |  |
| `status` | `enum('Berkas Diproses'` |  |
| `balance` | `decimal(15` |  |
| `last_login_at` | `datetime DEFAULT NULL` |  |
| `remember_token` | `varchar(100) DEFAULT NULL` |  |
| `created_at` | `timestamp NOT NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL ON UPDATE current_timestamp()` |  |
| `registration_step` | `int(11) DEFAULT 1` |  |
| `fcm_token` | `text DEFAULT NULL` |  |
| `is_verify` | `tinyint(1) DEFAULT 0` |  |
| `nik` | `varchar(255) DEFAULT NULL` |  |
| `rata_rata_rating` | `decimal(3` |  |
| `total_ulasan` | `int(11) DEFAULT 0` |  |

## Tabel: `tukang_rating`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `id_tukang` | `int(11) NOT NULL` |  |
| `target_id` | `int(11) DEFAULT NULL` |  |
| `project_type` | `enum('construction'` |  |
| `skill_score` | `enum('1'` |  |
| `behavior_score` | `enum('1'` |  |
| `comment` | `text DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `tukang_transactions`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `tukang_id` | `int(11) NOT NULL` |  |
| `amount` | `decimal(15` |  |
| `type` | `enum('income'` |  |
| `description` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |

## Tabel: `users`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `nik` | `varchar(255) DEFAULT NULL` |  |
| `full_name` | `varchar(100) NOT NULL` |  |
| `email` | `varchar(100) NOT NULL` |  |
| `password` | `varchar(255) NOT NULL` |  |
| `phone_number` | `varchar(20) DEFAULT NULL` |  |
| `gender` | `enum('Laki - laki'` |  |
| `birth_date` | `date DEFAULT NULL` |  |
| `address` | `text DEFAULT NULL` |  |
| `role` | `enum('client'` |  |
| `status` | `enum('pending'` |  |
| `avatar` | `varchar(255) DEFAULT 'default.png'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |
| `fcm_token` | `text DEFAULT NULL` |  |

## Tabel: `user_admin`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `full_name` | `varchar(255) NOT NULL` |  |
| `email` | `varchar(100) NOT NULL` |  |
| `password` | `varchar(255) NOT NULL` |  |
| `role` | `varchar(100) NOT NULL` |  |
| `phone_number` | `varchar(20) DEFAULT NULL` |  |
| `photo` | `varchar(255) DEFAULT NULL` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |

## Tabel: `user_fcm_tokens`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(10) UNSIGNED NOT NULL` |  |
| `user_id` | `int(10) UNSIGNED NOT NULL` |  |
| `user_type` | `enum('client'` |  |
| `fcm_token` | `text NOT NULL` |  |
| `created_at` | `timestamp NULL DEFAULT current_timestamp()` |  |
| `updated_at` | `timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()` |  |
| `is_notification_enabled` | `tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = ON` |  |

## Tabel: `vouchers`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) UNSIGNED NOT NULL` |  |
| `code` | `varchar(50) NOT NULL` |  |
| `name` | `varchar(100) NOT NULL` |  |
| `description` | `text DEFAULT NULL` |  |
| `discount_nominal` | `decimal(15` |  |
| `image` | `varchar(255) DEFAULT NULL` |  |
| `valid_until` | `date DEFAULT NULL` |  |
| `is_active` | `tinyint(1) DEFAULT 1` |  |
| `created_at` | `datetime DEFAULT NULL` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

## Tabel: `withdrawal_requests`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `int(11) NOT NULL` |  |
| `tukang_id` | `int(11) NOT NULL` |  |
| `amount` | `decimal(15` |  |
| `status` | `enum('pending'` |  |
| `created_at` | `datetime DEFAULT current_timestamp()` |  |
| `updated_at` | `datetime DEFAULT NULL` |  |

