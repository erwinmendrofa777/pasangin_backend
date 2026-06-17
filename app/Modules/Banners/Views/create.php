<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Banner
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Banner Baru
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<!-- Import Google Font Outfit jika belum ter-import -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Banner card styling */
    .banner-card-container {
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.03), 0 5px 15px rgba(0, 0, 0, 0.01) !important;
        border: 1px solid #e2e8f0 !important;
        background: #fff;
    }
    
    .banner-card-header {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 20px 25px !important;
        background: #fff !important;
    }
    
    .banner-card-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700 !important;
        color: #1e293b !important;
        font-size: 1.15rem !important;
    }
    
    /* Target App Selection Cards */
    .target-app-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .target-app-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.02);
    }
    
    .target-app-card.active {
        border-color: var(--palette-primary);
        background-color: rgba(255, 92, 92, 0.03);
    }
    
    .target-app-card .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #64748b;
        margin-bottom: 10px;
        transition: all 0.25s ease;
    }
    
    .target-app-card.active .card-icon {
        background: var(--palette-primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25);
    }
    
    .target-app-card .card-label {
        font-weight: 700;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    
    .target-app-card.active .card-label {
        color: var(--palette-primary);
    }
    
    .target-app-card .card-sub {
        font-size: 0.72rem;
        color: #64748b;
        line-height: 1.4;
    }
    
    .target-app-card .check-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 1rem;
        color: var(--palette-primary);
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .target-app-card.active .check-badge {
        opacity: 1;
        transform: scale(1);
    }
    
    /* Drag & Drop Dropzone */
    .banner-upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 30px 20px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .banner-upload-dropzone:hover,
    .banner-upload-dropzone.dragover {
        border-color: var(--palette-primary);
        background: rgba(255, 92, 92, 0.02);
    }
    
    .banner-upload-dropzone .upload-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--palette-primary);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
    }
    
    .banner-upload-dropzone:hover .upload-icon-wrapper {
        transform: scale(1.08);
        box-shadow: 0 6px 14px rgba(255, 92, 92, 0.15);
    }
    
    /* Mobile Mockup Frame */
    .banner-mockup-container {
        background: #f8fafc;
        padding: 12px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
    }
    
    .mobile-mockup-frame {
        width: 100%;
        max-width: 440px;
        aspect-ratio: 2/1; /* 2:1 ratio */
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        background: #e2e8f0;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }
    
    .mobile-mockup-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .mobile-mockup-frame .banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.15) 60%, transparent 100%);
        z-index: 1;
    }
    
    .mobile-mockup-frame .banner-title-overlay {
        position: absolute;
        bottom: 12px;
        left: 12px;
        right: 12px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        line-height: 1.3;
        z-index: 2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Input layout styling */
    .banner-input-group {
        position: relative;
    }
    .banner-input-group i {
        position: absolute;
        left: 15px;
        top: 14px;
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .banner-input-group input {
        padding-left: 42px !important;
        height: 42px !important;
        border-radius: 12px !important;
        border: 1px solid #cbd5e1 !important;
        font-size: 0.85rem !important;
        transition: all 0.25s ease !important;
    }
    .banner-input-group input:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.1) !important;
    }
    
    /* Buttons */
    .btn-save-banner {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover, #ff3b3b) 100%) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 10px 24px !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25) !important;
        transition: all 0.25s ease !important;
        color: #ffffff !important;
    }
    .btn-save-banner:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 15px rgba(255, 92, 92, 0.35) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Banners\Views\components\_create_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Banners\Views\components\_create_scripts') ?>
<?= $this->endSection() ?>
