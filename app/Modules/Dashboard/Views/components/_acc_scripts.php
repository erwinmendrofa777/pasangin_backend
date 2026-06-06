<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    // --- 1. TAB SWITCHER LOGIC ---
    const tabBtns = document.querySelectorAll('.custom-tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        tabBtns.forEach(b => b.classList.remove('active'));
        tabPanes.forEach(p => p.classList.remove('active'));

        this.classList.add('active');
        const targetId = this.getAttribute('data-target');
        document.getElementById(targetId).classList.add('active');
      });
    });

    // --- 2. CHART.JS INITIALIZATION ---
    if (typeof Chart !== 'undefined') {
      const chartData = <?= json_encode($accountingStats['charts']) ?>;

      // Arus Kas Bulanan (Bar & Line Combo Chart)
      const ctxCashflow = document.getElementById('cashflowTrendChart').getContext('2d');
      new Chart(ctxCashflow, {
        type: 'bar',
        data: {
          labels: chartData.cashflow_monthly.labels,
          datasets: [
            {
              type: 'bar',
              label: 'Pendapatan Masuk (Lunas)',
              data: chartData.cashflow_monthly.income,
              backgroundColor: 'var(--palette-primary)',
              hoverBackgroundColor: 'var(--palette-primary-hover)',
              borderWidth: 0,
              barPercentage: 0.6
            },
            {
              type: 'line',
              label: 'Dana Keluar (Pencairan Saldo)',
              data: chartData.cashflow_monthly.expense,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.05)',
              borderWidth: 3,
              fill: true,
              pointBackgroundColor: '#f59e0b',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 5,
              pointHoverRadius: 7
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 20,
              fontFamily: "'Inter', 'Segoe UI', sans-serif",
              fontSize: 11,
              fontColor: '#475569'
            }
          },
          tooltips: {
            backgroundColor: 'rgba(15, 23, 42, 0.95)',
            titleFontFamily: "'Inter', sans-serif",
            bodyFontFamily: "'Inter', sans-serif",
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(tooltipItem, data) {
                let label = data.datasets[tooltipItem.datasetIndex].label || '';
                if (label) label += ': ';
                label += 'Rp ' + tooltipItem.yLabel.toLocaleString('id-ID');
                return label;
              }
            }
          },
          scales: {
            yAxes: [{
              gridLines: { color: '#f1f5f9', drawBorder: false },
              ticks: {
                beginAtZero: true,
                fontFamily: "'Inter', sans-serif",
                fontColor: '#94a3b8',
                callback: function(value) {
                  if (value >= 1e6) return 'Rp ' + (value / 1e6).toFixed(1) + ' jt';
                  return 'Rp ' + value.toLocaleString('id-ID');
                }
              }
            }],
            xAxes: [{
              gridLines: { display: false, drawBorder: false },
              ticks: {
                fontFamily: "'Inter', sans-serif",
                fontColor: '#64748b',
                fontStyle: 'bold'
              }
            }]
          }
        }
      });

      // Kontribusi Pendapatan Divisi (Doughnut Chart)
      const ctxDivision = document.getElementById('divisionRevenueChart').getContext('2d');
      const divLabels = chartData.division_revenue.labels;
      const divValues = chartData.division_revenue.data;
      const colorPalette = ['#6366f1', 'var(--palette-primary)', '#f59e0b']; // Indigo, Teal, Amber
      
      const totalSum = divValues.reduce((a, b) => a + b, 0);

      if (totalSum === 0) {
        document.getElementById('divisionRevenueLegend').innerHTML = '<li class="legend-item"><i class="fas fa-info-circle text-muted"></i> Belum ada data pendapatan.</li>';
      } else {
        new Chart(ctxDivision, {
          type: 'doughnut',
          data: {
            labels: divLabels,
            datasets: [{
              data: divValues,
              backgroundColor: colorPalette,
              borderWidth: 3,
              borderColor: '#ffffff',
              hoverBorderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 75,
            legend: { display: false },
            tooltips: {
              backgroundColor: 'rgba(15, 23, 42, 0.95)',
              bodyFontFamily: "'Inter', sans-serif",
              padding: 12,
              cornerRadius: 8,
              callbacks: {
                label: function(tooltipItem, data) {
                  let label = data.labels[tooltipItem.index] || '';
                  if (label) label += ': ';
                  label += 'Rp ' + data.datasets[0].data[tooltipItem.index].toLocaleString('id-ID');
                  return label;
                }
              }
            }
          }
        });

        // Generate Custom Legend
        const legendList = document.getElementById('divisionRevenueLegend');
        legendList.innerHTML = '';
        divLabels.forEach((label, i) => {
          const value = divValues[i];
          const percent = ((value / totalSum) * 100).toFixed(1);
          const color = colorPalette[i];
          
          const li = document.createElement('li');
          li.className = 'legend-item';
          li.innerHTML = `<span class="legend-dot" style="background-color: ${color}"></span><span title="${label}">${label} (${percent}%)</span>`;
          legendList.appendChild(li);
        });
      }
    } else {
      console.error('Chart.js library not loaded.');
    }
  });
</script>
