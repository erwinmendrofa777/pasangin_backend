<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Kepala Divisi Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== KADIV DESIGNER DASHBOARD PREMIUM STYLES ===== */
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
    border-left: 5px solid var(--palette-primary);
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
    background: linear-gradient(135deg, rgba(255, 92, 92, 0.06), rgba(255, 92, 92, 0.01));
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
    background: linear-gradient(135deg, var(--palette-primary), #4e5fe0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: linear-gradient(135deg, rgba(255, 92, 92, 0.1), rgba(255, 92, 92, 0.05));
    border: 1px solid rgba(255, 92, 92, 0.15);
    color: var(--palette-primary);
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
    box-shadow: 0 12px 30px rgba(255, 92, 92, 0.08);
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
    background: linear-gradient(135deg, var(--palette-primary), #4e5fe0);
    box-shadow: 0 8px 20px rgba(255, 92, 92, 0.25);
  }

  .icon-pending {
    background: linear-gradient(135deg, #ffa426, #f88f01);
    box-shadow: 0 8px 20px rgba(255, 164, 38, 0.25);
  }

  .icon-approved {
    background: linear-gradient(135deg, #1cc88a, #13855c);
    box-shadow: 0 8px 20px rgba(28, 200, 138, 0.25);
  }

  /* Task Cards Styles */
  .task-scroll-container::-webkit-scrollbar {
    height: 8px;
  }
  .task-scroll-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 10px;
  }
  .task-scroll-container::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
  }
  .task-scroll-container::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.25);
  }

  .task-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #edf2f7;
    background: #ffffff;
    position: relative;
  }

  .task-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    border-color: #e2e8f0;
  }

  .btn-minimal {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
    transition: all 0.2s;
    color: var(--palette-primary);
  }

  .task-card:hover .btn-minimal {
    background: var(--palette-primary);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25) !important;
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    margin-bottom: 20px;
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
    .manage-grid, .analysis-grid {
      gap: 16px;
      margin-bottom: 20px;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. Header Sambutan -->
  <div class="welcome-header">
    <div class="header-left">
      <h1>Selamat Datang, <span><?= esc(session()->get('full_name') ?? 'Kadiv Desainer') ?>!</span></h1>
      <p>Ruang kerja pengawasan tim desainer, monitoring beban kerja studio, dan pelacakan tugas pribadi Anda.</p>
    </div>
    <div>
      <div class="role-badge">
        <i class="fas fa-crown"></i>
        Kepala Divisi Desain
      </div>
    </div>
  </div>

  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_my_tasks') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_analysis_charts') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_workload_critical') ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_kd_scripts') ?>
<?= $this->endSection() ?>
