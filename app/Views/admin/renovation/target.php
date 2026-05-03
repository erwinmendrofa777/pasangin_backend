<?php
// ========================
// DATA PREPARATION (PHP)
// ========================
$numWeeks = (int) ($renovation['week'] ?? 8);
$workday = (int) ($renovation['workday'] ?? 7);
$startDate = $renovation['start_date'] ?? null;

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

// Index target_list by id_renovation_rabs untuk lookup start_week & end_week
$targetByRabId = [];
$targetByAddendumId = [];
foreach ($target_list ?? [] as $t) {
  if (!empty($t['id_renovation_rabs'])) {
    $targetByRabId[$t['id_renovation_rabs']] = $t;
  }
  if (!empty($t['id_renovation_addendum'])) {
    $targetByAddendumId[$t['id_renovation_addendum']] = $t;
  }
}

// Group $addendum
$groupedAddendum = [];
foreach ($addendum ?? [] as $r) {
  $g = $r['group_name'] ?? '';
  $sg = $r['sub_group_name'] ?? '';
  $groupedAddendum[$g][$sg][] = $r;
}

$renovationId = $renovation['id'] ?? '';
$totalCols = 8 + $numWeeks + 1; // Sesuaikan dengan jumlah kolom data yang baru
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

$totalRealisasiAll = 0;
$totalSelisihAll = 0;
$totalHargaRealisasiAll = 0;
$totalSelisihHargaAll = 0;

$totalRealisasiAddendumAll = 0;
$totalSelisihAddendumAll = 0;
$totalHargaRealisasiAddendumAll = 0;
$totalSelisihHargaAddendumAll = 0;

?>

