<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Kepala Divisi Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== KADIV DESIGNER DASHBOARD PREMIUM STYLES ===== */
  .dashboard-container {
    padding: 0px 0;
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
    border-left: 5px solid var(--palette-primary);
    position: relative;
    overflow: hidden;
  }

  .welcome-header::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 160px;
    height: 160px;
    background: radial-gradient(circle, rgba(244, 67, 54, 0.07), transparent 70%);
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
    background: linear-gradient(135deg, #e53935, #ff7043);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: linear-gradient(135deg, #e53935, #ff7043);
    color: #ffffff;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(229, 57, 53, 0.3);
  }

  /* Stats Grid System */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 1px solid #f0f2f8;
    position: relative;
    overflow: hidden;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(229, 57, 53, 0.10);
  }

  .stat-card::after {
    content: '';
    position: absolute;
    bottom: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    background: rgba(0, 0, 0, 0.015);
    border-radius: 50%;
  }

  .stat-info h3 {
    font-size: 0.8rem;
    font-weight: 700;
    color: #a0aec0;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0 0 6px;
  }

  .stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #2d3748;
    line-height: 1;
  }

  .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  }

  /* Card Gradients */
  .icon-active-proj {
    background: linear-gradient(135deg, #e53935, #ff7043);
    box-shadow: 0 8px 20px rgba(229, 57, 53, 0.28);
  }

  .icon-pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.28);
  }

  .icon-approved {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.28);
  }

  /* Management Cards Styles */
  .manage-grid {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 24px;
    margin-bottom: 28px;
  }

  .analysis-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 24px;
    margin-bottom: 28px;
  }

  .premium-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
    border: 1px solid #eef0fb;
    padding: 24px;
    display: flex;
    flex-direction: column;
  }

  .premium-card-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f6f7fb;
  }

  .premium-card-title h4 {
    font-size: 1.1rem;
    font-weight: 750;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .premium-card-title h4 i {
    color: var(--palette-primary);
  }

  .designer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  /* Charts Grid System */
  .charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
  }

  .chart-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
    border: 1px solid #eef0fb;
    display: flex;
    flex-direction: column;
  }

  .chart-title-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px solid #f6f7fb;
  }

  .chart-card h4 {
    font-size: 1rem;
    font-weight: 750;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .chart-card h4 i {
    color: var(--palette-primary);
  }

  .chart-container {
    position: relative;
    width: 100%;
    min-height: 280px;
    max-height: 320px;
  }

  /* Workload Radar Chart specific container */
  .workload-chart-container {
    position: relative;
    width: 100%;
    flex: 1;
    min-height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Workload Details Container */
  .workload-details-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
  }

  @media (min-width: 768px) {
    .workload-details-container {
      border-left: 1px dashed #e2e8f0;
      padding-left: 24px;
    }
  }

  /* Premium scrollbar for review queue container */
  .review-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f8fafc;
  }

  .review-scroll-container::-webkit-scrollbar {
    width: 6px;
  }

  .review-scroll-container::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 10px;
  }

  .review-scroll-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
    border: 1px solid #f8fafc;
  }

  .review-scroll-container::-webkit-scrollbar-thumb:hover {
    background: var(--palette-primary);
  }

  /* Premium Card items for Awaiting Reviews */
  .review-queue-card {
    background: #ffffff;
    border: 1px solid #eef0fb;
    border-left: 4px solid #f59e0b;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
  }

  .review-queue-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.08);
    border-color: rgba(245, 158, 11, 0.25);
  }

  .btn-review-outline {
    border-color: #cbd5e1;
    color: #475569;
    background-color: #ffffff;
    transition: all 0.25s ease;
  }

  .btn-review-outline:hover {
    background-color: #f8fafc;
    color: var(--palette-primary);
    border-color: var(--palette-primary);
  }

  .btn-review-primary {
    background: linear-gradient(135deg, #e53935, #ff7043);
    border: none;
    box-shadow: 0 4px 10px rgba(229, 57, 53, 0.15);
    transition: all 0.25s ease;
    color: #ffffff;
  }

  .btn-review-primary:hover {
    background: linear-gradient(135deg, #d32f2f, #f4511e);
    box-shadow: 0 6px 14px rgba(229, 57, 53, 0.25);
    transform: translateY(-1px);
    color: #ffffff;
  }

  /* Compact Circular Preview Button */
  .btn-preview-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background-color: #f8fafc;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    font-size: 0.78rem;
    border: 1px solid #e2e8f0;
    text-decoration: none !important;
  }

  .btn-preview-circle:hover {
    background-color: #e2e8f0;
    color: var(--palette-primary);
    transform: scale(1.08);
  }

  /* Responsive styling */
  @media (max-width: 992px) {

    .manage-grid,
    .analysis-grid,
    .charts-row {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 768px) {
    .welcome-header {
      flex-direction: column;
      align-items: flex-start;
      padding: 20px;
      gap: 12px;
    }

    .welcome-header .role-badge {
      margin-top: 4px;
    }

    .header-left h1 {
      font-size: 1.5rem;
    }

    .header-left p {
      font-size: 0.85rem;
    }
  }

  @media (max-width: 576px) {
    .dashboard-container {
      padding: 12px 0;
    }

    .welcome-header {
      padding: 16px;
      border-left-width: 4px;
    }

    .header-left h1 {
      font-size: 1.3rem;
    }

    .chart-card,
    .premium-card {
      padding: 16px;
    }

    .chart-title-wrapper,
    .premium-card-title {
      margin-bottom: 16px;
      padding-bottom: 10px;
    }

    .chart-card h4,
    .premium-card-title h4 {
      font-size: 0.9rem;
    }

    .stat-card {
      padding: 16px;
    }

    .stat-value {
      font-size: 1.5rem;
    }

    .stat-icon {
      width: 44px;
      height: 44px;
      font-size: 1.1rem;
    }

    .manage-grid,
    .analysis-grid {
      gap: 16px;
      margin-bottom: 20px;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. Ringkasan Statistik KPI -->
  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_stats_cards') ?>

  <!-- 2. Grafik Analisis Proyek -->
  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_analysis_charts') ?>

  <!-- 3. Distribusi Beban Kerja (col-8) & Antrean Tinjauan Desain (col-4) -->
  <div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
      <?= $this->include('App\Modules\Dashboard\Views\components\_kd_workload') ?>
    </div>
    <div class="col-12 col-lg-4">
      <?= $this->include('App\Modules\Dashboard\Views\components\_kd_review_queue') ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Dashboard\Views\components\_kd_scripts') ?>
<?= $this->endSection() ?>