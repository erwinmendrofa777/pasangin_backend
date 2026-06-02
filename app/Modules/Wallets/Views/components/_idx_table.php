<?php
/**
 * Component: _idx_table.php
 * Description: Table card untuk daftar saldo mitra tukang (Wallets Index)
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header: Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-wallet me-2"></i>Daftar Saldo
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, No HP...">
            </div>
            <?php if (can('wallet_withdraw_request')): ?>
                <a href="<?= base_url('admin/wallet/withdrawals') ?>"
                    class="btn btn-warning d-flex align-items-center justify-content-center text-nowrap mt-2 mt-md-0"
                    style="border-radius: 12px; font-size: 0.88rem; padding: 5px 16px; color: #fff;">
                    <i class="fas fa-file-invoice-dollar me-1"></i> Tarik Dana
                </a>
            <?php endif; ?>
        </div>
    </div>

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
                    <?php foreach ($tukang as $key => $t): ?>
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
