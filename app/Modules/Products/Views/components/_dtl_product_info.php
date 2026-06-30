<!-- ======================== LEFT: PROFILE INFO ======================== -->
<div class="col-12 col-md-7">
    <div class="card shadow-sm profile-card">

        <!-- Hero Banner -->
        <div class="profile-hero bg-primary">
            <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
                <h5 class="text-white mb-0 ms-3 fw-bold" style="font-size:1.2rem;">
                    <?= esc($product['name'] ?? '-') ?>
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

            <!-- Avatar + ID -->
            <div class="d-flex align-items-end justify-content-between mb-3">
                <div class="avatar-wrapper">
                    <?php if ($photoSrc): ?>
                        <a href="<?= $photoSrc ?>" class="glightbox" data-title="<?= esc($product['name']) ?>" data-description="Nama Produk: <?= esc($product['name']) ?> &lt;br&gt; Supplier: <?= esc($product['supplier_name'] ?: '-') ?> &lt;br&gt; Harga: Rp <?= number_format($product['price'] ?? 0, 0, ',', '.') ?> &lt;br&gt; Stok: <?= esc($product['stock'] ?: '0') ?>">
                            <img src="<?= $photoSrc ?>" alt="<?= esc($product['name']) ?>"
                                class="avatar-img" id="img-preview" data-toggle="tooltip" title="Klik untuk memperbesar">
                        </a>
                    <?php else: ?>
                        <div class="avatar-initials"><?= $initials ?></div>
                    <?php endif; ?>
                </div>
                <span class="text-muted" style="font-size:0.77rem; padding-bottom:4px;">
                    Dibuat Pada: <strong><?= esc($product['created_at']) ?></strong>
                </span>
            </div>

            <hr class="my-3">

            <!-- Info List -->
            <p class="section-title"><i class="fas fa-box-open me-1"></i>Informasi Produk</p>

            <div class="info-list">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="info-label">Kategori Aplikasi (Global)</div>
                        <div class="info-value">
                            <?php if (!empty($product['app_category_name'])): ?>
                                <span class="badge badge-primary px-3 py-2" style="border-radius: 6px; font-size: 0.8rem;"><?= esc($product['app_category_name']) ?></span>
                            <?php else: ?>
                                <span class="text-danger fw-bold" style="font-size: 0.82rem;"><i class="fas fa-exclamation-circle me-1"></i>Belum Ditentukan (Butuh Kualifikasi)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-store"></i></div>
                    <div>
                        <div class="info-label">Kategori Toko Supplier</div>
                        <div class="info-value"><?= esc($product['category_name'] ?? '-') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-tag"></i></div>
                    <div>
                        <div class="info-label">Nama Produk</div>
                        <div class="info-value"><?= esc($product['name'] ?? '-') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-align-left"></i></div>
                    <div>
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value"><?= esc($product['description'] ?? '-') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="info-label">Harga</div>
                        <div class="info-value">Rp <?= number_format($product['price'] ?? 0, 0, ',', '.') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-boxes"></i></div>
                    <div>
                        <div class="info-label">Stok</div>
                        <div class="info-value"><?= esc($product['stock'] ?? '0') ?> <?= esc($product['unit'] ?? '') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div>
                        <div class="info-label">Minimal Order</div>
                        <div class="info-value"><?= esc($product['min_order'] ?? '1') ?> <?= esc($product['unit'] ?? '') ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-weight-hanging"></i></div>
                    <div>
                        <div class="info-label">Berat</div>
                        <div class="info-value"><?= esc($product['weight'] ?? '0') ?> Gram</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-star text-warning"></i></div>
                    <div>
                        <div class="info-label">Rata-Rata Rating</div>
                        <div class="info-value"><?= esc($product['rata_rata_rating'] ?? '0') ?> / 5.0</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-comments"></i></div>
                    <div>
                        <div class="info-label">Total Ulasan</div>
                        <div class="info-value"><?= esc($product['total_ulasan'] ?? '0') ?> Ulasan</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