<style>
  .tbl-outer {
    overflow-x: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
  }

  table.table-schedule {
    min-width: 700px;
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

  table.table-schedule th.left {
    text-align: left;
  }

  table.table-schedule td {
    vertical-align: middle;
    font-size: 13px;
  }

  table.table-schedule td.num {
    text-align: center;
    color: #6c757d;
  }

  table.table-schedule tr.group-header td {
    background: #e8ecf3;
    font-weight: 700;
    color: #2c3e6b;
    font-size: 13px;
    border-top: 2px solid #c5cee0;
  }

  table.table-schedule tr.subgroup-header td {
    background: #f4f6f9;
    font-weight: 600;
    color: #546a8e;
    font-size: 12.5px;
    padding-left: 24px !important;
    font-style: italic;
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
    min-width: 68px;
  }

  /* Baris pekerjaan bisa diklik */
  table.table-schedule tr.item-row {
    cursor: pointer;
    transition: background 0.15s;
  }

  table.table-schedule tr.item-row:hover {
    background: #eef1ff !important;
  }

  table.table-schedule tr.item-row.selected {
    background: #dde4ff !important;
  }

  .btn-del {
    background: transparent;
    border: none;
    cursor: pointer;
    color: #adb5bd;
    font-size: 14px;
    padding: 2px 6px;
    border-radius: 4px;
    line-height: 1;
  }

  .btn-del:hover {
    color: #fc544b;
    background: #fff5f5;
  }

  /* ===== MOBILE ===== */
  @media (max-width: 767px) {
    .tbl-outer {
      -webkit-overflow-scrolling: touch;
    }

    /* Jadikan section-title lebih kecil */
    .section-title {
      font-size: 0.78rem !important;
      margin-bottom: 6px !important;
    }

    /* Form panel: stack semua kolom jadi full-width */
    .card .row.g-2>[class*='col-'] {
      width: 100% !important;
      flex: 0 0 100% !important;
      max-width: 100% !important;
    }

    .card .col-auto {
      width: 100% !important;
    }

    /* Input & select jadi full-width di form panel */
    .card .form-control,
    .card .form-select {
      width: 100% !important;
      max-width: 100% !important;
    }

    /* Tombol simpan full-width */
    .card .btn {
      width: 100%;
      text-align: center;
    }

    /* ---- TABLE: Sembunyikan kolom non-esensial ---- */
    /* col-3: JUMLAH HARGA */
    table.table-schedule thead tr th:nth-child(3),
    table.table-schedule tbody tr.item-row td:nth-child(3),
    table.table-schedule tbody tr.total-row td:nth-child(2),
    table.table-schedule tbody tr.total-row-add td:nth-child(2) {
      display: none !important;
    }

    /* col-7: JUMLAH HARGA REALISASI */
    table.table-schedule thead tr th:nth-child(7),
    table.table-schedule tbody tr.item-row td:nth-child(7),
    table.table-schedule tbody tr.total-row td:nth-child(6),
    table.table-schedule tbody tr.total-row-add td:nth-child(6) {
      display: none !important;
    }

    /* col-8: SELISIH JUMLAH HARGA */
    table.table-schedule thead tr th:nth-child(8),
    table.table-schedule tbody tr.item-row td:nth-child(8),
    table.table-schedule tbody tr.total-row td:nth-child(7),
    table.table-schedule tbody tr.total-row-add td:nth-child(7) {
      display: none !important;
    }

    /* col-9+: Minggu (Gantt chart) + kolom hapus */
    table.table-schedule thead tr th:nth-child(n+9),
    table.table-schedule tbody tr.item-row td:nth-child(n+9),
    table.table-schedule tbody tr.total-row td:nth-child(n+8),
    table.table-schedule tbody tr.total-row-add td:nth-child(n+8) {
      display: none !important;
    }

    /* Kompakkan sel yang tersisa */
    table.table-schedule th,
    table.table-schedule td {
      font-size: 11px !important;
      padding: 6px 8px !important;
    }

    /* Kolom uraian: boleh wrap teks */
    table.table-schedule thead tr th:nth-child(2),
    table.table-schedule tbody tr.item-row td:nth-child(2) {
      white-space: normal;
      min-width: 130px;
      max-width: 200px;
    }

    /* Kolom NO lebih sempit */
    table.table-schedule thead tr th:nth-child(1),
    table.table-schedule tbody tr td:nth-child(1) {
      min-width: 28px;
      width: 28px;
    }

    /* Badge minggu di mobile (rangkuman Gantt) */
    .week-badge-mobile {
      display: inline-block;
      font-size: 10px;
      background: #e8ecff;
      color: #6777ef;
      border-radius: 4px;
      padding: 1px 5px;
      margin-top: 2px;
    }
  }

  @media (min-width: 768px) {
    .week-badge-mobile {
      display: none;
    }

    .mobile-scroll-hint {
      display: none !important;
    }
  }

  /* Scroll hint */
  .mobile-scroll-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.72rem;
    color: #6c757d;
    padding: 5px 0 3px;
    animation: swipeHintAnim 2s ease-in-out infinite;
  }

  @keyframes swipeHintAnim {

    0%,
    100% {
      opacity: 0.5;
      transform: translateX(0);
    }

    50% {
      opacity: 1;
      transform: translateX(4px);
    }
  }
</style>

