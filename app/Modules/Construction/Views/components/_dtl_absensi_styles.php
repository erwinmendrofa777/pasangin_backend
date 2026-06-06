<style>
    /* Kunci lebar kolom deskripsi agar tidak stretch */
    #table-absensi .desc-cell {
        max-width: 220px;
        min-width: 160px;
    }

    /* ===== POPOVER DESKRIPSI - Premium Style ===== */
    /* Bootstrap 5: customClass ditaruh langsung ke .popover */
    .desc-popover {
        max-width: 340px !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
        overflow: hidden;
    }

    .desc-popover .popover-arrow::before,
    .desc-popover .popover-arrow::after {
        border-left-color: #fff !important;
    }

    .desc-popover .popover-header {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%) !important;
        color: #fff !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        padding: 10px 14px !important;
        border-bottom: none !important;
        letter-spacing: 0.3px;
    }

    .desc-popover .popover-body {
        font-size: 0.82rem;
        line-height: 1.65;
        color: #495057;
        white-space: pre-wrap;
        word-break: break-word;
        padding: 12px 14px !important;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
    }

    .desc-popover .popover-body::-webkit-scrollbar {
        width: 4px;
    }

    .desc-popover .popover-body::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 4px;
    }

    /* Tombol selengkapnya */
    .desc-popover-btn:focus {
        box-shadow: none;
        outline: none;
    }

    .desc-popover-btn:hover .badge {
        background: #f1f3f9 !important;
        color: var(--palette-primary) !important;
        border-color: rgba(255, 92, 92, 0.3) !important;
        transition: all 0.2s ease;
    }

    /* DataTables */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.2rem 0.5rem !important;
        margin-left: 2px !important;
    }

    .dataTables_wrapper .dataTables_info {
        float: left;
        padding-top: 15px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        padding-top: 15px;
    }
    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000000 !important;
        border-radius: 16px;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.8) !important;
        max-width: 850px !important;
    }
    .glightbox-video-slide .gslide-inner-content {
        background: transparent !important;
    }
    .glightbox-video-slide .gslide-description {
        background: rgba(0, 0, 0, 0.85) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 20px !important;
    }
    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
    }
</style>
