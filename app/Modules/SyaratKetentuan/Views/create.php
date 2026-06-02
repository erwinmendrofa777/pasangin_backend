<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Tambah Syarat & Ketentuan<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Syarat & Ketentuan<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
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

    .form-control-custom {
        border-radius: 12px;
        border: 2px solid #f1f3f9;
        padding: 12px 16px;
        font-weight: 600;
        color: #495057;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #6777ef;
        background: #fff;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
        outline: none;
    }

    /* Editor.js Wrapper */
    .editor-wrapper {
        border: 2px solid #f1f3f9;
        border-radius: 12px;
        min-height: 400px;
        background: #fff;
        transition: border-color 0.2s;
        overflow: hidden;
    }

    .editor-wrapper:focus-within {
        border-color: #6777ef;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
    }

    #editorjs {
        padding: 16px 24px;
        min-height: 380px;
    }

    /* Override Editor.js styles to match our design */
    .codex-editor__redactor {
        padding-bottom: 60px !important;
    }

    .ce-toolbar__actions {
        right: -20px;
    }

    .ce-block__content,
    .ce-toolbar__content {
        max-width: 100% !important;
    }

    .ce-toolbar__plus:hover,
    .ce-toolbar__settings-btn:hover {
        background: #6777ef !important;
        color: #fff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_crt_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_crt_scripts') ?>
<?= $this->endSection() ?>