<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 60%, #084298 100%);
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 44px !important;
        border-radius: 12px !important;
        border: 1.5px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.88rem;
        height: 42px;
    }

    .search-wrapper input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #adb5bd;
        opacity: 0.8;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f6ff;
    }

    #table-1 thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #f8fbff !important;
    }

    #table-1 tbody td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== BADGES ===== */
    .status-badge {
        border-radius: 50px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Status Design: PENDING, SURVEY_SCHEDULED, PAYMENT_VERIFIED, COMPLETED, CANCELLED */
    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-survey {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-payment {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #e9ecef;
        color: #495057;
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
        background: #0d6efd;
        color: #ffffff;
    }

    .btn-action-detail:hover {
        background: #0b5ed7;
        color: #ffffff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #ffffff;
    }

    .btn-action-delete:hover {
        background: #b02a37;
        color: #fff;
    }

    .btn-action-disabled {
        background: #f1f3f5;
        color: #adb5bd;
        cursor: not-allowed;
    }

    .btn-action-disabled:hover {
        transform: none;
        box-shadow: none;
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

    .dataTables_paginate .page-item .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        margin: 0 3px;
        border: 1px solid transparent;
        color: #0d6efd;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    .dataTables_paginate .page-item:not(.active) .page-link:hover {
        background: #e7f0ff !important;
        border-color: #e7f0ff !important;
        color: #0d6efd !important;
    }

    @media (max-width: 768px) {
        .table-card-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px !important;
            padding: 20px 16px !important;
            background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%) !important;
        }

        .table-card-header h6 {
            font-size: 1rem !important;
            width: 100%;
        }

        .search-wrapper {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dt-footer {
            flex-direction: column;
            gap: 12px;
            padding: 16px !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (in_array(strtolower(session()->get('role') ?? ''), ['kepala divisi desain', 'drafter', 'arsitek'])): ?>
<!-- ===== STATS CARDS ===== -->
<div class="row mb-0">
    <!-- Card Survei -->
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Survei Lapangan</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['surveys']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Laporan</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-route fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25" style="font-size: 0.8rem;">
                <span class="text-white-75">Total file laporan survei yang diunggah</span>
            </div>
        </div>
    </div>

    <!-- Card Target -->
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #6f42c1 0%, #a881af 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Target Pengerjaan</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['targets']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Target</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-bullseye fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between"
                style="font-size: 0.8rem;">
                <span class="text-white-75"><i
                        class="fas fa-check-circle me-1"></i><?= $workStats['targets']['done'] ?? 0 ?> Selesai</span>
                <span class="text-white-75"><i
                        class="fas fa-spinner me-1"></i><?= $workStats['targets']['progress'] ?? 0 ?> Proses</span>
                <span class="text-white-75"><i
                        class="fas fa-clock me-1"></i><?= $workStats['targets']['pending'] ?? 0 ?> Antri</span>
            </div>
        </div>
    </div>

    <!-- Card Desain -->
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Hasil Desain</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['designs']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Berkas</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-magic fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between"
                style="font-size: 0.8rem;">
                <span class="text-white-75"><i
                        class="fas fa-check-double me-1"></i><?= $workStats['designs']['approved'] ?? 0 ?>
                    Disetujui</span>
                <span class="text-white-75"><i
                        class="fas fa-exclamation-triangle me-1"></i><?= $workStats['designs']['rejected'] ?? 0 ?>
                    Revisi</span>
                <span class="text-white-75"><i
                        class="fas fa-hourglass-half me-1"></i><?= $workStats['designs']['pending'] ?? 0 ?>
                    Tinjau</span>
            </div>
        </div>
    </div>
</div>


    <!-- ===== DAFTAR TUGAS SAYA ===== -->
    <div class="card border-0 mb-4" style="border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04);">
        <!-- Card Header -->
        <div class="card-header border-0 d-flex align-items-center"
            style="background: #ffffff; padding: 20px 24px; border-radius: 20px 20px 0 0; border-bottom: 1px solid rgba(0,0,0,0.03) !important;">
            <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-2 me-3 d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="fas fa-clipboard-check fa-lg"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px; font-size: 1.2rem;">Daftar Tugas Saya</h5>
                <p class="text-muted mb-0" style="font-size: 0.85rem; letter-spacing: 0.2px;">Monitor target pengerjaan
                    desain Anda yang sedang aktif</p>
            </div>
        </div>

        <div class="card-body" style="background: #f8fbff; border-radius: 0 0 20px 20px; padding: 24px;">
            <?php if (!empty($designerTasks)): ?>
                <!-- Custom styles for elegant task cards -->
                <style>
                    .task-scroll-container::-webkit-scrollbar {
                        height: 8px;
                    }

                    .task-scroll-container::-webkit-scrollbar-track {
                        background: rgba(0, 0, 0, 0.02);
                        border-radius: 10px;
                    }

                    .task-scroll-container::-webkit-scrollbar-thumb {
                        background: rgba(0, 0, 0, 0.15);
                        border-radius: 10px;
                    }

                    .task-scroll-container::-webkit-scrollbar-thumb:hover {
                        background: rgba(0, 0, 0, 0.25);
                    }

                    .task-card {
                        border-radius: 12px;
                        transition: all 0.3s ease;
                        border: 1px solid #edf2f7;
                        background: #ffffff;
                        position: relative; /* Pastikan untuk stretched-link */
                    }

                    .task-card:hover {
                        transform: translateY(-4px);
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
                        border-color: #e2e8f0;
                    }

                    .btn-minimal {
                        width: 36px;
                        height: 36px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f8f9fa;
                        border-radius: 50%;
                        transition: all 0.2s;
                        color: #0d6efd;
                    }

                    .task-card:hover .btn-minimal {
                        background: #0d6efd;
                        color: #ffffff;
                        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25) !important;
                    }
                </style>

                <!-- Horizontal Scroll Container -->
                <div class="d-flex flex-nowrap gap-4 pb-3 pt-2 px-1 task-scroll-container"
                    style="overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scroll-behavior: smooth;">
                    <?php foreach ($designerTasks as $task): ?>
                        <?php
                        // Logic Status Dinamis
                        $tStatus = $task['status'];
                        $tColor = 'secondary';

                        if ($tStatus === 'PENDING') {
                            $tStatus = 'BELUM DIKERJAKAN';
                            $tColor = 'secondary';
                        } elseif ($tStatus === 'ON PROGRESS') {
                            $tStatus = 'SEDANG DIPROSES';
                            $tColor = 'info';
                        } elseif ($tStatus === 'DONE') {
                            $tStatus = 'SELESAI (TANPA FILE)';
                            $tColor = 'success';
                        }

                        if ($task['total_designs'] > 0) {
                            if ($task['approved_designs'] > 0) {
                                $tStatus = 'DISETUJUI';
                                $tColor = 'success';
                            } elseif ($task['pending_designs'] > 0) {
                                $tStatus = 'TINJAUAN';
                                $tColor = 'primary';
                            } else {
                                $tStatus = 'PERLU REVISI';
                                $tColor = 'danger';
                            }
                        }
                        ?>
                        <div style="min-width: 300px; width: 300px; flex: 0 0 auto;">
                            <div class="card h-100 shadow-sm task-card">
                                <div class="card-body p-4 d-flex flex-column">

                                    <!-- Header: Status + Date -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                            <?= date('d M', strtotime($task['created_at'])) ?>
                                        </span>
                                        <span class="badge text-white bg-<?= $tColor ?> rounded-pill px-2 py-1"
                                            style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                            <?= esc($tStatus) ?>
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="fw-bold text-dark mb-4"
                                        style="font-size: 1.1rem; line-height: 1.4; white-space: normal;">
                                        <?= esc($task['task_name']) ?></h5>

                                    <!-- Simple Info -->
                                    <div class="mb-4 flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-light">
                                            <div class="text-muted" style="font-size: 0.8rem;">Konsep</div>
                                            <div class="fw-semibold text-dark text-end"
                                                style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                                                <?= esc($task['design_concept'] ?? 'Proyek Khusus') ?></div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-muted" style="font-size: 0.8rem;">Klien</div>
                                            <div class="fw-semibold text-dark text-end"
                                                style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                                                <?= esc($task['client_name'] ?? 'Klien Internal') ?></div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div
                                        class="mt-auto pt-1 border-top border-light d-flex justify-content-between align-items-center">
                                        <?php
                                        $targetStartDateStr = '';
                                        $targetEndDateStr = '';
                                        if (!empty($task['request_start_date'])) {
                                            $projStart = new DateTime($task['request_start_date']);
                                            $tStart = clone $projStart;
                                            if ($task['start_week'] > 1) {
                                                $tStart->modify('+' . ($task['start_week'] - 1) . ' days');
                                            }
                                            $tEnd = clone $projStart;
                                            if ($task['end_week'] > 1) {
                                                $tEnd->modify('+' . ($task['end_week'] - 1) . ' days');
                                            }
                                            $targetStartDateStr = $tStart->format('d M');
                                            $targetEndDateStr = $tEnd->format('d M Y');
                                        }
                                        ?>
                                        <div>
                                            <div class="text-dark fw-bold" style="font-size: 0.85rem;">
                                                Hari <?= esc($task['start_week']) ?> &ndash; <?= esc($task['end_week']) ?>
                                            </div>
                                            <?php if ($targetStartDateStr): ?>
                                                <div class="text-muted" style="font-size: 0.75rem; margin-top: 2px;">
                                                    <?= $targetStartDateStr ?> - <?= $targetEndDateStr ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>?target_id=<?= $task['id'] ?>&admin_id=<?= $task['user_admin_id'] ?>#design"
                                            class="btn-minimal shadow-sm text-decoration-none stretched-link">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-white shadow-sm"
                    style="border-radius: 16px; border: 1px dashed #e2e8f0; margin: 0 4px;">
                    <div class="text-muted mb-3"><i class="fas fa-check-circle opacity-25 text-success"
                            style="font-size: 3.5rem;"></i></div>
                    <h5 class="text-dark fw-bold mb-2">Semua Tugas Selesai!</h5>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Saat ini Anda tidak memiliki target pekerjaan yang
                        mengantri.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header: Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-paint-brush me-2"></i>Daftar Permintaan Desain
        </h6>
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, telepon, konsep...">
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Client</th>
                        <th class="text-center">Tanggal Pengajuan</th>
                        <th class="text-center">Konsep</th>
                        <th class="text-center">Estimasi Pengerjaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $key => $row): ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                </td>
                                <td>
                                    <div class="text-start text-center ps-3">
                                        <span class="fw-bold text-dark d-block"><?= esc($row['full_name']) ?></span>
                                        <span class="text-muted"
                                            style="font-size: 0.8rem;"><?= esc($row['phone_number']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold"><i
                                            class="far fa-calendar-alt text-muted me-1"></i><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                </td>
                                <td class="fw-bold text-primary"><?= esc($row['design_concept']) ?></td>
                                <td>
                                    <?php if (!empty($row['start_date']) && !empty($row['target_date'])): ?>
                                        <div class="text-start d-inline-block">
                                            <div style="font-size: 0.75rem;"><strong
                                                    class="text-dark"><?= date('d M Y', strtotime($row['start_date'])) ?> -
                                                    <?= date('d M Y', strtotime($row['target_date'])) ?></strong></div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic" style="font-size: 0.75rem;">Belum dijadwalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $sClass = 'status-default';
                                    $sIcon = 'fas fa-circle';
                                    if ($status == 'PENDING') {
                                        $sClass = 'status-pending';
                                        $sIcon = 'fas fa-clock';
                                    } elseif ($status == 'SURVEY_SCHEDULED') {
                                        $sClass = 'status-survey';
                                        $sIcon = 'fas fa-calendar-check';
                                    } elseif ($status == 'PAYMENT_VERIFIED') {
                                        $sClass = 'status-payment';
                                        $sIcon = 'fas fa-file-invoice-dollar';
                                    } elseif ($status == 'COMPLETED') {
                                        $sClass = 'status-completed';
                                        $sIcon = 'fas fa-check-circle';
                                    } elseif ($status == 'CANCELLED') {
                                        $sClass = 'status-cancelled';
                                        $sIcon = 'fas fa-times-circle';
                                    }
                                    ?>
                                    <span class="status-badge <?= $sClass ?>">
                                        <i class="<?= $sIcon ?>"></i> <?= esc(str_replace('_', ' ', $status)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (can('design_detail')): ?>
                                            <a href="<?= base_url('admin/design/show/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn-action btn-action-disabled" data-toggle="tooltip"
                                                title="Akses Detail Terkunci">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (can('design_delete')): ?>
                                            <a href="<?= base_url('admin/design/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete"
                                                onclick="return confirm('Yakin hapus data ini?')" data-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn-action btn-action-disabled" data-toggle="tooltip"
                                                title="Akses Hapus Terkunci">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function () {
        /* ===== DataTables ===== */
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "emptyTable": "Tidak ada data yang tersedia",
                "zeroRecords": "Tidak ada data yang cocok ditemukan"
            },
            "columnDefs": [{
                "sortable": false,
                "targets": [6]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
            "drawCallback": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        /* ===== Custom Search ===== */
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function () {
            if (this.value === '') table.search('').draw();
        });

        /* ===== Tooltips ===== */
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?= $this->endSection() ?>