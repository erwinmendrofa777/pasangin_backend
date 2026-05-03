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

    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }

    .dataTables_paginate .paginate_button:hover:not(.current) {
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