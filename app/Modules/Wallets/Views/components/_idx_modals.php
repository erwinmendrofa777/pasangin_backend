<?php
/**
 * Component: _idx_modals.php
 * Description: Modal kelola saldo per mitra tukang (Wallets Index)
 * Pattern: Composite Pattern - Leaf Component
 * Note: Diletakkan di luar table card untuk menghindari bug DOM HTML
 */
?>

<!-- Modals diletakkan di luar table untuk menghindari bug DOM HTML -->
<?php foreach (is_array($tukang) ? $tukang : [] as $t): ?>
    <div class="modal fade" id="modalSaldo<?= $t['id'] ?>" tabindex="-1" aria-labelledby="modalSaldoLabel<?= $t['id'] ?>"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('admin/wallet/update-balance') ?>" method="post" class="w-100">
                <div class="modal-content"
                    style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="modal-header"
                        style="background: #f8f9fa; border-bottom: 1px solid #e9ecef; border-radius: 16px 16px 0 0; padding: 16px 20px;">
                        <h6 class="modal-title fw-bold text-primary mb-0" id="modalSaldoLabel<?= $t['id'] ?>">
                            <i class="fas fa-wallet me-2"></i>Kelola Saldo: <?= esc($t['name']) ?>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="tukang_id" value="<?= $t['id'] ?>">

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Jenis
                                Transaksi</label>
                            <select name="type" class="form-select"
                                style="border-radius: 10px; border: 1.5px solid #dee2e6;">
                                <option value="income">Tambah Saldo (Upah/Bonus)</option>
                                <option value="withdraw">Potong Saldo (Denda/Admin)</option>
                            </select>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Nominal
                                (Rp)</label>
                            <input type="number" name="amount" class="form-control"
                                style="border-radius: 10px; border: 1.5px solid #dee2e6;" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Keterangan</label>
                            <textarea name="description" class="form-control" rows="3"
                                style="border-radius: 10px; border: 1.5px solid #dee2e6;"
                                placeholder="Contoh: Upah Proyek ID #12"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 16px 20px;">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold ladda-button" data-style="zoom-in"
                            style="border-radius: 10px;">
                            <span class="ladda-label">Simpan Transaksi</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>
