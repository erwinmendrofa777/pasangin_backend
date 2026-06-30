<?php
// FILE: app/Modules/Chat/Views/cs.php
// Halaman Chat Customer Service — Hanya percakapan CS langsung (klien/tukang tanpa supplier)

echo $this->extend('layout/app');

echo $this->section('title');
?>
Customer Service Chat - Pasangin
<?php
echo $this->endSection();

echo $this->section('style');
?>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ========== SHARED BASE STYLES (diambil dari index.php) ========== */
    .chat-section-wrapper { font-family: 'Outfit', sans-serif; }
    .chat-section-wrapper .text-primary { color: var(--palette-primary) !important; }
    .chat-section-wrapper .btn-outline-primary { color: var(--palette-primary) !important; border-color: var(--palette-primary) !important; background-color: transparent !important; }
    .chat-section-wrapper .btn-outline-primary:hover, .chat-section-wrapper .btn-outline-primary:focus, .chat-section-wrapper .btn-outline-primary:active { background-color: var(--palette-primary) !important; color: #ffffff !important; }
    .chat-card-container { border-radius: 20px !important; overflow: hidden; box-shadow: 0 20px 50px rgba(255,92,92,0.04), 0 5px 15px rgba(0,0,0,0.01) !important; border: 1px solid rgba(255,92,92,0.08) !important; background: #fff; }
    .pasangin-chat-box { height: 100vh; display: flex; flex-direction: column; background: #f8fafc; min-height: 0; }
    .chat-list-container { height: 100vh; overflow-y: auto; background: #fff; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; }
    .chat-list-header { background: #ffffff; padding: 24px 24px 10px 24px; }
    .chat-search-filter-wrapper { background: #fff; padding: 0 24px 15px 24px; border-bottom: 1px solid #f8fafc; }
    .search-input-wrapper { position: relative; display: flex; align-items: center; background: #f8fafc !important; border-radius: 12px !important; padding: 0 16px !important; border: 1px solid #e2e8f0 !important; transition: all 0.3s ease; height: 44px !important; }
    .search-input-wrapper:focus-within { background: #fff !important; border-color: var(--palette-primary) !important; box-shadow: 0 4px 12px rgba(255,92,92,0.08) !important; }
    .search-input-wrapper .search-icon { color: #94a3b8 !important; margin-right: 10px !important; font-size: 0.85rem !important; display: inline-flex !important; align-items: center !important; }
    .search-input-wrapper input { border: none !important; background: transparent !important; box-shadow: none !important; height: 100% !important; font-size: 0.85rem !important; padding: 0 !important; color: #334155 !important; width: 100% !important; outline: none !important; }
    .search-input-wrapper .clear-search { color: #cbd5e0 !important; transition: color 0.2s !important; font-size: 0.85rem !important; padding-left: 5px !important; display: inline-flex !important; align-items: center !important; }
    .search-input-wrapper .clear-search:hover { color: #64748b !important; }
    #chat-list { flex: 1; overflow-y: auto; }
    .chat-list-user { cursor: pointer; transition: all 0.25s ease; border-left: 4px solid transparent; background-color: #ffffff; position: relative; padding: 22px 24px !important; }
    .chat-list-user:hover { background-color: #f8fafc; }
    .chat-list-user.active { background-color: rgba(255,92,92,0.05) !important; border-left-color: var(--palette-primary); }
    .chat-list-user.active .chat-client-name { color: var(--palette-primary) !important; }
    .badge-klien { background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .badge-tukang { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .badge-tech { background-color: #f5f3ff; color: #8b5cf6; border: 1px solid #ddd6fe; }
    .badge-acct { background-color: #ffedd5; color: #d97706; border: 1px solid #fed7aa; }
    .badge-gen { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .badge-supplier { background-color: #f3e8ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .pasangin-chat-header { padding: 16px 25px; background: #fff; border-bottom: 1px solid #f1f5f9; z-index: 10; }
    .pasangin-chat-content { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px; background-color: #f8fafc; background-image: radial-gradient(#e2e8f0 0.6px, transparent 0.6px); background-size: 18px 18px; min-height: 0; }
    .pasangin-chat-item { display: flex; width: 100%; align-items: flex-start; margin-bottom: 0; animation: slideUp 0.25s ease-out; flex-shrink: 0; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .pasangin-chat-item-body { max-width: 70%; min-width: 0; display: flex; flex-direction: column; }
    .pasangin-chat-text { padding: 10px 16px; font-size: 14px; line-height: 1.5; position: relative; width: fit-content; max-width: 100%; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; overflow-wrap: anywhere; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .chat-media-preview { display: block; width: 100%; max-width: 240px; max-height: 240px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .chat-media-preview:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .pasangin-chat-text:has(.chat-media-preview) { padding: 5px !important; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-text:has(.chat-media-preview) { border-radius: 12px 12px 12px 4px !important; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-text:has(.chat-media-preview) { border-radius: 12px 12px 4px 12px !important; }
    .chat-media-preview-container { position: relative; width: fit-content; max-width: 240px; border-radius: 8px; overflow: hidden; cursor: pointer; }
    .play-button-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.15); color: #fff; font-size: 20px; cursor: pointer; transition: background 0.3s ease; }
    .play-button-overlay i { background: rgba(15,23,42,0.75); width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding-left: 3px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: all 0.3s ease; }
    .chat-media-preview-container:hover .play-button-overlay i { transform: scale(1.1); background: var(--palette-primary); box-shadow: 0 6px 14px rgba(255,92,92,0.4); }
    .chat-lightbox-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.85); backdrop-filter: blur(8px); z-index: 99999; align-items: center; justify-content: center; animation: fadeIn 0.2s ease; }
    .chat-lightbox-overlay.active { display: flex; }
    .chat-lightbox-image-wrapper { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .chat-lightbox-overlay img { max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: zoomIn 0.25s ease; transform-origin: center center; transition: transform 0.1s ease-out; cursor: grab; user-select: none; -webkit-user-drag: none; }
    .chat-lightbox-overlay img:active { cursor: grabbing; }
    .chat-lightbox-close { position: absolute; top: 20px; right: 28px; color: #fff; font-size: 28px; cursor: pointer; opacity: 0.8; transition: opacity 0.2s, transform 0.2s; z-index: 100002; }
    .chat-lightbox-close:hover { opacity: 1; transform: scale(1.15); }
    .chat-lightbox-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; align-items: center; gap: 12px; background: rgba(15,23,42,0.85); backdrop-filter: blur(12px) saturate(180%); border: 1px solid rgba(255,255,255,0.1); padding: 8px 18px; border-radius: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.35); z-index: 100001; pointer-events: auto; }
    .chat-lightbox-controls button { background: transparent; border: none; color: #fff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .chat-lightbox-controls button:hover { background: rgba(255,255,255,0.15); transform: scale(1.1); }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    .pasangin-chat-time { font-size: 10px; margin-top: 5px; display: inline-flex; align-items: center; gap: 4px; font-weight: 500; }
    .pasangin-chat-item.pasangin-chat-left { justify-content: flex-start; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-text { background: #ffffff; color: #1e293b; border-radius: 16px 16px 16px 4px; border: 1px solid #e2e8f0; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-avatar { margin-right: 12px; order: 1; margin-top: 2px; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-item-body { order: 2; align-items: flex-start; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-time { color: #94a3b8; }
    .pasangin-chat-item.pasangin-chat-right { justify-content: flex-end; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-text { background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover, #ff3b3b) 100%); color: #fff; border-radius: 16px 16px 4px 16px; box-shadow: 0 4px 12px rgba(255,92,92,0.12); }
    .pasangin-chat-item.pasangin-chat-right.pasangin-chat-item-failed .pasangin-chat-text { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; box-shadow: 0 4px 12px rgba(239,68,68,0.2) !important; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-avatar { margin-right: 12px; order: 2; margin-top: 2px; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-item-body { order: 1; align-items: flex-end; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-time { color: rgba(255,255,255,0.8); justify-content: flex-end; }
    .pasangin-chat-avatar img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .chat-date-separator { display: flex; align-items: center; justify-content: center; margin: 12px 0; position: relative; }
    .chat-date-separator::before { content: ''; position: absolute; left: 0; right: 0; height: 1px; background: #e2e8f0; z-index: 1; }
    .chat-date-pill { background: #ffffff; border: 1px solid #e2e8f0; color: #64748b; font-size: 11px; font-weight: 600; padding: 5px 14px; border-radius: 20px; z-index: 2; box-shadow: 0 2px 4px rgba(0,0,0,0.02); text-transform: uppercase; letter-spacing: 0.5px; }
    .unread-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 18px; height: 18px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; font-size: 10px; font-weight: 700; border-radius: 10px; padding: 0 5px; box-shadow: 0 0 0 0 rgba(239,68,68,0.4); animation: pulse-unread 1.5s infinite; }
    @keyframes pulse-unread { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239,68,68,0.4); } 70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239,68,68,0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239,68,68,0); } }
    .pasangin-chat-footer { background: #fff; padding: 18px 25px; border-top: 1px solid #f1f5f9; }
    .input-wrapper { background: #f1f5f9; border-radius: 30px; padding: 4px 6px 4px 20px; display: flex; align-items: center; border: 1px solid #e2e8f0; transition: all 0.3s ease; }
    .input-wrapper:focus-within { background: #fff; border-color: var(--palette-primary); box-shadow: 0 4px 16px rgba(255,92,92,0.08); }
    .input-wrapper input { border: none !important; background: transparent !important; box-shadow: none !important; height: 44px; font-size: 14px; color: #334155; }
    .btn-send { width: 44px; height: 44px; border-radius: 50% !important; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover, #ff3b3b) 100%); border: none; color: #fff; box-shadow: 0 4px 10px rgba(255,92,92,0.3); }
    .btn-send:hover { transform: scale(1.05); box-shadow: 0 6px 14px rgba(255,92,92,0.4); background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover, #ff3b3b) 100%) !important; color: #fff !important; }
    .btn-send:focus, .btn-send:active { outline: none !important; background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover, #ff3b3b) 100%) !important; color: #fff !important; }
    .chat-loading-spinner { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; }
    @keyframes floating { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .floating-illustration { animation: floating 4s ease-in-out infinite; }
    .pasangin-chat-content::-webkit-scrollbar { width: 5px; } .pasangin-chat-content::-webkit-scrollbar-track { background: transparent; } .pasangin-chat-content::-webkit-scrollbar-thumb { background: rgba(255,92,92,0.4); border-radius: 10px; }
    .chat-list-container::-webkit-scrollbar, #chat-list::-webkit-scrollbar { width: 4px; } .chat-list-container::-webkit-scrollbar-thumb, #chat-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .pasangin-chat-content { scrollbar-width: thin; scrollbar-color: rgba(255,92,92,0.4) transparent; }
    .chat-list-container, #chat-list { scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
    #attachment-btn { background: transparent; border: none; outline: none; color: #64748b; cursor: pointer; padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.25s ease; margin-right: 5px; }
    #attachment-btn:hover { background: #e2e8f0; color: var(--palette-primary); }
    #attachment-preview-container { display: flex; align-items: center; justify-content: space-between; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px 16px; margin-bottom: 8px; animation: slideUp 0.2s ease-out; }
    @keyframes pulse-green { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16,185,129,0.5); } 70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16,185,129,0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16,185,129,0); } }
    .chat-section-wrapper .section-header { border-radius: 20px !important; margin-left: 0 !important; margin-right: 0 !important; margin-top: 0 !important; border: 1px solid rgba(255,92,92,0.08) !important; box-shadow: 0 20px 50px rgba(255,92,92,0.04), 0 5px 15px rgba(0,0,0,0.01) !important; background: #fff; padding: 16px 25px !important; }
    #project-info-panel { border-left: 4px solid #6366f1 !important; background: linear-gradient(to right, rgba(99,102,241,0.03), #ffffff) !important; animation: slideDown 0.2s ease-out; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    #btn-view-project { transition: all 0.2s ease !important; }
    #btn-view-project:hover { background-color: #6366f1 !important; color: #fff !important; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(99,102,241,0.25) !important; }
    @media (max-width: 767.98px) {
        .chat-list-container, .pasangin-chat-box { height: calc(100vh - 115px) !important; }
        .chat-card-container:not(.mobile-chat-active) .chat-list-container { display: flex !important; width: 100% !important; }
        .chat-card-container:not(.mobile-chat-active) .col-md-8.col-12 { display: none !important; }
        .chat-card-container.mobile-chat-active .chat-list-container { display: none !important; }
        .chat-card-container.mobile-chat-active .col-md-8.col-12 { display: block !important; width: 100% !important; }
        .pasangin-chat-header { padding: 12px 15px !important; }
        .pasangin-chat-footer { padding: 12px 15px !important; }
        .chat-list-header { padding: 16px 16px 8px 16px !important; }
        .chat-search-filter-wrapper { padding: 0 16px 12px 16px !important; }
        .chat-list-user { padding: 16px 16px !important; }
        #btn-back-to-list { display: flex !important; }
    }
    /* ========== CS PAGE SPECIFIC ========== */
    .page-type-badge-cs {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255, 92, 92, 0.08);
        color: var(--palette-primary);
        border: 1px solid rgba(255, 92, 92, 0.2);
        border-radius: 20px; padding: 4px 12px;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px;
    }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>
<section class="section chat-section-wrapper">
    <div class="section-header d-flex justify-content-between align-items-center py-3 mb-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="page-type-badge-cs"><i class="fas fa-headset me-1"></i>Customer Service</span>
            </div>
            <h1 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.5rem; color: #1e293b; margin: 0; line-height: 1.2;">Chat Customer Service</h1>
            <div class="text-muted mt-1" style="font-size: 0.8rem; font-weight: 500; letter-spacing: 0.2px;">Percakapan langsung dengan klien dan tukang</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 d-flex align-items-center" style="border-radius: 30px; font-weight: 600; font-size: 0.75rem; background-color: rgba(16,185,129,0.08); color: #10b981; border: 1px solid rgba(16,185,129,0.15);">
                <span class="me-2 d-inline-block" style="width: 7px; height: 7px; border-radius: 50%; background-color: #10b981; animation: pulse-green 1.5s infinite;"></span>
                Live
            </span>
        </div>

    </div>

    <div class="section-body">
        <div class="card chat-card-container">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Sidebar: Daftar User CS -->
                    <div class="col-md-4 col-12 chat-list-container">
                        <div class="chat-list-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark" style="font-size: 1rem;"><i class="fas fa-headset text-danger me-2"></i>CS Chat</h6>
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <?php
                                $totalUnread = 0;
                                foreach ($conversations ?? [] as $c) { $totalUnread += intval($c['unread_by_admin_count'] ?? 0); }
                                ?>
                                <span id="total-unread-badge" class="badge px-2 py-1 text-danger"
                                    style="font-size: 0.72rem; border-radius: 12px; background-color: #fee2e2; font-weight: 600; <?= $totalUnread > 0 ? '' : 'display: none;' ?>">
                                    <?= $totalUnread ?> Belum Dibaca
                                </span>
                                <span class="badge px-2 py-1"
                                    style="font-size: 0.72rem; border-radius: 12px; background-color: rgba(255,92,92,0.08); color: var(--palette-primary); border: 1px solid rgba(255,92,92,0.15); font-weight: 600;">
                                    <?= count($conversations ?? []) ?> Percakapan
                                </span>
                            </div>
                        </div>

                        <div class="chat-search-filter-wrapper">
                            <div class="search-input-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="chat-search" placeholder="Cari nama klien/tukang..." autocomplete="off">
                                <i class="fas fa-times clear-search" id="clear-search" style="display: none; cursor: pointer;"></i>
                            </div>
                            <?php if (can('super_admin_override') || count($allowedCategories ?? []) > 1): ?>
                            <div class="category-select-wrapper mt-2">
                                <select id="chat-category-select" class="form-select form-select-sm"
                                    style="font-size: 0.75rem; font-weight: 600; color: #475569; border-radius: 10px; border: 1px solid #e2e8f0; padding: 8px 12px; background-color: #ffffff; cursor: pointer;">
                                    <option value="all">Semua Departemen</option>
                                    <?php if (can('super_admin_override') || in_array('technical', $allowedCategories ?? [])): ?>
                                        <option value="technical">Technical</option>
                                    <?php endif; ?>
                                    <?php if (can('super_admin_override') || in_array('accounting', $allowedCategories ?? [])): ?>
                                        <option value="accounting">Accounting</option>
                                    <?php endif; ?>
                                    <?php if (can('super_admin_override') || in_array('general', $allowedCategories ?? [])): ?>
                                        <option value="general">General</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div id="chat-list">
                            <ul class="list-unstyled mb-0">
                                <?php if (!empty($conversations)): ?>
                                    <?php foreach ($conversations as $convo): ?>
                                        <?php
                                        if ($convo['client_type'] === 'tukang') {
                                             $badgeClass = 'badge-tukang';
                                             $badgeText = 'Tukang';
                                         } elseif ($convo['client_type'] === 'supplier') {
                                             $badgeClass = 'badge-supplier';
                                             $badgeText = 'Supplier';
                                         } else {
                                             $badgeClass = 'badge-klien';
                                             $badgeText = 'Klien';
                                         }
                                         $timeStr = '';
                                         if (!empty($convo['last_message_at'])) {
                                             $timeStr = date('H:i', strtotime($convo['last_message_at']));
                                             if (date('Y-m-d', strtotime($convo['last_message_at'])) !== date('Y-m-d')) {
                                                 $timeStr = date('d M', strtotime($convo['last_message_at']));
                                             }
                                         }
                                         $lastMsg = esc($convo['last_message_preview'] ?? 'Belum ada riwayat pesan');
                                         $cat = $convo['category'] ?? 'general';
                                         if ($cat === 'technical') { $catBadgeClass = 'badge-tech'; $catText = 'Technical'; }
                                         elseif ($cat === 'accounting') { $catBadgeClass = 'badge-acct'; $catText = 'Accounting'; }
                                         else { $catBadgeClass = 'badge-gen'; $catText = 'General'; }
                                         $avatarUrl = '';
                                         if (!empty($convo['client_avatar'])) {
                                             if ($convo['client_type'] === 'tukang') { 
                                                 $avatarUrl = base_url('uploads/tukang/' . $convo['client_avatar']); 
                                             } elseif ($convo['client_type'] === 'supplier') {
                                                 $avatarUrl = strpos($convo['client_avatar'], 'http') === 0 ? $convo['client_avatar'] : base_url('uploads/supplier/' . $convo['client_avatar']); 
                                             } else { 
                                                 $avatarUrl = strpos($convo['client_avatar'], 'http') === 0 ? $convo['client_avatar'] : base_url('uploads/profile/' . $convo['client_avatar']); 
                                             }
                                         } else { $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($convo['client_name'] ?? 'User') . "&background=random&color=fff"; }
                                        ?>
                                        <li class="d-flex chat-list-user p-3 align-items-center border-bottom"
                                            data-id="<?= $convo['id'] ?>"
                                            data-chat-type="cs"
                                            data-name="<?= esc($convo['client_name']) ?>"
                                            data-type="<?= esc($convo['client_type']) ?>"
                                            data-avatar="<?= esc($convo['client_avatar'] ?? '') ?>"
                                            data-status="<?= esc($convo['status'] ?? 'open') ?>"
                                            data-title="<?= esc($convo['title'] ?? 'Obrolan') ?>"
                                            data-category="<?= esc($cat) ?>"
                                            data-supplier-id="">
                                            <img class="me-3 rounded-circle border shadow-sm" width="48" height="48" src="<?= $avatarUrl ?>" alt="avatar">
                                            <div class="flex-grow-1" style="overflow: hidden;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h6 class="mt-0 mb-0 fw-bold text-truncate text-dark chat-client-name" style="font-size: 0.92rem; max-width: 65%;">
                                                        <?= esc($convo['client_name']) ?>
                                                        <?php if (($convo['status'] ?? 'open') === 'closed'): ?>
                                                            <i class="fas fa-lock text-danger ms-1" style="font-size: 0.75rem;" title="Tertutup"></i>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <span class="text-small text-muted chat-item-time" style="font-size: 0.72rem; font-weight: 500;"><?= $timeStr ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="text-small text-muted text-truncate mb-0 chat-item-preview" style="max-width: 70%; font-size: 0.8rem;"><?= $lastMsg ?></p>
                                                    <div class="d-flex align-items-center" style="gap: 4px;">
                                                        <?php $unreadCount = intval($convo['unread_by_admin_count'] ?? 0); ?>
                                                        <span class="unread-badge me-2" id="unread-badge-<?= $convo['id'] ?>" <?= $unreadCount > 0 ? '' : 'style="display: none;"' ?>><?= $unreadCount ?></span>
                                                        <span class="badge <?= $catBadgeClass ?> px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $catText ?></span>
                                                        <span class="badge <?= $badgeClass ?> px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $badgeText ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-center p-5 text-muted">
                                        <i class="fas fa-headset fa-2x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada percakapan CS.</p>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Panel Kanan: Chat Box -->
                    <?= $this->include('App\Modules\Chat\Views\components\_chat_box') ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<?= $this->include('App\Modules\Chat\Views\components\_lightbox') ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('script'); ?>
<?= $this->include('App\Modules\Chat\Views\components\_scripts') ?>
<?= $this->include('App\Modules\Chat\Views\components\_lightbox_scripts') ?>
<?php echo $this->endSection(); ?>
