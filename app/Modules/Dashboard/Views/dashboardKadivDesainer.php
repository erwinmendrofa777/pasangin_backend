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
  .icon-active-proj {
    background: linear-gradient(135deg, #6777ef, #4e5fe0);
    box-shadow: 0 8px 20px rgba(103, 119, 239, 0.25);
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
    color: #0d6efd;
  }

  .task-card:hover .btn-minimal {
    background: #0d6efd;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25) !important;
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
    color: #6777ef;
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

  <!-- 3. DAFTAR TUGAS SAYA (Horizontal Scroll) -->
  <div class="card border-0 mb-4 shadow-sm" style="border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04);">
    <div class="card-header border-0 d-flex align-items-center p-3 p-md-4"
         style="background: #ffffff; border-radius: 20px 20px 0 0; border-bottom: 1px solid rgba(0,0,0,0.03) !important;">
      <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-2 me-3 d-flex align-items-center justify-content-center"
           style="width: 48px; height: 48px;">
        <i class="fas fa-clipboard-check fa-lg"></i>
      </div>
      <div>
        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px; font-size: 1.2rem;">Daftar Tugas Saya</h5>
        <p class="text-muted mb-0" style="font-size: 0.85rem; letter-spacing: 0.2px;">Target perancangan desain pribadi Anda yang sedang aktif</p>
      </div>
    </div>

    <div class="card-body p-3 p-md-4" style="background: #f8fbff; border-radius: 0 0 20px 20px;">
      <?php if (!empty($designerTasks)): ?>
        <div class="d-flex flex-nowrap gap-4 pb-3 pt-2 px-1 task-scroll-container"
             style="overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scroll-behavior: smooth;">
          <?php foreach ($designerTasks as $task): ?>
            <?php
            $tStatus = $task['status'];
            $tColor = 'secondary';

            if ($tStatus === 'PENDING') {
                $tStatus = 'BELUM DIKERJAKAN';
                $tColor = 'secondary';
            } elseif ($tStatus === 'ON PROGRESS') {
                $tStatus = 'SEDANG DIPROSES';
                $tColor = 'info';
            } elseif ($tStatus === 'DONE') {
                $tStatus = 'SELESAI (TANPA FILE)';
                $tColor = 'success';
            }

            if ($task['total_designs'] > 0) {
                if ($task['approved_designs'] > 0) {
                    $tStatus = 'DISETUJUI';
                    $tColor = 'success';
                } elseif ($task['pending_designs'] > 0) {
                    $tStatus = 'TINJAUAN';
                    $tColor = 'primary';
                } else {
                    $tStatus = 'PERLU REVISI';
                    $tColor = 'danger';
                }
            }
            ?>
            <div style="min-width: 300px; width: 300px; flex: 0 0 auto;">
              <div class="card h-100 shadow-sm task-card">
                <div class="card-body p-4 d-flex flex-column">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                      <?= date('d M', strtotime($task['created_at'])) ?>
                    </span>
                    <span class="badge text-white bg-<?= $tColor ?> rounded-pill px-2 py-1"
                          style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">
                      <?= esc($tStatus) ?>
                    </span>
                  </div>

                  <h5 class="fw-bold text-dark mb-4" style="font-size: 1.1rem; line-height: 1.4; white-space: normal;">
                    <?= esc($task['task_name']) ?>
                  </h5>

                  <div class="mb-4 flex-grow-1">
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-light">
                      <div class="text-muted" style="font-size: 0.8rem;">Konsep</div>
                      <div class="fw-semibold text-dark text-end" style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                        <?= esc($task['design_concept'] ?? 'Proyek Khusus') ?>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="text-muted" style="font-size: 0.8rem;">Klien</div>
                      <div class="fw-semibold text-dark text-end" style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                        <?= esc($task['client_name'] ?? 'Klien Internal') ?>
                      </div>
                    </div>
                  </div>

                  <div class="mt-auto pt-1 border-top border-light d-flex justify-content-between align-items-center">
                    <?php
                    $targetStartDateStr = '';
                    $targetEndDateStr = '';
                    if (!empty($task['request_start_date'])) {
                        $projStart = new DateTime($task['request_start_date']);
                        $tStart = clone $projStart;
                        if ($task['start_week'] > 1) {
                            $tStart->modify('+' . ($task['start_week'] - 1) . ' days');
                        }
                        $tEnd = clone $projStart;
                        if ($task['end_week'] > 1) {
                            $tEnd->modify('+' . ($task['end_week'] - 1) . ' days');
                        }
                        $targetStartDateStr = $tStart->format('d M');
                        $targetEndDateStr = $tEnd->format('d M Y');
                    }
                    ?>
                    <div>
                      <div class="text-dark fw-bold" style="font-size: 0.85rem;">
                        Hari <?= esc($task['start_week']) ?> &ndash; <?= esc($task['end_week']) ?>
                      </div>
                      <?php if ($targetStartDateStr): ?>
                        <div class="text-muted" style="font-size: 0.75rem; margin-top: 2px;">
                          <?= $targetStartDateStr ?> - <?= $targetEndDateStr ?>
                        </div>
                      <?php endif; ?>
                    </div>
                    <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>?target_id=<?= $task['id'] ?>&admin_id=<?= $task['user_admin_id'] ?>#design"
                       class="btn-minimal shadow-sm text-decoration-none stretched-link">
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <div class="text-muted mb-3" style="font-size: 3rem;">
            <i class="far fa-folder-open"></i>
          </div>
          <h6 class="fw-bold text-dark">Tidak Ada Tugas Desain Aktif</h6>
          <p class="text-muted" style="font-size: 0.85rem;">Anda sedang tidak memegang target pengerjaan desain saat ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 3b. GRAFIK ANALISIS (Chart.js) -->
  <div class="analysis-grid">
    <!-- Proporsi Kategori Proyek -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-chart-pie"></i> Proporsi Kategori Proyek Aktif</h4>
      </div>
      <div class="chart-container">
        <canvas id="proporsiChart"></canvas>
      </div>
    </div>

    <!-- Tren Kinerja Divisi -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-chart-line"></i> Tren Riwayat Kinerja Proyek</h4>
      </div>
      <div class="chart-container">
        <canvas id="trenKinerjaChart"></canvas>
      </div>
    </div>
  </div>

  <!-- 4. DISTRIBUSI BEBAN & PROYEK MENDESAK -->
  <div class="manage-grid">
    <!-- Beban Kerja Staf -->
    <div class="chart-card">
      <div class="chart-title-wrapper">
        <h4><i class="fas fa-chart-bar"></i> Distribusi Tugas Kerja Tim</h4>
      </div>
      <div class="chart-container" style="min-height: 300px;">
        <canvas id="bebanKerjaChart"></canvas>
      </div>
    </div>

    <!-- Proyek Mendesak -->
    <div class="premium-card">
      <div class="premium-card-title">
        <h4><i class="fas fa-business-time"></i> Proyek Mendesak (Deadline Terdekat)</h4>
      </div>
      <div>
        <?php if (!empty($kadivStats['critical_projects'])): ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($kadivStats['critical_projects'] as $p): ?>
              <?php
              $diff = (new DateTime($p['target_date']))->diff(new DateTime());
              $daysLeft = $diff->days;
              if ($diff->invert === 0 && $daysLeft > 0) {
                  $timeText = 'Terlambat ' . $daysLeft . ' hari';
                  $timeColor = 'danger';
              } else {
                  $timeText = $daysLeft . ' hari lagi';
                  $timeColor = ($daysLeft <= 3) ? 'danger' : (($daysLeft <= 7) ? 'warning' : 'info');
              }
              ?>
              <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: #fafafc; border-left: 4px solid var(--bs-<?= $timeColor ?>);">
                <div style="max-width: 70%;">
                  <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                    <?= esc($p['design_concept'] ?? 'Proyek Desain') ?>
                  </div>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    Klien: <?= esc($p['full_name'] ?? 'Internal') ?> | Target: <?= date('d M Y', strtotime($p['target_date'])) ?>
                  </div>
                </div>
                <div class="text-end">
                  <span class="badge bg-<?= $timeColor ?>-subtle text-<?= $timeColor ?> fw-bold px-2 py-1" style="font-size: 0.75rem;">
                    <?= $timeText ?>
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <div class="text-muted mb-2" style="font-size: 2.5rem;">
              <i class="far fa-calendar-check"></i>
            </div>
            <h6 class="fw-bold text-dark">Tidak Ada Tenggat Kritis</h6>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Semua proyek aman terkendali.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Script CDN Chart.js v4 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Shim for Chart.js v4 to prevent Stisla scripts.js from crashing
  if (window.Chart) {
    Chart.defaults.global = {
      tooltips: {}
    };
  }

  document.addEventListener("DOMContentLoaded", function () {
    // 1. CHART PROPORSI PROYEK (Doughnut Chart)
    const ctxProporsi = document.getElementById('proporsiChart').getContext('2d');
    new Chart(ctxProporsi, {
      type: 'doughnut',
      data: {
        labels: ['Desain', 'Konstruksi', 'Renovasi'],
        datasets: [{
          data: [
            <?= (int) ($kadivStats['overview']['active_projects_breakdown']['design'] ?? 0) ?>,
            <?= (int) ($kadivStats['overview']['active_projects_breakdown']['construction'] ?? 0) ?>,
            <?= (int) ($kadivStats['overview']['active_projects_breakdown']['renovation'] ?? 0) ?>
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

    // 2. CHART BEBAN KERJA STAF (Bar Chart)
    <?php
    $labels = [];
    $activeTasks = [];
    $completedDesigns = [];
    foreach ($kadivStats['team_workload'] as $row) {
        $labels[] = esc($row['full_name']);
        $activeTasks[] = (int) $row['active_tasks'];
        $completedDesigns[] = (int) $row['completed_designs'];
    }
    ?>
    const ctxBeban = document.getElementById('bebanKerjaChart').getContext('2d');
    new Chart(ctxBeban, {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
          {
            label: 'Tugas Aktif (Pending & On Progress)',
            data: <?= json_encode($activeTasks) ?>,
            backgroundColor: '#fc544b', // Coral Red (burden)
            borderColor: '#e0483f',
            borderWidth: 1,
            borderRadius: 8,
            barPercentage: 0.6,
            categoryPercentage: 0.6
          },
          {
            label: 'Desain Disetujui (Approved)',
            data: <?= json_encode($completedDesigns) ?>,
            backgroundColor: '#1cc88a', // Emerald Green (performance)
            borderColor: '#15a873',
            borderWidth: 1,
            borderRadius: 8,
            barPercentage: 0.6,
            categoryPercentage: 0.6
          }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { family: 'Inter, sans-serif', size: 11 }
            },
            grid: { color: '#f1f5f9' }
          },
          y: {
            ticks: {
              font: { family: 'Inter, sans-serif', size: 11, weight: '600' }
            },
            grid: { display: false }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              font: { family: 'Inter, sans-serif', size: 11, weight: '600' }
            }
          },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

    // 3. CHART TREN KINERJA (Line Chart)
    <?php
    $trendLabels = [];
    $activeProjectsData = [];
    $pendingRequestsData = [];
    $approvedDesignsData = [];

    foreach ($kadivStats['historical_trends'] as $t) {
        $trendLabels[] = $t['label'];
        $activeProjectsData[] = $t['active_projects'];
        $pendingRequestsData[] = $t['pending_requests'];
        $approvedDesignsData[] = $t['approved_designs'];
    }
    ?>
    const ctxTren = document.getElementById('trenKinerjaChart').getContext('2d');
    new Chart(ctxTren, {
      type: 'line',
      data: {
        labels: <?= json_encode($trendLabels) ?>,
        datasets: [
          {
            label: 'Total Proyek Aktif',
            data: <?= json_encode($activeProjectsData) ?>,
            borderColor: '#6777ef',
            backgroundColor: 'rgba(103, 119, 239, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3
          },
          {
            label: 'Antrean Desain Baru',
            data: <?= json_encode($pendingRequestsData) ?>,
            borderColor: '#fc544b',
            backgroundColor: 'rgba(252, 84, 75, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3
          },
          {
            label: 'Desain Selesai',
            data: <?= json_encode($approvedDesignsData) ?>,
            borderColor: '#1cc88a',
            backgroundColor: 'rgba(28, 200, 138, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              font: { family: 'Inter, sans-serif', size: 11 }
            },
            grid: { color: '#f1f5f9' }
          },
          x: {
            ticks: {
              font: { family: 'Inter, sans-serif', size: 11, weight: '600' }
            },
            grid: { display: false }
          }
        },
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 15,
              font: { family: 'Inter, sans-serif', size: 11, weight: '600' }
            }
          },
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
