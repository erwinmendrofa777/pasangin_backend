<style>
    .survey-card-form,
    .survey-card-history {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(255, 92, 92, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: surveyFadeUp 0.4s ease both;
    }

    .survey-card-history {
        animation-delay: 0.1s;
    }

    @keyframes surveyFadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .survey-card-form .card-header,
    .survey-card-history .card-header {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        border: none;
        padding: 16px 22px;
    }

    .survey-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .survey-input {
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        padding: 10px 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .survey-input:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.12);
    }

    .btn-survey-submit {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border: none;
        border-radius: 8px;
        padding: 11px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-survey-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.35);
    }

    .empty-survey {
        padding: 56px 24px;
        text-align: center;
        animation: surveyFadeUp 0.5s ease both 0.2s;
    }

    .empty-survey-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff5f5, #ffe5e5);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.6rem;
        color: var(--palette-primary);
        opacity: 0.6;
    }

    /* Divider: horizontal di mobile, vertikal di desktop */
    .survey-divider-y {
        border-top: 1px dashed #dee2e6;
    }

    @media (min-width: 768px) {
        .survey-divider-x {
            border-left: 1px dashed #dee2e6;
        }

        .survey-divider-y {
            border-top: none;
        }
    }

    /* File Upload Box Premium */
    .file-upload-box {
        position: relative;
        height: 48px;
        background: #fafafa;
        border: 2px dashed var(--palette-primary)55;
        border-radius: 12px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-box:hover {
        background: #fff;
        border-color: var(--palette-primary);
    }

    .file-upload-box .file-label {
        flex: 1;
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-upload-box input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-box i {
        color: var(--palette-primary);
        font-size: 1.1rem;
        margin-left: 10px;
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
