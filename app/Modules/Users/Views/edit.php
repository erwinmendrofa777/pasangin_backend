<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit User - <?= esc($user['full_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Edit User
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
    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }
    .input-group:focus-within .input-group-text {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #e7f0ff;
    }
    .input-group:focus-within .form-control {
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

    /* ===== ROLE CHIP HERO ===== */
    .role-chip-hero {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 50px;
        padding: 4px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-flex; align-items: center; gap: 5px;
    }

    /* ===== FIELD HINT ===== */
    .field-hint {
        font-size: 0.74rem;
        color: #6c757d;
        margin-top: 4px;
    }

    @media (max-width: 767px) {
        .edit-hero { padding: 22px 18px 60px; }
        .edit-body { padding: 0 16px 20px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
/* ===== AVATAR ===== */
if (!empty($user['avatar'])) {
    $avatarSrc = strpos($user['avatar'], 'http') === 0
        ? $user['avatar']
        : base_url('uploads/profile/' . $user['avatar']);
} else {
    $avatarSrc = null;
}

/* ===== INITIALS ===== */
$nameParts = explode(' ', trim($user['full_name'] ?? 'U'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
    </a>
</div>

<form id="edit-user-form" method="POST" action="<?= base_url('admin/users/update/' . $user['id']) ?>" enctype="multipart/form-data">
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
                                Edit Data User
                            </h5>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2" style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-hashtag me-1 opacity-75"></i>ID <?= esc($user['id']) ?>
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar -->
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <div class="avatar-wrapper position-relative">
                            <?php if ($avatarSrc): ?>
                                <img src="<?= $avatarSrc ?>" alt="<?= esc($user['full_name']) ?>"
                                     class="avatar-img" id="img-preview">
                            <?php else: ?>
                                <div class="avatar-initials d-flex" id="img-preview-initials"><?= $initials ?></div>
                                <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">
                            <?php endif; ?>

                            <!-- Upload Button Overlay -->
                            <label for="avatar" class="btn btn-sm btn-primary position-absolute rounded-circle shadow" 
                                   style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*" onchange="previewImage()">
                        </div>
                    </div>

                    <!-- ===== Identitas Pribadi ===== -->
                    <div class="card section-card mb-0">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-id-card me-2"></i>Identitas Pribadi</h6>
                        </div>
                        <div class="card-body pt-1">
                            <div class="row g-3">

                                <!-- Nama Lengkap -->
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                               value="<?= esc($user['full_name'] ?? '') ?>"
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>

                                <!-- NIK -->
                                <div class="col-12 col-sm-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        <input type="text" class="form-control" id="nik" name="nik"
                                               value="<?= esc($user['nik'] ?? '') ?>"
                                               placeholder="16 digit NIK" maxlength="16">
                                    </div>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="col-12 col-sm-6">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">-- Pilih --</option>
                                            <option value="Laki - laki" <?= ($user['gender'] ?? '') === 'Laki - laki' ? 'selected' : '' ?>>Laki - laki</option>
                                            <option value="Perempuan" <?= ($user['gender'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="col-12 col-sm-6">
                                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date"
                                               value="<?= esc($user['birth_date'] ?? '') ?>">
                                    </div>
                                </div>

                            </div>

                    <!-- ===== Kontak ===== -->
                        <div class="card-header pt-1 pb-1 mb-0 ps-0">
                            <h6><i class="fas fa-id-card me-2"></i>Informasi Kontak</h6>
                        </div>
                            <div class="row g-3">

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?= esc($user['email'] ?? '') ?>"
                                               placeholder="contoh@email.com" required>
                                    </div>
                                </div>

                                <!-- Nomor WhatsApp -->
                                <div class="col-12">
                                    <label for="phone_number" class="form-label">Nomor WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                               value="<?= esc($user['phone_number'] ?? '') ?>"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <div class="input-group align-items-start">
                                        <span class="input-group-text" style="padding-top:31.5px;padding-bottom:31.5px;"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"><?= esc($user['address'] ?? '') ?></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row g-3 mb-4 px-4">
                            <div class="col-6">
                                <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out" id="submit-btn">
                                    <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-cancel text-center w-100">
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

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
/* ===== Flash Messages ===== */
<?php if (session()->getFlashdata('success')): ?>
iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
iziToast.error({ timeout: 6000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
<?php endif; ?>

/* ===== Loading on Submit (Ladda) ===== */
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('edit-user-form');
    
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

/* ===== Image Preview ===== */
function previewImage() {
    const avatar = document.querySelector('#avatar');
    const imgPreview = document.querySelector('#img-preview');
    const imgPreviewInitials = document.querySelector('#img-preview-initials');

    if (avatar.files && avatar.files[0]) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(avatar.files[0]);

        fileReader.onload = function(e) {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('d-none');
            
            // Hide initials if they exist
            if (imgPreviewInitials) {
                imgPreviewInitials.classList.remove('d-flex');
                imgPreviewInitials.classList.add('d-none');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
