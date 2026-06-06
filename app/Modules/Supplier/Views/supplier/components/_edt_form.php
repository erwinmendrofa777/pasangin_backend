<?php
/* ===== LOGO ===== */
$avatarSrc = null;
if (!empty($supplier['logo_url'])) {
    $avatarSrc = strpos($supplier['logo_url'], 'http') === 0
        ? $supplier['logo_url']
        : base_url('uploads/supplierLogo/' . $supplier['logo_url']);
}

/* ===== INITIALS ===== */
$nameParts = explode(' ', trim($supplier['name'] ?? 'S'));
$initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>

<form id="edit-supplier-form" method="POST" action="<?= base_url('admin/suppliers/update/' . $supplier['id']) ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">

        <!-- ======================== MAIN FORM ======================== -->
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold ms-1" style="font-size:1.15rem;">
                                Edit Data Supplier
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar -->
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <div class="avatar-wrapper position-relative">
                            <?php if ($avatarSrc): ?>
                                <img src="<?= $avatarSrc ?>" alt="<?= esc($supplier['name']) ?>" class="avatar-img"
                                    id="img-preview">
                            <?php else: ?>
                                <div class="avatar-initials d-flex" id="img-preview-initials"><?= $initials ?></div>
                                <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">
                            <?php endif; ?>

                            <!-- Upload Button Overlay -->
                            <?php if (can('suppliers_edit')): ?>
                                <label for="logo_url" class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                                    style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="logo_url" name="logo_url" class="d-none" accept="image/*"
                                    onchange="previewImage()">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ===== Informasi Perusahaan ===== -->
                    <div class="card section-card mb-0">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-building me-2"></i>Informasi Perusahaan</h6>
                        </div>
                        <div class="card-body pt-1">
                            <div class="row g-3">

                                <!-- Nama Supplier -->
                                <div class="col-12 col-sm-6">
                                    <label for="name" class="form-label">Nama Supplier / Perusahaan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?= esc($supplier['name'] ?? '') ?>"
                                            placeholder="Masukkan nama perusahaan" required>
                                    </div>
                                </div>

                                <!-- Is Active -->
                                <div class="col-12 col-sm-6">
                                    <label for="is_active" class="form-label">Akses Tampil di Web/Sistem</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1" <?= ($supplier['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                                                Tampil (Aktif)</option>
                                            <option value="0" <?= ($supplier['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                                                Sembunyikan (Non-Aktif)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12 col-sm-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?= esc($supplier['email'] ?? '') ?>" placeholder="Masukkan email"
                                            required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-12 col-sm-6">
                                    <label for="password" class="form-label">Password <span class="text-muted fw-normal"
                                            style="font-size: 0.75rem;">(Kosongkan jika tidak diubah)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Ketik password baru jika ingin mengubahnya">
                                    </div>
                                </div>

                            </div>

                            <!-- ===== Kontak ===== -->
                            <div class="card-header pt-1 pb-1 mb-0 ps-0 mt-3">
                                <h6><i class="fas fa-address-book me-2"></i>Informasi Kontak Operasional</h6>
                            </div>
                            <div class="row g-3 pt-2">

                                <!-- Kontak Person -->
                                <div class="col-12 col-sm-6">
                                    <label for="contact_person" class="form-label">Nama Kontak Person <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" class="form-control" id="contact_person"
                                            name="contact_person" value="<?= esc($supplier['contact_person'] ?? '') ?>"
                                            placeholder="Nama pengurus/PIC supplier" required>
                                    </div>
                                </div>

                                <!-- Telepon -->
                                <div class="col-12 col-sm-6">
                                    <label for="phone" class="form-label">Nomor Telepon Operasional</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="<?= esc($supplier['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-12 col-sm-6">
                                    <label for="address" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="<?= esc($supplier['address'] ?? '') ?>" placeholder="Masukkan alamat"
                                            required>
                                    </div>
                                </div>

                                <!-- District (Kecamatan) -->
                                <div class="col-12 col-sm-6">
                                    <label for="district" class="form-label">Kecamatan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="district" name="district"
                                            value="<?= esc($supplier['district'] ?? '') ?>"
                                            placeholder="Masukkan kecamatan" required>
                                    </div>
                                </div>

                                <!-- City (Kota) -->
                                <div class="col-12 col-sm-6">
                                    <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="<?= esc($supplier['city'] ?? '') ?>" placeholder="Masukkan kota"
                                            required>
                                    </div>
                                </div>

                                <!-- Province (Provinsi) -->
                                <div class="col-12 col-sm-6">
                                    <label for="province" class="form-label">Provinsi <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="province" name="province"
                                            value="<?= esc($supplier['province'] ?? '') ?>"
                                            placeholder="Masukkan provinsi" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Submit Area -->
                        <div class="row g-3 mb-4 px-4 mt-1">
                            <div class="col-6">
                                <?php if (can('suppliers_edit')): ?>
                                    <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out"
                                        id="submit-btn">
                                        <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Perubahan</span>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary w-100 opacity-50" disabled>
                                        <i class="fas fa-lock me-2"></i>Akses Ditolak
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('admin/suppliers') ?>"
                                    class="btn btn-outline-secondary btn-cancel text-center w-100">
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