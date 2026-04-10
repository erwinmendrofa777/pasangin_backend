<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BARIS 1: STATISTIK ATAS -->
<div class="row">
  
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card shadow card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="far fa-user"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Client</h4>
        </div>
        <div class="card-body">
          <?= $jumlahClient ?>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card shadow card-statistic-1">
      <div class="card-icon bg-danger">
        <i class="fas fa-tools"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Tukang</h4>
        </div>
        <div class="card-body">
          <?= $jumlahTukang ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card shadow card-statistic-1">
      <div class="card-icon bg-warning">
        <i class="fas fa-shop"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>suppliers</h4>
        </div>
        <div class="card-body">
          <?= $jumlahSupplier ?>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card shadow card-statistic-1">
      <div class="card-icon bg-success">
        <i class="fas fa-wallet"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Produk</h4>
        </div>
        <div class="card-body">
          <?= $jumlahProduk ?>
        </div>
      </div>
    </div>
  </div>  

</div>

<!-- BARIS 2: GRAFIK PENDAPATAN & PENJUALAN -->
<div class="row">

  <div class="col-lg-8 col-12">
    <div class="card shadow">
      <div class="card-header">
        <h4>Total Pendapatan</h4>
      </div>
      <div class="card-body" style="height: 355px;">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow gradient-bottom">
      <div class="card-header">
        <h4>Top 5 Products</h4>
      </div>
      <div class="card-body" id="top-5-scroll" style="max-height: 300px; overflow-y: auto; padding: 1.25rem;">
        <ul class="list-unstyled list-unstyled-border">
          <?php if (!empty($topProducts)): ?>
            <?php foreach ($topProducts as $product): ?>
              <li class="media d-flex align-items-start" style="margin-bottom: 1rem;">
                <?php if (!empty($product['product_photo'])): ?>
                  <?php if (strpos($product['product_photo'], 'http') === 0): ?>
                    <img src="<?= $product['product_photo'] ?>" class="rounded me-3 flex-shrink-0" width="55" height="55" style="object-fit: cover; object-position: center;" alt="<?= esc($product['product_name']) ?>">
                  <?php else: ?>
                    <img src="<?= base_url('uploads/products/'.$product['product_photo']) ?>" class="rounded me-3 flex-shrink-0" width="55" height="55" style="object-fit: cover; object-position: center;" alt="<?= esc($product['product_name']) ?>">
                  <?php endif; ?>
                <?php else: ?>
                  <div class="rounded me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background-color: #f8f9fa; color: #6c757d;">
                    <i class="fas fa-image"></i>
                  </div>
                <?php endif; ?>
                <div class="flex-grow-1" style="min-width: 0;">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="media-title font-weight-bold" style="font-size: 14px; margin-bottom: 2px;"><?= esc($product['product_name'] ?? 'N/A') ?></div>
                    <div class="font-weight-600 text-muted text-small"><?= number_format($product['total_sales']) ?> penjualan</div>
                  </div>
                  <div class="text-muted text-small mb-2" style="font-size: 12px;">Supplier: <?= esc($product['supplier_name'] ?? 'N/A') ?></div>
                  <div class="budget-price">
                    <div class="budget-price-square bg-danger" data-width="" style="height: 8px;"></div>
                    <div class="budget-price-label" style="font-size: 11px;">Rp <?= number_format($product['product_price'], 0, ',', '.') ?></div>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="media text-center">
              <div class="media-body">
                <div class="text-muted">Belum ada data penjualan produk</div>
              </div>
            </li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="card-footer pt-3 d-flex justify-content-center">
        
      </div>
    </div>
  </div>
</div>

<!-- BARIS 3: GRAFIK PENJUALAN -->
<div class="row">

  <div class="col-lg-4 col-12">
    <div class="card shadow pb-2">
      <div class="card-header">
        <h4>Permintaan Tarik Dana</h4>
      </div>
      <div class="card-body" style="height: 370px; overflow-y: auto; padding: 1.25rem;">
        <?php if (!empty($tarikDana)): ?>
          <div class="list-group list-group-flush">
            <?php foreach ($tarikDana as $transaction): ?>
              <div class="list-group-item border-0 px-0 py-2 ">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">

                      <?php if ($transaction['status'] == 'approved'): ?>
                        <span class="badge bg-success me-2">
                          <i class="fas fa-arrow-down"></i> Approved
                        </span>
                      <?php elseif ($transaction['status'] == 'pending'): ?>
                        <span class="badge bg-warning me-2">
                          <i class="fas fa-arrow-up"></i> Pending
                        </span>
                      <?php else: ?>
                        <span class="badge bg-danger me-2">
                          <i class="fas fa-arrow-up"></i> Rejected
                        </span>
                      <?php endif; ?>

                      <span class="font-weight-bold text-<?= $transaction['status'] == 'approved' ? 'success' : ($transaction['status'] == 'pending' ? 'warning' : 'danger') ?>">
                        Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                      </span>
                    </div>
                    <div class="text-muted small">
                      <div>Nominal: Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></div>
                    </div>
                      <div class="text-muted small mt-1">Nama Tukang : <?= esc($transaction['tukang_name']) ?></div>
                  </div>
                  <div class="text-muted small text-end">
                    <?= date('d/m/Y', strtotime($transaction['created_at'])) ?><br>
                    <?= date('H:i', strtotime($transaction['created_at'])) ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center text-muted py-4">
            <i class="fas fa-receipt fa-2x mb-3"></i>
            <div>Belum ada riwayat transaksi</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-8 col-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="mb-0 pb-0">Jumlah Penjualan</h4>
      </div>
      <div class="card-body px-1 mt-0 pt-0" style="height: 380px;">
        <canvas id="salesCountChart"></canvas>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<!-- JS KHUSUS DASHBOARD INI (Agar Grafiknya Jalan) -->
 <!-- 1. Panggil Library Chart.js dari CDN -->

