<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Promo Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Promo
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(103, 119, 239, 0.05);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(103, 119, 239, 0.03);
        border-radius: 50%;
    }

    /* ===== STAT PILLS ===== */
    .stat-pill {
        background: #f0f4ff;
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #4b49ac;
        font-weight: 700;
        border: 1px solid #e0e6ff;
    }

    .stat-pill .stat-num {
        background: #6777ef;
        color: #fff;
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
        border-radius: 50px !important;
        border: 1.5px solid #e4e6fc;
        transition: all 0.3s ease;
        font-size: 0.88rem;
        width: 280px;
        height: 44px;
        background: #fdfdff !important;
    }

    .search-wrapper input:focus {
        border-color: #6777ef;
        background: #fff !important;
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.15);
        width: 350px;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
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
        background: #f8f9ff;
    }

    #table-1 thead th {
        color: #6777ef;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #eef0ff;
        border-top: none;
        padding: 14px 12px;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #fcfcff !important;
    }

    #table-1 tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-color: #f1f3f9;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== PROMO IMAGE ===== */
    .promo-thumb {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        object-position: center;
        border: 2px solid #eef0ff;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
    }

    /* ===== BADGES ===== */
    .badge-status {
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .discount-pill {
        background: #fff0f0;
        color: #e03131;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid #ffc9c9;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HEADER SECTION -->
<div class="card page-header-card mb-2 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="text-primary mb-2 fw-bold">Manajemen Promo</h4>
                <p class="text-muted mb-0 small">Kelola berbagai penawaran diskon dan kode promo khusus dari supplier.</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end gap-2 mt-3 mt-md-0">
                <div class="stat-pill shadow-sm">
                    <span>Total Promo</span>
                    <span class="stat-num"><?= number_format($stats['total']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Aktif</span>
                    <span class="stat-num"><?= number_format($stats['active']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Non-Aktif</span>
                    <span class="stat-num"><?= number_format($stats['inactive']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">

        <!-- TABLE CARD -->
        <div class="card shadow-sm table-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3 px-4">
                <div class="search-wrapper">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Ketik untuk mencari promo...">
                </div>
                <!-- Tombol tambah jika diperlukan di masa depan -->
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 100px;">Gambar</th>
                                <th>Info Promo</th>
                                <th>Supplier</th>
                                <th class="text-center">Potongan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promos as $key => $row): ?>
                                <tr>
                                    <td class="text-center text-muted fw-bold"><?= $key + 1 ?></td>
                                    <td>
                                        <?php
                                        $photoUrl = !empty($row['photo'])
                                            ? (strpos($row['photo'], 'http') === 0 ? $row['photo'] : base_url('uploads/promos/' . $row['photo']))
                                            : base_url('assets/img/news/img01.jpg'); // Placeholder
                                        ?>
                                        <img src="<?= $photoUrl ?>" class="promo-thumb" alt="promo">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($row['title']) ?></div>
                                        <div class="small text-primary fw-bold mt-1">
                                            <i class="fas fa-ticket-alt me-1"></i><?= esc($row['promo_code']) ?>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-calendar-alt me-1"></i><?= date('d M', strtotime($row['start_date'])) ?> - <?= date('d M Y', strtotime($row['end_date'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light p-2 rounded-circle text-primary" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                                <i class="fas fa-store"></i>
                                            </div>
                                            <span class="fw-600"><?= esc($row['supplier_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="discount-pill">
                                            <?php if ($row['discount_type'] == "fixed"): ?>
                                                -Rp<?= number_format($row['discount_value'], 0, ',', '.') ?>
                                            <?php else: ?>
                                                -<?= number_format($row['discount_value'], 0, ',', '.') ?>%
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status <?= $row['status'] == 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                            <?= $row['status'] == 'active' ? 'Aktif' : 'Non-Aktif' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (can('promo')): ?>
                                        <a href="<?= base_url('admin/promo/detail/' . $row['id']) ?>" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; padding: 6px 12px;" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye me-1"></i> Detail
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
    $(document).ready(function() {
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "_MENU_",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ promo",
                "paginate": {
                    "next": "<i class='fas fa-chevron-right'></i>",
                    "previous": "<i class='fas fa-chevron-left'></i>"
                }
            },
            "pageLength": 10,
            "ordering": true,
            "order": [
                [0, "asc"]
            ],
            "dom": 'rt<"d-flex justify-content-between p-4"ip>',
            "drawCallback": function() {
                $('.pagination').addClass('pagination-sm');
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        <?php if (session()->getFlashdata('success')): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil!',
                message: '<?= session()->getFlashdata('success') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>