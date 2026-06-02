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
