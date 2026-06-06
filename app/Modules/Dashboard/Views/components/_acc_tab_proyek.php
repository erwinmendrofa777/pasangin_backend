<!-- ============================================== -->
<!-- TAB 2: REALISASI PROYEK -->
<!-- ============================================== -->
<div id="tab-proyek" class="tab-pane">
  <!-- Ringkasan Anggaran Proyek Khusus -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="premium-card mb-0 p-3 text-center border-0 shadow-sm" style="border-radius: 12px;">
        <div class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase;">Total Nilai Kontrak</div>
        <div class="fw-bold text-indigo mt-1" style="font-size: 1.25rem;">Rp <?= number_format($accountingStats['kpis']['total_project_budget'], 0, ',', '.') ?></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="premium-card mb-0 p-3 text-center border-0 shadow-sm" style="border-radius: 12px;">
        <div class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase;">Total Realisasi Biaya</div>
        <div class="fw-bold text-emerald mt-1" style="font-size: 1.25rem;">Rp <?= number_format($accountingStats['kpis']['total_project_realization'], 0, ',', '.') ?></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="premium-card mb-0 p-3 text-center border-0 shadow-sm" style="border-radius: 12px;">
        <div class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase;">Total Selisih Anggaran</div>
        <div class="fw-bold text-dark mt-1" style="font-size: 1.25rem;">Rp <?= number_format($accountingStats['kpis']['total_project_difference'], 0, ',', '.') ?></div>
      </div>
    </div>
  </div>

  <!-- Tabel Realisasi Proyek -->
  <div class="premium-card">
    <div class="premium-card-header">
      <h4><i class="fas fa-building text-emerald"></i> Detail Realisasi Finansial Proyek (Konstruksi & Renovasi)</h4>
    </div>
    <div class="p-0 table-responsive">
      <?php if (empty($accountingStats['projectRealizations'])): ?>
        <div class="empty-state">
          <i class="fas fa-folder-open"></i><p>Tidak ada proyek aktif dengan anggaran (RAB) saat ini.</p>
        </div>
      <?php else: ?>
        <table class="table premium-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Klien / Proyek</th>
              <th>Tipe</th>
              <th>Nilai Kontrak</th>
              <th>Realisasi Harga</th>
              <th>Selisih Harga</th>
              <th style="min-width: 140px;">Progress</th>
              <th class="text-center">Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($accountingStats['projectRealizations'] as $idx => $p): ?>
              <?php 
                $progPercent = $p['budget'] > 0 ? ($p['realization'] / $p['budget']) * 100 : 0;
                $progColor = 'progress-primary';
                if ($progPercent >= 100) $progColor = 'progress-emerald';
                else if ($progPercent == 0) $progColor = 'progress-slate';
                $diffCls = $p['difference'] >= 0 ? 'text-success' : 'text-danger';
              ?>
              <tr>
                <td class="text-muted fw-bold"><?= $idx + 1 ?></td>
                <td>
                  <div class="fw-bold text-dark"><?= esc($p['name']) ?></div>
                  <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= esc($p['address']) ?></small>
                </td>
                <td><span class="badge-tag"><?= esc($p['type']) ?></span></td>
                <td class="fw-bold text-dark">Rp <?= number_format($p['budget'], 0, ',', '.') ?></td>
                <td class="fw-bold text-emerald">Rp <?= number_format($p['realization'], 0, ',', '.') ?></td>
                <td class="fw-bold <?= $diffCls ?>">Rp <?= number_format($p['difference'], 0, ',', '.') ?></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="custom-progress">
                      <div class="custom-progress-bar <?= $progColor ?>" style="width: <?= min(100, $progPercent) ?>%;"></div>
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 0.75rem;"><?= number_format($progPercent, 1) ?>%</span>
                  </div>
                </td>
                <td class="text-center">
                  <?php 
                    $statCls = 'status-pending';
                    $statusName = $p['status'];
                    if (strtoupper($statusName) === 'COMPLETED') { $statCls = 'status-paid'; $statusName = 'SELESAI'; }
                    else if (strtoupper($statusName) === 'CONSTRUCTION') { $statCls = 'status-info'; $statusName = 'KONSTRUKSI'; }
                    else if (strtoupper($statusName) === 'RAB') { $statCls = 'status-pending'; $statusName = 'RAB'; }
                    else if (strtoupper($statusName) === 'DESIGNING') { $statCls = 'status-info'; $statusName = 'DESAIN'; }
                  ?>
                  <span class="badge-status <?= $statCls ?>"><?= esc($statusName) ?></span>
                </td>
                <td class="text-center">
                  <a href="<?= esc($p['detail_url']) ?>" class="btn btn-sm btn-outline-primary px-3 py-1 fw-bold" style="border-radius:30px; font-size:0.7rem; border-color:var(--palette-primary); color:var(--palette-primary);">
                    Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
