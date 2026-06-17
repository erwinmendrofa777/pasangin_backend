<!-- ===== TABLE CARD: MANAGERIAL TUGAS ===== -->
<div class="card table-card">

    <!-- Card Header: Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-tasks me-2"></i>Daftar Tugas Proyek Desain
        </h6>
    </div>

    <div class="card-body">
        <div class="table-responsive p-4">
            <table class="table table-hover" id="table-managerial" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width:50px;">No</th>
                        <th class="text-center">Nama Tugas</th>
                        <th class="text-center">Proyek / Konsep</th>
                        <th class="text-center">Klien</th>
                        <th class="text-center">Desainer</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($designerTasks)): ?>
                        <?php foreach ($designerTasks as $key => $task): ?>
                            <?php
                            $tStatus = $task['status'];
                            $tColor = 'secondary';
                            $sIcon = 'fas fa-circle';

                            if ($tStatus === 'PENDING') {
                                $tStatus = 'BELUM DIKERJAKAN';
                                $tColor = 'status-default';
                                $sIcon = 'fas fa-clock';
                            } elseif ($tStatus === 'ON PROGRESS') {
                                $tStatus = 'SEDANG DIPROSES';
                                $tColor = 'status-payment';
                                $sIcon = 'fas fa-spinner fa-spin-hover';
                            } elseif ($tStatus === 'DONE') {
                                $tStatus = 'SELESAI (TANPA FILE)';
                                $tColor = 'status-survey';
                                $sIcon = 'fas fa-check-circle';
                            }

                            if ($task['total_designs'] > 0) {
                                if ($task['approved_designs'] > 0) {
                                    $tStatus = 'DISETUJUI';
                                    $tColor = 'status-completed';
                                    $sIcon = 'fas fa-check-double';
                                } elseif ($task['pending_designs'] > 0) {
                                    $tStatus = 'TINJAUAN';
                                    $tColor = 'status-payment';
                                    $sIcon = 'fas fa-hourglass-half';
                                } else {
                                    $tStatus = 'PERLU REVISI';
                                    $tColor = 'status-cancelled';
                                    $sIcon = 'fas fa-exclamation-triangle';
                                }
                            }
                            ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($task['task_name']) ?></span>
                                </td>
                                <td class="fw-semibold text-primary">
                                    <?= esc($task['design_concept'] ?? 'Proyek Khusus') ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= esc($task['client_name'] ?? 'Klien Internal') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size:0.8rem;">
                                        <i class="fas fa-user-tie me-1 text-primary"></i> <?= esc($task['designer_name'] ?? 'Sistem / Belum Ditugaskan') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size:0.85rem;">
                                        Hari <?= esc($task['start_week']) ?> &ndash; <?= esc($task['end_week']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?= $tColor ?>">
                                        <i class="<?= $sIcon ?>"></i> <?= esc($tStatus) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail Proyek">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada tugas proyek desain aktif saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
