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
