<!-- 2. Stat Cards (Total Proyek) -->
<div class="stats-grid">
  <!-- Card Desain -->
  <div class="stat-card">
    <div class="stat-info">
      <h3>Proyek Desain</h3>
      <div class="stat-value"><?= number_format($desainerStats['totals']['design']) ?></div>
    </div>
    <div class="stat-icon icon-design">
      <i class="fas fa-pencil-ruler"></i>
    </div>
  </div>

  <!-- Card Konstruksi -->
  <div class="stat-card">
    <div class="stat-info">
      <h3>Proyek Konstruksi</h3>
      <div class="stat-value"><?= number_format($desainerStats['totals']['construction']) ?></div>
    </div>
    <div class="stat-icon icon-construction">
      <i class="fas fa-building"></i>
    </div>
  </div>

  <!-- Card Renovasi -->
  <div class="stat-card">
    <div class="stat-info">
      <h3>Proyek Renovasi</h3>
      <div class="stat-value"><?= number_format($desainerStats['totals']['renovation']) ?></div>
    </div>
    <div class="stat-icon icon-renovation">
      <i class="fas fa-tools"></i>
    </div>
  </div>

  <!-- Card Grand Total -->
  <div class="stat-card" style="background: #fafbff; border-color: rgba(103, 119, 239, 0.2);">
    <div class="stat-info">
      <h3>Akumulasi Proyek</h3>
      <div class="stat-value" style="color: #6777ef;"><?= number_format($desainerStats['totals']['grand_total']) ?></div>
    </div>
    <div class="stat-icon icon-grand">
      <i class="fas fa-folder-open"></i>
    </div>
  </div>
</div>
