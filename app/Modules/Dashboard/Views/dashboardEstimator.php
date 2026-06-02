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
    border-left: 5px solid #4e73df;
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
    background: linear-gradient(135deg, rgba(78, 115, 223, 0.06), rgba(78, 115, 223, 0.01));
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
    background: linear-gradient(135deg, #4e73df, #224abe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(78, 115, 223, 0.05));
    border: 1px solid rgba(78, 115, 223, 0.15);
    color: #4e73df;
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

  .stat-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(78, 115, 223, 0.08);
    border-color: rgba(78, 115, 223, 0.2);
  }

  .stat-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: #e2e8f0;
    transition: all 0.3s ease;
  }

  .stat-card.card-blue::after {
    background: linear-gradient(90deg, #4e73df, #224abe);
  }
  .stat-card.card-purple::after {
    background: linear-gradient(90deg, #9b51e0, #7b2cbf);
  }
  .stat-card.card-orange::after {
    background: linear-gradient(90deg, #f39c12, #d35400);
  }
  .stat-card.card-green::after {
    background: linear-gradient(90deg, #1cc88a, #138a5e);
  }

  .stat-info {
    z-index: 1;
  }

  .stat-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
  }

  .stat-value {
    font-size: 1.6rem;
    font-weight: 800;
    color: #2d3748;
    line-height: 1.2;
  }

  .stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: all 0.3s ease;
  }

  .card-blue .stat-icon-wrapper {
    background: rgba(78, 115, 223, 0.1);
    color: #4e73df;
  }
  .card-purple .stat-icon-wrapper {
    background: rgba(155, 81, 224, 0.1);
    color: #9b51e0;
  }
  .card-orange .stat-icon-wrapper {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
  }
  .card-green .stat-icon-wrapper {
    background: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
  }

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
    color: #4e73df;
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
    color: #4e73df;
  }

  .premium-tabs-nav .nav-link.active {
    background: #ffffff;
    color: #4e73df;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.08);
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
    background: rgba(78, 115, 223, 0.1);
    color: #4e73df;
    border: 1px solid rgba(78, 115, 223, 0.15);
  }

  .badge-type-renovation {
    background: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
    border: 1px solid rgba(28, 200, 138, 0.15);
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
    background: linear-gradient(90deg, #1cc88a, #138a5e);
  }

  .fill-incomplete {
    background: linear-gradient(90deg, #f39c12, #d35400);
  }

  .fill-none {
    background: linear-gradient(90deg, #e11d48, #be123c);
  }

  .compliance-percentage {
    font-size: 0.85rem;
    font-weight: 800;
    width: 48px;
    text-align: right;
  }

  .percentage-complete {
    color: #1cc88a;
  }

  .percentage-incomplete {
    color: #f39c12;
  }

  .percentage-none {
    color: #e11d48;
  }

  /* Action Buttons */
  .btn-premium-action {
    background: #ffffff;
    color: #4e73df;
    border: 1px solid rgba(78, 115, 223, 0.2);
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
    background: #4e73df;
    color: #ffffff;
    border-color: #4e73df;
    box-shadow: 0 4px 10px rgba(78, 115, 223, 0.15);
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
