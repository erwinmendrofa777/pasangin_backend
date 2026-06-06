<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Mitra Tukang - <?= esc($tukang['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .profile-hero {
        background: var(--palette-primary);
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
        border-radius: 16px;
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

    /* ===== CARDS ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
    }

    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
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

    .status-berkas {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-test {
        background: #e0f2fe;
        color: #075985;
    }

    .status-aktivasi {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-siap {
        background: #d1fae5;
        color: #065f46;
    }

    .verify-badge {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .bg-verified {
        background: #dcfce7;
        color: #15803d;
    }

    .bg-unverified {
        background: #f3f4f6;
        color: #4b5563;
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
        background: #ffe5e5;
        color: var(--palette-primary);
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

    /* ===== PHOTO PREVIEW ===== */
    .doc-photo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #dee2e6;
        cursor: zoom-in;
        transition: all 0.2s ease-in-out;
    }

    .doc-photo:hover {
        transform: scale(1.04);
        border-color: var(--palette-primary);
        box-shadow: 0 6px 18px rgba(255, 92, 92, 0.18);
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

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--palette-primary);
        margin-bottom: 10px;
        margin-top: 20px;
    }

    /* ===== RATINGS ===== */
    .rating-card {
        border-radius: 12px;
        border: 1px solid #f0f2f5;
        padding: 15px;
        margin-bottom: 15px;
        background: #fff;
    }

    /* ===== CUSTOM SCROLLBAR ===== */
    .ratings-scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .ratings-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .ratings-scroll-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .ratings-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #bbb;
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
<?= $this->include('App\Modules\Tukang\Views\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>