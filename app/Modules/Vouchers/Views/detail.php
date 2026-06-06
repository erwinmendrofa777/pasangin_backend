<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Voucher - <?= esc($voucher['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Voucher
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .voucher-hero {
        background: var(--palette-primary);
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 68px;
        position: relative;
        overflow: hidden;
    }

    .voucher-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .voucher-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== VOUCHER IMAGE ===== */
    .voucher-preview-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -90px;
    }

    .voucher-preview-img {
        width: 240px;
        height: 140px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .detail-body {
        padding: 0 24px 28px;
    }

    /* ===== BADGES ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 12px;
        background: #ffe5e5;
        color: var(--palette-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .info-label {
        font-size: 0.72rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #2d3436;
        font-weight: 600;
    }

    /* ===== CODE BOX ===== */
    .voucher-code-box {
        background: #f8fafc;
        border: 2px dashed var(--palette-primary);
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        margin-top: 20px;
    }

    .voucher-code-text {
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--palette-primary);
        letter-spacing: 2px;
    }

    @media (max-width: 767px) {
        .voucher-preview-img {
            width: 100%;
            height: auto;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Vouchers\Views\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Vouchers\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>