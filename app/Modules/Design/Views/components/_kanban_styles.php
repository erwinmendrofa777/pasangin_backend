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
        border-radius: 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.02), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        max-height: 82vh;
    }

    .kanban-column:hover {
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 10px 10px -5px rgba(15, 23, 42, 0.03);
        border-color: #cbd5e1;
    }

    /* Kolom TINJAUAN: tampilkan visual disabled karena tidak bisa di-drag masuk manual */
    .kanban-column.review {
        position: relative;
    }

    .kanban-column.drag-over {
        border-color: var(--palette-primary) !important;
        background: rgba(255, 92, 92, 0.02) !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.08) !important;
    }

    /* Column Header */
    .kanban-column-header {
        padding: 20px 24px 14px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid transparent;
        background: #ffffff;
    }

    .kanban-column-title {
        font-size: 0.92rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kanban-column-count {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 50px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    /* Column Accent Colors & Glowing Top Borders */
    .kanban-column.pending {
        border-top: 4px solid #f59e0b !important;
        background: #fefce8 !important;
    }
    .kanban-column.pending .kanban-column-header {
        border-bottom-color: rgba(245, 158, 11, 0.08);
    }
    .kanban-column.pending .kanban-column-title {
        color: #b45309;
    }
    .kanban-column.pending .kanban-column-count {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }

    .kanban-column.progress-col {
        border-top: 4px solid #3b82f6 !important;
        background: #eff6ff !important;
    }
    .kanban-column.progress-col .kanban-column-header {
        border-bottom-color: rgba(59, 130, 246, 0.08);
    }
    .kanban-column.progress-col .kanban-column-title {
        color: #1d4ed8;
    }
    .kanban-column.progress-col .kanban-column-count {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .kanban-column.review {
        border-top: 4px solid var(--palette-primary, #ff5c5c) !important;
        background: #fef2f2 !important;
    }
    .kanban-column.review .kanban-column-header {
        border-bottom-color: rgba(255, 92, 92, 0.08);
    }
    .kanban-column.review .kanban-column-title {
        color: var(--palette-primary, #e53935);
    }
    .kanban-column.review .kanban-column-count {
        background: #ffe5e5;
        color: var(--palette-primary, #e53935);
        border-color: #ffcccc;
    }

    .kanban-column.done {
        border-top: 4px solid #10b981 !important;
        background: #f0fdf4 !important;
    }
    .kanban-column.done .kanban-column-header {
        border-bottom-color: rgba(16, 185, 129, 0.08);
    }
    .kanban-column.done .kanban-column-title {
        color: #047857;
    }
    .kanban-column.done .kanban-column-count {
        background: #d1fae5;
        color: #047857;
        border-color: #a7f3d0;
    }

    /* Column Body (Scrollable Task List) — semua kolom scrollable */
    .kanban-column-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 150px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.1) transparent;
    }

    .kanban-column-body::-webkit-scrollbar {
        width: 5px;
    }

    .kanban-column-body::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }

    .kanban-column-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: background 0.2s ease;
    }

    .kanban-column-body::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.22);
    }

    /* Scrollbar warna hijau khusus kolom Selesai (done) */
    .kanban-column.done .kanban-column-body::-webkit-scrollbar-thumb {
        background: rgba(16, 185, 129, 0.3);
    }

    .kanban-column.done .kanban-column-body::-webkit-scrollbar-thumb:hover {
        background: rgba(16, 185, 129, 0.55);
    }

    /* Scrollbar warna merah untuk kolom Tinjauan (review) */
    .kanban-column.review .kanban-column-body::-webkit-scrollbar-thumb {
        background: rgba(229, 57, 53, 0.25);
    }

    .kanban-column.review .kanban-column-body::-webkit-scrollbar-thumb:hover {
        background: rgba(229, 57, 53, 0.5);
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
        border-radius: 16px;
        border: 1px solid #edf2f7;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03), 0 1px 3px rgba(15, 23, 42, 0.02);
        cursor: grab;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .kanban-card:hover {
        transform: translateY(-4px) scale(1.005);
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.06), 0 10px 10px -5px rgba(15, 23, 42, 0.02) !important;
        border-color: var(--palette-primary, #ff5c5c) !important;
    }

    .kanban-card:active {
        cursor: grabbing;
    }

    /* SortableJS Drag Ghosting Styling */
    .sortable-ghost {
        opacity: 0.4;
        background: rgba(255, 92, 92, 0.05);
        border: 2px dashed var(--palette-primary);
    }

    .sortable-chosen {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: var(--palette-primary) !important;
        transform: rotate(2deg) scale(1.02);
    }

    /* Card Details */
    .kanban-card-title {
        font-size: 0.94rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .kanban-card-concept {
        font-size: 0.7rem;
        font-weight: 800;
        color: var(--palette-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .kanban-card-info-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.72rem;
        color: #64748b;
        margin-bottom: 4px;
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
        margin-top: 6px;
    }

    .kanban-card-designer-select {
        font-size: 0.72rem !important;
        font-weight: 700 !important;
        padding: 6px 10px !important;
        height: auto !important;
        border-radius: 10px !important;
        border: 1.2px solid #cbd5e1 !important;
        background-color: #f8fafc !important;
        color: #334155 !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kanban-card-designer-select:hover {
        background-color: #f1f5f9 !important;
        border-color: #94a3b8 !important;
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
        letter-spacing: 0.6px;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Client Highlight Badge */
    .kanban-card-client-badge {
        font-size: 0.76rem;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 6px 12px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
        border: none;
        transition: all 0.25s ease;
    }

    .kanban-card:hover .kanban-card-client-badge {
        background: rgba(255, 92, 92, 0.04);
        color: var(--palette-primary, #ff5c5c);
    }

    .kanban-card-client-badge i {
        color: var(--palette-primary, #e53935);
        font-size: 0.82rem;
    }

    .kanban-card-client-badge .client-name {
        font-weight: 800;
        color: #1e293b;
    }

    .badge-pending {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .badge-progress {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .badge-review {
        background: #ffe5e5;
        color: var(--palette-primary);
        border: 1px solid #ffcccc;
    }

    .badge-revisi {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .badge-done {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    /* Floating action buttons inside cards */
    .kanban-card-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }

    .btn-kanban-action {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .btn-kanban-action:hover {
        background: var(--palette-primary, #ff5c5c);
        color: #ffffff;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25);
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

    /* ===== Dropzone Area Style ===== */
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
        border-color: var(--palette-primary, #ff5c5c);
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
        color: var(--palette-primary, #ff5c5c);
    }

    .dropzone-area:hover .dropzone-icon-wrapper i,
    .dropzone-area.dragover .dropzone-icon-wrapper i {
        color: var(--palette-primary, #ff5c5c) !important;
    }

    /* ===== Upload Previews in Modal ===== */
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
        transition: all 0.2s ease;
    }

    .preview-item:hover {
        border-color: var(--palette-primary, #ff5c5c);
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

    /* ===== Custom Scrollbar for Modal Results List ===== */
    .design-results-list::-webkit-scrollbar {
        width: 6px;
    }

    .design-results-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }

    .design-results-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
        transition: background 0.2s ease;
    }

    .design-results-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* ===== Premium Modal Info Cards (Pending status) ===== */
    .modal-info-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        height: 100%;
    }

    .modal-info-card:hover {
        border-color: var(--palette-primary, #ff5c5c);
        background: #fffdfd;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.04);
    }

    .modal-info-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .modal-info-card-icon.client {
        background: #fee2e2;
        color: #ef4444;
    }

    .modal-info-card-icon.designer {
        background: #e0f2fe;
        color: #0284c7;
    }

    .modal-info-card-icon.schedule {
        background: #ecfdf5;
        color: #059669;
    }

    .modal-info-card-content {
        flex-grow: 1;
        min-width: 0;
    }

    .modal-info-card-label {
        font-size: 8px;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 2px;
    }

    .modal-info-card-value {
        font-size: 12px;
        color: #1e293b;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
