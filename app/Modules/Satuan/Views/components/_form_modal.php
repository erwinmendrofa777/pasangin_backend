<!-- ===== FORM MODAL FOR ADD & EDIT ===== -->
<div class="modal fade" id="satuanModal" tabindex="-1" aria-labelledby="satuanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="satuanModalTitle">
                    <i class="fas fa-balance-scale me-2 text-primary"></i>Tambah Satuan Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <?php
            $oldId = old('id');
            $formAction = site_url('admin/satuan/store');
            if (!empty($oldId)) {
                $formAction = site_url('admin/satuan/update/' . $oldId);
            }
            ?>
            <form id="satuanForm" action="<?= $formAction ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="satuan_id" value="<?= esc($oldId) ?>">
                <div class="modal-body py-4">
                    <?php if (!empty($error) && old('nama_satuan') !== null): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-3" role="alert" style="border-radius: 8px; font-size: 0.82rem;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <?= esc($error) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mb-0">
                        <label for="nama_satuan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Nama Satuan <span class="text-danger">*</span></label>
                        <?php 
                        $hasFieldError = !empty($validationErrors) && isset($validationErrors['nama_satuan']);
                        $fieldError = $hasFieldError ? $validationErrors['nama_satuan'] : '';
                        ?>
                        <input type="text" 
                               name="nama_satuan" 
                               id="nama_satuan" 
                               class="form-control <?= $hasFieldError ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: Kg, Pcs, Box, Liter..." 
                               required 
                               value="<?= esc(old('nama_satuan')) ?>"
                               style="border-radius: 8px; font-size: 0.85rem; height: 42px; border: 1.5px solid #e2e8f0; font-weight: 600;">
                        <?php if ($hasFieldError): ?>
                            <div class="invalid-feedback fw-bold">
                                <?= esc($fieldError) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-end gap-2 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius:8px; font-size: 0.85rem; height: 38px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius:8px; font-size: 0.85rem; height: 38px;">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
