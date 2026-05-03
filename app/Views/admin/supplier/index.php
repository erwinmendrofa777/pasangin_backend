<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Supplier
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

    /* ===== STAT PILLS ===== */
    .stat-pill {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #fff;
        font-weight: 600;
        backdrop-filter: blur(4px);
    }

    .stat-pill .stat-num {
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50px;
        padding: 1px 10px;
        font-weight: 700;
        font-size: 0.85rem;
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
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
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

    /* ===== AVATAR ===== */
    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
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

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-banned {
        background: #e5e7eb;
        color: #1f2937;
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
        cursor: pointer;
    }

    .btn-action-detail {
        background: #0d6efd;
        color: #fff;
    }

    .btn-action-detail:hover {
        background: #0d6efd;
        color: #fff;
    }

    .btn-action-edit {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
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

    .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #e7f0ff !important;
        border-color: #e7f0ff !important;
        color: #0d6efd !important;
    }

    mark {
        background-color: #dbeafe;
        color: #1d4ed8;
        padding: 1px 3px;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .page-header-card {
            border-radius: 12px;
        }

        .page-header-card>.d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
            padding: 16px !important;
        }

        .header-actions {
            width: 100% !important;
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

    <!-- Card Header: Search & Add -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header" style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center" style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-truck me-2"></i>Daftar Supplier
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper pb-sm-0 pb-3">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput"
                    placeholder="Cari nama, email, telepon...">
            </div>
            <?php if (can('suppliers_create')): ?>
                <a href="<?= base_url('admin/suppliers/create') ?>" class="btn btn-primary d-flex align-items-center justify-content-center" style="border-radius: 12px; font-size: 0.88rem; padding: 3px 16px; white-space: nowrap;">
                    <i class="fas fa-plus me-1"></i> Tambah Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center">Nama Supplier</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Kontak Person</th>
                        <th class="text-center">Telepon</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $key => $supplier) : ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="fw-semibold text-start ps-3"><?= esc($supplier['name']) ?></td>
                            <td class="text-muted"><?= esc($supplier['email']) ?></td>
                            <td class="text-muted"><?= esc($supplier['contact_person']) ?></td>
                            <td class="text-muted"><?= esc($supplier['phone']) ?></td>
                            <td>
                                <?php
                                $status = $supplier['status'];
                                $statusMap = [
                                    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle', 'label' => 'Approved'],
                                    'pending'  => ['class' => 'status-pending',  'icon' => 'fas fa-clock',        'label' => 'Pending'],
                                    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected'],
                                    'banned'   => ['class' => 'status-banned',   'icon' => 'fas fa-ban',          'label' => 'Banned'],
                                ];
                                $s = $statusMap[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>">
                                    <i class="<?= $s['icon'] ?>"></i><?= $s['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('suppliers')): ?>
                                        <a href="<?= base_url('admin/suppliers/detail/' . $supplier['id']) ?>"
                                            class="btn-action btn-action-detail"
                                            data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('suppliers_edit')): ?>
                                        <a href="<?= base_url('admin/suppliers/edit/' . $supplier['id']) ?>"
                                            class="btn-action btn-action-edit"
                                            data-toggle="tooltip" title="Edit Supplier">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
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
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 20000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 20000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    // Inisialisasi DataTable
    $(document).ready(function() {
        // Konfigurasi DataTables dengan fitur search yang enhanced
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
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
                    "targets": [2, 3, 4, 6]
                } // Foto dan Aksi tidak bisa di-sort
            ],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>', // Matches users/index.php design
            "drawCallback": function(settings) {
                // Re-initialize tooltips after table redraw
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // Hubungkan search input custom dengan DataTables search
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Clear search when input is cleared
        $('#searchInput').on('search', function() {
            if (this.value === '') {
                table.search('').draw();
            }
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
        $(document).on('submit', 'form', function() {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });
    });
</script>
<?= $this->endSection() ?>