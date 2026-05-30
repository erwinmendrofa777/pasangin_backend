<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Admin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Admin
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: #6777EF;
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }
    .edit-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .edit-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 280px; height: 280px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* ===== AVATAR ===== */
    .avatar-wrapper { position: relative; display: inline-block; margin-top: -55px; }
    .avatar-img {
        width: 90px; height: 90px;
        object-fit: cover; object-position: center;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 4px rgba(0,0,0,0.18);
        background: #e9ecef;
    }
    .avatar-initials {
        width: 90px; height: 90px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.18);
        background: linear-gradient(135deg, #6ea8fe, #0d6efd);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700; color: #fff;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13,110,253,0.10), 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .edit-body { padding: 0 28px 28px; }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(13,110,253,0.08), 0 1px 6px rgba(0,0,0,0.05);
    }
    .section-card .card-header {
        background: #f0f6ff;
        border-bottom: 1px solid #dce8ff;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }
    .section-card .card-header h6 {
        color: #0d6efd;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }
    .section-card .card-body { padding: 20px; }

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #dee2e6;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: #212529;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
    }
    .form-control::placeholder { color: #adb5bd; font-size: 0.87rem; }
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid #dee2e6;
        border-right: none;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 0.88rem;
    }
    .input-group .form-control, .input-group .form-select {
        border-radius: 0 10px 10px 0;
    }
    .input-group:focus-within .input-group-text {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #e7f0ff;
    }
    .input-group:focus-within .form-control, .input-group:focus-within .form-select {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
        color: #fff;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13,110,253,0.35);
        color: #fff;
    }
    .btn-cancel {
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 767px) {
        .edit-hero { padding: 22px 18px 60px; }
        .edit-body { padding: 0 16px 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/admin') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Admin
    </a>
</div>

<form id="create-admin-form" method="POST" action="<?= base_url('admin/admin/store') ?>" enctype="multipart/form-data">
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
                                Tambah Admin Baru
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar -->
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <div class="avatar-wrapper position-relative">
                            <div class="avatar-initials d-flex" id="img-preview-initials"><i class="fas fa-user-plus"></i></div>
                            <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">

                            <!-- Upload Button Overlay -->
                            <label for="photo" class="btn btn-sm btn-primary position-absolute rounded-circle shadow" 
                                   style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="photo" name="photo" class="d-none" accept="image/*" onchange="previewImage()">
                        </div>
                    </div>

                    <!-- ===== Identitas Admin ===== -->
                    <div class="card section-card mb-4">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-id-card me-2"></i>Identitas Admin</h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">

                                <!-- Nama Lengkap -->
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                               value="<?= old('full_name') ?>"
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-12 col-sm-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="Minimal 6 karakter" required>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="col-12 col-sm-6">
                                    <label for="role" class="form-label">Role Admin <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="" disabled selected>-- Pilih Role --</option>
                                            <?php foreach ($roles as $r): ?>
                                                <option value="<?= esc($r['role_name']) ?>" <?= old('role') == $r['role_name'] ? 'selected' : '' ?>>
                                                    <?= esc(ucwords(str_replace('_', ' ', $r['role_name']))) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Kontak ===== -->
                    <div class="card section-card mb-4">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-address-book me-2"></i>Informasi Kontak</h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?= old('email') ?>"
                                               placeholder="contoh@email.com" required>
                                    </div>
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="col-12">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                               value="<?= old('phone_number') ?>"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 px-4">
                        <div class="col-6">
                            <?php if (can('admin_create')): ?>
                            <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out" id="submit-btn">
                                <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan</span>
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-lock me-2"></i>Akses Ditolak
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('admin/admin') ?>" class="btn btn-outline-secondary btn-cancel text-center w-100">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
<?php if (session()->getFlashdata('error')): ?>
iziToast.error({ timeout: 6000, title: 'Gagal', message: '<?= strip_tags(session()->getFlashdata('error')) ?>', position: 'topCenter' });
<?php endif; ?>

document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('create-admin-form');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('.ladda-button');
            if (submitBtn) {
                const l = Ladda.create(submitBtn);
                l.start();
            }
        });
    }
});

function previewImage() {
    const photo = document.querySelector('#photo');
    const imgPreview = document.querySelector('#img-preview');
    const imgPreviewInitials = document.querySelector('#img-preview-initials');

    if (photo.files && photo.files[0]) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(photo.files[0]);

        fileReader.onload = function(e) {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('d-none');
            
            if (imgPreviewInitials) {
                imgPreviewInitials.classList.remove('d-flex');
                imgPreviewInitials.classList.add('d-none');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
