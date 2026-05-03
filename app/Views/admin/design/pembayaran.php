<div class="row g-4 mt-1">
    <!-- Kolom Form Tambah Tagihan -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-primary text-white" style="border-radius: 12px 12px 0 0; padding: 16px 20px;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Tagihan Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('admin/design/add-invoice/' . $request['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Target
                            Pembayaran</label>
                        <select name="description" class="form-select form-control"
                            style="border-radius:8px; padding:10px 14px;" required>
                            <option value="" disabled selected>-- Pilih Target Desain --</option>
                            <?php if (!empty($targets)):
                                foreach ($targets as $t): ?>
                                    <option value="<?= esc($t['task_name']) ?>"><?= esc($t['task_name']) ?></option>
                                <?php endforeach;
                            else: ?>
                                <option value="Tagihan Proyek">Tagihan Proyek</option>
                            <?php endif; ?>
                        </select>
                        <div class="form-text" style="font-size:0.75rem;"><i
                                class="fas fa-info-circle text-primary me-1"></i>Pilih berdasarkan target pekerjaan.
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Nominal
                            (Rp)</label>
                        <input type="number" name="amount" class="form-control"
                            style="border-radius:8px; padding:10px 14px;" placeholder="Contoh: 1500000" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Jatuh
                            Tempo</label>
                        <input type="date" name="due_date" class="form-control"
                            style="border-radius:8px; padding:10px 14px;" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold ladda-button" data-style="zoom-in"
                        style="border-radius:8px; padding:12px;">
                        <span class="ladda-label"><i class="fas fa-paper-plane me-2"></i>Kirim Tagihan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Riwayat Tagihan -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius: 12px; min-height: 510px;">
            <div class="card-header bg-primary border-bottom-0"
                style="border-radius: 12px 12px 0 0; padding: 20px 24px 10px;">
                <h6 class="mb-0 fw-bold text-white"><i class="fas fa-file-invoice-dollar me-2"></i>Daftar Tagihan
                    Pembayaran</h6>
            </div>
            <div class="card-body p-0" style="overflow-y: auto;">
                <!-- Desktop View (Tabel) -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <?php if (empty($invoices)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                            <i class="fas fa-receipt mb-3" style="font-size: 3rem; color: #adb5bd;"></i>
                                            <h6 class="text-muted fw-semibold">Belum ada tagihan</h6>
                                            <p class="text-muted" style="font-size:0.85rem;">Buat tagihan pembayaran melalui
                                                form di samping.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($invoices as $inv): ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    style="width: 42px; height: 42px; background: #e7f0ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #0d6efd; font-size: 1.2rem;">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.95rem;">
                                                        <?= esc($inv['description']) ?></h6>
                                                    <?php if (!empty($inv['due_date'])): ?>
                                                        <small class="text-muted">Jatuh Tempo:
                                                            <?= date('d M Y', strtotime($inv['due_date'])) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="fw-bold text-primary">Rp
                                                <?= number_format($inv['amount'], 0, ',', '.') ?></span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <?php
                                            $invStatus = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
                                            $isPaid = ($invStatus === 'PAID');
                                            ?>
                                            <?php if ($isPaid): ?>
                                                <span class="badge bg-success"
                                                    style="padding: 6px 12px; border-radius: 6px;"><?= $invStatus ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"
                                                    style="padding: 6px 12px; border-radius: 6px;"><?= $invStatus ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                                class="btn btn-sm btn-outline-danger ladda-button" data-style="zoom-in"
                                                style="border-radius:8px;"
                                                onclick="if(confirm('Hapus tagihan ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                data-bs-toggle="tooltip" title="Hapus Tagihan">
                                                <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View (Card List) -->
                <div class="d-block d-md-none p-3 bg-light">
                    <?php if (empty($invoices)): ?>
                        <div class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                <i class="fas fa-receipt mb-3" style="font-size: 3rem; color: #adb5bd;"></i>
                                <h6 class="text-muted fw-semibold">Belum ada tagihan</h6>
                                <p class="text-muted" style="font-size:0.85rem;">Buat tagihan pembayaran melalui form.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($invoices as $inv): ?>
                                <?php
                                $invStatus = $inv['status'] ?? $inv['payment_status'] ?? 'UNPAID';
                                $isPaid = ($invStatus === 'PAID');
                                $badgeCls = $isPaid ? 'bg-success' : 'bg-danger';
                                ?>
                                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width: 42px; height: 42px; background: #e7f0ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #0d6efd; font-size: 1.2rem; flex-shrink: 0;">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.95rem;">
                                                        <?= esc($inv['description']) ?>
                                                    </h6>
                                                    <span class="badge <?= $badgeCls ?>" style="padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;"><?= $invStatus ?></span>
                                                </div>
                                            </div>
                                            <a href="<?= base_url('admin/design/delete-invoice/' . $inv['id']) ?>"
                                                class="btn btn-sm btn-outline-danger" style="border-radius:8px;"
                                                onclick="if(confirm('Hapus tagihan ini?')) { return true; } return false;">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3" style="border-top: 1px dashed #dee2e6;">
                                            <div>
                                                <small class="text-muted d-block" style="font-size:0.75rem;">Jatuh Tempo</small>
                                                <span class="fw-semibold text-dark" style="font-size:0.85rem;"><?= !empty($inv['due_date']) ? date('d M Y', strtotime($inv['due_date'])) : '-' ?></span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block" style="font-size:0.75rem;">Nominal Tagihan</small>
                                                <span class="fw-bold text-primary" style="font-size:1rem;">Rp <?= number_format($inv['amount'], 0, ',', '.') ?></span>
                                            </div>
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