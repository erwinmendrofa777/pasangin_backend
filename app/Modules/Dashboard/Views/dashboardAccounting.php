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
    border-left: 5px solid var(--palette-primary);
  }
  .header-left h1 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 4px;
  }
  .header-left h1 span {
    background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .header-left p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0;
  }
  .role-badge {
    background: rgba(255, 92, 92, 0.1);
    color: var(--palette-primary);
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
    color: var(--palette-primary);
    background: rgba(255, 92, 92, 0.05);
  }
  .custom-tab-btn.active {
    background: var(--palette-primary);
    color: #ffffff;
    box-shadow: 0 6px 16px rgba(255, 92, 92, 0.25);
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
  .stat-card.card-primary::before { background: var(--palette-primary); }
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
  .card-primary .stat-icon-wrapper { background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); }
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
  .progress-primary { background: var(--palette-primary); }
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

  <?= $this->include('App\Modules\Dashboard\Views\components\_acc_kpi_cards') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_acc_tab_ikhtisar') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_acc_tab_proyek') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_acc_tab_wallet') ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_acc_scripts') ?>
<?= $this->endSection() ?>
