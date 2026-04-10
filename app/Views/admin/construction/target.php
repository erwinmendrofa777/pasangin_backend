<?php
// ========================
// DATA PREPARATION (PHP)
// ========================
$numWeeks  = (int)($construction['week'] ?? 8);
$startDate = $construction['start_date'] ?? null;

// Helper: hitung label minggu ke-$i
function schedWeekLabel(int $i, ?string $startDate): string {
    if (!$startDate) return 'MG ' . $i;
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    $d = new \DateTime($startDate);
    $d->modify('+' . (($i - 1) * 7) . ' days');
    $e = (clone $d)->modify('+6 days');
    return $d->format('j') . ' ' . $months[(int)$d->format('n') - 1]
         . ' – '
         . $e->format('j') . ' ' . $months[(int)$e->format('n') - 1];
}

// Group $rab by group_name → sub_group_name → items[]
$grouped = [];
foreach ($rab ?? [] as $r) {
    $g  = $r['group_name']     ?? '';
    $sg = $r['sub_group_name'] ?? '';
    $grouped[$g][$sg][] = $r;
}

// Index target_list by id_construction_rabs untuk lookup start_week & end_week
$targetByRabId = [];
foreach ($target_list ?? [] as $t) {
    $rabId = $t['id_construction_rabs'] ?? null;
    if ($rabId) {
        $targetByRabId[$rabId] = $t;
    }
}

$constructionId = $construction['id'] ?? '';
$totalCols = 7 + $numWeeks + 1; // No + Uraian + Bobot + weeks + (aksi)
$totalBobot = 100;
$totalHarga = 0;

foreach ($rab ?? [] as $r) {
    $totalHarga += $r['total_price'] ?? 0;
}

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
</style>

<h2 class="visually-hidden">Tabel jadwal dan bobot pekerjaan konstruksi per minggu</h2>

