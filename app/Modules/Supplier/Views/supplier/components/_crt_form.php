<form id="create-supplier-form" method="POST" action="<?= base_url('admin/suppliers/save') ?>"
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
                                Tambah Data Supplier Baru
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar -->
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <div class="avatar-wrapper position-relative">
                            <div class="avatar-initials d-flex" id="img-preview-initials"><i class="fas fa-building"
                                    style="font-size: 1.5rem;"></i></div>
                            <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">

                            <!-- Upload Button Overlay -->
                            <label for="logo_url" class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                                style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="logo_url" name="logo_url" class="d-none" accept="image/*"
                                onchange="previewImage()">
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
                                <div class="col-12">
                                    <label for="name" class="form-label">Nama Supplier / Perusahaan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?= set_value('name') ?>" placeholder="Masukkan nama perusahaan"
                                            required>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="text" class="form-control" id="email" name="email"
                                            value="<?= set_value('email') ?>" placeholder="Masukkan email" required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="<?= set_value('password') ?>" placeholder="Masukkan password"
                                            required>
                                    </div>
                                </div>

                                <!-- Is Active -->
                                <div class="col-12 col-sm-6">
                                    <label for="is_active" class="form-label">Akses Tampil di Web/Sistem</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1" <?= set_value('is_active') == '1' ? 'selected' : '' ?>>Tampil
                                                (Aktif)</option>
                                            <option value="0" <?= set_value('is_active') == '0' ? 'selected' : '' ?>>
                                                Sembunyikan (Non-Aktif)</option>
                                        </select>
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
                                            name="contact_person" value="<?= set_value('contact_person') ?>"
                                            placeholder="Nama pengurus/PIC supplier" required>
                                    </div>
                                </div>

                                <!-- Telepon -->
                                <div class="col-12 col-sm-6">
                                    <label for="phone" class="form-label">Nomor Telepon Operasional</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="<?= set_value('phone') ?>" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                                <!-- alamat -->
                                <div class="col-12 col-sm-6">
                                    <label for="address" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="<?= set_value('address') ?>" placeholder="Masukkan alamat" required>
                                    </div>
                                </div>

                                <!-- district -->
                                <div class="col-12 col-sm-6">
                                    <label for="district" class="form-label">Kecamatan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="district" name="district"
                                            value="<?= set_value('district') ?>" placeholder="Masukkan kecamatan"
                                            required>
                                    </div>
                                </div>

                                <!-- city -->
                                <div class="col-12 col-sm-6">
                                    <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="<?= set_value('city') ?>" placeholder="Masukkan kota" required>
                                    </div>
                                </div>

                                <!-- province -->
                                <div class="col-12 col-sm-6">
                                    <label for="province" class="form-label">Provinsi <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="province" name="province"
                                            value="<?= set_value('province') ?>" placeholder="Masukkan provinsi"
                                            required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Submit Area -->
                        <div class="row g-3 mb-4 px-4 mt-1">
                            <div class="col-6">
                                <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out"
                                    id="submit-btn">
                                    <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Supplier</span>
                                </button>
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