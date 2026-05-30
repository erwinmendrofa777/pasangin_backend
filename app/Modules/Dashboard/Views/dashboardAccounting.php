<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Accounting
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== PREMIUM TABULAR DASHBOARD STYLES ===== */
  .dashboard-container {
    padding: 16px 0 32px 0;
    animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
  }

  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* --- WELCOME HEADER --- */
  .welcome-header {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px 32px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 5px solid #0d6efd;
  }
  .header-left h1 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 4px;
  }
  .header-left h1 span {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .header-left p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0;
  }
  .role-badge {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  /* --- CUSTOM TABS --- */
  .custom-tabs {
    display: flex;
    gap: 12px;
    background: #ffffff;
    padding: 10px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    margin-bottom: 28px;
    overflow-x: auto;
  }
  .custom-tab-btn {
    border: none;
    background: transparent;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
  }
  .custom-tab-btn:hover {
    color: #0d6efd;
    background: rgba(13, 110, 253, 0.05);
  }
  .custom-tab-btn.active {
    background: #0d6efd;
    color: #ffffff;
    box-shadow: 0 6px 16px rgba(13, 110, 253, 0.25);
  }
  .tab-pane {
    display: none;
    animation: tabFadeIn 0.5s ease both;
  }
  .tab-pane.active {
    display: block;
  }
  @keyframes tabFadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* --- KPI CARDS (Compact for Top Row) --- */
  .stat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.015);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.03);
  }
  .stat-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #e2e8f0;
  }
  .stat-card.card-primary::before { background: #0d6efd; }
  .stat-card.card-indigo::before { background: #6366f1; }
  .stat-card.card-amber::before { background: #f59e0b; }
  .stat-card.card-emerald::before { background: #10b981; }

  .stat-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
  }
  .stat-value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
  }
  .stat-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
  }
  .card-primary .stat-icon-wrapper { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
  .card-indigo .stat-icon-wrapper { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
  .card-amber .stat-icon-wrapper { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
  .card-emerald .stat-icon-wrapper { background: rgba(16, 185, 129, 0.1); color: #10b981; }

  /* --- PREMIUM CONTAINERS (Charts & Tables) --- */
  .premium-card {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.015);
    border: 1px solid #f1f5f9;
    margin-bottom: 24px;
    overflow: hidden;
  }
  .premium-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .premium-card-header h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .premium-card-body {
    padding: 24px;
  }

  /* --- CLEAN TABLES --- */
  .premium-table {
    margin: 0;
    width: 100%;
  }
  .premium-table th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 20px;
    border-bottom: 1px solid #e2e8f0;
  }
  .premium-table td {
    padding: 16px 20px;
    vertical-align: middle;
    color: #334155;
    font-size: 0.85rem;
    border-bottom: 1px solid #f1f5f9;
  }
  .premium-table tr:hover td {
    background-color: #fafbfd;
  }
  
  /* --- BADGES & PROGRESS BARS --- */
  .badge-status {
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .status-paid { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
  .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
  .status-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
  
  .badge-tag {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    border: 1px solid #cbd5e1;
  }

  .custom-progress {
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
    width: 100%;
    min-width: 80px;
  }
  .custom-progress-bar {
    height: 100%;
    border-radius: 3px;
    transition: width 0.6s ease;
  }
  .progress-primary { background: #0d6efd; }
  .progress-emerald { background: #10b981; }
  .progress-slate { background: #64748b; }

  /* --- EMPTY STATE --- */
  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
  }
  .empty-state i {
    font-size: 2.5rem;
    margin-bottom: 12px;
    color: #cbd5e1;
  }
  .empty-state p {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
  }

  /* --- CHART LEGENDS --- */
  .chart-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    list-style: none;
    padding: 0;
    margin: 16px 0 0 0;
  }
  .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
  }
  .legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }

  @media (max-width: 768px) {
    .custom-tabs { flex-wrap: nowrap; overflow-x: auto; }
    .welcome-header { flex-direction: column; align-items: flex-start; gap: 16px; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. WELCOME HEADER -->
  <div class="welcome-header">
    <div class="header-left">
      <h1>Dashboard <span>Accounting</span></h1>
      <p>Ringkasan performa finansial, progres proyek, dan pengelolaan saldo tukang terkini.</p>
    </div>
    <div class="header-right">
      <span class="role-badge">
        <i class="fas fa-wallet"></i> Divisi Accounting
      </span>
    </div>
  </div>

  <!-- 2. CUSTOM TABS NAVIGATION -->
  <div class="custom-tabs">
    <button class="custom-tab-btn active" data-target="tab-ikhtisar">
      <i class="fas fa-chart-line"></i> Ikhtisar Keuangan
    </button>
    <button class="custom-tab-btn" data-target="tab-proyek">
      <i class="fas fa-project-diagram"></i> Realisasi Proyek
    </button>
    <button class="custom-tab-btn" data-target="tab-wallet">
      <i class="fas fa-money-check-alt"></i> Dompet & Voucher
    </button>
  </div>

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

  <!-- ============================================== -->
  <!-- TAB 1: IKHTISAR KEUANGAN -->
  <!-- ============================================== -->
  <div id="tab-ikhtisar" class="tab-pane active">
    <div class="row">
      <!-- Arus Kas Bulanan -->
      <div class="col-12 col-xl-8 mb-4">
        <div class="premium-card h-100 mb-0">
          <div class="premium-card-header">
            <h4><i class="fas fa-chart-area text-primary"></i> Tren Arus Kas Bulanan (6 Bulan Terakhir)</h4>
          </div>
          <div class="premium-card-body">
            <div style="position: relative; height: 280px; width: 100%;">
              <canvas id="cashflowTrendChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <!-- Kontribusi Pendapatan Divisi -->
      <div class="col-12 col-xl-4 mb-4">
        <div class="premium-card h-100 mb-0">
          <div class="premium-card-header">
            <h4><i class="fas fa-chart-pie text-indigo"></i> Kontribusi Pendapatan Divisi</h4>
          </div>
          <div class="premium-card-body d-flex flex-column justify-content-center">
            <div style="position: relative; height: 210px; width: 100%;">
              <canvas id="divisionRevenueChart"></canvas>
            </div>
            <ul id="divisionRevenueLegend" class="chart-legend"></ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Log Invoice Lintas Divisi -->
    <div class="premium-card">
      <div class="premium-card-header">
        <h4><i class="fas fa-list-alt text-primary"></i> 5 Log Invoice Lintas Divisi Terbaru</h4>
        <a href="<?= base_url('admin/design') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Semua Invoice</a>
      </div>
      <div class="p-0 table-responsive">
        <?php if (empty($accountingStats['recentInvoices'])): ?>
          <div class="empty-state">
            <i class="fas fa-file-invoice"></i><p>Belum ada data invoice saat ini.</p>
          </div>
        <?php else: ?>
          <table class="table premium-table">
            <thead>
              <tr>
                <th>Divisi</th>
                <th>ID Invoice</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Voucher</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($accountingStats['recentInvoices'] as $inv): ?>
                <tr>
                  <td><span class="badge-tag"><?= esc($inv['tipe']) ?></span></td>
                  <td class="fw-bold text-dark">#<?= esc($inv['id']) ?></td>
                  <td class="text-truncate" style="max-width: 200px;"><?= esc($inv['description'] ?: 'Pembayaran Proyek') ?></td>
                  <td class="fw-bold text-primary">Rp <?= number_format($inv['amount'], 0, ',', '.') ?></td>
                  <td>
                    <?php if ($inv['voucher_code']): ?>
                      <span class="badge badge-light text-dark shadow-sm border" style="font-size:0.7rem; border-radius:6px;">
                        <i class="fas fa-tag text-primary me-1"></i><?= esc($inv['voucher_code']) ?>
                      </span>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if (strtoupper($inv['status']) == 'PAID'): ?>
                      <span class="badge-status status-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                    <?php else: ?>
                      <span class="badge-status status-pending"><i class="fas fa-clock"></i> PENDING</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

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
                    <a href="<?= esc($p['detail_url']) ?>" class="btn btn-sm btn-outline-primary px-3 py-1 fw-bold" style="border-radius:30px; font-size:0.7rem; border-color:#0d6efd; color:#0d6efd;">
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

  <!-- ============================================== -->
  <!-- TAB 3: DOMPET & VOUCHER -->
  <!-- ============================================== -->
  <div id="tab-wallet" class="tab-pane">
    <!-- Ringkasan Dompet & Voucher -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-6">
        <div class="premium-card mb-0 p-4 text-center border-0 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b, #0f172a);">
          <div class="text-start">
            <div class="text-light fw-bold" style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.8;">Total Saldo Tukang (Liabilitas)</div>
            <div class="fw-bold text-white mt-1" style="font-size: 1.4rem;">Rp <?= number_format($accountingStats['kpis']['total_tukang_balance'], 0, ',', '.') ?></div>
          </div>
          <i class="fas fa-wallet text-white" style="font-size: 2.5rem; opacity: 0.3;"></i>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="premium-card mb-0 p-4 text-center border-0 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: linear-gradient(135deg, #0d6efd, #0a58ca);">
          <div class="text-start">
            <div class="text-light fw-bold" style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.8;">Total Penghematan Voucher</div>
            <div class="fw-bold text-white mt-1" style="font-size: 1.4rem;">Rp <?= number_format($accountingStats['kpis']['total_voucher_discount'], 0, ',', '.') ?></div>
          </div>
          <i class="fas fa-ticket-alt text-white" style="font-size: 2.5rem; opacity: 0.3;"></i>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Antrean Tarik Dana Terkini -->
      <div class="col-12 col-xl-7 mb-4">
        <div class="premium-card h-100 mb-0">
          <div class="premium-card-header">
            <h4><i class="fas fa-hand-holding-usd text-amber"></i> 5 Antrean Tarik Dana Terkini</h4>
            <a href="<?= base_url('admin/wallet/withdrawals') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Kelola Semua</a>
          </div>
          <div class="p-0 table-responsive">
            <?php if (empty($accountingStats['pendingWithdrawals'])): ?>
              <div class="empty-state">
                <i class="fas fa-check-circle text-emerald"></i><p>Semua antrean tarik dana telah diproses!</p>
              </div>
            <?php else: ?>
              <table class="table premium-table">
                <thead>
                  <tr>
                    <th>Tukang</th>
                    <th>No. Telepon</th>
                    <th>Nominal</th>
                    <th>Tgl Pengajuan</th>
                    <th class="text-center">Aksi Cepat</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($accountingStats['pendingWithdrawals'] as $w): ?>
                    <tr>
                      <td class="fw-bold text-dark"><?= esc($w['tukang_name']) ?></td>
                      <td><?= esc($w['phone']) ?></td>
                      <td class="fw-bold text-amber">Rp <?= number_format($w['amount'], 0, ',', '.') ?></td>
                      <td style="font-size: 0.8rem;"><?= date('d M Y', strtotime($w['created_at'])) ?></td>
                      <td class="text-center d-flex gap-1 justify-content-center">
                        <a href="<?= base_url('admin/wallet/withdraw-approve/' . $w['id'] . '/approved') ?>" class="btn btn-sm btn-success px-2 py-1 shadow-sm" onclick="return confirm('Setujui penarikan dana ini?')" style="border-radius:6px; font-size:0.7rem;" title="Setujui">
                          <i class="fas fa-check"></i>
                        </a>
                        <a href="<?= base_url('admin/wallet/withdraw-approve/' . $w['id'] . '/rejected') ?>" class="btn btn-sm btn-danger px-2 py-1 shadow-sm" onclick="return confirm('Tolak penarikan dana ini?')" style="border-radius:6px; font-size:0.7rem;" title="Tolak">
                          <i class="fas fa-times"></i>
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

      <!-- Voucher Promo Aktif -->
      <div class="col-12 col-xl-5 mb-4">
        <div class="premium-card h-100 mb-0">
          <div class="premium-card-header">
            <h4><i class="fas fa-tags text-primary"></i> Voucher Promo Aktif</h4>
            <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Tambah</a>
          </div>
          <div class="p-0 table-responsive">
            <?php if (empty($accountingStats['activeVouchers'])): ?>
              <div class="empty-state">
                <i class="fas fa-ticket-alt"></i><p>Tidak ada voucher promo aktif saat ini.</p>
              </div>
            <?php else: ?>
              <table class="table premium-table">
                <thead>
                  <tr>
                    <th>Kode Voucher</th>
                    <th>Potongan</th>
                    <th>Masa Berlaku</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($accountingStats['activeVouchers'] as $v): ?>
                    <tr>
                      <td class="fw-bold text-dark">
                        <span class="badge badge-light text-primary border shadow-sm px-2 py-1" style="font-size:0.75rem; border-radius:6px;">
                          <?= esc($v['code']) ?>
                        </span>
                      </td>
                      <td class="fw-bold text-emerald">Rp <?= number_format($v['discount_nominal'], 0, ',', '.') ?></td>
                      <td style="font-size:0.8rem;"><?= date('d M Y', strtotime($v['valid_until'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. TAB SWITCHER LOGIC ---
    const tabBtns = document.querySelectorAll('.custom-tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        tabBtns.forEach(b => b.classList.remove('active'));
        tabPanes.forEach(p => p.classList.remove('active'));

        this.classList.add('active');
        const targetId = this.getAttribute('data-target');
        document.getElementById(targetId).classList.add('active');
      });
    });

    // --- 2. CHART.JS INITIALIZATION ---
    if (typeof Chart !== 'undefined') {
      const chartData = <?= json_encode($accountingStats['charts']) ?>;

      // Arus Kas Bulanan (Bar & Line Combo Chart)
      const ctxCashflow = document.getElementById('cashflowTrendChart').getContext('2d');
      new Chart(ctxCashflow, {
        type: 'bar',
        data: {
          labels: chartData.cashflow_monthly.labels,
          datasets: [
            {
              type: 'bar',
              label: 'Pendapatan Masuk (Lunas)',
              data: chartData.cashflow_monthly.income,
              backgroundColor: '#0d6efd',
              hoverBackgroundColor: '#0a58ca',
              borderWidth: 0,
              barPercentage: 0.6
            },
            {
              type: 'line',
              label: 'Dana Keluar (Pencairan Saldo)',
              data: chartData.cashflow_monthly.expense,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.05)',
              borderWidth: 3,
              fill: true,
              pointBackgroundColor: '#f59e0b',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 5,
              pointHoverRadius: 7
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 20,
              fontFamily: "'Inter', 'Segoe UI', sans-serif",
              fontSize: 11,
              fontColor: '#475569'
            }
          },
          tooltips: {
            backgroundColor: 'rgba(15, 23, 42, 0.95)',
            titleFontFamily: "'Inter', sans-serif",
            bodyFontFamily: "'Inter', sans-serif",
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(tooltipItem, data) {
                let label = data.datasets[tooltipItem.datasetIndex].label || '';
                if (label) label += ': ';
                label += 'Rp ' + tooltipItem.yLabel.toLocaleString('id-ID');
                return label;
              }
            }
          },
          scales: {
            yAxes: [{
              gridLines: { color: '#f1f5f9', drawBorder: false },
              ticks: {
                beginAtZero: true,
                fontFamily: "'Inter', sans-serif",
                fontColor: '#94a3b8',
                callback: function(value) {
                  if (value >= 1e6) return 'Rp ' + (value / 1e6).toFixed(1) + ' jt';
                  return 'Rp ' + value.toLocaleString('id-ID');
                }
              }
            }],
            xAxes: [{
              gridLines: { display: false, drawBorder: false },
              ticks: {
                fontFamily: "'Inter', sans-serif",
                fontColor: '#64748b',
                fontStyle: 'bold'
              }
            }]
          }
        }
      });

      // Kontribusi Pendapatan Divisi (Doughnut Chart)
      const ctxDivision = document.getElementById('divisionRevenueChart').getContext('2d');
      const divLabels = chartData.division_revenue.labels;
      const divValues = chartData.division_revenue.data;
      const colorPalette = ['#6366f1', '#0d6efd', '#f59e0b']; // Indigo, Teal, Amber
      
      const totalSum = divValues.reduce((a, b) => a + b, 0);

      if (totalSum === 0) {
        document.getElementById('divisionRevenueLegend').innerHTML = '<li class="legend-item"><i class="fas fa-info-circle text-muted"></i> Belum ada data pendapatan.</li>';
      } else {
        new Chart(ctxDivision, {
          type: 'doughnut',
          data: {
            labels: divLabels,
            datasets: [{
              data: divValues,
              backgroundColor: colorPalette,
              borderWidth: 3,
              borderColor: '#ffffff',
              hoverBorderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 75,
            legend: { display: false },
            tooltips: {
              backgroundColor: 'rgba(15, 23, 42, 0.95)',
              bodyFontFamily: "'Inter', sans-serif",
              padding: 12,
              cornerRadius: 8,
              callbacks: {
                label: function(tooltipItem, data) {
                  let label = data.labels[tooltipItem.index] || '';
                  if (label) label += ': ';
                  label += 'Rp ' + data.datasets[0].data[tooltipItem.index].toLocaleString('id-ID');
                  return label;
                }
              }
            }
          }
        });

        // Generate Custom Legend
        const legendList = document.getElementById('divisionRevenueLegend');
        legendList.innerHTML = '';
        divLabels.forEach((label, i) => {
          const value = divValues[i];
          const percent = ((value / totalSum) * 100).toFixed(1);
          const color = colorPalette[i];
          
          const li = document.createElement('li');
          li.className = 'legend-item';
          li.innerHTML = `<span class="legend-dot" style="background-color: ${color}"></span><span title="${label}">${label} (${percent}%)</span>`;
          legendList.appendChild(li);
        });
      }
    } else {
      console.error('Chart.js library not loaded.');
    }
  });
</script>
<?= $this->endSection() ?>
