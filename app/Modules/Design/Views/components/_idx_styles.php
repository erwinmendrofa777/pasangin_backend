<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 60%, #084298 100%);
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 44px !important;
        border-radius: 12px !important;
        border: 1.5px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.88rem;
        height: 42px;
    }

    .search-wrapper input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #adb5bd;
        opacity: 0.8;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f6ff;
    }

    #table-1 thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #f8fbff !important;
    }

    #table-1 tbody td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== BADGES ===== */
    .status-badge {
        border-radius: 50px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Status Design: PENDING, SURVEY_SCHEDULED, PAYMENT_VERIFIED, COMPLETED, CANCELLED */
    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-survey {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-payment {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #e9ecef;
        color: #495057;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action-detail {
        background: #0d6efd;
        color: #ffffff;
    }

    .btn-action-detail:hover {
        background: #0b5ed7;
        color: #ffffff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #ffffff;
    }

    .btn-action-delete:hover {
        background: #b02a37;
        color: #fff;
    }

    .btn-action-disabled {
        background: #f1f3f5;
        color: #adb5bd;
        cursor: not-allowed;
    }

    .btn-action-disabled:hover {
        transform: none;
        box-shadow: none;
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
        color: #0d6efd;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    .dataTables_paginate .page-item:not(.active) .page-link:hover {
        background: #e7f0ff !important;
        border-color: #e7f0ff !important;
        color: #0d6efd !important;
    }

    /* ===== TASK SCROLL ===== */
    .task-scroll-container::-webkit-scrollbar {
        height: 8px;
    }

    .task-scroll-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.02);
        border-radius: 10px;
    }

    .task-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .task-scroll-container::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }

    .task-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid #edf2f7;
        background: #ffffff;
        position: relative;
    }

    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
        border-color: #e2e8f0;
    }

    .btn-minimal {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 50%;
        transition: all 0.2s;
        color: #0d6efd;
    }

    .task-card:hover .btn-minimal {
        background: #0d6efd;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25) !important;
    }

    @media (max-width: 768px) {
        .table-card-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px !important;
            padding: 20px 16px !important;
            background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%) !important;
        }

        .table-card-header h6 {
            font-size: 1rem !important;
            width: 100%;
        }

        .search-wrapper {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dt-footer {
            flex-direction: column;
            gap: 12px;
            padding: 16px !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }

    .btn-export-pdf {
        background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 8px 16px;
        font-size: 0.88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        height: 42px;
    }

    .btn-export-pdf:hover {
        background: linear-gradient(135deg, #c82333 0%, #a71d2a 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }
</style>
