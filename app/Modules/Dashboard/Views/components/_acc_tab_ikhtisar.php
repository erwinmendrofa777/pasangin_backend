<!-- ============================================== -->
<!-- TAB 1: IKHTISAR KEUANGAN -->
<!-- ============================================== -->
<div id="tab-ikhtisar" class="tab-pane active">
  <div class="row">
    <!-- Arus Kas Bulanan -->
    <div class="col-12 col-xl-8 mb-4">
      <div class="premium-card h-100 mb-0">
        <div class="premium-card-header">
          <h4><i class="fas fa-chart-area text-primary"></i> Tren Arus Kas Bulanan (6 Bulan Terakhir)</h4>
        </div>
        <div class="premium-card-body">
          <div style="position: relative; height: 280px; width: 100%;">
            <canvas id="cashflowTrendChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- Kontribusi Pendapatan Divisi -->
    <div class="col-12 col-xl-4 mb-4">
      <div class="premium-card h-100 mb-0">
        <div class="premium-card-header">
          <h4><i class="fas fa-chart-pie text-indigo"></i> Kontribusi Pendapatan Divisi</h4>
        </div>
        <div class="premium-card-body d-flex flex-column justify-content-center">
          <div style="position: relative; height: 210px; width: 100%;">
            <canvas id="divisionRevenueChart"></canvas>
          </div>
          <ul id="divisionRevenueLegend" class="chart-legend"></ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Log Invoice Lintas Divisi -->
  <div class="premium-card">
    <div class="premium-card-header">
      <h4><i class="fas fa-list-alt text-primary"></i> 5 Log Invoice Lintas Divisi Terbaru</h4>
      <a href="<?= base_url('admin/design') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Semua Invoice</a>
    </div>
    <div class="p-0 table-responsive">
      <?php if (empty($accountingStats['recentInvoices'])): ?>
        <div class="empty-state">
          <i class="fas fa-file-invoice"></i><p>Belum ada data invoice saat ini.</p>
        </div>
      <?php else: ?>
        <table class="table premium-table">
          <thead>
            <tr>
              <th>Divisi</th>
              <th>ID Invoice</th>
              <th>Keterangan</th>
              <th>Nominal</th>
              <th>Voucher</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($accountingStats['recentInvoices'] as $inv): ?>
              <tr>
                <td><span class="badge-tag"><?= esc($inv['tipe']) ?></span></td>
                <td class="fw-bold text-dark">#<?= esc($inv['id']) ?></td>
                <td class="text-truncate" style="max-width: 200px;"><?= esc($inv['description'] ?: 'Pembayaran Proyek') ?></td>
                <td class="fw-bold text-primary">Rp <?= number_format($inv['amount'], 0, ',', '.') ?></td>
                <td>
                  <?php if ($inv['voucher_code']): ?>
                    <span class="badge badge-light text-dark shadow-sm border" style="font-size:0.7rem; border-radius:6px;">
                      <i class="fas fa-tag text-primary me-1"></i><?= esc($inv['voucher_code']) ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if (strtoupper($inv['status']) == 'PAID'): ?>
                    <span class="badge-status status-paid"><i class="fas fa-check-circle"></i> LUNAS</span>
                  <?php else: ?>
                    <span class="badge-status status-pending"><i class="fas fa-clock"></i> PENDING</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
