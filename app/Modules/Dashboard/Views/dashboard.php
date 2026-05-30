<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
  /* ===== STAT CARDS ===== */
  .stat-card {
    border-radius: 14px;
    border: none;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
    background: #fff;
    overflow: hidden;
    position: relative;
  }

  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.12);
  }

  .stat-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
  }

  .stat-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #8e94a9;
    margin-bottom: 4px;
  }

  .stat-value {
    font-size: 1.9rem;
    font-weight: 800;
    color: #2d3748;
    line-height: 1;
  }

  .stat-bg {
    position: absolute;
    right: -10px;
    bottom: -10px;
    font-size: 5rem;
    opacity: 0.06;
  }

  /* ===== CHART CARDS ===== */
  .dash-card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    height: 100%;
  }

  .dash-card .card-header {
    background: #fff;
    border-bottom: 1px solid #f8f9fa;
    padding: 20px 24px;
    font-weight: 700;
    font-size: 1.05rem;
    color: #2d3748;
  }

  .dash-card .card-body {
    background: #fff;
    padding: 24px;
  }

  /* ===== CHART WRAPPER - Responsive Height ===== */
  .chart-wrapper {
    position: relative;
    width: 100%;
    height: 280px;
  }

  @media (min-width: 768px) {
    .chart-wrapper {
      height: 320px;
    }
  }

  @media (min-width: 992px) {
    .chart-wrapper {
      height: 360px;
    }
  }

  /* ===== TOP PRODUCTS LIST ===== */
  .product-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 0;
    border-bottom: 1px solid #f0f2f5;
  }

  .product-item:last-child {
    border-bottom: none;
  }

  .product-thumb {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 10px;
    object-fit: cover;
    background: #f0f2f5;
  }

  .product-thumb-placeholder {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 10px;
    background: #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    font-size: 1.1rem;
  }

  .product-name {
    font-size: 0.85rem;
    font-weight: 700;
    color: #2d3748;
    max-width: 160px;
  }

  .product-meta {
    font-size: 0.72rem;
    color: #8e94a9;
  }

  .product-price {
    font-size: 0.78rem;
    font-weight: 700;
    color: #0d6efd;
    white-space: nowrap;
    margin-left: auto;
  }

  .product-sales {
    font-size: 0.72rem;
    color: #adb5bd;
    white-space: nowrap;
  }

  /* ===== TARIK DANA LIST ===== */
  .tarik-item {
    padding: 10px 0px;
    border-bottom: 1px solid #f0f2f5;
  }

  .tarik-item:last-child {
    border-bottom: none;
  }

  /* ===== SCROLLABLE LIST ===== */
  .list-scroll {
    max-height: 380px;
    overflow-y: auto;
  }

  .list-scroll::-webkit-scrollbar {
    width: 4px;
  }

  .list-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .list-scroll::-webkit-scrollbar-thumb {
    background: #d0d5e1;
    border-radius: 4px;
  }

  /* ===== RESPONSIVE ADJUSTMENTS ===== */
  @media (max-width: 576px) {
    .stat-card {
      padding: 14px 16px;
      gap: 12px;
    }

    .stat-icon {
      width: 44px;
      height: 44px;
      min-width: 44px;
      font-size: 1.1rem;
      border-radius: 12px;
    }

    .stat-value {
      font-size: 1.5rem;
    }

    .stat-label {
      font-size: 0.7rem;
    }

    .dash-card .card-header {
      font-size: 0.95rem;
      padding: 16px 20px;
    }

    .dash-card .card-body {
      padding: 16px 20px;
    }

    .product-name {
      max-width: 120px;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== BARIS 1: STATISTIK ===== -->
<div class="row g-4 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg,#4e73df,#224abe);">
        <i class="far fa-user"></i>
      </div>
      <div>
        <div class="stat-label">Client</div>
        <div class="stat-value"><?= $jumlahClient ?></div>
      </div>
      <i class="far fa-user stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg,#e74a3b,#be2617);">
        <i class="fas fa-hard-hat"></i>
      </div>
      <div>
        <div class="stat-label">Tukang</div>
        <div class="stat-value"><?= $jumlahTukang ?></div>
      </div>
      <i class="fas fa-hard-hat stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg,#f6c23e,#dda20a);">
        <i class="fas fa-store"></i>
      </div>
      <div>
        <div class="stat-label">Suppliers</div>
        <div class="stat-value"><?= $jumlahSupplier ?></div>
      </div>
      <i class="fas fa-store stat-bg"></i>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background: linear-gradient(135deg,#1cc88a,#13855c);">
        <i class="fas fa-box"></i>
      </div>
      <div>
        <div class="stat-label">Produk</div>
        <div class="stat-value"><?= $jumlahProduk ?></div>
      </div>
      <i class="fas fa-box stat-bg"></i>
    </div>
  </div>
</div>

<!-- ===== BARIS 2: GRAFIK PENDAPATAN & TOP PRODUK ===== -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-8">
    <div class="card dash-card">
      <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-chart-bar text-primary"></i> Total Pendapatan
      </div>
      <div class="card-body">
        <div class="chart-wrapper">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card dash-card">
      <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-trophy text-warning"></i> Top 5 Products
      </div>
      <div class="card-body p-0">
        <div class="list-scroll p-4">
          <?php if (!empty($topProducts)): ?>
            <?php foreach ($topProducts as $product): ?>
              <div class="product-item">
                <?php if (!empty($product['product_photo'])): ?>
                  <?php $src = strpos($product['product_photo'], 'http') === 0 ? $product['product_photo'] : base_url('uploads/products/' . $product['product_photo']); ?>
                  <img src="<?= $src ?>" class="product-thumb" alt="<?= esc($product['product_name']) ?>">
                <?php else: ?>
                  <div class="product-thumb-placeholder"><i class="fas fa-image"></i></div>
                <?php endif; ?>
                <div class="flex-grow-1 text-start" style="min-width:0;">
                  <div class="product-name"><?= esc($product['product_name'] ?? 'N/A') ?></div>
                  <div class="product-meta"><?= esc($product['supplier_name'] ?? 'N/A') ?></div>
                </div>
                <div class="text-end">
                  <div class="product-price">Rp <?= number_format($product['product_price'], 0, ',', '.') ?></div>
                  <div class="product-sales"><?= number_format($product['total_sales']) ?> terjual</div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-muted py-4 small">
              <i class="fas fa-box-open fa-2x mb-2 d-block opacity-50"></i>
              Belum ada data penjualan produk
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== BARIS 3: TARIK DANA & GRAFIK PENJUALAN ===== -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-4">
    <div class="card dash-card">
      <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-money-bill-wave text-danger"></i> Permintaan Tarik Dana
      </div>
      <div class="card-body p-0">
        <div class="list-scroll p-4">
          <?php if (!empty($tarikDana)): ?>
            <?php foreach ($tarikDana as $transaction): ?>
              <?php
              $status = $transaction['status'];
              $badgeClass = $status === 'approved' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
              $icon = $status === 'approved' ? 'fa-check-circle' : ($status === 'pending' ? 'fa-clock' : 'fa-times-circle');
              $label = ucfirst($status);
              ?>
              <div class="tarik-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                      <span class="badge bg-<?= $badgeClass ?> rounded-pill">
                        <i class="fas <?= $icon ?> me-1"></i><?= $label ?>
                      </span>
                      <span class="fw-bold text-<?= $badgeClass ?>" style="font-size:0.85rem;">
                        Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                      </span>
                    </div>
                    <div class="text-muted" style="font-size:0.76rem;">
                      <i class="fas fa-user-circle me-1"></i><?= esc($transaction['tukang_name']) ?>
                    </div>
                  </div>
                  <div class="text-end text-muted" style="font-size:0.72rem; white-space:nowrap;">
                    <?= date('d/m/Y', strtotime($transaction['created_at'])) ?><br>
                    <?= date('H:i', strtotime($transaction['created_at'])) ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-muted py-4 small">
              <i class="fas fa-receipt fa-2x mb-2 d-block opacity-50"></i>
              Belum ada riwayat transaksi
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-8">
    <div class="card dash-card">
      <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-chart-line text-primary"></i> Jumlah Penjualan
      </div>
      <div class="card-body">
        <div class="chart-wrapper">
          <canvas id="salesCountChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
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
<?= $this->endSection() ?>