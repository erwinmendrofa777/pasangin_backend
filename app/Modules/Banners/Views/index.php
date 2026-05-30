<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Banner
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Banner Iklan
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

    /* ===== BANNER IMAGE ===== */
    .banner-preview {
        width: 140px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        object-position: center;
        border: 2px solid #dce8ff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.12);
        transition: transform 0.2s ease;
    }

    .banner-preview:hover {
        transform: scale(1.05);
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

    .badge-client {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-tukang {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
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
        .search-wrapper {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="d-flex justify-content-between align-items-center px-4 py-3"
                style="border-bottom: 1px solid #f0f4fa;">
                <div>
                    <h6 class="mb-0 fw-bold text-primary"
                        style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
                        <i class="fas fa-images me-2"></i>Daftar Banner Iklan
                    </h6>
                </div>
                <div class="d-flex gap-3">
                    <div class="search-wrapper" style="width: 280px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari banner...">
                    </div>
                    <?php if (can('banner_create')): ?>
                        <a href="<?= base_url('admin/banner/create') ?>"
                            class="btn btn-primary d-flex align-items-center gap-2 px-3"
                            style="border-radius: 12px; font-weight: 600;">
                            <i class="fas fa-plus"></i> Tambah Banner
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th class="text-center">Pratinjau</th>
                                <th class="text-start">Judul Banner</th>
                                <th class="text-center">Target App</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Dibuat Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $key => $row): ?>
                                <tr class="text-center align-middle">
                                    <td>
                                        <span class="fw-semibold text-muted"
                                            style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('uploads/banners/' . $row['image']) ?>" class="glightbox"
                                            data-gallery="banner-gallery" data-title="<?= esc($row['title']) ?>"
                                            data-description="Target: <?= $row['target_app'] == 'client' ? 'Client App' : 'Tukang App' ?>">
                                            <img src="<?= base_url('uploads/banners/' . $row['image']) ?>"
                                                class="banner-preview" data-toggle="tooltip"
                                                title="<?= esc($row['title']) ?>">
                                        </a>
                                    </td>
                                    <td class="text-start fw-semibold"><?= esc($row['title'] ?: '-') ?></td>
                                    <td>
                                        <?php if ($row['target_app'] == 'client'): ?>
                                            <span class="status-badge badge-client">Client App</span>
                                        <?php else: ?>
                                            <span class="status-badge badge-tukang">Tukang App</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            <div class="fw-bold text-dark">
                                                <?= date('d M Y', strtotime($row['created_at'])) ?>
                                            </div>
                                            <div><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (can('banner_delete')): ?>
                                            <a href="<?= base_url('admin/banner/delete/' . $row['id']) ?>"
                                                class="btn btn-danger btn-sm ladda-button rounded-pill px-3"
                                                data-style="zoom-in"
                                                onclick="if(confirm('Yakin hapus banner ini?')) { Ladda.create(this).start(); return true; } return false;">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>

    $(document).ready(function () {
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

                // Re-initialize GLightbox
                if (window.GLightbox) {
                    GLightbox({ selector: '.glightbox' });
                }
            }
        });

        if (window.GLightbox) {
            GLightbox({ selector: '.glightbox' });
        }

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function () {
            if (this.value === '') table.search('').draw();
        });

        $('[data-toggle="tooltip"]').tooltip();

        // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
        $(document).on('submit', 'form', function () {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });
    });
</script>
<?= $this->endSection() ?>