<!-- Script CDN Chart.js v4 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Shim untuk Chart.js v4 agar Stisla JS bawaan template tidak crash
  if (window.Chart) {
    Chart.defaults.global = {
      tooltips: {}
    };
  }

  document.addEventListener("DOMContentLoaded", function() {
    const chartData = <?= json_encode($creatorStats['charts']) ?>;

    // -----------------------------------------------------------------
    // 1. DISTRIBUSI TARGET APP (Doughnut Chart)
    // -----------------------------------------------------------------
    const ctxApp = document.getElementById('targetAppChart').getContext('2d');
    const catLabels = chartData.target_app.labels;
    const catValues = chartData.target_app.data;
    const colorPalette = ['#e53935', '#10b981'];

    const totalSum = catValues.reduce((a, b) => a + b, 0);

    if (totalSum === 0) {
      document.getElementById('targetAppChart').style.display = 'none';
      document.getElementById('targetAppLegend').innerHTML = '<li class="legend-item"><i class="fas fa-info-circle text-muted"></i> Data target aplikasi belum tersedia.</li>';
    } else {
      new Chart(ctxApp, {
        type: 'doughnut',
        data: {
          labels: catLabels,
          datasets: [{
            data: catValues,
            backgroundColor: colorPalette,
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
            legend: {
              display: false // Menggunakan legend kustom
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
                    label += context.parsed + ' konten';
                  }
                  return label;
                }
              }
            }
          }
        }
      });

      // Generate Custom Legend
      const legendList = document.getElementById('targetAppLegend');
      legendList.innerHTML = '';
      
      catLabels.forEach((label, i) => {
        const value = catValues[i];
        const percent = ((value / totalSum) * 100).toFixed(1);
        const color = colorPalette[i] || '#858796';

        const li = document.createElement('li');
        li.className = 'legend-item';
        li.innerHTML = `
          <span class="legend-dot" style="background-color: ${color}"></span>
          <span class="legend-text" title="${label}">${label} (${percent}%)</span>
        `;
        legendList.appendChild(li);
      });
    }

    // -----------------------------------------------------------------
    // 2. TREN KONTEN 6 BULAN TERAKHIR (Line Chart)
    // -----------------------------------------------------------------
    const ctxTrend = document.getElementById('monthlyTrendChart').getContext('2d');
    
    new Chart(ctxTrend, {
      type: 'line',
      data: {
        labels: chartData.monthly_trend.labels,
        datasets: [
          {
            label: 'Banners',
            data: chartData.monthly_trend.banners,
            borderColor: '#e53935',
            backgroundColor: 'rgba(229, 57, 53, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#e53935',
            pointHoverRadius: 7
          },
          {
            label: 'Tips',
            data: chartData.monthly_trend.tips,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#10b981',
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
              stepSize: 1
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
                  label += context.parsed.y + ' konten';
                }
                return label;
              }
            }
          }
        }
      }
    });

  });
</script>
