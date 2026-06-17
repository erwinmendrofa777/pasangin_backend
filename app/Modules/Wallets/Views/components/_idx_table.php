<?php
/**
 * Component: _idx_table.php
 * Description: Table card untuk daftar saldo mitra tukang (Wallets Index)
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center">Nama Tukang</th>
                        <th class="text-center">No. HP</th>
                        <th class="text-center">Saldo Saat Ini</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (is_array($tukang) ? $tukang : [] as $key => $t): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="fw-semibold text-start ps-3"><?= esc($t['name']) ?></td>
                            <td class="text-muted"><?= esc($t['phone'] ?: '-') ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($t['balance'], 0, ',', '.') ?></td>
                            <td>
                                <?php if (can('wallet_manage')): ?>
                                    <button class="btn btn-primary btn-sm my-1" style="border-radius: 8px; font-weight: 600;"
                                        data-bs-toggle="modal" data-bs-target="#modalSaldo<?= $t['id'] ?>">
                                        <i class="fas fa-edit me-1"></i>Kelola Saldo
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted"><i class="fas fa-lock me-1"></i> No Access</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
