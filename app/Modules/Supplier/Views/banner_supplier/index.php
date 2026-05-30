<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Banner Supplier
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
        cursor: pointer;
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
        text-transform: uppercase;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-action-detail {
        background: #0d6efd;
        color: #0d6efd;
    }

    .btn-action-detail:hover {
        background: #0d6efd;
        color: #fff;
    }

    .btn-action-edit {
        background: #fd7e14;
        color: #fd7e14;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #dc3545;
    }

    .btn-action-delete:hover {
        background: #dc3545;
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
        .card-header-flex {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start !important;
        }

        .search-wrapper {
            width: 100% !important;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="d-flex justify-content-between align-items-center px-4 py-3 card-header-flex"
                style="border-bottom: 1px solid #f0f4fa;">
                <div>
                    <h6 class="mb-0 fw-bold text-primary"
                        style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
                        <i class="fas fa-store me-2"></i>Banner Supplier
                    </h6>
                    <p class="text-muted small mb-0 mt-1">Kelola pengajuan banner promosi dari mitra supplier.</p>
                </div>
                <div class="d-flex gap-3 flex-wrap flex-grow-1 justify-content-end align-items-center">
                    <div class="search-wrapper" style="width: 280px; min-width: 200px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Cari supplier atau promo...">
                    </div>
                    <?php if (can('banner_supplier_create')): ?>
                        <a href="<?= base_url('admin/banner-supplier/add') ?>"
                            class="btn btn-primary d-flex align-items-center gap-2 px-3 btn-add"
                            style="border-radius: 12px; font-weight: 600; height: 42px;">
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
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center">Banner</th>
                                <th class="text-start">Supplier & Judul Promo</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Tanggal</th>
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
                                        <a href="<?= base_url('uploads/supplier/banner/' . $row['image']) ?>" class="glightbox" data-gallery="supplier-banner-gallery" data-title="<?= esc($row['supplier_name']) ?>" data-description="<?= esc($row['title']) ?>">
                                            <img src="<?= base_url('uploads/supplier/banner/' . $row['image']) ?>"
                                                class="banner-preview" data-toggle="tooltip" title="Klik untuk memperbesar">
                                        </a>
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-bold text-primary"><?= esc($row['supplier_name']) ?></div>
                                        <div class="fw-semibold mt-1"><?= esc($row['title']) ?></div>
                                        <?php if ($row['note']): ?>
                                            <div class="small text-muted mt-1 fst-italic">Note:
                                                <?= esc(substr($row['note'], 0, 50)) ?>...
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'APPROVED'): ?>
                                            <span class="status-badge status-approved"><i class="fas fa-check-circle"></i>
                                                Approved</span>
                                        <?php elseif ($row['status'] == 'REJECTED'): ?>
                                            <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i>
                                                Rejected</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending"><i class="fas fa-clock"></i>
                                                Pending</span>
                                        <?php endif; ?>
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
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('admin/banner-supplier/detail/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip"
                                                title="Tinjau Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (can('banner_supplier_update')): ?>
                                                <a href="<?= base_url('admin/banner-supplier/edit/' . $row['id']) ?>"
                                                    class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Data">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can('banner_supplier_delete')): ?>
                                                <button class="btn-action btn-action-delete btn-delete"
                                                    data-id="<?= $row['id'] ?>" data-toggle="tooltip" title="Hapus Banner">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
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
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
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
                "targets": [1, 2, 5]
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

        // Clear search when input is cleared
        $('#searchInput').on('search', function () {
            if (this.value === '') {
                table.search('').draw();
            }
        });

        // Delete handler (Using Event Delegation for DataTables compatibility)
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Banner?',
                text: "Data banner dan file akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var deleteUrl = '<?= base_url('admin/banner-supplier/delete') ?>/' + id;
                    $('#deleteForm').attr('action', deleteUrl).submit();
                }
            });
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    // Flash Messages
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>