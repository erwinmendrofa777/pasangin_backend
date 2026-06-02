<style>
    /* ── Upload dropzone ── */
    .desain-upload-card {
        border: 2px dashed #c9d1db;
        border-radius: 14px;
        background: #fafbfc;
        transition: border-color 0.25s ease, background 0.25s ease;
        animation: desainFadeUp 0.4s ease both;
    }

    .desain-upload-card:hover {
        border-color: #6777ef;
        background: #f0f2ff;
    }

    /* ── Gallery card ── */
    .desain-gallery-card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e4e9f0;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        position: relative;
        animation: desainFadeUp 0.35s ease both;
        background: #fff;
    }

    .desain-gallery-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .desain-gallery-card:nth-child(2) {
        animation-delay: 0.10s;
    }

    .desain-gallery-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .desain-gallery-card:nth-child(4) {
        animation-delay: 0.20s;
    }

    .desain-gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(103, 119, 239, 0.18);
    }

    .desain-gallery-card .desain-thumb {
        height: 150px;
        object-fit: cover;
        width: 100%;
        display: block;
    }

    .desain-gallery-card .desain-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 150px;
        background: rgba(30, 35, 60, 0.55);
        opacity: 0;
        transition: opacity 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .desain-gallery-card:hover .desain-overlay {
        opacity: 1;
    }

    .desain-gallery-card .desain-meta {
        padding: 12px 14px;
    }

    .desain-pdf-placeholder {
        height: 150px;
        background: linear-gradient(135deg, #fff5f5, #ffe8e8);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .desain-overlay-btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        transition: transform 0.15s ease;
    }

    .desain-overlay-btn:hover {
        transform: scale(1.1);
    }

    .desain-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6c757d;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .desain-input {
        border-radius: 8px;
        border: 1.5px solid #e0e4ff;
        font-size: 0.85rem;
        padding: 9px 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .desain-input:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.12);
    }

    @keyframes desainFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .desain-empty {
        border: 2px dashed #dee2e6;
        border-radius: 14px;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        animation: desainFadeUp 0.4s ease both 0.1s;
    }

    /* ── MOBILE ── */
    .desain-mobile-actions {
        display: none;
        padding: 8px 12px;
        border-top: 1px solid #f0f2f5;
        gap: 8px;
    }

    @media (max-width: 767px) {

        /* Upload card: padding kompak */
        .desain-upload-card {
            padding: 16px !important;
        }

        /* Thumbnail lebih pendek */
        .desain-gallery-card .desain-thumb,
        .desain-gallery-card .desain-pdf-placeholder {
            height: 110px;
        }

        /* Sembunyikan hover overlay di HP */
        .desain-gallery-card .desain-overlay {
            display: none !important;
        }

        /* Tampilkan tombol aksi di bawah card */
        .desain-mobile-actions {
            display: flex;
        }

        .desain-gallery-card .desain-meta {
            padding: 8px 10px;
        }
    }

    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000 !important;
        padding: 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        width: 95% !important;
        max-width: 900px !important;
        border-radius: 12px;
        overflow: hidden !important;
        overflow-y: hidden !important;
    }
    .glightbox-video-slide .gslide-inner-content {
        background: #000 !important;
        overflow: hidden !important;
        width: 100% !important;
    }
    .glightbox-video-slide .gslide-description {
        display: none !important;
    }
    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
        overflow: hidden !important;
        background: #000 !important;
    }
</style>
