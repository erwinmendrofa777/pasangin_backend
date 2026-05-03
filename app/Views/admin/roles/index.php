<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Role
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Role
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

    /* ===== ROLE ICON ===== */
    .role-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .role-icon-super {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
        box-shadow: 0 3px 8px rgba(13, 110, 253, 0.3);
    }

    .role-icon-custom {
        background: #e7f3ff;
        color: #0d6efd;
        border: 2px solid #dce8ff;
    }

    /* ===== PERMISSION BADGES ===== */
    .perm-pill {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin: 2px;
        white-space: nowrap;
    }

    .pill-parent {
        background: #e7f3ff;
        color: #0a58ca;
        border: 1px solid #cce5ff;
    }

    .pill-action {
        background: #f1f3f5;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.67rem;
        border: 1px solid #e9ecef;
    }

    .pill-full {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
        border: none;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.2);
        font-size: 0.73rem;
        padding: 4px 12px;
    }

    .pill-more {
        background: #dee2e6;
        color: #495057;
        font-size: 0.67rem;
        padding: 3px 9px;
        border-radius: 20px;
        font-weight: 600;
        margin: 2px;
        display: inline-flex;
        align-items: center;
    }

    /* ===== ROLE TYPE BADGE ===== */
    .role-type-badge {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 9px;
        border-radius: 20px;
    }

    .role-type-super {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
    }

    .role-type-custom {
        background: #e7f3ff;
        color: #0d6efd;
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
        background: #fff4e6;
        color: #fd7e14;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-delete {
        background: #fff5f5;
        color: #fa5252;
    }

    .btn-action-delete:hover {
        background: #fa5252;
        color: #fff;
    }

    .btn-action-lock {
        background: #f1f3f5;
        color: #adb5bd;
        cursor: not-allowed;
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
            <i class="fas fa-user-shield me-2"></i>Daftar Role
        </h6>
        <div class="d-flex gap-3 align-items-center">
            <div class="search-wrapper" style="width: 260px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama role...">
            </div>
            <?php if (can('roles_create')): ?>
                <a href="<?= base_url('admin/roles/create') ?>"
                    class="btn btn-primary rounded-pill px-3 mx-3 shadow-sm d-flex align-items-center gap-2"
                    style="font-size:0.85rem; white-space:nowrap;">
                    <i class="fas fa-plus"></i> Tambah Role
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">No</th>
                        <th style="width:22%">Nama Role</th>
                        <th style="width:53%">Hak Akses</th>
                        <th class="text-center" style="width:10%">Jumlah</th>
                        <th class="text-center" style="width:10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $key => $row):
                        $isSuper = strtolower($row['role_name']) === 'super_admin';
                        $perms   = json_decode($row['permissions'], true) ?? [];
                        $shown   = array_slice($perms, 0, 4);
                        $more    = count($perms) - count($shown);
                    ?>
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="role-icon-wrap <?= $isSuper ? 'role-icon-super' : 'role-icon-custom' ?>">
                                        <i class="fas <?= $isSuper ? 'fa-crown' : 'fa-user-tag' ?>"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size:0.9rem;">
                                            <?= esc(ucwords(str_replace('_', ' ', $row['role_name']))) ?>
                                        </div>
                                        <span class="role-type-badge <?= $isSuper ? 'role-type-super' : 'role-type-custom' ?>">
                                            <?= $isSuper ? 'Sistem Utama' : 'Custom Role' ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($isSuper): ?>
                                    <span class="perm-pill pill-full">
                                        <i class="fas fa-infinity"></i> Full Access — Semua izin aktif
                                    </span>
                                <?php elseif (!empty($shown)): ?>
                                    <?php foreach ($shown as $p):
                                        $isParent = !str_contains($p, '_');
                                    ?>
                                        <span class="perm-pill <?= $isParent ? 'pill-parent' : 'pill-action' ?>">
                                            <i class="fas <?= $isParent ? 'fa-folder' : 'fa-check' ?>" style="font-size:0.55rem;"></i>
                                            <?= esc($p) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($more > 0): ?>
                                        <span class="pill-more">+<?= $more ?> lagi</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <em class="text-muted" style="font-size:0.8rem;">Tidak ada izin</em>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isSuper): ?>
                                    <span class="badge rounded-pill" style="background:#d1fae5; color:#065f46; font-size:0.75rem; padding:5px 12px;">
                                        ∞ Semua
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background:#e7f3ff; color:#0a58ca; font-size:0.75rem; padding:5px 12px;">
                                        <?= count($perms) ?> izin
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('roles_edit')): ?>
                                        <a href="<?= base_url('admin/roles/edit/' . $row['id']) ?>"
                                            class="btn-action btn-action-edit"
                                            data-toggle="tooltip" title="Edit Role">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($isSuper): ?>
                                        <div class="btn-action btn-action-lock" data-toggle="tooltip" title="Role Sistem (Terkunci)">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    <?php elseif (can('roles_delete')): ?>
                                        <a href="<?= base_url('admin/roles/delete/' . $row['id']) ?>"
                                            class="btn-action btn-action-delete"
                                            onclick="return confirm('Yakin hapus role \'<?= esc($row['role_name']) ?>\'?\nAdmin yang menggunakan role ini akan kehilangan akses.')"
                                            data-toggle="tooltip" title="Hapus Role">
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
        <div class="dt-footer px-4 py-3 d-flex justify-content-between align-items-center border-top">
            <div id="table-info-custom" class="text-muted" style="font-size: 0.85rem;"></div>
            <div id="table-pagination-custom"></div>
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
            message: '<?= strip_tags(session()->getFlashdata('error')) ?>',
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
                "emptyTable": "Tidak ada role yang tersedia",
                "zeroRecords": "Role tidak ditemukan"
            },
            "columnDefs": [{
                "sortable": false,
                "targets": [2, 3, 4]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"d-none"ip>', // Hide original footer
            "drawCallback": function() {
                // Pindahkan konten asli DataTables ke custom div yang ada di bawah table-responsive
                $('#table-info-custom').html($('#table-1_info').html());
                $('#table-pagination-custom').html($('#table-1_paginate').html());

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