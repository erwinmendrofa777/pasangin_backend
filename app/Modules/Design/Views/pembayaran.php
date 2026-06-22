<?php /* =============================================
TAGIHAN PEMBAYARAN — v5 (Construction-style layout)
============================================= */ ?>

<?php
$linkedInvoices  = array_filter($invoices ?? [], fn($inv) => !empty($inv['design_target_id']));
$manualInvoices  = array_filter($invoices ?? [], fn($inv) =>  empty($inv['design_target_id']));
$invoiceByTarget = [];
foreach ($linkedInvoices as $inv) {
    $invoiceByTarget[(int) $inv['design_target_id']] = $inv;
}

// ── Hitung statistik ──
$totalTagihan  = count($invoices ?? []);
$tagiBayar     = 0; // jumlah yang sudah PAID
$totalNominal  = 0; // sum semua nominal (yang sudah diisi)
$totalTerbayar = 0; // sum nominal PAID

foreach ($invoices ?? [] as $inv) {
    $amt = (float) ($inv['amount'] ?? 0);
    if ($inv['amount'] !== null) $totalNominal += $amt;

    $st = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
    if ($st === 'PAID') {
        $tagiBayar++;
        $totalTerbayar += $amt;
    }
}

$tagihanAktif = $totalTagihan - $tagiBayar;
$pctBayar     = $totalNominal > 0
    ? round(($totalTerbayar / $totalNominal) * 100, 1)
    : 0;
?>

