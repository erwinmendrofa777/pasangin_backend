<?php
/**
 * Component: _header_card.php
 * Description: Unified header card — judul, search, dan 2 saldo dalam satu card premium
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== UNIFIED HEADER + BALANCE CARD ===== -->
<div class="card header-card mb-4">

    <!-- Top Row: Icon + Title + Search -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center px-4 pt-4 pb-3 header-top">
        <div class="d-flex align-items-center mb-3 mb-lg-0">
            <div class="balance-icon me-3" style="width:48px; height:48px; border-radius:14px; background:rgba(255,92,92,0.1); color:var(--palette-primary); flex-shrink:0;">
                <i class="fas fa-university" style="font-size:1.25rem;"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold text-dark" style="letter-spacing:-0.3px;">Saldo Admin &amp; Platform</h5>
                <p class="text-muted mb-0 small">Monitor saldo platform internal, cek saldo Midtrans live, dan kelola mutasi keuangan.</p>
            </div>
        </div>
        <div class="search-wrapper" style="width:240px; max-width:100%;">
            <input type="text" class="search-input w-100" id="searchInput" placeholder="Cari riwayat mutasi..." style="padding-left:38px !important;">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>

    <!-- Bottom Row: 2 Balance Sections -->
    <div class="row g-0">

        <!-- Saldo Platform (Internal) -->
        <div class="col-12 col-lg-6 balance-section balance-section-green">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="balance-label">Saldo Platform (Internal)</span>
                <div class="balance-icon" style="background:#ecfdf5; color:#10B981;">
                    <i class="fas fa-university"></i>
                </div>
            </div>
            <div class="balance-amount">
                Rp <?= number_format($localBalance, 0, ',', '.') ?>
            </div>
            <?php if (can('admin_balance_manage')): ?>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="fintech-btn fintech-btn-success" data-bs-toggle="modal" data-bs-target="#modalDeposit">
                        <i class="fas fa-plus-circle"></i> Deposit
                    </button>
                    <button type="button" class="fintech-btn fintech-btn-danger" data-bs-toggle="modal" data-bs-target="#modalWithdraw">
                        <i class="fas fa-minus-circle"></i> Tarik Saldo
                    </button>
                    <button type="button" id="btn-sync-midtrans" class="fintech-btn fintech-btn-secondary">
                        <i class="fas fa-sync"></i> Sinkronkan
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Saldo Midtrans Live -->
        <div class="col-12 col-lg-6 balance-section balance-section-primary">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="balance-label">Saldo Midtrans (Live Payin)</span>
                <div class="balance-icon" style="background:#fff5f5; color:var(--palette-primary);">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="balance-amount">
                Rp <?= number_format($midtransBalance, 0, ',', '.') ?>
            </div>
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
