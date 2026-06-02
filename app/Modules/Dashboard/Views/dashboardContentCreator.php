<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Content Creator
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== CONTENT CREATOR DASHBOARD PREMIUM STYLES ===== */
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
    border-left: 5px solid #36b9cc;
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
    background: linear-gradient(135deg, rgba(54, 185, 204, 0.06), rgba(54, 185, 204, 0.01));
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
    background: linear-gradient(135deg, #36b9cc, #258391);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: linear-gradient(135deg, rgba(54, 185, 204, 0.1), rgba(54, 185, 204, 0.05));
    border: 1px solid rgba(54, 185, 204, 0.15);
    color: #36b9cc;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  /* Metric Cards */
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
    box-shadow: 0 15px 35px rgba(54, 185, 204, 0.08);
    border-color: rgba(54, 185, 204, 0.2);
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
  .stat-card.card-green::after {
    background: linear-gradient(90deg, #1cc88a, #138a5e);
  }
  .stat-card.card-teal::after {
    background: linear-gradient(90deg, #36b9cc, #258391);
  }
  .stat-card.card-orange::after {
    background: linear-gradient(90deg, #f6c23e, #dda20a);
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
  .card-green .stat-icon-wrapper {
    background: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
  }
  .card-teal .stat-icon-wrapper {
    background: rgba(54, 185, 204, 0.1);
    color: #36b9cc;
  }
  .card-orange .stat-icon-wrapper {
    background: rgba(246, 194, 62, 0.1);
    color: #f6c23e;
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
    color: #36b9cc;
  }

  .premium-card .card-body {
    padding: 24px;
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

  .badge-app-client {
    background: rgba(78, 115, 223, 0.1);
    color: #4e73df;
    border: 1px solid rgba(78, 115, 223, 0.15);
  }

  .badge-app-tukang {
    background: rgba(28, 200, 138, 0.1);
    color: #1cc88a;
    border: 1px solid rgba(28, 200, 138, 0.15);
  }

  .badge-app-all {
    background: rgba(54, 185, 204, 0.1);
    color: #36b9cc;
    border: 1px solid rgba(54, 185, 204, 0.15);
  }

  .badge-status-active {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
  }

  .badge-status-draft {
    background: #fff8e6;
    color: #d97706;
    border: 1px solid #ffeeba;
  }

  /* Action Buttons */
  .btn-premium-action {
    background: #ffffff;
    color: #36b9cc;
    border: 1px solid rgba(54, 185, 204, 0.2);
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
    background: #36b9cc;
    color: #ffffff;
    border-color: #36b9cc;
    box-shadow: 0 4px 10px rgba(54, 185, 204, 0.15);
    text-decoration: none;
  }

  .btn-premium-action-sm {
    padding: 4px 8px;
    font-size: 0.7rem;
    border-radius: 6px;
  }

  /* Quick Action Buttons (Gradient Style) */
  .quick-action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 12px;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 12px;
  }

  .quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    color: #ffffff;
    text-decoration: none;
  }

  .quick-action-card:last-child {
    margin-bottom: 0;
  }

  .action-blue {
    background: linear-gradient(135deg, #4e73df, #224abe);
  }
  .action-green {
    background: linear-gradient(135deg, #1cc88a, #138a5e);
  }
  .action-teal {
    background: linear-gradient(135deg, #36b9cc, #258391);
  }

  .quick-action-icon {
    font-size: 1.5rem;
    opacity: 0.9;
  }

  .quick-action-info {
    display: flex;
    flex-direction: column;
  }

  .quick-action-title {
    font-weight: 800;
    font-size: 0.95rem;
  }

  .quick-action-desc {
    font-size: 0.75rem;
    opacity: 0.8;
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
  }

  @media (max-width: 575.98px) {
    .stat-card {
      padding: 16px;
    }
    .stat-icon-wrapper {
      width: 46px;
      height: 46px;
      font-size: 1.2rem;
    }
    .stat-value {
      font-size: 1.4rem;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. WELCOME HEADER -->
  <div class="welcome-header">
    <div class="header-left">
      <h1>Selamat Datang Kembali, <span><?= esc(session()->get('full_name')) ?></span>!</h1>
      <p>Berikut ringkasan performa konten aplikasi, statistik banner, tips terbaru, dan aktivitas notifikasi hari ini.</p>
    </div>
    <div class="header-right">
      <span class="role-badge">
        <i class="fas fa-photo-video"></i> Content Creator
      </span>
    </div>
  </div>

  <?= $this->include('App\Modules\Dashboard\Views\components\_cc_kpi_cards') ?>

  <!-- 3. ROW 1: CHARTS -->
  <div class="row g-4">
    <!-- Left Column: Tren Konten (8 Col) -->
    <div class="col-12 col-lg-8">
      <?= $this->include('App\Modules\Dashboard\Views\components\_cc_trend_chart') ?>
    </div>

    <!-- Right Column: Distribusi (4 Col) -->
    <div class="col-12 col-lg-4">
      <?= $this->include('App\Modules\Dashboard\Views\components\_cc_distribution_chart') ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_cc_scripts') ?>
<?= $this->endSection() ?>
