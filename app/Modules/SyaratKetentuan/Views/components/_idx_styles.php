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

    /* ===== STYLING TABS PREMIUM ===== */
    .nav-pills.custom-pills .nav-link {
        color: var(--palette-primary) !important;
        font-weight: 700;
        border-radius: 50px;
        padding: 7px 20px;
        margin-right: 8px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        background: #fff5f5;
        border: 1px solid #ffd3d3 !important;
        font-size: 0.88rem;
    }

    .nav-pills.custom-pills .nav-link:hover {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.3) !important;
    }

    .nav-pills.custom-pills .nav-link.active {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.4) !important;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        overflow: hidden !important;
        background: #fff !important;
    }

    .table-card .card-body {
        padding: 0 !important;
    }

    /* ===== TABLE STYLING ===== */
    .table-custom {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
    }

    .table-custom thead tr {
        background: var(--palette-primary) !important;
    }

    .table-custom thead th {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: none !important;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .table-custom thead th:first-child {
        border-top-left-radius: 16px !important;
    }

    .table-custom thead th:last-child {
        border-top-right-radius: 16px !important;
    }

    .table-custom tbody tr:last-child td:first-child {
        border-bottom-left-radius: 16px !important;
    }

    .table-custom tbody tr:last-child td:last-child {
        border-bottom-right-radius: 16px !important;
    }

    .table-custom tbody tr {
        transition: background 0.15s ease !important;
    }

    .table-custom tbody tr:hover {
        background: #fffafa !important;
    }

    .table-custom td {
        vertical-align: middle !important;
        padding: 14px 12px !important;
        border-bottom: 1px solid #f0f4fa !important;
        font-size: 0.88rem !important;
        color: #343a40 !important;
    }

    .desc-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
        line-height: 1.4;
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

    .btn-edit {
        background-color: #ff9f43 !important;
        border-color: #ff9f43 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.15) !important;
    }

    .btn-edit:hover {
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

    @media (max-width: 768px) {
        .table-custom th,
        .table-custom td {
            white-space: nowrap !important;
        }
    }
</style>
