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
        <?= number_format($totalSelisihHargaAll) ?>
      </div>
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
          <?= number_format($totalSelisihAddendumAll, 2) ?>%
        </div>
      </div>
      <div class="stat-chip">
        <div class="label"><i class="fas fa-coins me-1"></i>Selisih Harga</div>
        <div class="value <?= $totalSelisihHargaAddendumAll > 0 ? 'danger' : 'success' ?>">Rp
          <?= number_format($totalSelisihHargaAddendumAll) ?>
        </div>
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
        <h6 class="text-white">Tambah / Edit Target</h6>
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
        <h6 class="text-white">Atur Jadwal Proyek</h6>
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