<?= $this->section('script') ?>
<script>
// 2. Siapkan data dari Controller.
// Kode ini akan mengambil variabel $chartLabels & $chartData yang Anda kirim dari controller.
// Data baru untuk Grafik Penjualan
const salesLabels = <?= isset($salesLabels) ? json_encode($salesLabels) : '[]' ?>;
const salesCountData = <?= isset($salesCountData) ? json_encode($salesCountData) : '[]' ?>;
const salesRevenueData = <?= isset($salesRevenueData) ? json_encode($salesRevenueData) : '[]' ?>;


// Opsi styling umum untuk kedua chart
const tickColor = '#6c757d';
const gridColor = '#e9ecef';

// 4. Inisialisasi Grafik Pendapatan (Bar Chart)
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
  type: 'bar',
  data: {
    labels: salesLabels,
    datasets: [{
      label: 'Total Pendapatan (Rp)',
      data: salesRevenueData,
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
        ticks: {
          fontColor: tickColor,
          fontFamily: "'Nunito', 'Segoe UI', 'Arial'",
          beginAtZero: true,
          // Format angka menjadi Rupiah (dengan singkatan Jt/Rb)
          callback: function(value) {
            if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
            if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
          }
        },
        gridLines: { color: gridColor }
      }],
      xAxes: [{
        ticks: {
          fontColor: tickColor,
          fontFamily: "'Nunito', 'Segoe UI', 'Arial'",
          fontSize: 10 // Ukuran font untuk sumbu X (bulan & tahun)
        },
        gridLines: { display: false }
      }]
    },
    legend: {
      labels: {
        fontColor: tickColor,
        fontFamily: "'Nunito', 'Segoe UI', 'Arial'"
      }
    },
    tooltips: {
      backgroundColor: '#343a40',
      titleFontSize: 12,
      titleFontFamily: "'Nunito', 'Segoe UI', 'Arial'",
      bodyFontSize: 11,
      bodyFontFamily: "'Nunito', 'Segoe UI', 'Arial'",
      callbacks: {
        label: function(tooltipItem, data) {
          let label = data.datasets[tooltipItem.datasetIndex].label || '';
          if (label) {
            label += ': ';
          }
          if (tooltipItem.yLabel !== null) {
            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(tooltipItem.yLabel);
          }
          return label;
        }
      }
    }
  }
});

// 5. Inisialisasi Grafik Jumlah Penjualan (Line Chart)
const salesCountCtx = document.getElementById('salesCountChart');
new Chart(salesCountCtx, {
  type: 'line',
  data: {
    labels: salesLabels,
    datasets: [{
      label: 'Jumlah Penjualan',
      data: salesCountData,
      borderColor: 'rgba(54, 162, 235, 1)',
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      fill: true,
      tension: 0.1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
        ticks: {
          fontColor: tickColor,
          fontFamily: "'Nunito', 'Segoe UI', 'Arial'",
          beginAtZero: true,
          precision: 0 // Memastikan tidak ada angka desimal
        },
        gridLines: { color: gridColor }
      }],
      xAxes: [{
        ticks: {
          fontColor: tickColor,
          fontFamily: "'Nunito', 'Segoe UI', 'Arial'",
          fontSize: 10 // Ukuran font untuk sumbu X (bulan & tahun)
        },
        gridLines: { display: false }
      }]
    },
    legend: {
      labels: {
        fontColor: tickColor,
        fontFamily: "'Nunito', 'Segoe UI', 'Arial'"
      }
    },
    tooltips: {
      backgroundColor: '#343a40',
      titleFontSize: 14,
      titleFontFamily: "'Nunito', 'Segoe UI', 'Arial'",
      bodyFontSize: 12,
      bodyFontFamily: "'Nunito', 'Segoe UI', 'Arial'"
    }
  }
});
</script>
<?= $this->endSection() ?>