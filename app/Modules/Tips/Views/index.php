<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Konten
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== PAGE HEADER ===== */
    .page-header-card {
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg, #6777ef 0%, #4d5fd1 100%);
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.25);
        overflow: hidden;
        position: relative;
        padding: 28px 32px;
        margin-bottom: 24px;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -70px;
        left: -30px;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    .page-header-card h4 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
        position: relative;
        z-index: 1;
    }

    .page-header-card p {
        color: rgba(255, 255, 255, 0.75);
        margin: 0;
        font-size: 0.88rem;
        position: relative;
        z-index: 1;
    }

    .btn-add-tips {
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 9px 20px;
        transition: all 0.2s;
        backdrop-filter: blur(5px);
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-tips:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    /* ===== STATS ROW ===== */
    .stat-mini-card {
        border: none;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 16px rgba(103, 119, 239, 0.08);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-mini-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .stat-mini-card .stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        color: #2d3748;
    }

    .stat-mini-card .stat-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-top: 2px;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
    }

    .table-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4fa;
        padding: 20px 28px;
        border-radius: 20px 20px 0 0;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 40px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e9ecef;
        font-size: 0.85rem;
        height: 38px;
        transition: all 0.2s;
    }

    .search-wrapper input:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.12);
        outline: none;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f8f7ff;
    }

    #table-1 thead th {
        color: #6777ef;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #ebe8ff;
        padding: 14px 16px;
    }

    #table-1 tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
        border-bottom: 1px solid #f8f9fa;
    }

    #table-1 tbody tr:last-child td {
        border-bottom: none;
    }

    #table-1 tbody tr:hover {
        background: #faf9ff;
    }

    /* ===== TIPS IMAGE ===== */
    .tips-img {
        width: 110px;
        height: 65px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    /* ===== TITLE + EXCERPT ===== */
    .tips-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .tips-excerpt {
        color: #8e94a9;
        font-size: 0.78rem;
        max-width: 280px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 3px;
    }

    /* ===== BADGES ===== */
    .badge-pill {
        border-radius: 50px;
        padding: 5px 12px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
        background: #e8fdf0;
        color: #0a6640;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .btn-action-detail {
        background: #eef0fd;
        color: #6777ef;
    }

    .btn-action-detail:hover {
        background: #6777ef;
        color: #fff;
    }

    .btn-action-delete {
        background: #fff0f0;
        color: #e03131;
    }

    .btn-action-delete:hover {
        background: #e03131;
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

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #8e94a9;
    }

    .empty-state i {
        font-size: 3rem;
        color: #d0d4f5;
        margin-bottom: 16px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== PAGE HEADER ===== -->
<div class="page-header-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4><i class="fas fa-lightbulb me-2" style="opacity:0.85;"></i>Tips & Tricks</h4>
            <p>Kelola konten tips dan artikel yang ditampilkan di aplikasi</p>
        </div>
        <?php if (can('tips_create')): ?>
            <a href="<?= base_url('admin/tips/create') ?>" class="btn-add-tips">
                <i class="fas fa-plus"></i> Tambah Tips Baru
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">
    <?php
    $total = count($tips);
    $aktif = count(array_filter($tips, fn($t) => $t['is_active'] == 1));
    $client = count(array_filter($tips, fn($t) => strtolower($t['target_app']) == 'client'));
    $tukang = count(array_filter($tips, fn($t) => strtolower($t['target_app']) == 'tukang'));
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#eef0fd;">
                <i class="fas fa-layer-group" style="color:#6777ef;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $total ?></div>
                <div class="stat-lbl">Total Tips</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#e8fdf0;">
                <i class="fas fa-check-circle" style="color:#0a6640;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $aktif ?></div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#eff6ff;">
                <i class="fas fa-user" style="color:#1e40af;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $client ?></div>
                <div class="stat-lbl">Untuk Client</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#fff7ed;">
                <i class="fas fa-tools" style="color:#9a3412;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $tukang ?></div>
                <div class="stat-lbl">Untuk Tukang</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="mb-0 fw-800"
            style="color:#6777ef; font-size:0.82rem; letter-spacing:0.5px; text-transform:uppercase;">
            <i class="fas fa-list-ul me-2"></i>Daftar Tips & Tricks
        </h6>
        <div class="search-wrapper" style="width:260px;">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari judul tips...">
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($tips)): ?>
            <div class="empty-state">
                <i class="fas fa-lightbulb d-block"></i>
                <p class="fw-bold mb-1" style="color:#4a5568;">Belum ada data tips</p>
                <p class="small mb-0">Mulai tambah konten tips & artikel untuk pengguna aplikasi.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="table-1">
                    <thead>
                        <tr class="text-center">
                            <th style="width:50px;">No</th>
                            <th style="width:130px;">Visual</th>
                            <th class="text-start">Judul Tips</th>
                            <th>Target</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tips as $key => $row): ?>
                            <tr class="text-center">
                                <td class="fw-bold text-muted" style="font-size:0.8rem;"><?= $key + 1 ?></td>
                                <td>
                                    <a href="<?= base_url('uploads/tips/' . $row['image']) ?>" class="glightbox" data-gallery="tips-gallery" data-title="<?= esc($row['title']) ?>" data-description="Target: <?= esc($row['target_app']) ?>">
                                        <img src="<?= base_url('uploads/tips/' . $row['image']) ?>" class="tips-img"
                                            alt="<?= esc($row['title']) ?>">
                                    </a>
                                </td>
                                <td class="text-start">
                                    <div class="tips-title"><?= esc($row['title']) ?></div>
                                    <div class="tips-excerpt">
                                        <?php
                                        // Ambil teks dari JSON Editor.js jika ada, fallback ke strip_tags
                                        $contentRaw = $row['content'] ?? '';
                                        $decoded = json_decode($contentRaw, true);
                                        if ($decoded && isset($decoded['blocks'])) {
                                            $firstText = '';
                                            foreach ($decoded['blocks'] as $block) {
                                                if (isset($block['data']['text']) && !empty($block['data']['text'])) {
                                                    $firstText = strip_tags($block['data']['text']);
                                                    break;
                                                }
                                            }
                                            echo esc($firstText ?: '—');
                                        } else {
                                            echo esc(strip_tags($contentRaw) ?: '—');
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (strtolower($row['target_app']) == 'tukang'): ?>
                                        <span class="badge-pill bg-tukang"><i class="fas fa-tools"></i> Tukang</span>
                                    <?php else: ?>
                                        <span class="badge-pill bg-client"><i class="fas fa-user"></i> Client</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:0.8rem;">
                                        <?= date('d M Y', strtotime($row['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['is_active'] == 1): ?>
                                        <span class="badge-pill status-active"><i class="fas fa-check-circle"
                                                style="font-size:0.7rem;"></i> Aktif</span>
                                    <?php else: ?>
                                        <span class="badge-pill status-inactive"><i class="fas fa-eye-slash"
                                                style="font-size:0.7rem;"></i> Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if (can('tips')): ?>
                                            <a href="<?= base_url('admin/tips/detail/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (can('tips_delete')): ?>
                                            <a href="<?= base_url('admin/tips/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus tips ini?')">
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
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function () {

        <?php if (!empty($tips)): ?>
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

            // Hubungkan search input custom dengan DataTables search
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
        <?php endif; ?>

        /* ===== Flash Messages ===== */
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