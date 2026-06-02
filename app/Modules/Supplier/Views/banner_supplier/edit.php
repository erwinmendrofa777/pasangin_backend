<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit Banner Supplier
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: #6777ef;
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }

    .edit-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .edit-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== BANNER PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
        width: 100%;
        max-width: 400px;
    }

    .banner-preview-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center;
        border-radius: 14px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #fff;
    }

    .edit-body {
        padding: 0 28px 28px;
    }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(103, 119, 239, 0.08), 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .section-card .card-header {
        background: #f8fbff;
        border-bottom: 1px solid #eef2ff;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    .section-card .card-header h6 {
        color: #6777ef;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #eef2ff;
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 4px rgba(103, 119, 239, 0.1);
    }

    .select2-container--default .select2-selection--single {
        border-radius: 10px !important;
        border: 1.5px solid #eef2ff !important;
        height: 48px !important;
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #6777ef, #4c5fd7);
        border: none;
        color: #fff;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(103, 119, 239, 0.35);
        color: #fff;
    }

    @media (max-width: 768px) {
        .edit-hero { padding: 20px 20px 60px; }
        .edit-body { padding: 0 16px 20px; }
        .avatar-wrapper { margin-top: -50px; }
        .banner-preview-img { height: 160px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_edt_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_edt_scripts') ?>
<?= $this->endSection() ?>
