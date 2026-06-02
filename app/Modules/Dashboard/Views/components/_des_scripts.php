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
