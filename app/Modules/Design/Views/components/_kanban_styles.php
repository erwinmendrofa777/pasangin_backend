<style>
    /* ===== KANBAN BOARD CONTAINER ===== */
    .kanban-board {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding: 10px 5px 24px 5px;
        align-items: flex-start;
        min-height: 650px;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
    }

    .kanban-board::-webkit-scrollbar {
        height: 8px;
    }

    .kanban-board::-webkit-scrollbar-track {
        background: transparent;
    }

    .kanban-board::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.12);
        border-radius: 10px;
    }

    .kanban-board::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }

    /* ===== KANBAN COLUMN ===== */
    .kanban-column {
        flex: 1;
        min-width: 290px;
        max-width: 350px;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease;
    }

    /* Hanya kolom Selesai (DONE) yang memiliki batasan tinggi dan overflow */
    .kanban-column.done {
        max-height: 80vh;
        overflow: hidden;
    }

    .kanban-column.drag-over {
        border-color: var(--palette-primary);
        background: rgba(255, 92, 92, 0.02);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.1);
    }

    /* Column Header */
    .kanban-column-header {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid transparent;
        background: #ffffff;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .kanban-column-title {
        font-size: 0.88rem;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kanban-column-count {
        font-size: 0.75rem;
        font-weight: 800;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    /* Column Colors */
    .kanban-column.pending .kanban-column-header {
        border-bottom-color: #f59e0b;
    }
    .kanban-column.pending .kanban-column-title {
        color: #d97706;
    }
    .kanban-column.pending .kanban-column-count {
        background: #fef3c7;
        color: #d97706;
        border-color: #fde68a;
    }

    .kanban-column.progress-col .kanban-column-header {
        border-bottom-color: #3b82f6;
    }
    .kanban-column.progress-col .kanban-column-title {
        color: #2563eb;
    }
    .kanban-column.progress-col .kanban-column-count {
        background: #dbeafe;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .kanban-column.review .kanban-column-header {
        border-bottom-color: var(--palette-primary, #e53935);
    }
    .kanban-column.review .kanban-column-title {
        color: var(--palette-primary, #e53935);
    }
    .kanban-column.review .kanban-column-count {
        background: #ffe5e5;
        color: var(--palette-primary, #e53935);
        border-color: #ffcccc;
    }

    .kanban-column.done .kanban-column-header {
        border-bottom-color: #10b981;
    }
    .kanban-column.done .kanban-column-title {
        color: #059669;
    }
    .kanban-column.done .kanban-column-count {
        background: #d1fae5;
        color: #059669;
        border-color: #a7f3d0;
    }

    /* Column Body (Scrollable Task List) */
    .kanban-column-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 150px;
        overflow: visible; /* Default tanpa scroll untuk pending, progress, dan review */
    }

    /* Scrollable hanya untuk kolom Selesai (done) */
    .kanban-column.done .kanban-column-body {
        overflow-y: auto;
    }

    /* Mempercantik Scrollbar Kolom Selesai (done) */
    .kanban-column.done .kanban-column-body::-webkit-scrollbar {
        width: 6px;
    }

    .kanban-column.done .kanban-column-body::-webkit-scrollbar-track {
        background: rgba(16, 185, 129, 0.02);
        border-radius: 10px;
    }

    .kanban-column.done .kanban-column-body::-webkit-scrollbar-thumb {
        background: rgba(16, 185, 129, 0.25);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: background 0.25s ease;
    }

    .kanban-column.done .kanban-column-body::-webkit-scrollbar-thumb:hover {
        background: rgba(16, 185, 129, 0.5);
    }

    /* Empty state */
    .kanban-column-empty {
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
        font-size: 0.8rem;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        background: #ffffff;
    }

    /* ===== KANBAN CARD ===== */
    .kanban-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #edf2f7;
        padding: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        cursor: grab;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .kanban-card:active {
        cursor: grabbing;
    }

    /* SortableJS Drag Ghosting Styling */
    .sortable-ghost {
        opacity: 0.4;
        background: rgba(229, 57, 53, 0.05);
        border: 2px dashed var(--palette-primary);
    }

    .sortable-chosen {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: var(--palette-primary) !important;
        transform: rotate(2deg) scale(1.02);
    }

    /* Card Details */
    .kanban-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.35;
        margin-bottom: 8px;
        word-wrap: break-word;
    }

    .kanban-card-concept {
        font-size: 0.76rem;
        font-weight: 700;
        color: var(--palette-primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .kanban-card-info-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.72rem;
        color: #64748b;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .kanban-card-info-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Designer select picker in card */
    .kanban-card-designer-wrapper {
        margin-top: 10px;
    }

    .kanban-card-designer-select {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        padding: 5px 8px !important;
        height: auto !important;
        border-radius: 8px !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        color: #475569 !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kanban-card-designer-select:focus {
        border-color: var(--palette-primary) !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.1) !important;
    }

    /* Card badges */
    .kanban-card-badge {
        font-size: 0.65rem;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 8px;
        display: inline-block;
    }

    /* Client Highlight Badge */
    .kanban-card-client-badge {
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        background: #f8fafc;
        border-radius: 8px;
        padding: 6px 10px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #e2e8f0;
    }

    .kanban-card-client-badge i {
        color: var(--palette-primary, #e53935);
        font-size: 0.88rem;
    }

    .kanban-card-client-badge .client-name {
        font-weight: 800;
        color: #0f172a;
    }

    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-progress {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-review {
        background: #ffe5e5;
        color: var(--palette-primary);
    }

    .badge-revisi {
        background: #fee2e2;
        color: #ef4444;
    }

    .badge-done {
        background: #d1fae5;
        color: #059669;
    }

    /* Floating action buttons inside cards */
    .kanban-card-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
    }

    .btn-kanban-action {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-kanban-action:hover {
        background: var(--palette-primary);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* GLIGHTBOX PREVIEW ROW (Awaiting Review Cards) */
    .review-preview-row {
        margin-top: 12px;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .review-preview-item {
        position: relative;
        width: 42px;
        height: 42px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        cursor: pointer;
    }

    .review-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .review-preview-item .video-play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
    }

    .review-preview-item .pdf-file-icon {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff5f5;
        color: #dc3545;
        font-size: 14px;
    }

    /* ===== MODAL DETAIL TUGAS PREMIUM STYLES ===== */
    #kanbanCardDetailModal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12), 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    #kanbanCardDetailModal .modal-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 22px 28px;
    }
    
    #kanbanCardDetailModal .modal-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.3px;
    }
    
    #kanbanCardDetailModal .modal-body {
        padding: 28px;
    }
    
    #kanbanCardDetailModal .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 18px 28px;
    }

    /* Modern Status Badges in Modal */
    .modal-badge {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 6px 14px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .modal-badge-pending {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .modal-badge-progress {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .modal-badge-review {
        background: #fff5f5;
        color: var(--palette-primary, #ff5c5c);
        border: 1px solid #ffcccc;
    }

    .modal-badge-revisi {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .modal-badge-done {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    /* Modal Info Box Grid */
    .modal-info-card {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 14px 16px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .modal-info-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transform: translateY(-1px);
    }

    .modal-info-card .info-label {
        font-size: 0.62rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal-info-card .info-label i {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .modal-info-card .info-value {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-word;
    }
    
    .modal-info-card .info-value.text-designer {
        color: var(--palette-primary, #ff5c5c);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Keterangan Box */
    .description-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.82rem;
        color: #334155;
        line-height: 1.5;
    }

    #modalDetailKeterangan[readonly] {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border-color: #cbd5e1 !important;
        cursor: default;
    }

    /* Premium Custom File Upload Input */
    .custom-file-upload {
        position: relative;
        width: 100%;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 38px;
        padding: 0 16px;
        background: #ffffff;
        border: 1.5px dashed #cbd5e1;
        border-radius: 10px;
        cursor: pointer;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        transition: all 0.2s ease;
        margin-bottom: 0;
    }

    .file-upload-label:hover {
        border-color: var(--palette-primary, #ff5c5c);
        background: rgba(255, 92, 92, 0.01);
        color: var(--palette-primary, #ff5c5c);
    }

    .file-upload-label i {
        font-size: 1.05rem;
        color: #94a3b8;
        transition: all 0.2s ease;
    }

    .file-upload-label:hover i {
        color: var(--palette-primary, #ff5c5c);
        transform: translateY(-1px);
    }

    .file-upload-filename {
        max-width: 85%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ===== UPLOADED DESIGN ITEMS IN MODAL ===== */
    .design-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .design-item-row:hover {
        background: #ffffff;
        border-color: var(--palette-primary, #ff5c5c);
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.05);
        transform: translateY(-1px);
    }

    .design-item-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .design-item-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .design-item-icon.icon-pdf {
        background: #fff5f5;
        color: #dc3545;
    }

    .design-item-icon.icon-video {
        background: #fffbeb;
        color: #ffc107;
    }

    .design-item-icon.icon-img {
        background: #eef2ff;
        color: #4f46e5;
    }

    .design-item-icon.icon-3d {
        background: #eef6ff;
        color: #0ea5e9;
    }

    .design-item-icon.icon-general {
        background: #f1f5f9;
        color: #64748b;
    }

    .design-item-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .design-item-title {
        font-size: 0.8rem;
        font-weight: 750;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .design-item-meta {
        font-size: 0.68rem;
        color: #64748b;
        font-weight: 500;
    }

    .design-item-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: 10px;
    }
</style>
