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
    border-left: 5px solid #6777ef;
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
    background: linear-gradient(135deg, rgba(103, 119, 239, 0.06), rgba(103, 119, 239, 0.01));
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
    background: linear-gradient(135deg, #6777ef, #4e5fe0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .header-left p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .role-badge {
    background: linear-gradient(135deg, rgba(103, 119, 239, 0.1), rgba(103, 119, 239, 0.05));
    border: 1px solid rgba(103, 119, 239, 0.15);
    color: #6777ef;
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
    box-shadow: 0 12px 30px rgba(103, 119, 239, 0.08);
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
    background: linear-gradient(135deg, #6777ef, #4e5fe0);
    box-shadow: 0 8px 20px rgba(103, 119, 239, 0.25);
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
    color: #6777ef;
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

  <!-- 2. Stat Cards (Total Proyek) -->
  <div class="stats-grid">
    <!-- Card Desain -->
    <div class="stat-card">
      <div class="stat-info">
        <h3>Proyek Desain</h3>
        <div class="stat-value"><?= number_format($desainerStats['totals']['design']) ?></div>
      </div>
      <div class="stat-icon icon-design">
        <i class="fas fa-pencil-ruler"></i>
      </div>
    </div>

    <!-- Card Konstruksi -->
    <div class="stat-card">
      <div class="stat-info">
        <h3>Proyek Konstruksi</h3>
        <div class="stat-value"><?= number_format($desainerStats['totals']['construction']) ?></div>
      </div>
      <div class="stat-icon icon-construction">
        <i class="fas fa-building"></i>
      </div>
    </div>

    <!-- Card Renovasi -->
    <div class="stat-card">
      <div class="stat-info">
        <h3>Proyek Renovasi</h3>
        <div class="stat-value"><?= number_format($desainerStats['totals']['renovation']) ?></div>
      </div>
      <div class="stat-icon icon-renovation">
        <i class="fas fa-tools"></i>
      </div>
    </div>

    <!-- Card Grand Total -->
    <div class="stat-card" style="background: #fafbff; border-color: rgba(103, 119, 239, 0.2);">
      <div class="stat-info">
        <h3>Akumulasi Proyek</h3>
        <div class="stat-value" style="color: #6777ef;"><?= number_format($desainerStats['totals']['grand_total']) ?>
        </div>
      </div>
      <div class="stat-icon icon-grand">
        <i class="fas fa-folder-open"></i>
      </div>
    </div>
  </div>

  <!-- 3. Grafik Baris 1: Proporsi Beban & Status Desain -->
  <div class="charts-row">
    <!-- Chart Proporsi Beban Kerja -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-chart-pie"></i> Proporsi Kategori Proyek</h4>
      </div>
      <div class="chart-container">
        <canvas id="proporsiChart"></canvas>
      </div>
    </div>

    <!-- Chart Detail Status Desain -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-tasks"></i> Status Proyek Desain</h4>
      </div>
      <div class="chart-container">
        <canvas id="designStatusChart"></canvas>
      </div>
    </div>
  </div>

  <!-- 4. Grafik Baris 2: Status Konstruksi & Renovasi -->
  <div class="charts-row">
    <!-- Chart Detail Status Konstruksi -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-hard-hat"></i> Status Proyek Konstruksi</h4>
      </div>
      <div class="chart-container">
        <canvas id="constructionStatusChart"></canvas>
      </div>
    </div>

    <!-- Chart Detail Status Renovasi -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-home"></i> Status Proyek Renovasi</h4>
      </div>
      <div class="chart-container">
        <canvas id="renovationStatusChart"></canvas>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Script CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Parsing data dari PHP ke JavaScript
    const statsData = <?= json_encode($desainerStats) ?>;

    // ==========================================
    // 1. CHART PROPORSI PROYEK (Doughnut Chart)
    // ==========================================
    const ctxProporsi = document.getElementById('proporsiChart').getContext('2d');
    new Chart(ctxProporsi, {
      type: 'doughnut',
      data: {
        labels: ['Desain', 'Konstruksi', 'Renovasi'],
        datasets: [{
          data: [
            statsData.totals.design,
            statsData.totals.construction,
            statsData.totals.renovation
          ],
          backgroundColor: [
            '#6777ef', // Violet
            '#1cc88a', // Emerald Green
            '#fc544b'  // Coral Red
          ],
          borderWidth: 4,
          borderColor: '#ffffff',
          hoverOffset: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 20,
              font: { family: 'Inter, sans-serif', size: 12, weight: '600' }
            }
          },
          tooltip: {
            padding: 12,
            cornerRadius: 8,
            titleFont: { family: 'Inter', size: 13, weight: 'bold' },
            bodyFont: { family: 'Inter', size: 12 }
          }
        }
      }
    });

    // Helper untuk membuat gradien bar chart horizontal
    function createHorizontalGradient(ctx, colorStart, colorEnd) {
      const gradient = ctx.createLinearGradient(0, 0, 400, 0);
      gradient.addColorStop(0, colorStart);
      gradient.addColorStop(1, colorEnd);
      return gradient;
    }

    // ==========================================
    // 2. CHART STATUS DESAIN (Horizontal Bar Chart)
    // ==========================================
    const ctxDesign = document.getElementById('designStatusChart').getContext('2d');
    const designLabels = Object.keys(statsData.by_status.design);
    const designValues = Object.values(statsData.by_status.design);

    new Chart(ctxDesign, {
      type: 'bar',
      data: {
        labels: designLabels,
        datasets: [{
          label: 'Jumlah Proyek',
          data: designValues,
          backgroundColor: createHorizontalGradient(ctxDesign, 'rgba(103, 119, 239, 1)', 'rgba(103, 119, 239, 0.25)'),
          borderColor: '#6777ef',
          borderWidth: 1.5,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
          x: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#4a5568', font: { family: 'Inter, sans-serif', size: 11, weight: '500' } },
            grid: { color: '#f1f5f9' }
          },
          y: {
            ticks: { color: '#2d3748', font: { family: 'Inter, sans-serif', size: 11, weight: '600' } },
            grid: { display: false }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

    // ==========================================
    // 3. CHART STATUS KONSTRUKSI (Horizontal Bar Chart)
    // ==========================================
    const ctxConstruction = document.getElementById('constructionStatusChart').getContext('2d');
    const constructionLabels = Object.keys(statsData.by_status.construction);
    const constructionValues = Object.values(statsData.by_status.construction);

    new Chart(ctxConstruction, {
      type: 'bar',
      data: {
        labels: constructionLabels,
        datasets: [{
          label: 'Jumlah Proyek',
          data: constructionValues,
          backgroundColor: createHorizontalGradient(ctxConstruction, 'rgba(28, 200, 138, 1)', 'rgba(28, 200, 138, 0.25)'),
          borderColor: '#1cc88a',
          borderWidth: 1.5,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
          x: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#4a5568', font: { family: 'Inter, sans-serif', size: 11, weight: '500' } },
            grid: { color: '#f1f5f9' }
          },
          y: {
            ticks: { color: '#2d3748', font: { family: 'Inter, sans-serif', size: 11, weight: '600' } },
            grid: { display: false }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

    // ==========================================
    // 4. CHART STATUS RENOVASI (Horizontal Bar Chart)
    // ==========================================
    const ctxRenovation = document.getElementById('renovationStatusChart').getContext('2d');
    const renovationLabels = Object.keys(statsData.by_status.renovation);
    const renovationValues = Object.values(statsData.by_status.renovation);

    new Chart(ctxRenovation, {
      type: 'bar',
      data: {
        labels: renovationLabels,
        datasets: [{
          label: 'Jumlah Proyek',
          data: renovationValues,
          backgroundColor: createHorizontalGradient(ctxRenovation, 'rgba(252, 84, 75, 1)', 'rgba(252, 84, 75, 0.25)'),
          borderColor: '#fc544b',
          borderWidth: 1.5,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
          x: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#4a5568', font: { family: 'Inter, sans-serif', size: 11, weight: '500' } },
            grid: { color: '#f1f5f9' }
          },
          y: {
            ticks: { color: '#2d3748', font: { family: 'Inter, sans-serif', size: 11, weight: '600' } },
            grid: { display: false }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

  });
</script>
<?= $this->endSection() ?>