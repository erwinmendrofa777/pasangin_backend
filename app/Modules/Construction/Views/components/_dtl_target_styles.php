<style>
    .tbl-outer {
        overflow-x: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    table.table-schedule {
        min-width: 700px;
        margin-bottom: 0;
    }

    table.table-schedule th {
        background: #f8f9fa;
        font-size: 12px;
        font-weight: 600;
        color: #34395e;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
    }

    table.table-schedule th.left {
        text-align: left;
    }

    table.table-schedule td {
        vertical-align: middle;
        font-size: 13px;
    }

    table.table-schedule td.num {
        text-align: center;
        color: #6c757d;
    }

    table.table-schedule tr.group-header td {
        background: #ffebeb;
        font-weight: 700;
        color: #b91c1c;
        font-size: 13px;
        border-top: 2px solid var(--palette-primary);
    }

    table.table-schedule tr.subgroup-header td {
        background: #fafafa;
        font-weight: 600;
        color: #6c757d;
        font-size: 12.5px;
        padding-left: 24px !important;
        font-style: italic;
    }

    table.table-schedule .bar {
        height: 14px;
        border-radius: 3px;
        background: var(--palette-primary);
        min-width: 6px;
    }

    table.table-schedule .cell-bar {
        padding: 5px 6px;
        text-align: center;
    }

    table.table-schedule .week-th {
        min-width: 68px;
    }

    /* Baris pekerjaan bisa diklik */
    table.table-schedule tr.item-row {
        cursor: pointer;
        transition: background 0.15s;
    }

    table.table-schedule tr.item-row:hover {
        background: #fff5f5 !important;
    }

    table.table-schedule tr.item-row.selected {
        background: #ffe5e5 !important;
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
</style>
