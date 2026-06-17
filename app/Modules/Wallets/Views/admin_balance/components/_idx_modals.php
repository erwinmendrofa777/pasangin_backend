<?php
/**
 * Component: _idx_modals.php
 * Description: Modal Deposit dan Withdraw untuk halaman Saldo Admin & Platform
 * Pattern: Composite Pattern - Leaf Component
 */
?>

<!-- ===== MODALS FOR DEPOSIT & WITHDRAWAL ===== -->
<?php if (can('admin_balance_manage')): ?>

    <!-- Modal Deposit -->
    <div class="modal fade fintech-modal" id="modalDeposit" tabindex="-1" aria-labelledby="modalDepositLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('admin/admin-balance/deposit') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDepositLabel">
                            <i class="fas fa-plus-circle me-2 text-success"></i> Deposit Manual Saldo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-custom">
                            <label for="deposit_amount">Nominal Deposit (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <span class="prefix">Rp</span>
                                <input type="number" name="amount" id="deposit_amount" class="form-control-custom"
                                    placeholder="Contoh: 500000" min="1" required>
                            </div>
                        </div>
                        <div class="form-group-custom">
                            <label for="deposit_desc">Keterangan / Catatan <span class="text-danger">*</span></label>
                            <textarea name="description" id="deposit_desc" class="form-control-custom" rows="3"
                                placeholder="Contoh: Suntikan modal awal platform" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="fintech-btn fintech-btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="fintech-btn fintech-btn-success">Simpan Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Withdraw -->
    <div class="modal fade fintech-modal" id="modalWithdraw" tabindex="-1" aria-labelledby="modalWithdrawLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('admin/admin-balance/withdraw') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalWithdrawLabel">
                            <i class="fas fa-minus-circle me-2 text-primary"></i> Tarik Dana Platform
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-custom">
                            <label for="withdraw_amount">Nominal Penarikan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <span class="prefix">Rp</span>
                                <input type="number" name="amount" id="withdraw_amount" class="form-control-custom"
                                    placeholder="Contoh: 200000" min="1" required>
                            </div>
                        </div>
                        <div class="form-group-custom">
                            <label for="withdraw_desc">Keterangan / Catatan <span class="text-danger">*</span></label>
                            <textarea name="description" id="withdraw_desc" class="form-control-custom" rows="3"
                                placeholder="Contoh: Penarikan profit platform oleh owner" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="fintech-btn fintech-btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="fintech-btn fintech-btn-danger">Simpan Penarikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php endif; ?>
