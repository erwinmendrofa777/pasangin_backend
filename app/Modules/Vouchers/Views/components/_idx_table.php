<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary"
            style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-ticket-alt me-2"></i>Daftar Voucher
        </h6>
        <div class="d-flex gap-3">
            <div class="search-wrapper" style="width: 280px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari kode atau nama voucher...">
            </div>
            <?php if (can('vouchers_create')): ?>
                <a href="<?= base_url('admin/vouchers/create') ?>"
                    class="btn btn-primary d-flex align-items-center gap-2 px-3"
                    style="border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Tambah Voucher
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Visual</th>
                        <th class="text-center">Kode</th>
                        <th class="text-start">Nama Voucher</th>
                        <th class="text-center">Potongan</th>
                        <th class="text-center">Berlaku Hingga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('uploads/vouchers/' . $row['image']) ?>" class="glightbox" data-gallery="voucher-gallery" data-title="<?= esc($row['name']) ?>" data-description="Kode: <?= esc($row['code']) ?> | Potongan: Rp <?= number_format($row['discount_nominal'], 0, ',', '.') ?>">
                                    <img src="<?= base_url('uploads/vouchers/' . $row['image']) ?>" class="voucher-img"
                                        data-toggle="tooltip" title="<?= esc($row['name']) ?>">
                                </a>
                            </td>
                            <td><span class="badge bg-light text-primary fw-bold px-3 py-2"
                                    style="border: 1px dashed var(--palette-primary); border-radius: 8px;"><?= esc($row['code']) ?></span>
                            </td>
                            <td class="text-start fw-semibold"><?= esc($row['name'] ?: '-') ?></td>
                            <td><span class="fw-bold text-success">Rp
                                    <?= number_format($row['discount_nominal'], 0, ',', '.') ?></span></td>
                            <td>
                                <div class="text-muted" style="font-size: 0.82rem;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?= date('d M Y', strtotime($row['valid_until'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $isExpired = strtotime($row['valid_until']) < time();
                                if ($row['is_active'] == 0): ?>
                                    <span class="status-badge status-inactive"><i class="fas fa-times-circle"></i>
                                        Nonaktif</span>
                                <?php elseif ($isExpired): ?>
                                    <span class="status-badge status-expired"><i class="fas fa-exclamation-circle"></i>
                                        Expired</span>
                                <?php else: ?>
                                    <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('vouchers')): ?>
                                        <a href="<?= base_url('admin/vouchers/detail/' . $row['id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-light"><i class="fas fa-lock"></i> No Access</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
