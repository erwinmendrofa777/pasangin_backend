<!-- 2. KPI METRIC GRID -->
<div class="stats-grid">
  <!-- Antrean RAB Utama -->
  <div class="stat-card card-indigo">
    <div class="stat-info">
      <div class="stat-label">Antrean RAB Utama</div>
      <div class="stat-value"><?= (int)$estimatorStats['kpis']['queue_rab_count'] ?></div>
    </div>
    <div class="stat-icon-wrapper">
      <i class="fas fa-file-invoice-dollar"></i>
    </div>
  </div>

  <!-- Proyek Pra-RAB (Upcoming) -->
  <div class="stat-card card-purple">
    <div class="stat-info">
      <div class="stat-label">Pra-RAB (Upcoming)</div>
      <div class="stat-value"><?= (int)$estimatorStats['kpis']['upcoming_rab_count'] ?></div>
    </div>
    <div class="stat-icon-wrapper">
      <i class="fas fa-clock"></i>
    </div>
  </div>

  <!-- Proyek Pengerjaan Aktif -->
  <div class="stat-card card-amber">
    <div class="stat-info">
      <div class="stat-label">Proyek Aktif (Konstruksi/Renovasi)</div>
      <div class="stat-value"><?= (int)$estimatorStats['kpis']['total_active_project'] ?></div>
    </div>
    <div class="stat-icon-wrapper">
      <i class="fas fa-hard-hat"></i>
    </div>
  </div>

  <!-- Total Anggaran Terkelola -->
  <div class="stat-card card-emerald">
    <div class="stat-info">
      <div class="stat-label">Anggaran Terkelola</div>
      <div class="stat-value" style="font-size: 1.3rem;">Rp <?= number_format($estimatorStats['kpis']['total_estimated_budget'], 0, ',', '.') ?></div>
    </div>
    <div class="stat-icon-wrapper">
      <i class="fas fa-coins"></i>
    </div>
  </div>
</div>
