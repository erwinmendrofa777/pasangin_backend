<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Tukang
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
        padding: 14px 12px;
    }

    #table-1 tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        font-size: 0.88rem;
    }

    /* ===== AVATAR ===== */
    .tukang-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .tukang-avatar:hover {
        transform: scale(1.1);
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }

    /* ===== BADGES ===== */
    .status-badge {
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-berkas {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-test {
        background: #e0f2fe;
        color: #075985;
    }

    .status-aktivasi {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-siap {
        background: #d1fae5;
        color: #065f46;
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
            padding-bottom: 12px;
            border-bottom: 1px dashed #e2e8f0;
            width: 100%;
        }

        .header-actions {
            width: 100% !important;
            flex-direction: column !important;
            gap: 12px !important;
        }

        .header-actions .btn {
            width: 100% !important;
            padding: 10px 16px !important;
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

<div class="card table-card">
    <!-- Header with Search and Create Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-tools me-2"></i>Daftar Mitra Tukang
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email...">
            </div>
            <?php if (can('tukang_create')): ?>
                <a href="<?= base_url('admin/tukang/create') ?>"
                    class="btn btn-primary d-flex align-items-center justify-content-center text-nowrap"
                    style="border-radius: 12px; font-size: 0.82rem; padding: 8px 14px;">
                    <i class="fas fa-plus me-1"></i> Tambah Mitra
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Foto</th>
                        <th class="text-start" style="width: 30%;">Nama & Spesialisasi</th>
                        <th>Email & Telepon</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tukang as $key => $row): ?>
                        <tr class="text-center">
                            <td class="fw-bold text-muted"><?= $key + 1 ?></td>
                            <td>
                                <?php
                                $photoSrc = !empty($row['profile_photo']) ? base_url('uploads/tukang/' . $row['profile_photo']) : base_url('uploads/tukang/default.jpg');
                                ?>
                                <a href="<?= $photoSrc ?>" class="glightbox" data-gallery="tukang-<?= $row['id'] ?>"
                                    data-title="<?= esc($row['name']) ?>"
                                    data-description="Nama: <?= esc($row['name']) ?> &lt;br&gt; Spesialisasi: <?= esc($row['specialization'] ?: 'Umum') ?> &lt;br&gt; Email: <?= esc($row['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($row['phone'] ?: '-') ?> &lt;br&gt; Rating: <?= esc($row['rata_rata_rating'] ?: '0.0') ?> / 5.0">
                                    <img src="<?= $photoSrc ?>" class="tukang-avatar" alt="<?= esc($row['name']) ?>"
                                        data-toggle="tooltip" title="Klik untuk memperbesar">
                                </a>
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($row['name']) ?></div>
                                <div class="text-muted small"><i class="fas fa-briefcase me-1"></i>
                                    <?= esc($row['specialization'] ?: 'Umum') ?></div>
                            </td>
                            <td class="text-start">
                                <div class="small text-dark fw-semibold"><i class="fas fa-envelope me-1 opacity-50"></i>
                                    <?= esc($row['email'] ?: '-') ?></div>
                                <div class="small text-muted"><i class="fas fa-phone me-1 opacity-50"></i>
                                    <?= esc($row['phone'] ?: '-') ?></div>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'];
                                $statusClass = 'status-berkas';
                                $icon = 'fas fa-file-alt';

                                switch ($status) {
                                    case 'Berkas Diproses':
                                        $statusClass = 'status-berkas';
                                        $icon = 'fas fa-file-medical';
                                        break;
                                    case 'Ditolak':
                                        $statusClass = 'status-ditolak';
                                        $icon = 'fas fa-times-circle';
                                        break;
                                    case 'Proses Test':
                                        $statusClass = 'status-test';
                                        $icon = 'fas fa-vial';
                                        break;
                                    case 'Proses Aktivasi':
                                        $statusClass = 'status-aktivasi';
                                        $icon = 'fas fa-user-check';
                                        break;
                                    case 'Siap Kerja':
                                        $statusClass = 'status-siap';
                                        $icon = 'fas fa-check-double';
                                        break;
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <i class="<?= $icon ?> me-1"></i> <?= $status ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-center">
                                    <div class="fw-bold text-primary mb-1">
                                        <i class="fas fa-star text-warning me-1"></i><?= $row['rata_rata_rating'] ?>
                                    </div>
                                    <div style="font-size: 0.65rem;" class="text-muted text-nowrap">
                                        S: <?= $row['skill_score'] ?> | B: <?= $row['behavior_score'] ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('tukang')): ?>
                                        <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('tukang_delete')): ?>
                                        <a href="<?= base_url('admin/tukang/delete/' . $row['id']) ?>"
                                            class="btn-action btn-action-delete ladda-button" data-style="zoom-in"
                                            onclick="return confirm('Hapus data mitra tukang ini?')">
                                            <i class="fas fa-trash-alt"></i>
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
    $(document).ready(function () {
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
                "targets": [1, 6]
            }
            ],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>', // Matches users/index.php design
            "drawCallback": function (settings) {
                // Re-initialize tooltips after table redraw
                $('[data-toggle="tooltip"]').tooltip();
                if (window.globalLightbox) {
                    window.globalLightbox.reload();
                }
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

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
    });
</script>
<?= $this->endSection() ?>