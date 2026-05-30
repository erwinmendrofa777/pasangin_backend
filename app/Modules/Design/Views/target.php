<?php
// Tentukan jumlah hari dari target_date dan start_date
$numDays = 7;
if (!empty($request['start_date']) && !empty($request['target_date'])) {
    $start = new DateTime($request['start_date']);
    $end = new DateTime($request['target_date']);
    $diff = $start->diff($end);
    $numDays = max(1, $diff->days);
}
?>

<style>
    .tbl-outer {
        overflow-x: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    table.table-schedule {
        min-width: 600px;
        margin-bottom: 0;
    }

    table.table-schedule th {
        background: #f8f9fa;
        font-size: 12px;
        font-weight: 600;
        color: #34395e;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
    }

    table.table-schedule td {
        vertical-align: middle;
        font-size: 13px;
    }

    table.table-schedule td.num {
        text-align: center;
        color: #6c757d;
    }

    table.table-schedule .bar {
        height: 14px;
        border-radius: 3px;
        background: #6777ef;
        min-width: 6px;
    }

    table.table-schedule .cell-bar {
        padding: 5px 6px;
        text-align: center;
    }

    table.table-schedule .week-th {
        min-width: 60px;
    }

    table.table-schedule tr.item-row {
        cursor: pointer;
        transition: background 0.15s;
    }

    table.table-schedule tr.item-row:hover {
        background: #eef1ff !important;
    }

    .progress-bar-thin {
        height: 8px;
        border-radius: 4px;
    }
</style>

<!-- ── TABEL TARGET ── -->
<!-- ── TABEL TARGET (DESKTOP) ── -->
<div class="tbl-outer mt-4 g-3 d-none d-md-block shadow-sm">
    <div class="card-header bg-primary text-white border-bottom-0"
        style="border-radius: 10px 10px 0 0; padding: 16px 20px;">
        <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Target Pengerjaan</h6>
    </div>
    <div class="bg-white rounded-bottom" style="overflow-x: auto;">
        <table class="table table-bordered table-sm table-schedule table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:36px;">NO</th>
                    <th class="text-start" style="min-width:220px;">URAIAN PEKERJAAN</th>
                    <th style="width:120px;">STATUS</th>
                    <?php for ($i = 1; $i <= $numDays; $i++): ?>
                        <th class="week-th"><span style="font-size:10px;">HARI <?= $i ?></span></th>
                    <?php endfor; ?>
                    <th style="width:100px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($targets)):
                    foreach ($targets as $idx => $t): ?>
                        <?php
                        $startDay = (int) ($t['start_week'] ?? 0);
                        $endDay = (int) ($t['end_week'] ?? 0);

                        $statusClr = 'secondary';
                        if ($t['status'] === 'DONE') {
                            $statusClr = 'success';
                        } elseif ($t['status'] === 'ON PROGRESS') {
                            $statusClr = 'primary';
                        } elseif ($t['status'] === 'PENDING') {
                            $statusClr = 'warning';
                        }
                        ?>
                        <tr class="item-row text-center">
                            <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                            <td class="text-start ps-2">
                                <span class="fw-semibold" style="font-size:13px;"><?= esc($t['task_name']) ?></span>
                                <div class="mt-1 d-flex align-items-center"
                                    style="font-size:11px;color:#6777ef;font-weight:600;"
                                    title="Dikerjakan oleh: <?= esc($t['admin_name'] ?? 'Sistem') ?>">
                                    <i class="fas fa-user-tie me-1"></i>
                                    <?= esc(strlen($t['admin_name'] ?? 'Sistem') > 15 ? substr($t['admin_name'] ?? 'Sistem', 0, 15) . '...' : ($t['admin_name'] ?? 'Sistem')) ?>
                                </div>
                                <?php if (!empty($t['keterangan'])): ?>
                                    <small class="text-muted d-block mt-1"
                                        style="font-size:10px;"><?= esc($t['keterangan']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle;">
                                <form action="<?= base_url('admin/design/update-target-progress/' . $t['id']) ?>" method="post"
                                    style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="keterangan" value="<?= esc($t['keterangan'] ?? '') ?>">
                                    <div class="m-1" style="display: inline-block; position: relative;">
                                        <select name="status" onchange="this.form.submit()"
                                            class="bg-<?= $statusClr ?> text-white fw-bold shadow-none"
                                            style="appearance: none; -webkit-appearance: none; padding: 4px 24px 4px 12px; font-size: 11px; border: none; border-radius: 30px; cursor: pointer; outline: none; width: auto; text-align: center; display: inline-block;">
                                            <option value="PENDING" <?= $t['status'] === 'PENDING' ? 'selected' : '' ?>
                                                style="background: #fff; color: #34395e; font-weight: normal;">PENDING</option>
                                            <option value="ON PROGRESS" <?= $t['status'] === 'ON PROGRESS' ? 'selected' : '' ?>
                                                style="background: #fff; color: #34395e; font-weight: normal;">ON PROGRESS
                                            </option>
                                            <option value="DONE" <?= $t['status'] === 'DONE' ? 'selected' : '' ?>
                                                style="background: #fff; color: #34395e; font-weight: normal;">DONE</option>
                                        </select>
                                        <i class="fas fa-chevron-down text-white"
                                            style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); font-size: 10px; pointer-events: none;"></i>
                                    </div>
                                </form>
                            </td>
                            <?php for ($d = 1; $d <= $numDays; $d++): ?>
                                <?php $isActive = ($startDay > 0 && $d >= $startDay && $d <= $endDay); ?>
                                <td class="cell-bar">
                                    <?php if ($isActive): ?>
                                        <div class="bar"></div>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                            <td class="text-center">
                                <a href="<?= base_url('admin/design/delete-target/' . $t['id'] . '/' . $request['id']) ?>"
                                    class="btn btn-xs btn-outline-danger" onclick="return confirm('Hapus target ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="<?= 4 + $numDays ?>" class="text-center text-muted py-4">Belum ada target. Tambahkan
                            target
                            di bawah.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ── CARD TARGET (MOBILE) ── -->
<div class="d-block d-md-none mt-4">
    <div class="d-flex align-items-center mb-3">
        <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-tasks me-2"></i>Target Pengerjaan</h6>
    </div>

    <div class="d-flex flex-column gap-3">
        <?php if (!empty($targets)):
            foreach ($targets as $idx => $t):
                $statusClr = 'secondary';
                if ($t['status'] === 'DONE')
                    $statusClr = 'success';
                elseif ($t['status'] === 'ON PROGRESS')
                    $statusClr = 'primary';
                elseif ($t['status'] === 'PENDING')
                    $statusClr = 'warning';
                ?>
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
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
                                <select name="status" onchange="this.form.submit()"
                                    class="badge bg-<?= $statusClr ?> border-0 py-2 px-3 shadow-sm"
                                    style="appearance: none; outline: none; border-radius: 20px;">
                                    <option value="PENDING" <?= $t['status'] === 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                    <option value="ON PROGRESS" <?= $t['status'] === 'ON PROGRESS' ? 'selected' : '' ?>>ON PROGRESS
                                    </option>
                                    <option value="DONE" <?= $t['status'] === 'DONE' ? 'selected' : '' ?>>DONE</option>
                                </select>
                            </form>
                        </div>

                        <div class="d-flex align-items-center mb-3"
                            style="font-size: 0.8rem; color: #6777ef; font-weight: 600;">
                            <i class="fas fa-user-tie me-2"></i> PJ: <?= esc($t['admin_name'] ?? 'Sistem') ?>
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
                                <div class="text-white fw-bold bg-primary px-3 py-1 rounded-pill d-inline-block text-center"
                                    style="font-size: 0.8rem; width: fit-content;">
                                    <i class="fas fa-stopwatch me-1"></i> Durasi: <?= $targetDuration ?> Hari
                                </div>
                                <?php if ($targetStartDateStr): ?>
                                    <div class="text-muted mt-1" style="font-size: 0.8rem;">
                                        <i class="fas fa-calendar-alt me-1"></i> <?= $targetStartDateStr ?> s/d
                                        <?= $targetEndDateStr ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= base_url('admin/design/delete-target/' . $t['id'] . '/' . $request['id']) ?>"
                                class="btn btn-sm btn-outline-danger py-1 px-2" style="border-radius: 8px;"
                                onclick="return confirm('Hapus target ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
            <div class="text-center p-4 bg-white shadow-sm" style="border-radius: 12px;">
                <i class="fas fa-tasks text-muted mb-2" style="font-size: 2rem; opacity: 0.5;"></i>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada target.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ── FORM TAMBAH TARGET ── -->
<div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
    <div class="card-body p-4">
        <p class="fw-bold mb-3" style="font-size:0.95rem;"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah
            Target Baru</p>
        <form action="<?= base_url('admin/design/create-target/' . $request['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">NAMA PEKERJAAN
                        / TUGAS</label>
                    <input type="text" name="task_name" class="form-control form-control-sm py-2 px-3"
                        placeholder="Contoh: Desain Logo" style="border-radius: 8px;" required>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">ADMIN
                        PELAKSANA</label>
                    <select name="user_admin_id" class="form-control form-control-sm" required
                        style="height: 40px;border-radius:8px;font-size:13px;">
                        <option value="">— Pilih Admin —</option>
                        <?php foreach ($admin_users ?? [] as $au): ?>
                            <option value="<?= $au['id'] ?>">
                                <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">MULAI (HARI
                        KE-)</label>
                    <input type="number" name="start_week" class="form-control form-control-sm py-2 px-3 text-center"
                        min="1" value="1" style="border-radius: 8px;" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">SELESAI (HARI
                        KE-)</label>
                    <input type="number" name="end_week" class="form-control form-control-sm py-2 px-3 text-center"
                        min="1" value="2" style="border-radius: 8px;" required>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 ladda-button" data-style="zoom-in"
                        style="border-radius: 8px;">
                        <span class="ladda-label"><i class="fas fa-save me-1"></i> Tambah Target</span>
                    </button>
                </div>
            </div>
        </form>

        <hr class="my-4" style="border-style: dashed; border-color: #dee2e6; opacity: 1;">

        <!-- Pengaturan Jadwal Proyek -->
        <p class="fw-bold mb-3" style="font-size:0.95rem;color:#495057;"><i
                class="fas fa-calendar-alt text-primary me-2"></i>Pengaturan Rentang Jadwal Proyek</p>
        <form action="<?= base_url('admin/design/update-progress/' . $request['id']) ?>" method="post"
            class="row g-3 align-items-end">
            <?= csrf_field() ?>
            <input type="hidden" name="progress_percent" value="<?= $request['progress_percent'] ?? 0 ?>">
            <input type="hidden" name="status" value="<?= $request['status'] ?? 'PENDING' ?>">
            <div class="col-12 col-md-3">
                <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">TANGGAL
                    MULAI</label>
                <input type="date" name="start_date" id="start_date_input"
                    class="form-control form-control-sm py-2 px-3" value="<?= esc($request['start_date'] ?? '') ?>"
                    style="border-radius: 8px;" required>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">TARGET
                    SELESAI</label>
                <input type="date" name="target_date" id="target_date_input"
                    class="form-control form-control-sm py-2 px-3" value="<?= esc($request['target_date'] ?? '') ?>"
                    style="border-radius: 8px;" required>
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
            <div class="col-12 col-md-3">
                <label class="form-label mb-2" style="font-size:12px;font-weight:600;color:#6c757d;">TOTAL HARI</label>
                <input type="text" id="total_hari_input"
                    class="form-control form-control-sm py-2 fw-bold bg-light text-primary text-center"
                    value="<?= $duration ?> Hari" style="border-radius: 8px; border: 1px dashed #ced4da;" readonly>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 8px;">
                    <i class="fas fa-check-circle me-1"></i> Simpan Jadwal
                </button>
            </div>
        </form>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const startInput = document.getElementById('start_date_input');
                const targetInput = document.getElementById('target_date_input');
                const totalInput = document.getElementById('total_hari_input');

                function calculateDays() {
                    if (startInput.value && targetInput.value) {
                        const start = new Date(startInput.value);
                        const end = new Date(targetInput.value);
                        if (end >= start) {
                            const diffTime = Math.abs(end - start);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            totalInput.value = diffDays + " Hari";
                        } else {
                            totalInput.value = "0 Hari";
                        }
                    }
                }

                startInput.addEventListener('change', calculateDays);
                targetInput.addEventListener('change', calculateDays);
            });
        </script>
    </div>
</div>