<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit Admin - <?= esc($admin['full_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Edit Admin
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

<?php
/* ===== PHOTO ===== */
if (!empty($admin['photo'])) {
    $avatarSrc = strpos($admin['photo'], 'http') === 0
        ? $admin['photo']
        : base_url('uploads/profile/' . $admin['photo']);
} else {
    $avatarSrc = null;
}

/* ===== INITIALS ===== */
$nameParts = explode(' ', trim($admin['full_name'] ?? 'A'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/admin') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Admin
    </a>
</div>

    <?= $this->include('App\Modules\Admin\Views\admin\components\_edit_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\admin\components\_edit_scripts') ?>
<?= $this->endSection() ?>
