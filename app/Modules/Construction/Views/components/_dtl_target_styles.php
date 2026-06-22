<style>
    .tbl-outer {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.015);
        background: #fff;
        margin-bottom: 28px;
    }

    /* Standard Premium Header Card */
    .header-card {
        background: #ffffff !important;
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden;
    }

    .header-icon-wrap {
        width: 48px;
        height: 48px;
        background: rgba(255, 92, 92, 0.1) !important;
        color: var(--palette-primary) !important;
        border-radius: 12px !important;
        border: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
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
        border-right: 1px solid #e2e8f0 !important;
    }

    table.table-schedule td.cell-bar:last-child {
        border-right: none !important;
    }

    table.table-schedule td.num {
        text-align: center;
        font-weight: 500;
    }

    table.table-schedule tr.group-header {
        cursor: pointer;
        user-select: none;
    }



    .group-chevron {
        font-size: 11px;
        color: var(--palette-primary);
        transition: transform 0.2s ease;
    }

    table.table-schedule tr.group-header td {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%) !important;
        font-weight: 700;
        color: #1e293b !important;
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        border-left: 4px solid var(--palette-primary) !important;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding-top: 12px;
        padding-bottom: 12px;
        border-right: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    /* Visual feedback when group is collapsed */
    table.table-schedule tr.group-header.collapsed td {
        background: #f1f5f9 !important;
        color: #64748b !important;
    }

    table.table-schedule tr.subgroup-header td {
        background: #fdfdfd;
        font-weight: 600;
        color: #475569;
        font-size: 12px;
        padding-left: 32px !important;
        border-left: 4px solid #cbd5e1;
        border-bottom: 1px solid #e2e8f0;
        border-right: none;
        letter-spacing: 0.3px;
    }

    table.table-schedule td.cell-bar {
        padding: 4px 0 !important; /* Minimal vertical padding so bars touch horizontally */
        vertical-align: middle;
        text-align: center;
        background-color: #fafbfc;
        transition: background-color 0.15s ease;
    }

    /* When wg-hidden is applied, ensure padding truly becomes 0 even with !important above */
    table.table-schedule td.cell-bar.wg-hidden,
    th.week-th.wg-hidden {
        padding: 0 !important;
    }

    table.table-schedule td.cell-bar.cell-bar-start,
    table.table-schedule td.cell-bar.cell-bar-middle {
        border-right: none !important;
    }

    table.table-schedule .cell-bar.week-active {
        background-color: transparent !important;
        background-image: repeating-linear-gradient(
            to right,
            transparent,
            transparent calc(100% / var(--colspan) - 1px),
            #e2e8f0 calc(100% / var(--colspan) - 1px),
            #e2e8f0 calc(100% / var(--colspan))
        ) !important;
    }



    /* ===== GANTT BAR CONTAINER & PROGRESS ===== */
    .gantt-bar-container {
        position: relative;
        height: 28px;
        border-radius: 14px; /* pill shape */
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin: 0 4px;
        width: calc(100% - 8px);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        box-sizing: border-box;
        background: #ffffff; /* force solid white base background */
    }

    /* Progress fill background */
    .gantt-progress-fill {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        height: 100%;
        border-radius: inherit;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }

    /* Content wrapper */
    .gantt-bar-content {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 8px;
        box-sizing: border-box;
    }

    .gantt-bar-text {
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: 0.3px;
        transition: color 0.2s ease;
    }

    /* --- STATUS COLORS (MAIN & ADDENDUM UNIFIED) --- */
    /* 0% progress: Kuning Cerah / Sunflower Yellow (Pending / Belum dikerjakan) */
    .bar-main.status-planned,
    .bar-addendum.status-planned {
        background: #eab308 !important; /* bright sunflower yellow-500 */
        border: 1.5px solid #ca8a04 !important; /* darker yellow-600 border */
    }
    .bar-main.status-planned .gantt-bar-text,
    .bar-addendum.status-planned .gantt-bar-text {
        color: #1e293b !important; /* dark slate text for maximum readability on bright yellow */
    }
    .bar-main.status-planned:hover,
    .bar-addendum.status-planned:hover {
        background: #ca8a04 !important; /* hover to slightly darker yellow */
        border-color: #a16207 !important;
        transform: translateY(-1px);
    }

    /* 1% - 99% progress: Hijau (Ada Progress) */
    .bar-main.status-progress,
    .bar-addendum.status-progress {
        background: #eab308 !important; /* base background is bright sunflower yellow */
        border: 1.5px solid #ca8a04 !important;
    }
    .bar-main.status-progress .gantt-progress-fill,
    .bar-addendum.status-progress .gantt-progress-fill {
        background: #10b981 !important; /* solid emerald-500 progress fill */
    }
    .bar-main.status-progress .gantt-bar-text,
    .bar-addendum.status-progress .gantt-bar-text {
        color: #ffffff !important; /* white text */
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6) !important; /* shadow so text is readable over both green & yellow */
    }
    .bar-main.status-progress:hover,
    .bar-addendum.status-progress:hover {
        border-color: #059669 !important;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
        transform: translateY(-1px);
    }

    /* 100% progress: Hijau (Selesai) */
    .bar-main.status-completed,
    .bar-addendum.status-completed {
        background: #10b981 !important; /* solid emerald-500 */
        border: 1.5px solid #059669 !important; /* emerald-600 */
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
    }
    .bar-main.status-completed .gantt-bar-text,
    .bar-addendum.status-completed .gantt-bar-text {
        color: #ffffff !important;
    }
    .bar-main.status-completed:hover,
    .bar-addendum.status-completed:hover {
        background: #059669 !important; /* solid emerald-600 */
        border-color: #047857 !important;
        box-shadow: 0 6px 14px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
    }



    /* ===== LOWONGAN & PELAMAR BUTTONS ===== */
    .btn-loker-create {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        background-color: #ffffff;
        color: #10b981;
        border: 1.5px dashed #10b981;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-loker-create:hover {
        background-color: rgba(16, 185, 129, 0.06);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
        transform: translateY(-0.5px);
    }
    .btn-loker-create i {
        font-size: 10px;
    }

    .loker-active-group {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-loker-applicants {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        background-color: rgba(99, 102, 241, 0.08);
        color: #4f46e5;
        border: 1px solid rgba(99, 102, 241, 0.2);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-loker-applicants:hover {
        background-color: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.35);
        transform: translateY(-0.5px);
    }
    .btn-loker-applicants .applicant-count {
        background-color: #4f46e5;
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        padding: 1.5px 6px;
        border-radius: 10px;
        line-height: 1;
    }

    .btn-loker-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-loker-edit:hover {
        background-color: #ffffff;
        color: #10b981;
        border-color: #10b981;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transform: translateY(-0.5px);
    }
    .btn-loker-edit i {
        font-size: 10px;
    }

    table.table-schedule .week-th {
        min-width: 80px;
    }

    /* ===== WEEK GROUP COLLAPSE TRIGGER ===== */
    th.week-group-trigger {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    th.week-group-trigger:hover {
        filter: brightness(0.9) contrast(1.1);
    }
    .week-group-chevron {
        display: inline-block;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Hover micro-animation: nudge chevron */
    th.week-group-trigger:hover .week-group-chevron {
        transform: translateX(-3px);
    }
    th.week-group-trigger.collapsed-trigger:hover .week-group-chevron {
        transform: rotate(180deg) translateX(-3px);
    }

    th.week-group-trigger.collapsed-trigger {
        background: #c2410c !important; /* solid premium dark orange-red */
        border-left: 1px solid rgba(255, 255, 255, 0.1) !important;
        min-width: 90px !important;
        width: 90px !important;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
    }
    th.week-group-trigger.collapsed-trigger .week-group-chevron {
        transform: rotate(180deg); /* point right */
    }
    th.week-group-trigger.collapsed-trigger .text-muted {
        color: rgba(255, 255, 255, 0.45) !important;
    }

    /* Week cell columns that are collapsible */
    .week-col-cell {
        transition: width 0.3s ease, min-width 0.3s ease, padding 0.3s ease, opacity 0.3s ease;
        overflow: hidden;
        white-space: nowrap;
    }
    .week-col-cell.wg-hidden {
        width: 0 !important;
        min-width: 0 !important;
        max-width: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        opacity: 0;
        overflow: hidden;
        border: none !important;
        pointer-events: none;
    }

    /* Baris pekerjaan bisa diklik */
    table.table-schedule tr.item-row td.text-start,
    table.table-schedule tr.item-row td.week-active {
        cursor: pointer;
    }

    table.table-schedule tr.item-row {
        transition: all 0.2s ease;
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
        /* col-2 & col-3: STATUS TAGIHAN & JUMLAH HARGA (sebelumnya col-2 & col-3) */
        table.table-schedule thead tr th:nth-child(2),
        table.table-schedule tbody tr.item-row td:nth-child(2),
        table.table-schedule tbody tr.total-row td:nth-child(2),
        table.table-schedule tbody tr.total-row-add td:nth-child(2),
        table.table-schedule thead tr th:nth-child(3),
        table.table-schedule tbody tr.item-row td:nth-child(3),
        table.table-schedule tbody tr.total-row td:nth-child(3),
        table.table-schedule tbody tr.total-row-add td:nth-child(3) {
            display: none !important;
        }

        /* col-6: JUMLAH HARGA REALISASI (sebelumnya col-5) */
        table.table-schedule thead tr th:nth-child(6),
        table.table-schedule tbody tr.item-row td:nth-child(6),
        table.table-schedule tbody tr.total-row td:nth-child(6),
        table.table-schedule tbody tr.total-row-add td:nth-child(6) {
            display: none !important;
        }

        /* col-7: SELISIH JUMLAH HARGA (sebelumnya col-6) */
        table.table-schedule thead tr th:nth-child(7),
        table.table-schedule tbody tr.item-row td:nth-child(7),
        table.table-schedule tbody tr.total-row td:nth-child(7),
        table.table-schedule tbody tr.total-row-add td:nth-child(7) {
            display: none !important;
        }

        /* col-8+: Minggu (Gantt chart) + kolom hapus (sebelumnya col-7+) */
        table.table-schedule thead tr th:nth-child(n+8),
        table.table-schedule tbody tr.item-row td:nth-child(n+8),
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

        /* Kolom uraian: boleh wrap teks (sebelumnya col-1) */
        table.table-schedule thead tr th:nth-child(1),
        table.table-schedule tbody tr.item-row td:nth-child(1) {
            white-space: normal;
            min-width: 130px;
            max-width: 200px;
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

    /* Detail columns that can be toggled */
    .detail-col {
        display: none !important; /* Hidden by default */
    }
    
    /* When active, show them */
    body.show-detail-cols .detail-col {
        display: table-cell !important;
    }

    /* Custom premium primary button matching standard bootstrap heights */
    .btn-target-primary {
        font-family: 'Outfit', 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover)) !important;
        border: none !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-target-primary:hover {
        background: linear-gradient(135deg, var(--palette-primary-hover), var(--palette-primary)) !important;
        box-shadow: 0 6px 20px rgba(255, 92, 92, 0.35);
        color: #fff !important;
        transform: translateY(-1px);
    }

    .btn-target-primary:active {
        transform: translateY(0) scale(0.98);
    }

    /* ===== STICKY COLUMNS / FREEZE PANE SYSTEM ===== */
    @media (min-width: 768px) {
        .tbl-outer {
            overflow-x: auto;
            position: relative;
        }

        table.table-schedule {
            border-collapse: separate;
        }

        /* Freeze Column 1 (URAIAN PEKERJAAN) - NO column removed */
        table.table-schedule th:nth-child(1),
        table.table-schedule td:nth-child(1) {
            position: sticky;
            left: 0;
            z-index: 5;
            background-color: #ffffff;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.04), inset -1px 0 0 #e2e8f0;
        }

        /* Ensure Table Headers stay on top */
        table.table-schedule th:nth-child(1) {
            z-index: 10;
            background: var(--palette-primary) !important;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.04), inset -1px 0 0 rgba(255, 255, 255, 0.15) !important;
        }



        /* Sticky Accordion Text */
        table.table-schedule tr.group-header td > div,
        table.table-schedule tr.subgroup-header td > div {
            position: sticky;
            left: 14px;
            display: inline-flex;
            align-items: center;
            z-index: 6;
        }

        table.table-schedule tr.subgroup-header td > div {
            left: 32px;
        }
    }

    /* Force background for separator th under all circumstances to avoid white headers */
    th.week-group-sep {
        background: var(--palette-primary) !important;
        background-color: var(--palette-primary) !important;
    }

    /* Hide total rows by default when detail columns are hidden */
    table.table-schedule tr.total-row,
    table.table-schedule tr.total-row-add {
        display: none !important;
    }

    /* Show total rows only when detail columns are shown */
    body.show-detail-cols table.table-schedule tr.total-row,
    body.show-detail-cols table.table-schedule tr.total-row-add {
        display: table-row !important;
    }

    /* ============================================== */
    /* --- PREMIUM REDESIGNED MODALS & COMPONENTS --- */
    /* ============================================== */
    
    /* Modern scrollbar for modals */
    .modal-dialog-scrollable .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Custom Search wrapper */
    .search-wrapper {
        position: relative;
        margin-bottom: 16px;
    }
    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }
    .search-wrapper .search-input {
        padding-left: 38px;
        padding-right: 16px;
        height: 42px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background-color: #ffffff;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.2s ease;
        width: 100%;
    }
    .search-wrapper .search-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        background-color: #ffffff;
        outline: none;
    }

    /* Pills & Tabs modernizer */
    #kelola-loker-tabs {
        background: #e2e8f0;
        padding: 4px;
        border-radius: 10px;
        display: inline-flex;
    }
    #kelola-loker-tabs .nav-link {
        color: #475569;
        border-radius: 8px !important;
        font-size: 12.5px;
        font-weight: 600;
        padding: 6px 16px;
        transition: all 0.2s ease;
        background: transparent;
        border: none !important;
    }
    #kelola-loker-tabs .nav-link.active {
        color: #ffffff !important;
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    /* Premium Vacancy Table */
    .modal-body table.table-loker {
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
        background: transparent !important;
    }
    .modal-body table.table-loker thead th {
        background: transparent !important;
        color: #64748b !important;
        border: none !important;
        padding: 8px 16px !important;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    .modal-body table.table-loker tbody tr {
        background: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .modal-body table.table-loker tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.04) !important;
    }
    .modal-body table.table-loker tbody td {
        border: none !important;
        padding: 12px 16px !important;
        background: #ffffff !important;
    }
    .modal-body table.table-loker tbody td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .modal-body table.table-loker tbody td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    /* Skill chips selector styling */
    .skills-chips-wrapper {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        max-height: 200px;
        overflow-y: auto;
        transition: all 0.2s ease;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .skills-chips-wrapper:focus-within {
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
    }
    .skill-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .skill-chip:hover {
        background-color: #e2e8f0;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .skill-chip.active {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-color: #28a745 !important;
        color: #198754 !important;
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.08);
    }
    .skill-chip.active i {
        color: #28a745;
    }
    .skill-chip i {
        font-size: 10px;
        transition: transform 0.2s ease;
    }
    .skill-chip.active i {
        transform: scale(1.1);
    }

    /* Custom info cards in Create vacancy modal */
    .info-display-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
    }
    .info-display-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border-color: #cbd5e1;
    }
    .info-display-card .card-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        margin-bottom: 12px;
    }
    .info-display-card.ic-green {
        border-left: 4px solid #28a745 !important;
    }
    .info-display-card.ic-green .card-icon-wrap {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    .info-display-card.ic-blue {
        border-left: 4px solid #2563eb !important;
    }
    .info-display-card.ic-blue .card-icon-wrap {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }
    .info-display-card .card-label {
        font-size: 9.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .info-display-card .card-value {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
    }
    .info-display-card .card-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 4px;
    }

    /* Custom inputs & textareas styling */
    .modal-content .form-control, 
    .modal-content .form-select {
        border: 1.5px solid #e2e8f0;
        padding: 9px 12px;
        font-size: 13px;
        border-radius: 8px;
        color: #1e293b;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .modal-content .form-control:focus, 
    .modal-content .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .modal-content .form-control::placeholder {
        color: #94a3b8;
    }

    /* Upah breakdown style */
    .premium-breakdown-card {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 14px;
        font-size: 12.5px;
        color: #475569;
    }

    /* Mobile job mockup details */
    .job-preview-mockup {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .job-preview-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        padding: 20px;
        color: #ffffff;
        text-align: center;
    }
    .job-preview-header .mock-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 10px;
    }
    .job-preview-body {
        padding: 20px;
    }
    .job-preview-section {
        margin-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }
    .job-preview-section:last-child {
        margin-bottom: 0;
        border-bottom: none;
        padding-bottom: 0;
    }
    .job-preview-section-title {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .job-preview-salary-box {
        background: rgba(16, 185, 129, 0.05);
        border: 1px solid rgba(16, 185, 129, 0.15);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .job-preview-salary-box i {
        font-size: 18px;
        color: #10b981;
    }
    .job-preview-salary-box .salary-value {
        font-size: 16.5px;
        font-weight: 800;
        color: #065f46;
    }

    /* Applicant modern card */
    .applicant-card {
        background: #ffffff;
        border: 1.5px solid #f1f5f9;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        position: relative;
        overflow: hidden;
    }
    .applicant-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }
    .applicant-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #94a3b8;
    }
    .applicant-card.status-siap-kerja::before,
    .applicant-card.status-approved::before { background: #10b981; }
    .applicant-card.status-ditolak::before { background: #ef4444; }
    .applicant-card.status-proses-test::before { background: #3b82f6; }
    .applicant-card.status-proses-aktivasi::before { background: #06b6d4; }
    .applicant-card.status-berkas-diproses::before { background: #f59e0b; }

    .applicant-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
        text-transform: uppercase;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    
    .wa-btn {
        background-color: #25d366 !important;
        color: white !important;
        border: none !important;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        padding: 5px 10px;
        text-decoration: none !important;
    }
    .wa-btn:hover {
        background-color: #128c7e !important;
        box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3);
        transform: translateY(-0.5px);
    }

    /* Accordion Custom Styling */
    #accordionTenagaKerja .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }
    #accordionTenagaKerja .accordion-button:not(.collapsed) {
        color: #16a34a;
        background-color: #f0fdf4;
    }
    #accordionTenagaKerja .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(40, 167, 69, 0.2);
    }
    #accordionTenagaKerja .accordion-button::after {
        background-size: 0.8rem;
    }

    /* Toggle Status Lowongan Button style */
    .toggle-job-status-btn {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .toggle-job-status-btn:hover {
        transform: translateY(-1px);
        filter: brightness(0.95);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }
</style>
