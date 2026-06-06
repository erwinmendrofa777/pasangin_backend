<?php if (in_array(strtolower(session()->get('role') ?? ''), ['kepala divisi desain', 'drafter', 'arsitek'])): ?>
    <?php
    $designerTasks = array_filter($requests, function ($p) {
        return $p['status'] === 'DESIGNING';
    });
    $dStats = $stats['designer'] ?? [
        'queue_designing' => count($designerTasks),
        'queue_survey' => 0,
        'my_designs_total' => 0,
        'my_projects_total' => 0,
        'impact_renovation' => 0,
        'impact_completed' => 0,
    ];
    ?>
    <!-- ===== STATS CARDS ===== -->
    <div class="row mb-0">
        <!-- Card Antrean Pekerjaan -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
                style="background: linear-gradient(135deg, var(--palette-primary) 0%, #0dcaf0 100%); color: #fff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1"
                            style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Antrean Desain</h6>
                        <h3 class="mb-0 fw-bold"><?= number_format($dStats['queue_designing']) ?> <span
                                style="font-size: 0.95rem; font-weight: normal;">Siap Dikerjakan</span></h3>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between" style="font-size: 0.8rem;">
                    <span class="text-white-75"><i class="fas fa-clipboard-list me-1"></i><?= $dStats['queue_survey'] ?> Menunggu Survei</span>
                </div>
            </div>
        </div>

        <!-- Card Kontribusi Desain -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
                style="background: linear-gradient(135deg, #6f42c1 0%, #a881af 100%); color: #fff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1"
                            style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Kontribusi Saya</h6>
                        <h3 class="mb-0 fw-bold"><?= number_format($dStats['my_designs_total']) ?> <span
                                style="font-size: 0.95rem; font-weight: normal;">File Desain</span></h3>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;">
                        <i class="fas fa-pencil-ruler fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between" style="font-size: 0.8rem;">
                    <span class="text-white-75"><i class="fas fa-project-diagram me-1"></i>Tersebar di <?= $dStats['my_projects_total'] ?> Proyek</span>
                </div>
            </div>
        </div>

        <!-- Card Dampak Renovasi -->
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
                style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: #fff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1"
                            style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Dampak Renovasi</h6>
                        <h3 class="mb-0 fw-bold"><?= number_format($dStats['impact_renovation'] + $dStats['impact_completed']) ?> <span
                                style="font-size: 0.95rem; font-weight: normal;">Proyek</span></h3>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                        style="width: 52px; height: 52px;">
                        <i class="fas fa-hard-hat fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between" style="font-size: 0.8rem;">
                    <span class="text-white-75"><i class="fas fa-tools me-1"></i><?= $dStats['impact_renovation'] ?> Sedang Dikerjakan</span>
                    <span class="text-white-75"><i class="fas fa-check-double me-1"></i><?= $dStats['impact_completed'] ?> Selesai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== DAFTAR TUGAS SAYA ===== -->
    <div class="card border-0 mb-4" style="border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04);">
        <!-- Card Header -->
        <div class="card-header border-0 d-flex align-items-center"
            style="background: #ffffff; padding: 20px 24px; border-radius: 20px 20px 0 0; border-bottom: 1px solid rgba(0,0,0,0.03) !important;">
            <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-2 me-3 d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="fas fa-clipboard-check fa-lg"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px; font-size: 1.2rem;">Daftar Tugas Saya</h5>
                <p class="text-muted mb-0" style="font-size: 0.85rem; letter-spacing: 0.2px;">Monitor proyek renovasi yang menunggu desain</p>
            </div>
        </div>

        <div class="card-body" style="background: #fffafa; border-radius: 0 0 20px 20px; padding: 24px;">
            <?php if (!empty($designerTasks)): ?>
                <!-- Custom styles for elegant task cards -->
                <style>
                    .task-scroll-container::-webkit-scrollbar {
                        height: 8px;
                    }

                    .task-scroll-container::-webkit-scrollbar-track {
                        background: rgba(0, 0, 0, 0.02);
                        border-radius: 10px;
                    }

                    .task-scroll-container::-webkit-scrollbar-thumb {
                        background: rgba(0, 0, 0, 0.15);
                        border-radius: 10px;
                    }

                    .task-scroll-container::-webkit-scrollbar-thumb:hover {
                        background: rgba(0, 0, 0, 0.25);
                        border-radius: 10px;
                    }

                    .task-card {
                        border-radius: 12px;
                        transition: all 0.3s ease;
                        border: 1px solid #edf2f7;
                        background: #ffffff;
                        position: relative; /* Pastikan untuk stretched-link */
                    }

                    .task-card:hover {
                        transform: translateY(-4px);
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
                        border-color: #e2e8f0;
                    }

                    .btn-minimal {
                        width: 36px;
                        height: 36px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f8f9fa;
                        border-radius: 50%;
                        transition: all 0.2s;
                        color: var(--palette-primary);
                    }

                    .task-card:hover .btn-minimal {
                        background: var(--palette-primary);
                        color: #ffffff;
                        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25) !important;
                    }
                </style>

                <!-- Horizontal Scroll Container -->
                <div class="d-flex flex-nowrap gap-4 pb-3 pt-2 px-1 task-scroll-container"
                    style="overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scroll-behavior: smooth;">
                    <?php foreach ($designerTasks as $task): ?>
                        <div style="min-width: 300px; width: 300px; flex: 0 0 auto;">
                            <div class="card h-100 shadow-sm task-card">
                                <div class="card-body p-4 d-flex flex-column">

                                    <!-- Header: Status + Date -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                                            <?= date('d M Y', strtotime($task['created_at'])) ?>
                                        </span>
                                        <span class="badge text-white bg-warning rounded-pill px-2 py-1"
                                            style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                                            MENUNGGU DESAIN
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="fw-bold text-dark mb-4"
                                        style="font-size: 1.1rem; line-height: 1.4; white-space: normal;">
                                        Proyek Renovasi #<?= esc($task['id']) ?></h5>

                                    <!-- Simple Info -->
                                    <div class="mb-4 flex-grow-1">
                                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-light">
                                            <div class="text-muted" style="font-size: 0.8rem;">Lokasi</div>
                                            <div class="fw-semibold text-dark text-end"
                                                style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                                                <?= esc(strlen($task['address']) > 40 ? substr($task['address'], 0, 40) . '...' : $task['address']) ?></div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-muted" style="font-size: 0.8rem;">Klien</div>
                                            <div class="fw-semibold text-dark text-end"
                                                style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                                                <?= esc($task['client_name'] ?? '-') ?></div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div
                                        class="mt-auto pt-1 border-top border-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-dark fw-bold" style="font-size: 0.85rem;">
                                                Detail Tugas <i class="fas fa-chevron-right ml-1" style="font-size:0.7rem;"></i>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('admin/renovation/detail/' . $task['id']) ?>#desain"
                                            class="btn-minimal shadow-sm text-decoration-none stretched-link">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-white shadow-sm"
                    style="border-radius: 16px; border: 1px dashed #e2e8f0; margin: 0 4px;">
                    <div class="text-muted mb-3"><i class="fas fa-check-circle opacity-25 text-success"
                            style="font-size: 3.5rem;"></i></div>
                    <h5 class="text-dark fw-bold mb-2">Semua Tugas Selesai!</h5>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Saat ini Anda tidak memiliki proyek renovasi yang menunggu desain.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- TABLE SECTION -->