<div class="py-3">
  <div class="tbl-outer">
    <table id="mainTable" class="table table-bordered table-sm table-schedule table-hover">
      <thead >
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
              <span style="font-size:10px;"><?= schedWeekLabel($i, $startDate) ?></span>
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
                $actName   = $item['activity_name'] ?? '';
                $rabItemId = $item['id'] ?? 0;
                $grpName   = $item['group_name'] ?? '';
                $sgName    = $item['sub_group_name'] ?? '';
                $total     = $item['total_price'] ?? '';
                $tgt       = $targetByRabId[$rabItemId] ?? null;
                $startWeek = (int)($tgt['start_week'] ?? 0);
                $endWeek   = (int)($tgt['end_week']   ?? 0);
                $bobot     = $total > 0 ? ($total / $totalHarga) * 100 : 0;
                $indent    = $subgroup !== '' ? '28px' : '14px';
              ?>
              <tr class="item-row text-center"
                  data-rab-id="<?= $rabItemId ?>"
                  data-group="<?= esc($grpName) ?>"
                  data-subgroup="<?= esc($sgName) ?>"
                  data-activity="<?= esc($actName) ?>"
                  data-bobot="<?= number_format($bobot, 2, '.', '') ?>"
                  onclick="selectRow(this)">
                <td class="num" style="color:#adb5bd;"><?= $idx + 1 ?></td>
                <td class="text-start" style="padding-left:<?= $indent ?>;"><?= esc($actName) ?></td>
                <td>Rp <?= number_format($total) ?></td>
                <td class="num"><?= number_format($bobot, 2) ?>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

        <tr style="background:#f8f9fa;font-weight:500;">
          <td colspan="2" class="text-end fw-bold" style="font-size:12px;font-weight:500;padding-left:14px;">TOTAL</td>
          <td class="text-center">Rp <?= number_format($totalHarga) ?></td>
          <td class="num fw-bold" style="font-weight:bold;"><?= number_format($totalBobot, 2) ?>%</td>
          <td colspan="<?= $numWeeks + 1 ?>"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="card border mt-3" style="border-radius:12px;">
    <div class="card-body p-3">
      <p class="fw-500 mb-2" style="font-size:13px; font-weight:500;">
        Tambah / Edit Target
        <small class="text-muted" id="selected-info-<?= $constructionId ?>" style="font-size:11px;"></small>
      </p>
      <form id="form-create-target" action="<?= base_url('admin/construction/create-target/' . $constructionId) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="rab_id" id="inp-rab-id-<?= $constructionId ?>" value="">
        <div class="row g-2 align-items-end">
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Group</label>
            <input type="text" class="form-control form-control-sm" id="inp-group-<?= $constructionId ?>" placeholder="Klik pekerjaan di atas" style="width:160px;" readonly>
          </div>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Sub Grup</label>
            <input type="text" class="form-control form-control-sm" id="inp-subgroup-<?= $constructionId ?>" placeholder="–" style="width:160px;" readonly>
          </div>
          <div class="col">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Uraian Pekerjaan</label>
            <input type="text" class="form-control form-control-sm" id="inp-name-<?= $constructionId ?>" placeholder="Klik pekerjaan di atas..." readonly>
          </div>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Bobot (%)</label>
            <input type="number" class="form-control form-control-sm" name="bobot" id="inp-bobot-<?= $constructionId ?>" step="0.01" min="0" max="100"
              placeholder="0.00" style="width:90px;">
          </div>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Mulai (MG ke-)</label>
            <input type="number" class="form-control form-control-sm" name="start_week" id="inp-start-<?= $constructionId ?>" min="1" max="<?= $numWeeks ?>" value="1" style="width:80px;">
          </div>
          <div class="col-auto">
            <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Selesai (MG ke-)</label>
            <input type="number" class="form-control form-control-sm" name="end_week" id="inp-end-<?= $constructionId ?>" min="1" max="<?= $numWeeks ?>" value="2" style="width:80px;">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary" id="btn-submit-target-<?= $constructionId ?>" disabled>
              <i class="fas fa-save"></i> Simpan Target
            </button>
          </div>
        </div>
      </form>

      <hr class="my-2">

      <form action="<?= base_url('admin/construction/update-schedule') ?>" method="post" class="row g-2 align-items-end">
        <?= csrf_field() ?>
        <input type="hidden" name="construction_id" value="<?= $constructionId ?>">
        <div class="col-auto">
          <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Jumlah Minggu</label>
          <input type="number" class="form-control form-control-sm" name="week" min="1" max="52"
            value="<?= $numWeeks ?>" style="width:80px;">
        </div>
        <div class="col-auto">
          <label class="form-label mb-1" style="font-size:11px; font-weight:500; color:#6c757d;">Tanggal Mulai Proyek</label>
          <input type="date" class="form-control form-control-sm" name="start_date"
            value="<?= $startDate ?? '' ?>" style="width:160px;">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Simpan Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function selectRow(tr) {
  // Hapus seleksi sebelumnya
  document.querySelectorAll('#mainTable tr.item-row.selected').forEach(function(el) {
    el.classList.remove('selected');
  });
  tr.classList.add('selected');

  var rabId    = tr.getAttribute('data-rab-id');
  var group    = tr.getAttribute('data-group');
  var subgroup = tr.getAttribute('data-subgroup');
  var activity = tr.getAttribute('data-activity');
  var bobot    = tr.getAttribute('data-bobot');
  var cid      = <?= json_encode($constructionId) ?>;

  // Isi hidden field rab_id (POST)
  var hiddenInput = document.getElementById('inp-rab-id-' + cid);
  if (hiddenInput) hiddenInput.value = rabId;

  // Isi field tampilan
  var inpGroup    = document.getElementById('inp-group-' + cid);
  var inpSubgroup = document.getElementById('inp-subgroup-' + cid);
  var inpName     = document.getElementById('inp-name-' + cid);
  var inpBobot    = document.getElementById('inp-bobot-' + cid);
  if (inpGroup)    inpGroup.value    = group;
  if (inpSubgroup) inpSubgroup.value = subgroup;
  if (inpName)     inpName.value     = activity;
  if (inpBobot)    inpBobot.value    = bobot;

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