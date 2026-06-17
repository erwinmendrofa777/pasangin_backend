<!-- ============================================== -->
<!-- TAB 3: DOMPET & VOUCHER -->
<!-- ============================================== -->
<div id="tab-wallet" class="tab-pane">
  <!-- Ringkasan Dompet & Voucher -->
  <div class="row g-4 mb-4">
    <!-- Saldo Tukang -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card card-slate h-100 mb-0">
        <div class="stat-info">
          <div class="stat-label">Saldo Tukang (Liabilitas)</div>
          <div class="stat-value"><?= number_format($accountingStats['kpis']['total_tukang_balance'], 0, ',', '.') ?></div>
        </div>
        <div class="stat-icon-wrapper"><i class="fas fa-wallet"></i></div>
      </div>
    </div>
    <!-- Saldo Platform Internal -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card card-emerald h-100 mb-0">
        <div class="stat-info">
          <div class="stat-label">Saldo Platform (Internal)</div>
          <div class="stat-value"><?= number_format($accountingStats['kpis']['total_admin_balance'], 0, ',', '.') ?></div>
        </div>
        <div class="stat-icon-wrapper"><i class="fas fa-university"></i></div>
      </div>
    </div>
    <!-- Saldo Penampungan Midtrans -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card card-primary h-100 mb-0">
        <div class="stat-info">
          <div class="stat-label">Saldo Midtrans (Payin)</div>
          <div class="stat-value"><?= number_format($accountingStats['kpis']['midtrans_payin_balance'], 0, ',', '.') ?></div>
        </div>
        <div class="stat-icon-wrapper"><i class="fas fa-dollar-sign"></i></div>
      </div>
    </div>
    <!-- Penghematan Voucher -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card card-purple h-100 mb-0">
        <div class="stat-info">
          <div class="stat-label">Penghematan Voucher</div>
          <div class="stat-value"><?= number_format($accountingStats['kpis']['total_voucher_discount'], 0, ',', '.') ?></div>
        </div>
        <div class="stat-icon-wrapper"><i class="fas fa-ticket-alt"></i></div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Antrean Tarik Dana Terkini -->
    <div class="col-12 col-xl-7 mb-4">
      <div class="premium-card h-100 mb-0">
        <div class="premium-card-header">
          <h4><i class="fas fa-hand-holding-usd text-amber"></i> 5 Antrean Tarik Dana Terkini</h4>
          <a href="<?= base_url('admin/wallet/withdrawals') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Kelola Semua</a>
        </div>
        <div class="p-0 table-responsive">
          <?php if (empty($accountingStats['pendingWithdrawals'])): ?>
            <div class="empty-state">
              <i class="fas fa-check-circle text-emerald"></i><p>Semua antrean tarik dana telah diproses!</p>
            </div>
          <?php else: ?>
            <table class="table premium-table">
              <thead>
                <tr>
                  <th>Tukang</th>
                  <th>No. Telepon</th>
                  <th>Nominal</th>
                  <th>Tgl Pengajuan</th>
                  <th class="text-center">Aksi Cepat</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($accountingStats['pendingWithdrawals'] as $w): ?>
                  <tr>
                    <td class="fw-bold text-dark"><?= esc($w['tukang_name']) ?></td>
                    <td><?= esc($w['phone']) ?></td>
                    <td class="fw-bold text-amber">Rp <?= number_format($w['amount'], 0, ',', '.') ?></td>
                    <td style="font-size: 0.8rem;"><?= date('d M Y', strtotime($w['created_at'])) ?></td>
                    <td class="text-center d-flex gap-1 justify-content-center">
                      <a href="<?= base_url('admin/wallet/withdraw-approve/' . $w['id'] . '/approved') ?>" class="btn btn-sm btn-success px-2 py-1 shadow-sm" onclick="return confirm('Setujui penarikan dana ini?')" style="border-radius:6px; font-size:0.7rem;" title="Setujui">
                        <i class="fas fa-check"></i>
                      </a>
                      <a href="<?= base_url('admin/wallet/withdraw-approve/' . $w['id'] . '/rejected') ?>" class="btn btn-sm btn-danger px-2 py-1 shadow-sm" onclick="return confirm('Tolak penarikan dana ini?')" style="border-radius:6px; font-size:0.7rem;" title="Tolak">
                        <i class="fas fa-times"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Voucher Promo Aktif -->
    <div class="col-12 col-xl-5 mb-4">
      <div class="premium-card h-100 mb-0">
        <div class="premium-card-header">
          <h4><i class="fas fa-tags text-primary"></i> Voucher Promo Aktif</h4>
          <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-sm btn-light fw-bold" style="font-size:0.75rem; border-radius: 20px;">Tambah</a>
        </div>
        <div class="p-0 table-responsive">
          <?php if (empty($accountingStats['activeVouchers'])): ?>
            <div class="empty-state">
              <i class="fas fa-ticket-alt"></i><p>Tidak ada voucher promo aktif saat ini.</p>
            </div>
          <?php else: ?>
            <table class="table premium-table">
              <thead>
                <tr>
                  <th>Kode Voucher</th>
                  <th>Potongan</th>
                  <th>Masa Berlaku</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($accountingStats['activeVouchers'] as $v): ?>
                  <tr>
                    <td class="fw-bold text-dark">
                      <span class="badge badge-light text-primary border shadow-sm px-2 py-1" style="font-size:0.75rem; border-radius:6px;">
                        <?= esc($v['code']) ?>
                      </span>
                    </td>
                    <td class="fw-bold text-emerald">Rp <?= number_format($v['discount_nominal'], 0, ',', '.') ?></td>
                    <td style="font-size:0.8rem;"><?= date('d M Y', strtotime($v['valid_until'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
