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
