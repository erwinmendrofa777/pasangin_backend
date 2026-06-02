<!-- 3. TOP KPI CARDS (Always visible) -->
<div class="row g-4 mb-4">
  <!-- Total Pendapatan Terbayar -->
  <div class="col-12 col-sm-6 col-xl-3 mb-4 mb-xl-0">
    <div class="stat-card card-primary h-100">
      <div class="stat-info">
        <div class="stat-label">Pendapatan Lunas</div>
        <div class="stat-value">Rp <?= number_format($accountingStats['kpis']['total_revenue'], 0, ',', '.') ?></div>
      </div>
      <div class="stat-icon-wrapper"><i class="fas fa-money-bill-wave"></i></div>
    </div>
  </div>
  <!-- Total Piutang Aktif -->
  <div class="col-12 col-sm-6 col-xl-3 mb-4 mb-xl-0">
    <div class="stat-card card-indigo h-100">
      <div class="stat-info">
        <div class="stat-label">Total Piutang Aktif</div>
        <div class="stat-value">Rp <?= number_format($accountingStats['kpis']['total_receivables'], 0, ',', '.') ?></div>
      </div>
      <div class="stat-icon-wrapper"><i class="fas fa-file-invoice-dollar"></i></div>
    </div>
  </div>
  <!-- Antrean Payout -->
  <div class="col-12 col-sm-6 col-xl-3 mb-4 mb-xl-0">
    <div class="stat-card card-amber h-100">
      <div class="stat-info">
        <div class="stat-label">Antrean Tarik Dana</div>
        <div class="stat-value">Rp <?= number_format($accountingStats['kpis']['pending_payouts_amount'], 0, ',', '.') ?></div>
      </div>
      <div class="stat-icon-wrapper"><i class="fas fa-history"></i></div>
    </div>
  </div>
  <!-- Sisa Anggaran Proyek -->
  <div class="col-12 col-sm-6 col-xl-3 mb-0">
    <div class="stat-card card-emerald h-100">
      <div class="stat-info">
        <div class="stat-label">Efisiensi Anggaran</div>
        <div class="stat-value">Rp <?= number_format(abs($accountingStats['kpis']['total_project_difference']), 0, ',', '.') ?></div>
      </div>
      <div class="stat-icon-wrapper"><i class="fas fa-balance-scale"></i></div>
    </div>
  </div>
</div>
