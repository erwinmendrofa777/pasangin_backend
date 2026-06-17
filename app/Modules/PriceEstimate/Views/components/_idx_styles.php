<style>
    /* ===== HEADER CARD ===== */
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
    }

    /* ===== STATS ROW ===== */
    .stat-mini-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        padding: 18px 20px !important;
        box-shadow: 0 4px 16px rgba(255, 92, 92, 0.04) !important;
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        background: #fff !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .stat-mini-card:hover {
        transform: translateY(-4px) !important;
        border-color: var(--palette-primary) !important;
        box-shadow: 0 12px 28px rgba(255, 92, 92, 0.12) !important;
    }

    .stat-mini-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: all 0.3s ease !important;
    }

    .stat-mini-card:hover .stat-mini-icon {
        transform: scale(1.1) !important;
    }

    .stat-mini-card .stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        color: #2d3748;
    }

    .stat-mini-card .stat-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-top: 2px;
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

    /* ===== CONCEPT CARDS ===== */
    .concept-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        background: #fff !important;
        margin-bottom: 30px !important;
        overflow: hidden !important;
        transition: all 0.3s ease !important;
    }

    .concept-card:hover {
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.03), 0 2px 8px rgba(0, 0, 0, 0.01) !important;
    }

    .concept-card .card-header {
        background: #fff !important;
        border-bottom: 1px solid #f0f4fa !important;
        padding: 20px 25px !important;
    }

    /* ===== TABLE STYLING ===== */
    .quality-table {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        overflow: hidden !important;
    }

    .quality-table thead tr {
        background: var(--palette-primary) !important;
    }

    .quality-table thead th {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.6px !important;
        text-transform: uppercase !important;
        border-bottom: none !important;
        border-top: none !important;
        padding: 14px 20px !important;
        white-space: nowrap;
    }

    .quality-table tbody tr {
        transition: background 0.15s ease !important;
    }

    .quality-table tbody tr:hover {
        background: #fffafa !important;
    }

    .quality-table tbody td {
        border-bottom: 1px solid #f0f4fa !important;
        padding: 14px 20px !important;
        font-size: 0.88rem !important;
        vertical-align: middle !important;
        color: #343a40 !important;
    }

    .quality-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* ===== PRICE TAGS ===== */
    .price-pill {
        background: rgba(255, 92, 92, 0.08) !important;
        color: var(--palette-primary) !important;
        font-weight: 700 !important;
        padding: 4px 12px !important;
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        display: inline-block !important;
        letter-spacing: 0.3px !important;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-circle-action {
        width: 36px !important;
        height: 36px !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: none !important;
        font-size: 0.85rem !important;
    }

    .btn-edit-quality {
        background-color: #ff9f43 !important;
        border-color: #ff9f43 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.15) !important;
    }

    .btn-edit-quality:hover {
        background-color: #f08f2e !important;
        border-color: #f08f2e !important;
        box-shadow: 0 6px 16px rgba(255, 159, 67, 0.3) !important;
        transform: translateY(-2px) !important;
        color: #fff !important;
    }

    .btn-circle-delete {
        background-color: #ff4d4d !important;
        border-color: #ff4d4d !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.15) !important;
    }

    .btn-circle-delete:hover {
        background-color: #e04444 !important;
        border-color: #e04444 !important;
        box-shadow: 0 6px 16px rgba(255, 77, 77, 0.3) !important;
        transform: translateY(-2px) !important;
        color: #fff !important;
    }

    /* ===== MODAL CUSTOM ===== */
    .modal-content-custom {
        border: none !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
    }

    .modal-header-custom {
        padding: 30px 30px 10px !important;
        border: none !important;
    }

    .modal-body-custom {
        padding: 10px 30px 30px !important;
    }

    .form-label-custom {
        font-size: 0.75rem !important;
        font-weight: 800 !important;
        color: #8e94a9 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 8px !important;
    }

    .form-control-custom {
        border-radius: 12px !important;
        border: 2px solid #f1f3f9 !important;
        padding: 12px 16px !important;
        font-weight: 600 !important;
        color: #495057 !important;
        transition: all 0.2s !important;
    }

    .form-control-custom:focus {
        border-color: var(--palette-primary) !important;
        background: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.1) !important;
        outline: none !important;
    }

    @media (max-width: 768px) {
        .quality-table th,
        .quality-table td {
            white-space: nowrap !important;
        }
    }
</style>
