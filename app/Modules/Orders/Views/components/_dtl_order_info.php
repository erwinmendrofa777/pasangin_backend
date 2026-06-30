<?php
/* ===== STATUS META ===== */
$status = $order['status'] ?? 'UNPAID';
$statusMeta = [
    'UNPAID'     => ['class' => 'status-pending',   'icon' => 'fas fa-exclamation-circle','label' => 'UNPAID'],
    'PAID'       => ['class' => 'status-paid',      'icon' => 'fas fa-check-circle',      'label' => 'PAID'],
    'PROCESSED'  => ['class' => 'status-paid',      'icon' => 'fas fa-box',               'label' => 'PROCESSED'],
    'LOADING'    => ['class' => 'status-paid',      'icon' => 'fas fa-dolly-flatbed',     'label' => 'LOADING'],
    'SHIPPED'    => ['class' => 'status-paid',      'icon' => 'fas fa-truck',             'label' => 'SHIPPED'],
    'ARRIVED'    => ['class' => 'status-paid',      'icon' => 'fas fa-clipboard-check',   'label' => 'ARRIVED'],
    'COMPLETED'  => ['class' => 'status-paid',      'icon' => 'fas fa-check-double',      'label' => 'COMPLETED'],
    'CANCELLED'  => ['class' => 'status-cancelled', 'icon' => 'fas fa-times-circle',      'label' => 'CANCELLED'],
    'EXPIRED'    => ['class' => 'status-cancelled', 'icon' => 'fas fa-times-circle',      'label' => 'EXPIRED'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => $status];
?>

<!-- ===== LEFT: ORDER INFO CARD ===== -->
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

            <!-- Info: Pengiriman -->
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

            <!-- Google Maps Embed -->
            <div class="mt-4 mb-2">
                <p class="section-title mb-2"><i class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis Pengiriman</p>
                <?php if (!empty($order['latitude']) && !empty($order['longitude'])): ?>
                    <div class="map-container shadow-sm p-1 bg-white" style="border-radius: 14px; border: 1px solid #e9ecef;">
                        <iframe
                            src="https://maps.google.com/maps?q=<?= esc($order['latitude']) ?>,<?= esc($order['longitude']) ?>&hl=id&z=15&output=embed"
                            width="100%" height="220"
                            style="border:0; border-radius:10px;"
                            allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="text-end mt-2">
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($order['latitude']) ?>,<?= esc($order['longitude']) ?>"
                            target="_blank" class="text-primary text-decoration-none"
                            style="font-size:0.82rem; font-weight:600;">
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

            <!-- Info: Pembayaran -->
            <p class="section-title"><i class="fas fa-money-bill-wave me-1"></i>Rincian Pembayaran</p>
            <div class="info-list mb-4">
                <?php
                $totalProductPrice = 0;
                if (!empty($items)) {
                    foreach ($items as $itm) {
                        $totalProductPrice += ($itm['price'] * $itm['quantity']);
                    }
                }
                $shippingFee     = $order['shipping_fee']    ?? 0;
                $appFee          = $order['app_fee']         ?? 0;
                $taxAmount       = $order['tax_amount']      ?? 0;
                $discountAmount  = $order['discount_amount'] ?? 0;
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

            <!-- Item Pesanan -->
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
                                                    <img src="<?= $photoSrc ?>" alt="<?= esc($item['product_name']) ?>"
                                                        class="order-item-img" data-toggle="tooltip" title="Klik untuk memperbesar">
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
