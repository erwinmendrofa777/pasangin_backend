<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Banner Supplier - <?= esc($banner['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .detail-hero {
        background: #6777ef;
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    /* ===== IMAGE PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -60px;
        margin-left: 10px;
    }

    .banner-preview-img {
        width: 320px;
        height: 180px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 30px rgba(103, 119, 239, 0.2);
        background: #e9ecef;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .banner-preview-img:hover {
        transform: scale(1.02);
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #fff;
    }

    .detail-body {
        padding: 0 24px 28px;
    }

    /* ===== BADGES ===== */
    .badge-pill {
        border-radius: 50px;
        padding: 6px 16px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO STYLES ===== */
    .info-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
        display: block;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .action-sidebar {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
    }

    @media (max-width: 768px) {
        .detail-hero {
            padding: 20px 20px 60px;
        }

        .avatar-wrapper {
            margin-top: -40px;
            margin-left: 0;
            width: 100%;
            text-align: center;
        }

        .banner-preview-img {
            width: 100%;
            max-width: 320px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_dtl_scripts') ?>
<?= $this->endSection() ?>