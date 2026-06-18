<?php
// ========================
// DATA PREPARATION (PHP)
// ========================
$numWeeks = (int) ($construction['week'] ?? 8);
$workday = (int) ($construction['workday'] ?? 7);
$startDate = $construction['start_date'] ?? null;

// Helper: hitung label minggu ke-$i
function schedWeekLabel(int $i, ?string $startDate, int $workday): string
{
    if (!$startDate)
        return 'MG ' . $i;
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    $d = new \DateTime($startDate);

    // Setiap "minggu" timeline selalu berjarak 7 hari kalender secara berurutan.
    // Majukan start date ke awal minggu ke-$i
    $d->modify('+' . (($i - 1) * 7) . ' days');

    // Tanggal berakhirnya kerja pada minggu tersebut adalah start date + (workday - 1).
    // Sisa hari hingga genap 7 hari dianggap sebagai hari libur.
    $e = (clone $d)->modify('+' . ($workday - 1) . ' days');

    return $d->format('j') . ' ' . $months[(int) $d->format('n') - 1]
        . ' – '
        . $e->format('j') . ' ' . $months[(int) $e->format('n') - 1];
}

// Group $rab by group_name → sub_group_name → items[]
$grouped = [];
foreach ($rab ?? [] as $r) {
    $g = $r['group_name'] ?? '';
    $sg = $r['sub_group_name'] ?? '';
    $grouped[$g][$sg][] = $r;
}

// Index target_list by id_construction_rabs untuk lookup start_week & end_week
$targetByRabId = [];
$targetByAddendumId = [];
foreach ($target_list ?? [] as $t) {
    if (!empty($t['id_construction_rabs'])) {
        $targetByRabId[$t['id_construction_rabs']] = $t;
    }
    if (!empty($t['id_construction_addendum'])) {
        $targetByAddendumId[$t['id_construction_addendum']] = $t;
    }
}

// Group $addendum
$groupedAddendum = [];
foreach ($addendum ?? [] as $r) {
    $g = $r['group_name'] ?? '';
    $sg = $r['sub_group_name'] ?? '';
    $groupedAddendum[$g][$sg][] = $r;
}

$constructionId = $construction['id'] ?? '';
$totalCols = 9 + $numWeeks; // Sesuaikan dengan jumlah kolom data yang baru
$totalBobot = 100;
$totalHarga = 0;
foreach ($rab ?? [] as $r) {
    $totalHarga += $r['total_price'] ?? 0;
}

$totalHargaAddendum = 0;
foreach ($addendum ?? [] as $r) {
    $totalHargaAddendum += $r['total_price'] ?? 0;
}

// Ambil progress realisasi
$db = \Config\Database::connect();
$progressData = [];
if ($constructionId) {
    $progressData = $db->table('construction_progress')
        ->select('id_construction_targets, SUM(volume) as total_progress')
        ->where('construction_id', $constructionId)
        ->where('status', 'APPROVED')
        ->groupBy('id_construction_targets')
        ->get()->getResultArray();
}
$progressByTargetId = [];
foreach ($progressData as $pd) {
    $progressByTargetId[$pd['id_construction_targets']] = (float) $pd['total_progress'];
}

$totalVolumeAll = 0;
$totalRealisasiAll = 0;
$totalSelisihAll = 0;
$totalHargaRealisasiAll = 0;
$totalSelisihHargaAll = 0;

$totalVolumeAddendumAll = 0;
$totalRealisasiAddendumAll = 0;
$totalSelisihAddendumAll = 0;
$totalHargaRealisasiAddendumAll = 0;
$totalSelisihHargaAddendumAll = 0;

?>



