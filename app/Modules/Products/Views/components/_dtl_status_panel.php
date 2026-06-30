<!-- ======================== RIGHT: UPDATE STATUS & REVIEWS ======================== -->
<div class="col-12 col-md-5 mt-0">
    <?php if (can('products_status')): ?>
        <div class="card shadow-sm mt-sm-4 action-card">

            <!-- Card Header -->
            <div class="card-header">
                <h6 class="text-white mb-0 fw-bold">
                    <i class="fas fa-shield-alt me-2"></i>Kelola Kelayakan Produk
                </h6>
            </div>

            <div class="card-body p-4 pt-3">
                <div class="d-grid gap-2">
                    <?php
                    $actions = [
                        'approved' => ['color' => 'success', 'icon' => 'fas fa-check-circle', 'label' => 'Setujui (Approve)', 'desc' => 'Produk disetujui tampil di App Client'],
                        'rejected' => ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'label' => 'Tolak (Reject)', 'desc' => 'Produk ditolak/disembunyikan dari App Client'],
                    ];
                    foreach ($actions as $key => $act):
                        $isActive = ($approval === $key);
                        ?>
                        <button type="button"
                            class="btn <?= $isActive ? 'btn-' . $act['color'] : 'btn-outline-' . $act['color'] ?> status-action-btn text-start"
                            <?= $isActive ? 'disabled' : '' ?> <?= !$isActive ? 'data-bs-toggle="modal" data-bs-target="#confirmStatusModal"' : '' ?> 
                            data-approval-status="<?= $key ?>"
                            data-status-label="<?= $act['label'] ?>" data-color="<?= $act['color'] ?>"
                            data-icon="<?= $act['icon'] ?>">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="<?= $act['icon'] ?>" style="width:16px; text-align:center;"></i>
                                    <div>
                                        <div style="font-size:0.88rem; font-weight:700; line-height:1.2;"><?= $act['label'] ?>
                                        </div>
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
                        Tombol berwarna solid menunjukkan status persetujuan yang sedang aktif saat ini.
                    </p>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- Rating Card -->
    <div class="card shadow-sm action-card mt-3">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 16px 16px 0 0;">
            <h6 class="mb-0 fw-bold" style="color: #495057;">
                <i class="fas fa-star text-warning me-2"></i> <span class="text-white">Ulasan Produk
                    (<?= count($ratings ?? []) ?>)</span>
            </h6>
        </div>
        <div class="card-body p-0 my-2" style="max-height: 294px; overflow-y: auto;">
            <?php if (empty($ratings)): ?>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-comment-slash fs-3 mb-2" style="opacity: 0.5;"></i>
                    <p class="mb-0" style="font-size:0.85rem;">Belum ada ulasan untuk produk ini.</p>
                </div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($ratings as $rating): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-warning" style="font-size:0.85rem;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= ($i <= (int) $rating['rating']) ? '' : 'text-light' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted" style="font-size:0.75rem; font-weight: 500;">
                                    <?= date('d M Y', strtotime($rating['created_at'])) ?>
                                </small>
                            </div>
                            <?php if (!empty($rating['comment'])): ?>
                                <p class="mb-2" style="font-size:0.88rem; line-height:1.5; color:#495057;">
                                    <?= esc($rating['comment']) ?>
                                </p>
                            <?php endif; ?>

                            <div class="d-flex gap-2 flex-wrap">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php $imgKey = 'gambar' . $i; ?>
                                    <?php if (!empty($rating[$imgKey])): ?>
                                        <?php
                                        $imgSrc = strpos($rating[$imgKey], 'http') === 0
                                            ? $rating[$imgKey]
                                            : base_url('uploads/products/rating/' . $rating[$imgKey]);
                                        ?>
                                        <a href="<?= $imgSrc ?>" target="_blank" class="d-inline-block">
                                            <img src="<?= $imgSrc ?>" alt="Review Image" class="rounded border shadow-sm"
                                                style="width:60px; height:60px; object-fit:cover; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.05)'"
                                                onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</div>