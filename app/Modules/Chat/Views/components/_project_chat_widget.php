<?php
/**
 * Komponen Reusable Widget Chat Proyek (Sisi Admin)
 * Digunakan untuk disematkan di halaman detail proyek (Desain/Konstruksi/Renovasi).
 * 
 * @var int $projectId ID dari proyek (desain/construction/renovation_requests)
 * @var string $projectType Tipe proyek ('design', 'construction', 'renovation')
 */
?>

<!-- 1. Floating Action Button (FAB) -->
<button type="button" id="project-chat-fab" class="btn-chat-fab" title="Diskusi Klien Proyek">
    <i class="fas fa-comments"></i>
    <span id="project-chat-badge" style="display: none;"></span>
</button>

<!-- 2. Chat Widget Window Box -->
<div id="project-chat-window" class="chat-widget-box" style="display: none;">
    <!-- Header Widget -->
    <div class="chat-widget-header">
        <div class="d-flex align-items-center" style="gap: 10px;">
            <img id="widget-client-avatar" src="https://ui-avatars.com/api/?name=Klien&background=FF5C5C&color=fff" class="rounded-circle border" width="34" height="34" alt="avatar">
            <div style="overflow: hidden; max-width: 180px;">
                <h6 id="widget-client-name" class="m-0 fw-bold text-truncate text-white" style="font-size: 0.85rem;">Memuat Klien...</h6>
                <div class="d-flex align-items-center" style="gap: 4px; font-size: 0.68rem; opacity: 0.85;">
                    <span class="widget-status-dot"></span>
                    <span id="widget-project-title" class="text-truncate">Detail Proyek</span>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <button type="button" id="widget-btn-minimize" class="widget-header-btn" title="Minimalkan"><i class="fas fa-minus"></i></button>
            <button type="button" id="widget-btn-close" class="widget-header-btn" title="Tutup"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <!-- Body Pesan -->
    <div id="widget-chat-messages" class="chat-widget-body">
        <div class="text-center my-auto py-5 text-muted" id="widget-chat-loading">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="mt-2" style="font-size: 0.75rem;">Menghubungkan obrolan...</div>
        </div>
    </div>

    <!-- Area Preview Lampiran -->
    <div id="widget-attachment-preview" class="p-2 border-top bg-light align-items-center justify-content-between" style="display: none; font-size: 0.78rem;">
        <div class="d-flex align-items-center text-truncate" style="gap: 6px;">
            <i id="widget-preview-icon" class="fas fa-file text-primary"></i>
            <span id="widget-preview-name" class="text-truncate" style="max-width: 200px; font-weight: 500;">nama_file.jpg</span>
        </div>
        <button type="button" id="widget-btn-clear-attachment" class="btn btn-sm text-danger p-0" style="font-size: 0.9rem;"><i class="fas fa-times-circle"></i></button>
    </div>

    <!-- Footer Form Input -->
    <form id="widget-chat-form" class="chat-widget-footer" autocomplete="off" enctype="multipart/form-data">
        <input type="file" id="widget-file-input" style="display: none;" accept="image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
        <div class="widget-input-wrapper">
            <button type="button" id="widget-btn-attachment" class="widget-footer-btn" title="Lampirkan File"><i class="fas fa-paperclip"></i></button>
            <input type="text" id="widget-message-input" placeholder="Tulis pesan proyek...">
        </div>
        <button type="submit" id="widget-btn-send" class="widget-send-btn" title="Kirim"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>

<!-- Include Lightbox HTML -->
<?= view('App\Modules\Chat\Views\components\_lightbox') ?>

