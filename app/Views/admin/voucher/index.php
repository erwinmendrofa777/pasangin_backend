<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Voucher
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Voucher
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
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

    /* ===== VOUCHER IMAGE ===== */
    .voucher-img {
        width: 100px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        object-position: center;
        border: 2px solid #dce8ff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.12);
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

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-inactive {
        background: #e5e7eb;
        color: #1f2937;
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
        color: #fff;
    }

    .btn-action-detail:hover {
        background: #084298;
        color: #fff;
    }

    .btn-action-edit {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-edit:hover {
        background: #d35400;
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

    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }

    @media (max-width: 768px) {
        .search-wrapper {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary" style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-ticket-alt me-2"></i>Daftar Voucher
        </h6>
        <div class="d-flex gap-3">
            <div class="search-wrapper" style="width: 280px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari kode atau nama voucher...">
            </div>
            <?php if (can('vouchers_create')): ?>
            <a href="<?= base_url('admin/vouchers/create') ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3" style="border-radius: 12px; font-weight: 600;">
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
                                <img src="<?= base_url('uploads/vouchers/' . $row['image']) ?>" class="voucher-img" data-toggle="tooltip" title="<?= esc($row['name']) ?>">
                            </td>
                            <td><span class="badge bg-light text-primary fw-bold px-3 py-2" style="border: 1px dashed #0d6efd; border-radius: 8px;"><?= esc($row['code']) ?></span></td>
                            <td class="text-start fw-semibold"><?= esc($row['name'] ?: '-') ?></td>
                            <td><span class="fw-bold text-success">Rp <?= number_format($row['discount_nominal'], 0, ',', '.') ?></span></td>
                            <td>
                                <div class="text-muted" style="font-size: 0.82rem;">
                                    <i class="fas fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($row['valid_until'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $isExpired = strtotime($row['valid_until']) < time();
                                if ($row['is_active'] == 0): ?>
                                    <span class="status-badge status-inactive"><i class="fas fa-times-circle"></i> Nonaktif</span>
                                <?php elseif ($isExpired): ?>
                                    <span class="status-badge status-expired"><i class="fas fa-exclamation-circle"></i> Expired</span>
                                <?php else: ?>
                                    <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('vouchers')): ?>
                                    <a href="<?= base_url('admin/vouchers/detail/' . $row['id']) ?>"
                                        class="btn-action btn-action-detail"
                                        data-toggle="tooltip" title="Lihat Detail">
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

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function() {
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
                "targets": [1, 7]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
            "drawCallback": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        /* ===== Custom Search ===== */
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function() {
            if (this.value === '') table.search('').draw();
        });

        /* ===== Tooltips ===== */
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?= $this->endSection() ?>