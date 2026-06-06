<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Tips - <?= esc($tips['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .detail-hero {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        border-radius: 20px 20px 0 0;
        padding: 35px 35px 85px;
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

    .detail-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== IMAGE PREVIEW (Main Cover) ===== */
    .cover-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -75px;
        margin-left: 35px;
        z-index: 2;
    }

    .tips-cover-img {
        width: 280px;
        height: 160px;
        object-fit: cover;
        border-radius: 18px;
        border: 5px solid #fff;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(255, 92, 92, 0.08);
        overflow: hidden;
    }

    .detail-body {
        padding: 0 35px 40px;
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

    .bg-tukang {
        background: #fff7ed;
        color: #9a3412;
    }

    .bg-client {
        background: #eff6ff;
        color: #1e40af;
    }

    .status-active {
        background: #e8fdf0;
        color: #0a6640;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* ===== CONTENT RENDERING ===== */
    .rendered-content {
        color: #4a5568;
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .rendered-content h2,
    .rendered-content h3,
    .rendered-content h4 {
        color: #2d3748;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .rendered-content p {
        margin-bottom: 1.25rem;
    }

    .rendered-content ul,
    .rendered-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .rendered-content li {
        margin-bottom: 0.5rem;
    }

    .rendered-content figure {
        margin: 2.5rem 0;
        text-align: center;
    }

    .rendered-content figure img {
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        max-width: 100%;
    }

    .rendered-content figcaption {
        margin-top: 0.75rem;
        font-size: 0.85rem;
        color: #8e94a9;
        font-style: italic;
    }

    .rendered-content blockquote {
        background: #f8f9ff;
        border-left: 4px solid var(--palette-primary);
        padding: 20px 25px;
        border-radius: 0 12px 12px 0;
        margin: 2rem 0;
        font-style: italic;
        color: var(--palette-primary-hover);
    }

    .rendered-content hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 3rem 0;
    }

    /* Side Widget */
    .side-widget {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(255, 92, 92, 0.08);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tips\Views\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tips\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>