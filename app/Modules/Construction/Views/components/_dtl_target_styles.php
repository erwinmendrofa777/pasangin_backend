<style>
    .tbl-outer {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.015);
        background: #fff;
        margin-bottom: 28px;
    }

    table.table-schedule {
        min-width: 1000px;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    table.table-schedule th {
        background: var(--palette-primary) !important;
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: #ffffff !important;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        padding: 14px 10px;
        border: none !important;
    }

    table.table-schedule thead tr:first-child th:first-child {
        border-top-left-radius: 12px;
    }

    table.table-schedule thead tr:first-child th:last-child {
        border-top-right-radius: 12px;
    }

    table.table-schedule th.left {
        text-align: left;
    }

    table.table-schedule th.week-th {
        border-right: 1px solid rgba(255, 255, 255, 0.15) !important;
    }

    table.table-schedule th.week-th:last-child {
        border-right: none !important;
    }

    table.table-schedule th .text-primary {
        color: #ffffff !important;
    }

    table.table-schedule th .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    table.table-schedule td {
        vertical-align: middle;
        font-size: 13px;
        color: #334155;
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        border-right: none;
    }

    table.table-schedule td.cell-bar {
        border-right: 1px solid #f1f5f9;
    }

    table.table-schedule td.cell-bar:last-child {
        border-right: none;
    }

    table.table-schedule td.num {
        text-align: center;
        font-weight: 500;
    }

    table.table-schedule tr.group-header {
        cursor: pointer;
        user-select: none;
    }

    table.table-schedule tr.group-header:hover td {
        background: linear-gradient(90deg, #ffe4e6 0%, #fef2f2 100%);
    }

    .group-chevron {
        font-size: 11px;
        color: #f43f5e;
        transition: transform 0.2s ease;
    }

    table.table-schedule tr.group-header td {
        background: linear-gradient(90deg, #fff1f2 0%, #fff 100%);
        font-weight: 700;
        color: var(--palette-primary);
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        border-left: 4px solid var(--palette-primary);
        border-top: 1px solid #fecdd3;
        border-bottom: 1px solid #fecdd3;
        padding-top: 12px;
        padding-bottom: 12px;
        border-right: none;
    }

    table.table-schedule tr.subgroup-header td {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
        font-size: 12px;
        padding-left: 28px !important;
        border-left: 4px solid #cbd5e1;
        border-bottom: 1px solid #e2e8f0;
        border-right: none;
    }

    table.table-schedule td.cell-bar {
        padding: 6px 0 !important; /* Remove horizontal padding so bars touch */
        text-align: center;
        background-color: #fafbfc;
        transition: background-color 0.15s ease;
    }

    table.table-schedule td.cell-bar.cell-bar-start,
    table.table-schedule td.cell-bar.cell-bar-middle {
        border-right: none !important;
    }

    table.table-schedule .cell-bar.week-active {
        background-color: rgba(255, 92, 92, 0.02);
    }

    table.table-schedule td.cell-bar:hover {
        background-color: #f1f5f9;
    }

    table.table-schedule .bar {
        height: 16px;
        background: var(--palette-primary);
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.35);
        transition: all 0.2s ease;
    }

    table.table-schedule .bar.bg-warning {
        background: #f59e0b !important;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35);
    }

    /* Unified continuous bar segment shapes */
    table.table-schedule .bar.bar-single {
        border-radius: 20px;
        width: calc(100% - 8px);
        margin: 0 4px;
    }

    table.table-schedule .bar.bar-start {
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        width: calc(100% - 4px);
        margin-left: 4px;
    }

    table.table-schedule .bar.bar-end {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        width: calc(100% - 4px);
        margin-right: 4px;
    }

    table.table-schedule .bar.bar-middle {
        border-radius: 0;
        width: 100%;
        margin: 0;
    }

    table.table-schedule tr.item-row:hover .bar {
        transform: scaleY(1.1); /* Hover scale vertically so connection is maintained */
    }

    table.table-schedule .week-th {
        min-width: 80px;
    }

    /* Baris pekerjaan bisa diklik */
    table.table-schedule tr.item-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    table.table-schedule tr.item-row:hover {
        background-color: #f8fafc !important;
    }

    table.table-schedule tr.item-row.selected {
        background-color: rgba(255, 92, 92, 0.06) !important;
    }

    table.table-schedule tr.item-row.selected td {
        border-bottom-color: rgba(255, 92, 92, 0.12);
    }

    table.table-schedule tr.total-row td,
    table.table-schedule tr.total-row-add td {
        background: #f8fafc;
        font-weight: 700;
        color: #1e293b;
        border-top: 2px solid #cbd5e1;
        border-bottom: 2px solid #cbd5e1;
        padding-top: 14px;
        padding-bottom: 14px;
        border-right: none;
    }

    .btn-del {
        background: transparent;
        border: none;
        cursor: pointer;
        color: #adb5bd;
        font-size: 14px;
        padding: 2px 6px;
        border-radius: 4px;
        line-height: 1;
    }

    .btn-del:hover {
        color: #fc544b;
        background: #fff5f5;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 767px) {
        .tbl-outer {
            -webkit-overflow-scrolling: touch;
        }

        /* Jadikan section-title lebih kecil */
        .section-title {
            font-size: 0.78rem !important;
            margin-bottom: 6px !important;
        }

        /* Form panel: stack semua kolom jadi full-width */
        .card .row.g-2>[class*='col-'] {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        .card .col-auto {
            width: 100% !important;
        }

        /* Input & select jadi full-width di form panel */
        .card .form-control,
        .card .form-select {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Tombol simpan full-width */
        .card .btn {
            width: 100%;
            text-align: center;
        }

        /* ---- TABLE: Sembunyikan kolom non-esensial ---- */
        /* col-3: JUMLAH HARGA */
        table.table-schedule thead tr th:nth-child(3),
        table.table-schedule tbody tr.item-row td:nth-child(3),
        table.table-schedule tbody tr.total-row td:nth-child(2),
        table.table-schedule tbody tr.total-row-add td:nth-child(2) {
            display: none !important;
        }

        /* col-7: JUMLAH HARGA REALISASI */
        table.table-schedule thead tr th:nth-child(7),
        table.table-schedule tbody tr.item-row td:nth-child(7),
        table.table-schedule tbody tr.total-row td:nth-child(6),
        table.table-schedule tbody tr.total-row-add td:nth-child(6) {
            display: none !important;
        }

        /* col-8: SELISIH JUMLAH HARGA */
        table.table-schedule thead tr th:nth-child(8),
        table.table-schedule tbody tr.item-row td:nth-child(8),
        table.table-schedule tbody tr.total-row td:nth-child(7),
        table.table-schedule tbody tr.total-row-add td:nth-child(7) {
            display: none !important;
        }

        /* col-9+: Minggu (Gantt chart) + kolom hapus */
        table.table-schedule thead tr th:nth-child(n+9),
        table.table-schedule tbody tr.item-row td:nth-child(n+9),
        table.table-schedule tbody tr.total-row td:nth-child(n+8),
        table.table-schedule tbody tr.total-row-add td:nth-child(n+8) {
            display: none !important;
        }

        /* Kompakkan sel yang tersisa */
        table.table-schedule th,
        table.table-schedule td {
            font-size: 11px !important;
            padding: 6px 8px !important;
        }

        /* Kolom uraian: boleh wrap teks */
        table.table-schedule thead tr th:nth-child(2),
        table.table-schedule tbody tr.item-row td:nth-child(2) {
            white-space: normal;
            min-width: 130px;
            max-width: 200px;
        }

        /* Kolom NO lebih sempit */
        table.table-schedule thead tr th:nth-child(1),
        table.table-schedule tbody tr td:nth-child(1) {
            min-width: 28px;
            width: 28px;
        }

        /* Badge minggu di mobile (rangkuman Gantt) */
        .week-badge-mobile {
            display: inline-block;
            font-size: 10px;
            background: #e8ecff;
            color: var(--palette-primary);
            border-radius: 4px;
            padding: 1px 5px;
            margin-top: 2px;
        }
    }

    @media (min-width: 768px) {
        .week-badge-mobile {
            display: none;
        }

        .mobile-scroll-hint {
            display: none !important;
        }
    }

    /* Scroll hint */
    .mobile-scroll-hint {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.72rem;
        color: #6c757d;
        padding: 5px 0 3px;
        animation: swipeHintAnim 2s ease-in-out infinite;
    }

    @keyframes swipeHintAnim {

        0%,
        100% {
            opacity: 0.5;
            transform: translateX(0);
        }

        50% {
            opacity: 1;
            transform: translateX(4px);
        }
    }
    /* ===== PROGRESS MODAL CARDS ===== */
    .prog-card {
        position: relative;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        padding: 14px 16px;
        margin-bottom: 12px;
        transition: box-shadow .2s, transform .15s;
        overflow: visible;
    }

    .prog-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        transform: translateY(-1px);
    }

    .prog-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 14px 0 0 14px;
    }

    .prog-card.st-approved::before { background: #22c55e; }
    .prog-card.st-rejected::before { background: #ef4444; }
    .prog-card.st-pending::before  { background: #f59e0b; }

    /* Status pill (clickable dropdown trigger) */
    .prog-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 4px 10px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: .5px;
        transition: opacity .15s, transform .1s;
        position: relative;
    }

    .prog-status-pill:hover { opacity: .85; transform: scale(1.03); }
    .prog-status-pill.approved { background: #dcfce7; color: #16a34a; }
    .prog-status-pill.rejected { background: #fee2e2; color: #dc2626; }
    .prog-status-pill.pending  { background: #fef9c3; color: #b45309; }

    .prog-status-pill .prog-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .approved .prog-dot { background: #16a34a; }
    .rejected .prog-dot { background: #dc2626; }
    .pending  .prog-dot { background: #b45309; }

    /* Custom dropdown for status */
    .prog-status-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        z-index: 9999;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 150px;
        padding: 6px 0;
        display: none;
        animation: dropIn .15s ease;
    }

    .prog-status-dropdown.open { display: block; }

    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .prog-status-dropdown a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: background .12s;
        cursor: pointer;
    }

    .prog-status-dropdown a:hover { background: #f9fafb; }

    .prog-status-dropdown a .dot-dd {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .prog-status-dropdown a.opt-pending  .dot-dd { background: #f59e0b; }
    .prog-status-dropdown a.opt-approved .dot-dd { background: #22c55e; }
    .prog-status-dropdown a.opt-rejected .dot-dd { background: #ef4444; }

    .prog-status-dropdown a.opt-pending:hover  { color: #b45309; }
    .prog-status-dropdown a.opt-approved:hover { color: #16a34a; }
    .prog-status-dropdown a.opt-rejected:hover { color: #dc2626; }

    /* Week & volume badges in card */
    .prog-week-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(99,102,241,.1);
        color: #6366f1;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 9px;
    }

    .prog-vol-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border: 1px solid #e2e8f0;
    }

    .prog-photo-wrap img {
        max-width: 130px;
        max-height: 95px;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        object-fit: cover;
        transition: transform .15s, box-shadow .15s;
        cursor: pointer;
    }

    .prog-photo-wrap img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .prog-no-photo-sm {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        color: #94a3b8;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 5px 10px;
        margin-top: 6px;
    }

    .prog-keterangan {
        font-size: 0.8rem;
        color: #475569;
        line-height: 1.5;
        margin: 6px 0;
    }

    .prog-date-label {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* Spinner for status update */
    .prog-status-loading {
        display: inline-block;
        width: 10px; height: 10px;
        border: 2px solid rgba(0,0,0,.15);
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin .5s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
</style>
