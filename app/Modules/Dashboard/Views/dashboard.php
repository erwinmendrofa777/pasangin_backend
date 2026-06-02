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

<?= $this->include('App\Modules\Dashboard\Views\components\_stats_cards') ?>

<!-- ===== BARIS 2: GRAFIK PENDAPATAN & TOP PRODUK ===== -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-8">
    <?= $this->include('App\Modules\Dashboard\Views\components\_revenue_chart') ?>
  </div>

  <div class="col-12 col-lg-4">
    <?= $this->include('App\Modules\Dashboard\Views\components\_top_products') ?>
  </div>
</div>

<!-- ===== BARIS 3: TARIK DANA & GRAFIK PENJUALAN ===== -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-4">
    <?= $this->include('App\Modules\Dashboard\Views\components\_tarik_dana') ?>
  </div>

  <div class="col-12 col-lg-8">
    <?= $this->include('App\Modules\Dashboard\Views\components\_sales_chart') ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
  <?= $this->include('App\Modules\Dashboard\Views\components\_scripts') ?>
<?= $this->endSection() ?>