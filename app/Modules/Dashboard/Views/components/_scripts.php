<script>
  <?php if (session()->getFlashdata('success')): ?>
    iziToast.success({
      timeout: 5000,
      title: 'Berhasil',
      message: '<?= session()->getFlashdata('success') ?>',
      position: 'topCenter'
    });
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    iziToast.error({
      timeout: 5000,
      title: 'Gagal',
      message: '<?= strip_tags(session()->getFlashdata('error')) ?>',
      position: 'topCenter'
    });
  <?php endif; ?>

  const salesLabels = <?= isset($salesLabels) ? json_encode($salesLabels) : '[]' ?>;
  const salesCountData = <?= isset($salesCountData) ? json_encode($salesCountData) : '[]' ?>;
  const salesRevenueData = <?= isset($salesRevenueData) ? json_encode($salesRevenueData) : '[]' ?>;

  const tickColor = '#6c757d';
  const gridColor = '#e9ecef';

  // ─── Revenue Bar Chart ───────────────────────────────────────────
  new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
      labels: salesLabels,
      datasets: [{
        label: 'Total Pendapatan (Rp)',
        data: salesRevenueData,
        backgroundColor: 'rgba(78,115,223,0.15)',
        borderColor: 'rgba(78,115,223,1)',
        borderWidth: 2,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [{
          ticks: {
            fontColor: tickColor,
            fontFamily: "'Nunito','Segoe UI','Arial'",
            beginAtZero: true,
            callback: function (v) {
              if (v >= 1e6) return 'Rp ' + (v / 1e6) + 'Jt';
              if (v >= 1e3) return 'Rp ' + (v / 1e3) + 'Rb';
              return 'Rp ' + v;
            }
          },
          gridLines: {
            color: gridColor
          }
        }],
        xAxes: [{
          ticks: {
            fontColor: tickColor,
            fontFamily: "'Nunito','Segoe UI','Arial'",
            fontSize: 10
          },
          gridLines: {
            display: false
          }
        }]
      },
      legend: {
        labels: {
          fontColor: tickColor,
          fontFamily: "'Nunito','Segoe UI','Arial'"
        }
      },
      tooltips: {
        backgroundColor: '#343a40',
        titleFontSize: 12,
        bodyFontSize: 11,
        callbacks: {
          label: function (ti, d) {
            let l = d.datasets[ti.datasetIndex].label || '';
            return l + ': Rp ' + new Intl.NumberFormat('id-ID').format(ti.yLabel);
          }
        }
      }
    }
  });

  // ─── Sales Count Line Chart ──────────────────────────────────────
  new Chart(document.getElementById('salesCountChart'), {
    type: 'line',
    data: {
      labels: salesLabels,
      datasets: [{
        label: 'Jumlah Penjualan',
        data: salesCountData,
        borderColor: 'rgba(28,200,138,1)',
        backgroundColor: 'rgba(28,200,138,0.1)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [{
          ticks: {
            fontColor: tickColor,
            fontFamily: "'Nunito','Segoe UI','Arial'",
            beginAtZero: true,
            precision: 0
          },
          gridLines: {
            color: gridColor
          }
        }],
        xAxes: [{
          ticks: {
            fontColor: tickColor,
            fontFamily: "'Nunito','Segoe UI','Arial'",
            fontSize: 10
          },
          gridLines: {
            display: false
          }
        }]
      },
      legend: {
        labels: {
          fontColor: tickColor,
          fontFamily: "'Nunito','Segoe UI','Arial'"
        }
      },
      tooltips: {
        backgroundColor: '#343a40',
        titleFontSize: 12,
        bodyFontSize: 11
      }
    }
  });
</script>
