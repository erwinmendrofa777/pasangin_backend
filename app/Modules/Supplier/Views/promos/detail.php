<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Promo - <?= esc($promo['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Promo
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .promo-hero {
        background: var(--palette-primary);
        border-radius: 16px 16px 0 0;
        padding: 20px 28px 70px;
        position: relative;
        overflow: hidden;
    }

    .promo-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .promo-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== PROMO IMAGE ===== */
    .promo-img-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -60px;
    }

    .promo-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        background: #fff;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .action-card .card-header {
        background: var(--palette-primary) !important;
        border-radius: 16px 16px 0 0;
        padding: 18px 22px;
        border: none;
    }

    /* ===== STATUS PILL ===== */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 16px 0;
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
        background: #f0f4ff;
        color: var(--palette-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .info-label {
        font-size: 0.7rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #2d3436;
        font-weight: 600;
        word-break: break-word;
    }

    /* ===== ACTION BUTTONS ===== */
    .status-action-btn {
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .status-action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .promo-code-box {
        background: #f8f9ff;
        border: 2px dashed var(--palette-primary);
        border-radius: 12px;
        padding: 15px;
        text-align: center;
    }

    .promo-code-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--palette-primary);
        letter-spacing: 2px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\promos\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\promos\components\_dtl_scripts') ?>
<?= $this->endSection() ?>