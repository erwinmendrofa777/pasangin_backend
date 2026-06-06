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

    /* ── Design gallery card ── */
    .design-card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e4e9f0;
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }

    .design-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(255, 92, 92, .18);
    }

    .design-card.approved {
        border: 2px solid #28a745 !important;
        box-shadow: 0 4px 16px rgba(40, 167, 69, .18);
    }

    .design-card .design-thumb {
        height: 140px;
        object-fit: cover;
        width: 100%;
        display: block;
    }

    .design-card .design-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 140px;
        background: rgba(30, 35, 60, .55);
        opacity: 0;
        transition: opacity .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .design-card:hover .design-overlay {
        opacity: 1;
    }

    .design-card .design-meta {
        padding: 10px 12px 12px;
    }

    .pdf-placeholder {
        height: 140px;
        background: #fff5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-placeholder {
        height: 140px;
        background: #fff9f0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
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
</style>
