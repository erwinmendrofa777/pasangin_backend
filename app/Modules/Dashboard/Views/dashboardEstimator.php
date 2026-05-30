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

  <!-- 2. KPI METRIC GRID -->
  <div class="stats-grid">
    <!-- Antrean RAB Utama -->
    <div class="stat-card card-blue">
      <div class="stat-info">
        <div class="stat-label">Antrean RAB Utama</div>
        <div class="stat-value"><?= (int)$estimatorStats['kpis']['queue_rab_count'] ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-file-invoice-dollar"></i>
      </div>
    </div>

    <!-- Proyek Pra-RAB (Upcoming) -->
    <div class="stat-card card-purple">
      <div class="stat-info">
        <div class="stat-label">Pra-RAB (Upcoming)</div>
        <div class="stat-value"><?= (int)$estimatorStats['kpis']['upcoming_rab_count'] ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-clock"></i>
      </div>
    </div>

    <!-- Proyek Pengerjaan Aktif -->
    <div class="stat-card card-orange">
      <div class="stat-info">
        <div class="stat-label">Proyek Aktif (Konstruksi/Renovasi)</div>
        <div class="stat-value"><?= (int)$estimatorStats['kpis']['total_active_project'] ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-hard-hat"></i>
      </div>
    </div>

    <!-- Total Anggaran Terkelola -->
    <div class="stat-card card-green">
      <div class="stat-info">
        <div class="stat-label">Anggaran Terkelola</div>
        <div class="stat-value" style="font-size: 1.3rem;">Rp <?= number_format($estimatorStats['kpis']['total_estimated_budget'], 0, ',', '.') ?></div>
      </div>
      <div class="stat-icon-wrapper">
        <i class="fas fa-coins"></i>
      </div>
    </div>
  </div>

  <!-- 3. ROW 1: TASK QUEUE -->
  <div class="row">
    <!-- Antrean Tugas (Full Width) -->
    <div class="col-12">
      <div class="card premium-card">
        <div class="card-header">
          <h4><i class="fas fa-tasks"></i> Antrean Tugas Estimasi & RAB</h4>
        </div>
        <div class="card-body">
          <!-- Nav Tabs -->
          <ul class="nav premium-tabs-nav" id="rabTaskTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="main-queue-tab" data-toggle="tab" href="#main-queue" role="tab" aria-controls="main-queue" aria-selected="true">
                Antrean Utama (RAB) <span class="badge badge-primary ml-1" style="font-size: 0.7rem;"><?= count($estimatorStats['queues']['main']) ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="upcoming-queue-tab" data-toggle="tab" href="#upcoming-queue" role="tab" aria-controls="upcoming-queue" aria-selected="false">
                Pra-RAB / Upcoming <span class="badge badge-secondary ml-1" style="font-size: 0.7rem;"><?= count($estimatorStats['queues']['upcoming']) ?></span>
              </a>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="rabTaskTabsContent">
            <!-- Tab 1: Antrean Utama -->
            <div class="tab-pane fade show active" id="main-queue" role="tabpanel" aria-labelledby="main-queue-tab">
              <?php if (empty($estimatorStats['queues']['main'])): ?>
                <div class="empty-state">
                  <i class="far fa-check-circle"></i>
                  <p>Tidak ada antrean pembuatan RAB aktif saat ini.</p>
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table premium-table">
                    <thead>
                      <tr>
                        <th>Proyek / Klien</th>
                        <th>Tipe</th>
                        <th>Status Proyek</th>
                        <th>Nilai Biaya</th>
                        <th>Target Progres</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($estimatorStats['queues']['main'] as $p): ?>
                        <tr>
                          <td>
                            <strong><?= esc($p['full_name'] ?: 'Klien Umum') ?></strong>
                            <div class="text-muted" style="font-size: 0.75rem;">ID: #<?= $p['id'] ?> | <?= date('d M Y', strtotime($p['created_at'])) ?></div>
                          </td>
                          <td>
                            <span class="status-badge badge-type-<?= $p['type'] ?>">
                              <i class="fas <?= $p['type'] === 'construction' ? 'fa-building' : 'fa-home' ?>"></i> <?= esc($p['type']) ?>
                            </span>
                          </td>
                          <td>
                            <span class="status-badge badge-status-rab">RAB</span>
                          </td>
                          <td>
                            <strong>Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                              <?= $p['is_rab_locked'] === '1' ? '🔒 Terkunci' : '📝 Draf (' . $p['rab_count'] . ' item)' ?>
                            </div>
                          </td>
                          <td>
                            <div style="font-size: 0.8rem; font-weight: 700;">
                              <?= $p['target_count'] > 0 ? $p['total_bobot_target'] . '%' : 'Belum Dibuat' ?>
                            </div>
                          </td>
                          <td>
                            <a href="<?= base_url('admin/' . $p['type'] . '/detail/' . $p['id']) ?>" class="btn-premium-action btn-premium-action-sm">
                              <i class="fas fa-edit"></i> Detail & RAB
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>

            <!-- Tab 2: Upcoming / Pra-RAB -->
            <div class="tab-pane fade" id="upcoming-queue" role="tabpanel" aria-labelledby="upcoming-queue-tab">
              <?php if (empty($estimatorStats['queues']['upcoming'])): ?>
                <div class="empty-state">
                  <i class="fas fa-calendar-alt"></i>
                  <p>Tidak ada proyek dalam tahap Survei atau Desain.</p>
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table premium-table">
                    <thead>
                      <tr>
                        <th>Proyek / Klien</th>
                        <th>Tipe</th>
                        <th>Status Proyek</th>
                        <th>Anggaran Awal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($estimatorStats['queues']['upcoming'] as $p): ?>
                        <tr>
                          <td>
                            <strong><?= esc($p['full_name'] ?: 'Klien Umum') ?></strong>
                            <div class="text-muted" style="font-size: 0.75rem;">ID: #<?= $p['id'] ?> | <?= date('d M Y', strtotime($p['created_at'])) ?></div>
                          </td>
                          <td>
                            <span class="status-badge badge-type-<?= $p['type'] ?>">
                              <i class="fas <?= $p['type'] === 'construction' ? 'fa-building' : 'fa-home' ?>"></i> <?= esc($p['type']) ?>
                            </span>
                          </td>
                          <td>
                            <span class="status-badge badge-status-<?= strtolower($p['status']) ?>">
                              <?= esc($p['status']) ?>
                            </span>
                          </td>
                          <td>
                            Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?>
                          </td>
                          <td>
                            <a href="<?= base_url('admin/' . $p['type'] . '/detail/' . $p['id']) ?>" class="btn-premium-action btn-premium-action-sm">
                              <i class="fas fa-eye"></i> Detail
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. ROW 2: GRAPH VISUALIZATION (CHARTS) -->
  <div class="row">
    <!-- Left Column: Tren Riwayat Nilai Proyek (8 Col) -->
    <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
      <div class="card premium-card">
        <div class="card-header">
          <h4><i class="fas fa-chart-line"></i> Tren Riwayat Anggaran RAB & Addendum</h4>
        </div>
        <div class="card-body">
          <div class="chart-container-premium">
            <canvas id="trenNilaiChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Proporsi Kategori Pekerjaan RAB (4 Col) -->
    <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-12">
      <div class="card premium-card">
        <div class="card-header">
          <h4><i class="fas fa-chart-pie"></i> Proporsi Kategori Pekerjaan RAB</h4>
        </div>
        <div class="card-body">
          <div class="chart-container-premium d-flex flex-column align-items-center justify-content-center">
            <div style="position: relative; height: 230px; width: 100%;">
              <canvas id="kategoriRabChart"></canvas>
            </div>
            <!-- Custom Legend -->
            <ul class="legend-list" id="kategoriLegend"></ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Script CDN Chart.js v4 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Shim untuk Chart.js v4 agar Stisla JS bawaan template tidak crash
  if (window.Chart) {
    Chart.defaults.global = {
      tooltips: {}
    };
  }

  document.addEventListener("DOMContentLoaded", function () {
    // Explicitly handle tab switching via jQuery to prevent Bootstrap 4 conflicts in the theme template
    $('#rabTaskTabs a').on('click', function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // -----------------------------------------------------------------
    // 1. TREN RIWAYAT ANGGARAN RAB & ADDENDUM (Line Chart)
    // -----------------------------------------------------------------
    const ctxTren = document.getElementById('trenNilaiChart').getContext('2d');
    
    const monthlyLabels = <?= json_encode($estimatorStats['charts']['monthly']['labels']) ?>;
    const monthlyRab = <?= json_encode($estimatorStats['charts']['monthly']['rab']) ?>;
    const monthlyAddendum = <?= json_encode($estimatorStats['charts']['monthly']['addendum']) ?>;

    new Chart(ctxTren, {
      type: 'line',
      data: {
        labels: monthlyLabels,
        datasets: [
          {
            label: 'Total Anggaran RAB Utama',
            data: monthlyRab,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#4e73df',
            pointHoverRadius: 7
          },
          {
            label: 'Total Anggaran Addendum',
            data: monthlyAddendum,
            borderColor: '#f39c12',
            backgroundColor: 'rgba(243, 156, 18, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#f39c12',
            pointHoverRadius: 7
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#f1f5f9'
            },
            ticks: {
              font: {
                family: 'Inter, sans-serif',
                size: 11
              },
              callback: function(value, index, values) {
                if (value >= 1e6) {
                  return 'Rp ' + (value / 1e6).toFixed(1) + ' Jt';
                }
                return 'Rp ' + value;
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: 'Inter, sans-serif',
                size: 11,
                weight: '600'
              }
            }
          }
        },
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 15,
              font: {
                family: 'Inter, sans-serif',
                size: 11,
                weight: '600'
              }
            }
          },
          tooltip: {
            padding: 12,
            backgroundColor: 'rgba(15, 23, 42, 0.9)',
            titleFont: { family: 'Inter, sans-serif', size: 12, weight: '700' },
            bodyFont: { family: 'Inter, sans-serif', size: 12 },
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                }
                return label;
              }
            }
          }
        }
      }
    });

    // -----------------------------------------------------------------
    // 2. PROPORSI KATEGORI PEKERJAAN RAB (Doughnut Chart)
    // -----------------------------------------------------------------
    const ctxKategori = document.getElementById('kategoriRabChart').getContext('2d');
    
    const catLabels = <?= json_encode($estimatorStats['charts']['categories']['labels']) ?>;
    const catValues = <?= json_encode($estimatorStats['charts']['categories']['values']) ?>;
    
    const colorPalette = [
      '#4e73df', // Blue
      '#1cc88a', // Emerald Green
      '#f39c12', // Orange
      '#9b51e0', // Purple
      '#36b9cc', // Teal
      '#858796'  // Slate Grey (for 'Lainnya')
    ];

    if (catValues.length === 0) {
      // Jika tidak ada data sama sekali, sembunyikan donut chart dan tampilkan pesan kosong
      document.getElementById('kategoriRabChart').style.display = 'none';
      document.getElementById('kategoriLegend').innerHTML = '<li class="legend-item"><i class="fas fa-info-circle text-muted"></i> Data pekerjaan RAB belum tersedia.</li>';
    } else {
      const myDoughnut = new Chart(ctxKategori, {
        type: 'doughnut',
        data: {
          labels: catLabels,
          datasets: [{
            data: catValues,
            backgroundColor: colorPalette.slice(0, catValues.length),
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '65%',
          plugins: {
            legend: {
              display: false // Kita bikin legend custom di bawah
            },
            tooltip: {
              padding: 12,
              backgroundColor: 'rgba(15, 23, 42, 0.9)',
              bodyFont: { family: 'Inter, sans-serif', size: 12 },
              cornerRadius: 8,
              callbacks: {
                label: function(context) {
                  let label = context.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed !== null) {
                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                  }
                  return label;
                }
              }
            }
          }
        }
      });

      // Generate Custom Legend
      const legendList = document.getElementById('kategoriLegend');
      legendList.innerHTML = '';
      
      const totalSum = catValues.reduce((a, b) => a + b, 0);

      catLabels.forEach((label, i) => {
        const value = catValues[i];
        const percent = ((value / totalSum) * 100).toFixed(1);
        const color = colorPalette[i] || '#858796';

        const li = document.createElement('li');
        li.className = 'legend-item';
        li.innerHTML = `
          <span class="legend-dot" style="background-color: ${color}"></span>
          <span class="legend-text" title="${label}">${label.substring(0, 15)}${label.length > 15 ? '...' : ''} (${percent}%)</span>
        `;
        legendList.appendChild(li);
      });
    }

  });
</script>
<?= $this->endSection() ?>
