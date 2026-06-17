<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail User - <?= esc($user['full_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail User
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
        border-radius: 50%;
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
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: var(--palette-primary);
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
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
    }

    /* ===== RIGHT CARD ===== */
    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        height: 100%;
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

    .status-approved {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .status-pending {
        background: #fff3cd;
        color: #7d5a00;
    }

    .status-rejected {
        background: #f8d7da;
        color: #842029;
    }

    .status-banned {
        background: #343a40;
        color: #f8f9fa;
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
        color: var(--palette-primary);
        margin-bottom: 10px;
    }

    /* ===== ROLE CHIP ===== */
    .role-chip-hero {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border-radius: 50px;
        padding: 5px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    @media (max-width: 767px) {
        .profile-hero {
            padding: 28px 18px 60px;
        }

        .profile-body {
            padding: 0 16px 22px;
        }
    }

    /* ===== TAB NAVIGATION ===== */
    .tab-nav-link {
        border: none !important;
        background: transparent !important;
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        padding: 12px 20px !important;
        border-bottom: 3px solid transparent !important;
        transition: all 0.2s ease-in-out !important;
    }

    .tab-nav-link:hover {
        color: var(--palette-primary) !important;
    }

    .tab-nav-link.active {
        color: var(--palette-primary) !important;
        border-bottom-color: var(--palette-primary) !important;
        background: transparent !important;
    }

    .detail-container-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        background: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card detail-container-card shadow-sm">
    <!-- Profile Hero Card Header -->
    <?= $this->include('App\Modules\Users\Views\components\_profile_info_hero') ?>

    <!-- Tabs Nav Bar -->
    <div class="bg-light border-bottom px-4 pt-2">
        <ul class="nav nav-tabs border-0" id="detailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active tab-nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-content" type="button" role="tab" aria-controls="profile-content" aria-selected="true">
                    <i class="fas fa-user me-2"></i>Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-content" type="button" role="tab" aria-controls="orders-content" aria-selected="false">
                    <i class="fas fa-shopping-cart me-2"></i>Pesanan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-nav-link" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects-content" type="button" role="tab" aria-controls="projects-content" aria-selected="false">
                    <i class="fas fa-building me-2"></i>Proyek
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-content" type="button" role="tab" aria-controls="status-content" aria-selected="false">
                    <i class="fas fa-sliders-h me-2"></i>Kelola Status
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-content" type="button" role="tab" aria-controls="activity-content" aria-selected="false">
                    <i class="fas fa-history me-2"></i>Aktivitas
                </button>
            </li>
        </ul>
    </div>

    <!-- Tabs Content -->
    <div class="card-body p-4">
        <div class="tab-content" id="detailTabsContent">
            <!-- 1. Profil -->
            <div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">
                <?= $this->include('App\Modules\Users\Views\components\_profile_info') ?>
            </div>
            
            <!-- 2. Pesanan (AJAX) -->
            <div class="tab-pane fade" id="orders-content" role="tabpanel" aria-labelledby="orders-tab">
                <?= $this->include('App\Modules\Users\Views\components\_tab_orders') ?>
            </div>
            
            <!-- 3. Proyek (AJAX) -->
            <div class="tab-pane fade" id="projects-content" role="tabpanel" aria-labelledby="projects-tab">
                <?= $this->include('App\Modules\Users\Views\components\_tab_projects') ?>
            </div>
            
            <!-- 4. Kelola Status -->
            <div class="tab-pane fade" id="status-content" role="tabpanel" aria-labelledby="status-tab">
                <?= $this->include('App\Modules\Users\Views\components\_status_actions') ?>
            </div>
            
            <!-- 5. Aktivitas -->
            <div class="tab-pane fade" id="activity-content" role="tabpanel" aria-labelledby="activity-tab">
                <?= $this->include('App\Modules\Users\Views\components\_tab_activity') ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Users\Views\components\_dtl_scripts') ?>
<?= $this->endSection() ?>