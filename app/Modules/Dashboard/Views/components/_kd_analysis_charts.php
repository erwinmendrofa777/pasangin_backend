<style>
  .filter-btn {
    border-color: #e2e8f0;
    color: #64748b;
    background-color: #ffffff;
    transition: all 0.25s ease;
    border-width: 1.5px;
  }
  .filter-btn:hover {
    background-color: #f8fafc;
    color: #e53935;
    border-color: #cbd5e1;
  }
  .filter-btn.active {
    background-color: #e53935 !important;
    color: #ffffff !important;
    border-color: #e53935 !important;
    box-shadow: 0 4px 12px rgba(229, 57, 53, 0.18);
  }
</style>

<!-- 3b. GRAFIK ANALISIS (Chart.js) - Full Width -->
<div class="row mb-4">
  <div class="col-12">
    <div class="chart-card">
      <div class="chart-title-wrapper d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-chart-bar"></i> Total Pengajuan Proyek Desain per Bulan</h4>
        
        <div class="btn-group btn-group-sm" role="group" aria-label="Filter Grafik">
          <button type="button" class="btn filter-btn py-1.5 px-3 active" data-months="3" style="font-weight: 700; border-radius: 20px 0 0 20px; font-size: 0.78rem;">3 Bulan</button>
          <button type="button" class="btn filter-btn py-1.5 px-3" data-months="6" style="font-weight: 700; font-size: 0.78rem; border-left: none; border-right: none;">6 Bulan</button>
          <button type="button" class="btn filter-btn py-1.5 px-3" data-months="12" style="font-weight: 700; border-radius: 0 20px 20px 0; font-size: 0.78rem;">12 Bulan</button>
        </div>
      </div>
      <div class="chart-container" style="min-height: 320px; max-height: 380px;">
        <canvas id="pengajuanProyekChart"></canvas>
      </div>
    </div>
  </div>
</div>
