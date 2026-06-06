<style>
    /* ===== PELAMAR PREMIUM STYLES ===== */
    .pelamar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .pelamar-title {
        font-size: 1rem;
        font-weight: 700;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .pelamar-count-badge {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        border-radius: 50px;
        padding: 3px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        animation: countPulse 2s ease-in-out infinite;
    }

    @keyframes countPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(255, 92, 92, 0.4);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(255, 92, 92, 0);
        }
    }

    /* ===== APPLICANT CARD ===== */
    .applicant-card {
        border: 1px solid #f0f2f5;
        border-radius: 14px;
        padding: 16px 20px;
        background: #fff;
        margin-bottom: 14px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .applicant-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e9ecef;
        border-radius: 4px 0 0 4px;
        transition: background 0.25s ease;
    }

    .applicant-card:hover {
        box-shadow: 0 8px 28px rgba(255, 92, 92, 0.12);
        transform: translateY(-2px);
        border-color: #ffcccc;
    }

    .applicant-card:hover::before {
        background: var(--palette-primary);
    }

    .applicant-card.status-approved::before {
        background: #47c363;
    }

    .applicant-card.status-rejected::before {
        background: #fc544b;
    }

    .applicant-card.status-processing::before {
        background: #ffa426;
    }

    .applicant-card.status-siapkerja::before {
        background: #47c363;
    }

    /* ===== AVATAR ===== */
    .applicant-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.3);
    }

    /* ===== STATUS PILLS ===== */
    .status-pill-sm {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .status-pill-sm .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.75;
    }

    .pill-siapkerja {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .pill-ditolak {
        background: #f8d7da;
        color: #842029;
    }

    .pill-proses {
        background: #fff3cd;
        color: #7d5a00;
    }

    .pill-berkas {
        background: #cff4fc;
        color: #055160;
    }

    .pill-default {
        background: #e2e3e5;
        color: #41464b;
    }

    /* ===== SELECT INLINE ===== */
    .status-select-inline {
        border: 1.5px solid #f3f4f6;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #34395e;
        background: #fafafa;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s ease, background-color 0.2s ease;
        min-width: 160px;
    }

    .status-select-inline:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.15);
    }

    .btn-update-status {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-update-status:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.4);
        color: #fff;
    }

    /* ===== EMPTY STATE ===== */
    .empty-pelamar {
        text-align: center;
        padding: 48px 24px;
        animation: fadeInUp 0.5s ease;
    }

    .empty-pelamar .empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff5f5, #ffe5e5);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: var(--palette-primary);
        opacity: 0.7;
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .applicant-card {
        animation: fadeInUp 0.35s ease both;
    }

    .applicant-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .applicant-card:nth-child(2) {
        animation-delay: 0.10s;
    }

    .applicant-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .applicant-card:nth-child(4) {
        animation-delay: 0.20s;
    }

    .applicant-card:nth-child(5) {
        animation-delay: 0.25s;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 767px) {
        .pelamar-header {
            flex-wrap: wrap;
            gap: 10px;
        }

        .applicant-card-body {
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .applicant-actions {
            width: 100%;
            border-top: 1px solid #f0f2f5;
            padding-top: 12px;
            margin-top: 8px;
        }

        .applicant-actions form {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
        }

        .applicant-actions .status-select-inline {
            width: 100%;
        }

        .applicant-actions .btn-update-status {
            width: 100%;
            text-align: center;
            padding: 8px 0;
        }
    }
</style>
