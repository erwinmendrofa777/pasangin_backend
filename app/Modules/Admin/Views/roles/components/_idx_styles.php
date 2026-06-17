<style>
    /* ===== HEADER CARD ===== */
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
    }

    /* ===== PREMIUM CUSTOM SEARCH ===== */
    .search-wrapper {
        position: relative;
        display: inline-block;
    }

    .search-input {
        display: block !important;
        width: 100% !important;
        height: 40px !important;
        border-radius: 10px !important;
        font-size: 0.82rem !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #334155 !important;
        font-weight: 600 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01) !important;
        outline: none !important;
    }

    .search-input:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }

    .search-input:focus {
        border-color: var(--palette-primary) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.12), 0 6px 16px rgba(255, 92, 92, 0.06) !important;
        transform: translateY(-1px);
        color: #0f172a !important;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 5;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .search-input:focus ~ .search-icon,
    .search-input:hover ~ .search-icon {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.15) rotate(15deg) !important;
    }

    .search-input::placeholder {
        color: #94a3b8;
        opacity: 0.8;
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

    /* ===== TABLE ===== */
    #table-1 {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
    }

    #table-1 thead tr {
        background: var(--palette-primary) !important;
    }

    #table-1 thead th {
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

    #table-1 thead th:first-child {
        border-top-left-radius: 16px !important;
    }

    #table-1 thead th:last-child {
        border-top-right-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:first-child {
        border-bottom-left-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:last-child {
        border-bottom-right-radius: 16px !important;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #fffafa !important;
    }

    #table-1 tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== ROLE ICON ===== */
    .role-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .role-icon-super {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        color: #fff;
        box-shadow: 0 3px 8px rgba(255, 92, 92, 0.3);
    }

    .role-icon-custom {
        background: #fff5f5;
        color: var(--palette-primary);
        border: 2px solid #ffd3d3;
    }

    /* ===== PERMISSION BADGES ===== */
    .perm-pill {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        padding: 4px 12px !important;
        border-radius: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin: 2px !important;
        white-space: nowrap !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
        transition: all 0.2s ease !important;
    }

    .perm-pill:hover {
        transform: translateY(-1px) !important;
    }

    .pill-parent {
        background: rgba(13, 148, 136, 0.08) !important;
        color: #0d9488 !important;
        border: 1px solid rgba(13, 148, 136, 0.18) !important;
    }

    .pill-parent:hover {
        background: rgba(13, 148, 136, 0.14) !important;
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.1) !important;
    }

    .pill-action {
        background: rgba(99, 102, 241, 0.08) !important;
        color: #4f46e5 !important;
        border: 1px solid rgba(99, 102, 241, 0.18) !important;
    }

    .pill-action:hover {
        background: rgba(99, 102, 241, 0.14) !important;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.1) !important;
    }

    .pill-full {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%) !important;
        color: #fff !important;
        border: none !important;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.2) !important;
        font-size: 0.73rem !important;
        padding: 4px 14px !important;
    }

    .pill-full:hover {
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.35) !important;
    }

    .pill-more {
        background: #e2e8f0 !important;
        color: #475569 !important;
        font-size: 0.67rem !important;
        padding: 3px 9px !important;
        border-radius: 20px !important;
        font-weight: 700 !important;
        margin: 2px !important;
        display: inline-flex !important;
        align-items: center !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
        border: 1px solid #cbd5e1 !important;
        transition: all 0.2s ease !important;
    }

    .pill-more:hover {
        background: #cbd5e1 !important;
        transform: translateY(-1px) !important;
    }

    /* ===== SEGMENTED PERMISSION BADGES ===== */
    .perm-container {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 6px;
        white-space: nowrap;
    }

    .perm-badge-group {
        display: inline-flex;
        align-items: center;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1;
        vertical-align: middle;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.06);
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
    }

    .perm-badge-group:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
        border-color: rgba(255, 92, 92, 0.2);
    }

    .perm-badge-group .badge-prefix {
        background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);
        color: #fff;
        padding: 5px 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .perm-badge-group.badge-system .badge-prefix {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    }
    
    .perm-badge-group.badge-project .badge-prefix {
        background: linear-gradient(135deg, #0284c7 0%, #075985 100%);
    }

    .perm-badge-group.badge-content .badge-prefix {
        background: linear-gradient(135deg, #db2777 0%, #9d174d 100%);
    }

    .perm-badge-group .badge-suffix {
        background: #f8fafc;
        color: #475569;
        padding: 5px 8px;
        border-left: 1px solid rgba(0, 0, 0, 0.06);
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    /* ===== SUPER ADMIN PREMIUM BADGE ===== */
    .super-access-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.12);
        border: 1px solid rgba(255, 92, 92, 0.25);
        transition: all 0.22s ease;
    }

    .super-access-badge:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.2);
    }

    .super-access-badge .badge-text {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        color: #fff;
        padding: 5px 10px;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .super-access-badge .badge-detail {
        background: #fff5f5;
        color: var(--palette-primary);
        padding: 5px 10px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* ===== MORE ITEMS PILL ===== */
    .pill-more-interactive {
        background: #e2e8f0 !important;
        color: #475569 !important;
        font-size: 0.68rem !important;
        padding: 5px 10px !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
        letter-spacing: 0.3px !important;
        border: 1px solid #cbd5e1 !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        vertical-align: middle;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .pill-more-interactive:hover {
        background: var(--palette-primary) !important;
        color: #fff !important;
        border-color: var(--palette-primary) !important;
        transform: translateY(-1.5px) !important;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25) !important;
    }


    /* ===== ROLE TYPE BADGE ===== */
    .role-type-badge {
        font-size: 0.68rem;
        font-weight: 800;
        padding: 2px 9px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
        margin-top: 4px;
    }

    .role-type-super {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        color: #fff;
    }

    .role-type-custom {
        background: #ffe5e5;
        color: var(--palette-primary);
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 36px !important;
        height: 36px !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: none !important;
        font-size: 0.85rem !important;
    }

    .btn-action-edit {
        background-color: #ff9f43 !important;
        border-color: #ff9f43 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.15) !important;
    }

    .btn-action-edit:hover {
        background-color: #f08f2e !important;
        border-color: #f08f2e !important;
        box-shadow: 0 6px 16px rgba(255, 159, 67, 0.3) !important;
        transform: translateY(-2px) !important;
        color: #fff !important;
    }

    .btn-action-delete {
        background-color: #ff4d4d !important;
        border-color: #ff4d4d !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.15) !important;
    }

    .btn-action-delete:hover {
        background-color: #e04444 !important;
        border-color: #e04444 !important;
        box-shadow: 0 6px 16px rgba(255, 77, 77, 0.3) !important;
        transform: translateY(-2px) !important;
        color: #fff !important;
    }

    .btn-action-lock {
        background-color: #94a3b8 !important;
        border-color: #94a3b8 !important;
        color: #fff !important;
        cursor: not-allowed !important;
        box-shadow: 0 4px 10px rgba(148, 163, 184, 0.15) !important;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px;
        border-top: 1px solid #f0f4fa;
        background: #fafcff;
    }

    .dataTables_info {
        font-size: 0.82rem;
        color: #6c757d !important;
    }

    .dataTables_paginate .page-item .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        margin: 0 3px;
        border: 1px solid transparent;
        color: var(--palette-primary) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }

    .dataTables_paginate .page-item.disabled .page-link {
        color: #98a6ad !important;
        opacity: 0.5;
        background: transparent !important;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.3);
    }

    .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #ffe5e5 !important;
        border-color: #ffe5e5 !important;
        color: var(--palette-primary) !important;
    }

    @media (max-width: 768px) {
        .search-wrapper {
            width: 100% !important;
        }

        .dt-footer {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 12px;
            padding: 16px !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: center !important;
            width: 100% !important;
        }

        .dataTables_paginate .pagination {
            justify-content: center !important;
            margin: 0 !important;
        }

        .dataTables_info {
            text-align: center !important;
            width: 100% !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }
</style>
