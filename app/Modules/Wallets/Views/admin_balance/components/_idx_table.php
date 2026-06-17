<?php
/**
 * Component: _idx_table.php
 * Description: Tabel riwayat mutasi saldo platform (Admin Balance Index)
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== TRANSACTION HISTORY TABLE CARD ===== -->
<div class="card table-card">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-transactions" style="width:100%;">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 20%">Tanggal</th>
                        <th class="text-center" style="width: 15%">Tipe</th>
                        <th class="text-center" style="width: 15%">Kategori</th>
                        <th class="text-center" style="width: 15%">Nominal</th>
                        <th class="text-center" style="width: 30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr class="empty-row">
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat transaksi platform.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($history as $h): ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $no++ ?></span>
                                </td>
                                <td class="text-muted"><?= date('d M Y - H:i', strtotime($h['created_at'])) ?> WIB</td>
                                <td>
                                    <?php if ($h['type'] === 'income'): ?>
                                        <span class="badge-premium badge-income">
                                            <i class="fas fa-arrow-down me-1"></i> MASUK
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-premium badge-expense">
                                            <i class="fas fa-arrow-up me-1"></i> KELUAR
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-premium badge-source">
                                        <?= esc(str_replace('_', ' ', $h['source'])) ?>
                                    </span>
                                </td>
                                <td class="fw-bold <?= $h['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                                    <?= $h['type'] === 'income' ? '+' : '-' ?> Rp <?= number_format($h['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="text-start ps-3">
                                    <?= esc($h['description']) ?>
                                    <?php if ($h['reference_id']): ?>
                                        <br><small class="text-muted">Ref: <b><?= esc($h['reference_id']) ?></b></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
