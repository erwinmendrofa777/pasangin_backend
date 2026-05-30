<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Pesanan - <?= esc($order['order_id']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Pesanan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .profile-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 24px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== AVATAR / ICON ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -55px;
    }

    .avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #0d6efd;
    }

    /* ===== LEFT CARD ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
    }

    /* ===== RIGHT CARD ===== */
    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        height: 100%;
    }

    .action-card .card-header {
        background: #6777EF !important;
        border-radius: 16px 16px 0 0;
        padding: 18px 22px;
        border: none;
    }

    /* ===== STATUS PILL ===== */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-pill .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.75;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #e2e3e5;
        color: #41464b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        background: #e7f0ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .info-label {
        font-size: 0.72rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.93rem;
        color: #212529;
        font-weight: 500;
        word-break: break-word;
    }

    /* ===== STATUS ACTION BUTTONS ===== */
    .status-action-btn {
        border-radius: 10px;
        font-size: 0.83rem;
        font-weight: 600;
        padding: 10px 12px;
        transition: all 0.18s ease;
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .status-action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    .status-action-btn:disabled {
        opacity: 0.85;
        cursor: not-allowed;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #0d6efd;
        margin-bottom: 10px;
    }

    @media (max-width: 767px) {
        .profile-hero {
            padding: 28px 18px 60px;
        }

        .profile-body {
            padding: 0 16px 22px;
        }
    }

    /* ===== ORDER ITEM IMAGE ===== */
    .order-item-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .order-item-img:hover {
        transform: scale(1.12);
        border-color: #0d6efd;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
/* ===== STATUS META ===== */
$status = $order['status'] ?? 'PENDING';

$statusMeta = [
    'PAID'       => ['class' => 'status-paid',       'icon' => 'fas fa-check-circle',  'label' => 'PAID'],
    'SETTLEMENT' => ['class' => 'status-paid',       'icon' => 'fas fa-check-circle',  'label' => 'SETTLEMENT'],
    'SHIPPED'    => ['class' => 'status-paid',       'icon' => 'fas fa-truck',         'label' => 'SHIPPED'],
    'COMPLETED'  => ['class' => 'status-paid',       'icon' => 'fas fa-check-double',  'label' => 'COMPLETED'],
    'PENDING'    => ['class' => 'status-pending',    'icon' => 'fas fa-clock',         'label' => 'PENDING'],
    'UNPAID'     => ['class' => 'status-pending',    'icon' => 'fas fa-exclamation-circle', 'label' => 'UNPAID'],
    'CANCELLED'  => ['class' => 'status-cancelled',  'icon' => 'fas fa-times-circle',  'label' => 'CANCELLED'],
    'EXPIRED'    => ['class' => 'status-cancelled',  'icon' => 'fas fa-times-circle',  'label' => 'EXPIRED'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => $status];
?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- ===== 2-COLUMN LAYOUT ===== -->
<div class="row g-4 align-items-start">

    <!-- ======================== LEFT: ORDER INFO ======================== -->
    <div class="col-12 col-md-7">
        <div class="card shadow-sm profile-card">

            <!-- Hero Banner -->
            <div class="profile-hero bg-primary">
                <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
                    <h5 class="text-white mb-0 ms-1 fw-bold" style="font-size:1.2rem;">
                        Pesanan <?= esc($order['order_id']) ?>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill <?= $currentMeta['class'] ?>">
                            <span class="dot"></span><?= $currentMeta['label'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Body -->
            <div class="profile-body">

                <!-- Info List: Pengiriman -->
                <p class="section-title"><i class="fas fa-map-marker-alt mt-3 me-1"></i>Info Pengiriman</p>
                <div class="info-list mb-4">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div class="info-label">ID Pelanggan (User ID)</div>
                            <div class="info-value"><?= esc($order['user_id'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="info-label">Nama Penerima</div>
                            <div class="info-value"><?= esc($order['recipient_name'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value"><?= esc($order['recipient_phone'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="info-label">Tanggal Pesanan Dibuat</div>
                            <div class="info-value"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-home"></i></div>
                        <div>
                            <div class="info-label">Alamat Pengiriman</div>
                            <div class="info-value"><?= esc($order['shipping_address'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>

                <!-- ===== GOOGLE MAPS EMBED ===== -->
                <div class="mt-4 mb-2">
                    <p class="section-title mb-2"><i class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis Pengiriman</p>
                    <?php if (!empty($order['latitude']) && !empty($order['longitude'])): ?>
                        <div class="map-container shadow-sm p-1 bg-white" style="border-radius: 14px; border: 1px solid #e9ecef;">
                            <iframe
                                src="https://maps.google.com/maps?q=<?= esc($order['latitude']) ?>,<?= esc($order['longitude']) ?>&hl=id&z=15&output=embed"
                                width="100%"
                                height="220"
                                style="border:0; border-radius:10px;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="text-end mt-2">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($order['latitude']) ?>,<?= esc($order['longitude']) ?>" target="_blank" class="text-primary text-decoration-none" style="font-size:0.82rem; font-weight:600;">
                                <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4 bg-light" style="border-radius: 12px; border: 1px dashed #ced4da;">
                            <i class="fas fa-map-marked-alt text-muted mb-2" style="font-size:2rem; opacity:0.5;"></i>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:500;">Koordinat peta belum disetel.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info List: Pembayaran -->
                <p class="section-title"><i class="fas fa-money-bill-wave me-1"></i>Rincian Pembayaran</p>
                <div class="info-list mb-4">
                    <?php
                    $totalProductPrice = 0;
                    if (!empty($items)) {
                        foreach ($items as $itm) {
                            $totalProductPrice += ($itm['price'] * $itm['quantity']);
                        }
                    }

                    // Kalkulasi Total Pembayaran secara transparan
                    $shippingFee = $order['shipping_fee'] ?? 0;
                    $appFee = $order['app_fee'] ?? 0;
                    $taxAmount = $order['tax_amount'] ?? 0;
                    $discountAmount = $order['discount_amount'] ?? 0;

                    $calculatedTotal = $totalProductPrice + $shippingFee + $appFee + $taxAmount - $discountAmount;
                    ?>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-box"></i></div>
                        <div>
                            <div class="info-label">Total Harga Produk</div>
                            <div class="info-value">Rp <?= number_format($totalProductPrice, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-truck"></i></div>
                        <div>
                            <div class="info-label">Ongkos Kirim</div>
                            <div class="info-value">Rp <?= number_format($shippingFee, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <?php if ($appFee > 0): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-server"></i></div>
                            <div>
                                <div class="info-label">Biaya Aplikasi</div>
                                <div class="info-value">Rp <?= number_format($appFee, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($taxAmount > 0): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-percent"></i></div>
                            <div>
                                <div class="info-label">Pajak</div>
                                <div class="info-value">Rp <?= number_format($taxAmount, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($discountAmount > 0): ?>
                        <div class="info-item">
                            <div class="info-icon text-success" style="background:#e6f9ed;"><i class="fas fa-tags"></i></div>
                            <div>
                                <div class="info-label text-success">Diskon <?= !empty($order['voucher_code']) ? '(' . esc($order['voucher_code']) . ')' : '' ?></div>
                                <div class="info-value text-success">- Rp <?= number_format($discountAmount, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="info-item" style="background:#f8f9fa; border-radius:8px; padding:12px;">
                        <div class="info-icon bg-primary text-white"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="info-label text-primary">Total Pembayaran</div>
                            <div class="info-value fw-bold text-primary" style="font-size:1.1rem;">Rp <?= number_format($calculatedTotal, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Info List: Item Pesanan -->
                <p class="section-title"><i class="fas fa-box-open me-1"></i>Item Pesanan</p>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: 1px solid #f0f2f5; border-radius: 8px; overflow:hidden;">
                        <thead class="bg-light text-center" style="font-size:0.8rem;">
                            <tr>
                                <th class="border-0">Produk</th>
                                <th class="border-0">Harga</th>
                                <th class="border-0">Jumlah</th>
                                <th class="border-0">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr class="align-middle">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($item['product_photo'])): ?>
                                                    <?php $photoSrc = strpos($item['product_photo'], 'http') === 0 ? $item['product_photo'] : base_url('uploads/products/' . $item['product_photo']); ?>
                                                    <a href="<?= $photoSrc ?>" class="glightbox" 
                                                       data-gallery="order-item-<?= $item['id'] ?? uniqid() ?>" 
                                                       data-title="<?= esc($item['product_name'] ?? 'Detail Produk') ?>" 
                                                       data-description="Produk: <?= esc($item['product_name'] ?? '-') ?> &lt;br&gt; Harga: Rp <?= number_format($item['price'], 0, ',', '.') ?> &lt;br&gt; Jumlah: <?= esc($item['quantity']) ?> pcs &lt;br&gt; Subtotal: Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>">
                                                        <img src="<?= $photoSrc ?>" alt="<?= esc($item['product_name']) ?>" class="order-item-img" data-toggle="tooltip" title="Klik untuk memperbesar">
                                                    </a>
                                                <?php else: ?>
                                                    <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-box text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="text-start">
                                                    <h6 class="mb-0 fw-bold" style="font-size:0.85rem;"><?= esc($item['product_name'] ?? 'Dihapus') ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="font-size:0.85rem;">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td class="text-center" style="font-size:0.85rem;">x<?= $item['quantity'] ?></td>
                                        <td class="text-center fw-bold text-primary" style="font-size:0.85rem;">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Item pesanan tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- /LEFT -->

    <!-- ======================== RIGHT: UPDATE STATUS ======================== -->
    <div class="col-12 col-md-5 mt-0 mt-sm-4">
        <?php if (can('orders_status')): ?>
            <div class="card shadow-sm mb-3 action-card">

                <!-- Card Header -->
                <div class="card-header">
                    <h6 class="text-white mb-0 fw-bold">
                        <i class="fas fa-sliders-h me-2"></i>Kelola Status Pesanan
                    </h6>
                </div>

                <div class="card-body p-4 pt-3">
                    <div class="d-grid gap-2">
                        <?php
                        $actions = [
                            'PENDING'    => ['color' => 'warning', 'icon' => 'fas fa-clock',         'label' => 'PENDING',    'desc' => 'Menunggu proses sistem'],
                            'UNPAID'     => ['color' => 'warning', 'icon' => 'fas fa-exclamation-circle', 'label' => 'UNPAID', 'desc' => 'Menunggu pembayaran pembeli'],
                            'PAID'       => ['color' => 'success', 'icon' => 'fas fa-check-circle',  'label' => 'PAID',       'desc' => 'Pembayaran diterima'],
                            'SETTLEMENT' => ['color' => 'success', 'icon' => 'fas fa-check-circle',  'label' => 'SETTLEMENT', 'desc' => 'Pembayaran selesai'],
                            'SHIPPED'    => ['color' => 'success', 'icon' => 'fas fa-truck',         'label' => 'SHIPPED',    'desc' => 'Pesanan sedang dikirim'],
                            'COMPLETED'  => ['color' => 'success', 'icon' => 'fas fa-check-double',  'label' => 'COMPLETED',  'desc' => 'Pesanan selesai'],
                            'CANCELLED'  => ['color' => 'danger',  'icon' => 'fas fa-times-circle',  'label' => 'CANCELLED',  'desc' => 'Pesanan dibatalkan'],
                        ];

                        foreach ($actions as $key => $act):
                            $isActive = ($status === $key);
                        ?>
                            <button type="button"
                                class="btn <?= $isActive ? 'btn-' . $act['color'] : 'btn-outline-' . $act['color'] ?> status-action-btn text-start"
                                <?= $isActive ? 'disabled' : '' ?>
                                <?= !$isActive ? 'data-bs-toggle="modal" data-bs-target="#confirmStatusModal"' : '' ?>
                                data-status="<?= $key ?>"
                                data-status-label="<?= $act['label'] ?>"
                                data-color="<?= $act['color'] ?>"
                                data-icon="<?= $act['icon'] ?>">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="<?= $act['icon'] ?>" style="width:16px; text-align:center;"></i>
                                        <div>
                                            <div style="font-size:0.88rem; font-weight:700; line-height:1.2;"><?= $act['label'] ?></div>
                                            <div style="font-size:0.72rem; font-weight:400; opacity:0.75;"><?= $act['desc'] ?></div>
                                        </div>
                                    </div>
                                    <?php if ($isActive): ?>
                                        <i class="fas fa-check-circle ms-2" style="font-size:1rem;"></i>
                                    <?php else: ?>
                                        <i class="fas fa-chevron-right ms-2" style="font-size:0.75rem; opacity:0.6;"></i>
                                    <?php endif; ?>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Note -->
                    <div class="mt-3 pt-3 border-top">
                        <p class="text-muted mb-0" style="font-size:0.78rem;">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Tombol berwarna solid menunjukkan status yang sedang aktif dan tidak dapat dipilih kembali.
                        </p>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- /RIGHT -->

</div>
<!-- /2-COLUMN LAYOUT -->


<!-- ===== CONFIRMATION MODAL ===== -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1"
    aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="confirmStatusModalLabel">
                    <i class="fas fa-shield-alt text-primary me-2"></i>Konfirmasi Perubahan Status
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div id="modalIconWrap" class="mb-3 mx-auto"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.9rem;">
                </div>
                <p class="mb-1 fw-semibold" style="font-size:1rem;">Ubah status menjadi</p>
                <h5 id="modalStatusLabel" class="fw-bold mb-3"></h5>
                <p class="text-muted" style="font-size:0.85rem;">
                    Status pesanan <strong><?= esc($order['order_id']) ?></strong> akan segera diperbarui.
                    Pastikan keputusan ini sudah benar.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="updateStatusForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" id="modalConfirmBtn" class="btn px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Ya, Ubah Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil!',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 6000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    /* ===== Confirm Status Modal ===== */
    document.addEventListener('DOMContentLoaded', function() {

        var confirmModal = document.getElementById('confirmStatusModal');
        confirmModal.addEventListener('show.bs.modal', function(event) {
            var btn = event.relatedTarget;
            var newStatus = btn.getAttribute('data-status');
            var statusLabel = btn.getAttribute('data-status-label');
            var color = btn.getAttribute('data-color');
            var icon = btn.getAttribute('data-icon');

            // Set form action
            document.getElementById('updateStatusForm').action =
                '<?= base_url('admin/orders/update/' . $order['id']) ?>';

            // Append input hidden for status
            let existingInput = document.getElementById('hiddenStatusInput');
            if (!existingInput) {
                existingInput = document.createElement('input');
                existingInput.type = 'hidden';
                existingInput.name = 'status';
                existingInput.id = 'hiddenStatusInput';
                document.getElementById('updateStatusForm').appendChild(existingInput);
            }
            existingInput.value = newStatus;

            // Color mapping
            var colorMap = {
                success: {
                    bg: '#d1e7dd',
                    color: '#0a5c36'
                },
                warning: {
                    bg: '#fff3cd',
                    color: '#7d5a00'
                },
                danger: {
                    bg: '#f8d7da',
                    color: '#842029'
                },
                dark: {
                    bg: '#dee2e6',
                    color: '#212529'
                },
            };
            var c = colorMap[color] || {
                bg: '#e7f0ff',
                color: '#0d6efd'
            };

            var iconWrap = document.getElementById('modalIconWrap');
            iconWrap.style.background = c.bg;
            iconWrap.style.color = c.color;
            iconWrap.innerHTML = '<i class="' + icon + '"></i>';

            var label = document.getElementById('modalStatusLabel');
            label.textContent = statusLabel;
            label.style.color = c.color;

            document.getElementById('modalConfirmBtn').className = 'btn btn-' + color + ' px-4 fw-semibold';
        });

        /* Loading spinner on submit */
        document.getElementById('updateStatusForm').addEventListener('submit', function() {
            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
        });
    });
</script>
<?= $this->endSection() ?>