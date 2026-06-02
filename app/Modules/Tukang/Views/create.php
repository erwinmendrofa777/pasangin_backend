<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Mitra Tukang Baru
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: #0d6efd;
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

    /* ===== AVATAR PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
    }

    .tukang-preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        object-position: center;
        border-radius: 20px;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    .tukang-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 20px;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #6ea8fe, #0d6efd);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .edit-body {
        padding: 0 28px 28px;
    }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(13, 110, 253, 0.08), 0 1px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
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

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #dee2e6;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: #212529;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid #dee2e6;
        border-right: none;
        background: #f8f9fa;
        color: #6c757d;
    }

    /* ===== PHOTO BOXES ===== */
    .doc-upload-box {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #fdfdfd;
        height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .doc-upload-box:hover {
        border-color: #0d6efd;
        background: #f0f6ff;
    }

    .doc-preview-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 10px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
        color: #fff;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.35);
    }

    @media (max-width: 768px) {
        .edit-hero {
            padding: 20px 20px 60px;
        }

        .edit-hero .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .edit-body {
            padding: 0 16px 20px;
        }

        .tukang-preview-img,
        .tukang-placeholder {
            width: 90px;
            height: 90px;
        }

        .avatar-wrapper {
            margin-top: -45px;
        }

        .section-card .card-header {
            padding: 12px 16px;
        }

        .section-card .card-body {
            padding: 16px;
        }

        .btn-save,
        .btn-outline-secondary,
        .btn-secondary {
            height: 48px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100% !important;
        }

        .back-btn-wrapper {
            margin-bottom: 20px !important;
        }

        .back-btn-wrapper .btn {
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            background: #fff;
            color: #495057;
            border: 1px solid #dee2e6;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_crt_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_crt_scripts') ?>
<?= $this->endSection() ?>