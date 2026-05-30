<?php /* =============================================
TAGIHAN PEMBAYARAN — Beautified Bootstrap 5
============================================= */ ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .invoice-wrap,
    .invoice-wrap *:not(i):not([class*="fa"]) {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Form Card ── */
    .inv-form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 24px rgba(99, 102, 241, .08);
        overflow: hidden;
    }

    .inv-form-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 60%, #818cf8 100%);
        padding: 20px 24px;
        position: relative;
        overflow: hidden;
    }

    .inv-form-header::before {
        content: '';
        position: absolute;
        right: -24px;
        top: -24px;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .10);
    }

    .inv-form-header::after {
        content: '';
        position: absolute;
        right: 20px;
        bottom: -32px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
    }

    .inv-form-header h6 {
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .3px;
    }

    /* ── Form controls ── */
    .inv-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .inv-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .875rem;
        color: #111827;
        transition: border-color .2s, box-shadow .2s;
        background: #fafafa;
    }

    .inv-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        background: #fff;
        outline: none;
    }

    .inv-control option {
        background: #fff;
    }

    .inv-submit-btn {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: .875rem;
        letter-spacing: .3px;
        color: #fff;
        transition: transform .15s, box-shadow .15s, opacity .15s;
        box-shadow: 0 4px 14px rgba(99, 102, 241, .35);
    }

    .inv-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, .45);
        opacity: .95;
    }

    .inv-submit-btn:active {
        transform: translateY(0);
    }

    /* ── List Card ── */
    .inv-list-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 24px rgba(99, 102, 241, .08);
        overflow: hidden;
        min-height: 510px;
    }

    .inv-list-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 60%, #818cf8 100%);
        padding: 20px 24px 14px;
        position: relative;
        overflow: hidden;
    }

    .inv-list-header::before {
        content: '';
        position: absolute;
        right: -16px;
        top: -32px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .inv-list-header h6 {
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .3px;
    }

    /* ── Table rows ── */
    .inv-table tbody tr {
        transition: background .15s;
        border-bottom: 1px solid #f3f4f6;
    }

    .inv-table tbody tr:hover {
        background: #f5f3ff;
    }

    .inv-table tbody td {
        vertical-align: middle;
    }

    /* ── Invoice icon ── */
    .inv-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* ── Status badges ── */
    .badge-paid {
        background: #d1fae5;
        color: #065f46;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .4px;
    }

    .badge-unpaid {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .4px;
    }

    /* ── Amount display ── */
    .inv-amount-final {
        font-size: .95rem;
        font-weight: 700;
        color: #4f46e5;
    }

    .inv-amount-old {
        font-size: .75rem;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .inv-discount-tag {
        font-size: .68rem;
        font-weight: 700;
        color: #059669;
        background: #d1fae5;
        border-radius: 6px;
        padding: 2px 7px;
        display: inline-block;
    }

    /* ── Delete btn ── */
    .inv-del-btn {
        border: 1.5px solid #fca5a5;
        color: #ef4444;
        background: transparent;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: .8rem;
        transition: background .15s, color .15s;
    }

    .inv-del-btn:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    /* ── Empty state ── */
    .inv-empty {
        padding: 64px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .inv-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #d1d5db;
        margin-bottom: 8px;
    }

    /* ── Mobile card ── */
    .inv-mobile-card {
        border: 1.5px solid #f3f4f6;
        border-radius: 14px;
        padding: 14px;
        background: #fff;
        transition: box-shadow .2s;
    }

    .inv-mobile-card:hover {
        box-shadow: 0 4px 16px rgba(99, 102, 241, .12);
    }

    .inv-divider {
        border-top: 1.5px dashed #e5e7eb;
        margin-top: 12px;
        padding-top: 12px;
    }

    /* ── Form helper text ── */
    .inv-help-text {
        font-size: .73rem;
        color: #9ca3af;
        margin-top: 5px;
    }
</style>

<div class="row g-4 mt-1 invoice-wrap">

    <!-- ═══════════════════════════════════
       KOLOM FORM TAMBAH TAGIHAN
  ══════════════════════════════════════ -->
    <div class="col-md-4">
        <div class="inv-form-card card">

            <div class="inv-form-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Tagihan Baru
                </h6>
            </div>

            <div class="card-body p-4">
                <form action="<?= base_url('admin/design/add-invoice/' . $request['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Target Pembayaran -->
                    <div class="mb-3">
                        <label class="inv-label">Target Pembayaran</label>
                        <select name="description" class="form-select inv-control" required>
                            <option value="" disabled selected>— Pilih target desain —</option>
                            <?php if (!empty($targets)):
                                foreach ($targets as $t): ?>
                                    <option value="<?= esc($t['task_name']) ?>"><?= esc($t['task_name']) ?></option>
                                <?php endforeach;
                            else: ?>
                                <option value="Tagihan Proyek">Tagihan Proyek</option>
                            <?php endif; ?>
                        </select>
                        <p class="inv-help-text">
                            <i class="fas fa-info-circle me-1 text-indigo-400"></i>Berdasarkan target pekerjaan
                        </p>
                    </div>

                    <!-- Nominal -->
                    <div class="mb-3">
                        <label class="inv-label">Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                style="border: 1.5px solid #e5e7eb; border-right: none; border-radius: 10px 0 0 10px; background: #f3f4f6; color: #6b7280; font-size: .85rem; font-weight: 600;">Rp</span>
                            <input type="number" name="amount" class="form-control inv-control"
                                style="border-left: none; border-radius: 0 10px 10px 0;" placeholder="Contoh: 1.500.000"
                                required>
                        </div>
                    </div>

                    <!-- Jatuh Tempo -->
                    <div class="mb-4">
                        <label class="inv-label">Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-control inv-control" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn inv-submit-btn w-100 ladda-button" data-style="zoom-in">
                        <span class="ladda-label">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Tagihan
                        </span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- ═══════════════════════════════════
       KOLOM RIWAYAT TAGIHAN
  ══════════════════════════════════════ -->
    <div class="col-md-8">
        <div class="inv-list-card card">

            <div class="inv-list-header">
                <h6 class="mb-0 text-white">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Daftar Tagihan Pembayaran
                </h6>
            </div>

            <div class="card-body p-0" style="overflow-y: auto;">

                <!-- ── Desktop View ── -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table inv-table mb-0">
                        <tbody>

                            <?php if (empty($invoices)): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="inv-empty">
                                            <div class="inv-empty-icon">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                            <h6 class="fw-bold text-muted mb-1">Belum ada tagihan</h6>
                                            <p class="text-muted mb-0" style="font-size:.83rem;">
                                                Buat tagihan pembayaran melalui form di samping.
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            <?php else: ?>
                                <?php foreach ($invoices as $inv):
                                    $invStatus = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
                                    $isPaid = ($invStatus === 'PAID');
                                    $originalAmount = (int) $inv['amount'];
                                    $discount = (int) ($inv['discount_nominal'] ?? 0);
                                    $finalAmount = max(0, $originalAmount - $discount);
                                    ?>
                                    <tr>
                                        <!-- Deskripsi + Due Date -->
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="inv-icon-wrap">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size:.9rem; line-height:1.3;">
                                                        <?= esc($inv['description']) ?>
                                                    </div>
                                                    <?php if (!empty($inv['due_date'])): ?>
                                                        <div class="d-flex align-items-center gap-1 mt-1">
                                                            <i class="fas fa-calendar-alt"
                                                                style="font-size:.7rem; color:#9ca3af;"></i>
                                                            <small class="text-muted" style="font-size:.75rem;">
                                                                <?= date('d M Y', strtotime($inv['due_date'])) ?>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Nominal -->
                                        <td class="py-3 text-center" style="min-width: 130px;">
                                            <?php if ($discount > 0): ?>
                                                <div class="inv-amount-old">Rp <?= number_format($originalAmount, 0, ',', '.') ?>
                                                </div>
                                                <div class="inv-amount-final">Rp <?= number_format($finalAmount, 0, ',', '.') ?>
                                                </div>
                                                <div class="mt-1">
                                                    <span class="inv-discount-tag">
                                                        <i class="fas fa-tag me-1"></i>Hemat Rp
                                                        <?= number_format($discount, 0, ',', '.') ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <div class="inv-amount-final">Rp <?= number_format($originalAmount, 0, ',', '.') ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-3 text-center" style="min-width: 90px;">
                                            <?php if ($isPaid): ?>
                                                <span class="badge-paid">
                                                    <i class="fas fa-check-circle me-1"></i><?= $invStatus ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-unpaid">
                                                    <i class="fas fa-clock me-1"></i><?= $invStatus ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="pe-4 py-3 text-end" style="min-width: 60px;">
                                            <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                                class="btn inv-del-btn ladda-button" data-style="zoom-in"
                                                data-bs-toggle="tooltip" title="Hapus Tagihan"
                                                onclick="if(confirm('Hapus tagihan ini?')){ Ladda.create(this).start(); return true; } return false;">
                                                <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <!-- ── Mobile View ── -->
                <div class="d-block d-md-none p-3" style="background: #f9fafb;">
                    <?php if (empty($invoices)): ?>
                        <div class="inv-empty">
                            <div class="inv-empty-icon"><i class="fas fa-receipt"></i></div>
                            <h6 class="fw-bold text-muted mb-1">Belum ada tagihan</h6>
                            <p class="text-muted mb-0" style="font-size:.83rem;">Buat tagihan melalui form.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($invoices as $inv):
                                $invStatus = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
                                $isPaid = ($invStatus === 'PAID');
                                $originalAmount = (int) $inv['amount'];
                                $discount = (int) ($inv['discount_nominal'] ?? 0);
                                $finalAmount = max(0, $originalAmount - $discount);
                                ?>
                                <div class="inv-mobile-card">

                                    <!-- Header row -->
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="inv-icon-wrap">
                                                <i class="fas fa-file-invoice"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size:.9rem; line-height:1.3;">
                                                    <?= esc($inv['description']) ?>
                                                </div>
                                                <div class="mt-1">
                                                    <?php if ($isPaid): ?>
                                                        <span class="badge-paid">
                                                            <i class="fas fa-check-circle me-1"></i><?= $invStatus ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge-unpaid">
                                                            <i class="fas fa-clock me-1"></i><?= $invStatus ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                            class="btn inv-del-btn"
                                            onclick="if(confirm('Hapus tagihan ini?')){ return true; } return false;">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>

                                    <!-- Footer row -->
                                    <div class="d-flex align-items-center justify-content-between inv-divider">
                                        <div>
                                            <div class="inv-label mb-1">Jatuh Tempo</div>
                                            <div class="fw-semibold text-dark" style="font-size:.85rem;">
                                                <?= !empty($inv['due_date']) ? date('d M Y', strtotime($inv['due_date'])) : '—' ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="inv-label mb-1">Nominal</div>
                                            <?php if ($discount > 0): ?>
                                                <div class="inv-amount-old">Rp <?= number_format($originalAmount, 0, ',', '.') ?>
                                                </div>
                                                <div class="inv-amount-final">Rp <?= number_format($finalAmount, 0, ',', '.') ?>
                                                </div>
                                                <span class="inv-discount-tag mt-1 d-inline-block">
                                                    <i class="fas fa-tag me-1"></i>Disc Rp
                                                    <?= number_format($discount, 0, ',', '.') ?>
                                                </span>
                                            <?php else: ?>
                                                <div class="inv-amount-final">Rp <?= number_format($originalAmount, 0, ',', '.') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

</div>