<?php
// ========================
// DATA PREPARATION (PHP)
// ========================
$numWeeks = (int) ($construction['week'] ?? 8);
$workday = (int) ($construction['workday'] ?? 7);
$startDate = $construction['start_date'] ?? null;
if (empty($startDate) && !empty($construction['created_at'])) {
    $startDate = date('Y-m-d', strtotime($construction['created_at']));
}
if (empty($startDate)) {
    $startDate = date('Y-m-d');
}

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

// Helper: hitung label rentang minggu ke-$startWeek sampai ke-$endWeek
function schedRangeLabel(int $startWeek, int $endWeek, ?string $startDate, int $workday): string
{
    if (!$startDate) {
        return 'MG ' . $startWeek . ($startWeek !== $endWeek ? '–' . $endWeek : '');
    }
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    $d = new \DateTime($startDate);
    $d->modify('+' . (($startWeek - 1) * 7) . ' days');

    $e = new \DateTime($startDate);
    $e->modify('+' . (($endWeek - 1) * 7 + ($workday - 1)) . ' days');

    $mStart = $months[(int)$d->format('n') - 1];
    $mEnd = $months[(int)$e->format('n') - 1];
    
    if ($d->format('Y') === $e->format('Y')) {
        return $d->format('j') . ' ' . $mStart . ' – ' . $e->format('j') . ' ' . $mEnd . ' ' . $e->format('Y');
    } else {
        return $d->format('j') . ' ' . $mStart . ' ' . $d->format('Y') . ' – ' . $e->format('j') . ' ' . $mEnd . ' ' . $e->format('Y');
    }
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
$invoiceList = $invoice_list ?? [];

// Group $addendum
$groupedAddendum = [];
foreach ($addendum ?? [] as $r) {
    $g = $r['group_name'] ?? '';
    $sg = $r['sub_group_name'] ?? '';
    $groupedAddendum[$g][$sg][] = $r;
}

$constructionId = $construction['id'] ?? '';
$weekGroupSize = 5;
$numWeekSeps = 0;
$totalCols = 8 + $numWeeks; // 8 fixed cols + week cols
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
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center">
                    <div class="header-icon-wrap me-3">
                        <i class="fas fa-calendar-alt" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Target Pekerjaan RAB</h5>
                        <p class="text-muted mb-0 small">Atur jadwal pelaksanaan dan penugasan tukang untuk setiap pekerjaan RAB.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap flex-md-nowrap gap-2 justify-content-md-end">
                    <?php if (can('construction_lowongan') || can('construction_pelamar')): ?>
                        <button type="button" class="btn btn-outline-primary py-2 px-3" 
                            data-bs-toggle="modal" data-bs-target="#modalKelolaLowongan" style="font-weight: 600; border-radius: 8px; font-size: 13px; white-space: nowrap;">
                            <i class="fas fa-users-cog me-1"></i> Kelola Lowongan &amp; Pelamar
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-secondary py-2 px-3" 
                        id="toggleDetailCols" onclick="toggleDetailColumns()" style="font-weight: 600; border-radius: 8px; font-size: 13px; white-space: nowrap;">
                        <i class="fas fa-eye me-1"></i> Tampilkan Detail
                    </button>
                    <button type="button" class="btn btn-target-primary py-2 px-3" data-bs-toggle="modal"
                        data-bs-target="#modalAturJadwal" style="white-space: nowrap; font-weight: 600; border-radius: 8px; font-size: 13px;">
                        <i class="fas fa-calendar-check me-1"></i> Atur Jadwal Proyek
                    </button>
                </div>
            </div>
        </div>
    </div>
    <p class="mobile-scroll-hint"><i class="fas fa-hand-point-right"></i> Geser kanan untuk lihat detail Gantt &amp;
        Harga</p>
    <div class="tbl-outer">
        <table id="mainTable" class="table table-sm table-schedule mt-0 pt-0">
            <thead>
                <tr>
                    <th class="left fw-bold text-center" style="min-width:450px;">URAIAN PEKERJAAN</th>
                    <th class="px-2 fw-bold detail-col text-center" style="width:140px;">STATUS TAGIHAN</th>
                    <th class="px-2 fw-bold detail-col" style="min-width:150px;">JUMLAH HARGA</th>
                    <th class="px-2 fw-bold detail-col" style="width:120px;">VOLUME</th>
                    <th class="px-2 fw-bold detail-col" style="width:120px;">SELISIH VOLUME</th>
                    <th class="px-2 fw-bold detail-col" style="width:250px;">JUMLAH HARGA REALISASI</th>
                    <th class="px-2 fw-bold detail-col" style="width:250px;">SELISIH JUMLAH HARGA</th>
                    <?php
                    $weekGroupSize = 5;
                    for ($i = 1; $i <= $numWeeks; $i++):
                        $isFirstOfGroup = (($i - 1) % $weekGroupSize === 0);
                        $grpIdx = (int)(($i - 1) / $weekGroupSize);
                        if ($isFirstOfGroup):
                    ?>
                        <th class="week-th week-col-cell text-center week-group-trigger" 
                            data-group-idx="<?= $grpIdx ?>"
                            data-start-week="<?= $i ?>"
                            data-end-week="<?= min($i + $weekGroupSize - 1, $numWeeks) ?>"
                            data-label-original="MG <?= $i ?>"
                            onclick="toggleWeekGroup(<?= $grpIdx ?>, this)"
                            style="vertical-align: middle; padding: 8px 4px; min-width: 85px; cursor: pointer; user-select: none;">
                            <div class="fw-bold text-primary mb-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                <i class="fas fa-chevron-left week-group-chevron" style="font-size:8px; opacity: 0.85;"></i>
                                <span class="week-label-text">MG <?= $i ?></span>
                            </div>
                            <div class="text-muted fw-normal week-date-subtext" style="font-size: 9px; line-height: 1.1;">
                                <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                        </th>
                    <?php else: ?>
                        <th class="week-th week-col-cell text-center" data-wg="<?= $grpIdx ?>" style="vertical-align: middle; padding: 8px 4px; min-width: 80px;">
                            <div class="fw-bold text-primary mb-1" style="font-size: 11px; letter-spacing: 0.5px;">MG
                                <?= $i ?></div>
                            <div class="text-muted fw-normal" style="font-size: 9px; line-height: 1.1;">
                                <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                        </th>
                    <?php 
                        endif;
                    endfor; 
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grouped as $group => $subgroups): ?>
                    <?php
                    $groupHash = 'group-' . md5($group);
                    ?>
                    <?php /* GROUP HEADER */ ?>
                    <tr class="group-header" onclick="toggleGroup('<?= $groupHash ?>', this)">
                        <td colspan="99">
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
                                <td colspan="99">
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

                            // Kalkulasi Invoice
                            $matchedInvoice = null;
                            $desc = trim(($sgName ? $sgName . ' — ' : '') . $actName);
                            foreach ($invoiceList as $inv) {
                                if (!empty($inv['rab_id']) && (int)$inv['rab_id'] === (int)$rabItemId) {
                                    $matchedInvoice = $inv;
                                    break;
                                }
                                if (empty($inv['rab_id']) && strtolower(trim($inv['description'])) === strtolower($desc)) {
                                    $matchedInvoice = $inv;
                                    break;
                                }
                            }
                            $payStatus = 'unbilled';
                            if ($matchedInvoice) {
                                $payStatus = $matchedInvoice['status'] === 'PAID' ? 'paid' : 'unpaid';
                            }
                            ?>
                            <tr class="item-row text-center <?= $groupHash ?>" data-rab-id="<?= $rabItemId ?>"
                                data-group="<?= esc($grpName) ?>" data-subgroup="<?= esc($sgName) ?>"
                                data-activity="<?= esc($actName) ?>" data-volume="<?= number_format($volume, 2, '.', '') ?>"
                                data-job-apps="<?= esc($idJobApps) ?>" data-target-id="<?= $targetId ?? '' ?>">
                                <td class="text-start" style="padding-left:<?= $indent ?>;" onclick="selectRow(this)">
                                    <?= esc($actName) ?>
                                    <?php if ($startWeek > 0): ?>
                                        <span class="week-badge-mobile">MG <?= $startWeek ?>–<?= $endWeek ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="detail-col text-center">
                                    <?php if ($payStatus === 'paid'): ?>
                                        <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15);">
                                            <i class="fas fa-check-circle me-1"></i> LUNAS
                                        </span>
                                    <?php elseif ($payStatus === 'unpaid'): ?>
                                        <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.15);">
                                            <i class="fas fa-clock me-1"></i> BELUM BAYAR
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.15);">
                                            <i class="fas fa-file-invoice me-1"></i> BELUM DITAGIH
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="detail-col">Rp <?= number_format($total) ?></td>
                                <td class="num detail-col"><?= number_format($volume, 2) ?></td>
                                <td class="num detail-col <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($selisih, 2) ?>
                                </td>
                                <td class="detail-col">Rp <?= number_format($hargaRealisasi) ?></td>
                                <td class="detail-col <?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                                    <?= number_format($hargaSelisih) ?>
                                                         <?php
                                $weekGroupSize = 5;
                                $w = 1;
                                while ($w <= $numWeeks):
                                    $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                                    if ($isActive && $w == $startWeek):
                                         $colspan = ($endWeek - $startWeek + 1);
                                        
                                        $pct = $volume > 0 ? min(100, max(0, ($realisasi / $volume) * 100)) : 0;
                                        $statusClass = 'status-planned';
                                        if ($pct >= 100) {
                                            $statusClass = 'status-completed';
                                        } elseif ($pct > 0) {
                                            $statusClass = 'status-progress';
                                        }

                                        $realisasiStr = number_format($realisasi, 2);
                                        $volumeStr = number_format($volume, 2);
                                        $pctStr = number_format($pct, 0) . '%';

                                        if ($colspan >= 4) {
                                            $barLabel = "Realisasi: {$realisasiStr} / {$volumeStr} {$unit} ({$pctStr})";
                                        } elseif ($colspan >= 2) {
                                            $barLabel = "{$realisasiStr} / {$volumeStr} ({$pctStr})";
                                        } else {
                                            $barLabel = $realisasiStr;
                                        }
                                ?>
                                    <td colspan="<?= $colspan ?>"
                                        class="cell-bar week-col-cell week-active"
                                        style="--colspan: <?= $colspan ?>;"
                                        data-start-wk="<?= $startWeek ?>"
                                        data-end-wk="<?= $endWeek ?>"
                                        onclick="showProgressList(event, <?= $targetId ?? 0 ?>, '<?= esc($actName) ?>')">
                                        <div class="gantt-bar-container bar-main <?= $statusClass ?>"
                                            title="Target: <?= $volumeStr ?> <?= esc($unit) ?> | Realisasi: <?= $realisasiStr ?> <?= esc($unit) ?> (<?= $pctStr ?>)">
                                            <div class="gantt-progress-fill" style="width: <?= $pct ?>%;"></div>
                                            <div class="gantt-bar-content">
                                                <span class="gantt-bar-text"><?= $barLabel ?></span>
                                            </div>
                                        </div>
                                    </td>
                                <?php
                                        $w = $endWeek + 1;
                                    else:
                                        $grpIdx = (int)(($w - 1) / $weekGroupSize);
                                ?>
                                    <td class="cell-bar week-col-cell" data-wg="<?= $grpIdx ?>"></td>
                                <?php
                                        $w++;
                                    endif;
                                endwhile;
                                ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <tr style="background:#f8f9fa;font-weight:500;" class="total-row">
                    <td class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
                        TOTAL</td>
                    <td class="detail-col"></td>
                    <td class="text-center detail-col">Rp <?= number_format($totalHarga) ?></td>
                    <td class="num fw-bold detail-col" style="font-weight:bold;"><?= number_format($totalVolumeAll, 2) ?></td>
                    <td class="num fw-bold detail-col <?= $totalSelisihAll > 0 ? 'text-danger' : 'text-success' ?>">
                        <?= number_format($totalSelisihAll, 2) ?></td>
                    <td class="text-center detail-col">Rp <?= number_format($totalHargaRealisasiAll) ?></td>
                    <td class="text-center detail-col <?= $totalSelisihHargaAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
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
            <table id="addendumTable" class="table table-sm table-schedule">
                <thead>
                    <tr>
                        <th class="left fw-bold text-center" style="min-width:450px;">URAIAN PEKERJAAN ADDENDUM</th>
                        <th class="px-2 fw-bold detail-col text-center" style="width:140px;">STATUS TAGIHAN</th>
                        <th class="px-2 fw-bold detail-col" style="min-width:150px;">JUMLAH HARGA</th>
                        <th class="px-2 fw-bold detail-col" style="width:120px;">VOLUME</th>
                        <th class="px-2 fw-bold detail-col" style="width:120px;">SELISIH VOLUME</th>
                        <th class="px-2 fw-bold detail-col" style="width:250px;">JUMLAH HARGA REALISASI</th>
                        <th class="px-2 fw-bold detail-col" style="width:250px;">SELISIH JUMLAH HARGA</th>
                        <?php
                        $weekGroupSize = 5;
                        for ($i = 1; $i <= $numWeeks; $i++):
                            $isFirstOfGroup = (($i - 1) % $weekGroupSize === 0);
                            $grpIdx = (int)(($i - 1) / $weekGroupSize);
                            if ($isFirstOfGroup):
                        ?>
                            <th class="week-th week-col-cell text-center week-group-trigger" 
                                data-group-idx="<?= $grpIdx ?>"
                                data-start-week="<?= $i ?>"
                                data-end-week="<?= min($i + $weekGroupSize - 1, $numWeeks) ?>"
                                data-label-original="MG <?= $i ?>"
                                onclick="toggleWeekGroup(<?= $grpIdx ?>, this)"
                                style="vertical-align: middle; padding: 8px 4px; min-width: 85px; cursor: pointer; user-select: none;">
                                <div class="fw-bold text-primary mb-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-chevron-left week-group-chevron" style="font-size:8px; opacity: 0.85;"></i>
                                    <span class="week-label-text">MG <?= $i ?></span>
                                </div>
                                <div class="text-muted fw-normal week-date-subtext" style="font-size: 9px; line-height: 1.1;">
                                    <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                            </th>
                        <?php else: ?>
                            <th class="week-th week-col-cell text-center" data-wg="<?= $grpIdx ?>" style="vertical-align: middle; padding: 8px 4px; min-width: 80px;">
                                <div class="fw-bold text-primary mb-1" style="font-size: 11px; letter-spacing: 0.5px;">MG
                                    <?= $i ?></div>
                                <div class="text-muted fw-normal" style="font-size: 9px; line-height: 1.1;">
                                    <?= schedWeekLabel($i, $startDate, $workday) ?></div>
                            </th>
                        <?php 
                            endif;
                        endfor; 
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedAddendum as $group => $subgroups): ?>
                        <?php
                        $groupHashAdd = 'group-add-' . md5($group);
                        ?>
                        <?php /* GROUP HEADER */ ?>
                        <tr class="group-header" onclick="toggleGroup('<?= $groupHashAdd ?>', this)">
                            <td colspan="99">
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
                                    <td colspan="99">
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

                                // Kalkulasi Invoice
                                $matchedInvoice = null;
                                $desc = trim(($sgName ? $sgName . ' — ' : '') . $actName);
                                foreach ($invoiceList as $inv) {
                                    if (strtolower(trim($inv['description'])) === strtolower($desc) || strtolower(trim($inv['description'])) === strtolower($actName)) {
                                        $matchedInvoice = $inv;
                                        break;
                                    }
                                }
                                $payStatus = 'unbilled';
                                if ($matchedInvoice) {
                                    $payStatus = $matchedInvoice['status'] === 'PAID' ? 'paid' : 'unpaid';
                                }
                                ?>
                                <tr class="item-row text-center <?= $groupHashAdd ?>" data-rab-id="" data-addendum-id="<?= $addId ?>"
                                    data-group="<?= esc($grpName) ?>" data-subgroup="<?= esc($sgName) ?>"
                                    data-activity="[ADDENDUM] <?= esc($actName) ?>"
                                    data-volume="<?= number_format($volume, 2, '.', '') ?>" data-job-apps="<?= esc($idJobApps) ?>"
                                    data-target-id="<?= $targetId ?? '' ?>">
                                    <td class="text-start" style="padding-left:<?= $indent ?>;" onclick="selectRow(this)">
                                        <?= esc($actName) ?>
                                    </td>
                                    <td class="detail-col text-center">
                                        <?php if ($payStatus === 'paid'): ?>
                                            <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15);">
                                                <i class="fas fa-check-circle me-1"></i> LUNAS
                                            </span>
                                        <?php elseif ($payStatus === 'unpaid'): ?>
                                            <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.15);">
                                                <i class="fas fa-clock me-1"></i> BELUM BAYAR
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="font-size: 8.5px; font-weight: 600; padding: 2px 6px; border-radius: 4px; background-color: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.15);">
                                                <i class="fas fa-file-invoice me-1"></i> BELUM DITAGIH
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="detail-col">Rp <?= number_format($total) ?></td>
                                    <td class="num detail-col"><?= number_format($volume, 2) ?></td>
                                    <td class="num detail-col <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                                        <?= number_format($selisih, 2) ?>
                                    </td>
                                    <td class="detail-col">Rp <?= number_format($hargaRealisasi) ?></td>
                                    <td class="detail-col <?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                                        <?= number_format($hargaSelisih) ?>
                                    </td>
                                    <?php
                                    $w = 1;
                                    while ($w <= $numWeeks):
                                        $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                                        if ($isActive && $w == $startWeek):
                                            $colspan = ($endWeek - $startWeek + 1);

                                            $pct = $volume > 0 ? min(100, max(0, ($realisasi / $volume) * 100)) : 0;
                                            $statusClass = 'status-planned';
                                            if ($pct >= 100) {
                                                $statusClass = 'status-completed';
                                            } elseif ($pct > 0) {
                                                $statusClass = 'status-progress';
                                            }

                                            $realisasiStr = number_format($realisasi, 2);
                                            $volumeStr = number_format($volume, 2);
                                            $pctStr = number_format($pct, 0) . '%';

                                            if ($colspan >= 4) {
                                                $barLabel = "Realisasi: {$realisasiStr} / {$volumeStr} {$unit} ({$pctStr})";
                                            } elseif ($colspan >= 2) {
                                                $barLabel = "{$realisasiStr} / {$volumeStr} ({$pctStr})";
                                            } else {
                                                $barLabel = $realisasiStr;
                                            }
                                    ?>
                                            <td colspan="<?= $colspan ?>"
                                                class="cell-bar week-col-cell week-active"
                                                style="--colspan: <?= $colspan ?>;"
                                                data-start-wk="<?= $startWeek ?>"
                                                data-end-wk="<?= $endWeek ?>"
                                                onclick="showProgressList(event, <?= $targetId ?? 0 ?>, '[ADDENDUM] <?= esc($actName) ?>')">
                                                <div class="gantt-bar-container bar-addendum <?= $statusClass ?>"
                                                    title="Target: <?= $volumeStr ?> <?= esc($unit) ?> | Realisasi: <?= $realisasiStr ?> <?= esc($unit) ?> (<?= $pctStr ?>)">
                                                    <div class="gantt-progress-fill" style="width: <?= $pct ?>%;"></div>
                                                    <div class="gantt-bar-content">
                                                        <span class="gantt-bar-text"><?= $barLabel ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                    <?php
                                            $w = $endWeek + 1;
                                        else:
                                            $grpIdx = (int)(($w - 1) / $weekGroupSize);
                                    ?>
                                            <td class="cell-bar week-col-cell" data-wg="<?= $grpIdx ?>"></td>
                                    <?php
                                            $w++;
                                        endif;
                                    endwhile;
                                    ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <tr style="background:#f8f9fa;font-weight:500;" class="total-row-add">
                        <td class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
                            TOTAL ADDENDUM</td>
                        <td class="detail-col"></td>
                        <td class="text-center detail-col">Rp <?= number_format($totalHargaAddendum) ?></td>
                        <td class="num fw-bold detail-col" style="font-weight:bold;"><?= number_format($totalVolumeAddendumAll, 2) ?>
                        </td>
                        <td class="num fw-bold detail-col <?= $totalSelisihAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= number_format($totalSelisihAddendumAll, 2) ?></td>
                        <td class="text-center detail-col">Rp <?= number_format($totalHargaRealisasiAddendumAll) ?></td>
                        <td class="text-center detail-col <?= $totalSelisihHargaAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
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
</div><!-- Modal Buat Lowongan -->
<?php if (can('construction_lowongan')): ?>
    <?php
    $_startDate = $construction['start_date'] ?? null;
    if (empty($_startDate) && !empty($construction['created_at'])) {
        $_startDate = date('Y-m-d', strtotime($construction['created_at']));
    }
    if (empty($_startDate)) {
        $_startDate = date('Y-m-d');
    }
    $_longitude = $construction['longitude'] ?? '';
    $_latitude = $construction['latitude'] ?? '';
    ?>
    <div class="modal fade" id="modalBuatLowongan" tabindex="-1" aria-labelledby="modalBuatLowonganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-bottom: none; padding: 20px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); color: #fff;">
                            <i class="fas fa-briefcase" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalBuatLowonganLabel"
                                style="font-family: 'Outfit', sans-serif;">
                                Buat / Perbarui Lowongan Kerja
                            </h5>
                            <span id="job-modal-subtitle" class="text-white-50 small d-block mt-1 fw-normal"
                                style="font-size: 0.85rem;"></span>
                        </div>
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

                        <!-- Info Jadwal (read-only display banner) -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center p-3" style="background: #f8fafc; border: 1.5px dashed #e2e8f0; border-radius: 12px;">
                                <div class="d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px; background: rgba(40, 167, 69, 0.1); color: #28a745; border-radius: 10px; font-size: 18px;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="d-block text-secondary fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Jadwal Pekerjaan Target</span>
                                    <span id="display-jadwal-loker" class="fw-bold text-dark" style="font-size: 13.5px;">-</span>
                                    <span id="display-durasi-loker" class="badge bg-success-subtle text-success border border-success-subtle ms-2 px-2 py-1" style="font-size: 10px; border-radius: 4px; font-weight: 600;">-</span>
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
                                    <span class="input-group-text" style="background: #f8f9fa; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 8px 0 0 8px; font-weight: 600; color: #4b5563;">Rp</span>
                                    <input type="text" id="display-upah-loker-input" class="form-control" readonly disabled
                                        style="border-radius: 0 8px 8px 0; border: 1.5px solid #e2e8f0; background-color: #f1f5f9; font-weight: 700; color: #1e293b;">
                                    <input type="hidden" name="upah" id="inp-upah-loker">
                                </div>
                                <div class="mt-2 premium-breakdown-card" id="upah-breakdown-container">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Upah per Unit (Tenaga Kerja):</span>
                                        <span class="fw-bold text-dark" id="display-unit-wage">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>Volume Pekerjaan Target:</span>
                                        <span class="fw-bold text-dark" id="display-volume">-</span>
                                    </div>
                                    <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total Upah (Unit × Volume):</span>
                                        <span class="fw-bold text-success" id="display-total-wage-calc" style="font-size: 13.5px;">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-secondary mb-1"
                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-tools me-1 text-success"></i> Kualifikasi Skill <span
                                        class="text-danger">*</span>
                                </label>
                                
                                <!-- Hidden select for form post -->
                                <select name="skills[]" id="inp-skills-loker" class="d-none" multiple required>
                                    <?php foreach ($available_skills ?? [] as $sk): ?>
                                        <option value="<?= $sk['id'] ?>"><?= esc($sk['skill_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <!-- Live Search & Chips Interface -->
                                <div class="search-wrapper mb-2">
                                    <i class="fas fa-search" style="font-size: 11px; left: 12px;"></i>
                                    <input type="text" id="skills-search-input" class="search-input" placeholder="Cari skill..." style="height: 36px; padding-left: 34px; font-size: 12.5px; border-radius: 8px;">
                                </div>
                                
                                <div class="skills-chips-wrapper mb-1" id="skills-chips-container">
                                    <!-- Chips generated via JS -->
                                </div>
                                <small class="text-muted">Pilih minimal 1 kualifikasi skill yang dibutuhkan dengan mengklik chip di atas.</small>
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
                                    style="border-radius: 8px; border: 1.5px solid #e2e8f0; resize: vertical;"><?= esc($construction['address'] ?? '') ?></textarea>
                            </div>
                            <input type="hidden" name="is_open" id="inp-is-open-loker" value="1">
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-weight: 600; font-size: 13px;">Batal</button>
                        <button type="submit" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600; font-size: 13px; background: linear-gradient(135deg, #28a745, #20c997); border: none; box-shadow: 0 4px 10px rgba(40,167,69,0.2);">
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
                style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border-bottom: none; padding: 20px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); color: #fff;">
                            <i class="fas fa-users" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalLihatLowonganLabel"
                                style="font-family: 'Outfit', sans-serif;">
                                Pelamar &amp; Informasi Lowongan Kerja
                            </h5>
                            <span id="lihat-loker-subtitle" class="text-white-50 small d-block mt-1 fw-normal"
                                style="font-size: 0.85rem;">Pantau daftar tukang yang melamar dan detail penugasan proyek ini</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 24px; background-color: #f8fafc;">
                    <div class="row g-4">
                        <!-- Left: Detail/Preview Lowongan -->
                        <div class="col-lg-5">
                            <div class="job-preview-mockup mb-4">
                                <div class="job-preview-header">
                                    <div class="mock-icon shadow-sm">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2 text-white" style="font-size: 15px; font-family: 'Outfit', sans-serif;" id="display-detail-pekerjaan-loker">-</h5>
                                    <span class="badge bg-white text-indigo fw-bold px-2.5 py-1" style="font-size: 10px; border-radius: 20px; color:#4f46e5 !important;">
                                        <i class="fas fa-project-diagram me-1"></i> Detail Target
                                    </span>
                                </div>
                                <div class="job-preview-body">
                                    <div class="job-preview-section">
                                        <div class="job-preview-section-title">Upah Pekerjaan (Tenaga Kerja)</div>
                                        <div class="job-preview-salary-box">
                                            <i class="fas fa-coins"></i>
                                            <div>
                                                <div class="salary-value" id="display-upah-loker">Rp -</div>
                                                <div class="text-muted" style="font-size: 10.5px; font-weight: 500;">Upah bersih disalurkan ke Tukang</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="job-preview-section">
                                        <div class="job-preview-section-title">Jadwal & Durasi Pelaksanaan</div>
                                        <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border-start border-3 border-indigo" style="border-color:#4f46e5 !important;">
                                            <i class="far fa-calendar-alt text-indigo ms-1 me-1" style="font-size: 15px;"></i>
                                            <div id="display-jadwal-kerja-loker" style="font-size: 12.5px; font-weight: 600; color: #334155; line-height: 1.3;">-</div>
                                        </div>
                                    </div>

                                    <div class="job-preview-section">
                                        <div class="job-preview-section-title">Kualifikasi Skill yang Dibutuhkan</div>
                                        <div id="display-skills-loker" class="d-flex flex-wrap gap-2">
                                            <!-- Will be populated dynamically -->
                                        </div>
                                    </div>

                                    <div class="job-preview-section">
                                        <div class="job-preview-section-title">Patokan Alamat / Lokasi Proyek</div>
                                        <div class="p-3 rounded-3 bg-light border-start border-3 border-indigo" id="display-detail-lokasi-loker" style="font-size: 12.5px; color: #334155; line-height: 1.4; border-color:#4f46e5 !important;">
                                            -
                                        </div>
                                    </div>

                                    <div class="job-preview-section">
                                        <div class="job-preview-section-title">Status Lowongan</div>
                                        <div id="display-status-loker">
                                            <!-- Will be populated dynamically -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Daftar Pelamar -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1.5px solid #e2e8f0;">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between" style="border-bottom: 1.5px solid #f1f5f9 !important;">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 4px; height: 18px; background-color: #10b981; border-radius: 2px; margin-right: 10px;"></div>
                                        <h6 class="fw-bold text-dark mb-0">Daftar Pelamar Masuk</h6>
                                    </div>
                                    <span class="badge bg-success rounded-pill px-2.5 py-1.5" id="display-jumlah-pelamar-loker" style="font-size: 11px; font-weight: 600;">0 Pelamar</span>
                                </div>
                                <div class="card-body p-3 bg-light" id="lihat-pelamar-container" style="max-height: 620px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                    style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"
                        style="border-radius: 8px; font-weight: 600; font-size: 13px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (can('construction_lowongan') || can('construction_pelamar')): ?>
    <!-- Modal Kelola Lowongan & Pelamar -->
    <div class="modal fade" id="modalKelolaLowongan" tabindex="-1" aria-labelledby="modalKelolaLowonganLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border-top-left-radius: 16px; border-top-right-radius: 16px; padding: 20px 24px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); color: #fff;">
                            <i class="fas fa-users-cog" style="font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalKelolaLowonganLabel" style="font-family: 'Outfit', sans-serif;">Kelola Lowongan & Pelamar</h5>
                            <span class="text-white-50 small">Kelola penugasan tukang dan lowongan pekerjaan proyek</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <!-- Nav Tabs -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                        <ul class="nav nav-pills gap-2" id="kelola-loker-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold px-4 py-2" id="tab-rab-utama-btn" data-bs-toggle="pill" data-bs-target="#panel-rab-utama" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="fas fa-tasks me-2"></i> RAB Utama
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold px-4 py-2" id="tab-addendum-btn" data-bs-toggle="pill" data-bs-target="#panel-addendum" type="button" role="tab" style="border-radius: 10px;">
                                    <i class="fas fa-plus-circle me-2"></i> Addendum
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Real-time search bar -->
                        <div class="search-wrapper mb-0 w-100" style="max-width: 320px;">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-input" id="search-loker-input" placeholder="Cari lowongan pekerjaan..." onkeyup="filterLokerTable()">
                        </div>
                    </div>

                    <div class="tab-content" id="kelola-loker-tabContent">
                        <!-- Panel RAB Utama -->
                        <div class="tab-pane fade show active" id="panel-rab-utama" role="tabpanel" aria-labelledby="tab-rab-utama-btn">
                            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1.5px solid #e2e8f0;">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-loker mb-0" style="font-size: 13.5px;">
                                        <thead class="text-uppercase" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                            <tr>
                                                <th class="ps-4" style="width: 50px;">No</th>
                                                <th>Pekerjaan</th>
                                                <th>Jadwal Target</th>
                                                <th>Status Lowongan</th>
                                                <th class="text-end pe-4" style="width: 260px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $noRab = 1;
                                            foreach ($rab ?? [] as $r): 
                                                $rabItemId = $r['id'] ?? 0;
                                                $tgt = $targetByRabId[$rabItemId] ?? null;
                                                $targetId = $tgt['id'] ?? null;
                                                $actName = $r['activity_name'] ?? '';
                                                $startWeek = (int) ($tgt['start_week'] ?? 0);
                                                $endWeek = (int) ($tgt['end_week'] ?? 0);
                                                
                                                if (!$targetId) continue; // Hanya yang sudah diatur jadwal targetnya
                                                
                                                $job = $jobs_by_target[$targetId] ?? null;
                                                $jobApplicants = $applicants_by_target[$targetId] ?? [];
                                                $numApplicants = count($jobApplicants);
                                                $searchText = strtolower($actName . ' ' . ($r['group_name'] ?? '') . ' ' . ($r['sub_group_name'] ?? ''));
                                            ?>
                                                <tr class="loker-row" data-search-text="<?= esc($searchText) ?>">
                                                    <td class="ps-4 text-muted fw-bold"><?= $noRab++ ?></td>
                                                    <td>
                                                        <div class="fw-bold text-dark"><?= esc($actName) ?></div>
                                                        <span class="text-muted small"><?= esc($r['group_name'] ?? '') ?> <?= $r['sub_group_name'] ? ' — ' . esc($r['sub_group_name']) : '' ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($startWeek > 0): ?>
                                                            <span class="badge bg-light text-primary border border-primary-subtle px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">
                                                                <i class="far fa-calendar-alt me-1"></i> <?= schedRangeLabel($startWeek, $endWeek, $startDate, $workday) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($job): ?>
                                                            <?php if ((int) ($job['is_open'] ?? 1) === 1): ?>
                                                                <button type="button" class="btn btn-sm badge bg-success text-white px-2.5 py-1.5 fw-semibold toggle-job-status-btn"
                                                                    data-job-id="<?= $job['id'] ?>"
                                                                    data-applicants="<?= $numApplicants ?>"
                                                                    style="font-size: 11px; border-radius: 6px; border: none; box-shadow: 0 2px 6px rgba(25, 135, 84, 0.2);"
                                                                    onclick="toggleJobStatus(this)">
                                                                    <i class="fas fa-check-circle me-1"></i> Dibuka (<?= $numApplicants ?> Pelamar)
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-sm badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold toggle-job-status-btn"
                                                                    data-job-id="<?= $job['id'] ?>"
                                                                    data-applicants="<?= $numApplicants ?>"
                                                                    style="font-size: 11px; border-radius: 6px; border: 1px solid rgba(220, 53, 69, 0.15);"
                                                                    onclick="toggleJobStatus(this)">
                                                                    <i class="fas fa-times-circle me-1"></i> Ditutup (<?= $numApplicants ?> Pelamar)
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px; border: 1px solid rgba(108, 117, 125, 0.15);">
                                                                <i class="fas fa-exclamation-circle me-1"></i> Belum Dibuat
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <?php if ($job): ?>
                                                            <div class="d-inline-flex gap-2">
                                                                <button type="button" class="btn btn-sm px-3 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; background: linear-gradient(135deg, #4f46e5, #6366f1); color:#fff; border:none; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.15); transition: all 0.2s;"
                                                                    onclick="openLihatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>')">
                                                                    <i class="fas fa-users me-1"></i> Pelamar (<?= $numApplicants ?>)
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; border: 1px solid #cbd5e1; color:#475569; background:#fff; transition: all 0.2s;"
                                                                    onclick="openBuatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>', <?= (float) ($r['ahsp_tenaga_kerja_total'] ?? 0) ?>, <?= (float) ($r['volume'] ?? 0) ?>, <?= (int) ($r['ahsp_id'] ?? 0) ?>)"
                                                                    title="Edit Lowongan">
                                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-success px-3 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; background: linear-gradient(135deg, #28a745, #20c997); border:none; color:#fff; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.15); transition: all 0.2s;"
                                                                onclick="openBuatLowonganModal(event, <?= $targetId ?>, '<?= esc($actName) ?>', <?= (float) ($r['ahsp_tenaga_kerja_total'] ?? 0) ?>, <?= (float) ($r['volume'] ?? 0) ?>, <?= (int) ($r['ahsp_id'] ?? 0) ?>)">
                                                                <i class="fas fa-plus me-1"></i> Buat Loker
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if ($noRab === 1): ?>
                                                <tr class="empty-search-row">
                                                    <td colspan="5" class="text-center py-5 text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-secondary opacity-50"></i>
                                                        <p class="small mb-0">Belum ada target pekerjaan RAB utama yang terjadwal.</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Addendum -->
                        <div class="tab-pane fade" id="panel-addendum" role="tabpanel" aria-labelledby="tab-addendum-btn">
                            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1.5px solid #e2e8f0;">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-loker mb-0" style="font-size: 13.5px;">
                                        <thead class="text-uppercase" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                            <tr>
                                                <th class="ps-4" style="width: 50px;">No</th>
                                                <th>Pekerjaan</th>
                                                <th>Jadwal Target</th>
                                                <th>Status Lowongan</th>
                                                <th class="text-end pe-4" style="width: 260px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $noAdd = 1;
                                            foreach ($addendum ?? [] as $r): 
                                                $addId = $r['id'] ?? 0;
                                                $tgt = $targetByAddendumId[$addId] ?? null;
                                                $targetId = $tgt['id'] ?? null;
                                                $actName = $r['activity_name'] ?? '';
                                                $startWeek = (int) ($tgt['start_week'] ?? 0);
                                                $endWeek = (int) ($tgt['end_week'] ?? 0);
                                                
                                                if (!$targetId) continue;
                                                
                                                $job = $jobs_by_target[$targetId] ?? null;
                                                $jobApplicants = $applicants_by_target[$targetId] ?? [];
                                                $numApplicants = count($jobApplicants);
                                                $searchText = strtolower($actName . ' ' . ($r['group_name'] ?? '') . ' ' . ($r['sub_group_name'] ?? ''));
                                            ?>
                                                <tr class="loker-row" data-search-text="<?= esc($searchText) ?>">
                                                    <td class="ps-4 text-muted fw-bold"><?= $noAdd++ ?></td>
                                                    <td>
                                                        <div class="fw-bold text-dark">[ADD] <?= esc($actName) ?></div>
                                                        <span class="text-muted small"><?= esc($r['group_name'] ?? '') ?> <?= $r['sub_group_name'] ? ' — ' . esc($r['sub_group_name']) : '' ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($startWeek > 0): ?>
                                                            <span class="badge bg-light text-warning border border-warning-subtle px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">
                                                                <i class="far fa-calendar-alt me-1"></i> <?= schedRangeLabel($startWeek, $endWeek, $startDate, $workday) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($job): ?>
                                                            <?php if ((int) ($job['is_open'] ?? 1) === 1): ?>
                                                                <button type="button" class="btn btn-sm badge bg-success text-white px-2.5 py-1.5 fw-semibold toggle-job-status-btn"
                                                                    data-job-id="<?= $job['id'] ?>"
                                                                    data-applicants="<?= $numApplicants ?>"
                                                                    style="font-size: 11px; border-radius: 6px; border: none; box-shadow: 0 2px 6px rgba(25, 135, 84, 0.2);"
                                                                    onclick="toggleJobStatus(this)">
                                                                    <i class="fas fa-check-circle me-1"></i> Dibuka (<?= $numApplicants ?> Pelamar)
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-sm badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold toggle-job-status-btn"
                                                                    data-job-id="<?= $job['id'] ?>"
                                                                    data-applicants="<?= $numApplicants ?>"
                                                                    style="font-size: 11px; border-radius: 6px; border: 1px solid rgba(220, 53, 69, 0.15);"
                                                                    onclick="toggleJobStatus(this)">
                                                                    <i class="fas fa-times-circle me-1"></i> Ditutup (<?= $numApplicants ?> Pelamar)
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px; border: 1px solid rgba(108, 117, 125, 0.15);">
                                                                <i class="fas fa-exclamation-circle me-1"></i> Belum Dibuat
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <?php if ($job): ?>
                                                            <div class="d-inline-flex gap-2">
                                                                <button type="button" class="btn btn-sm px-3 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; background: linear-gradient(135deg, #4f46e5, #6366f1); color:#fff; border:none; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.15); transition: all 0.2s;"
                                                                    onclick="openLihatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>')">
                                                                    <i class="fas fa-users me-1"></i> Pelamar (<?= $numApplicants ?>)
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; border: 1px solid #cbd5e1; color:#475569; background:#fff; transition: all 0.2s;"
                                                                    onclick="openBuatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>', <?= (float) ($r['current_unit_price'] ?? 0) ?>, <?= (float) ($r['volume'] ?? 0) ?>, 0)"
                                                                    title="Edit Lowongan">
                                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-success px-3 py-1.5" style="font-size:11.5px; font-weight:600; border-radius:8px; background: linear-gradient(135deg, #28a745, #20c997); border:none; color:#fff; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.15); transition: all 0.2s;"
                                                                onclick="openBuatLowonganModal(event, <?= $targetId ?>, '[ADDENDUM] <?= esc($actName) ?>', <?= (float) ($r['current_unit_price'] ?? 0) ?>, <?= (float) ($r['volume'] ?? 0) ?>, 0)">
                                                                <i class="fas fa-plus me-1"></i> Buat Loker
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if ($noAdd === 1): ?>
                                                <tr class="empty-search-row">
                                                    <td colspan="5" class="text-center py-5 text-muted">
                                                        <i class="fas fa-info-circle fa-2x mb-2 text-secondary opacity-50"></i>
                                                        <p class="small mb-0">Belum ada target pekerjaan Addendum yang terjadwal.</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 18px 24px; background-color: #fafbfc;">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; font-size: 13px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>