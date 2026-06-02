<!-- 4. DISTRIBUSI BEBAN & PROYEK MENDESAK -->
<div class="manage-grid">
  <!-- Beban Kerja Staf -->
  <div class="chart-card">
    <div class="chart-title-wrapper">
      <h4><i class="fas fa-chart-bar"></i> Distribusi Tugas Kerja Tim</h4>
    </div>
    <div class="chart-container" style="min-height: 300px;">
      <canvas id="bebanKerjaChart"></canvas>
    </div>
  </div>

  <!-- Proyek Mendesak -->
  <div class="premium-card">
    <div class="premium-card-title">
      <h4><i class="fas fa-business-time"></i> Proyek Mendesak (Deadline Terdekat)</h4>
    </div>
    <div>
      <?php if (!empty($kadivStats['critical_projects'])): ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($kadivStats['critical_projects'] as $p): ?>
            <?php
            $diff = (new DateTime($p['target_date']))->diff(new DateTime());
            $daysLeft = $diff->days;
            if ($diff->invert === 0 && $daysLeft > 0) {
                $timeText = 'Terlambat ' . $daysLeft . ' hari';
                $timeColor = 'danger';
            } else {
                $timeText = $daysLeft . ' hari lagi';
                $timeColor = ($daysLeft <= 3) ? 'danger' : (($daysLeft <= 7) ? 'warning' : 'info');
            }
            ?>
            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: #fafafc; border-left: 4px solid var(--bs-<?= $timeColor ?>);">
              <div style="max-width: 70%;">
                <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                  <?= esc($p['design_concept'] ?? 'Proyek Desain') ?>
                </div>
                <div class="text-muted" style="font-size: 0.75rem;">
                  Klien: <?= esc($p['full_name'] ?? 'Internal') ?> | Target: <?= date('d M Y', strtotime($p['target_date'])) ?>
                </div>
              </div>
              <div class="text-end">
                <span class="badge bg-<?= $timeColor ?>-subtle text-<?= $timeColor ?> fw-bold px-2 py-1" style="font-size: 0.75rem;">
                  <?= $timeText ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <div class="text-muted mb-2" style="font-size: 2.5rem;">
            <i class="far fa-calendar-check"></i>
          </div>
          <h6 class="fw-bold text-dark">Tidak Ada Tenggat Kritis</h6>
          <p class="text-muted mb-0" style="font-size: 0.8rem;">Semua proyek aman terkendali.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
