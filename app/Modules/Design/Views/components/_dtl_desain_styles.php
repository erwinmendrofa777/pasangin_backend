<style>
    /* ── Upload dropzone area ── */
    .upload-card {
        border: 2px dashed #c9d1db;
        border-radius: 14px;
        background: #fafbfc;
        transition: border-color .2s, background .2s;
    }

    .upload-card:hover {
        border-color: var(--palette-primary);
        background: #f0f2ff;
    }

    /* ── Custom File Input ── */
    .file-upload-box {
        border: 1.5px solid #e4e9f0;
        border-radius: 10px;
        background: #fff;
        height: 42px;
        display: flex;
        align-items: center;
        padding: 0 15px;
        cursor: pointer;
        transition: all .2s;
        position: relative;
        overflow: hidden;
    }

    .file-upload-box:hover {
        border-color: var(--palette-primary);
        background: #f8f9ff;
    }

    .file-upload-box input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .file-upload-box .file-label {
        font-size: 13px;
        color: #6c757d;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 30px;
    }

    .file-upload-box i {
        color: #adb5bd;
        transition: color .2s;
    }

    .file-upload-box:hover i {
        color: var(--palette-primary);
    }

    /* ── Premium Design Card ── */
    .design-card {
        font-family: 'Plus Jakarta Sans', sans-serif;
        border-radius: 14px;
        overflow: visible; /* Diperlukan agar tumpukan stacked card terlihat */
        border: 1px solid #f1f5f9;
        background: #fff;
        transition: transform .3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .3s ease;
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 2px 4px rgba(0, 0, 0, 0.01);
    }

    .design-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px rgba(255, 92, 92, 0.08), 0 4px 10px rgba(0, 0, 0, 0.02);
    }

    .design-card:has(.approved-pane.active) {
        border: 2px solid #10b981 !important;
        box-shadow: 0 6px 20px rgba(16, 185, 129, .08);
    }

    .design-card:has(.approved-pane.active):hover {
        box-shadow: 0 20px 35px rgba(16, 185, 129, 0.12), 0 4px 10px rgba(0, 0, 0, 0.02);
    }

    .preview-wrapper {
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .design-card .design-thumb {
        height: 160px;
        object-fit: cover;
        width: 100%;
        display: block;
        transition: transform 0.3s ease;
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
    }

    .design-card:hover .design-thumb {
        transform: scale(1.02);
    }

    .tab-pane.active {
        display: flex !important;
        flex-direction: column;
        flex-grow: 1;
    }

    /* ── Stacked Paper Effect for Grouped Files ── */
    .design-card.is-stacked::before,
    .design-card.is-stacked::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #f1f5f9;
        z-index: -1;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
    }
    
    .design-card.is-stacked::before {
        transform: translate(4px, 4px) rotate(1deg);
        z-index: -2;
        opacity: 0.55;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    
    .design-card.is-stacked::after {
        transform: translate(2px, 2px) rotate(-0.8deg);
        z-index: -1;
        opacity: 0.8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    
    .design-card.is-stacked:hover::before {
        transform: translate(8px, 8px) rotate(2deg);
        opacity: 0.75;
    }
    
    .design-card.is-stacked:hover::after {
        transform: translate(4px, 4px) rotate(-1.5deg);
        opacity: 0.9;
    }

    /* ── Premium Backdrop Blur Overlay ── */
    .backdrop-blur-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 160px;
        background: rgba(15, 23, 42, 0.35) !important;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        z-index: 2;
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
    }

    .design-card:hover .backdrop-blur-overlay {
        opacity: 1;
    }

    /* ── Previews & Placeholders ── */
    .pdf-placeholder,
    .video-placeholder,
    .3d-placeholder,
    .general-placeholder {
        height: 160px;
        display: flex;
        flex-column: column;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
    }

    .pdf-placeholder {
        background: linear-gradient(135deg, #fff5f5 0%, #ffebeb 100%);
    }

    .video-placeholder {
        background: linear-gradient(135deg, #fffcf5 0%, #fff3db 100%);
        position: relative;
    }

    .3d-placeholder {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }

    .general-placeholder {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .design-card:hover .pdf-placeholder,
    .design-card:hover .video-placeholder,
    .design-card:hover .3d-placeholder,
    .design-card:hover .general-placeholder {
        transform: scale(1.02);
    }

    /* ── File Icons & Badges ── */
    .file-icon-pdf { color: #ef4444 !important; background: #fee2e2 !important; border: 1px solid #fee2e2; }
    .file-icon-image { color: #3b82f6 !important; background: #dbeafe !important; border: 1px solid #dbeafe; }
    .file-icon-video { color: #f59e0b !important; background: #fef3c7 !important; border: 1px solid #fef3c7; }
    .file-icon-3d { color: #06b6d4 !important; background: #ecfeff !important; border: 1px solid #ecfeff; }
    .file-icon-general { color: #64748b !important; background: #f1f5f9 !important; border: 1px solid #f1f5f9; }

    /* ── Design Files List in Card ── */
    .design-files-list {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 #f8fafc;
    }

    .design-files-list::-webkit-scrollbar {
        width: 4px;
    }

    .design-files-list::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 10px;
    }

    .design-files-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .design-files-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .design-file-item {
        background: transparent !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        border-radius: 0 !important;
        padding: 8px 4px !important;
        transition: all 0.2s ease-in-out;
    }

    .design-file-item:last-child {
        border-bottom: none !important;
    }

    .design-file-item:hover {
        background: rgba(15, 23, 42, 0.015) !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
        border-radius: 6px !important;
    }

    /* ── Action Buttons ── */
    .action-btn {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        width: 28px !important;
        height: 28px !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .action-btn:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06) !important;
    }

    .action-btn.text-danger { color: #ef4444 !important; }
    .action-btn.text-danger:hover {
        background: #fee2e2 !important;
        color: #ef4444 !important;
    }

    .action-btn.text-primary { color: #3b82f6 !important; }
    .action-btn.text-primary:hover {
        background: #dbeafe !important;
        color: #3b82f6 !important;
    }

    .action-btn.text-warning { color: #f59e0b !important; }
    .action-btn.text-warning:hover {
        background: #fef3c7 !important;
        color: #d97706 !important;
    }

    .action-btn.text-info { color: #06b6d4 !important; }
    .action-btn.text-info:hover {
        background: #ecfeff !important;
        color: #0891b2 !important;
    }

    /* ── Revision timeline (Tab Progress) ── */
    .rev-timeline {
        position: relative;
        padding-left: 28px;
    }

    .rev-timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .rev-item {
        position: relative;
        margin-bottom: 18px;
    }

    .rev-item:last-child {
        margin-bottom: 0;
    }

    .rev-dot {
        position: absolute;
        left: -24px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #adb5bd;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .rev-dot.approved {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a74533;
    }

    .rev-dot.rejected {
        background: #dc3545;
        box-shadow: 0 0 0 2px #dc354533;
    }

    .rev-dot.pending {
        background: #ffc107;
        box-shadow: 0 0 0 2px #ffc10733;
    }

    .rev-box {
        background: #fff;
        border: 1px solid #e4e9f0;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .rev-box.approved {
        border-color: #28a745;
        background: #f0fff4;
    }

    .rev-box.rejected {
        border-color: #dc354533;
        background: #fff8f8;
    }

    /* ── Upload Previews in Modal ── */
    .preview-files-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .preview-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        animation: slideIn 0.25s ease-out;
        transition: all 0.2s ease;
    }

    .preview-item:hover {
        border-color: var(--palette-primary);
        background: #f1f5f9;
    }

    .preview-item-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex-grow: 1;
    }

    .preview-thumb-container {
        width: 42px;
        height: 42px;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
    }

    .preview-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-file-details {
        min-width: 0;
        flex-grow: 1;
    }

    .preview-file-name {
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .preview-file-size {
        font-size: 10px;
        color: #64748b;
        margin-top: 1px;
    }

    .btn-remove-preview {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #fee2e2;
        color: #ef4444;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        font-size: 12px;
    }

    .btn-remove-preview:hover {
        background: #ef4444;
        color: #fff;
        transform: scale(1.1);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Dropzone Area Style ── */
    .dropzone-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
    }

    .dropzone-area:hover,
    .dropzone-area.dragover {
        border-color: var(--palette-primary);
        background: #f1f5f9;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1);
    }

    .dropzone-icon-wrapper {
        width: 48px;
        height: 48px;
        background: #e2e8f0;
        border-radius: 50%;
        color: #64748b;
        font-size: 20px;
        transition: all 0.2s ease;
    }

    .dropzone-area:hover .dropzone-icon-wrapper,
    .dropzone-area.dragover .dropzone-icon-wrapper {
        background: rgba(255, 92, 92, 0.1);
        color: var(--palette-primary);
    }

    .dropzone-area:hover .dropzone-icon-wrapper i,
    .dropzone-area.dragover .dropzone-icon-wrapper i {
        color: var(--palette-primary) !important;
    }

    /* ── Custom Premium Bootstrap Accordion ── */
    .design-accordion {
        --bs-accordion-border-color: #e2e8f0;
        --bs-accordion-bg: #fff;
        --bs-accordion-btn-bg: #fafbfc;
        --bs-accordion-active-bg: #f8fafc;
        --bs-accordion-active-color: #1e293b;
    }

    .design-accordion .accordion-item {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        margin-bottom: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }
    
    .design-accordion .accordion-item:last-child {
        margin-bottom: 0;
    }

    .design-accordion .accordion-button {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        padding: 16px 20px;
        color: #1e293b;
        background-color: #fafbfc;
        box-shadow: none !important;
        transition: all 0.2s ease-in-out;
    }

    .design-accordion .accordion-button:hover {
        background-color: #f1f5f9;
    }

    .design-accordion .accordion-button:not(.collapsed) {
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .design-accordion .accordion-button::after {
        background-size: 10px;
        transition: transform 0.2s ease-in-out;
    }

    .design-accordion .accordion-button:focus {
        border-color: #cbd5e1;
        box-shadow: none;
    }

    /* ── Collapsible Revisions Chevron Rotation ── */
    .rev-toggle-btn .fa-chevron-down {
        transition: transform 0.2s ease-in-out;
    }
    .rev-toggle-btn.collapsed .fa-chevron-down {
        transform: rotate(-90deg);
    }
</style>
