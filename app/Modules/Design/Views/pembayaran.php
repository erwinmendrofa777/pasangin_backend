<?php /* =============================================
TAGIHAN PEMBAYARAN — Beautified Bootstrap 5
============================================= */ ?>



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