<div class="invoice-wrap">

    <!-- ══════════════════════════════════════
         HERO CARD
    ═══════════════════════════════════════════ -->
    <div class="inv-hero mb-4">
        <div class="row align-items-center g-3">

            <!-- Kiri: total nominal + progress -->
            <div class="col-12 col-md-5">
                <div class="inv-hero-label mb-1">Total Nilai Tagihan</div>
                <div class="inv-hero-amount">
                    <?= $totalNominal > 0 ? 'Rp ' . number_format($totalNominal, 0, ',', '.') : 'Rp —' ?>
                </div>
                <p class="inv-hero-sub mt-1">
                    <?= count($targets ?? []) ?> target desain
                    <?php if (count($manualInvoices) > 0): ?>
                        &bull; <?= count($manualInvoices) ?> tagihan tambahan
                    <?php endif; ?>
                </p>
                <div class="inv-hero-progress-bar mt-3">
                    <div class="bar-fill" style="width:<?= $pctBayar ?>%"></div>
                </div>
                <div class="inv-progress-pct d-flex justify-content-between mt-2">
                    <span><?= $pctBayar ?>% terbayar</span>
                    <span style="opacity: 0.8; font-weight: 500; font-size: 0.7rem;">PROGRESS PEMBAYARAN</span>
                </div>
            </div>

            <!-- Kanan: stats -->
            <div class="col-12 col-md-7">
                <div class="d-flex flex-wrap align-items-stretch justify-content-md-end gap-2">
                    <!-- Stats -->
                    <div class="inv-stat-card">
                        <div class="sc-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="sc-info">
                            <span class="sc-label">Terbayar</span>
                            <span class="sc-val">
                                <?= $totalTerbayar > 0 ? 'Rp ' . number_format($totalTerbayar, 0, ',', '.') : 'Rp 0' ?>
                            </span>
                        </div>
                    </div>
                    <div class="inv-stat-card">
                        <div class="sc-icon"><i class="fas fa-clock"></i></div>
                        <div class="sc-info">
                            <span class="sc-label">Tagihan Aktif</span>
                            <span class="sc-val"><?= $tagihanAktif ?></span>
                        </div>
                    </div>
                    <div class="inv-stat-card">
                        <div class="sc-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="sc-info">
                            <span class="sc-label">Total Tagihan</span>
                            <span class="sc-val"><?= $totalTagihan ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ══════════════════════════════════════
         SECTION: TAGIHAN PER TARGET & TAMBAHAN
    ═══════════════════════════════════════════ -->
    <div class="inv-section-head mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-bullseye"></i>
            <span>Tagihan Per Target Desain</span>
        </div>
        <button type="button" class="inv-btn-add"
            data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
            <i class="fas fa-plus me-1"></i>Buat Tagihan
        </button>
    </div>

    <?php if (empty($targets) && empty($manualInvoices)): ?>
        <div class="inv-empty mb-4">
            <div class="inv-empty-icon"><i class="fas fa-bullseye"></i></div>
            <h6 class="fw-bold text-muted mb-1">Belum ada tagihan</h6>
            <p class="text-muted mb-0" style="font-size:.82rem;">
                Buat target di tab <strong>Target</strong> agar tagihan otomatis terbuat, atau klik <strong>Buat Tagihan</strong> untuk membuat tagihan manual.
            </p>
        </div>
    <?php else: ?>

        <?php if (!empty($targets)): ?>
            <?php foreach ($targets as $i => $t):
                $tid            = (int) $t['id'];
                $inv            = $invoiceByTarget[$tid] ?? null;
                $hasInvoice     = ($inv !== null);
                $originalAmount = $hasInvoice ? (float) ($inv['amount'] ?? 0) : 0;
                $discount       = $hasInvoice ? (int)   ($inv['discount_nominal'] ?? 0) : 0;
                $finalAmount    = max(0, $originalAmount - $discount);
                $invStatus      = $hasInvoice ? ($inv['status'] ?? $inv['payment_status'] ?? 'UNPAID') : null;
                $isPaid         = ($invStatus === 'PAID');
                $needsSetup     = $hasInvoice && ($inv['amount'] === null || $inv['due_date'] === null);
            ?>
            <div class="inv-target-row <?= $needsSetup ? 'needs-setup' : '' ?>">
                <div class="inv-target-header">

                    <!-- Nomor -->
                    <div class="inv-target-num <?= $isPaid ? 'done' : ($needsSetup ? 'warn' : '') ?>">
                        <?php if ($isPaid): ?>
                            <i class="fas fa-check" style="font-size:.7rem;"></i>
                        <?php else: ?>
                            <?= ($i + 1) ?>
                        <?php endif; ?>
                    </div>

                    <!-- Nama + badge status target -->
                    <div class="flex-grow-1">
                        <div class="inv-target-name"><?= esc($t['task_name']) ?></div>
                        <div class="d-flex gap-1 mt-1 flex-wrap">
                            <?php if ($t['status'] === 'DONE'): ?>
                                <span class="badge-target-status" style="background:#d1fae5;color:#065f46;">✓ Selesai</span>
                            <?php elseif ($t['status'] === 'ON PROGRESS'): ?>
                                <span class="badge-target-status" style="background:#dbeafe;color:#1d4ed8;">⚡ Dikerjakan</span>
                            <?php else: ?>
                                <span class="badge-target-status" style="background:#f1f5f9;color:#64748b;">○ Menunggu</span>
                            <?php endif; ?>
                            <?php if ($needsSetup): ?>
                                <span class="badge-needs-setup"><i class="fas fa-pen me-1"></i>Perlu diisi</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Jatuh tempo -->
                    <div class="text-center d-none d-md-block" style="min-width:110px;">
                        <?php if ($hasInvoice && !empty($inv['due_date'])): ?>
                            <div style="font-size:.65rem;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Jatuh Tempo</div>
                            <div style="font-size:.8rem;font-weight:600;color:#475569;">
                                <?= date('d M Y', strtotime($inv['due_date'])) ?>
                            </div>
                        <?php elseif ($hasInvoice): ?>
                            <span style="font-size:.78rem;color:#f59e0b;font-weight:600;">—</span>
                        <?php endif; ?>
                    </div>

                    <!-- Nominal -->
                    <div class="text-end" style="min-width:140px;">
                        <?php if ($hasInvoice && $inv['amount'] !== null): ?>
                            <?php if ($discount > 0): ?>
                                <div class="inv-amount-old">Rp <?= number_format($originalAmount, 0, ',', '.') ?></div>
                            <?php endif; ?>
                            <div class="inv-target-amount">
                                Rp <?= number_format($finalAmount, 0, ',', '.') ?>
                            </div>
                            <?php if ($discount > 0): ?>
                                <span class="inv-discount-tag"><i class="fas fa-tag me-1"></i>-Rp <?= number_format($discount, 0, ',', '.') ?></span>
                            <?php endif; ?>
                        <?php elseif ($hasInvoice): ?>
                            <div class="inv-target-amount null-amount">
                                <i class="fas fa-pen me-1"></i>Belum diisi
                            </div>
                        <?php else: ?>
                            <span style="color:#cbd5e1;font-size:.82rem;">—</span>
                        <?php endif; ?>
                    </div>

                    <!-- Status bayar -->
                    <div class="text-center d-none d-md-block" style="min-width:90px;">
                        <?php if ($hasInvoice): ?>
                            <?php if ($isPaid): ?>
                                <span class="badge-paid"><i class="fas fa-check-circle me-1"></i>PAID</span>
                            <?php elseif ($invStatus === 'PENDING'): ?>
                                <span class="badge-pending-pay"><i class="fas fa-spinner me-1"></i>PENDING</span>
                            <?php else: ?>
                                <span class="badge-unpaid"><i class="fas fa-clock me-1"></i>UNPAID</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color:#cbd5e1;">—</span>
                        <?php endif; ?>
                    </div>

                    <!-- Aksi -->
                    <div class="d-flex gap-1 ms-2">
                        <?php if ($hasInvoice && !$isPaid): ?>
                            <button type="button" class="inv-edit-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUpdateInvoice<?= $inv['id'] ?>"
                                title="Atur Nominal & Jatuh Tempo">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                class="btn inv-del-btn ladda-button" data-style="zoom-in"
                                onclick="if(confirm('Hapus tagihan target ini?')){ Ladda.create(this).start(); return true; } return false;">
                                <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                            </a>
                        <?php elseif ($hasInvoice && $isPaid): ?>
                            <span style="font-size:.75rem;color:#16a34a;font-weight:600;white-space:nowrap;">
                                <i class="fas fa-lock me-1"></i>Lunas
                            </span>
                        <?php endif; ?>
                    </div>

                </div><!-- /inv-target-header -->
            </div><!-- /inv-target-row -->

            <?php if ($hasInvoice && !$isPaid): ?>
            <!-- Modal Update Invoice -->
            <div class="modal fade" id="modalUpdateInvoice<?= $inv['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content inv-modal-content">
                        <div class="inv-modal-header">
                            <h6 class="modal-title text-white mb-0 fw-bold">
                                <i class="fas fa-file-invoice-dollar me-2 opacity-75"></i>
                                Atur Tagihan &mdash; <?= esc($t['task_name']) ?>
                            </h6>
                            <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('admin/design/update-invoice/' . $inv['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="modal-body p-4">
                                <div class="d-flex gap-2 mb-4 p-3" style="background:#fff1f1;border-radius:12px;border-left:3px solid var(--palette-primary);">
                                    <i class="fas fa-info-circle flex-shrink-0 mt-1" style="color:var(--palette-primary);"></i>
                                    <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.6;">
                                        Atur nominal tagihan dan tanggal jatuh tempo untuk target <strong><?= esc($t['task_name']) ?></strong>.
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="inv-label">Nominal (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border:1.5px solid #e2e8f0;border-right:none;border-radius:10px 0 0 10px;background:#f8fafc;color:#64748b;font-size:.85rem;font-weight:700;">Rp</span>
                                        <input type="number" name="amount" class="form-control inv-control"
                                            style="border-left:none;border-radius:0 10px 10px 0;"
                                            placeholder="Contoh: 1500000"
                                            value="<?= $inv['amount'] !== null ? (int) $inv['amount'] : '' ?>"
                                            min="1" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="inv-label">Jatuh Tempo</label>
                                    <input type="date" name="due_date" class="form-control inv-control"
                                        value="<?= esc($inv['due_date'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn inv-submit-btn px-4 ladda-button" data-style="zoom-in">
                                    <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($manualInvoices)): ?>
            <?php foreach ($manualInvoices as $inv):
                $invStatus      = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
                $isPaid         = ($invStatus === 'PAID');
                $originalAmount = (float) ($inv['amount'] ?? 0);
                $discount       = (int)   ($inv['discount_nominal'] ?? 0);
                $finalAmount    = max(0, $originalAmount - $discount);
                $isRevision     = strpos($inv['description'] ?? '', 'Tambahan Kuota Revisi') !== false;
            ?>
            <div class="inv-target-row">
                <div class="inv-target-header">

                    <!-- Nomor / Icon -->
                    <div class="inv-target-num <?= $isPaid ? 'done' : '' ?>" 
                         style="<?= $isRevision ? 'background: linear-gradient(135deg, #f59e0b, #d97706);' : 'background: linear-gradient(135deg, #64748b, #475569);' ?>">
                        <?php if ($isPaid): ?>
                            <i class="fas fa-check" style="font-size:.7rem;"></i>
                        <?php else: ?>
                            <i class="fas <?= $isRevision ? 'fa-redo-alt' : 'fa-receipt' ?>" style="font-size:.75rem;"></i>
                        <?php endif; ?>
                    </div>

                    <!-- Keterangan + badge status manual -->
                    <div class="flex-grow-1">
                        <div class="inv-target-name"><?= esc($inv['description']) ?></div>
                        <div class="d-flex gap-1 mt-1 flex-wrap">
                            <?php if ($isRevision): ?>
                                <span class="badge-target-status" style="background:#fef3c7;color:#92400e;">
                                    <i class="fas fa-redo-alt me-1" style="font-size:.65rem;"></i>Kuota Revisi
                                </span>
                            <?php else: ?>
                                <span class="badge-target-status" style="background:#e0f2fe;color:#0369a1;">
                                    <i class="fas fa-info-circle me-1" style="font-size:.65rem;"></i>Tagihan Tambahan
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Jatuh tempo -->
                    <div class="text-center d-none d-md-block" style="min-width:110px;">
                        <?php if (!empty($inv['due_date'])): ?>
                            <div style="font-size:.65rem;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Jatuh Tempo</div>
                            <div style="font-size:.8rem;font-weight:600;color:#475569;">
                                <?= date('d M Y', strtotime($inv['due_date'])) ?>
                            </div>
                        <?php else: ?>
                            <span style="font-size:.78rem;color:#cbd5e1;font-weight:600;">—</span>
                        <?php endif; ?>
                    </div>

                    <!-- Nominal -->
                    <div class="text-end" style="min-width:140px;">
                        <?php if ($discount > 0): ?>
                            <div class="inv-amount-old">Rp <?= number_format($originalAmount, 0, ',', '.') ?></div>
                        <?php endif; ?>
                        <div class="inv-target-amount">
                            Rp <?= number_format($finalAmount, 0, ',', '.') ?>
                        </div>
                        <?php if ($discount > 0): ?>
                            <span class="inv-discount-tag"><i class="fas fa-tag me-1"></i>-Rp <?= number_format($discount, 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Status bayar -->
                    <div class="text-center d-none d-md-block" style="min-width:90px;">
                        <?php if ($isPaid): ?>
                            <span class="badge-paid"><i class="fas fa-check-circle me-1"></i>PAID</span>
                        <?php elseif ($invStatus === 'PENDING'): ?>
                            <span class="badge-pending-pay"><i class="fas fa-spinner me-1"></i>PENDING</span>
                        <?php else: ?>
                            <span class="badge-unpaid"><i class="fas fa-clock me-1"></i>UNPAID</span>
                        <?php endif; ?>
                    </div>

                    <!-- Aksi -->
                    <div class="d-flex gap-1 ms-2">
                        <?php if (!$isPaid): ?>
                            <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                class="btn inv-del-btn ladda-button" data-style="zoom-in"
                                onclick="if(confirm('Hapus tagihan tambahan ini?')){ Ladda.create(this).start(); return true; } return false;">
                                <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                            </a>
                        <?php else: ?>
                            <span style="font-size:.75rem;color:#16a34a;font-weight:600;white-space:nowrap;">
                                <i class="fas fa-lock me-1"></i>Lunas
                            </span>
                        <?php endif; ?>
                    </div>

                </div><!-- /inv-target-header -->
            </div><!-- /inv-target-row -->
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</div><!-- /invoice-wrap -->


<!-- ════════════════════════════════════════════
     MODAL: Tambah Tagihan Manual
════════════════════════════════════════════════ -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content inv-modal-content">
            <div class="inv-modal-header">
                <h6 class="modal-title text-white mb-0 fw-bold">
                    <i class="fas fa-plus-circle me-2 opacity-75"></i>Tambah Tagihan Manual
                </h6>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/design/add-invoice/' . $request['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="d-flex gap-2 mb-4 p-3" style="background:#fff1f1;border-radius:12px;border-left:3px solid var(--palette-primary);">
                        <i class="fas fa-info-circle flex-shrink-0 mt-1" style="color:var(--palette-primary);"></i>
                        <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.6;">
                            Untuk tagihan di luar target desain — seperti biaya konsultasi, revisi tambahan, atau biaya lainnya.
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="inv-label">Deskripsi Tagihan</label>
                        <input type="text" name="description" class="form-control inv-control"
                            placeholder="Contoh: Biaya Konsultasi Tambahan" required>
                    </div>
                    <div class="mb-3">
                        <label class="inv-label">Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border:1.5px solid #e2e8f0;border-right:none;border-radius:10px 0 0 10px;background:#f8fafc;color:#64748b;font-size:.85rem;font-weight:700;">Rp</span>
                            <input type="number" name="amount" class="form-control inv-control"
                                style="border-left:none;border-radius:0 10px 10px 0;"
                                placeholder="Contoh: 500000" min="1" required>
                        </div>
                    </div>
                    <div>
                        <label class="inv-label">Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-control inv-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn inv-submit-btn px-4 ladda-button" data-style="zoom-in">
                        <span class="ladda-label"><i class="fas fa-paper-plane me-2"></i>Kirim Tagihan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>