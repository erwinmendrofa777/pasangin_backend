<style>
    .animate-up {
        animation: progFadeUp 0.4s ease both;
    }

    @keyframes progFadeUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .progress-title {
        font-size: 1rem;
        font-weight: 700;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .progress-count-badge {
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* ── Target Group Card ── */
    .target-group-card {
        border: 1px solid #e4e9f0;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .target-group-header {
        background: linear-gradient(135deg, #f8f9ff, #eef1ff);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s ease;
        border-bottom: 1px solid #e4e9f0;
    }

    .target-group-header:hover {
        background: #eaedff;
    }

    .target-group-header .target-name {
        font-weight: 700;
        font-size: 0.88rem;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .target-group-header .target-name .tg-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .target-group-header .target-name>div {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .target-group-header .tg-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .target-group-header .tg-chevron {
        transition: transform 0.25s ease;
        color: #6777ef;
        font-size: 0.8rem;
    }

    .target-group-header.collapsed .tg-chevron {
        transform: rotate(-90deg);
    }

    .tg-count-pill {
        background: #e0e4ff;
        color: #6777ef;
        border-radius: 50px;
        padding: 2px 10px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    /* ── Progress Card inside group ── */
    .progress-item-card {
        padding: 16px 20px;
        background: #fff;
        transition: all 0.2s ease;
        position: relative;
        border-bottom: 1px solid #f0f2f5;
    }

    .progress-item-card:last-child {
        border-bottom: none;
    }

    .progress-item-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e9ecef;
        transition: background 0.2s ease;
    }

    .progress-item-card:hover {
        background: #fafbff;
    }

    .progress-item-card:hover::before {
        background: #6777ef;
    }

    .progress-item-card.st-approved::before {
        background: #47c363;
    }

    .progress-item-card.st-rejected::before {
        background: #fc544b;
    }

    .progress-item-card.st-pending::before {
        background: #ffa426;
    }

    /* ── Status pills ── */
    .prog-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .pill-approved {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .pill-rejected {
        background: #f8d7da;
        color: #842029;
    }

    .pill-pending {
        background: #fff3cd;
        color: #7d5a00;
    }

    .prog-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* ── Number & Photos ── */
    .prog-num {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #f0f3ff;
        color: #6777ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .prog-photo-thumb {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .prog-photo-thumb:hover {
        transform: scale(1.1);
    }

    .prog-no-photo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ced4da;
    }

    /* ── Mobile Optimization ── */
    @media (max-width: 575px) {
        .progress-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .target-group-header {
            padding: 12px 15px;
        }

        .target-group-header .tg-meta .tg-count-pill {
            display: none;
        }

        .progress-item-card {
            padding: 15px;
        }

        .progress-item-card .d-flex {
            flex-wrap: wrap;
        }

        .progress-item-card .prog-dropdown {
            width: 100%;
            margin-top: 12px;
        }

        .progress-item-card .prog-status-pill {
            width: 100%;
            justify-content: center;
        }

        .prog-num {
            display: none;
        }

        /* Hide number on mobile to save space */
    }

    /* ── Empty state ── */
    .progress-empty {
        text-align: center;
        padding: 60px 20px;
    }

    .progress-empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #f0f3ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.8rem;
        color: #6777ef;
        opacity: 0.5;
    }

    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000000 !important;
        border-radius: 16px;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8) !important;
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
