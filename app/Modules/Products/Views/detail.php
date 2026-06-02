<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Produk - <?= esc($product['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Produk
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .profile-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 68px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== AVATAR ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -55px;
    }

    .avatar-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        object-position: center;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #e9ecef;
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .avatar-img:hover {
        transform: scale(1.06);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
    }

    .avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
    }

    /* ===== LEFT CARD ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
    }

    /* ===== RIGHT CARD ===== */
    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        height: 100%;
    }

    .action-card .card-header {
        background: #6777EF !important;
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
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-pill .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.75;
    }

    .status-aktif {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .status-tidak_aktif {
        background: #f8d7da;
        color: #842029;
    }

    .status-habis {
        background: #fff3cd;
        color: #7d5a00;
    }

    .status-default {
        background: #e2e3e5;
        color: #41464b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        background: #e7f0ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .info-label {
        font-size: 0.72rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.93rem;
        color: #212529;
        font-weight: 500;
        word-break: break-word;
    }

    /* ===== STATUS ACTION BUTTONS ===== */
    .status-action-btn {
        border-radius: 10px;
        font-size: 0.83rem;
        font-weight: 600;
        padding: 10px 12px;
        transition: all 0.18s ease;
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .status-action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    .status-action-btn:disabled {
        opacity: 0.85;
        cursor: not-allowed;
    }

    /* ===== CURRENT STATUS CARD ===== */
    .current-status-box {
        border-radius: 12px;
        padding: 16px 18px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #0d6efd;
        margin-bottom: 10px;
    }

    @media (max-width: 767px) {
        .profile-hero {
            padding: 28px 18px 60px;
        }

        .profile-body {
            padding: 0 16px 22px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
/* ===== STATUS META ===== */
$status = $product['status'] ?? 'unknown';
$statusMeta = [
    'aktif'       => ['class' => 'status-aktif',       'icon' => 'fas fa-check-circle',  'label' => 'Aktif'],
    'tidak aktif' => ['class' => 'status-tidak_aktif', 'icon' => 'fas fa-times-circle',   'label' => 'Tidak Aktif'],
    'habis'       => ['class' => 'status-habis',       'icon' => 'fas fa-box-open',      'label' => 'Habis'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];

/* ===== PHOTO ===== */
if (!empty($product['photo'])) {
    $photoSrc = strpos($product['photo'], 'http') === 0
        ? $product['photo']
        : base_url('uploads/products/' . $product['photo']);
} else {
    $photoSrc = null;
}

/* ===== INITIALS ===== */
$nameParts = explode(' ', trim($product['name'] ?? 'P'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));

$this->setData([
    'status' => $status,
    'statusMeta' => $statusMeta,
    'currentMeta' => $currentMeta,
    'photoSrc' => $photoSrc,
    'initials' => $initials
]);
?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- ===== 2-COLUMN LAYOUT ===== -->
<div class="row g-4 align-items-start">
    <?= $this->include('App\Modules\Products\Views\components\_dtl_product_info') ?>
    <?= $this->include('App\Modules\Products\Views\components\_dtl_status_panel') ?>
</div>

<?= $this->include('App\Modules\Products\Views\components\_dtl_modal_confirm') ?>
<?= $this->include('App\Modules\Products\Views\components\_dtl_modal_delete') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Products\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>