<div class="card shadow-sm table-card animate-up" style="animation-delay: 0.1s;">
    <!-- Card Header: Search -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3 table-header-controls"
        style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary"
            style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-list me-2"></i>Daftar Proyek Renovasi
        </h6>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('admin/renovation/export-pdf') ?>" class="btn-export-pdf" target="_blank">
                <i class="fas fa-file-pdf"></i> Export Laporan
            </a>
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control shadow-sm" id="searchInput"
                    placeholder="Cari pelanggan, tipe, atau status...">
            </div>
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
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                    <?= esc($row['client_name'] ?? '-') ?>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-phone-alt text-primary me-1"
                                        style="font-size: 0.75rem;"></i> <?= esc($row['phone_number'] ?? '-') ?></div>
                            </td>
                            <td class="text-center">
                                <div class="fw-semibold text-primary small"><?= esc($row['renovation_type'] ?? '-') ?></div>
                                <div class="text-muted small">
                                    <i
                                        class="far fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($row['created_at'])) ?>
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
                                    'PENDING' => ['class' => 'st-pending', 'icon' => 'fas fa-clock', 'label' => 'Menunggu'],
                                    'SURVEY' => ['class' => 'st-survey', 'icon' => 'fas fa-map-marker-alt', 'label' => 'Survey'],
                                    'DESIGNING' => ['class' => 'st-designing', 'icon' => 'fas fa-pencil-ruler', 'label' => 'Desain'],
                                    'RAB' => ['class' => 'st-rab', 'icon' => 'fas fa-file-invoice', 'label' => 'RAB'],
                                    'CONSTRUCTION' => ['class' => 'st-construction', 'icon' => 'fas fa-hard-hat', 'label' => 'Konstruksi'],
                                    'COMPLETED' => ['class' => 'st-completed', 'icon' => 'fas fa-check-circle', 'label' => 'Selesai'],
                                    'CANCELLED' => ['class' => 'st-cancelled', 'icon' => 'fas fa-times-circle', 'label' => 'Batal'],
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
                                        class="btn-action btn-kelola" data-toggle="tooltip" title="Kelola Proyek">
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
