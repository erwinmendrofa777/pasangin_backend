<!-- LIST OF CONCEPTS -->
<div class="row">
    <?php foreach ($concepts as $concept) : ?>
        <div class="col-12">
            <div class="card concept-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: 800;">
                            <?= strtoupper(substr($concept['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h5 class="m-0 fw-800 text-dark"><?= esc($concept['name']) ?></h5>
                            <small class="text-muted"><?= count($concept['qualities']) ?> Tingkat Kualitas</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if (can('price-estimate_update')): ?>
                        <button class="btn btn-light btn-sm fw-bold px-3 btn-edit-concept" data-id="<?= $concept['id'] ?>" data-name="<?= $concept['name'] ?>" style="border-radius: 8px; color: #6777ef;">
                            <i class="fas fa-edit me-1"></i> Rename
                        </button>
                        <?php endif; ?>

                        <?php if (can('price-estimate_create')): ?>
                        <button class="btn btn-primary btn-sm fw-bold px-3 btn-add-quality" data-concept-id="<?= $concept['id'] ?>" data-concept-name="<?= $concept['name'] ?>" style="border-radius: 8px;">
                            <i class="fas fa-plus me-1"></i> Kualitas
                        </button>
                        <?php endif; ?>

                        <?php if (can('price-estimate_delete')): ?>
                        <a href="<?= site_url('admin/price-estimate/concept/delete/' . $concept['id']) ?>" class="btn btn-outline-danger btn-sm fw-bold px-3 ladda-button" data-style="zoom-in" onclick="return confirm('Hapus konsep ini beserta semua datanya?')">
                            <span class="ladda-label"><i class="fas fa-trash"></i></span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table quality-table">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Tier Kualitas</th>
                                    <th style="width: 300px;">Estimasi Harga (m²)</th>
                                    <th>Deskripsi / Spesifikasi</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($concept['qualities'])) : ?>
                                    <?php foreach ($concept['qualities'] as $quality) : ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark"><?= esc($quality['label']) ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="price-pill">Rp <?= number_format($quality['min_price'], 0, ',', '.') ?></span>
                                                    <span class="text-muted">~</span>
                                                    <span class="price-pill">Rp <?= number_format($quality['max_price'], 0, ',', '.') ?></span>
                                                </div>
                                            </td>
                                            <td class="text-muted small"><?= esc($quality['description'] ?? '-') ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <?php if (can('price-estimate_update')): ?>
                                                    <button class="btn-circle-action btn-edit-quality"
                                                        data-id="<?= $quality['id'] ?>"
                                                        data-label="<?= $quality['label'] ?>"
                                                        data-min-price="<?= $quality['min_price'] ?>"
                                                        data-max-price="<?= $quality['max_price'] ?>"
                                                        data-desc="<?= esc($quality['description'] ?? '') ?>">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <?php endif; ?>

                                                    <?php if (can('price-estimate_delete')): ?>
                                                    <a href="<?= site_url('admin/price-estimate/quality/delete/' . $quality['id']) ?>"
                                                        class="btn-circle-action btn-circle-delete text-danger ladda-button"
                                                        data-style="zoom-in"
                                                        onclick="return confirm('Hapus kualitas ini?')">
                                                        <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!can('price-estimate_update') && !can('price-estimate_delete')): ?>
                                                    <span class="badge badge-light"><i class="fas fa-lock"></i></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="mb-1 text-muted" style="opacity: 0.2;">
                                                <i class="fas fa-layer-group fa-4x"></i>
                                            </div>
                                            <p class="text-muted mt-1 mb-0 small">Belum ada tingkat kualitas untuk konsep ini.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
