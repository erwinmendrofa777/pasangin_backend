<!-- HEADER SECTION -->
<div class="card page-header-card mb-2 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="text-primary mb-2 fw-bold">Manajemen Promo</h4>
                <p class="text-muted mb-0 small">Kelola berbagai penawaran diskon dan kode promo khusus dari supplier.
                </p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end gap-2 mt-3 mt-md-0">
                <div class="stat-pill shadow-sm">
                    <span>Total Promo</span>
                    <span class="stat-num"><?= number_format($stats['total']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Aktif</span>
                    <span class="stat-num"><?= number_format($stats['active']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Non-Aktif</span>
                    <span class="stat-num"><?= number_format($stats['inactive']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">

        <!-- TABLE CARD -->
        <div class="card shadow-sm table-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3 px-4">
                <div class="search-wrapper">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Ketik untuk mencari promo...">
                </div>
                <!-- Tombol tambah jika diperlukan di masa depan -->
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 100px;">Gambar</th>
                                <th>Info Promo</th>
                                <th>Supplier</th>
                                <th class="text-center">Potongan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promos as $key => $row): ?>
                                <tr>
                                    <td class="text-center text-muted fw-bold"><?= $key + 1 ?></td>
                                    <td>
                                        <?php
                                        $photoUrl = !empty($row['photo'])
                                            ? (strpos($row['photo'], 'http') === 0 ? $row['photo'] : base_url('uploads/promos/' . $row['photo']))
                                            : base_url('assets/img/news/img01.jpg'); // Placeholder
                                        ?>
                                        <a href="<?= $photoUrl ?>" class="glightbox" data-gallery="promo-gallery" data-title="<?= esc($row['title']) ?>" data-description="Supplier: <?= esc($row['supplier_name']) ?> | Kode: <?= esc($row['promo_code']) ?>">
                                            <img src="<?= $photoUrl ?>" class="promo-thumb" alt="promo">
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($row['title']) ?></div>
                                        <div class="small text-primary fw-bold mt-1">
                                            <i class="fas fa-ticket-alt me-1"></i><?= esc($row['promo_code']) ?>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i
                                                class="far fa-calendar-alt me-1"></i><?= date('d M', strtotime($row['start_date'])) ?>
                                            - <?= date('d M Y', strtotime($row['end_date'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light p-2 rounded-circle text-primary"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                            <span class="fw-600"><?= esc($row['supplier_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="discount-pill">
                                            <?php if ($row['discount_type'] == "fixed"): ?>
                                                -Rp<?= number_format($row['discount_value'], 0, ',', '.') ?>
                                            <?php else: ?>
                                                -<?= number_format($row['discount_value'], 0, ',', '.') ?>%
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge-status <?= $row['status'] == 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                            <?= $row['status'] == 'active' ? 'Aktif' : 'Non-Aktif' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (can('promo')): ?>
                                            <a href="<?= base_url('admin/promo/detail/' . $row['id']) ?>"
                                                class="btn btn-outline-primary btn-sm"
                                                style="border-radius: 8px; padding: 6px 12px;" data-toggle="tooltip"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        <?php else: ?>
                                            <span class="badge badge-light"><i class="fas fa-lock"></i> No Access</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
