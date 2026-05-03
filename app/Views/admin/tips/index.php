<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Tips
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

    /* ===== TIPS IMAGE ===== */
    .tips-img {
        width: 120px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    /* ===== BADGES ===== */
    .badge-pill {
        border-radius: 50px;
        padding: 5px 12px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
    }

    .bg-tukang {
        background: #fff7ed;
        color: #9a3412;
    }

    .bg-client {
        background: #eff6ff;
        color: #1e40af;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #374151;
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

    .btn-delete {
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .dt-footer {
        padding: 14px 20px;
        background: #fafcff;
        border-top: 1px solid #f0f4fa;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary" style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-lightbulb me-2"></i>Daftar Tips & Tricks
        </h6>
        <div class="d-flex gap-3">
            <div class="search-wrapper" style="width: 280px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari judul tips...">
            </div>
            <?php if (can('tips_create')): ?>
            <a href="<?= base_url('admin/tips/create') ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3" style="border-radius: 12px; font-weight: 600;">
                <i class="fas fa-plus"></i> Tambah Tips
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
                        <th>Visual</th>
                        <th class="text-start">Judul Tips</th>
                        <th>Target App</th>
                        <th>Dibuat Pada</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tips as $key => $row): ?>
                        <tr class="text-center">
                            <td class="fw-bold text-muted"><?= $key + 1 ?></td>
                            <td>
                                <img src="<?= base_url('uploads/tips/' . $row['image']) ?>" class="tips-img" alt="Tips Image">
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($row['title']) ?></div>
                                <div class="text-muted small text-truncate" style="max-width: 300px;">
                                    <?= strip_tags($row['content']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if (strtolower($row['target_app']) == 'tukang'): ?>
                                    <span class="badge-pill bg-tukang"><i class="fas fa-tools me-1"></i> Tukang</span>
                                <?php else: ?>
                                    <span class="badge-pill bg-client"><i class="fas fa-user me-1"></i> Client</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i> <?= date('d M Y', strtotime($row['created_at'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($row['is_active'] == 1): ?>
                                    <span class="badge-pill status-active"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="badge-pill status-inactive"><i class="fas fa-times-circle me-1"></i> Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('tips')): ?>
                                    <a href="<?= base_url('admin/tips/detail/' . $row['id']) ?>"
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
    $(document).ready(function() {
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "columnDefs": [{
                "sortable": false,
                "targets": [1, 6]
            }],
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

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