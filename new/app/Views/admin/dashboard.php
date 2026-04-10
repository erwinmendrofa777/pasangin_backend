<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BARIS 1: STATISTIK ATAS -->
<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">Statistik Order -
          <div class="dropdown d-inline">
            <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">August</a>
            <ul class="dropdown-menu dropdown-menu-sm">
              <li class="dropdown-title">Select Month</li>
              <li><a href="#" class="dropdown-item">August</a></li>
              <li><a href="#" class="dropdown-item">September</a></li>
            </ul>
          </div>
        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">24</div>
            <div class="card-stats-item-label">Pending</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">12</div>
            <div class="card-stats-item-label">Shipping</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">23</div>
            <div class="card-stats-item-label">Completed</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Orders</h4>
        </div>
        <div class="card-body">
          59
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="balance-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-dollar-sign"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Balance</h4>
        </div>
        <div class="card-body">
          $187,13
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="sales-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-shopping-bag"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Sales</h4>
        </div>
        <div class="card-body">
          4,732
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BARIS 2: GRAFIK & TOP PRODUCT -->
<div class="row">
  <!-- GRAFIK BESAR -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h4>Budget vs Sales</h4>
      </div>
      <div class="card-body">
        <canvas id="myChart" height="158"></canvas>
      </div>
    </div>
  </div>
  
  <!-- TOP 5 PRODUCTS -->
  <div class="col-lg-4">
    <div class="card gradient-bottom">
      <div class="card-header">
        <h4>Top 5 Products</h4>
        <div class="card-header-action dropdown">
          <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Month</a>
          <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <li class="dropdown-title">Select Period</li>
            <li><a href="#" class="dropdown-item">Today</a></li>
            <li><a href="#" class="dropdown-item">Week</a></li>
            <li><a href="#" class="dropdown-item active">Month</a></li>
            <li><a href="#" class="dropdown-item">This Year</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body" id="top-5-scroll">
        <ul class="list-unstyled list-unstyled-border">
          <li class="media">
            <img class="mr-3 rounded" width="55" src="<?= base_url('assets/img/products/product-3-50.png') ?>" alt="product">
            <div class="media-body">
              <div class="float-right"><div class="font-weight-600 text-muted text-small">86 Sales</div></div>
              <div class="media-title">oPhone S9 Limited</div>
              <div class="mt-1">
                <div class="budget-price">
                  <div class="budget-price-square bg-primary" data-width="64%"></div>
                  <div class="budget-price-label">$68,714</div>
                </div>
                <div class="budget-price">
                  <div class="budget-price-square bg-danger" data-width="43%"></div>
                  <div class="budget-price-label">$38,700</div>
                </div>
              </div>
            </div>
          </li>
          <!-- Kamu bisa tambah list produk lain disini -->
        </ul>
      </div>
      <div class="card-footer pt-3 d-flex justify-content-center">
        <div class="budget-price justify-content-center">
          <div class="budget-price-square bg-primary" data-width="20"></div>
          <div class="budget-price-label">Selling Price</div>
        </div>
        <div class="budget-price justify-content-center">
          <div class="budget-price-square bg-danger" data-width="20"></div>
          <div class="budget-price-label">Budget Price</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<!-- JS KHUSUS DASHBOARD INI (Agar Grafiknya Jalan) -->
<?= $this->section('script') ?>
<script>
  "use strict";

  // Grafik Sales (Kanan Atas)
  var sales_chart = document.getElementById("sales-chart").getContext('2d');
  var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
  sales_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
  sales_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');
  new Chart(sales_chart, {
    type: 'line',
    data: {
      labels: ['16-07-2018', '17-07-2018', '18-07-2018', '19-07-2018', '20-07-2018'],
      datasets: [{
        label: 'Sales',
        data: [19, 15, 30, 25, 40],
        backgroundColor: sales_chart_bg_color,
        borderWidth: 2,
        borderColor: '#6777ef',
        pointBorderWidth: 0,
        pointBorderColor: 'transparent',
        pointRadius: 3,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
      }]
    },
    options: {
      layout: { padding: { left: -10, right: 0, top: 0, bottom: -10 } },
      legend: { display: false },
      scales: {
        yAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { beginAtZero: true } }],
        xAxes: [{ gridLines: { display: false, drawBorder: false } }]
      }
    }
  });

  // Grafik Utama (Budget vs Sales)
  var ctx = document.getElementById("myChart").getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ["January", "February", "March", "April", "May", "June", "July", "August"],
      datasets: [{
        label: 'Sales',
        data: [3200, 1800, 4305, 3022, 6310, 5120, 5880, 6154],
        borderWidth: 2,
        backgroundColor: 'rgba(63,82,227,.8)',
        borderColor: 'transparent',
        pointBorderWidth: 0,
        pointRadius: 3.5,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
      }, {
        label: 'Budget',
        data: [2207, 3403, 2200, 5025, 2302, 4208, 3880, 4880],
        borderWidth: 2,
        backgroundColor: 'rgba(254,86,83,.7)',
        borderColor: 'transparent',
        pointBorderWidth: 0,
        pointRadius: 3.5,
        pointBackgroundColor: 'transparent',
        pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
      }]
    },
    options: {
      legend: { display: false },
      scales: {
        yAxes: [{ gridLines: { drawBorder: false, color: '#f2f2f2' }, ticks: { beginAtZero: true, stepSize: 1500, callback: function(value) { return '$' + value; } } }],
        xAxes: [{ gridLines: { display: false } }]
      },
    }
  });
</script>
<?= $this->endSection() ?>
