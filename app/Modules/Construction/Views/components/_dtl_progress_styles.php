<style>
    .progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
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
        padding: 3px 12px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* ── Target Group Card ── */
    .target-group-card {
        border: 1px solid #e4e9f0;
        border-radius: 14px;
        margin-bottom: 16px;
        animation: progFadeUp 0.4s ease both;
    }

    .target-group-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .target-group-card:nth-child(2) {
        animation-delay: 0.10s;
    }

    .target-group-card:nth-child(3) {
        animation-delay: 0.15s;
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
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
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
        gap: 8px;
    }

    .target-group-header .target-name .tg-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        flex-shrink: 0;
    }

    .target-group-header .tg-meta {
        display: flex;
        align-items: center;
        gap: 8px;
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
        font-size: 0.72rem;
        font-weight: 700;
    }

    .target-group-body {
        padding: 0;
    }

    /* ── Progress Card inside group ── */
    .progress-item-card {
        padding: 14px 20px;
        background: #fff;
        transition: all 0.2s ease;
        position: relative;
        border-bottom: 1px solid #f0f2f5;
    }

    .progress-item-card:last-child {
        border-bottom: none;
        border-bottom-left-radius: 13px;
        border-bottom-right-radius: 13px;
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
        gap: 5px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .prog-status-pill:hover {
        filter: brightness(0.92);
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
        opacity: 0.7;
    }

    /* ── Number badge ── */
    .prog-num {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: linear-gradient(135deg, #f0f3ff, #e0e4ff);
        color: #6777ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ── Photo thumb ── */
    .prog-photo-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #e9ecef;
        transition: border-color 0.2s ease, transform 0.2s ease;
        cursor: pointer;
    }

    .prog-photo-thumb:hover {
        border-color: #6777ef;
        transform: scale(1.08);
    }

    .prog-no-photo {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 1rem;
    }

    /* ── Dropdown ── */
    .prog-dropdown .dropdown-menu {
        border-radius: 10px;
        border: 1px solid #e9ecef;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
        padding: 6px;
        min-width: 150px;
    }

    .prog-dropdown .dropdown-item {
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 500;
        padding: 6px 12px;
    }

    .prog-dropdown .dropdown-item:hover {
        background: #f0f3ff;
    }

    /* ── Empty state ── */
    .progress-empty {
        text-align: center;
        padding: 56px 24px;
        animation: progFadeUp 0.5s ease both;
    }

    .progress-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f0f3ff, #e0e4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.6rem;
        color: #6777ef;
        opacity: 0.6;
    }

    @keyframes progFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── MOBILE FRIENDLY ── */
    @media (max-width: 767px) {
        .progress-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .target-group-header {
            padding: 12px 14px;
        }

        .target-group-header .target-name {
            font-size: 0.82rem;
        }

        .target-group-header .target-name .tg-icon {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }

        .tg-count-pill {
            font-size: 0.65rem;
            padding: 2px 8px;
        }

        /* Use Grid for progress item to keep alignment crisp */
        .progress-item-card>.d-flex {
            display: grid !important;
            grid-template-columns: auto auto 1fr;
            grid-template-areas:
                "num photo content"
                "dropdown dropdown dropdown";
            gap: 12px;
        }

        .progress-item-card>.d-flex> :nth-child(1) {
            grid-area: num;
        }

        .progress-item-card>.d-flex> :nth-child(2) {
            grid-area: photo;
        }

        .progress-item-card>.d-flex> :nth-child(3) {
            grid-area: content;
        }

        .progress-item-card>.d-flex> :nth-child(4) {
            grid-area: dropdown;
        }

        .prog-dropdown {
            width: 100%;
            margin-top: 0;
            padding-top: 12px;
            border-top: 1px dashed #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        .prog-dropdown .dropdown {
            width: 100%;
        }

        .prog-dropdown .prog-status-pill {
            width: 100%;
            justify-content: center;
            padding: 8px 12px;
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
    }
</style>
