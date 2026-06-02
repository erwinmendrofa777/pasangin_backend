<!-- ============================================== -->
<!-- TAB 3: DOMPET & VOUCHER -->
<!-- ============================================== -->
<div id="tab-wallet" class="tab-pane">
  <!-- Ringkasan Dompet & Voucher -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
      <div class="premium-card mb-0 p-4 text-center border-0 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b, #0f172a);">
        <div class="text-start">
          <div class="text-light fw-bold" style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.8;">Total Saldo Tukang (Liabilitas)</div>
          <div class="fw-bold text-white mt-1" style="font-size: 1.4rem;">Rp <?= number_format($accountingStats['kpis']['total_tukang_balance'], 0, ',', '.') ?></div>
        </div>
        <i class="fas fa-wallet text-white" style="font-size: 2.5rem; opacity: 0.3;"></i>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="premium-card mb-0 p-4 text-center border-0 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: linear-gradient(135deg, #0d6efd, #0a58ca);">
        <div class="text-start">
          <div class="text-light fw-bold" style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.8;">Total Penghematan Voucher</div>
          <div class="fw-bold text-white mt-1" style="font-size: 1.4rem;">Rp <?= number_format($accountingStats['kpis']['total_voucher_discount'], 0, ',', '.') ?></div>
        </div>
        <i class="fas fa-ticket-alt text-white" style="font-size: 2.5rem; opacity: 0.3;"></i>
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
