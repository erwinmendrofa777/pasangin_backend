<?php
// ========================
// DATA PREPARATION (PHP)
// ========================
$numWeeks = (int) ($renovation['week'] ?? 8);
$workday = (int) ($renovation['workday'] ?? 7);
$startDate = $renovation['start_date'] ?? null;

function schedWeekLabel(int $i, ?string $startDate, int $workday): string
{
  if (!$startDate)
    return 'MG ' . $i;
  $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
  $d = new \DateTime($startDate);
  $d->modify('+' . (($i - 1) * 7) . ' days');
  $e = (clone $d)->modify('+' . ($workday - 1) . ' days');
  return $d->format('j') . ' ' . $months[(int) $d->format('n') - 1]
    . '–' . $e->format('j') . ' ' . $months[(int) $e->format('n') - 1];
}

$grouped = [];
foreach ($rab ?? [] as $r) {
  $grouped[$r['group_name'] ?? ''][$r['sub_group_name'] ?? ''][] = $r;
}

$targetByRabId = [];
$targetByAddendumId = [];
foreach ($target_list ?? [] as $t) {
  if (!empty($t['id_renovation_rabs']))
    $targetByRabId[$t['id_renovation_rabs']] = $t;
  if (!empty($t['id_renovation_addendum']))
    $targetByAddendumId[$t['id_renovation_addendum']] = $t;
}

$groupedAddendum = [];
foreach ($addendum ?? [] as $r) {
  $groupedAddendum[$r['group_name'] ?? ''][$r['sub_group_name'] ?? ''][] = $r;
}

$renovationId = $renovation['id'] ?? '';
$totalCols = 8 + $numWeeks + 1;
$totalBobot = 100;
$totalHarga = array_sum(array_column($rab ?? [], 'total_price'));
$totalHargaAddendum = array_sum(array_column($addendum ?? [], 'total_price'));

$db = \Config\Database::connect();
$progressData = [];
if ($renovationId) {
  $progressData = $db->table('renovation_progress')
    ->select('id_renovation_targets, SUM(bobot) as total_progress')
    ->where('renovation_id', $renovationId)
    ->where('status', 'APPROVED')
    ->groupBy('id_renovation_targets')
    ->get()->getResultArray();
}
$progressByTargetId = [];
foreach ($progressData as $pd) {
  $progressByTargetId[$pd['id_renovation_targets']] = (float) $pd['total_progress'];
}

$totalRealisasiAll = $totalSelisihAll = $totalHargaRealisasiAll = $totalSelisihHargaAll = 0;
$totalRealisasiAddendumAll = $totalSelisihAddendumAll = $totalHargaRealisasiAddendumAll = $totalSelisihHargaAddendumAll = 0;
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
  rel="stylesheet">

