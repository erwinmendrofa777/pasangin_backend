<form id="create-voucher-form" method="POST" action="<?= base_url('admin/vouchers/store') ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-8">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Tambah Voucher Baru
                            </h5>
                            <p class="text-white-50 small mb-0">Lengkapi formulir untuk membuat promo baru</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2"
                            style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-plus me-1 opacity-75"></i>VOUCHER BARU
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Image Preview -->
                    <div class="avatar-wrapper">
                        <div class="voucher-placeholder" id="img-preview-placeholder">
                            <i class="fas fa-ticket-alt fa-2x mb-1"></i>
                            <span style="font-size: 0.7rem; font-weight: 600; opacity: 0.8;">PRATINJAU GAMBAR</span>
                        </div>
                        <img src="" alt="Preview" class="voucher-preview-img d-none" id="img-preview">

                        <!-- Upload Button Overlay -->
                        <label for="image" class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                            style="bottom: 5px; right: -5px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff;">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="image" name="image" class="d-none" accept="image/*"
                            onchange="previewImage()" required>
                    </div>

                    <!-- ===== Formulir Voucher ===== -->
                    <div class="card section-card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-edit me-2"></i>Detail Informasi Voucher</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Kode Voucher -->
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Kode Voucher <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="<?= old('code') ?>" placeholder="Misal: PROMO50" required
                                            style="text-transform: uppercase;">
                                    </div>
                                    <div class="field-hint">Gunakan huruf kapital dan tanpa spasi.</div>
                                </div>

                                <!-- Nama Voucher -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Promo <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?= old('name') ?>" placeholder="Misal: Diskon Gajian" required>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-12">
                                    <label for="description" class="form-label">Deskripsi Promo</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Jelaskan syarat dan ketentuan promo ini..."><?= old('description') ?></textarea>
                                </div>

                                <!-- Nominal Diskon -->
                                <div class="col-md-6">
                                    <label for="discount_nominal" class="form-label">Nominal Potongan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="discount_nominal"
                                            name="discount_nominal" value="<?= old('discount_nominal') ?>"
                                            placeholder="0" required>
                                    </div>
                                </div>

                                <!-- Berlaku Sampai -->
                                <div class="col-md-6">
                                    <label for="valid_until" class="form-label">Berlaku Hingga <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="valid_until" name="valid_until"
                                            value="<?= old('valid_until') ?>" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-3 mb-4 px-4 mt-1">
                            <div class="col-6">
                                <?php if (can('vouchers_create')): ?>
                                    <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                        <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Voucher</span>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary w-100 fw-bold"
                                        style="border-radius: 10px; height: 45px;" disabled>
                                        <i class="fas fa-lock me-2"></i>Akses Ditolak
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('admin/vouchers') ?>"
                                    class="btn btn-outline-secondary w-100 py-2 fw-bold" style="border-radius: 10px;">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>