<style>
    /* ── Gallery Card Premium System ── */
    .desain-gallery-card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        animation: desainFadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        background: #fff;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Staggered animation delays for card entries */
    .desain-gallery-card:nth-child(1) { animation-delay: 0.05s; }
    .desain-gallery-card:nth-child(2) { animation-delay: 0.10s; }
    .desain-gallery-card:nth-child(3) { animation-delay: 0.15s; }
    .desain-gallery-card:nth-child(4) { animation-delay: 0.20s; }
    .desain-gallery-card:nth-child(5) { animation-delay: 0.25s; }

    .desain-gallery-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(50, 50, 93, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    /* ── Media & Thumbnail Wrappers ── */
    .desain-media-wrapper {
        position: relative;
        height: 160px;
        background: #f8fafc;
        width: 100%;
        overflow: hidden;
    }

    .desain-gallery-card .desain-thumb {
        height: 100%;
        object-fit: cover;
        width: 100%;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .desain-gallery-card:hover .desain-thumb {
        transform: scale(1.06);
    }

    /* ── Glassmorphism File Type Badges ── */
    .desain-file-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 3;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .desain-badge-image { background: rgba(219, 234, 254, 0.85); color: #1e40af; }
    .desain-badge-pdf { background: rgba(254, 226, 226, 0.85); color: #991b1b; }
    .desain-badge-video { background: rgba(254, 243, 199, 0.85); color: #92400e; }
    .desain-badge-3d { background: rgba(204, 251, 241, 0.85); color: #115e59; }
    .desain-badge-zip { background: rgba(243, 232, 255, 0.85); color: #6b21a8; }
    .desain-badge-generic { background: rgba(241, 245, 249, 0.85); color: #475569; }

    /* ── Placeholders for Document/3D/Video files ── */
    .desain-placeholder-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .desain-gallery-card:hover .desain-placeholder-wrapper {
        transform: scale(1.05);
    }

    .desain-pdf-placeholder, 
    .desain-video-placeholder, 
    .desain-3d-placeholder, 
    .desain-zip-placeholder, 
    .desain-generic-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
    }

    .desain-pdf-placeholder { background: linear-gradient(135deg, #fff5f5, #ffe3e3); }
    .desain-video-placeholder { background: linear-gradient(135deg, #fffaf0, #ffedd5); }
    .desain-3d-placeholder { background: linear-gradient(135deg, #e6fffa, #ccfbf1); }
    .desain-zip-placeholder { background: linear-gradient(135deg, #f3e8ff, #e9d5ff); }
    .desain-generic-placeholder { background: linear-gradient(135deg, #f8fafc, #edf2f7); }

    /* Play Button Overlay for video cards */
    .play-btn-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(221, 107, 32, 0.25);
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .desain-placeholder-wrapper:hover .play-btn-overlay {
        transform: translate(-50%, -50%) scale(1.1);
    }

    .play-btn-overlay i {
        font-size: 1rem;
        margin-left: 2px; /* Center-correction for play symbol */
    }

    /* ── Desktop Hover Overlay ── */
    .desain-gallery-card .desain-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        z-index: 2;
    }

    .desain-gallery-card:hover .desain-overlay {
        opacity: 1;
    }

    .desain-overlay-btn {
        border-radius: 50%;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none !important;
    }

    .desain-overlay-btn:hover {
        transform: scale(1.12);
        background: #fff;
    }

    .desain-overlay-btn i {
        font-size: 1.05rem;
    }

    /* ── Metadata Info Area ── */
    .desain-gallery-card .desain-meta {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .desain-card-title {
        font-size: 0.88rem;
        color: #1e293b;
        font-weight: 600;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .desain-author {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 6px;
    }

    .desain-author i {
        font-size: 0.85rem;
    }

    /* ── Comment Chat Bubble Style ── */
    .desain-comment-bubble {
        background: #f8fafc;
        border-left: 3px solid var(--palette-primary, #ff5c5c);
        padding: 8px 12px;
        border-radius: 0 10px 10px 0;
        font-size: 0.74rem;
        display: flex;
        align-items: flex-start;
    }

    .desain-comment-text {
        color: #475569;
        font-style: italic;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .desain-footer {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* ── Empty State ── */
    .desain-empty {
        background: #fff;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        min-height: 260px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        animation: desainFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .desain-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.7rem;
    }

    @keyframes desainFadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Mobile Layout Adjustments ── */
    .desain-mobile-actions {
        display: none;
        padding: 10px 14px 14px;
        gap: 8px;
        border-top: 1px solid #f1f5f9;
        background: #fff;
    }

    @media (max-width: 767px) {
        .desain-media-wrapper {
            height: 120px;
        }

        .desain-gallery-card .desain-overlay {
            display: none !important;
        }

        .desain-mobile-actions {
            display: flex;
        }

        .desain-mobile-actions .btn {
            border-radius: 8px;
            font-size: 0.76rem;
            padding: 6px 12px;
            font-weight: 600;
        }

        .desain-gallery-card .desain-meta {
            padding: 12px;
        }

        .desain-card-title {
            font-size: 0.82rem;
        }
    }

    /* ── GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ── */
    .glightbox-video-slide .gslide-inline {
        background: #000 !important;
        padding: 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        width: 95% !important;
        max-width: 900px !important;
        border-radius: 12px;
        overflow: hidden !important;
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

