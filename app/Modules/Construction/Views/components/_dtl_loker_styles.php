<style>
    /* ── Loker Premium Styles ── */
    .loker-section {
        animation: lokerFadeUp 0.4s ease both;
    }

    .loker-section:nth-child(2) {
        animation-delay: 0.1s;
    }

    @keyframes lokerFadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loker-preview-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(255, 92, 92, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .loker-preview-header {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        padding: 16px 22px;
    }

    .loker-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .loker-info-left {
        padding: 20px 24px;
        border-right: 1px solid #f0f2f5;
    }

    .loker-info-right {
        padding: 20px 24px;
    }

    .loker-field-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .loker-field-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: #34395e;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .loker-stat-card {
        background: #fafafa;
        border-radius: 10px;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .loker-stat-card:hover {
        border-color: #ffcccc;
        background: #fffcfc;
    }

    .loker-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* ── Form Card ── */
    .loker-form-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(255, 92, 92, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .loker-form-header {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        padding: 14px 22px;
    }

    .loker-form-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .loker-form-input {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        padding: 10px 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-size: 0.88rem;
    }

    .loker-form-input:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.12);
    }

    .btn-loker-submit {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-loker-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.35);
    }

    /* ── MOBILE RESPONSIVE ── */
    @media (max-width: 767px) {
        .loker-info-grid {
            grid-template-columns: 1fr;
        }

        .loker-info-left {
            border-right: none;
            border-bottom: 1px dashed #e0e4ff;
            padding: 16px 20px;
        }

        .loker-info-right {
            padding: 16px 20px;
        }

        .loker-preview-header,
        .loker-form-header {
            padding: 14px 16px;
        }

        .loker-stat-card {
            padding: 10px;
        }

        .loker-stat-card>.d-flex {
            gap: 10px !important;
            flex-direction: column;
            align-items: flex-start !important;
            text-align: left;
        }

        .loker-stat-icon {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }
</style>
