<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Supplier Saya
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
    }

    /* ===== PREMIUM CUSTOM SEARCH ===== */
    .search-wrapper {
        position: relative;
        display: inline-block;
    }

    .search-input {
        display: block !important;
        width: 100% !important;
        height: 40px !important;
        border-radius: 10px !important;
        font-size: 0.82rem !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #334155 !important;
        font-weight: 600 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01) !important;
        outline: none !important;
    }

    .search-input:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }

    .search-input:focus {
        border-color: var(--palette-primary) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.12), 0 6px 16px rgba(255, 92, 92, 0.06) !important;
        transform: translateY(-1px);
        color: #0f172a !important;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 5;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .search-input:focus~.search-icon,
    .search-input:hover~.search-icon {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.15) rotate(15deg) !important;
    }

    .search-input::placeholder {
        color: #94a3b8;
        opacity: 0.8;
    }

    /* ===== PRIMARY BUTTON SHADOW OVERRIDE ===== */
    .btn-primary {
        background-color: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25) !important;
        transition: all 0.2s ease !important;
    }

    .btn-primary:hover {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.4) !important;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        overflow: hidden !important;
        background: #fff !important;
    }

    .table-card .card-body {
        padding: 0 !important;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
    }

    #table-1 thead tr {
        background: var(--palette-primary) !important;
    }

    #table-1 thead th {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: none !important;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 thead th:first-child {
        border-top-left-radius: 16px !important;
    }

    #table-1 thead th:last-child {
        border-top-right-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:first-child {
        border-bottom-left-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:last-child {
        border-bottom-right-radius: 16px !important;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #fffafa !important;
    }

    #table-1 tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== AVATAR ===== */
    .product-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
    }

    .product-avatar:hover {
        transform: scale(1.1);
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
    }

    /* ===== BADGES & STATUSES ===== */
    .status-badge {
        border-radius: 30px !important;
        padding: 6px 14px !important;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        border: 1px solid transparent;
    }

    .status-berkas {
        background: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fde68a !important;
    }

    .status-ditolak {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fee2e2 !important;
    }

    .status-test {
        background: #f0f9ff !important;
        color: #0284c7 !important;
        border: 1px solid #bae6fd !important;
    }

    .status-aktivasi {
        background: #f5f3ff !important;
        color: #7c3aed !important;
        border: 1px solid #ddd6fe !important;
    }

    .status-siap {
        background: #f0fdf4 !important;
        color: #16a34a !important;
        border: 1px solid #bbf7d0 !important;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action-detail {
        background: var(--palette-primary);
        color: #fff;
    }

    .btn-action-detail:hover {
        background: var(--palette-primary-hover);
        color: #fff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #fff;
    }

    .btn-action-delete:hover {
        background: #bb2d3b;
        color: #fff;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px;
        border-top: 1px solid #f0f4fa;
        background: #fafcff;
    }

    .dataTables_info {
        font-size: 0.82rem;
        color: #6c757d !important;
    }

    /* ===== GROUP TREE TABLE & PAGINATION ===== */
    .group-parent-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .group-parent-row:hover {
        background-color: #fff9f9 !important;
    }

    .group-parent-row.expanded {
        background-color: #fff5f5 !important;
    }

    .group-parent-row.expanded .transition-icon {
        transform: rotate(90deg) !important;
        color: var(--palette-primary) !important;
    }

    .group-detail-row {
        background-color: #f8fafc !important;
    }

    .group-detail-row table {
        background-color: #ffffff !important;
    }

    .group-detail-row table th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 2px;
        padding: 5px 11px;
        font-weight: 600;
        color: #475569;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.2);
    }

    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #fff5f5 !important;
        border-color: #ffcccc !important;
        color: var(--palette-primary) !important;
    }

    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    @media (max-width: 576px) {
        .w-100-mobile {
            width: 100% !important;
        }
        .btn-w-100-mobile {
            width: 100% !important;
            margin-top: 8px;
        }
        /* Style for 2-column mobile statistics grid */
        .header-card .row.g-3 .col-6 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .header-card .row.g-3 .col-6 .d-flex {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }
        .header-card .row.g-3 .col-6 .rounded-circle {
            width: 34px !important;
            height: 34px !important;
            margin-right: 6px !important;
        }
        .header-card .row.g-3 .col-6 .rounded-circle i {
            font-size: 0.95rem !important;
        }
        .header-card .row.g-3 .col-6 h4 {
            font-size: 1.1rem !important;
        }
        .header-card .row.g-3 .col-6 span {
            font-size: 0.62rem !important;
        }
    }

    /* ===== MOBILE VIEW CUSTOM STYLES ===== */
    @media (max-width: 768px) {
        .group-parent-card-mobile {
            background-color: #fff !important;
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.2s ease !important;
        }
        .group-parent-card-mobile.expanded {
            background-color: #fff9f9 !important;
            border-color: #ffcccc !important;
            box-shadow: 0 6px 20px rgba(255, 92, 92, 0.05) !important;
        }
        .group-parent-card-mobile.expanded .transition-icon {
            transform: rotate(90deg);
            color: var(--palette-primary);
        }
        .group-parent-card-mobile .transition-icon-wrapper {
            transition: all 0.2s ease;
        }
        .bg-success-light {
            background: #f0fdf4 !important;
            color: #16a34a !important;
            border: 1px solid #bbf7d0 !important;
        }
        .bg-danger-light {
            background: #fef2f2 !important;
            color: #dc2626 !important;
            border: 1px solid #fee2e2 !important;
        }
        .bg-warning-light {
            background: #fffbeb !important;
            color: #d97706 !important;
            border: 1px solid #fde68a !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- ===== HEADER & STATS CARD ===== -->
<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-store" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Kelola Supplier & Produk</h5>
                        <p class="text-muted mb-0 small">Daftar, cari, dan kelola produk supplier yang Anda hubungkan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap w-100">
                    <!-- Search Input -->
                    <div class="search-wrapper w-100-mobile" style="width: 240px; max-width: 100%;">
                        <input type="text" class="search-input w-100" id="searchInput"
                            placeholder="Cari supplier atau produk..." style="padding-left: 38px !important;">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <!-- Claim Supplier Button -->
                    <button type="button" class="btn btn-primary btn-w-100-mobile d-flex align-items-center justify-content-center"
                        data-bs-toggle="modal" data-bs-target="#claimSupplierModal"
                        style="border-radius: 10px; font-size: 0.82rem; height: 40px; padding: 0 16px; white-space: nowrap; font-weight: 600;">
                        <i class="fas fa-plus me-1"></i> Hubungkan Supplier
                    </button>
                </div>
            </div>
        </div>

        <!-- Row Ringkasan Statistik -->
        <div class="row g-3 align-items-center mt-2">
            <!-- Col 1: Total Supplier -->
            <div class="col-6 col-lg-3">
                <div class="d-flex align-items-center py-2 px-1">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.08); color: #3b82f6; flex-shrink: 0;">
                        <i class="fas fa-store" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Total
                            Supplier</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= count($suppliers) ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 2: Total Produk -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.08); color: #f59e0b; flex-shrink: 0;">
                        <i class="fas fa-boxes" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Total
                            Produk</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $totalProducts ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 3: Produk Aktif -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.08); color: #10b981; flex-shrink: 0;">
                        <i class="fas fa-check-double" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Produk
                            Aktif</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $activeProducts ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 4: Menunggu Persetujuan -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(107, 114, 128, 0.08); color: #6b7280; flex-shrink: 0;">
                        <i class="fas fa-clock" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Menunggu
                            Review</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $pendingProducts ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MAIN TREE TABLE ===== -->