<!-- 3. Styling CSS untuk Widget Melayang -->
<style>
    /* Floating Action Button (FAB) */
    .btn-chat-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF5C5C, #ff3b3b);
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(255, 92, 92, 0.5);
        cursor: pointer;
        z-index: 1040;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-chat-fab:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 10px 28px rgba(255, 92, 92, 0.6);
    }
    .btn-chat-fab:active {
        transform: scale(0.95);
    }
    .btn-chat-fab i {
        font-size: 24px;
    }
    #project-chat-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        padding: 0 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        border: 2px solid #fff;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        animation: badge-pulse 1.5s infinite;
    }
    @keyframes badge-pulse {
        0%   { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70%  { transform: scale(1);    box-shadow: 0 0 0 7px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    /* Chat Widget Box */
    .chat-widget-box {
        position: fixed;
        bottom: 102px;
        right: 30px;
        width: 420px;
        height: 560px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(255, 92, 92, 0.08), 0 8px 24px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 92, 92, 0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 1040;
        animation: widgetSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes widgetSlideUp {
        from {
            opacity: 0;
            transform: translateY(24px) scale(0.94);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    @keyframes widgetSlideDown {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateY(20px) scale(0.93);
        }
    }
    .chat-widget-box.closing {
        animation: widgetSlideDown 0.28s cubic-bezier(0.4, 0, 1, 1) forwards;
        pointer-events: none;
    }

    /* Header */
    .chat-widget-header {
        background: linear-gradient(135deg, #FF5C5C 0%, #ff3b3b 100%);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
        flex-shrink: 0;
    }
    .widget-status-dot {
        width: 8px;
        height: 8px;
        background-color: #a7f3d0;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(167, 243, 208, 0.3);
        animation: pulse-status 2s infinite;
    }
    @keyframes pulse-status {
        0%, 100% { box-shadow: 0 0 0 3px rgba(167, 243, 208, 0.3); }
        50% { box-shadow: 0 0 0 5px rgba(167, 243, 208, 0.15); }
    }
    .widget-header-btn {
        background: rgba(255,255,255,0.15);
        border: none;
        color: rgba(255,255,255,0.9);
        padding: 6px 8px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .widget-header-btn:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }

    /* Body */
    .chat-widget-body {
        flex-grow: 1;
        padding: 16px;
        overflow-y: auto;
        background-color: #fff9f9;
        background-image: radial-gradient(#fecaca 0.6px, transparent 0.6px);
        background-size: 18px 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 0;
    }
    .chat-widget-body::-webkit-scrollbar { width: 4px; }
    .chat-widget-body::-webkit-scrollbar-track { background: transparent; }
    .chat-widget-body::-webkit-scrollbar-thumb { background: rgba(255, 92, 92, 0.35); border-radius: 10px; }
    .chat-widget-body::-webkit-scrollbar-thumb:hover { background: rgba(255, 92, 92, 0.6); }
    .chat-widget-body { scrollbar-width: thin; scrollbar-color: rgba(255, 92, 92, 0.35) transparent; }

    /* Bubbles */
    .widget-msg-row {
        display: flex;
        gap: 8px;
        max-width: 75%;
        min-width: 0;
        animation: widgetBubbleIn 0.22s ease-out;
    }
    @keyframes widgetBubbleIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .widget-msg-left {
        align-self: flex-start;
    }
    .widget-msg-right {
        align-self: flex-end;
        flex-direction: row-reverse;
        max-width: 75%;
    }
    .widget-bubble {
        padding: 9px 13px;
        border-radius: 16px;
        font-size: 0.82rem;
        line-height: 1.45;
        position: relative;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        overflow-wrap: anywhere;
        min-width: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .widget-msg-left .widget-bubble {
        background: #fff;
        color: #1e293b;
        border-radius: 16px 16px 16px 4px;
        border: 1px solid #e2e8f0;
    }
    .widget-msg-right .widget-bubble {
        background: linear-gradient(135deg, #FF5C5C 0%, #ff3b3b 100%);
        color: #fff;
        border-radius: 16px 16px 4px 16px;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
    }
    .widget-bubble-time {
        display: block;
        font-size: 0.62rem;
        margin-top: 4px;
        color: #94a3b8;
        font-weight: 500;
        padding: 0 4px;
        background: transparent;
        line-height: 1;
    }
    /* Wrapper seluruh baris pesan termasuk waktu */
    .widget-msg-wrapper {
        display: flex;
        flex-direction: column;
        max-width: 75%;
        min-width: 0;
    }
    .widget-msg-wrapper.widget-wrapper-right {
        align-self: flex-end;
        align-items: flex-end;
    }
    .widget-msg-wrapper.widget-wrapper-left {
        align-self: flex-start;
        align-items: flex-start;
    }
    .widget-msg-wrapper.widget-wrapper-right .widget-bubble-time {
        text-align: right;
    }
    .widget-msg-wrapper.widget-wrapper-left .widget-bubble-time {
        text-align: left;
    }
    /* Date Separator */
    .widget-date-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px 0 6px;
        position: relative;
    }
    .widget-date-separator::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 1px;
        background: #e2e8f0;
        z-index: 1;
    }
    .widget-date-pill {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 0.62rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        z-index: 2;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .widget-bubble-media {
        display: block;
        width: 100%;
        max-width: 200px;
        max-height: 180px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.08);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .widget-bubble-media:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    /* Kurangi padding bubble jika hanya media */
    .widget-msg-right .widget-bubble:has(.widget-bubble-media) {
        padding: 5px !important;
    }
    .widget-msg-left .widget-bubble:has(.widget-bubble-media) {
        padding: 5px !important;
    }

    /* Footer Form */
    .chat-widget-footer {
        padding: 12px 14px;
        background: #fff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chat-widget-footer .widget-input-wrapper {
        flex-grow: 1;
        background: #f1f5f9;
        border-radius: 30px;
        padding: 3px 6px 3px 16px;
        display: flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .chat-widget-footer .widget-input-wrapper:focus-within {
        background: #fff;
        border-color: #FF5C5C;
        box-shadow: 0 4px 14px rgba(255, 92, 92, 0.12);
    }
    .chat-widget-footer input[type="text"] {
        flex-grow: 1;
        border: none;
        background: transparent;
        box-shadow: none;
        padding: 7px 6px;
        font-size: 0.82rem;
        outline: none;
        color: #334155;
    }
    .chat-widget-footer input[type="text"]:focus {
        background: transparent;
    }
    .widget-footer-btn {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 6px;
        border-radius: 50%;
        font-size: 1.05rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .widget-footer-btn:hover {
        background: #fee2e2;
        color: #FF5C5C;
    }
    .widget-footer-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .widget-send-btn {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF5C5C 0%, #ff3b3b 100%);
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.35);
        font-size: 0.95rem;
    }
    .widget-send-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(255, 92, 92, 0.5);
    }
    .widget-send-btn:disabled {
        background: #fca5a5;
        cursor: not-allowed;
        transform: none;
    }
    @keyframes widget-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .widget-send-btn.sending i {
        animation: widget-spin 0.6s linear infinite;
    }
    .widget-msg-sending {
        opacity: 0.55;
        font-style: italic;
        font-size: 0.75rem;
    }

    /* Responsive Mobile refinements */
    @media (max-width: 575.98px) {
        .chat-widget-box {
            bottom: 0 !important;
            right: 0 !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0 !important;
        }
        .btn-chat-fab {
            bottom: 20px;
            right: 20px;
        }
    }

    /* Lightbox overlay untuk melihat gambar ukuran penuh */
    .chat-lightbox-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease;
    }
    .chat-lightbox-overlay.active {
        display: flex;
    }
    .chat-lightbox-image-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .chat-lightbox-overlay img {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        animation: zoomIn 0.25s ease;
        transform-origin: center center;
        transition: transform 0.1s ease-out;
        cursor: grab;
        user-select: none;
        -webkit-user-drag: none;
    }
    .chat-lightbox-overlay img:active {
        cursor: grabbing;
    }
    .chat-lightbox-close {
        position: absolute;
        top: 20px;
        right: 28px;
        color: #ffffff;
        font-size: 28px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s, transform 0.2s;
        z-index: 100002;
    }
    .chat-lightbox-close:hover {
        opacity: 1;
        transform: scale(1.15);
    }
    .chat-lightbox-controls {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 8px 18px;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        z-index: 100001;
        pointer-events: auto;
    }
    .chat-lightbox-controls button {
        background: transparent;
        border: none;
        color: #ffffff;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .chat-lightbox-controls button:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.1);
    }
    .chat-lightbox-controls button:active {
        transform: scale(0.95);
    }

    /* Media Preview Container & Play Button Overlay */
    .widget-media-preview-container {
        position: relative;
        width: fit-content;
        max-width: 100%;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
    }
    .widget-play-button-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.15);
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .widget-play-button-overlay i {
        background: rgba(15, 23, 42, 0.75);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 3px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    .widget-media-preview-container:hover .widget-play-button-overlay i {
        transform: scale(1.1);
        background: #6366f1;
        box-shadow: 0 6px 14px rgba(99, 102, 241, 0.45);
    }

    .widget-bubble-media {
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .widget-bubble-media:hover {
        opacity: 0.9;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<!-- 4. Logic Script (AJAX + real-time listeners) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const projId = <?= json_encode($projectId) ?>;
    const projType = <?= json_encode($projectType) ?>;
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';
    
    let widgetConvoId = null;
    let widgetIsOpen = false;
    let widgetPoller = null;

    // Inisialisasi obrolan proyek
    function initWidgetConversation() {
        $.ajax({
            url: '<?= site_url("admin/api/chat/project/create") ?>',
            method: 'POST',
            data: {
                project_id: projId,
                project_type: projType,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if (response.csrf_hash) csrfHash = response.csrf_hash;
                if (response.status === true) {
                    widgetConvoId = response.conversation_id;
                    
                    // Muat info klien dari response jika ada atau biarkan default detail
                    fetchClientInfo();
                    resetWidgetRenderState();
                    loadWidgetMessages(false);
                    startWidgetPolling();
                } else {
                    console.error("Gagal inisialisasi chat widget:", response.message);
                    $('#widget-chat-loading').html(`
                        <div class="text-danger py-4 px-2">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <div style="font-size: 0.78rem; font-weight: 600;">Gagal memuat obrolan:</div>
                            <div class="text-muted mt-1" style="font-size: 0.72rem; line-height: 1.3;">${escapeHtml(response.message || 'Error internal')}</div>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.csrf_hash) csrfHash = xhr.responseJSON.csrf_hash;
                console.error("Kesalahan inisialisasi chat widget:", xhr);
                const errMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Koneksi gagal atau kesalahan server.';
                $('#widget-chat-loading').html(`
                    <div class="text-danger py-4 px-2">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <div style="font-size: 0.78rem; font-weight: 600;">Gagal memuat obrolan:</div>
                        <div class="text-muted mt-1" style="font-size: 0.72rem; line-height: 1.3;">${escapeHtml(errMsg)}</div>
                    </div>
                `);
            }
        });
    }

    function fetchClientInfo() {
        if (!widgetConvoId) return;
        
        // Dapatkan data percakapan secara efisien dari single info endpoint
        $.ajax({
            url: `<?= site_url("admin/api/chat/project") ?>/${widgetConvoId}/info`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === true && response.data) {
                    const currentConvo = response.data;
                    $('#widget-client-name').text(currentConvo.client_name || 'Klien');
                    
                    let avatarUrl = '';
                    if (currentConvo.client_avatar) {
                        avatarUrl = currentConvo.client_avatar.startsWith('http') 
                            ? currentConvo.client_avatar 
                            : `<?= base_url('uploads/profile') ?>/${currentConvo.client_avatar}`;
                    } else {
                        avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(currentConvo.client_name || 'Klien')}&background=FF5C5C&color=fff`;
                    }
                    $('#widget-client-avatar').attr('src', avatarUrl);
                    
                    let labelProyek = 'Detail Proyek';
                    if (projType === 'design') labelProyek = 'Desain';
                    else if (projType === 'construction') labelProyek = 'Konstruksi';
                    else if (projType === 'renovation') labelProyek = 'Renovasi';
                    
                    $('#widget-project-title').text(`${labelProyek} #${projId}`);
                    
                    // Selalu update badge unread (terlepas widget terbuka/tertutup)
                    const unreadCount = parseInt(currentConvo.unread_by_admin_count || 0);
                    updateWidgetUnreadBadge(unreadCount);
                }
            }
        });
    }

    function updateWidgetUnreadBadge(count) {
        const badge = $('#project-chat-badge');
        if (count > 0 && !widgetIsOpen) {
            badge.text(count > 99 ? '99+' : count).css('display', 'flex');
        } else {
            badge.css('display', 'none');
        }
    }
    // State render incremental
    let lastRenderedMsgId = null;
    let lastRenderedDateKey = null;
    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    function resetWidgetRenderState() {
        lastRenderedMsgId = null;
        lastRenderedDateKey = null;
        $('#widget-chat-messages').empty();
    }

    function loadWidgetMessages(shouldScroll = true) {
        if (!widgetConvoId) return;

        const afterId = lastRenderedMsgId || '';
        $.ajax({
            url: `<?= site_url("admin/api/chat/project") ?>/${widgetConvoId}/messages`,
            method: 'GET',
            data: { after_id: afterId },
            dataType: 'json',
            success: function(response) {
                if (response.status === true && response.data) {
                    $('#widget-chat-loading').remove();
                    const messages = response.data || [];
                    appendNewWidgetMessages(messages, shouldScroll);
                }
            }
        });
    }

    function buildMsgHtml(msg) {
        const isMe = (msg.sender_type === 'admin');
        const rowClass = isMe ? 'widget-msg-right' : 'widget-msg-left';
        const wrapperSide = isMe ? 'widget-wrapper-right' : 'widget-wrapper-left';

        let fileUrl = msg.file_url;
        if (fileUrl && !fileUrl.startsWith('http://') && !fileUrl.startsWith('https://')) {
            const baseUrl = '<?= base_url() ?>'.replace(/\/$/, '');
            fileUrl = baseUrl + '/' + fileUrl.replace(/^\//, '');
        }

        let messageContent = '';
        if (msg.message_type === 'image' && fileUrl) {
            messageContent = `<img src="${fileUrl}" class="widget-bubble-media" onclick="openChatLightbox(this.src, 'image')">` ;
            if (msg.body) messageContent += `<div style="margin-top:6px;">${escapeHtml(msg.body)}</div>`;
        } else if (msg.message_type === 'video' && fileUrl) {
            messageContent = `
                <div class="widget-media-preview-container" onclick="openChatLightbox(this.querySelector('video').src, 'video')">
                    <video src="${fileUrl}" class="widget-bubble-media" style="max-height:160px; width:100%; object-fit:cover;"></video>
                    <div class="widget-play-button-overlay"><i class="fas fa-play"></i></div>
                </div>`;
            if (msg.body) messageContent += `<div style="margin-top:6px;">${escapeHtml(msg.body)}</div>`;
        } else if (msg.message_type === 'file' && fileUrl) {
            messageContent = `
                <a href="${fileUrl}" target="_blank" class="d-flex align-items-center p-2 rounded bg-light border text-dark text-decoration-none" style="font-size:0.75rem;">
                    <i class="fas fa-file me-2 text-primary"></i>
                    <span class="text-truncate" style="max-width:140px; font-weight:500;">${escapeHtml(msg.file_name || 'Unduh Berkas')}</span>
                </a>`;
            if (msg.body) messageContent += `<div style="margin-top:6px;">${escapeHtml(msg.body)}</div>`;
        } else {
            messageContent = `<div>${formatWidgetMessageBody(msg.body)}</div>`;
        }

        const timeStr = formatWidgetTime(msg.created_at);
        return `
            <div class="widget-msg-wrapper ${wrapperSide}" data-msg-id="${msg.id}">
                <div class="widget-msg-row ${rowClass}" style="max-width:100%;">
                    <div class="widget-bubble">${messageContent}</div>
                </div>
                <small class="widget-bubble-time">${timeStr}</small>
            </div>
        `;
    }

    function appendNewWidgetMessages(messages, shouldScroll) {
        const container = $('#widget-chat-messages');
        const isNearBottom = container[0].scrollHeight - container.scrollTop() - container.outerHeight() < 80;

        if (messages.length === 0) {
            if (container.children().length === 0) {
                container.html(`
                    <div class="text-center my-auto text-muted py-5" style="font-size: 0.78rem;">
                        <i class="far fa-comments fa-2x mb-2 text-muted" style="opacity: 0.5;"></i>
                        <div>Belum ada percakapan. Silakan mulai chat!</div>
                    </div>
                `);
            }
            return;
        }

        // Hanya append pesan yang BELUM di-render
        const newMessages = lastRenderedMsgId === null
            ? messages
            : messages.filter(m => parseInt(m.id) > parseInt(lastRenderedMsgId));

        if (newMessages.length === 0) return;

        // Hapus placeholder "belum ada percakapan" jika ada
        container.find('.text-center.my-auto').remove();

        newMessages.forEach(function(msg) {
            // Date separator
            const msgDate = new Date(msg.created_at);
            const dateKey = `${msgDate.getFullYear()}-${msgDate.getMonth()}-${msgDate.getDate()}`;
            if (dateKey !== lastRenderedDateKey) {
                lastRenderedDateKey = dateKey;
                const dayNum = msgDate.getDate();
                const monthStr = monthNames[msgDate.getMonth()];
                const yearNum = msgDate.getFullYear();
                container.append(`
                    <div class="widget-date-separator">
                        <span class="widget-date-pill">${dayNum} ${monthStr} ${yearNum}</span>
                    </div>
                `);
            }

            container.append(buildMsgHtml(msg));
            lastRenderedMsgId = msg.id;
        });

        if (shouldScroll || isNearBottom) {
            container.scrollTop(container[0].scrollHeight);
        }
    }

    // Format tampilan waktu pesan
    function formatWidgetTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2);
    }

    // Pengubah format link & escape HTML pada body teks
    function formatWidgetMessageBody(text) {
        if (!text) return '';
        let escaped = escapeHtml(text);
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        return escaped.replace(urlRegex, function(url) {
            return `<a href="${url}" target="_blank" class="text-decoration-underline text-reset">${url}</a>`;
        });
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Fungsi helper untuk menutup widget dengan animasi
    function closeWidget() {
        const win = $('#project-chat-window');
        win.addClass('closing');
        setTimeout(function() {
            win.hide().removeClass('closing');
            widgetIsOpen = false;
            startWidgetPolling();
        }, 290);
    }

    // Toggle Tampilan Widget Window
    $('#project-chat-fab').on('click', function() {
        if (!widgetConvoId) {
            initWidgetConversation();
        }

        const win = $('#project-chat-window');
        if (win.is(':visible')) {
            closeWidget();
        } else {
            win.show();
            widgetIsOpen = true;
            updateWidgetUnreadBadge(0); // Sembunyikan badge saat dibuka
            loadWidgetMessages(true);
            startWidgetPolling();

            // Set status read ke admin
            if (widgetConvoId) {
                $.ajax({
                    url: `<?= site_url("admin/api/chat/project") ?>/${widgetConvoId}/status`,
                    method: 'POST',
                    data: { status: 'open', [csrfName]: csrfHash },
                    dataType: 'json',
                    success: function(response) {
                        if (response.csrf_hash) csrfHash = response.csrf_hash;
                    }
                });
            }
        }
    });

    $('#widget-btn-minimize, #widget-btn-close').on('click', function() {
        closeWidget();
    });

    // Handle Upload attachment button
    $('#widget-btn-attachment').on('click', function() {
        $('#widget-file-input').click();
    });

    $('#widget-file-input').on('change', function() {
        const fileInput = this;
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            $('#widget-preview-name').text(file.name);
            
            const icon = $('#widget-preview-icon');
            icon.removeClass();
            if (file.type.startsWith('image/')) {
                icon.addClass('fas fa-file-image text-success');
            } else if (file.type.startsWith('video/')) {
                icon.addClass('fas fa-file-video text-warning');
            } else {
                icon.addClass('fas fa-file-alt text-primary');
            }
            
            $('#widget-attachment-preview').css('display', 'flex');
        } else {
            $('#widget-attachment-preview').hide();
        }
    });

    $('#widget-btn-clear-attachment').on('click', function() {
        $('#widget-file-input').val('');
        $('#widget-attachment-preview').hide();
    });

    // Kirim Pesan via AJAX Form Submit
    $('#widget-chat-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!widgetConvoId) return;

        const input = $('#widget-message-input');
        const text = input.val().trim();
        const fileInput = $('#widget-file-input')[0];
        const hasFile = fileInput && fileInput.files.length > 0;

        if (text === '' && !hasFile) return;

        // Siapkan FormData
        const formData = new FormData();
        formData.append('conversation_id', widgetConvoId);
        formData.append('message', text);
        formData.append(csrfName, csrfHash);

        if (hasFile) {
            const file = fileInput.files[0];
            formData.append('file', file);
            
            let attachmentType = 'file';
            if (file.type.startsWith('image/')) attachmentType = 'image';
            else if (file.type.startsWith('video/')) attachmentType = 'video';
            formData.append('attachment_type', attachmentType);
        }

        // Reset input form
        input.val('');
        $('#widget-file-input').val('');
        $('#widget-attachment-preview').hide();

        // === LOADING STATE ===
        const btnSend = $('#widget-btn-send');
        const msgInput = $('#widget-message-input');
        const btnAttach = $('#widget-btn-attachment');

        btnSend.prop('disabled', true).addClass('sending').html('<i class="fas fa-circle-notch"></i>');
        msgInput.prop('disabled', true);
        btnAttach.prop('disabled', true);

        // Tambahkan bubble "sedang mengirim" sementara di chat
        const tempId = 'widget-sending-' + Date.now();
        const tempBubble = `
            <div id="${tempId}" class="widget-msg-row widget-msg-right widget-msg-sending">
                <div class="widget-bubble">
                    <div>${hasFile ? '📎 Mengunggah berkas...' : escapeHtml(text || '')}</div>
                    <small class="widget-bubble-time"><i class="fas fa-clock" style="font-size:0.6rem;"></i> Mengirim...</small>
                </div>
            </div>
        `;
        const container = $('#widget-chat-messages');
        container.append(tempBubble);
        container.scrollTop(container[0].scrollHeight);

        const resetSendBtn = function() {
            btnSend.prop('disabled', false).removeClass('sending').html('<i class="fas fa-paper-plane"></i>');
            msgInput.prop('disabled', false).focus();
            btnAttach.prop('disabled', false);
        };

        $.ajax({
            url: '<?= site_url("admin/api/chat/project/send") ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.csrf_hash) csrfHash = response.csrf_hash;
                $('#' + tempId).remove();
                resetSendBtn();
                if (response.status === true) {
                    loadWidgetMessages(true);
                } else {
                    console.error("Gagal mengirim pesan widget:", response.message);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.csrf_hash) csrfHash = xhr.responseJSON.csrf_hash;
                $('#' + tempId).remove();
                resetSendBtn();
                console.error("Kesalahan pengiriman pesan widget:", xhr);
            }
        });
    });

    // Polling berkala (Dinamis: 30s saat terbuka, 60s saat tertutup sebagai fallback FCM)
    function startWidgetPolling() {
        if (widgetPoller) clearInterval(widgetPoller);
        const intervalMs = widgetIsOpen ? 30000 : 60000;
        widgetPoller = setInterval(function() {
            if (widgetConvoId) {
                fetchClientInfo(); // Selalu cek unread count
                if (widgetIsOpen) {
                    loadWidgetMessages(false);
                }
            }
        }, intervalMs);
    }

    // FCM real-time push update listener
    window.addEventListener('fcm_chat_received', function(e) {
        const payload = e.detail;
        if (!payload || !payload.data) return;
        
        if (payload.data.type === 'project_chat') {
            const incomingConvoId = payload.data.project_conversation_id;
            if (incomingConvoId == widgetConvoId) {
                if (widgetIsOpen) {
                    loadWidgetMessages(true);
                    
                    // Reset read status
                    $.ajax({
                        url: `<?= site_url("admin/api/chat/project") ?>/${widgetConvoId}/status`,
                        method: 'POST',
                        data: { status: 'open', [csrfName]: csrfHash },
                        dataType: 'json',
                        success: function(response) {
                            if (response.csrf_hash) csrfHash = response.csrf_hash;
                        }
                    });
                } else {
                    fetchClientInfo();
                }
            }
        }
    });

    // Jalankan init obrolan saat halaman termuat
    initWidgetConversation();
});
</script>

<!-- Include Lightbox JS Scripts -->
<?= view('App\Modules\Chat\Views\components\_lightbox_scripts') ?>
