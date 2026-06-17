<?php
/**
 * Component: _kpi_cards.php
 * Description: KPI Balance Cards (Saldo Platform & Midtrans) untuk halaman Admin Balance
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== KPI BALANCE CARDS ===== -->
<div class="row g-4 mb-4">

    <!-- Saldo Platform Lokal -->
    <div class="col-12 col-lg-6">
        <div class="fintech-card fintech-card-green h-100">
            <div class="card-meta">
                <span class="card-title-text">Saldo Platform (Internal)</span>
                <div class="card-icon-wrap">
                    <i class="fas fa-university"></i>
                </div>
            </div>
            <div class="card-amount">
                Rp <?= number_format($localBalance, 0, ',', '.') ?>
            </div>
            <!-- Manual Action Buttons -->
            <?php if (can('admin_balance_manage')): ?>
                <div class="fintech-btn-group">
                    <button type="button" class="fintech-btn fintech-btn-success" data-bs-toggle="modal" data-bs-target="#modalDeposit">
                        <i class="fas fa-plus-circle"></i> Deposit Manual
                    </button>
                    <button type="button" class="fintech-btn fintech-btn-danger" data-bs-toggle="modal" data-bs-target="#modalWithdraw">
                        <i class="fas fa-minus-circle"></i> Tarik Saldo
                    </button>
                    <button type="button" id="btn-sync-midtrans" class="fintech-btn fintech-btn-secondary">
                        <i class="fas fa-sync"></i> Sinkronkan Saldo
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Saldo Live Midtrans -->
    <div class="col-12 col-lg-6">
        <div class="fintech-card fintech-card-primary h-100">
            <div class="card-meta">
                <span class="card-title-text">Saldo Penampungan Midtrans (Live Payin)</span>
                <div class="card-icon-wrap">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="card-amount">
                Rp <?= number_format($midtransBalance, 0, ',', '.') ?>
            </div>
            <div>
                <?php if ($midtransError): ?>
                    <span class="status-chip status-chip-danger" title="<?= esc($midtransMessage) ?>">
                        <span class="dot"></span>
                        <span>Midtrans Terputus</span>
                    </span>
                <?php else: ?>
                    <span class="status-chip status-chip-success">
                        <span class="dot"></span>
                        <span>Koneksi Midtrans Terhubung</span>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
