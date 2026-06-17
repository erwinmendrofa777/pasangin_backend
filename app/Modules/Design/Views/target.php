<?php
// Tentukan jumlah hari dari target_date dan start_date
$numDays = 7;
if (!empty($request['start_date']) && !empty($request['target_date'])) {
    $start = new DateTime($request['start_date']);
    $end = new DateTime($request['target_date']);
    $diff = $start->diff($end);
    $numDays = max(1, $diff->days);
}
$timelineWidth = max(450, $numDays * 70);
?>

<!-- ── TABEL TARGET ── -->
<!-- ── TABEL TARGET (DESKTOP) ── -->
<div class="tbl-outer mt-4 g-3 d-none d-md-block">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-white"><i class="fas fa-tasks me-2"></i>Target Pengerjaan</h6>
        <div class="d-flex gap-2 align-items-center">
            <button class="btn btn-header-action btn-header-schedule d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modalEditMaxRevision"
                title="Edit Max Revision">
                <i class="fas fa-redo-alt"></i>
                <span>Max Revisi</span>
                <span class="badge rounded-pill ms-1"
                    style="background:#fbbf24; color:#1e293b; font-size:0.78rem; font-weight:700; min-width:22px;">
                    <?= (int) ($request['max_revision'] ?? 0) ?>
                </span>
            </button>
            <button class="btn btn-header-action btn-header-schedule" data-bs-toggle="modal"
                data-bs-target="#modalEditSchedule">
                <i class="fas fa-calendar-alt"></i> Jadwal Proyek
            </button>
            <button class="btn btn-header-action btn-header-add" data-bs-toggle="modal"
                data-bs-target="#modalAddTarget">
                <i class="fas fa-plus"></i> Tambah Target
            </button>
        </div>
    </div>
    <div class="bg-white rounded-bottom" style="overflow-x: auto;">
        <table class="table table-bordered table-sm table-schedule table-hover mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="position: relative; min-width: <?= $timelineWidth ?>px; padding: 0;">
                        <div class="w-100 h-100 d-flex align-items-center" style="min-height: 42px;">
                            <div class="d-flex w-100 h-100 position-relative">
                                <?php for ($i = 1; $i <= $numDays; $i++): ?>
                                    <?php
                                    $colLabel = "HARI " . $i;
                                    if (!empty($request['start_date'])) {
                                        $dObj = new DateTime($request['start_date']);
                                        if ($i > 1) {
                                            $dObj->modify('+' . ($i - 1) . ' days');
                                        }
                                        $colLabel = $dObj->format('d M');
                                    }
                                    ?>
                                    <div class="flex-grow-1 text-center position-relative d-flex align-items-center justify-content-center"
                                        style="font-size: 10px; font-weight: 700; border-right: 1px solid rgba(233, 236, 239, 0.7); min-width: 0; flex-basis: 0;"
                                        title="Hari <?= $i ?>">
                                        <span><?= $colLabel ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($targets)):
                    foreach ($targets as $idx => $t): ?>
                        <?php
                        $startDay = (int) ($t['start_week'] ?? 0);
                        $endDay = (int) ($t['end_week'] ?? 0);
                        ?>
                        <tr class="item-row text-center">
                            <td class="timeline-column" style="min-width: <?= $timelineWidth ?>px;">
                                <div class="timeline-grid-lines d-flex w-100 h-100 position-absolute" style="top: 0; left: 0;">
                                    <?php for ($g = 1; $g <= $numDays; $g++): ?>
                                        <div class="flex-grow-1 h-100"
                                            style="border-right: 1px solid rgba(233, 236, 239, 0.7); flex-basis: 0;"></div>
                                    <?php endfor; ?>
                                </div>
                                <?php
                                if ($startDay > 0 && $endDay >= $startDay) {
                                    $left = (($startDay - 1) / $numDays) * 100;
                                    $width = ((($endDay - $startDay) + 1) / $numDays) * 100;

                                    $dateTooltip = "Hari $startDay s/d $endDay";
                                    if (!empty($request['start_date'])) {
                                        $projStart = new DateTime($request['start_date']);

                                        $tStart = clone $projStart;
                                        if ($startDay > 1) {
                                            $tStart->modify('+' . ($startDay - 1) . ' days');
                                        }

                                        $tEnd = clone $projStart;
                                        if ($endDay > 1) {
                                            $tEnd->modify('+' . ($endDay - 1) . ' days');
                                        }
                                        $dateTooltip .= " (" . $tStart->format('d M Y') . " - " . $tEnd->format('d M Y') . ")";
                                    }

                                    $fullTooltip = esc($t['task_name']) . " | PJ: " . esc($t['admin_name'] ?? 'Sistem') . " (" . esc($dateTooltip) . ") - Klik untuk ubah status";

                                    $barClass = 'bar-progress';
                                    if ($t['status'] === 'PENDING') {
                                        $barClass = 'bar-pending';
                                    } elseif ($t['status'] === 'DONE') {
                                        $barClass = 'bar-done';
                                    }
                                    ?>
                                    <div class="gantt-bar-wrapper" style="left: <?= $left ?>%; width: <?= $width ?>%;">

                                        <!-- Sleek Hover Actions Menu -->
                                        <div class="gantt-actions-menu" onclick="event.stopPropagation();">
                                            <button type="button" class="btn-gantt-menu-item edit" data-bs-toggle="modal"
                                                data-bs-target="#modalStatus-<?= $t['id'] ?>" title="Ubah Status">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('admin/design/delete-target/' . $t['id'] . '/' . $request['id']) ?>"
                                                class="btn-gantt-menu-item delete"
                                                onclick="event.stopPropagation(); return confirm('Hapus target ini?')"
                                                title="Hapus Target">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>

                                        <div class="gantt-bar <?= $barClass ?>" data-bs-toggle="modal"
                                            data-bs-target="#modalStatus-<?= $t['id'] ?>">
                                            <span class="gantt-bar-text"><?= esc($t['task_name']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Status Target #<?= $t['id'] ?> -->
                                    <div class="modal fade modal-premium" id="modalStatus-<?= $t['id'] ?>" tabindex="-1"
                                        aria-labelledby="modalStatusLabel-<?= $t['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modalStatusLabel-<?= $t['id'] ?>">
                                                        <i class="fas fa-edit me-2"></i>Ubah Status Target
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="<?= base_url('admin/design/update-target-progress/' . $t['id']) ?>"
                                                    method="post">
                                                    <div class="modal-body">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="keterangan"
                                                            value="<?= esc($t['keterangan'] ?? '') ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Target</label>
                                                            <div class="fw-semibold text-dark text-wrap"
                                                                style="font-size: 13px; line-height: 1.3;">
                                                                <?= esc($t['task_name']) ?></div>
                                                        </div>
                                                        <div class="mb-1">
                                                            <label class="form-label">Status Pengerjaan</label>
                                                            <select name="status" class="form-select">
                                                                <option value="PENDING" <?= $t['status'] === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                                <option value="ON PROGRESS" <?= $t['status'] === 'ON PROGRESS' ? 'selected' : '' ?>>ON PROGRESS</option>
                                                                <option value="DONE" <?= $t['status'] === 'DONE' ? 'selected' : '' ?>>
                                                                    DONE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-cancel"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-submit">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td>
                            <div class="target-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Target</h6>
                                <p class="text-muted mb-0" style="font-size: 12px;">Tambahkan target pengerjaan proyek Anda
                                    terlebih dahulu.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ── TABEL TARGET (MOBILE) ── -->
<div class="d-block d-md-none mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-tasks me-2"></i>Target Pengerjaan</h6>
        <div class="d-flex gap-2 align-items-center">
            <button class="btn btn-sm d-flex align-items-center gap-1 px-2 shadow-sm"
                style="background:#f1f5f9; border:1.5px solid #cbd5e1; color:#334155; border-radius:20px; font-size:0.78rem; font-weight:600;"
                data-bs-toggle="modal" data-bs-target="#modalEditMaxRevision"
                title="Edit Max Revision">
                <i class="fas fa-redo-alt" style="font-size:0.72rem;"></i>
                <span class="badge rounded-pill ms-1"
                    style="background:#fbbf24; color:#1e293b; font-size:0.75rem; font-weight:700; min-width:20px;">
                    <?= (int) ($request['max_revision'] ?? 0) ?>
                </span>
            </button>
            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalEditSchedule">
                <i class="fas fa-calendar-alt me-1"></i>Jadwal
            </button>
            <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAddTarget">
                <i class="fas fa-plus me-1"></i>Target
            </button>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        <?php if (!empty($targets)):
            foreach ($targets as $idx => $t): ?>
                <?php
                $accentClass = '';
                $badgeClass = 'badge-progress';
                $svgFill = 'ff4c4c';
                if ($t['status'] === 'PENDING') {
                    $badgeClass = 'badge-pending';
                    $svgFill = 'f59e0b';
                    $accentClass = 'accent-pending';
                } elseif ($t['status'] === 'DONE') {
                    $badgeClass = 'badge-done';
                    $svgFill = '22c55e';
                    $accentClass = 'accent-done';
                }
                ?>
                <div class="target-card shadow-sm">
                    <div class="card-accent <?= $accentClass ?>"></div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div style="padding-right: 10px;">
                                <span class="badge bg-light text-dark border me-1">#<?= $idx + 1 ?></span>
                                <span class="fw-bold text-dark"
                                    style="font-size: 0.95rem; line-height: 1.3;"><?= esc($t['task_name']) ?></span>
                            </div>
                            <form action="<?= base_url('admin/design/update-target-progress/' . $t['id']) ?>" method="post"
                                style="margin:0;">
                                <?= csrf_field() ?>
                                <select name="status" onchange="this.form.submit()" class="status-badge <?= $badgeClass ?>"
                                    style="appearance: none; -webkit-appearance: none; outline: none; border: none; padding-right: 18px; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23<?= $svgFill ?>%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 6px center; background-size: 8px auto;">
                                    <option value="PENDING" <?= $t['status'] === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                    <option value="ON PROGRESS" <?= $t['status'] === 'ON PROGRESS' ? 'selected' : '' ?>>ON PROGRESS
                                    </option>
                                    <option value="DONE" <?= $t['status'] === 'DONE' ? 'selected' : '' ?>>DONE</option>
                                </select>
                            </form>
                        </div>

                        <div class="d-flex align-items-center mb-3 text-secondary" style="font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-user-tie me-2 text-muted"></i>PJ: <?= esc($t['admin_name'] ?? 'Sistem') ?>
                        </div>

                        <?php if (!empty($t['keterangan'])): ?>
                            <p class="text-muted mb-3" style="font-size: 0.8rem; line-height: 1.4;"><?= esc($t['keterangan']) ?></p>
                        <?php endif; ?>

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3"
                            style="border-top: 1px dashed #dee2e6;">
                            <div class="d-flex flex-column gap-1">
                                <?php
                                $targetDuration = max(1, ($t['end_week'] - $t['start_week']) + 1);
                                $targetStartDateStr = '';
                                $targetEndDateStr = '';

                                if (!empty($request['start_date'])) {
                                    $projStart = new DateTime($request['start_date']);

                                    $tStart = clone $projStart;
                                    if ($t['start_week'] > 1) {
                                        $tStart->modify('+' . ($t['start_week'] - 1) . ' days');
                                    }

                                    $tEnd = clone $projStart;
                                    if ($t['end_week'] > 1) {
                                        $tEnd->modify('+' . ($t['end_week'] - 1) . ' days');
                                    }

                                    $targetStartDateStr = $tStart->format('d M Y');
                                    $targetEndDateStr = $tEnd->format('d M Y');
                                }
                                ?>
                                <div class="badge bg-light text-primary border px-3 py-1.5 rounded-pill d-inline-block text-center fw-bold"
                                    style="font-size: 0.75rem; width: fit-content;">
                                    <i class="fas fa-stopwatch me-1"></i> Durasi: <?= $targetDuration ?> Hari
                                </div>
                                <?php if ($targetStartDateStr): ?>
                                    <div class="date-chip mt-2">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span><?= $targetStartDateStr ?> s/d <?= $targetEndDateStr ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= base_url('admin/design/delete-target/' . $t['id'] . '/' . $request['id']) ?>"
                                class="btn-gantt-delete" onclick="return confirm('Hapus target ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
            <div class="target-empty-state bg-white shadow-sm" style="border-radius: 12px;">
                <div class="empty-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Target</h6>
                <p class="text-muted mb-0" style="font-size: 12px;">Tambahkan target pengerjaan proyek Anda terlebih dahulu.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah Target Baru -->
<div class="modal fade modal-premium" id="modalAddTarget" tabindex="-1" aria-labelledby="modalAddTargetLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddTargetLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Target Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/design/create-target/' . $request['id']) ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Pekerjaan / Tugas</label>
                        <input type="text" name="task_name" class="form-control" placeholder="Contoh: Desain Logo"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Pelaksana</label>
                        <select name="user_admin_id" class="form-select">
                            <option value="">— Pilih Admin —</option>
                            <?php foreach ($admin_users ?? [] as $au): ?>
                                <?php
                                $role = strtolower($au['role'] ?? '');
                                if (!in_array($role, ['kepala divisi desain', 'arsitek', 'drafter'])) continue;
                                ?>
                                <option value="<?= $au['id'] ?>">
                                    <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Mulai (Hari Ke-)</label>
                                <input type="number" name="start_week" class="form-control text-center" min="1"
                                    value="1" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Selesai (Hari Ke-)</label>
                                <input type="number" name="end_week" class="form-control text-center" min="1" value="2"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan Target</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal Proyek -->
<div class="modal fade modal-premium" id="modalEditSchedule" tabindex="-1" aria-labelledby="modalEditScheduleLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditScheduleLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Pengaturan Rentang Jadwal Proyek
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/design/update-progress/' . $request['id']) ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="progress_percent" value="<?= $request['progress_percent'] ?? 0 ?>">
                    <input type="hidden" name="status" value="<?= $request['status'] ?? 'PENDING' ?>">

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date_input" class="form-control"
                            value="<?= esc($request['start_date'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target Selesai</label>
                        <input type="date" name="target_date" id="target_date_input" class="form-control"
                            value="<?= esc($request['target_date'] ?? '') ?>" required>
                    </div>

                    <?php
                    $duration = 0;
                    if (!empty($request['start_date']) && !empty($request['target_date'])) {
                        $start = new DateTime($request['start_date']);
                        $end = new DateTime($request['target_date']);
                        $diff = $start->diff($end);
                        $duration = $diff->days;
                    }
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Total Durasi</label>
                        <input type="text" id="total_hari_input" class="form-control duration-display"
                            value="<?= $duration ?> Hari" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Max Revision -->
<div class="modal fade modal-premium" id="modalEditMaxRevision" tabindex="-1"
    aria-labelledby="modalEditMaxRevisionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditMaxRevisionLabel">
                    <i class="fas fa-redo-alt me-2"></i>Edit Maksimal Revisi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/design/update-progress/' . $request['id']) ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="progress_percent" value="<?= $request['progress_percent'] ?? 0 ?>">
                    <input type="hidden" name="status" value="<?= $request['status'] ?? 'PENDING' ?>">
                    <input type="hidden" name="start_date" value="<?= esc($request['start_date'] ?? '') ?>">
                    <input type="hidden" name="target_date" value="<?= esc($request['target_date'] ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-redo-alt me-1 text-warning"></i>Maksimal Revisi
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="number" name="max_revision" id="maxRevisionInput"
                                class="form-control text-center fw-bold fs-5"
                                style="max-width: 120px; border-radius: 10px;"
                                min="0" value="<?= (int) ($request['max_revision'] ?? 0) ?>" required>
                            <span class="text-muted" style="font-size: 0.85rem;">kali revisi</span>
                        </div>
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            Jumlah maksimal revisi yang diperbolehkan untuk proyek ini.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>