<div class="py-3">
  <div class="section-title mb-0 pb-2 text-primary" style="margin-top: 10px;">TARGET PEKERJAAN RAB</div>
  <div class="tbl-outer">
    <table id="mainTable" class="table table-bordered table-sm table-schedule table-hover mt-0 pt-0">
      <thead>
        <tr>
          <th class="left fw-bold" rowspan="2" style="width:36px;">NO</th>
          <th class="left fw-bold text-center" rowspan="2" style="min-width:320px;">URAIAN PEKERJAAN</th>
          <th rowspan="2" class="px-2 fw-bold" style="min-width:150px;">JUMLAH HARGA</th>
          <th rowspan="2" class="px-2 fw-bold" style="width:60px;">BOBOT (%)</th>
          <th rowspan="2" class="px-2 fw-bold" style="width:60px;">BOBOT REALISASI (%)</th>
          <th rowspan="2" class="px-2 fw-bold" style="width:60px;">SELISIH BOBOT (%)</th>
          <th rowspan="2" class="px-2 fw-bold" style="width:250px;">JUMLAH HARGA REALISASI</th>
          <th rowspan="2" class="px-2 fw-bold" style="width:250px;">SELISIH JUMLAH HARGA</th>
          <?php for ($i = 1; $i <= $numWeeks; $i++): ?>
            <th class="week-th">
              <span style="display:block;font-size:10px;color:#6c757d;">MG <?= $i ?></span>
              <span style="font-size:10px;"><?= schedWeekLabel($i, $startDate, $workday) ?></span>
            </th>
          <?php endfor; ?>
          <th style="width:36px;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($grouped as $group => $subgroups): ?>
          <?php /* GROUP HEADER */ ?>
          <tr class="group-header">
            <td colspan="<?= $totalCols ?>" style="padding-left:10px;"><?= esc($group) ?></td>
          </tr>

          <?php foreach ($subgroups as $subgroup => $items): ?>
            <?php if ($subgroup !== ''): ?>
              <?php /* SUBGROUP HEADER */ ?>
              <tr class="subgroup-header">
                <td colspan="<?= $totalCols ?>">▸ <?= esc($subgroup) ?></td>
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

              // Kalkulasi Bobot
              $bobot = $totalHarga > 0 ? ($total / $totalHarga) * 100 : 0;
              $realisasi = $targetId ? ($progressByTargetId[$targetId] ?? 0) : 0;
              $selisih = $bobot - $realisasi;

              // Kalkulasi Harga
              $hargaRealisasi = ($realisasi / 100) * $totalHarga;
              $hargaSelisih = $total - $hargaRealisasi;

              $indent = $subgroup !== '' ? '28px' : '14px';

              // Update total keseluruhan
              $totalRealisasiAll += $realisasi;
              $totalSelisihAll += $selisih;
              $totalHargaRealisasiAll += $hargaRealisasi;
              $totalSelisihHargaAll += $hargaSelisih;
              ?>
              <tr class="item-row text-center" data-rab-id="<?= $rabItemId ?>" data-group="<?= esc($grpName) ?>"
                data-subgroup="<?= esc($sgName) ?>" data-activity="<?= esc($actName) ?>"
                data-bobot="<?= number_format($bobot, 2, '.', '') ?>" data-job-apps="<?= esc($idJobApps) ?>"
                onclick="selectRow(this)">
                <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                <td class="text-start" style="padding-left:<?= $indent ?>;">
                  <?= esc($actName) ?>
                  <?php if ($startWeek > 0): ?>
                    <span class="week-badge-mobile">MG <?= $startWeek ?>–<?= $endWeek ?></span>
                  <?php endif; ?>
                </td>
                <td>Rp <?= number_format($total) ?></td>
                <td class="num"><?= number_format($bobot, 2) ?>%</td>
                <td class="num text-success"><?= number_format($realisasi, 2) ?>%</td>
                <td class="num <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                  <?= number_format($selisih, 2) ?>%
                </td>
                <td>Rp <?= number_format($hargaRealisasi) ?></td>
                <td class="<?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                  <?= number_format($hargaSelisih) ?>
                </td>
                <?php for ($w = 1; $w <= $numWeeks; $w++): ?>
                  <?php
                  $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                  ?>
                  <td class="cell-bar <?= $isActive ? ' week-active' : '' ?>">
                    <?php if ($isActive): ?>
                      <div class="bar"></div>
                    <?php endif; ?>
                  </td>
                <?php endfor; ?>
                <td class="cell-bar"></td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        <?php endforeach; ?>

        <tr style="background:#f8f9fa;font-weight:500;" class="total-row">
          <td colspan="2" class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
            TOTAL</td>
          <td class="text-center">Rp <?= number_format($totalHarga) ?></td>
          <td class="num fw-bold" style="font-weight:bold;"><?= number_format($totalBobot, 2) ?>%</td>
          <td class="num fw-bold text-success"><?= number_format($totalRealisasiAll, 2) ?>%</td>
          <td class="num fw-bold <?= $totalSelisihAll > 0 ? 'text-danger' : 'text-success' ?>">
            <?= number_format($totalSelisihAll, 2) ?>%
          </td>
          <td class="text-center">Rp <?= number_format($totalHargaRealisasiAll) ?></td>
          <td class="text-center <?= $totalSelisihHargaAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
            <?= number_format($totalSelisihHargaAll) ?>
          </td>
          <td colspan="<?= $numWeeks + 1 ?>"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php if (!empty($groupedAddendum)): ?>
    <div class="section-title text-primary">TARGET PEKERJAAN ADDENDUM</div>
    <div class="tbl-outer">
      <table id="addendumTable" class="table table-bordered table-sm table-schedule table-hover">
        <thead>
          <tr>
            <th class="left fw-bold" rowspan="2" style="width:36px;">NO</th>
            <th class="left fw-bold text-center" rowspan="2" style="min-width:320px;">URAIAN PEKERJAAN ADDENDUM
            </th>
            <th rowspan="2" class="px-2 fw-bold" style="min-width:150px;">JUMLAH HARGA</th>
            <th rowspan="2" class="px-2 fw-bold" style="width:60px;">BOBOT (%)</th>
            <th rowspan="2" class="px-2 fw-bold" style="width:60px;">BOBOT REALISASI (%)</th>
            <th rowspan="2" class="px-2 fw-bold" style="width:60px;">SELISIH BOBOT (%)</th>
            <th rowspan="2" class="px-2 fw-bold" style="width:250px;">JUMLAH HARGA REALISASI</th>
            <th rowspan="2" class="px-2 fw-bold" style="width:250px;">SELISIH JUMLAH HARGA</th>
            <?php for ($i = 1; $i <= $numWeeks; $i++): ?>
              <th class="week-th">
                <span style="display:block;font-size:10px;color:#6c757d;">MG <?= $i ?></span>
                <span style="font-size:10px;"><?= schedWeekLabel($i, $startDate, $workday) ?></span>
              </th>
            <?php endfor; ?>
            <th style="width:36px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($groupedAddendum as $group => $subgroups): ?>
            <?php /* GROUP HEADER */ ?>
            <tr class="group-header">
              <td colspan="<?= $totalCols ?>" style="padding-left:10px;">ADD - <?= esc($group) ?></td>
            </tr>

            <?php foreach ($subgroups as $subgroup => $items): ?>
              <?php if ($subgroup !== ''): ?>
                <?php /* SUBGROUP HEADER */ ?>
                <tr class="subgroup-header">
                  <td colspan="<?= $totalCols ?>">▸ <?= esc($subgroup) ?></td>
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

                // Kalkulasi Bobot
                $bobot = $totalHargaAddendum > 0 ? ($total / $totalHargaAddendum) * 100 : 0;
                $realisasi = $targetId ? ($progressByTargetId[$targetId] ?? 0) : 0;
                $selisih = $bobot - $realisasi;

                // Kalkulasi Harga
                $hargaRealisasi = ($realisasi / 100) * $totalHargaAddendum;
                $hargaSelisih = $total - $hargaRealisasi;

                $indent = $subgroup !== '' ? '28px' : '14px';

                // Update total keseluruhan
                $totalRealisasiAddendumAll += $realisasi;
                $totalSelisihAddendumAll += $selisih;
                $totalHargaRealisasiAddendumAll += $hargaRealisasi;
                $totalSelisihHargaAddendumAll += $hargaSelisih;
                ?>
                <tr class="item-row text-center" data-rab-id="" data-addendum-id="<?= $addId ?>"
                  data-group="<?= esc($grpName) ?>" data-subgroup="<?= esc($sgName) ?>"
                  data-activity="[ADDENDUM] <?= esc($actName) ?>" data-bobot="<?= number_format($bobot, 2, '.', '') ?>"
                  data-job-apps="<?= esc($idJobApps) ?>" onclick="selectRow(this)">
                  <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                  <td class="text-start" style="padding-left:<?= $indent ?>;"><?= esc($actName) ?></td>
                  <td>Rp <?= number_format($total) ?></td>
                  <td class="num"><?= number_format($bobot, 2) ?>%</td>
                  <td class="num text-success"><?= number_format($realisasi, 2) ?>%</td>
                  <td class="num <?= $selisih > 0 ? 'text-danger' : 'text-success' ?>">
                    <?= number_format($selisih, 2) ?>%
                  </td>
                  <td>Rp <?= number_format($hargaRealisasi) ?></td>
                  <td class="<?= $hargaSelisih > 0 ? 'text-danger' : 'text-success' ?>">Rp
                    <?= number_format($hargaSelisih) ?>
                  </td>
                  <?php for ($w = 1; $w <= $numWeeks; $w++): ?>
                    <?php
                    $isActive = ($startWeek > 0 && $w >= $startWeek && $w <= $endWeek);
                    ?>
                    <td class="cell-bar <?= $isActive ? ' week-active' : '' ?>">
                      <?php if ($isActive): ?>
                        <div class="bar bg-warning"></div>
                      <?php endif; ?>
                    </td>
                  <?php endfor; ?>
                  <td class="cell-bar"></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>

          <tr style="background:#f8f9fa;font-weight:500;" class="total-row-add">
            <td colspan="2" class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">
              TOTAL ADDENDUM</td>
            <td class="text-center">Rp <?= number_format($totalHargaAddendum) ?></td>
            <td class="num fw-bold" style="font-weight:bold;">100.00%</td>
            <td class="num fw-bold text-success"><?= number_format($totalRealisasiAddendumAll, 2) ?>%</td>
            <td class="num fw-bold <?= $totalSelisihAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">
              <?= number_format($totalSelisihAddendumAll, 2) ?>%
            </td>
            <td class="text-center">Rp <?= number_format($totalHargaRealisasiAddendumAll) ?></td>
            <td class="text-center <?= $totalSelisihHargaAddendumAll > 0 ? 'text-danger' : 'text-success' ?>">Rp
              <?= number_format($totalSelisihHargaAddendumAll) ?>
            </td>
            <td colspan="<?= $numWeeks + 1 ?>"></td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <!-- ===== PREMIUM FORM PANEL ===== -->
  <div class="card mt-4"
    style="border:none; border-radius:16px; box-shadow: 0 6px 24px rgba(103,119,239,0.10), 0 2px 8px rgba(0,0,0,0.04); overflow:hidden;">

    <!-- SECTION 1: Tambah / Edit Target -->
    <div style="background: linear-gradient(135deg,#6777ef 0%,#7e8ef5 100%); padding:14px 20px;">
      <h6 class="text-white mb-0" style="font-size:0.9rem; font-weight:700;">
        <i class="fas fa-crosshairs mr-2"></i> Tambah / Edit Target
        <small class="ml-2" id="selected-info-<?= $renovationId ?>"
          style="font-size:0.75rem; opacity:0.85; font-weight:400;"></small>
      </h6>
    </div>
    <div class="card-body" style="padding:20px;">
      <form id="form-create-target" action="<?= base_url('admin/renovation/create-target/' . $renovationId) ?>"
        method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="rab_id" id="inp-rab-id-<?= $renovationId ?>" value="">
        <input type="hidden" name="addendum_id" id="inp-addendum-id-<?= $renovationId ?>" value="">

        <!-- Row 1: Pekerjaan Info -->
        <div class="row g-2 mb-3">
          <div class="col-md-3">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-layer-group mr-1 text-primary" style="font-size:0.7rem;"></i>Group
            </label>
            <input type="text" class="form-control form-control-sm" id="inp-group-<?= $renovationId ?>"
              placeholder="Klik pekerjaan di atas"
              style="border-radius:8px; border:1.5px solid #e0e4ff; background:#f8f9ff;" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-sitemap mr-1 text-primary" style="font-size:0.7rem;"></i>Sub Grup
            </label>
            <input type="text" class="form-control form-control-sm" id="inp-subgroup-<?= $renovationId ?>"
              placeholder="–" style="border-radius:8px; border:1.5px solid #e0e4ff; background:#f8f9ff;" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-hammer mr-1 text-primary" style="font-size:0.7rem;"></i>Uraian Pekerjaan
            </label>
            <input type="text" class="form-control form-control-sm" id="inp-name-<?= $renovationId ?>"
              placeholder="Klik pekerjaan pada tabel di atas..."
              style="border-radius:8px; border:1.5px solid #e0e4ff; background:#f8f9ff;" readonly>
          </div>
        </div>

        <!-- Row 2: Target Config -->
        <div class="row g-2 align-items-end">
          <div class="col-4">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-hard-hat mr-1 text-primary" style="font-size:0.7rem;"></i>Tukang
            </label>
            <select name="id_job_applications" required class="form-select form-select-sm"
              id="inp-tukang-<?= $renovationId ?>" style="border-radius:8px; border:1.5px solid #e0e4ff;">
              <option value="">Pilih Tukang</option>
              <?php foreach ($applicants ?? [] as $app): ?>
                <option value="<?= $app['id'] ?>"><?= esc($app['tukang_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-2">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-percentage mr-1 text-primary" style="font-size:0.7rem;"></i>Bobot
            </label>
            <input type="number" class="form-control form-control-sm" name="bobot" id="inp-bobot-<?= $renovationId ?>"
              step="0.01" min="0" max="100" placeholder="0.00" style="border-radius:8px; border:1.5px solid #e0e4ff;">
          </div>
          <div class="col-2">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-play mr-1 text-primary" style="font-size:0.7rem;"></i>Mulai (MG)
            </label>
            <input type="number" class="form-control form-control-sm" name="start_week"
              id="inp-start-<?= $renovationId ?>" min="1" max="<?= $numWeeks ?>" value="1"
              style="border-radius:8px; border:1.5px solid #e0e4ff;">
          </div>
          <div class="col-2">
            <label class="form-label mb-1"
              style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
              <i class="fas fa-stop mr-1 text-primary" style="font-size:0.7rem;"></i>Selesai (MG)
            </label>
            <input type="number" class="form-control form-control-sm" name="end_week" id="inp-end-<?= $renovationId ?>"
              min="1" max="<?= $numWeeks ?>" value="2" style="border-radius:8px; border:1.5px solid #e0e4ff;">
          </div>
          <div class="col-2">
            <button type="submit" class="btn btn-sm btn-primary shadow-sm" id="btn-submit-target-<?= $renovationId ?>"
              disabled style="border-radius:8px; padding:6px 18px; font-weight:600;">
              <i class="fas fa-save mr-1"></i> Simpan Target
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- SECTION 2: Atur Jadwal Proyek -->
    <div style="background: linear-gradient(135deg,#6777ef 0%,#7e8ef5 100%); padding:12px 20px;">
      <h6 class="text-white mb-0" style="font-size:0.85rem; font-weight:700;">
        <i class="fas fa-calendar-alt mr-2"></i> Atur Jadwal Proyek
      </h6>
    </div>
    <div class="card-body" style="padding:18px 20px; background:#fafffe;">
      <form action="<?= base_url('admin/renovation/update-schedule') ?>" method="post"
        class="row g-2 align-items-end flex-wrap">
        <?= csrf_field() ?>
        <input type="hidden" name="renovation_id" value="<?= $renovationId ?>">
        <div class="col-12 col-sm-auto">
          <label class="form-label mb-1"
            style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
            <i class="fas fa-hashtag mr-1 text-primary" style="font-size:0.7rem;"></i>Jumlah Minggu
          </label>
          <input type="number" class="form-control form-control-sm" name="week" min="1" max="52"
            value="<?= $numWeeks ?>" style="border-radius:8px; border:1.5px solid #e0e4ff;">
        </div>
        <div class="col-12 col-sm-auto">
          <label class="form-label mb-1"
            style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
            <i class="fas fa-briefcase mr-1 text-primary" style="font-size:0.7rem;"></i>Kerja/Minggu
          </label>
          <input type="number" class="form-control form-control-sm" name="workday" min="1" max="7"
            value="<?= $workday ?>" style="border-radius:8px; border:1.5px solid #e0e4ff;">
        </div>
        <div class="col-12 col-sm-auto">
          <label class="form-label mb-1"
            style="font-size:0.72rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">
            <i class="fas fa-calendar-check mr-1 text-primary" style="font-size:0.7rem;"></i>Tanggal Mulai
          </label>
          <input type="date" class="form-control form-control-sm" name="start_date" value="<?= $startDate ?? '' ?>"
            style="border-radius:8px; border:1.5px solid #e0e4ff;">
        </div>
        <div class="col-12 col-sm-auto">
          <button type="submit" class="btn btn-sm btn-primary shadow-sm"
            style="border-radius:8px; padding:6px 18px; font-weight:600;">
            <i class="fas fa-save mr-1"></i> Simpan Jadwal
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
  function selectRow(tr) {
    // Hapus seleksi sebelumnya
    document.querySelectorAll('#mainTable tr.item-row.selected').forEach(function (el) {
      el.classList.remove('selected');
    });
    tr.classList.add('selected');

    var rabId = tr.getAttribute('data-rab-id');
    var addendumId = tr.getAttribute('data-addendum-id');
    var group = tr.getAttribute('data-group');
    var subgroup = tr.getAttribute('data-subgroup');
    var activity = tr.getAttribute('data-activity');
    var bobot = tr.getAttribute('data-bobot');
    var jobApps = tr.getAttribute('data-job-apps');
    var cid = <?= json_encode($renovationId) ?>;

    // Isi hidden field rab_id (POST)
    var hiddenInputRab = document.getElementById('inp-rab-id-' + cid);
    if (hiddenInputRab) hiddenInputRab.value = rabId || '';

    var hiddenInputAddendum = document.getElementById('inp-addendum-id-' + cid);
    if (hiddenInputAddendum) hiddenInputAddendum.value = addendumId || '';

    // Isi field tampilan
    var inpGroup = document.getElementById('inp-group-' + cid);
    var inpSubgroup = document.getElementById('inp-subgroup-' + cid);
    var inpName = document.getElementById('inp-name-' + cid);
    var inpBobot = document.getElementById('inp-bobot-' + cid);
    var inpTukang = document.getElementById('inp-tukang-' + cid);
    if (inpGroup) inpGroup.value = group;
    if (inpSubgroup) inpSubgroup.value = subgroup;
    if (inpName) inpName.value = activity;
    if (inpBobot) inpBobot.value = bobot;
    if (inpTukang) {
      inpTukang.value = jobApps || '';
    }

    // Aktifkan tombol simpan
    var btn = document.getElementById('btn-submit-target-' + cid);
    if (btn) btn.disabled = false;

    // Info label
    var info = document.getElementById('selected-info-' + cid);
    if (info) info.textContent = '— ' + activity;

    // Fokus ke input mulai minggu
    var inpStart = document.getElementById('inp-start-' + cid);
    if (inpStart) inpStart.focus();
  }
</script>