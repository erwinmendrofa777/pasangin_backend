<!-- TABLE SECTION -->
<div class="card shadow-sm table-card">
    <!-- Card Header: Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center px-4 py-3 gap-3"
        style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary"
            style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-list me-2"></i>Daftar Proyek Konstruksi
        </h6>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('admin/construction/export-pdf') ?>" class="btn-export-pdf" target="_blank">
                <i class="fas fa-file-pdf"></i> Export Laporan
            </a>
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput"
                    placeholder="Cari pelanggan, lokasi, atau status...">
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th class="text-start">Pelanggan</th>
                        <th class="text-start">Informasi Lokasi</th>
                        <th class="text-center">Estimasi Pengerjaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                    <?= esc($row['full_name'] ?: '-') ?>
                                </div>
                                <div class="small text-muted"><i class="fas fa-phone-alt me-1"
                                        style="font-size: 0.75rem;"></i> <?= esc($row['phone'] ?: '-') ?></div>
                            </td>
                            <td class="text-start">
                                <div class="fw-semibold text-primary small">Luas: <?= $row['land_area'] ?> m²</div>
                                <div class="text-muted small" style="max-width: 250px; line-height: 1.4;">
                                    <?= esc(strlen($row['address']) > 40 ? substr($row['address'], 0, 40) . '...' : $row['address']) ?>
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
                                    echo '<span class="text-muted italic small">Belum diatur</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'];
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
                                <?php if (can('construction_detail')): ?>
                                    <a href="<?= base_url('admin/construction/detail/' . $row['id']) ?>"
                                        class="btn-action btn-kelola" data-toggle="tooltip" title="Kelola Proyek">
                                        <i class="fas fa-tools"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak ada akses</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