<style>
  /* ──────────────────────────────────────────────────────
   ROOT & BASE
────────────────────────────────────────────────────── */
  :root {
    --c-primary: #4f46e5;
    --c-primary-l: #818cf8;
    --c-accent: #06b6d4;
    --c-success: #10b981;
    --c-danger: #ef4444;
    --c-warn: #f59e0b;
    --c-bg: #f5f6fa;
    --c-surface: #ffffff;
    --c-border: #e5e7eb;
    --c-text: #111827;
    --c-muted: #6b7280;
    --c-group-bg: #eef2ff;
    --c-group-txt: #3730a3;
    --c-sub-bg: #f8fafc;
    --c-sub-txt: #475569;
    --radius-card: 14px;
    --radius-sm: 8px;
    --shadow-card: 0 2px 12px rgba(79, 70, 229, .08), 0 1px 3px rgba(0, 0, 0, .05);
    --font: 'Plus Jakarta Sans', system-ui, sans-serif;
  }

  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  .sched-wrap {
    font-family: var(--font);
    color: var(--c-text);
  }

  /* ──────────────────────────────────────────────────────
   SECTION BADGES
────────────────────────────────────────────────────── */
  .sched-section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--c-primary);
    background: #eef2ff;
    border: 1.5px solid #c7d2fe;
    border-radius: 20px;
    padding: 4px 14px;
    margin-bottom: 12px;
  }

  .sched-section-badge.addendum {
    color: #b45309;
    background: #fffbeb;
    border-color: #fde68a;
  }

  /* ──────────────────────────────────────────────────────
   TABLE WRAPPER
────────────────────────────────────────────────────── */
  .tbl-outer {
    overflow-x: auto;
    border-radius: var(--radius-card);
    border: 1.5px solid var(--c-border);
    box-shadow: var(--shadow-card);
    background: var(--c-surface);
  }

  /* ──────────────────────────────────────────────────────
   SUMMARY STATS ROW
────────────────────────────────────────────────────── */
  .summary-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .stat-chip {
    flex: 1;
    min-width: 160px;
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    box-shadow: var(--shadow-card);
  }

  .stat-chip .label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--c-muted);
    margin-bottom: 4px;
  }

  .stat-chip .value {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--c-text);
  }

  .stat-chip .value.danger {
    color: var(--c-danger);
  }

  .stat-chip .value.success {
    color: var(--c-success);
  }

  /* ──────────────────────────────────────────────────────
   MAIN TABLE
────────────────────────────────────────────────────── */
  table.tbl-sched {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    min-width: 900px;
  }

  /* Sticky header */
  table.tbl-sched thead th {
    position: sticky;
    top: 0;
    z-index: 4;
    background: #f0f1ff;
    font-family: var(--font);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--c-primary);
    border-bottom: 2px solid #c7d2fe;
    padding: 10px 10px;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
  }

  table.tbl-sched thead th.th-left {
    text-align: left;
  }

  table.tbl-sched thead th.th-no {
    width: 38px;
  }

  table.tbl-sched thead th.week-th {
    min-width: 72px;
    background: #f5f3ff;
    color: #6d28d9;
    font-size: .6rem;
    padding: 6px 4px;
    border-left: 1px dashed #ddd6fe;
  }

  table.tbl-sched thead th.week-th .wk-num {
    display: block;
    font-size: .72rem;
    font-weight: 800;
    color: #4338ca;
    line-height: 1;
  }

  table.tbl-sched thead th.week-th .wk-date {
    display: block;
    font-size: .55rem;
    font-weight: 500;
    color: #7c3aed;
    opacity: .8;
    margin-top: 2px;
  }

  /* General cells */
  table.tbl-sched td {
    font-family: var(--font);
    font-size: .78rem;
    padding: 9px 10px;
    border-bottom: 1px solid var(--c-border);
    vertical-align: middle;
    color: var(--c-text);
  }

  table.tbl-sched td.td-center {
    text-align: center;
  }

  table.tbl-sched td.td-muted {
    color: var(--c-muted);
    text-align: center;
  }

  table.tbl-sched td.td-mono {
    font-variant-numeric: tabular-nums;
    text-align: right;
    font-size: .76rem;
    white-space: nowrap;
  }

  /* GROUP row */
  table.tbl-sched tr.row-group td {
    background: var(--c-group-bg);
    color: var(--c-group-txt);
    font-weight: 700;
    font-size: .75rem;
    letter-spacing: .03em;
    padding: 8px 12px;
    border-top: 2px solid #c7d2fe;
    border-bottom: 1px solid #c7d2fe;
  }

  /* SUBGROUP row */
  table.tbl-sched tr.row-sub td {
    background: var(--c-sub-bg);
    color: var(--c-sub-txt);
    font-weight: 600;
    font-size: .72rem;
    font-style: italic;
    padding: 6px 12px 6px 28px;
    border-bottom: 1px dashed var(--c-border);
  }

  /* ITEM row */
  table.tbl-sched tr.row-item {
    cursor: pointer;
    transition: background .12s;
  }

  table.tbl-sched tr.row-item:hover {
    background: #f5f3ff !important;
  }

  table.tbl-sched tr.row-item.selected {
    background: #ede9fe !important;
  }

  table.tbl-sched tr.row-item td:first-child {
    border-left: 3px solid transparent;
    transition: border-color .15s;
  }

  table.tbl-sched tr.row-item.selected td:first-child,
  table.tbl-sched tr.row-item:hover td:first-child {
    border-left-color: var(--c-primary);
  }

  /* TOTAL row */
  table.tbl-sched tr.row-total td {
    background: #f0f1ff;
    font-weight: 700;
    font-size: .78rem;
    border-top: 2px solid #c7d2fe;
    border-bottom: none;
  }

  /* Gantt bar cell */
  table.tbl-sched td.cell-bar {
    padding: 0 4px;
    text-align: center;
    border-left: 1px dashed #e5e7eb;
  }

  .gantt-bar {
    height: 12px;
    border-radius: 6px;
    background: linear-gradient(90deg, var(--c-primary), var(--c-primary-l));
    box-shadow: 0 1px 4px rgba(79, 70, 229, .35);
    min-width: 8px;
  }

  .gantt-bar.bar-add {
    background: linear-gradient(90deg, var(--c-warn), #fbbf24);
    box-shadow: 0 1px 4px rgba(245, 158, 11, .35);
  }

  /* Bobot progress pill */
  .prog-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: .7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
  }

  .prog-pill.success {
    background: #d1fae5;
    color: #065f46;
  }

  .prog-pill.danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .prog-pill.neutral {
    background: #f3f4f6;
    color: #374151;
  }

  /* Money cells */
  .money {
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
  }

  .money.danger {
    color: var(--c-danger);
    font-weight: 600;
  }

  .money.success {
    color: var(--c-success);
    font-weight: 600;
  }

  /* ──────────────────────────────────────────────────────
   FORM CARD
────────────────────────────────────────────────────── */
  .form-card {
    border: 1.5px solid var(--c-border);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-card);
    margin-top: 28px;
  }

  .form-card-header {
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .form-card-header.primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
  }

  .form-card-header.teal {
    background: linear-gradient(135deg, #0891b2, #06b6d4);
  }

  .form-card-header .hdr-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, .18);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    color: #fff;
    flex-shrink: 0;
  }

  .form-card-header h6 {
    color: #fff;
    font-family: var(--font);
    font-weight: 700;
    font-size: .85rem;
    margin: 0;
  }

  .form-card-header small {
    color: rgba(255, 255, 255, .75);
    font-size: .72rem;
    font-weight: 400;
    display: block;
    margin-top: 1px;
  }

  .form-card-body {
    padding: 20px;
    background: var(--c-surface);
  }

  .form-card-body.tint {
    background: #fafffe;
  }

  /* Custom form controls */
  .fc-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font);
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--c-muted);
    margin-bottom: 5px;
  }

  .fc-label i {
    color: var(--c-primary);
    font-size: .65rem;
  }

  .fc-ctrl {
    font-family: var(--font) !important;
    font-size: .8rem !important;
    border: 1.5px solid var(--c-border) !important;
    border-radius: var(--radius-sm) !important;
    padding: 7px 10px !important;
    transition: border-color .15s, box-shadow .15s !important;
    background: #fafbff !important;
  }

  .fc-ctrl:focus {
    border-color: var(--c-primary) !important;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
    outline: none !important;
  }

  .fc-ctrl[readonly] {
    background: #f3f4f6 !important;
    color: var(--c-muted) !important;
  }

  /* Selected item card inside form */
  .selected-item-card {
    background: #eef2ff;
    border: 1.5px solid #c7d2fe;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 16px;
    display: none;
    align-items: center;
    gap: 10px;
  }

  .selected-item-card.visible {
    display: flex;
  }

  .selected-item-card .sic-icon {
    width: 36px;
    height: 36px;
    background: var(--c-primary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .9rem;
    flex-shrink: 0;
  }

  .selected-item-card .sic-name {
    font-weight: 700;
    font-size: .82rem;
    color: var(--c-primary);
  }

  .selected-item-card .sic-meta {
    font-size: .7rem;
    color: var(--c-muted);
    margin-top: 1px;
  }

  /* Btn primary custom */
  .btn-submit-custom {
    background: linear-gradient(135deg, var(--c-primary), #7c3aed);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 8px 20px;
    font-family: var(--font);
    font-weight: 700;
    font-size: .8rem;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-submit-custom:disabled {
    opacity: .45;
    cursor: not-allowed;
    transform: none !important;
  }

  .btn-submit-custom:not(:disabled):hover {
    opacity: .9;
    transform: translateY(-1px);
  }

  .btn-teal-custom {
    background: linear-gradient(135deg, #0891b2, #06b6d4);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 8px 20px;
    font-family: var(--font);
    font-weight: 700;
    font-size: .8rem;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-teal-custom:hover {
    opacity: .88;
  }

  /* ──────────────────────────────────────────────────────
   MOBILE
────────────────────────────────────────────────────── */
  @media (max-width: 767px) {
    .summary-stats .stat-chip {
      min-width: calc(50% - 6px);
    }

    table.tbl-sched thead th:nth-child(3),
    table.tbl-sched tbody tr td:nth-child(3) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(7),
    table.tbl-sched tbody tr td:nth-child(7) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(8),
    table.tbl-sched tbody tr td:nth-child(8) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(n+9),
    table.tbl-sched tbody tr td:nth-child(n+9) {
      display: none !important;
    }

    table.tbl-sched td,
    table.tbl-sched th {
      font-size: .7rem !important;
      padding: 7px 8px !important;
    }

    .week-badge-mob {
      display: inline-block;
      font-size: .6rem;
      background: #ede9fe;
      color: #4f46e5;
      border-radius: 4px;
      padding: 1px 5px;
      margin-top: 3px;
    }

    .form-card-body .row>[class*='col-'] {
      width: 100% !important;
    }

    .btn-submit-custom,
    .btn-teal-custom {
      width: 100%;
      justify-content: center;
    }

    .scroll-hint {
      display: flex !important;
    }
  }

  @media (min-width: 768px) {
    .week-badge-mob {
      display: none;
    }

    .scroll-hint {
      display: none !important;
    }
  }

  .scroll-hint {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: .7rem;
    color: var(--c-muted);
    padding: 5px 0 2px;
    animation: swipe 2s ease-in-out infinite;
  }

  @keyframes swipe {

    0%,
    100% {
      opacity: .5;
      transform: translateX(0);
    }

    50% {
      opacity: 1;
      transform: translateX(5px);
    }
  }
</style>

<?php
/* ──────────────────────────────────────────────────────
   HELPER MACRO: render one schedule table (RAB or Addendum)
────────────────────────────────────────────────────── */
function renderSchedTable(
  string $tableId,
  array $groupedItems,
  array $targetIndex,
  float $grandTotal,
  int $numWeeks,
  ?string $startDate,
  int $workday,
  array $progressByTargetId,
  string $renovationId,
  bool $isAddendum,
  float &$outTotalRealisasi,
  float &$outTotalSelisih,
  float &$outTotalHargaReal,
  float &$outTotalHargaSelisih
): void {
  $totalCols = 8 + $numWeeks + 1;
  $barClass = $isAddendum ? 'bar-add' : '';

  echo '<div class="tbl-outer">';
  echo '<div class="scroll-hint"><i class="fas fa-arrows-alt-h"></i> Geser untuk lihat minggu</div>';
  echo '<table id="' . $tableId . '" class="tbl-sched">';

  // THEAD
  echo '<thead><tr>';
  echo '<th class="th-no th-left">No</th>';
  echo '<th class="th-left" style="min-width:280px;">Uraian Pekerjaan</th>';
  echo '<th>Jumlah Harga</th>';
  echo '<th>Bobot (%)</th>';
  echo '<th>Realisasi (%)</th>';
  echo '<th>Selisih (%)</th>';
  echo '<th style="min-width:160px;">Harga Realisasi</th>';
  echo '<th style="min-width:160px;">Selisih Harga</th>';
  for ($i = 1; $i <= $numWeeks; $i++) {
    echo '<th class="week-th">'
      . '<span class="wk-num">MG ' . $i . '</span>'
      . '<span class="wk-date">' . schedWeekLabel($i, $startDate, $workday) . '</span>'
      . '</th>';
  }
  echo '<th style="width:36px;"></th>';
  echo '</tr></thead><tbody>';

  foreach ($groupedItems as $group => $subgroups) {
    echo '<tr class="row-group"><td colspan="' . $totalCols . '">'
      . ($isAddendum ? '<i class="fas fa-plus-circle me-2 opacity-75"></i>' : '<i class="fas fa-layer-group me-2 opacity-75"></i>')
      . esc($group) . '</td></tr>';

    foreach ($subgroups as $subgroup => $items) {
      if ($subgroup !== '') {
        echo '<tr class="row-sub"><td colspan="' . $totalCols . '">▸ ' . esc($subgroup) . '</td></tr>';
      }

      foreach ($items as $idx => $item) {
        $actName = $item['activity_name'] ?? '';
        $itemId = $item['id'] ?? 0;
        $grpName = $item['group_name'] ?? '';
        $sgName = $item['sub_group_name'] ?? '';
        $total = (float) ($item['total_price'] ?? 0);
        $tgt = $targetIndex[$itemId] ?? null;
        $targetId = $tgt['id'] ?? null;
        $startWeek = (int) ($tgt['start_week'] ?? 0);
        $endWeek = (int) ($tgt['end_week'] ?? 0);
        $idJobApps = $tgt['id_job_applications'] ?? '';

        $bobot = $grandTotal > 0 ? ($total / $grandTotal) * 100 : 0;
        $realisasi = $targetId ? ($progressByTargetId[$targetId] ?? 0) : 0;
        $selisih = $bobot - $realisasi;
        $hargaReal = ($realisasi / 100) * $grandTotal;
        $hargaSel = $total - $hargaReal;
        $indent = $subgroup !== '' ? '32px' : '14px';

        $outTotalRealisasi += $realisasi;
        $outTotalSelisih += $selisih;
        $outTotalHargaReal += $hargaReal;
        $outTotalHargaSelisih += $hargaSel;

        $dataAttr = 'data-rab-id="' . ($isAddendum ? '' : $itemId) . '"';
        $dataAttr .= ' data-addendum-id="' . ($isAddendum ? $itemId : '') . '"';
        $dataAttr .= ' data-group="' . esc($grpName) . '"';
        $dataAttr .= ' data-subgroup="' . esc($sgName) . '"';
        $dataAttr .= ' data-activity="' . esc(($isAddendum ? '[ADD] ' : '') . $actName) . '"';
        $dataAttr .= ' data-bobot="' . number_format($bobot, 2, '.', '') . '"';
        $dataAttr .= ' data-job-apps="' . esc($idJobApps) . '"';

        $selCls = $selisih > 0 ? 'danger' : 'success';
        $hSelCls = $hargaSel > 0 ? 'danger' : 'success';

        echo '<tr class="row-item" ' . $dataAttr . ' onclick="selectRow(this,\'' . esc($renovationId) . '\')">';
        echo '<td class="td-muted">' . ($idx + 1) . '</td>';
        echo '<td style="padding-left:' . $indent . ';">' . esc($actName);
        if ($startWeek > 0)
          echo '<span class="week-badge-mob">MG ' . $startWeek . '–' . $endWeek . '</span>';
        echo '</td>';
        echo '<td class="td-mono money">Rp ' . number_format($total) . '</td>';
        echo '<td class="td-center"><span class="prog-pill neutral">' . number_format($bobot, 2) . '%</span></td>';
        echo '<td class="td-center"><span class="prog-pill success">' . number_format($realisasi, 2) . '%</span></td>';
        echo '<td class="td-center"><span class="prog-pill ' . $selCls . '">' . number_format($selisih, 2) . '%</span></td>';
        echo '<td class="td-mono money">Rp ' . number_format($hargaReal) . '</td>';
        echo '<td class="td-mono money ' . $hSelCls . '">Rp ' . number_format($hargaSel) . '</td>';

        for ($w = 1; $w <= $numWeeks; $w++) {
          $active = $startWeek > 0 && $w >= $startWeek && $w <= $endWeek;
          echo '<td class="cell-bar">';
          if ($active)
            echo '<div class="gantt-bar ' . $barClass . '"></div>';
          echo '</td>';
        }
        echo '<td></td>';
        echo '</tr>';
      }
    }
  }

  // TOTAL row
  $selTotalCls = $outTotalSelisih > 0 ? 'danger' : 'success';
  $hSelTotalCls = $outTotalHargaSelisih > 0 ? 'danger' : 'success';
  echo '<tr class="row-total">';
  echo '<td colspan="2" class="text-end" style="padding-right:14px;">TOTAL' . ($isAddendum ? ' ADDENDUM' : '') . '</td>';
  echo '<td class="td-mono money">Rp ' . number_format($grandTotal) . '</td>';
  echo '<td class="td-center"><span class="prog-pill neutral">100.00%</span></td>';
  echo '<td class="td-center"><span class="prog-pill success">' . number_format($outTotalRealisasi, 2) . '%</span></td>';
  echo '<td class="td-center"><span class="prog-pill ' . $selTotalCls . '">' . number_format($outTotalSelisih, 2) . '%</span></td>';
  echo '<td class="td-mono money">Rp ' . number_format($outTotalHargaReal) . '</td>';
  echo '<td class="td-mono money ' . $hSelTotalCls . '">Rp ' . number_format($outTotalHargaSelisih) . '</td>';
  echo '<td colspan="' . ($numWeeks + 1) . '"></td>';
  echo '</tr>';

  echo '</tbody></table></div>';
}
?>

<div class="sched-wrap py-3">

  <!-- ══════════════════════════════════
       SECTION: RAB
  ══════════════════════════════════ -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <span class="sched-section-badge">
      <i class="fas fa-clipboard-list"></i> Target Pekerjaan RAB
    </span>
    <span style="font-size:.7rem; color:var(--c-muted);">
      <i class="fas fa-info-circle me-1"></i>Klik baris untuk mengisi form target
    </span>
  </div>

  <?php
  renderSchedTable(
    'mainTable',
    $grouped,
    $targetByRabId,
    $totalHarga,
    $numWeeks,
    $startDate,
    $workday,
    $progressByTargetId,
    $renovationId,
    false,
    $totalRealisasiAll,
    $totalSelisihAll,
    $totalHargaRealisasiAll,
    $totalSelisihHargaAll
  );
  ?>

  <!-- Summary chips RAB -->
  <div class="summary-stats mt-3">
    <div class="stat-chip">
      <div class="label"><i class="fas fa-wallet me-1"></i>Total RAB</div>
      <div class="value">Rp <?= number_format($totalHarga) ?></div>
    </div>
    <div class="stat-chip">
      <div class="label"><i class="fas fa-chart-pie me-1"></i>Realisasi</div>
      <div class="value success"><?= number_format($totalRealisasiAll, 2) ?>%</div>
    </div>
    <div class="stat-chip">
      <div class="label"><i class="fas fa-balance-scale me-1"></i>Selisih Bobot</div>
      <div class="value <?= $totalSelisihAll > 0 ? 'danger' : 'success' ?>"><?= number_format($totalSelisihAll, 2) ?>%
      </div>
    </div>
    <div class="stat-chip">
      <div class="label"><i class="fas fa-coins me-1"></i>Selisih Harga</div>
      <div class="value <?= $totalSelisihHargaAll > 0 ? 'danger' : 'success' ?>">Rp
        <?= number_format($totalSelisihHargaAll) ?></div>
    </div>
  </div>

  <!-- ══════════════════════════════════
       SECTION: ADDENDUM (conditional)
  ══════════════════════════════════ -->
  <?php if (!empty($groupedAddendum)): ?>
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 mt-4">
        <span class="sched-section-badge addendum">
          <i class="fas fa-file-medical-alt"></i> Target Pekerjaan Addendum
        </span>
      </div>

      <?php
      renderSchedTable(
        'addendumTable',
        $groupedAddendum,
        $targetByAddendumId,
        $totalHargaAddendum,
        $numWeeks,
        $startDate,
        $workday,
        $progressByTargetId,
        $renovationId,
        true,
        $totalRealisasiAddendumAll,
        $totalSelisihAddendumAll,
        $totalHargaRealisasiAddendumAll,
        $totalSelisihHargaAddendumAll
      );
      ?>

      <div class="summary-stats mt-3">
        <div class="stat-chip">
          <div class="label"><i class="fas fa-wallet me-1"></i>Total Addendum</div>
          <div class="value">Rp <?= number_format($totalHargaAddendum) ?></div>
        </div>
        <div class="stat-chip">
          <div class="label"><i class="fas fa-chart-pie me-1"></i>Realisasi</div>
          <div class="value success"><?= number_format($totalRealisasiAddendumAll, 2) ?>%</div>
        </div>
        <div class="stat-chip">
          <div class="label"><i class="fas fa-balance-scale me-1"></i>Selisih Bobot</div>
          <div class="value <?= $totalSelisihAddendumAll > 0 ? 'danger' : 'success' ?>">
            <?= number_format($totalSelisihAddendumAll, 2) ?>%</div>
        </div>
        <div class="stat-chip">
          <div class="label"><i class="fas fa-coins me-1"></i>Selisih Harga</div>
          <div class="value <?= $totalSelisihHargaAddendumAll > 0 ? 'danger' : 'success' ?>">Rp
            <?= number_format($totalSelisihHargaAddendumAll) ?></div>
        </div>
      </div>
  <?php endif; ?>

  <!-- ══════════════════════════════════
       FORM CARD
  ══════════════════════════════════ -->
  <div class="form-card">

    <!-- ── SECTION 1: Tambah / Edit Target ── -->
    <div class="form-card-header primary">
      <div class="hdr-icon"><i class="fas fa-crosshairs"></i></div>
      <div>
        <h6>Tambah / Edit Target</h6>
        <small id="selected-info-<?= $renovationId ?>">Klik baris pekerjaan pada tabel di atas untuk memilih</small>
      </div>
    </div>

    <div class="form-card-body">
      <!-- Selected item preview -->
      <div class="selected-item-card" id="sel-card-<?= $renovationId ?>">
        <div class="sic-icon"><i class="fas fa-hammer"></i></div>
        <div>
          <div class="sic-name" id="sel-card-name-<?= $renovationId ?>">–</div>
          <div class="sic-meta" id="sel-card-meta-<?= $renovationId ?>">–</div>
        </div>
      </div>

      <form id="form-create-target" action="<?= base_url('admin/renovation/create-target/' . $renovationId) ?>"
        method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="rab_id" id="inp-rab-id-<?= $renovationId ?>" value="">
        <input type="hidden" name="addendum_id" id="inp-addendum-id-<?= $renovationId ?>" value="">

        <!-- Row 1: Pekerjaan info (readonly) -->
        <div class="row g-3 mb-3">
          <div class="col-md-3">
            <label class="fc-label"><i class="fas fa-layer-group"></i>Group</label>
            <input type="text" class="form-control fc-ctrl" id="inp-group-<?= $renovationId ?>"
              placeholder="Klik pekerjaan di atas" readonly>
          </div>
          <div class="col-md-3">
            <label class="fc-label"><i class="fas fa-sitemap"></i>Sub Grup</label>
            <input type="text" class="form-control fc-ctrl" id="inp-subgroup-<?= $renovationId ?>" placeholder="–"
              readonly>
          </div>
          <div class="col-md-6">
            <label class="fc-label"><i class="fas fa-hammer"></i>Uraian Pekerjaan</label>
            <input type="text" class="form-control fc-ctrl" id="inp-name-<?= $renovationId ?>"
              placeholder="Klik pekerjaan pada tabel…" readonly>
          </div>
        </div>

        <!-- Row 2: Config target -->
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="fc-label"><i class="fas fa-hard-hat"></i>Tukang</label>
            <select name="id_job_applications" required class="form-select fc-ctrl"
              id="inp-tukang-<?= $renovationId ?>">
              <option value="">— Pilih Tukang —</option>
              <?php foreach ($applicants ?? [] as $app): ?>
                  <option value="<?= $app['id'] ?>"><?= esc($app['tukang_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="fc-label"><i class="fas fa-percentage"></i>Bobot (%)</label>
            <input type="number" class="form-control fc-ctrl" name="bobot" id="inp-bobot-<?= $renovationId ?>"
              step="0.01" min="0" max="100" placeholder="0.00">
          </div>
          <div class="col-3 col-md-2">
            <label class="fc-label"><i class="fas fa-play"></i>Mulai (MG)</label>
            <input type="number" class="form-control fc-ctrl" name="start_week" id="inp-start-<?= $renovationId ?>"
              min="1" max="<?= $numWeeks ?>" value="1">
          </div>
          <div class="col-3 col-md-2">
            <label class="fc-label"><i class="fas fa-stop"></i>Selesai (MG)</label>
            <input type="number" class="form-control fc-ctrl" name="end_week" id="inp-end-<?= $renovationId ?>" min="1"
              max="<?= $numWeeks ?>" value="2">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn-submit-custom w-100" id="btn-submit-target-<?= $renovationId ?>" disabled>
              <i class="fas fa-save"></i> Simpan Target
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- ── SECTION 2: Atur Jadwal ── -->
    <div class="form-card-header teal">
      <div class="hdr-icon"><i class="fas fa-calendar-alt"></i></div>
      <div>
        <h6>Atur Jadwal Proyek</h6>
        <small>Perbarui durasi, hari kerja, dan tanggal mulai renovasi</small>
      </div>
    </div>

    <div class="form-card-body tint">
      <form action="<?= base_url('admin/renovation/update-schedule') ?>" method="post"
        class="row g-3 align-items-end flex-wrap">
        <?= csrf_field() ?>
        <input type="hidden" name="renovation_id" value="<?= $renovationId ?>">

        <div class="col-6 col-md-auto">
          <label class="fc-label"><i class="fas fa-hashtag"></i>Jumlah Minggu</label>
          <input type="number" class="form-control fc-ctrl" name="week" min="1" max="52" value="<?= $numWeeks ?>"
            style="width:110px;">
        </div>
        <div class="col-6 col-md-auto">
          <label class="fc-label"><i class="fas fa-briefcase"></i>Kerja / Minggu</label>
          <input type="number" class="form-control fc-ctrl" name="workday" min="1" max="7" value="<?= $workday ?>"
            style="width:110px;">
        </div>
        <div class="col-md-auto">
          <label class="fc-label"><i class="fas fa-calendar-check"></i>Tanggal Mulai</label>
          <input type="date" class="form-control fc-ctrl" name="start_date" value="<?= $startDate ?? '' ?>">
        </div>
        <div class="col-md-auto">
          <button type="submit" class="btn-teal-custom">
            <i class="fas fa-save"></i> Simpan Jadwal
          </button>
        </div>
      </form>
    </div>

  </div><!-- /form-card -->
</div><!-- /sched-wrap -->

<script>
  (function () {
    function selectRow(tr, cid) {
      // Clear previous selection across ALL tables
      document.querySelectorAll('tr.row-item.selected').forEach(function (el) {
        el.classList.remove('selected');
      });
      tr.classList.add('selected');

      var rabId = tr.getAttribute('data-rab-id') || '';
      var addendumId = tr.getAttribute('data-addendum-id') || '';
      var group = tr.getAttribute('data-group') || '';
      var subgroup = tr.getAttribute('data-subgroup') || '';
      var activity = tr.getAttribute('data-activity') || '';
      var bobot = tr.getAttribute('data-bobot') || '';
      var jobApps = tr.getAttribute('data-job-apps') || '';

      function g(id) { return document.getElementById(id); }

      var inpRab = g('inp-rab-id-' + cid);
      var inpAdd = g('inp-addendum-id-' + cid);
      var inpGroup = g('inp-group-' + cid);
      var inpSub = g('inp-subgroup-' + cid);
      var inpName = g('inp-name-' + cid);
      var inpBobot = g('inp-bobot-' + cid);
      var inpTukang = g('inp-tukang-' + cid);
      var btn = g('btn-submit-target-' + cid);
      var infoEl = g('selected-info-' + cid);
      var selCard = g('sel-card-' + cid);
      var selName = g('sel-card-name-' + cid);
      var selMeta = g('sel-card-meta-' + cid);

      if (inpRab) inpRab.value = rabId;
      if (inpAdd) inpAdd.value = addendumId;
      if (inpGroup) inpGroup.value = group;
      if (inpSub) inpSub.value = subgroup;
      if (inpName) inpName.value = activity;
      if (inpBobot) inpBobot.value = bobot;
      if (inpTukang) inpTukang.value = jobApps;
      if (btn) btn.disabled = false;

      if (infoEl) infoEl.textContent = '— ' + activity;
      if (selCard) selCard.classList.add('visible');
      if (selName) selName.textContent = activity;
      if (selMeta) selMeta.textContent = (group ? group : '') + (subgroup ? ' › ' + subgroup : '');

      var inpStart = g('inp-start-' + cid);
      if (inpStart) inpStart.focus();
    }

    // Expose globally (used in onclick attributes)
    window.selectRow = selectRow;
  })();
</script>