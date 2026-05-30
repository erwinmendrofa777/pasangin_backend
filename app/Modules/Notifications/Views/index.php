<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Notifikasi
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Notifikasi
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
        width: 250px;
        height: 44px;
        background: #fdfdff !important;
    }

    .search-wrapper input:focus {
        border-color: #6777ef;
        background: #fff !important;
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.15);
        width: 400px;
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

    /* ===== TARGET BADGES ===== */
    .target-badge {
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-client {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-tukang {
        background: #fef9c3;
        color: #854d0e;
    }

    .badge-supplier {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-all {
        background: #f3f4f6;
        color: #374151;
    }

    /* ===== NOTIF CONTENT ===== */
    .notif-title {
        font-weight: 700;
        color: #212529;
        margin-bottom: 3px;
        display: block;
    }

    .notif-msg {
        font-size: 0.82rem;
        color: #6c757d;
        line-height: 1.4;
    }

    .notif-time {
        font-size: 0.75rem;
        color: #adb5bd;
        font-weight: 500;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HEADER SECTION -->
<div class="card page-header-card mb-2 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="text-primary mb-2 fw-bold">Riwayat Notifikasi</h4>
                <p class="text-muted mb-0 small">Pantau semua notifikasi sistem dan promosi yang telah dikirimkan ke
                    mitra & pelanggan.</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end gap-2 mt-3 mt-md-0">
                <div class="stat-pill shadow-sm">
                    <span>Total Sent</span>
                    <span class="stat-num"><?= number_format($stats['total']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Clients</span>
                    <span class="stat-num"><?= number_format($stats['client']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Tukang</span>
                    <span class="stat-num"><?= number_format($stats['tukang']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Suppliers</span>
                    <span class="stat-num"><?= number_format($stats['supplier']) ?></span>
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
                    <input type="text" class="form-control" id="searchInput"
                        placeholder="Ketik untuk mencari notifikasi">
                </div>
                <?php if (can('notification_create')): ?>
                    <a href="<?= base_url('admin/notification/create') ?>" class="btn btn-primary px-4 py-2 fw-bold"
                        style="border-radius: 12px; box-shadow: 0 4px 12px rgba(103, 119, 239, 0.35); height: 44px; display: flex; align-items: center;">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Notifikasi Baru
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1">
                        <thead>
                            <tr>
                                <th>Waktu Kirim</th>
                                <th>Target</th>
                                <th>Konten Notifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $n): ?>
                                <?php
                                $targetClass = 'badge-all';
                                if ($n['target_type'] == 'client')
                                    $targetClass = 'badge-client';
                                if ($n['target_type'] == 'tukang')
                                    $targetClass = 'badge-tukang';
                                if ($n['target_type'] == 'supplier')
                                    $targetClass = 'badge-supplier';
                                ?>
                                <tr>
                                    <td>
                                        <div class="notif-time">
                                            <i class="far fa-clock me-1"></i>
                                            <?= date('d M Y', strtotime($n['created_at'])) ?>
                                            <?= date('H:i', strtotime($n['created_at'])) ?> WIB
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <!-- Badge Role -->
                                            <span class="target-badge <?= $targetClass ?> mb-1">
                                                <i
                                                    class="fas <?= $n['target_type'] == 'client' ? 'fa-user' : ($n['target_type'] == 'tukang' ? 'fa-tools' : 'fa-store') ?> me-1"></i>
                                                <?= ucfirst($n['target_type']) ?>
                                            </span>

                                            <!-- Badge Scope (Spesifik/Semua) -->
                                            <?php if (!empty($n['target_id'])): ?>
                                                <span class="badge badge-dark"
                                                    style="font-size: 0.65rem; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.3px;">
                                                    <i class="fas fa-user-tag me-1"></i> ID: <?= $n['target_id'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light border text-muted"
                                                    style="font-size: 0.65rem; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.3px;">
                                                    <i class="fas fa-globe me-1"></i> Semua User
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            <?php if (!empty($n['image_url'])): ?>
                                                <img src="<?= esc($n['image_url']) ?>" alt="Banner" class="rounded-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light p-2 rounded-3 text-primary d-flex justify-content-center align-items-center"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-bell"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <span class="notif-title"><?= esc($n['title']) ?></span>
                                                <p class="notif-msg mb-0"><?= esc($n['message']) ?></p>
                                            </div>
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
                "targets": [0, 1]
            }
            ], // Foto dan Aksi tidak bisa di-sort
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [
                [0, "desc"]
            ],
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
            "drawCallback": function () {
                $('.pagination').addClass('pagination-sm');
            }
        });

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Clear search when input is cleared
        $('#searchInput').on('search', function () {
            if (this.value === '') {
                table.search('').draw();
            }
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
        $(document).on('submit', 'form', function () {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
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