<!-- ===== DESKTOP VIEW (TABLE) ===== -->
<div class="d-none d-md-block">
    <div class="card table-card mb-4 border-0 shadow-sm rounded-4 overflow-hidden"
        style="border: 1px solid rgba(226, 232, 240, 0.8) !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="table-1"
                    style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr class="bg-primary text-white text-center">
                            <th class="ps-4 py-3"
                                style="width: 5%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-top-left-radius: 12px; border-bottom: none;">
                                NO</th>
                            <th class="text-start py-3"
                                style="width: 45%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                                NAMA SUPPLIER</th>
                            <th class="text-start py-3"
                                style="width: 20%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                                TELEPON & EMAIL</th>
                            <th class="py-3"
                                style="width: 15%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                                JUMLAH PRODUK</th>
                            <th class="py-3"
                                style="width: 15%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-top-right-radius: 12px; border-bottom: none;">
                                AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($suppliers)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-store-slash fa-3x mb-3 d-block"></i>
                                    Belum ada supplier yang terhubung.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $rowIdx = 1;
                            foreach ($suppliers as $s):
                                $supplierProducts = $productsBySupplier[$s['id']] ?? [];
                                $parentId = 'parent-' . $s['id'];
                                $childId = 'child-' . $s['id'];
                                ?>
                                <!-- Supplier Parent Row -->
                                <tr class="group-parent-row" id="<?= $parentId ?>" data-group-name="<?= esc($s['name']) ?>"
                                    style="cursor: pointer; transition: background 0.2s ease;">
                                    <td class="ps-4 fw-bold text-muted text-center"><?= $rowIdx++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 32px; height: 32px; background: rgba(229, 57, 53, 0.08); color: var(--palette-primary); flex-shrink: 0;">
                                                <i class="fas fa-chevron-right transition-icon"
                                                    style="font-size: 0.8rem; transition: transform 0.2s ease;"></i>
                                            </div>
                                            <?php if (!empty($s['logo_url']) && file_exists('uploads/supplier_logos/' . $s['logo_url'])): ?>
                                                <img src="<?= base_url('uploads/supplier_logos/' . $s['logo_url']) ?>" alt="Logo"
                                                    class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <span class="fw-bold text-dark text-lg"><?= esc($s['name']) ?></span>
                                                <?php if ($s['status'] === 'approved'): ?>
                                                    <span class="badge bg-success text-white ms-1"
                                                        style="font-size: 0.65rem; border-radius: 4px; padding: 2px 6px;">Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-white ms-1"
                                                        style="font-size: 0.65rem; border-radius: 4px; padding: 2px 6px;"><?= esc($s['status']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark" style="font-size: 0.82rem;"><i
                                                class="fas fa-phone me-1 opacity-50"></i><?= esc($s['phone'] ?: '-') ?></div>
                                        <div class="text-muted" style="font-size: 0.78rem;"><i
                                                class="fas fa-envelope me-1 opacity-50"></i><?= esc($s['email'] ?: '-') ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2 text-white"
                                            style="font-size: 0.72rem; font-weight: 600;">
                                            <?= count($supplierProducts) ?> Produk
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/create') ?>"
                                            class="btn btn-sm btn-outline-primary px-3 d-inline-flex align-items-center justify-content-center"
                                            style="border-radius: 8px; font-size: 0.75rem; font-weight: 600; height: 34px; border: 1.5px solid var(--palette-primary); color: var(--palette-primary); background: transparent;">
                                            <i class="fas fa-plus me-1"></i> Tambah Produk
                                        </a>
                                    </td>
                                </tr>
                                <!-- Supplier Products Child Row -->
                                <tr class="group-detail-row" id="<?= $childId ?>" data-parent-id="<?= $parentId ?>"
                                    style="display: none; background-color: #f8fafc;">
                                    <td colspan="5" class="p-4" style="border-bottom: 1px solid #e2e8f0;">
                                        <div class="table-responsive rounded-3 border bg-white shadow-sm overflow-hidden">
                                            <table class="table table-hover mb-0 align-middle">
                                                <thead>
                                                    <tr class="bg-light text-center">
                                                        <th class="py-3"
                                                            style="width: 5%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            No</th>
                                                        <th class="py-3"
                                                            style="width: 10%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Foto</th>
                                                        <th class="text-start py-3"
                                                            style="width: 30%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Nama Produk</th>
                                                        <th class="text-start py-3"
                                                            style="width: 20%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Kategori</th>
                                                        <th class="text-end py-3"
                                                            style="width: 15%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Harga & Stok</th>
                                                        <th class="py-3"
                                                            style="width: 10%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Status</th>
                                                        <th class="py-3"
                                                            style="width: 12%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase;">
                                                            Verifikasi</th>
                                                        <th class="py-3"
                                                            style="width: 8%; font-size: 0.72rem; font-weight: 700; color: #334155 !important; text-transform: uppercase; border-top-right-radius: 8px;">
                                                            Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($supplierProducts)): ?>
                                                        <tr class="align-middle text-center">
                                                            <td colspan="8" class="text-center py-4 text-muted">
                                                                <i class="fas fa-box-open me-1"></i> Belum ada produk terdaftar.
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php
                                                        $prodIdx = 1;
                                                        foreach ($supplierProducts as $p):
                                                            $photoSrc = !empty($p['photo']) ? base_url('uploads/products/' . $p['photo']) : base_url('uploads/products/default.png');
                                                            ?>
                                                            <tr class="align-middle text-center" data-role="product">
                                                                <td class="fw-bold text-muted"><?= $prodIdx++ ?></td>
                                                                <td>
                                                                    <img src="<?= $photoSrc ?>" class="product-avatar"
                                                                        alt="<?= esc($p['name']) ?>"
                                                                        style="width: 42px; height: 42px; border-radius: 8px;">
                                                                </td>
                                                                <td class="text-start">
                                                                    <div class="fw-bold text-dark"><?= esc($p['name']) ?></div>
                                                                    <small class="text-muted"><?= esc($p['unit'] ?? 'pcs') ?> | Min. Order:
                                                                        <?= esc($p['min_order'] ?? 1) ?></small>
                                                                </td>
                                                                <td class="text-start">
                                                                    <span class="badge bg-light text-dark border px-2 py-1"
                                                                        style="font-size: 0.72rem; font-weight: 600; border-radius: 4px;">
                                                                        <?= esc($p['category_name'] ?? 'Tanpa Kategori') ?>
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">Rp
                                                                        <?= number_format($p['price'], 0, ',', '.') ?></div>
                                                                    <div class="small text-muted">Stok: <?= esc($p['stock']) ?></div>
                                                                </td>
                                                                <td>
                                                                    <?php if ($p['status'] === 'aktif'): ?>
                                                                        <span class="status-badge status-siap py-1 px-2" style="font-size: 0.65rem; display: block;"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                                                    <?php else: ?>
                                                                        <span class="status-badge status-ditolak py-1 px-2" style="font-size: 0.65rem; display: block;"><i class="fas fa-times-circle me-1"></i> Nonaktif</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $appStatus = $p['approval_status'];
                                                                    if ($appStatus === 'approved') {
                                                                        echo '<span class="status-badge status-siap py-1 px-2" style="font-size: 0.65rem; display: block;"><i class="fas fa-check-double me-1"></i> Disetujui</span>';
                                                                    } elseif ($appStatus === 'rejected') {
                                                                        echo '<span class="status-badge status-ditolak py-1 px-2" style="font-size: 0.65rem; display: block;"><i class="fas fa-times-circle me-1"></i> Ditolak</span>';
                                                                    } else {
                                                                        echo '<span class="status-badge status-berkas py-1 px-2" style="font-size: 0.65rem; display: block;"><i class="fas fa-clock me-1"></i> Pending</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-center gap-1">
                                                                        <a href="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/edit/' . $p['id']) ?>"
                                                                            class="btn-action btn-action-detail"
                                                                            style="width: 28px; height: 28px; border-radius: 6px;"
                                                                            title="Edit Produk"><i class="fas fa-pencil-alt"
                                                                                style="font-size: 0.72rem;"></i></a>
                                                                        <button type="button"
                                                                            class="btn-action btn-action-delete btn-delete-product"
                                                                            data-url="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/delete/' . $p['id']) ?>"
                                                                            style="width: 28px; height: 28px; border-radius: 6px;"
                                                                            title="Hapus Produk"><i class="fas fa-trash-alt"
                                                                                style="font-size: 0.72rem;"></i></button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <!-- Product Pagination Footer -->
                                            <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light" id="prod-pagination-footer-<?= $s['id'] ?>">
                                                <div class="product-pagination-info small text-muted" id="prod-info-<?= $s['id'] ?>"></div>
                                                <ul class="pagination pagination-sm mb-0 justify-content-end" id="prod-pagination-list-<?= $s['id'] ?>"></ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer -->
        <div class="card-footer dt-footer d-flex justify-content-between align-items-center" id="table-pagination-footer">
            <div class="dataTables_info" id="pagination-info" role="status" aria-live="polite"></div>
            <div class="dataTables_paginate paging_simple_numbers">
                <ul class="pagination mb-0" id="pagination-list"></ul>
            </div>
        </div>
    </div>
</div>

<!-- ===== MOBILE VIEW (CARDS) ===== -->
<div class="d-block d-md-none" id="mobile-cards-container">
    <?php if (empty($suppliers)): ?>
        <div class="text-center py-5 text-muted bg-white border rounded-4 shadow-sm p-4">
            <i class="fas fa-store-slash fa-3x mb-3 d-block"></i>
            Belum ada supplier yang terhubung.
        </div>
    <?php else: ?>
        <?php foreach ($suppliers as $s): 
            $supplierProducts = $productsBySupplier[$s['id']] ?? [];
            $parentIdMobile = 'parent-mobile-' . $s['id'];
            $childIdMobile = 'child-mobile-' . $s['id'];
            ?>
            <!-- Supplier Card Mobile -->
            <div class="group-parent-card-mobile card mb-3" id="<?= $parentIdMobile ?>" data-group-name="<?= esc($s['name']) ?>" style="border-radius:16px; border: 1px solid #e2e8f0; overflow:hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.2s ease; cursor: pointer;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2 transition-icon-wrapper"
                                style="width: 32px; height: 32px; background: rgba(229, 57, 53, 0.08); color: var(--palette-primary); flex-shrink: 0;">
                                <i class="fas fa-chevron-right transition-icon"
                                    style="font-size: 0.8rem; transition: transform 0.2s ease;"></i>
                            </div>
                            <?php if (!empty($s['logo_url']) && file_exists('uploads/supplier_logos/' . $s['logo_url'])): ?>
                                <img src="<?= base_url('uploads/supplier_logos/' . $s['logo_url']) ?>" alt="Logo"
                                    class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                            <?php endif; ?>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;"><?= esc($s['name']) ?></h6>
                                <?php if ($s['status'] === 'approved'): ?>
                                    <span class="badge bg-success text-white" style="font-size: 0.6rem; border-radius: 4px; padding: 1px 4px;">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-white" style="font-size: 0.6rem; border-radius: 4px; padding: 1px 4px;"><?= esc($s['status']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size: 0.68rem; font-weight: 600;">
                            <?= count($supplierProducts) ?> Produk
                        </span>
                    </div>

                    <!-- Tel & Email -->
                    <div class="small text-muted mb-3 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                        <div class="mb-1"><i class="fas fa-phone me-1 opacity-70"></i><?= esc($s['phone'] ?: '-') ?></div>
                        <div><i class="fas fa-envelope me-1 opacity-70"></i><?= esc($s['email'] ?: '-') ?></div>
                    </div>

                    <!-- Action: Tambah Produk -->
                    <div class="d-grid">
                        <a href="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/create') ?>"
                            class="btn btn-sm btn-outline-primary w-100"
                            style="border-radius: 8px; font-size: 0.75rem; font-weight: 600; padding: 6px 12px; border: 1.5px solid var(--palette-primary); color: var(--palette-primary); background: transparent;">
                            <i class="fas fa-plus me-1"></i> Tambah Produk Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supplier Products Mobile Container -->
            <div class="group-detail-card-mobile mb-4" id="<?= $childIdMobile ?>" style="display: none; margin-top: -8px;">
                <div class="bg-light p-2 rounded-4 border">
                    <?php if (empty($supplierProducts)): ?>
                        <div class="text-center py-3 text-muted small">
                            <i class="fas fa-box-open me-1"></i> Belum ada produk terdaftar.
                        </div>
                    <?php else: ?>
                        <!-- Products card list -->
                        <?php foreach ($supplierProducts as $p): 
                            $photoSrc = !empty($p['photo']) ? base_url('uploads/products/' . $p['photo']) : base_url('uploads/products/default.png');
                            ?>
                            <div class="product-card-mobile card mb-2 p-2 border-0 shadow-sm" data-role="product-mobile" style="border-radius: 12px;">
                                <div class="d-flex">
                                    <img src="<?= $photoSrc ?>" alt="<?= esc($p['name']) ?>" class="me-2" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;"><?= esc($p['name']) ?></div>
                                        <div class="text-muted" style="font-size: 0.72rem;"><?= esc($p['unit'] ?? 'pcs') ?> | Min. Order: <?= esc($p['min_order'] ?? 1) ?></div>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-top" style="border-top: 1px dashed #f1f5f9 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted">Kategori:</span>
                                        <span class="badge bg-light text-dark border px-2 py-0" style="font-size: 0.68rem;"><?= esc($p['category_name'] ?? 'Tanpa Kategori') ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted">Harga & Stok:</span>
                                        <span class="fw-bold text-dark" style="font-size: 0.8rem;">Rp <?= number_format($p['price'], 0, ',', '.') ?> <span class="text-muted font-weight-normal" style="font-size:0.7rem;">(Stok: <?= esc($p['stock']) ?>)</span></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted">Status Akun:</span>
                                        <?php if ($p['status'] === 'aktif'): ?>
                                            <span class="badge bg-success-light text-success px-2 py-0" style="font-size: 0.65rem;"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-light text-danger px-2 py-0" style="font-size: 0.65rem;"><i class="fas fa-times-circle me-1"></i> Nonaktif</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted">Verifikasi:</span>
                                        <?php
                                        $appStatus = $p['approval_status'];
                                        if ($appStatus === 'approved') {
                                            echo '<span class="badge bg-success-light text-success px-2 py-0" style="font-size: 0.65rem;"><i class="fas fa-check-double me-1"></i> Disetujui</span>';
                                        } elseif ($appStatus === 'rejected') {
                                            echo '<span class="badge bg-danger-light text-danger px-2 py-0" style="font-size: 0.65rem;"><i class="fas fa-times-circle me-1"></i> Ditolak</span>';
                                        } else {
                                            echo '<span class="badge bg-warning-light text-warning px-2 py-0" style="font-size: 0.65rem;"><i class="fas fa-clock me-1"></i> Pending</span>';
                                        }
                                        ?>
                                    </div>
                                    <div class="d-flex justify-content-end gap-1 mt-2">
                                        <a href="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/edit/' . $p['id']) ?>"
                                            class="btn btn-sm btn-light border" style="font-size: 0.72rem; padding: 4px 8px;"
                                            title="Edit Produk"><i class="fas fa-pencil-alt me-1"></i> Edit</a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger btn-delete-product"
                                            data-url="<?= site_url('admin/sales/suppliers/' . $s['id'] . '/products/delete/' . $p['id']) ?>"
                                            style="font-size: 0.72rem; padding: 4px 8px;"
                                            title="Hapus Produk"><i class="fas fa-trash-alt me-1"></i> Hapus</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Product Pagination Footer Mobile -->
                        <div class="d-flex flex-column align-items-center p-2 mt-2 bg-white rounded-3 border" id="prod-pagination-footer-mobile-<?= $s['id'] ?>">
                            <div class="product-pagination-info small text-muted mb-2" id="prod-info-mobile-<?= $s['id'] ?>"></div>
                            <ul class="pagination pagination-sm mb-0 justify-content-center" id="prod-pagination-list-mobile-<?= $s['id'] ?>"></ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination Footer Mobile (Suppliers) -->
<div class="d-block d-md-none mb-4" id="table-pagination-footer-mobile">
    <div class="card p-3 border-0 shadow-sm rounded-4 text-center">
        <div class="small text-muted mb-2" id="pagination-info-mobile"></div>
        <ul class="pagination pagination-sm justify-content-center mb-0" id="pagination-list-mobile"></ul>
    </div>
</div>

<!-- Modal Klaim Supplier -->
<div class="modal fade" id="claimSupplierModal" tabindex="-1" aria-labelledby="claimSupplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header text-white text-center d-block p-4"
                style="background: linear-gradient(135deg, var(--palette-primary, #e53935), #ff7070); border-bottom: none; position: relative;">
                <button type="button" class="btn-close btn-close-white position-absolute"
                    style="top: 20px; right: 20px; background: none; border: none; color: white; font-size: 1.2rem;"
                    data-bs-dismiss="modal" aria-label="Close">&times;</button>
                <i class="fas fa-qrcode fa-3x mb-3"></i>
                <h4 class="modal-title w-100 fw-bold" id="claimSupplierModalLabel">Klaim Toko Supplier</h4>
                <p class="mb-0 text-white-50" style="font-size: 0.9rem;">Hubungkan akun supplier yang Anda bantu input
                    produknya</p>
            </div>
            <div class="modal-body p-4">
                <form action="<?= site_url('admin/sales/claim') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group mb-4">
                        <label class="form-label text-center d-block fw-bold mb-3 text-muted"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">MASUKKAN KODE REFERAL DARI HP
                            SUPPLIER</label>
                        <input type="text" name="code" class="form-control form-control-lg text-center fw-bold"
                            placeholder="SUP-XXXXXX"
                            style="font-size: 1.25rem; letter-spacing: 1px; text-transform: uppercase; border-radius: 10px; border: 2px solid #dee2e6;"
                            required autocomplete="off">
                        <small class="text-muted d-block text-center mt-2" style="font-size: 0.78rem;">Kode dinamis
                            berlaku selama 10 menit.</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg"
                            style="border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, var(--palette-primary, #e53935), #c62828); border: none;">
                            <i class="fas fa-link me-2"></i> Hubungkan Toko
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function () {
        // Initialize Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();

        var currentPage = 1;
        var itemsPerPage = 5;

        // Toggle Detail Row on desktop row click (excluding buttons and links)
        $(document).on('click', '.group-parent-row', function (e) {
            if ($(e.target).closest('a, button').length) {
                return;
            }
            toggleRow($(this));
        });

        // Toggle Detail Row via explicit Detail button
        $(document).on('click', '.toggle-detail-btn', function (e) {
            e.preventDefault();
            toggleRow($(this).closest('.group-parent-row'));
        });

        function toggleRow(parentRow) {
            var childId = parentRow.attr('id').replace('parent-', 'child-');
            var childRow = $('#' + childId);

            if (parentRow.hasClass('expanded')) {
                parentRow.removeClass('expanded');
                childRow.fadeOut(150);
            } else {
                parentRow.addClass('expanded');
                childRow.fadeIn(150);
            }
        }

        // Toggle Detail Card on mobile click (excluding buttons and links)
        $(document).on('click', '.group-parent-card-mobile', function (e) {
            if ($(e.target).closest('a, button').length) {
                return;
            }
            toggleCardMobile($(this));
        });

        function toggleCardMobile(parentCard) {
            var childId = parentCard.attr('id').replace('parent-mobile-', 'child-mobile-');
            var childCard = $('#' + childId);

            if (parentCard.hasClass('expanded')) {
                parentCard.removeClass('expanded');
                childCard.fadeOut(150);
            } else {
                parentCard.addClass('expanded');
                childCard.fadeIn(150);
            }
        }

        // Display rows/cards for specific page
        function showPage(page) {
            currentPage = page;
            var start = (page - 1) * itemsPerPage;
            var end = start + itemsPerPage;
            var searchQuery = $('#searchInput').val().toLowerCase().trim();

            // 1. Desktop Sync
            var matchingParentsDesktop = $('.group-parent-row').filter(function () {
                return $(this).data('matches-search') !== false;
            });

            $('.group-parent-row').hide();
            $('.group-detail-row').hide();

            matchingParentsDesktop.slice(start, end).each(function () {
                var parent = $(this);
                parent.show();

                var childId = parent.attr('id').replace('parent-', 'child-');
                var childRow = $('#' + childId);

                if (searchQuery !== '') {
                    parent.addClass('expanded');
                    childRow.show();
                } else {
                    if (parent.hasClass('expanded')) {
                        childRow.show();
                    }
                }
            });

            updatePaginationControls(matchingParentsDesktop.length, start, end, '#pagination-info', '#pagination-list', '');

            // 2. Mobile Sync
            var matchingParentsMobile = $('.group-parent-card-mobile').filter(function () {
                return $(this).data('matches-search') !== false;
            });

            $('.group-parent-card-mobile').hide();
            $('.group-detail-card-mobile').hide();

            matchingParentsMobile.slice(start, end).each(function () {
                var parent = $(this);
                parent.show();

                var childId = parent.attr('id').replace('parent-mobile-', 'child-mobile-');
                var childCard = $('#' + childId);

                if (searchQuery !== '') {
                    parent.addClass('expanded');
                    childCard.show();
                } else {
                    if (parent.hasClass('expanded')) {
                        childCard.show();
                    }
                }
            });

            updatePaginationControls(matchingParentsMobile.length, start, end, '#pagination-info-mobile', '#pagination-list-mobile', '-mobile');
        }

        // Render Pagination buttons and stats info
        function updatePaginationControls(totalItems, start, end, infoSelector, listSelector, suffix) {
            var info = $(infoSelector);
            var list = $(listSelector);
            list.empty();

            if (totalItems === 0) {
                info.text('Tidak ada data supplier yang cocok');
                list.empty();
                return;
            }

            var displayStart = start + 1;
            var displayEnd = Math.min(end, totalItems);
            info.text('Menampilkan ' + displayStart + '-' + displayEnd + ' dari ' + totalItems + ' supplier');

            var totalPages = Math.ceil(totalItems / itemsPerPage);
            if (totalPages <= 1) {
                return;
            }

            // Previous Button
            var prevClass = (currentPage === 1) ? 'disabled' : '';
            list.append('<li class="page-item ' + prevClass + '"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '" data-suffix="' + suffix + '"><i class="fas fa-chevron-left"></i></a></li>');

            // Page numbers
            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                list.append('<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-page="' + i + '" data-suffix="' + suffix + '">' + i + '</a></li>');
            }

            // Next Button
            var nextClass = (currentPage === totalPages) ? 'disabled' : '';
            list.append('<li class="page-item ' + nextClass + '"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '" data-suffix="' + suffix + '"><i class="fas fa-chevron-right"></i></a></li>');
        }

        // Handle page navigation click
        $(document).on('click', '#pagination-list a, #pagination-list-mobile a', function (e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'));
            var parentLi = $(this).parent();
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }
            showPage(page);
        });

        // Desktop Product Pagination
        var productPages = {};
        function paginateProducts(supplierId, page) {
            productPages[supplierId] = page;
            var itemsPerProdPage = 5;
            var childRow = $('#child-' + supplierId);
            var allRows = childRow.find('tbody tr[data-role="product"]');
            var searchQuery = $('#searchInput').val().toLowerCase().trim();
            
            var targetRows;
            if (searchQuery !== '') {
                targetRows = allRows.filter('.matches-search');
            } else {
                targetRows = allRows;
            }

            var totalProds = targetRows.length;
            var start = (page - 1) * itemsPerProdPage;
            var end = start + itemsPerProdPage;

            allRows.hide();
            targetRows.slice(start, end).show();

            var info = $('#prod-info-' + supplierId);
            var list = $('#prod-pagination-list-' + supplierId);
            var footer = $('#prod-pagination-footer-' + supplierId);
            
            list.empty();

            if (allRows.length === 0) {
                footer.hide();
                return;
            }

            footer.show();

            if (totalProds === 0) {
                info.text('Tidak ada produk yang cocok');
                list.hide();
                return;
            }

            var displayStart = start + 1;
            var displayEnd = Math.min(end, totalProds);
            info.text('Menampilkan ' + displayStart + '-' + displayEnd + ' dari ' + totalProds + ' produk');

            var totalPages = Math.ceil(totalProds / itemsPerProdPage);
            if (totalPages <= 1) {
                list.hide();
                return;
            }
            list.show();

            var prevClass = (page === 1) ? 'disabled' : '';
            list.append('<li class="page-item ' + prevClass + '"><a class="page-link" href="#" data-supplier="' + supplierId + '" data-page="' + (page - 1) + '"><i class="fas fa-chevron-left" style="font-size:0.65rem;"></i></a></li>');

            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === page) ? 'active' : '';
                list.append('<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-supplier="' + supplierId + '" data-page="' + i + '">' + i + '</a></li>');
            }

            var nextClass = (page === totalPages) ? 'disabled' : '';
            list.append('<li class="page-item ' + nextClass + '"><a class="page-link" href="#" data-supplier="' + supplierId + '" data-page="' + (page + 1) + '"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i></a></li>');
        }

        // Desktop product pagination click
        $(document).on('click', '[id^="prod-pagination-list-"] .page-link', function(e) {
            e.preventDefault();
            var sId = $(this).data('supplier');
            var page = parseInt($(this).data('page'));
            var parentLi = $(this).parent();
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }
            paginateProducts(sId, page);
        });

        // Mobile Product Pagination
        var productMobilePages = {};
        function paginateProductsMobile(supplierId, page) {
            productMobilePages[supplierId] = page;
            var itemsPerProdPage = 5;
            var childCard = $('#child-mobile-' + supplierId);
            var allCards = childCard.find('.product-card-mobile');
            var searchQuery = $('#searchInput').val().toLowerCase().trim();
            
            var targetCards;
            if (searchQuery !== '') {
                targetCards = allCards.filter('.matches-search');
            } else {
                targetCards = allCards;
            }

            var totalProds = targetCards.length;
            var start = (page - 1) * itemsPerProdPage;
            var end = start + itemsPerProdPage;

            allCards.hide();
            targetCards.slice(start, end).show();

            var info = $('#prod-info-mobile-' + supplierId);
            var list = $('#prod-pagination-list-mobile-' + supplierId);
            var footer = $('#prod-pagination-footer-mobile-' + supplierId);
            
            list.empty();

            if (allCards.length === 0) {
                footer.hide();
                return;
            }

            footer.show();

            if (totalProds === 0) {
                info.text('Tidak ada produk yang cocok');
                list.hide();
                return;
            }

            var displayStart = start + 1;
            var displayEnd = Math.min(end, totalProds);
            info.text('Menampilkan ' + displayStart + '-' + displayEnd + ' dari ' + totalProds + ' produk');

            var totalPages = Math.ceil(totalProds / itemsPerProdPage);
            if (totalPages <= 1) {
                list.hide();
                return;
            }
            list.show();

            var prevClass = (page === 1) ? 'disabled' : '';
            list.append('<li class="page-item ' + prevClass + '"><a class="page-link" href="#" data-supplier-mobile="' + supplierId + '" data-page="' + (page - 1) + '"><i class="fas fa-chevron-left" style="font-size:0.65rem;"></i></a></li>');

            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === page) ? 'active' : '';
                list.append('<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-supplier-mobile="' + supplierId + '" data-page="' + i + '">' + i + '</a></li>');
            }

            var nextClass = (page === totalPages) ? 'disabled' : '';
            list.append('<li class="page-item ' + nextClass + '"><a class="page-link" href="#" data-supplier-mobile="' + supplierId + '" data-page="' + (page + 1) + '"><i class="fas fa-chevron-right" style="font-size:0.65rem;"></i></a></li>');
        }

        // Mobile product pagination click
        $(document).on('click', '[id^="prod-pagination-list-mobile-"] .page-link', function(e) {
            e.preventDefault();
            var sId = $(this).data('supplier-mobile');
            var page = parseInt($(this).data('page'));
            var parentLi = $(this).parent();
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }
            paginateProductsMobile(sId, page);
        });

        // Combined search query logic (Searches supplier name or product details)
        function applySearch() {
            var searchQuery = $('#searchInput').val().toLowerCase().trim();

            // 1. Desktop Search filter
            $('.group-parent-row').each(function () {
                var parentRow = $(this);
                var gName = (parentRow.data('group-name') || '').toLowerCase();
                var groupMatches = (searchQuery === '' || gName.indexOf(searchQuery) > -1);

                var childId = parentRow.attr('id').replace('parent-', 'child-');
                var childRow = $('#' + childId);

                var matchingChildCount = 0;

                childRow.find('tbody tr[data-role="product"]').each(function () {
                    var row = $(this);
                    var rowText = row.text().toLowerCase();

                    if (searchQuery === '' || groupMatches || rowText.indexOf(searchQuery) > -1) {
                        row.addClass('matches-search');
                        matchingChildCount++;
                    } else {
                        row.removeClass('matches-search');
                    }
                });

                var parentMatches = (searchQuery === '' || groupMatches || matchingChildCount > 0);
                parentRow.data('matches-search', parentMatches);

                if (searchQuery !== '') {
                    if (parentMatches) {
                        parentRow.addClass('expanded');
                    } else {
                        parentRow.removeClass('expanded');
                    }
                } else {
                    parentRow.removeClass('expanded');
                }

                var supplierId = parentRow.attr('id').replace('parent-', '');
                paginateProducts(supplierId, 1);
            });

            // 2. Mobile Search filter
            $('.group-parent-card-mobile').each(function () {
                var parentCard = $(this);
                var gName = (parentCard.data('group-name') || '').toLowerCase();
                var groupMatches = (searchQuery === '' || gName.indexOf(searchQuery) > -1);

                var childId = parentCard.attr('id').replace('parent-mobile-', 'child-mobile-');
                var childCard = $('#' + childId);

                var matchingChildCount = 0;

                childCard.find('.product-card-mobile').each(function () {
                    var card = $(this);
                    var cardText = card.text().toLowerCase();

                    if (searchQuery === '' || groupMatches || cardText.indexOf(searchQuery) > -1) {
                        card.addClass('matches-search');
                        matchingChildCount++;
                    } else {
                        card.removeClass('matches-search');
                    }
                });

                var parentMatches = (searchQuery === '' || groupMatches || matchingChildCount > 0);
                parentCard.data('matches-search', parentMatches);

                if (searchQuery !== '') {
                    if (parentMatches) {
                        parentCard.addClass('expanded');
                    } else {
                        parentCard.removeClass('expanded');
                    }
                } else {
                    parentCard.removeClass('expanded');
                }

                var supplierId = parentCard.attr('id').replace('parent-mobile-', '');
                paginateProductsMobile(supplierId, 1);
            });

            showPage(1);
        }

        // Search trigger
        $('#searchInput').on('keyup search input', function () {
            applySearch();
        });

        // Run initial load
        applySearch();

        // Auto-expand if redirect contains supplier_id query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const autoSupplierId = urlParams.get('supplier_id');
        if (autoSupplierId) {
            var parentRow = $('#parent-' + autoSupplierId);
            var parentCard = $('#parent-mobile-' + autoSupplierId);
            
            if (parentRow.length || parentCard.length) {
                var index = $('.group-parent-row').index(parentRow);
                if (index === -1) {
                    index = $('.group-parent-card-mobile').index(parentCard);
                }
                var targetPage = Math.floor(index / itemsPerPage) + 1;
                showPage(targetPage);

                var targetElement = window.innerWidth >= 768 ? parentRow : parentCard;
                if (targetElement.length) {
                    $('html, body').animate({
                        scrollTop: targetElement.offset().top - 100
                    }, 500);

                    setTimeout(function () {
                        if (window.innerWidth >= 768) {
                            if (!parentRow.hasClass('expanded')) {
                                toggleRow(parentRow);
                            }
                        } else {
                            if (!parentCard.hasClass('expanded')) {
                                toggleCardMobile(parentCard);
                            }
                        }
                    }, 300);
                }
            }
        }

        // SweetAlert confirm deletion
        $(document).on('click', '.btn-delete-product', function () {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Produk akan dihapus permanen dari toko supplier ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53935',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>