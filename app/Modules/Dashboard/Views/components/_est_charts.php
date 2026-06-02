<!-- 4. ROW 2: GRAPH VISUALIZATION (CHARTS) -->
<div class="row">
  <!-- Left Column: Tren Riwayat Nilai Proyek (8 Col) -->
  <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
    <div class="card premium-card">
      <div class="card-header">
        <h4><i class="fas fa-chart-line"></i> Tren Riwayat Anggaran RAB & Addendum</h4>
      </div>
      <div class="card-body">
        <div class="chart-container-premium">
          <canvas id="trenNilaiChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Proporsi Kategori Pekerjaan RAB (4 Col) -->
  <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-12">
    <div class="card premium-card">
      <div class="card-header">
        <h4><i class="fas fa-chart-pie"></i> Proporsi Kategori Pekerjaan RAB</h4>
      </div>
      <div class="card-body">
        <div class="chart-container-premium d-flex flex-column align-items-center justify-content-center">
          <div style="position: relative; height: 230px; width: 100%;">
            <canvas id="kategoriRabChart"></canvas>
          </div>
          <!-- Custom Legend -->
          <ul class="legend-list" id="kategoriLegend"></ul>
        </div>
      </div>
    </div>
  </div>
</div>
