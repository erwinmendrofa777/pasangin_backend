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
            borderColor: '#e53935',
            backgroundColor: 'rgba(229, 57, 53, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#e53935',
            pointHoverRadius: 7
          },
          {
            label: 'Total Anggaran Addendum',
            data: monthlyAddendum,
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.05)',
            fill: true,
            tension: 0.3,
            borderWidth: 3,
            pointBackgroundColor: '#f59e0b',
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
      '#e53935', // Brand Red (Primary)
      '#10b981', // Emerald Green
      '#f59e0b', // Amber
      '#7c3aed', // Purple
      '#6366f1', // Indigo
      '#64748b'  // Slate Grey
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
