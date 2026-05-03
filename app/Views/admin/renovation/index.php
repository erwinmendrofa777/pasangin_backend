<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Proyek Renovasi
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Renovasi
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

    .search-icon {
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
        width: 280px;
    }

    .search-wrapper input:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 4px rgba(103, 119, 239, 0.1);
        width: 350px;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f4ff;
    }

    #table-1 thead th {
        color: #6777ef;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody td {
        padding: 16px 12px;
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

    .st-pending {
        background: #fff9db;
        color: #f1c40f;
    }

    .st-survey {
        background: #e0f2fe;
        color: #0369a1;
    }

    .st-designing {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .st-rab {
        background: #ecfdf5;
        color: #059669;
    }

    .st-construction {
        background: #eff6ff;
        color: #2563eb;
    }

    .st-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .st-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-kelola {
        background: #6777ef;
        color: #fff;
    }

    @media (max-width: 768px) {
        .table-header-controls {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }
        .search-wrapper {
            width: 100%;
        }
        .search-wrapper input {
            width: 100% !important;
        }
    }

    .animate-up {
        animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="page-header-card p-4 mb-4 animate-up">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4 class="mb-1 font-weight-bold" style="color: #34395e;">Manajemen Renovasi</h4>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Pantau dan kelola semua permintaan renovasi pelanggan secara efisien.
            </p>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <div class="stat-pill">
                <span>Total Permintaan</span>
                <span class="stat-num"><?= count($requests) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- TABLE SECTION -->
<div class="card shadow-sm table-card animate-up" style="animation-delay: 0.1s;">
    <!-- Card Header: Search -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3 table-header-controls" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold" style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase; color: #6777ef;">
            <i class="fas fa-list me-2"></i>Daftar Proyek Renovasi
        </h6>
        <div class="search-wrapper d-flex align-items-center gap-2">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control shadow-sm" id="searchInput" placeholder="Cari pelanggan, tipe, atau status...">
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover text-nowrap" id="table-1">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th class="text-start">Pelanggan</th>
                        <th class="text-center">Tipe Renovasi</th>
                        <th class="text-center">Estimasi Pengerjaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= esc($row['client_name'] ?? '-') ?></div>
                                <div class="small text-muted mt-1"><i class="fas fa-phone-alt text-primary me-1" style="font-size: 0.75rem;"></i> <?= esc($row['phone_number'] ?? '-') ?></div>
                            </td>
                            <td class="text-center">
                                <div class="fw-semibold text-primary small"><?= esc($row['renovation_type'] ?? '-') ?></div>
                                <div class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($row['created_at'])) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                if (!empty($row['start_date']) && !empty($row['week'])) {
                                    $start = new DateTime($row['start_date']);
                                    $end = clone $start;
                                    $end->modify('+' . $row['week'] . ' weeks');

                                    echo '<div class="fw-bold text-dark">' . $start->format('d M') . ' - ' . $end->format('d M Y') . '</div>';
                                    echo '<div class="badge bg-light text-primary fw-bold" style="font-size: 0.65rem;">' . $row['week'] . ' MINGGU</div>';
                                } else {
                                    echo '<span class="badge bg-light text-muted border border-secondary border-opacity-25 shadow-sm" style="font-size: 0.7rem; font-weight: 500;">Belum diatur</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $status = strtoupper($row['status']);
                                $statusMap = [
                                    'PENDING'      => ['class' => 'st-pending',      'icon' => 'fas fa-clock',          'label' => 'Menunggu'],
                                    'SURVEY'       => ['class' => 'st-survey',       'icon' => 'fas fa-map-marker-alt', 'label' => 'Survey'],
                                    'DESIGNING'    => ['class' => 'st-designing',    'icon' => 'fas fa-pencil-ruler',   'label' => 'Desain'],
                                    'RAB'          => ['class' => 'st-rab',          'icon' => 'fas fa-file-invoice',   'label' => 'RAB'],
                                    'CONSTRUCTION' => ['class' => 'st-construction', 'icon' => 'fas fa-hard-hat',      'label' => 'Konstruksi'],
                                    'COMPLETED'    => ['class' => 'st-completed',    'icon' => 'fas fa-check-circle',   'label' => 'Selesai'],
                                    'CANCELLED'    => ['class' => 'st-cancelled',    'icon' => 'fas fa-times-circle',   'label' => 'Batal'],
                                ];
                                $s = $statusMap[$status] ?? ['class' => 'badge-secondary', 'icon' => 'fas fa-info-circle', 'label' => $status];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>">
                                    <i class="<?= $s['icon'] ?> me-1"></i><?= $s['label'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if (can('renovation_detail')): ?>
                                    <a href="<?= base_url('admin/renovation/detail/' . $row['id']) ?>"
                                        class="btn-action btn-kelola"
                                        data-toggle="tooltip" title="Kelola Proyek">
                                        <i class="fas fa-tools"></i>
                                    </a>
                                <?php endif; ?>
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
                "lengthMenu": "_MENU_",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "<i class='fas fa-chevron-right'></i>",
                    "previous": "<i class='fas fa-chevron-left'></i>"
                },
                "emptyTable": "Tidak ada data yang tersedia",
                "zeroRecords": "Tidak ada data yang cocok ditemukan"
            },
            "pageLength": 10,
            "dom": 'rt<"d-flex justify-content-between p-4"ip>',
            "drawCallback": function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

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
    });
</script>
<?= $this->endSection() ?>