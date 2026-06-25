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

  const projectLabels = <?= isset($projectLabels) ? json_encode($projectLabels) : '[]' ?>;
  const designTrend = <?= isset($designTrend) ? json_encode($designTrend) : '[]' ?>;
  const constTrend = <?= isset($constTrend) ? json_encode($constTrend) : '[]' ?>;
  const renovTrend = <?= isset($renovTrend) ? json_encode($renovTrend) : '[]' ?>;

  const projectStatusLabels = <?= isset($projectStatusLabels) ? json_encode($projectStatusLabels) : '[]' ?>;
  const projectStatusData = <?= isset($projectStatusData) ? json_encode($projectStatusData) : '[]' ?>;

  const tickColor = '#6c757d';
  const gridColor = '#e9ecef';

  // ─── Project Trend Line Chart ──────────────────────────────────────
  new Chart(document.getElementById('projectTrendChart'), {
    type: 'line',
    data: {
      labels: projectLabels,
      datasets: [
        {
          label: 'Desain',
          data: designTrend,
          borderColor: '#FF6F61',
          backgroundColor: 'rgba(255, 111, 97, 0.05)',
          fill: true,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 6
        },
        {
          label: 'Konstruksi',
          data: constTrend,
          borderColor: '#1cc88a',
          backgroundColor: 'rgba(28,200,138,0.05)',
          fill: true,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 6
        },
        {
          label: 'Renovasi',
          data: renovTrend,
          borderColor: '#f6c23e',
          backgroundColor: 'rgba(246,194,62,0.05)',
          fill: true,
          tension: 0.35,
          pointRadius: 4,
          pointHoverRadius: 6
        }
      ]
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

  // ─── Project Status Doughnut Chart ─────────────────────────────────
  new Chart(document.getElementById('projectStatusChart'), {
    type: 'doughnut',
    data: {
      labels: projectStatusLabels,
      datasets: [{
        data: projectStatusData,
        backgroundColor: [
          '#f6c23e', // Survey & Pending (kuning)
          '#FF6F61', // Tahap Desain (merah coral)
          '#36b9cc', // Tahap RAB (cyan)
          '#1cc88a'  // Masa Pelaksanaan (hijau)
        ],
        hoverBackgroundColor: [
          '#dfa82c',
          '#e55f52',
          '#258391',
          '#17a673'
        ],
        hoverBorderColor: "rgba(234, 236, 244, 1)"
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        labels: {
          fontColor: tickColor,
          fontFamily: "'Nunito','Segoe UI','Arial'",
          boxWidth: 15,
          padding: 15
        }
      },
      tooltips: {
        backgroundColor: '#343a40',
        bodyFontSize: 12,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10
      },
      cutoutPercentage: 65
    }
  });
</script>
