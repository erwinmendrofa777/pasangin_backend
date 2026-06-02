<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(103, 119, 239, 0.05);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(103, 119, 239, 0.03);
        border-radius: 50%;
    }

    /* ===== STAT PILLS ===== */
    .stat-pill {
        background: #f0f4ff;
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #4b49ac;
        font-weight: 700;
        border: 1px solid #e0e6ff;
    }

    .stat-pill .stat-num {
        background: #6777ef;
        color: #fff;
        border-radius: 50px;
        padding: 1px 10px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* ===== CONCEPT CARDS ===== */
    .concept-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.05);
        background: #fff;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .concept-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f8f9fa;
        padding: 20px 25px;
    }

    /* ===== TABLE STYLING ===== */
    .quality-table {
        margin-bottom: 0;
    }

    .quality-table thead th {
        background: #fcfcff;
        color: #8e94a9;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        padding: 15px 20px;
    }

    .quality-table tbody td {
        border-bottom: 1px solid #f8f9fa;
        padding: 18px 20px;
        font-size: 0.9rem;
        vertical-align: middle;
        color: #495057;
    }

    .quality-table tbody tr:last-child td {
        border: none;
    }

    /* ===== PRICE TAGS ===== */
    .price-pill {
        background: #f3f6ff;
        color: #6777ef;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.82rem;
        display: inline-block;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-circle-action {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid #eee;
        background: #fff;
        color: #6777ef;
    }

    .btn-circle-action:hover {
        background: #6777ef;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-circle-delete:hover {
        background: #fc544b;
        border-color: #fc544b;
    }

    /* ===== MODAL CUSTOM ===== */
    .modal-content-custom {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .modal-header-custom {
        padding: 30px 30px 10px;
        border: none;
    }

    .modal-body-custom {
        padding: 10px 30px 30px;
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
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_header') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_concepts') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_modals') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>