<style>
  /* ──────────────────────────────────────────────────────
   ROOT & BASE
────────────────────────────────────────────────────── */
  :root {
    --c-primary: #FF5C5C;
    --c-primary-l: #ff8e8e;
    --c-accent: #ff8e8e;
    --c-success: #10b981;
    --c-danger: #ef4444;
    --c-warn: #f59e0b;
    --c-bg: #f5f6fa;
    --c-surface: #ffffff;
    --c-border: #e5e7eb;
    --c-text: #111827;
    --c-muted: #6b7280;
    --c-group-bg: #fff5f5;
    --c-group-txt: #c92a2a;
    --c-sub-bg: #f8fafc;
    --c-sub-txt: #475569;
    --radius-card: 14px;
    --radius-sm: 8px;
    --shadow-card: 0 2px 12px rgba(255, 92, 92, .08), 0 1px 3px rgba(0, 0, 0, .05);
    --font: 'Plus Jakarta Sans', system-ui, sans-serif;
  }

  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  .sched-wrap {
    font-family: var(--font);
    color: var(--c-text);
  }

  /* ──────────────────────────────────────────────────────
   SECTION BADGES
────────────────────────────────────────────────────── */
  .sched-section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--c-primary);
    background: #fff5f5;
    border: 1.5px solid #ffcccc;
    border-radius: 20px;
    padding: 4px 14px;
    margin-bottom: 12px;
  }

  .sched-section-badge.addendum {
    color: #b45309;
    background: #fffbeb;
    border-color: #fde68a;
  }

  /* ──────────────────────────────────────────────────────
   TABLE WRAPPER
────────────────────────────────────────────────────── */
  .tbl-outer {
    overflow-x: auto;
    border-radius: var(--radius-card);
    border: 1.5px solid var(--c-border);
    box-shadow: var(--shadow-card);
    background: var(--c-surface);
  }

  /* ──────────────────────────────────────────────────────
   SUMMARY STATS ROW
────────────────────────────────────────────────────── */
  .summary-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .stat-chip {
    flex: 1;
    min-width: 160px;
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    box-shadow: var(--shadow-card);
  }

  .stat-chip .label {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--c-muted);
    margin-bottom: 4px;
  }

  .stat-chip .value {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--c-text);
  }

  .stat-chip .value.danger {
    color: var(--c-danger);
  }

  .stat-chip .value.success {
    color: var(--c-success);
  }

  /* ──────────────────────────────────────────────────────
   MAIN TABLE
────────────────────────────────────────────────────── */
  table.tbl-sched {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
    min-width: 900px;
  }

  /* Sticky header */
  table.tbl-sched thead th {
    position: sticky;
    top: 0;
    z-index: 4;
    background: #fff5f5;
    font-family: var(--font);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--c-primary);
    border-bottom: 2px solid #ffcccc;
    padding: 10px 10px;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
  }

  table.tbl-sched thead th.th-left {
    text-align: left;
  }

  table.tbl-sched thead th.th-no {
    width: 38px;
  }

  table.tbl-sched thead th.week-th {
    min-width: 72px;
    background: #fffafa;
    color: var(--c-primary);
    font-size: .6rem;
    padding: 6px 4px;
    border-left: 1px dashed #ffdddd;
  }

  table.tbl-sched thead th.week-th .wk-num {
    display: block;
    font-size: .72rem;
    font-weight: 800;
    color: var(--c-primary);
    line-height: 1;
  }

  table.tbl-sched thead th.week-th .wk-date {
    display: block;
    font-size: .55rem;
    font-weight: 500;
    color: var(--c-primary);
    opacity: .8;
    margin-top: 2px;
  }

  /* General cells */
  table.tbl-sched td {
    font-family: var(--font);
    font-size: .78rem;
    padding: 9px 10px;
    border-bottom: 1px solid var(--c-border);
    vertical-align: middle;
    color: var(--c-text);
  }

  table.tbl-sched td.td-center {
    text-align: center;
  }

  table.tbl-sched td.td-muted {
    color: var(--c-muted);
    text-align: center;
  }

  table.tbl-sched td.td-mono {
    font-variant-numeric: tabular-nums;
    text-align: right;
    font-size: .76rem;
    white-space: nowrap;
  }

  /* GROUP row */
  table.tbl-sched tr.row-group td {
    background: var(--c-group-bg);
    color: var(--c-group-txt);
    font-weight: 700;
    font-size: .75rem;
    letter-spacing: .03em;
    padding: 8px 12px;
    border-top: 2px solid #ffcccc;
    border-bottom: 1px solid #ffcccc;
  }

  /* SUBGROUP row */
  table.tbl-sched tr.row-sub td {
    background: var(--c-sub-bg);
    color: var(--c-sub-txt);
    font-weight: 600;
    font-size: .72rem;
    font-style: italic;
    padding: 6px 12px 6px 28px;
    border-bottom: 1px dashed var(--c-border);
  }

  /* ITEM row */
  table.tbl-sched tr.row-item {
    cursor: pointer;
    transition: background .12s;
  }

  table.tbl-sched tr.row-item:hover {
    background: #fff5f5 !important;
  }

  table.tbl-sched tr.row-item.selected {
    background: #ffe5e5 !important;
  }

  table.tbl-sched tr.row-item td:first-child {
    border-left: 3px solid transparent;
    transition: border-color .15s;
  }

  table.tbl-sched tr.row-item.selected td:first-child,
  table.tbl-sched tr.row-item:hover td:first-child {
    border-left-color: var(--c-primary);
  }

  /* TOTAL row */
  table.tbl-sched tr.row-total td {
    background: #fff5f5;
    font-weight: 700;
    font-size: .78rem;
    border-top: 2px solid #ffcccc;
    border-bottom: none;
  }

  /* Gantt bar cell */
  table.tbl-sched td.cell-bar {
    padding: 0 4px;
    text-align: center;
    border-left: 1px dashed #e5e7eb;
  }

  .gantt-bar {
    height: 12px;
    border-radius: 6px;
    background: linear-gradient(90deg, var(--c-primary), var(--c-primary-l));
    box-shadow: 0 1px 4px rgba(79, 70, 229, .35);
    min-width: 8px;
  }

  .gantt-bar.bar-add {
    background: linear-gradient(90deg, var(--c-warn), #fbbf24);
    box-shadow: 0 1px 4px rgba(245, 158, 11, .35);
  }

  /* Bobot progress pill */
  .prog-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: .7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
  }

  .prog-pill.success {
    background: #d1fae5;
    color: #065f46;
  }

  .prog-pill.danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .prog-pill.neutral {
    background: #f3f4f6;
    color: #374151;
  }

  /* Money cells */
  .money {
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
  }

  .money.danger {
    color: var(--c-danger);
    font-weight: 600;
  }

  .money.success {
    color: var(--c-success);
    font-weight: 600;
  }

  /* ──────────────────────────────────────────────────────
   FORM CARD
────────────────────────────────────────────────────── */
  .form-card {
    border: 1.5px solid var(--c-border);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-card);
    margin-top: 28px;
  }

  .form-card-header {
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .form-card-header.primary {
    background: linear-gradient(135deg, var(--c-primary), #ff7373);
  }

  .form-card-header.teal {
    background: linear-gradient(135deg, var(--c-primary), #ff7373);
  }

  .form-card-header .hdr-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, .18);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    color: #fff;
    flex-shrink: 0;
  }

  .form-card-header h6 {
    color: #fff;
    font-family: var(--font);
    font-weight: 700;
    font-size: .85rem;
    margin: 0;
  }

  .form-card-header small {
    color: rgba(255, 255, 255, .75);
    font-size: .72rem;
    font-weight: 400;
    display: block;
    margin-top: 1px;
  }

  .form-card-body {
    padding: 20px;
    background: var(--c-surface);
  }

  .form-card-body.tint {
    background: #fafffe;
  }

  /* Custom form controls */
  .fc-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font);
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--c-muted);
    margin-bottom: 5px;
  }

  .fc-label i {
    color: var(--c-primary);
    font-size: .65rem;
  }

  .fc-ctrl {
    font-family: var(--font) !important;
    font-size: .8rem !important;
    border: 1.5px solid var(--c-border) !important;
    border-radius: var(--radius-sm) !important;
    padding: 7px 10px !important;
    transition: border-color .15s, box-shadow .15s !important;
    background: #fafbff !important;
  }

  .fc-ctrl:focus {
    border-color: var(--c-primary) !important;
    box-shadow: 0 0 0 3px rgba(255, 92, 92, .12) !important;
    outline: none !important;
  }

  .fc-ctrl[readonly] {
    background: #f3f4f6 !important;
    color: var(--c-muted) !important;
  }

  /* Selected item card inside form */
  .selected-item-card {
    background: #fff5f5;
    border: 1.5px solid #ffcccc;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 16px;
    display: none;
    align-items: center;
    gap: 10px;
  }

  .selected-item-card.visible {
    display: flex;
  }

  .selected-item-card .sic-icon {
    width: 36px;
    height: 36px;
    background: var(--c-primary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .9rem;
    flex-shrink: 0;
  }

  .selected-item-card .sic-name {
    font-weight: 700;
    font-size: .82rem;
    color: var(--c-primary);
  }

  .selected-item-card .sic-meta {
    font-size: .7rem;
    color: var(--c-muted);
    margin-top: 1px;
  }

  /* Btn primary custom */
  .btn-submit-custom {
    background: linear-gradient(135deg, var(--c-primary), #ff7373);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 8px 20px;
    font-family: var(--font);
    font-weight: 700;
    font-size: .8rem;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-submit-custom:disabled {
    opacity: .45;
    cursor: not-allowed;
    transform: none !important;
  }

  .btn-submit-custom:not(:disabled):hover {
    opacity: .9;
    transform: translateY(-1px);
  }

  .btn-teal-custom {
    background: linear-gradient(135deg, var(--c-primary), #ff7373);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 8px 20px;
    font-family: var(--font);
    font-weight: 700;
    font-size: .8rem;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-teal-custom:hover {
    opacity: .88;
  }

  /* ──────────────────────────────────────────────────────
   MOBILE
────────────────────────────────────────────────────── */
  @media (max-width: 767px) {
    .summary-stats .stat-chip {
      min-width: calc(50% - 6px);
    }

    table.tbl-sched thead th:nth-child(3),
    table.tbl-sched tbody tr td:nth-child(3) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(7),
    table.tbl-sched tbody tr td:nth-child(7) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(8),
    table.tbl-sched tbody tr td:nth-child(8) {
      display: none !important;
    }

    table.tbl-sched thead th:nth-child(n+9),
    table.tbl-sched tbody tr td:nth-child(n+9) {
      display: none !important;
    }

    table.tbl-sched td,
    table.tbl-sched th {
      font-size: .7rem !important;
      padding: 7px 8px !important;
    }

    .week-badge-mob {
      display: inline-block;
      font-size: .6rem;
      background: #ffe5e5;
      color: var(--c-primary);
      border-radius: 4px;
      padding: 1px 5px;
      margin-top: 3px;
    }

    .form-card-body .row>[class*='col-'] {
      width: 100% !important;
    }

    .btn-submit-custom,
    .btn-teal-custom {
      width: 100%;
      justify-content: center;
    }

    .scroll-hint {
      display: flex !important;
    }
  }

  @media (min-width: 768px) {
    .week-badge-mob {
      display: none;
    }

    .scroll-hint {
      display: none !important;
    }
  }

  .scroll-hint {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: .7rem;
    color: var(--c-muted);
    padding: 5px 0 2px;
    animation: swipe 2s ease-in-out infinite;
  }

  @keyframes swipe {

    0%,
    100% {
      opacity: .5;
      transform: translateX(0);
    }

    50% {
      opacity: 1;
      transform: translateX(5px);
    }
  }
</style>
