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

    // 1. CHART BEBAN KERJA STAF (Bar Chart)
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
    const canvasBeban = document.getElementById('bebanKerjaChart');
    if (!canvasBeban) return;
    const ctxBeban = canvasBeban.getContext('2d');
    
    // Destroy previous Chart instance if it exists to prevent overlap
    if (window.bebanKerjaChartInstance) {
      window.bebanKerjaChartInstance.destroy();
    }
    
    window.bebanKerjaChartInstance = new Chart(ctxBeban, {
      type: 'radar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
          {
            label: 'Tugas Kerja Aktif',
            data: <?= json_encode($activeTasks) ?>,
            backgroundColor: 'rgba(239, 68, 68, 0.24)', // Premium translucent red
            borderColor: '#ef4444',
            borderWidth: 2,
            pointBackgroundColor: '#ef4444',
            pointBorderColor: '#fff',
            pointRadius: 4,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#ef4444',
            tension: 0 // Straight lines for precise data representation
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          r: {
            angleLines: {
              color: '#e2e8f0',
              lineWidth: 1
            },
            grid: {
              color: '#e2e8f0',
              circular: true // Clean modern circular rings instead of polygons
            },
            suggestedMin: 0,
            ticks: {
              stepSize: 1,
              backdropColor: 'transparent',
              color: '#94a3b8',
              font: { family: 'Inter, sans-serif', size: 9, weight: '500' }
            },
            pointLabels: {
              padding: 10, // Avoid label clipping, expand chart canvas
              font: { family: 'Inter, sans-serif', size: 11, weight: '700' },
              color: '#475569'
            }
          }
        },
        plugins: {
          legend: {
            display: false // Only one dataset, legend is redundant and hidden for maximum size
          },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

    // 2. CHART TOTAL PENGAJUAN PROYEK DESAIN (Bar Chart dengan Filter Dinamis)
    const rawSubmissionData = <?= json_encode($kadivStats['submission_trends'] ?? []) ?>;

    // Filter default: 3 Bulan Terakhir
    let defaultMonths = 3;
    let initialSlice = rawSubmissionData.slice(-defaultMonths);

    const canvasPengajuan = document.getElementById('pengajuanProyekChart');
    if (!canvasPengajuan) return;
    const ctxPengajuan = canvasPengajuan.getContext('2d');
    
    // Destroy previous Chart instance if it exists to prevent overlap
    if (window.pengajuanProyekChartInstance) {
      window.pengajuanProyekChartInstance.destroy();
    }
    
    window.pengajuanProyekChartInstance = new Chart(ctxPengajuan, {
      type: 'bar',
      data: {
        labels: initialSlice.map(item => item.label),
        datasets: [{
          label: 'Total Pengajuan Proyek Desain',
          data: initialSlice.map(item => item.count),
          backgroundColor: 'rgba(229, 57, 53, 0.85)', // Premium Primary Red
          borderColor: '#e53935',
          borderWidth: 1.5,
          borderRadius: 6,
          barPercentage: 0.35,
          categoryPercentage: 0.5
        }]
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
            display: false
          },
          tooltip: {
            padding: 12,
            cornerRadius: 8
          }
        }
      }
    });

    // Event Listener untuk Tombol Filter (3, 6, 12 Bulan)
    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('click', function () {
        // Ubah status kelas aktif
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Dapatkan jumlah bulan filter
        const months = parseInt(this.getAttribute('data-months'));
        const newSlice = rawSubmissionData.slice(-months);
        
        // Sesuaikan lebar bar secara dinamis agar visualnya seimbang
        let barPercentage = 0.35;
        if (months === 6) barPercentage = 0.45;
        if (months === 12) barPercentage = 0.55;
        
        // Update data chart
        if (window.pengajuanProyekChartInstance) {
          window.pengajuanProyekChartInstance.data.labels = newSlice.map(item => item.label);
          window.pengajuanProyekChartInstance.data.datasets[0].data = newSlice.map(item => item.count);
          window.pengajuanProyekChartInstance.data.datasets[0].barPercentage = barPercentage;
          window.pengajuanProyekChartInstance.update();
        }
      });
    });

  });
</script>
