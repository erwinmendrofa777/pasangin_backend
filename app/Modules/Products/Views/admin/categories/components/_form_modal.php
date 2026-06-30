<!-- ===== FORM MODAL FOR ADD & EDIT ===== -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark" id="categoryModalTitle">
                    <i class="fas fa-tags me-2 text-primary"></i>Tambah Kategori Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <?php
            $oldId = old('id');
            $formAction = site_url('admin/product-categories/store');
            if (!empty($oldId)) {
                $formAction = site_url('admin/product-categories/update/' . $oldId);
            }
            ?>
            <form id="categoryForm" action="<?= $formAction ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="category_id" value="<?= esc($oldId) ?>">
                <div class="modal-body py-4">
                    <?php if (!empty($error) && old('name') !== null): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-3" role="alert" style="border-radius: 8px; font-size: 0.82rem;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <?= esc($error) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mb-0">
                        <label for="name" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Nama Kategori <span class="text-danger">*</span></label>
                        <?php 
                        $hasFieldError = !empty($validationErrors) && isset($validationErrors['name']);
                        $fieldError = $hasFieldError ? $validationErrors['name'] : '';
                        ?>
                        <input type="text" 
                                name="name" 
                                id="name" 
                                class="form-control <?= $hasFieldError ? 'is-invalid' : '' ?>" 
                                placeholder="Contoh: Semen, Bahan Kayu, Besi & Baja..." 
                                required 
                                value="<?= esc(old('name')) ?>"
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
