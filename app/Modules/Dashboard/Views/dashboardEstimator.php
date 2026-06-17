<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Estimator
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== ESTIMATOR DASHBOARD PREMIUM STYLES ===== */
  .dashboard-container {
    padding: 24px 0;
    animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
  }

  @keyframes fadeSlideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Welcome Header Section */
  .welcome-header {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px 32px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 5px solid var(--palette-primary, #e53935);
    position: relative;
    overflow: hidden;
  }

  .welcome-header::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, rgba(229, 57, 53, 0.06), rgba(229, 57, 53, 0.01));
    border-radius: 50%;
    pointer-events: none;
  }

  .header-left h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #2d3748;
    margin: 0 0 6px;
  }

  .header-left h1 span {
    background: linear-gradient(135deg, var(--palette-primary, #e53935), var(--palette-primary-hover, #ff7043));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: rgba(229, 57, 53, 0.1);
    border: 1px solid rgba(229, 57, 53, 0.15);
    color: var(--palette-primary, #e53935);
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  /* Stats Grid System */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
  }

  /* --- KPI CARDS (Left Accent flat style) --- */
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
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  }

  .stat-info {
    flex: 1;
    min-width: 0;
  }

  .stat-card:hover {
    transform: translateY(-4px);
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

  .stat-card.card-primary::before  { background: var(--palette-primary, #e53935); }
  .stat-card.card-indigo::before   { background: #6366f1; }
  .stat-card.card-amber::before    { background: #f59e0b; }
  .stat-card.card-emerald::before  { background: #10b981; }
  .stat-card.card-slate::before    { background: #475569; }
  .stat-card.card-purple::before   { background: #7c3aed; }

  .stat-card.card-primary:hover  { box-shadow: 0 12px 25px rgba(229, 57, 53, 0.08) !important; border-color: rgba(229, 57, 53, 0.2); }
  .stat-card.card-indigo:hover   { box-shadow: 0 12px 25px rgba(99, 102, 241, 0.08) !important; border-color: rgba(99, 102, 241, 0.2); }
  .stat-card.card-amber:hover    { box-shadow: 0 12px 25px rgba(245, 158, 11, 0.08) !important; border-color: rgba(245, 158, 11, 0.2); }
  .stat-card.card-emerald:hover  { box-shadow: 0 12px 25px rgba(16, 185, 129, 0.08) !important; border-color: rgba(16, 185, 129, 0.2); }
  .stat-card.card-slate:hover    { box-shadow: 0 12px 25px rgba(71, 85, 105, 0.08) !important; border-color: rgba(71, 85, 105, 0.2); }
  .stat-card.card-purple:hover   { box-shadow: 0 12px 25px rgba(124, 58, 237, 0.08) !important; border-color: rgba(124, 58, 237, 0.2); }

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
    transition: all 0.3s ease;
  }

  .card-primary .stat-icon-wrapper  { background: rgba(229, 57, 53, 0.1); color: var(--palette-primary, #e53935); }
  .card-indigo .stat-icon-wrapper   { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
  .card-amber .stat-icon-wrapper    { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
  .card-emerald .stat-icon-wrapper  { background: rgba(16, 185, 129, 0.1); color: #10b981; }
  .card-slate .stat-icon-wrapper    { background: rgba(71, 85, 105, 0.1); color: #475569; }
  .card-purple .stat-icon-wrapper   { background: rgba(124, 58, 237, 0.1); color: #7c3aed; }

  .card-primary:hover .stat-icon-wrapper  { background: var(--palette-primary, #e53935); color: #ffffff; }
  .card-indigo:hover .stat-icon-wrapper   { background: #6366f1; color: #ffffff; }
  .card-amber:hover .stat-icon-wrapper    { background: #f59e0b; color: #ffffff; }
  .card-emerald:hover .stat-icon-wrapper  { background: #10b981; color: #ffffff; }
  .card-slate:hover .stat-icon-wrapper    { background: #475569; color: #ffffff; }
  .card-purple:hover .stat-icon-wrapper   { background: #7c3aed; color: #ffffff; }

  .stat-card:hover .stat-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
  }

  /* Cards Layout */
  .premium-card {
    background: #ffffff;
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    margin-bottom: 28px;
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .premium-card:hover {
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
  }

  .premium-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .premium-card .card-header h4 {
    font-size: 1.05rem;
    font-weight: 800;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .premium-card .card-header h4 i {
    color: var(--palette-primary, #e53935);
  }

  .premium-card .card-body {
    padding: 24px;
  }

  /* Tabs Styling */
  .premium-tabs-nav {
    display: flex;
    gap: 10px;
    border-bottom: none;
    margin-bottom: 20px;
    background: #f8fafc;
    padding: 6px;
    border-radius: 12px;
    width: fit-content;
  }

  .premium-tabs-nav .nav-link {
    border: none;
    background: transparent;
    color: #64748b;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 8px 20px;
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .premium-tabs-nav .nav-link:hover {
    color: var(--palette-primary, #e53935);
  }

  .premium-tabs-nav .nav-link.active {
    background: #ffffff;
    color: var(--palette-primary, #e53935);
    box-shadow: 0 4px 12px rgba(229, 57, 53, 0.08);
  }

  /* Table Style */
  .table-responsive {
    border-radius: 12px;
    overflow: hidden;
  }

  .premium-table {
    margin-bottom: 0;
  }

  .premium-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 14px 20px;
    border-bottom: 1px solid #e2e8f0;
  }

  .premium-table td {
    padding: 16px 20px;
    vertical-align: middle;
    color: #334155;
    font-size: 0.9rem;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
  }

  .premium-table tr {
    transition: all 0.2s ease;
  }

  .premium-table tr:hover {
    background-color: #fafbfd;
  }

  /* Badges */
  .status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
  }

  .badge-type-construction {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    border: 1px solid rgba(99, 102, 241, 0.15);
  }

  .badge-type-renovation {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.15);
  }

  .badge-status-survey {
    background: #fff8e6;
    color: #d97706;
    border: 1px solid #ffeeba;
  }

  .badge-status-designing {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
  }

  .badge-status-rab {
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
  }

  .badge-status-construction, .badge-status-renovation {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
  }

  /* Target Compliance Widget */
  .compliance-item {
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
  }

  .compliance-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .compliance-item:first-child {
    padding-top: 0;
  }

  .compliance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
  }

  .compliance-name {
    font-weight: 700;
    color: #334155;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 60%;
  }

  .compliance-meta {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .compliance-progress-container {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .compliance-progress-bar-wrapper {
    flex-grow: 1;
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
  }

  .compliance-progress-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .fill-complete {
    background: #10b981;
  }

  .fill-incomplete {
    background: #f59e0b;
  }

  .fill-none {
    background: #ef4444;
  }

  .compliance-percentage {
    font-size: 0.85rem;
    font-weight: 800;
    width: 48px;
    text-align: right;
  }

  .percentage-complete {
    color: #10b981;
  }

  .percentage-incomplete {
    color: #f59e0b;
  }

  .percentage-none {
    color: #ef4444;
  }

  /* Action Buttons */
  .btn-premium-action {
    background: #ffffff;
    color: var(--palette-primary, #e53935);
    border: 1px solid rgba(229, 57, 53, 0.2);
    font-weight: 700;
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-premium-action:hover {
    background: var(--palette-primary, #e53935);
    color: #ffffff;
    border-color: var(--palette-primary, #e53935);
    box-shadow: 0 4px 10px rgba(229, 57, 53, 0.15);
    text-decoration: none;
  }

  .btn-premium-action-sm {
    padding: 4px 8px;
    font-size: 0.7rem;
    border-radius: 6px;
  }

  /* Empty state */
  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
  }

  .empty-state i {
    font-size: 3rem;
    margin-bottom: 12px;
    color: #cbd5e1;
  }

  .empty-state p {
    margin: 0;
    font-weight: 600;
    font-size: 0.95rem;
  }

  /* Chart Layout Tweaks */
  .chart-container-premium {
    position: relative;
    height: 320px;
    width: 100%;
  }

  .legend-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px;
    list-style: none;
    padding: 0;
    margin: 16px 0 0 0;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
  }

  .legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }

  /* Responsive Adjustments */
  @media (max-width: 991.98px) {
    .welcome-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
      padding: 20px 24px;
    }
    
    .stats-grid {
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
  }

  @media (max-width: 575.98px) {
    .premium-tabs-nav {
      width: 100%;
      flex-direction: column;
      gap: 6px;
    }
    
    .premium-tabs-nav .nav-link {
      width: 100%;
      text-align: center;
    }

    .compliance-name {
      max-width: 45%;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. WELCOME HEADER -->
  <div class="welcome-header">
    <div class="header-left">
      <h1>Selamat Datang kembali, <span><?= esc(session()->get('full_name')) ?></span>!</h1>
      <p>Berikut adalah ringkasan pekerjaan penyusunan RAB, bobot Target mingguan, dan Addendum hari ini.</p>
    </div>
    <div class="header-right">
      <span class="role-badge">
        <i class="fas fa-calculator"></i> Estimator
      </span>
    </div>
  </div>

  <?= $this->include('App\Modules\Dashboard\Views\components\_est_kpi_cards') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_est_task_queue') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_est_charts') ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_est_scripts') ?>
<?= $this->endSection() ?>
