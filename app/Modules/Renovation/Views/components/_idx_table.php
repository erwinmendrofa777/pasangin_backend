<!-- TABLE SECTION -->
<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover text-nowrap" id="table-1" style="width:100%">
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
                                <div class="small text-muted mt-1"><i class="fas fa-phone-alt me-1"
                                        style="font-size: 0.75rem; color: #e53935;"></i> <?= esc($row['phone_number'] ?? '-') ?></div>
                            </td>
                            <td class="text-center">
                                <div class="fw-semibold small" style="color: #e53935;"><?= esc($row['renovation_type'] ?? '-') ?></div>
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
                                    echo '<div class="badge bg-light fw-bold" style="font-size: 0.65rem; color: #e53935 !important;">' . $row['week'] . ' MINGGU</div>';
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
