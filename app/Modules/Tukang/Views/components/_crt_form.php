<form id="create-tukang-form" method="POST" action="<?= base_url('admin/tukang/store') ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Tambah Mitra Tukang Baru
                            </h5>
                            <p class="text-white-50 small mb-0">Lengkapi formulir pendaftaran mitra tukang secara
                                lengkap</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2"
                            style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-plus me-1 opacity-75"></i>PENDAFTARAN BARU
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar Preview -->
                    <div class="avatar-wrapper">
                        <div class="tukang-placeholder" id="profile-placeholder">
                            <i class="fas fa-user-plus fa-2x mb-1"></i>
                            <span style="font-size: 0.6rem; font-weight: 700; opacity: 0.8;">FOTO PROFIL</span>
                        </div>
                        <img src="" alt="Preview" class="tukang-preview-img d-none" id="profile-preview">

                        <!-- Upload Button Overlay -->
                        <label for="profile_photo"
                            class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                            style="bottom: 5px; right: -5px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff;">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*"
                            onchange="previewFile(this, 'profile')" required>
                    </div>

                    <div class="row g-4 mt-1">
                        <!-- LEFT COLUMN: Account & Personal -->
                        <div class="col-lg-7">
                            <!-- Account Info -->
                            <div class="card section-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-user-circle me-2"></i>Informasi Akun & Kontak</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">Nama Lengkap Sesuai KTP <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="<?= old('name') ?>" placeholder="Contoh: Budi Santoso" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= old('email') ?>" placeholder="email@contoh.com" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Nomor WhatsApp <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?= old('phone') ?>" placeholder="08123456789" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="specialization" class="form-label">Spesialisasi Keahlian <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="specialization" name="specialization"
                                                required>
                                                <option value="" disabled selected>Pilih Keahlian...</option>
                                                <option value="Tukang Bangunan" <?= old('specialization') == 'Tukang Bangunan' ? 'selected' : '' ?>>Tukang Bangunan (General)</option>
                                                <option value="Tukang Listrik" <?= old('specialization') == 'Tukang Listrik' ? 'selected' : '' ?>>Tukang Listrik</option>
                                                <option value="Tukang Ledeng" <?= old('specialization') == 'Tukang Ledeng' ? 'selected' : '' ?>>Tukang Ledeng / Plumber</option>
                                                <option value="Tukang Cat" <?= old('specialization') == 'Tukang Cat' ? 'selected' : '' ?>>Tukang Cat</option>
                                                <option value="Tukang Kayu" <?= old('specialization') == 'Tukang Kayu' ? 'selected' : '' ?>>Tukang Kayu</option>
                                                <option value="Tukang Las" <?= old('specialization') == 'Tukang Las' ? 'selected' : '' ?>>Tukang Las / Besi</option>
                                                <option value="Tukang Plafon" <?= old('specialization') == 'Tukang Plafon' ? 'selected' : '' ?>>Tukang Plafon / Gypsum</option>
                                                <option value="Tukang Keramik" <?= old('specialization') == 'Tukang Keramik' ? 'selected' : '' ?>>Tukang Keramik / Lantai</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Details -->
                            <div class="card section-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-id-card me-2"></i>Detail Identitas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nik" name="nik"
                                                value="<?= old('nik') ?>" placeholder="16 digit nomor KTP" required
                                                maxlength="16">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="gender" class="form-label">Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="gender" name="gender" required>
                                                <option value="Laki-laki" <?= old('gender') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="Perempuan" <?= old('gender') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob" class="form-label">Tanggal Lahir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="dob" name="dob"
                                                value="<?= old('dob') ?>" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="ktp_address" class="form-label">Alamat Sesuai KTP <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="ktp_address" name="ktp_address" rows="2"
                                                placeholder="Alamat lengkap sesuai KTP"
                                                required><?= old('ktp_address') ?></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="domicile_address" class="form-label">Alamat Domisili Sekarang
                                                <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="domicile_address" name="domicile_address"
                                                rows="2" placeholder="Alamat tinggal saat ini"
                                                required><?= old('domicile_address') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Documents -->
                        <div class="col-lg-5">
                            <div class="card section-card">
                                <div class="card-header">
                                    <h6><i class="fas fa-file-image me-2"></i>Dokumen Pendukung</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <!-- KTP Photo -->
                                        <div class="col-12">
                                            <label class="form-label d-block">Foto KTP Asli <span
                                                    class="text-danger">*</span></label>
                                            <div class="doc-upload-box"
                                                onclick="document.getElementById('ktp_photo').click()">
                                                <div id="ktp-placeholder">
                                                    <i class="fas fa-id-card fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0 small text-muted">Klik untuk upload Foto KTP</p>
                                                </div>
                                                <img src="" id="ktp-preview" class="doc-preview-img">
                                            </div>
                                            <input type="file" id="ktp_photo" name="ktp_photo" class="d-none"
                                                accept="image/*" onchange="previewFile(this, 'ktp')" required>
                                        </div>

                                        <!-- Selfie Photo -->
                                        <div class="col-12">
                                            <label class="form-label d-block">Foto Selfie dengan KTP <span
                                                    class="text-danger">*</span></label>
                                            <div class="doc-upload-box"
                                                onclick="document.getElementById('selfie_photo').click()">
                                                <div id="selfie-placeholder">
                                                    <i class="fas fa-user-shield fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0 small text-muted">Klik untuk upload Foto Selfie</p>
                                                </div>
                                                <img src="" id="selfie-preview" class="doc-preview-img">
                                            </div>
                                            <input type="file" id="selfie_photo" name="selfie_photo" class="d-none"
                                                accept="image/*" onchange="previewFile(this, 'selfie')" required>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mt-4 mb-0"
                                        style="border-radius: 12px; border: none; background: #eef6ff;">
                                        <div class="d-flex gap-2">
                                            <i class="fas fa-info-circle text-primary mt-1"></i>
                                            <p class="small mb-0 text-dark">
                                                Pastikan semua foto dokumen terlihat jelas, tidak terpotong, dan
                                                pencahayaan cukup untuk mempermudah proses verifikasi.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="card section-card border-0 shadow-none bg-transparent">
                        <div class="card-body p-0 pb-4 mt-2">
                            <div class="d-flex flex-column flex-md-row justify-content-end gap-3">
                                <a href="<?= base_url('admin/tukang') ?>"
                                    class="btn btn-outline-secondary fw-bold order-2 order-md-1 cancel-btn"
                                    style="border-radius: 10px; height: 50px; display: flex; align-items: center; justify-content: center; padding: 0 30px;">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <?php if (can('tukang_create')): ?>
                                    <button type="submit" class="btn btn-save ladda-button order-1 order-md-2"
                                        data-style="zoom-out" style="height: 50px; padding: 0 40px; border-radius: 10px;">
                                        <span class="ladda-label"><i class="fas fa-check-circle me-2"></i>Daftarkan
                                            Mitra</span>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary fw-bold order-1 order-md-2"
                                        style="border-radius:10px; height: 50px; padding: 0 40px;" disabled>
                                        <i class="fas fa-lock me-2"></i>Akses Ditolak
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>