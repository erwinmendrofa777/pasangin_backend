<!-- 2. KPI METRIC GRID -->
<div class="row g-4 mb-4">
  <!-- Banner Aktif -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card card-blue h-100 mb-0">
      <div class="stat-info">
        <div class="stat-label">Banner Aktif</div>
        <div class="stat-value"><?= number_format($creatorStats['kpis']['active_banners']) ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-image"></i>
      </div>
    </div>
  </div>

  <!-- Tips Aktif -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card card-green h-100 mb-0">
      <div class="stat-info">
        <div class="stat-label">Tips Aktif</div>
        <div class="stat-value"><?= number_format($creatorStats['kpis']['active_tips']) ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-lightbulb"></i>
      </div>
    </div>
  </div>

  <!-- Total Notifikasi -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card card-teal h-100 mb-0">
      <div class="stat-info">
        <div class="stat-label">Total Notifikasi</div>
        <div class="stat-value"><?= number_format($creatorStats['kpis']['total_notifications']) ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-bell"></i>
      </div>
    </div>
  </div>

  <!-- Konten Draft (Nonaktif) -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card card-orange h-100 mb-0">
      <div class="stat-info">
        <div class="stat-label">Draf (Nonaktif)</div>
        <div class="stat-value"><?= number_format($creatorStats['kpis']['draft_content']) ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-file-alt"></i>
      </div>
    </div>
  </div>
</div>