<div class="py-3">
    <div class="card header-card" style="margin-bottom: 16px;">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                            <i class="fas fa-calendar-alt" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Target Pekerjaan RAB</h5>
                            <p class="text-muted mb-0 small">Atur jadwal pelaksanaan dan penugasan tukang untuk setiap
                                pekerjaan RAB.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <button type="button" class="btn-adm btn-adm-primary" data-bs-toggle="modal"
                        data-bs-target="#modalAturJadwal">
                        <i class="fas fa-calendar-check me-1"></i> Atur Jadwal Proyek
                    </button>
                </div>
            </div>
        </div>
    </div>
    <p class="mobile-scroll-hint"><i class="fas fa-hand-point-right"></i> Geser kanan untuk lihat detail Gantt &amp;
        Harga</p>
    <div class="tbl-outer">
        <table id="mainTable" class="table table-sm table-schedule table-hover mt-0 pt-0">
            <thead>
                <tr>
                    <th class="left fw-bold" style="width:36px;">NO</th>
                    <th class="left fw-bold text-center" style="min-width:320px;">URAIAN PEKERJAAN</th>
                    <th class="px-2 fw-bold" style="min-width:180px;">LOWONGAN &amp; PELAMAR</th>
                    <th class="px-2 fw-bold" style="min-width:150px;">JUMLAH HARGA</th>
                    <th class="px-2 fw-bold" style="width:120px;">VOLUME</th>
                    <th class="px-2 fw-bold" style="width:120px;">REALISASI VOLUME</th>
                    <th class="px-2 fw-bold" style="width:120px;">SELISIH VOLUME</th>
                    <th class="px-2 fw-bold" style="width:250px;">JUMLAH HARGA REALISASI</th>
                    <th class="px-2 fw-bold" style="width:250px;">SELISIH JUMLAH HARGA</th>
                    <?php for ($i = 1; $i <= $numWeeks; $i++): ?>
                        <th class="week-th text-center" style="vertical-align: middle; padding: 8px 4px; min-width: 80px;">
                            <div class="fw-bold text-primary mb-1" style="font-size: 11px; letter-spacing: 0.5px;">MG
                                <?= $i ?></div>
                            <div class="text-muted fw-normal" style="font-size: 9px; line-height: 1.1;">
                                <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                        </th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grouped as $group => $subgroups): ?>
                    <?php
                    $groupHash = 'group-' . md5($group);
                    ?>
                    <?php /* GROUP HEADER */ ?>
                    <tr class="group-header" onclick="toggleGroup('<?= $groupHash ?>', this)">
                        <td colspan="<?= $totalCols ?>">
                            <div class="d-flex align-items-center w-100">
                                <i class="fas fa-chevron-down me-2 group-chevron"></i>
                                <i class="fas fa-layer-group me-2 opacity-75"></i>
                                <span class="fw-bold"><?= esc($group) ?></span>
                            </div>
                        </td>
                    </tr>

                    <?php foreach ($subgroups as $subgroup => $items): ?>
                        <?php if ($subgroup !== ''): ?>
                            <?php /* SUBGROUP HEADER */ ?>
                            <tr class="subgroup-header <?= $groupHash ?>">
                                <td colspan="<?= $totalCols ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-caret-right me-2 opacity-50"></i>
                                        <span><?= esc($subgroup) ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($items as $idx => $item): ?>
                            <?php
                            $actName = $item['activity_name'] ?? '';
                            $rabItemId = $item['id'] ?? 0;
                            $grpName = $item['group_name'] ?? '';
                            $sgName = $item['sub_group_name'] ?? '';
                            $total = (float) ($item['total_price'] ?? 0);
                            $tgt = $targetByRabId[$rabItemId] ?? null;
                            $targetId = $tgt['id'] ?? null;
                            $startWeek = (int) ($tgt['start_week'] ?? 0);
                            $endWeek = (int) ($tgt['end_week'] ?? 0);
                            $idJobApps = $tgt['id_job_applications'] ?? '';

                            // Kalkulasi Volume
                            $volume = (float) ($item['volume'] ?? 0);
                            $unit = $item['unit'] ?? '';
                            $realisasi = $targetId ? ($progressByTargetId[$targetId] ?? 0) : 0;
                            $selisih = $volume - $realisasi;

                            // Kalkulasi Harga
                            $hargaRealisasi = $realisasi * (float) ($item['current_unit_price'] ?? 0);
                            $hargaSelisih = $total - $hargaRealisasi;

                            $indent = $subgroup !== '' ? '28px' : '14px';

                            // Update total keseluruhan
                            $totalVolumeAll += $volume;
                            $totalRealisasiAll += $realisasi;
                            $totalSelisihAll += $selisih;
                            $totalHargaRealisasiAll += $hargaRealisasi;
                            $totalSelisihHargaAll += $hargaSelisih;
                            ?>
                            <tr class="item-row text-center <?= $groupHash ?>" data-rab-id="<?= $rabItemId ?>"
                                data-group="<?= esc($grpName) ?>" data-subgroup="<?= esc($sgName) ?>"
                                data-activity="<?= esc($actName) ?>" data-volume="<?= number_format($volume, 2, '.', '') ?>"
                                data-job-apps="<?= esc($idJobApps) ?>" data-target-id="<?= $targetId ?? '' ?>"
                                onclick="selectRow(this)">
                                <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                                <td class="text-start" style="padding-left:<?= $indent ?>;">
                                    <?= esc($actName) ?>
                                    <?php if ($startWeek > 0): ?>
                                        <span class="week-badge-mobile">MG <?= $startWeek ?>–<?= $endWeek ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($targetId): ?>
                                        <?php
                                        $job = $jobs_by_target[$targetId] ?? null;
                                        $jobApplicants = $applicants_by_target[$targetId] ?? [];
                                        $numApplicants = count($jobApplicants);
                                        ?>
                                        <?php if ($job): ?>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-indigo btn-adm-action"
                                                    onclick="openLihatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>')"
                                                    style="font-size: 11px; padding: 3px 8px; background-color: #4f46e5; color: #fff; border: none; border-radius: 6px;">
                                                    <i class="fas fa-users me-1"></i> Pelamar
                                                    <span class="badge bg-white text-indigo rounded-pill ms-1"
                                                        style="font-size: 9px; padding: 2px 5px; color: #4f46e5 !important;"><?= $numApplicants ?></span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-adm-action"
                                                    onclick="openBuatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>', <?= (float) ($item['ahsp_tenaga_kerja_total'] ?? 0) ?>, <?= (float) $volume ?>)"
                                                    style="font-size: 11px; padding: 3px 6px; border-radius: 6px;" title="Edit Lowongan">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-success btn-adm-action"
                                                onclick="openBuatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>', <?= (float) ($item['ahsp_tenaga_kerja_total'] ?? 0) ?>, <?= (float) $volume ?>)"
                                                style="font-size: 11px; padding: 3px 8px; border-radius: 6px; background-color: #28a745; border-color: #28a745; color: #fff;">
                                                <i class="fas fa-plus me-1"></i> Buat Loker
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small" style="font-size: 10px; font-style: italic;">Atur target
                                            dulu</span>
                                    <?php endif; ?>
                                </td>
                                <td>Rp <?= number_format($total) ?></td>
                                <td class="num"><?= number_format($volume, 2) ?></td>
                                <td class="num text-success"><?= number_format($realisasi, 2) ?></td>
                                <td class="num <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($selisih, 2) ?>
                                </td>
                                <td>Rp <?= number_format($hargaRealisasi) ?></td>
                                <td class="<?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                                    <?= number_format($hargaSelisih) ?>
                                </td>
                                <?php for ($w = 1; $w <= $numWeeks; $w++): ?>
                                    <?php
                                    $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                                    $barClass = '';
                                    if ($isActive) {
                                        if ($startWeek == $endWeek) {
                                            $barClass = 'bar-single';
                                        } elseif ($w == $startWeek) {
                                            $barClass = 'bar-start';
                                        } elseif ($w == $endWeek) {
                                            $barClass = 'bar-end';
                                        } else {
                                            $barClass = 'bar-middle';
                                        }
                                    }
                                    ?>
                                    <td
                                        class="cell-bar <?= $isActive ? ' week-active' : '' ?> <?= $isActive ? 'cell-' . $barClass : '' ?>">
                                        <?php if ($isActive): ?>
                                            <div class="bar <?= $barClass ?>"
                                                onclick="showProgressList(event, <?= $targetId ?? 0 ?>, '<?= esc($actName) ?>')"></div>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <tr style="background:#f8f9fa;font-weight:500;" class="total-row">
                    <td colspan="3" class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
                        TOTAL</td>
                    <td class="text-center">Rp <?= number_format($totalHarga) ?></td>
                    <td class="num fw-bold" style="font-weight:bold;"><?= number_format($totalVolumeAll, 2) ?></td>
                    <td class="num fw-bold text-success"><?= number_format($totalRealisasiAll, 2) ?></td>
                    <td class="num fw-bold <?= $totalSelisihAll > 0 ? 'text-danger' : 'text-success' ?>">
                        <?= number_format($totalSelisihAll, 2) ?></td>
                    <td class="text-center">Rp <?= number_format($totalHargaRealisasiAll) ?></td>
                    <td class="text-center <?= $totalSelisihHargaAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
                        <?= number_format($totalSelisihHargaAll) ?>
                    </td>
                    <td colspan="<?= $numWeeks ?>"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if (!empty($groupedAddendum)): ?>
        <div class="section-title text-primary">TARGET PEKERJAAN ADDENDUM</div>
        <div class="tbl-outer">
            <table id="addendumTable" class="table table-sm table-schedule table-hover">
                <thead>
                    <tr>
                        <th class="left fw-bold" style="width:36px;">NO</th>
                        <th class="left fw-bold text-center" style="min-width:320px;">URAIAN PEKERJAAN ADDENDUM</th>
                        <th class="px-2 fw-bold" style="min-width:180px;">LOWONGAN &amp; PELAMAR</th>
                        <th class="px-2 fw-bold" style="min-width:150px;">JUMLAH HARGA</th>
                        <th class="px-2 fw-bold" style="width:120px;">VOLUME</th>
                        <th class="px-2 fw-bold" style="width:120px;">REALISASI VOLUME</th>
                        <th class="px-2 fw-bold" style="width:120px;">SELISIH VOLUME</th>
                        <th class="px-2 fw-bold" style="width:250px;">JUMLAH HARGA REALISASI</th>
                        <th class="px-2 fw-bold" style="width:250px;">SELISIH JUMLAH HARGA</th>
                        <?php for ($i = 1; $i <= $numWeeks; $i++): ?>
                            <th class="week-th text-center" style="vertical-align: middle; padding: 8px 4px; min-width: 80px;">
                                <div class="fw-bold text-primary mb-1" style="font-size: 11px; letter-spacing: 0.5px;">MG
                                    <?= $i ?></div>
                                <div class="text-muted fw-normal" style="font-size: 9px; line-height: 1.1;">
                                    <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                            </th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedAddendum as $group => $subgroups): ?>
                        <?php
                        $groupHashAdd = 'group-add-' . md5($group);
                        ?>
                        <?php /* GROUP HEADER */ ?>
                        <tr class="group-header" onclick="toggleGroup('<?= $groupHashAdd ?>', this)">
                            <td colspan="<?= $totalCols ?>">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chevron-down me-2 group-chevron"></i>
                                    <i class="fas fa-plus-circle me-2 opacity-75"></i>
                                    <span>ADD - <?= esc($group) ?></span>
                                </div>
                            </td>
                        </tr>

                        <?php foreach ($subgroups as $subgroup => $items): ?>
                            <?php if ($subgroup !== ''): ?>
                                <?php /* SUBGROUP HEADER */ ?>
                                <tr class="subgroup-header <?= $groupHashAdd ?>">
                                    <td colspan="<?= $totalCols ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-caret-right me-2 opacity-50"></i>
                                            <span><?= esc($subgroup) ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($items as $idx => $item): ?>
                                <?php
                                $actName = $item['activity_name'] ?? '';
                                $addId = $item['id'] ?? 0;
                                $grpName = $item['group_name'] ?? '';
                                $sgName = $item['sub_group_name'] ?? '';
                                $total = (float) ($item['total_price'] ?? 0);
                                $tgt = $targetByAddendumId[$addId] ?? null;
                                $targetId = $tgt['id'] ?? null;
                                $startWeek = (int) ($tgt['start_week'] ?? 0);
                                $endWeek = (int) ($tgt['end_week'] ?? 0);
                                $idJobApps = $tgt['id_job_applications'] ?? '';

                                // Kalkulasi Volume
                                $volume = (float) ($item['volume'] ?? 0);
                                $unit = $item['unit'] ?? '';
                                $realisasi = $targetId ? ($progressByTargetId[$targetId] ?? 0) : 0;
                                $selisih = $volume - $realisasi;

                                // Kalkulasi Harga
                                $hargaRealisasi = $realisasi * (float) ($item['current_unit_price'] ?? 0);
                                $hargaSelisih = $total - $hargaRealisasi;

                                $indent = $subgroup !== '' ? '28px' : '14px';

                                // Update total keseluruhan
                                $totalVolumeAddendumAll += $volume;
                                $totalRealisasiAddendumAll += $realisasi;
                                $totalSelisihAddendumAll += $selisih;
                                $totalHargaRealisasiAddendumAll += $hargaRealisasi;
                                $totalSelisihHargaAddendumAll += $hargaSelisih;
                                ?>
                                <tr class="item-row text-center <?= $groupHashAdd ?>" data-rab-id="" data-addendum-id="<?= $addId ?>"
                                    data-group="<?= esc($grpName) ?>" data-subgroup="<?= esc($sgName) ?>"
                                    data-activity="[ADDENDUM] <?= esc($actName) ?>"
                                    data-volume="<?= number_format($volume, 2, '.', '') ?>" data-job-apps="<?= esc($idJobApps) ?>"
                                    data-target-id="<?= $targetId ?? '' ?>" onclick="selectRow(this)">
                                    <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                                    <td class="text-start" style="padding-left:<?= $indent ?>;"><?= esc($actName) ?></td>
                                    <td>
                                        <?php if ($targetId): ?>
                                            <?php
                                            $job = $jobs_by_target[$targetId] ?? null;
                                            $jobApplicants = $applicants_by_target[$targetId] ?? [];
                                            $numApplicants = count($jobApplicants);
                                            ?>
                                            <?php if ($job): ?>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-indigo btn-adm-action"
                                                        onclick="openLihatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>')"
                                                        style="font-size: 11px; padding: 3px 8px; background-color: #4f46e5; color: #fff; border: none; border-radius: 6px;">
                                                        <i class="fas fa-users me-1"></i> Pelamar
                                                        <span class="badge bg-white text-indigo rounded-pill ms-1"
                                                            style="font-size: 9px; padding: 2px 5px; color: #4f46e5 !important;"><?= $numApplicants ?></span>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success btn-adm-action"
                                                        onclick="openBuatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>', <?= (float) ($item['current_unit_price'] ?? 0) ?>, <?= (float) $volume ?>)"
                                                        style="font-size: 11px; padding: 3px 6px; border-radius: 6px;" title="Edit Lowongan">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-success btn-adm-action"
                                                    onclick="openBuatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>', <?= (float) ($item['current_unit_price'] ?? 0) ?>, <?= (float) $volume ?>)"
                                                    style="font-size: 11px; padding: 3px 8px; border-radius: 6px; background-color: #28a745; border-color: #28a745; color: #fff;">
                                                    <i class="fas fa-plus me-1"></i> Buat Loker
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted small" style="font-size: 10px; font-style: italic;">Atur target
                                                dulu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>Rp <?= number_format($total) ?></td>
                                    <td class="num"><?= number_format($volume, 2) ?></td>
                                    <td class="num text-success"><?= number_format($realisasi, 2) ?></td>
                                    <td class="num <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                                        <?= number_format($selisih, 2) ?>
                                    </td>
                                    <td>Rp <?= number_format($hargaRealisasi) ?></td>
                                    <td class="<?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                                        <?= number_format($hargaSelisih) ?>
                                    </td>
                                    <?php for ($w = 1; $w <= $numWeeks; $w++): ?>
                                        <?php
                                        $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                                        $barClass = '';
                                        if ($isActive) {
                                            if ($startWeek == $endWeek) {
                                                $barClass = 'bar-single';
                                            } elseif ($w == $startWeek) {
                                                $barClass = 'bar-start';
                                            } elseif ($w == $endWeek) {
                                                $barClass = 'bar-end';
                                            } else {
                                                $barClass = 'bar-middle';
                                            }
                                        }
                                        ?>
                                        <td
                                            class="cell-bar <?= $isActive ? ' week-active' : '' ?> <?= $isActive ? 'cell-' . $barClass : '' ?>">
                                            <?php if ($isActive): ?>
                                                <div class="bar bg-warning <?= $barClass ?>"
                                                    onclick="showProgressList(event, <?= $targetId ?? 0 ?>, '[ADDENDUM] <?= esc($actName) ?>')">
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <tr style="background:#f8f9fa;font-weight:500;" class="total-row-add">
                        <td colspan="3" class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
                            TOTAL ADDENDUM</td>
                        <td class="text-center">Rp <?= number_format($totalHargaAddendum) ?></td>
                        <td class="num fw-bold" style="font-weight:bold;"><?= number_format($totalVolumeAddendumAll, 2) ?>
                        </td>
                        <td class="num fw-bold text-success"><?= number_format($totalRealisasiAddendumAll, 2) ?></td>
                        <td class="num fw-bold <?= $totalSelisihAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= number_format($totalSelisihAddendumAll, 2) ?></td>
                        <td class="text-center">Rp <?= number_format($totalHargaRealisasiAddendumAll) ?></td>
                        <td class="text-center <?= $totalSelisihHargaAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
                            <?= number_format($totalSelisihHargaAddendumAll) ?>
                        </td>
                        <td colspan="<?= $numWeeks ?>"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <!-- Modal Tambah / Edit Target -->
    <div class="modal fade" id="modalTargetEdit" tabindex="-1" aria-labelledby="modalTargetEditLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%); border-bottom: none; padding: 18px 24px;">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalTargetEditLabel"
                            style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
                            <i class="fas fa-crosshairs me-2"></i> Tambah / Edit Target
                        </h5>
                        <span id="selected-info-<?= $constructionId ?>" class="small opacity-75 d-block mt-1 fw-normal"
                            style="font-size: 0.85rem;"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="form-create-target"
                    action="<?= base_url('admin/construction/create-target/' . $constructionId) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="rab_id" id="inp-rab-id-<?= $constructionId ?>" value="">
                    <input type="hidden" name="addendum_id" id="inp-addendum-id-<?= $constructionId ?>" value="">

                    <div class="modal-body" style="padding: 24px;">

                        <!-- Row 2: Target Config -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-hard-hat me-1 text-primary"></i> Tukang
                                </label>
                                <select name="id_job_applications" class="form-select"
                                    id="inp-tukang-<?= $constructionId ?>"
                                    style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                                    <option value="">Pilih Tukang</option>
                                    <?php foreach ($applicants ?? [] as $app): ?>
                                        <option value="<?= $app['id'] ?>"><?= esc($app['tukang_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-play me-1 text-primary"></i> Mulai (MG)
                                </label>
                                <input type="number" class="form-control" name="start_week"
                                    id="inp-start-<?= $constructionId ?>" min="1" max="<?= $numWeeks ?>" value="1"
                                    style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-stop me-1 text-primary"></i> Selesai (MG)
                                </label>
                                <input type="number" class="form-control" name="end_week"
                                    id="inp-end-<?= $constructionId ?>" min="1" max="<?= $numWeeks ?>" value="2"
                                    style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-target-<?= $constructionId ?>"
                            style="border-radius: 8px; font-weight: 600; background-color: var(--palette-primary); border-color: var(--palette-primary);">
                            <i class="fas fa-save me-1"></i> Simpan Target
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Atur Jadwal Proyek -->
<div class="modal fade" id="modalAturJadwal" tabindex="-1" aria-labelledby="modalAturJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header text-white"
                style="background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%); border-bottom: none; padding: 18px 24px;">
                <h5 class="modal-title fw-bold" id="modalAturJadwalLabel"
                    style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
                    <i class="fas fa-calendar-alt me-2"></i> Atur Jadwal Proyek
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/construction/update-schedule') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="construction_id" value="<?= $constructionId ?>">
                <div class="modal-body" style="padding: 24px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary mb-1"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-hashtag me-1 text-primary"></i> Jumlah Minggu
                        </label>
                        <input type="number" class="form-control" name="week" min="1" max="52" value="<?= $numWeeks ?>"
                            required style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary mb-1"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-briefcase me-1 text-primary"></i> Kerja / Minggu
                        </label>
                        <input type="number" class="form-control" name="workday" min="1" max="7" value="<?= $workday ?>"
                            required style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary mb-1"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-calendar-check me-1 text-primary"></i> Tanggal Mulai
                        </label>
                        <input type="date" class="form-control" name="start_date" value="<?= $startDate ?? '' ?>"
                            style="border-radius: 8px; border: 1.5px solid #e5e7eb;">
                    </div>
                </div>
                <div class="modal-footer"
                    style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px; font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-primary"
                        style="border-radius: 8px; font-weight: 600; background-color: var(--palette-primary); border-color: var(--palette-primary);">
                        <i class="fas fa-save me-1"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal List Progress Pekerjaan -->
<div class="modal fade" id="modalProgressList" tabindex="-1" aria-labelledby="modalProgressListLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header text-white"
                style="background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%); border-bottom: none; padding: 18px 24px;">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modalProgressListLabel"
                        style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
                        <i class="fas fa-chart-line me-2"></i> Detail Progress Pekerjaan
                    </h5>
                    <span id="progress-modal-subtitle" class="small opacity-75 d-block mt-1 fw-normal"
                        style="font-size: 0.85rem;"></span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px; max-height: 60vh; overflow-y: auto;">
                <div id="progress-list-container">
                    <!-- Progress items will be dynamically rendered here -->
                </div>
            </div>
            <div class="modal-footer"
                style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    style="border-radius: 8px; font-weight: 600;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Lowongan -->
<?php if (can('construction_lowongan')): ?>
    <?php
    $_startDate = $construction['start_date'] ?? null;
    $_longitude = $construction['longitude'] ?? '';
    $_latitude = $construction['latitude'] ?? '';
    ?>
    <div class="modal fade" id="modalBuatLowongan" tabindex="-1" aria-labelledby="modalBuatLowonganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-bottom: none; padding: 18px 24px;">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalBuatLowonganLabel"
                            style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
                            <i class="fas fa-briefcase me-2"></i> Buat / Perbarui Lowongan Tukang
                        </h5>
                        <span id="job-modal-subtitle" class="small opacity-75 d-block mt-1 fw-normal"
                            style="font-size: 0.85rem;"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="<?= base_url('admin/construction/update-job-info') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $constructionId ?>">
                    <input type="hidden" name="construction_target_id" id="inp-target-id-loker" value="">
                    <!-- Hidden: auto-filled dari JS berdasarkan target -->
                    <input type="hidden" name="tanggal_mulai" id="inp-mulai-loker" value="">
                    <input type="hidden" name="tanggal_akhir" id="inp-akhir-loker" value="">
                    <input type="hidden" name="longitude" value="<?= esc($_longitude) ?>">
                    <input type="hidden" name="latitude" value="<?= esc($_latitude) ?>">

                    <div class="modal-body" style="padding: 24px;">

                        <!-- Info Jadwal & Lokasi (read-only display) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #f0fdf4; border: 1.5px solid #bbf7d0;">
                                    <div class="mb-1"
                                        style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #16a34a; font-weight: 700;">
                                        <i class="fas fa-calendar-alt me-1"></i> Jadwal Pekerjaan Target
                                    </div>
                                    <div class="fw-bold text-dark" id="display-jadwal-loker" style="font-size: 0.92rem;">
                                        -
                                    </div>
                                    <div class="text-muted mt-1" id="display-durasi-loker" style="font-size: 0.78rem;">-
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background: #eff6ff; border: 1.5px solid #bfdbfe;">
                                    <div class="mb-1"
                                        style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #2563eb; font-weight: 700;">
                                        <i class="fas fa-map-marker-alt me-1"></i> Koordinat Lokasi (Otomatis)
                                    </div>
                                    <div class="fw-bold text-dark" style="font-size: 0.92rem;">
                                        <?= $_latitude ? esc($_latitude) . ', ' . esc($_longitude) : '<em class="text-muted" style="font-size:0.85rem">Belum tersedia</em>' ?>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 0.78rem;">Latitude &amp; Longitude</div>
                                </div>
                            </div>
                        </div>

                        <!-- Field yang diisi manual -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-money-bill-wave me-1 text-success"></i> Upah per Pekerjaan (Rp) <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #f8f9fa; border: 1.5px solid #e5e7eb; border-right: none; border-radius: 8px 0 0 8px; font-weight: 600; color: #4b5563;">Rp</span>
                                    <input type="text" id="display-upah-loker-input" class="form-control" readonly disabled
                                        style="border-radius: 0 8px 8px 0; border: 1.5px solid #e5e7eb; background-color: #f3f4f6; font-weight: 600; color: #1f2937;">
                                    <input type="hidden" name="upah" id="inp-upah-loker">
                                </div>
                                <div class="mt-2 p-3 rounded-3" id="upah-breakdown-container" style="background: #f8fafc; border: 1px dashed #cbd5e1; font-size: 0.82rem; color: #475569;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Upah per Unit (Tenaga Kerja):</span>
                                        <span class="fw-semibold text-dark" id="display-unit-wage">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Volume Pekerjaan Target:</span>
                                        <span class="fw-semibold text-dark" id="display-volume">-</span>
                                    </div>
                                    <hr style="margin: 6px 0; border-top: 1px dashed #cbd5e1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Total Upah (Unit × Volume):</span>
                                        <span class="fw-bold text-success" id="display-total-wage-calc">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-hard-hat me-1 text-success"></i> Uraian Pekerjaan Tukang <span
                                        class="text-danger">*</span>
                                </label>
                                <textarea name="detail_pekerjaan" id="inp-detail-pekerjaan-loker" class="form-control"
                                    rows="4"
                                    placeholder="Contoh: Tukang bertanggung jawab atas pemasangan keramik lantai, pengecatan tembok, dan finishing interior..."
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #e5e7eb; resize: vertical;"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-map-marked-alt me-1 text-success"></i> Patokan Alamat / Detail Lokasi
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="detail_lokasi" id="inp-detail-lokasi-loker" class="form-control" rows="3"
                                    placeholder="Contoh: Rumah no. 12, Gang Merpati, dekat SPBU Pertamina Jl. Sudirman..."
                                    required
                                    style="border-radius: 8px; border: 1.5px solid #e5e7eb; resize: vertical;"><?= esc($construction['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-success" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-paper-plane me-1"></i> Publikasikan Lowongan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Lihat Lowongan & Pelamar -->
<?php if (can('construction_lowongan') || can('construction_pelamar')): ?>
    <div class="modal fade" id="modalLihatLowongan" tabindex="-1" aria-labelledby="modalLihatLowonganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content"
                style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border-bottom: none; padding: 18px 24px;">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalLihatLowonganLabel"
                            style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
                            <i class="fas fa-users me-2"></i> Pelamar &amp; Informasi Lowongan Kerja
                        </h5>
                        <span id="lihat-loker-subtitle" class="small opacity-75 d-block mt-1 fw-normal"
                            style="font-size: 0.85rem;">Pantau daftar tukang yang melamar dan detail penugasan proyek
                            ini</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; background-color: #f8fafc;">
                    <div class="row g-4">
                        <!-- Left: Detail/Preview Lowongan -->
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <div
                                        style="width: 4px; height: 18px; background-color: #4f46e5; border-radius: 2px; margin-right: 10px;">
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Preview Tampilan Lowongan</h6>
                                </div>
                                <div class="card-body p-4" style="font-family: 'DM Sans', sans-serif;">
                                    <div class="mb-4">
                                        <span class="text-secondary fw-bold text-uppercase d-block mb-1"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Rincian Tugas & Tanggung
                                            Jawab</span>
                                        <div class="p-3 rounded bg-light border-start border-3"
                                            id="display-detail-pekerjaan-loker"
                                            style="font-size: 0.9rem; line-height: 1.6; color: #334155; border-color: #6366f1 !important;">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <span class="text-secondary fw-bold text-uppercase d-block mb-1"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;">Lokasi & Patokan
                                            Proyek</span>
                                        <div class="p-3 rounded bg-light border-start border-3"
                                            id="display-detail-lokasi-loker"
                                            style="font-size: 0.9rem; line-height: 1.6; color: #334155; border-color: #6366f1 !important;">
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <span class="text-secondary fw-bold text-uppercase d-block mb-1"
                                                style="font-size: 0.75rem; letter-spacing: 0.5px;">Jadwal Kerja</span>
                                            <div class="p-3 rounded bg-light d-flex align-items-center"
                                                id="display-jadwal-kerja-loker"
                                                style="font-size: 0.88rem; color: #334155; font-weight: 500;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary fw-bold text-uppercase d-block mb-1"
                                                style="font-size: 0.75rem; letter-spacing: 0.5px;">Upah per Pekerjaan</span>
                                            <div class="p-3 rounded bg-light d-flex align-items-center"
                                                id="display-upah-loker"
                                                style="font-size: 0.88rem; color: #334155; font-weight: 700; height: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Daftar Pelamar -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                <div
                                    class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div
                                            style="width: 4px; height: 18px; background-color: #10b981; border-radius: 2px; margin-right: 10px;">
                                        </div>
                                        <h6 class="fw-bold text-dark mb-0">Daftar Pelamar Masuk</h6>
                                    </div>
                                    <span class="badge bg-success rounded-pill px-2.5 py-1"
                                        id="display-jumlah-pelamar-loker" style="font-size: 0.8rem;">0 Pelamar</span>
                                </div>
                                <div class="card-body p-0" id="lihat-pelamar-container"
                                    style="max-height: 500px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                    style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px; font-weight: 600;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>