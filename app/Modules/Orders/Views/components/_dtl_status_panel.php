<!-- ===== RIGHT: UPDATE STATUS CARD ===== -->
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
                        'UNPAID'     => ['color' => 'warning', 'icon' => 'fas fa-exclamation-circle','label' => 'UNPAID',     'desc' => 'Menunggu pembayaran pembeli'],
                        'PAID'       => ['color' => 'success', 'icon' => 'fas fa-check-circle',      'label' => 'PAID',       'desc' => 'Pembayaran diterima'],
                        'PROCESSED'  => ['color' => 'info',    'icon' => 'fas fa-box',               'label' => 'PROCESSED',  'desc' => 'Pesanan sedang dipersiapkan'],
                        'LOADING'    => ['color' => 'info',    'icon' => 'fas fa-dolly-flatbed',     'label' => 'LOADING',    'desc' => 'Pesanan sedang dimuat'],
                        'SHIPPED'    => ['color' => 'success', 'icon' => 'fas fa-truck',             'label' => 'SHIPPED',    'desc' => 'Pesanan sedang dikirim'],
                        'ARRIVED'    => ['color' => 'success', 'icon' => 'fas fa-clipboard-check',   'label' => 'ARRIVED',    'desc' => 'Pesanan sampai di lokasi'],
                        'COMPLETED'  => ['color' => 'success', 'icon' => 'fas fa-check-double',      'label' => 'COMPLETED',  'desc' => 'Pesanan selesai'],
                        'CANCELLED'  => ['color' => 'danger',  'icon' => 'fas fa-times-circle',      'label' => 'CANCELLED',  'desc' => 'Pesanan dibatalkan'],
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
