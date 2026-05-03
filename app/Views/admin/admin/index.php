<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Admin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Admin
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

    .status-super {
        background: #d1fae5;
        color: #065f46;
    }

    .status-default {
        background: #e0e7ff;
        color: #3730a3;
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

    .btn-action-edit {
        background: #fd7e14;
        color: #e67e22;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
        color: #e67e22;
    }

    .btn-action-delete {
        background: #ff0000ff;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary" style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-user-tie me-2"></i>Daftar Admin
        </h6>
        <div class="d-flex gap-3 align-items-center">
            <div class="search-wrapper" style="width: 250px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, role...">
            </div>
            <?php if (can('admin_create')): ?>
            <a href="<?= base_url('admin/admin/create') ?>" class="btn btn-primary rounded-pill px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Admin
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
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td><span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span></td>
                            <td class="fw-semibold text-start ps-3"><?= esc($row['full_name'] ?: '-') ?></td>
                            <td class="text-muted"><?= esc($row['email'] ?: '-') ?></td>
                            <td>
                                <?php if ($row['role'] === 'super_admin'): ?>
                                    <span class="status-badge status-super"><i class="fas fa-crown"></i> Super Admin</span>
                                <?php else: ?>
                                    <span class="status-badge status-default"><i class="fas fa-user-cog"></i> <?= esc(ucfirst($row['role'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('admin_edit')): ?>
                                    <a href="<?= base_url('admin/admin/edit/' . $row['id']) ?>"
                                        class="btn-action btn-action-edit"
                                        data-toggle="tooltip" title="Edit Admin">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if (can('admin_delete')): ?>
                                    <?php if ($row['id'] != session()->get('user_id') && $row['id'] != 1): ?>
                                        <a href="<?= base_url('admin/admin/delete/' . $row['id']) ?>"
                                            class="btn-action btn-action-delete"
                                            onclick="return confirm('Yakin ingin menghapus admin ini?')"
                                            data-toggle="tooltip" title="Hapus Admin">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php if (!can('admin_edit') && !can('admin_delete')): ?>
                                    <span class="badge badge-light"><i class="fas fa-lock"></i></span>
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
                "targets": [4]
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

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function() {
            if (this.value === '') table.search('').draw();
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?= $this->endSection() ?>