<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
        overflow: hidden;
    }

    .detail-header {
        background: #fff;
        border-bottom: 1px solid #f8f9fa;
        padding: 30px;
    }

    .detail-body {
        background: #fff;
        padding: 40px;
    }

    .doc-content {
        background: #fdfdff;
        border: 1.5px solid #f1f3f9;
        border-radius: 16px;
        padding: 40px 48px;
        font-size: 1rem;
        line-height: 1.9;
        color: #343a40;
    }

    /* Editor.js Block Rendering Styles */
    .doc-content h1 { font-size: 2rem; font-weight: 800; margin: 1.5rem 0 0.75rem; color: #1a1d23; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem; }
    .doc-content h2 { font-size: 1.5rem; font-weight: 700; margin: 1.5rem 0 0.75rem; color: #2d3748; }
    .doc-content h3 { font-size: 1.2rem; font-weight: 700; margin: 1.2rem 0 0.6rem; color: #4a5568; }
    .doc-content h4 { font-size: 1rem; font-weight: 700; margin: 1rem 0 0.5rem; color: #718096; }

    .doc-content p { margin-bottom: 1rem; text-align: justify; }

    .doc-content ol, .doc-content ul {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .doc-content ol li, .doc-content ul li {
        margin-bottom: 0.4rem;
    }

    .doc-content hr.sk-delimiter {
        border: none;
        border-top: 3px solid #e9ecef;
        margin: 2rem auto;
        width: 40%;
        border-radius: 4px;
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        display: block;
    }

    .target-badge {
        display: inline-block;
        padding: 6px 16px;
        background: #f0f4ff;
        color: #6777ef;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
        border: 1px solid #e0e6ff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>