<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard Desainer
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== DESIGNER DASHBOARD PREMIUM STYLES ===== */
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
  .icon-design {
    background: linear-gradient(135deg, var(--palette-primary), #4e5fe0);
    box-shadow: 0 8px 20px rgba(255, 92, 92, 0.25);
  }

  .icon-construction {
    background: linear-gradient(135deg, #1cc88a, #13855c);
    box-shadow: 0 8px 20px rgba(28, 200, 138, 0.25);
  }

  .icon-renovation {
    background: linear-gradient(135deg, #fc544b, #f73c3b);
    box-shadow: 0 8px 20px rgba(252, 84, 75, 0.25);
  }

  .icon-grand {
    background: linear-gradient(135deg, #ffa426, #f88f01);
    box-shadow: 0 8px 20px rgba(255, 164, 38, 0.25);
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
    .charts-row {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 576px) {
    .welcome-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
      padding: 20px;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

  <!-- 1. Header Sambutan -->
  <div class="welcome-header">
    <div class="header-left">
      <h1>Selamat Datang, <span><?= esc(session()->get('full_name') ?? 'Desainer') ?>!</span></h1>
      <p>Berikut adalah ikhtisar analitis seluruh proyek aktif dan pipeline studio desain Anda.</p>
    </div>
    <div>
      <div class="role-badge">
        <i class="fas fa-magic"></i>
        <?= esc(ucwords(str_replace('_', ' ', session()->get('role') ?? 'Desainer'))) ?>
      </div>
    </div>
  </div>

  <?= $this->include('App\Modules\Dashboard\Views\components\_des_kpi_cards') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_des_charts_row1') ?>

  <?= $this->include('App\Modules\Dashboard\Views\components\_des_charts_row2') ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_des_scripts') ?>
<?= $this->endSection() ?>