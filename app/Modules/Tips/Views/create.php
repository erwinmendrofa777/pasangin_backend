<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(255, 92, 92, 0.08);
    }

    .form-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f8f9fa;
        padding: 25px 30px;
    }

    .form-card .card-body {
        padding: 30px;
    }

    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 800;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    /* Editor.js Wrapper */
    .editor-wrapper {
        border: 2px solid #f1f3f9;
        border-radius: 12px;
        min-height: 400px;
        background: #fff;
        transition: border-color 0.2s;
        overflow: visible;
    }

    .editor-wrapper:focus-within {
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.1);
    }

    #editorjs {
        padding: 16px 24px;
        min-height: 380px;
    }

    .codex-editor__redactor {
        padding-bottom: 60px !important;
    }

    .ce-block__content,
    .ce-toolbar__content {
        max-width: 100% !important;
    }

    .ce-toolbar__plus:hover,
    .ce-toolbar__settings-btn:hover {
        background: var(--palette-primary) !important;
        color: #fff !important;
    }

    /* Image Preview */
    .image-preview-container {
        width: 100%;
        height: 200px;
        border: 2px dashed #f1f3f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 15px;
        cursor: pointer;
        transition: 0.3s;
    }

    .image-preview-container:hover {
        border-color: var(--palette-primary);
        background: #fcfcff;
    }

    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-container i {
        font-size: 40px;
        color: #d0d4f5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tips\Views\components\_crt_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tips\Views\components\_crt_scripts') ?>
<?= $this->endSection() ?>
