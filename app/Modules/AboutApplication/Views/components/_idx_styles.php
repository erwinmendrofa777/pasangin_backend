<style>
    /* ===== HEADER CARD ===== */
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
    }

    /* ===== PRIMARY BUTTON SHADOW OVERRIDE ===== */
    .btn-primary {
        background-color: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25) !important;
        transition: all 0.2s ease !important;
    }

    .btn-primary:hover {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.4) !important;
    }

    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary:active:focus,
    .btn-primary.active,
    .btn-primary:focus:active,
    .btn-primary.disabled:focus {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 92, 92, 0.3) !important;
    }

    /* ===== FORM CARD ===== */
    .form-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        background: #fff !important;
        overflow: hidden !important;
    }

    .form-card .card-body {
        padding: 30px !important;
    }

    .form-label-custom {
        font-size: 0.75rem !important;
        font-weight: 800 !important;
        color: #8e94a9 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 8px !important;
    }

    /* ===== EDITOR.JS WRAPPER ===== */
    .editor-wrapper {
        border: 2px solid #f1f3f9 !important;
        border-radius: 12px !important;
        min-height: 400px !important;
        background: #fff !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        overflow: hidden !important;
    }

    .editor-wrapper:focus-within {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.1) !important;
    }

    #editorjs {
        padding: 16px 24px !important;
        min-height: 380px !important;
    }

    .codex-editor__redactor {
        padding-bottom: 60px !important;
    }

    .ce-block__content,
    .ce-toolbar__content {
        max-width: 100% !important;
    }

    .ce-toolbar__plus:hover,
    .ce-toolbar__settings-btn:hover {
        background: var(--palette-primary) !important;
        color: #fff !important;
    }
</style>
