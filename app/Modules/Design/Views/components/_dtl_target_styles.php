<style>
    /* ======================================================
       TARGET PAGE — PREMIUM STYLES
       ====================================================== */

    /* ── Outer table wrapper ── */
    .tbl-outer {
        overflow-x: auto;
        border-radius: 14px;
        border: 1.5px solid rgba(255, 92, 92, 0.13);
        box-shadow: 0 4px 32px rgba(255, 92, 92, 0.07), 0 1px 6px rgba(0,0,0,0.04);
        background: #fff;
    }

    /* ── Card header ── */
    .tbl-outer .card-header {
        background: linear-gradient(135deg, var(--palette-primary, #ff5c5c) 0%, #ff7b7b 100%) !important;
        padding: 14px 22px !important;
        border-radius: 12px 12px 0 0 !important;
    }

    /* Expand table card on desktop, but keep rounded corners */
    @media (min-width: 768px) {
        .tbl-outer {
            margin-left: 0 !important;
            margin-right: 0 !important;
            border-radius: 14px !important;
        }
        .tbl-outer .card-header {
            border-radius: 12px 12px 0 0 !important;
            padding-left: 22px !important;
            padding-right: 22px !important;
        }
    }


    /* ── Gantt table ── */
    table.table-schedule {
        width: 100%;
        min-width: 800px;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    table.table-schedule th {
        background: linear-gradient(180deg, #f8f9ff 0%, #f1f4fd 100%);
        font-size: 10px;
        font-weight: 800;
        color: #5a6a85;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        padding: 10px 6px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        border-bottom: 2px solid rgba(255,92,92,0.12);
    }

    table.table-schedule td {
        vertical-align: middle;
        font-size: 13px;
        border-color: rgba(0,0,0,0.05);
    }

    table.table-schedule tr.item-row {
        cursor: pointer;
        transition: background 0.18s;
    }

    table.table-schedule tr.item-row:hover {
        background: #fff5f5 !important;
    }

    /* ── Timeline column ── */
    table.table-schedule td.timeline-column {
        position: relative;
        padding: 10px 0 !important;
        background-color: #fafbff;
        height: 62px;
        vertical-align: middle;
    }

    /* ── Grid lines ── */
    .timeline-grid-lines {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }

    /* ── Gantt bar wrapper ── */
    .gantt-bar-wrapper {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 30px;
        z-index: 10;
        padding: 0 3px;
    }

    /* ── Gantt bars ── */
    .gantt-bar {
        width: 100%;
        height: 100%;
        border-radius: 50px;
        background: linear-gradient(90deg, #ff8585, #ff4c4c) !important;
        box-shadow: 0 3px 12px rgba(255, 76, 76, 0.28), inset 0 1px 0 rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid rgba(255,255,255,0.7);
        transition: transform 0.22s cubic-bezier(0.25, 0.8, 0.25, 1),
                    box-shadow 0.22s ease,
                    filter 0.22s ease;
        position: relative;
    }

    .gantt-bar::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50px;
        background: linear-gradient(180deg, rgba(255,255,255,0.22) 0%, transparent 60%);
        pointer-events: none;
    }

    .gantt-bar.bar-pending {
        background: linear-gradient(90deg, #ffd166, #f7a e1b) !important;
        background: linear-gradient(90deg, #ffd166, #f9a825) !important;
        box-shadow: 0 3px 12px rgba(249, 168, 37, 0.30), inset 0 1px 0 rgba(255,255,255,0.25);
    }

    .gantt-bar.bar-progress {
        background: linear-gradient(90deg, #ff8585, #ff4c4c) !important;
        box-shadow: 0 3px 12px rgba(255, 76, 76, 0.28), inset 0 1px 0 rgba(255,255,255,0.25);
    }

    .gantt-bar.bar-done {
        background: linear-gradient(90deg, #6de195, #22c55e) !important;
        box-shadow: 0 3px 12px rgba(34, 197, 94, 0.28), inset 0 1px 0 rgba(255,255,255,0.25);
    }

    .gantt-bar:hover {
        transform: scaleY(1.08) translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 76, 76, 0.38);
        filter: brightness(1.06);
    }

    .gantt-bar.bar-pending:hover {
        box-shadow: 0 6px 20px rgba(249, 168, 37, 0.38);
    }

    .gantt-bar.bar-done:hover {
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.38);
    }

    .gantt-bar-text {
        font-size: 10px;
        color: #ffffff;
        font-weight: 800;
        letter-spacing: 0.4px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.22);
        padding: 0 10px;
        position: relative;
        z-index: 1;
    }

    /* ── Action column ── */
    table.table-schedule td:last-child {
        text-align: center;
    }

    /* ── Trash button ── */
    .btn-gantt-delete {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1.5px solid rgba(220,53,69,0.25);
        background: rgba(255, 92, 92, 0.06);
        color: #dc3545;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.18s ease;
    }

    .btn-gantt-delete:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        box-shadow: 0 3px 10px rgba(220,53,69,0.30);
        transform: scale(1.08);
    }

    /* ── Mobile target cards ── */
    .target-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 16px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .target-card:hover {
        box-shadow: 0 8px 28px rgba(255,92,92,0.13);
        transform: translateY(-2px);
    }

    .target-card .card-accent {
        height: 4px;
        background: linear-gradient(90deg, #ff8585, #ff4c4c);
    }

    .target-card .card-accent.accent-pending {
        background: linear-gradient(90deg, #ffd166, #f9a825);
    }

    .target-card .card-accent.accent-done {
        background: linear-gradient(90deg, #6de195, #22c55e);
    }

    /* ── Status badge ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-badge.badge-pending {
        background: #fff8e1;
        color: #f59e0b;
        border: 1.5px solid rgba(245, 158, 11, 0.25);
    }

    .status-badge.badge-progress {
        background: #fff0f0;
        color: #ff4c4c;
        border: 1.5px solid rgba(255, 76, 76, 0.22);
    }

    .status-badge.badge-done {
        background: #f0fdf4;
        color: #22c55e;
        border: 1.5px solid rgba(34, 197, 94, 0.22);
    }

    .status-badge .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: blink 1.4s ease-in-out infinite;
    }

    .status-badge.badge-done .badge-dot {
        animation: none;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.35; }
    }

    /* ── Date range chip ── */
    .date-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8f9ff;
        border: 1.5px solid rgba(90, 106, 133, 0.14);
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        color: #5a6a85;
    }

    /* ── Modal polish ── */
    .modal-premium .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.14);
        overflow: hidden;
    }

    .modal-premium .modal-header {
        background: linear-gradient(135deg, var(--palette-primary, #ff5c5c) 0%, #ff7b7b 100%);
        border-bottom: none;
        padding: 18px 22px 14px;
    }

    .modal-premium .modal-title {
        color: #fff !important;
        font-weight: 700;
        font-size: 15px;
    }

    .modal-premium .modal-title i {
        color: rgba(255,255,255,0.85);
    }

    .modal-premium .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-premium .modal-body {
        padding: 24px 22px 8px;
    }

    .modal-premium .form-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #8896ab;
        margin-bottom: 6px;
    }

    .modal-premium .form-control,
    .modal-premium .form-select {
        border-radius: 10px;
        border: 1.5px solid rgba(0,0,0,0.10);
        font-size: 13px;
        padding: 10px 14px;
        transition: border-color 0.18s, box-shadow 0.18s;
        background: #fafbff;
    }

    .modal-premium .form-control:focus,
    .modal-premium .form-select:focus {
        border-color: var(--palette-primary, #ff5c5c);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.10);
        background: #fff;
    }

    .modal-premium .modal-footer {
        background: #f8f9ff;
        border-top: 1.5px solid rgba(0,0,0,0.06);
        padding: 14px 22px;
        border-radius: 0 0 18px 18px;
    }

    .modal-premium .btn-cancel {
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        background: #fff;
        border: 1.5px solid rgba(0,0,0,0.10);
        color: #6c757d;
        padding: 9px 20px;
        transition: all 0.18s;
    }

    .modal-premium .btn-cancel:hover {
        background: #f1f3f9;
        border-color: rgba(0,0,0,0.15);
    }

    .modal-premium .btn-submit {
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        background: linear-gradient(135deg, var(--palette-primary, #ff5c5c), #ff7b7b);
        border: none;
        color: #fff;
        padding: 9px 24px;
        box-shadow: 0 4px 14px rgba(255,92,92,0.30);
        transition: all 0.2s;
    }

    .modal-premium .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255,92,92,0.38);
        filter: brightness(1.05);
    }

    /* ── Duration readonly input ── */
    .duration-display {
        background: linear-gradient(135deg, #fff5f5, #fff) !important;
        border: 1.5px dashed rgba(255,92,92,0.35) !important;
        color: var(--palette-primary, #ff5c5c) !important;
        font-weight: 800 !important;
        text-align: center;
        font-size: 14px !important;
    }

    /* ── Header action buttons ── */
    .btn-header-action {
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        padding: 7px 14px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
    }

    .btn-header-schedule {
        background: #fff !important;
        color: var(--palette-primary, #ff5c5c) !important;
        border: 1.5px solid transparent !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10) !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn-header-schedule:hover {
        background: #fff5f5 !important;
        color: var(--palette-primary, #ff5c5c) !important;
        border: 1.5px solid rgba(255, 92, 92, 0.2) !important;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .btn-header-schedule:hover i {
        color: var(--palette-primary, #ff5c5c) !important;
    }

    .btn-header-add {
        background: #fff !important;
        color: var(--palette-primary, #ff5c5c) !important;
        border: 1.5px solid transparent !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10) !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn-header-add:hover {
        background: #fff5f5 !important;
        color: var(--palette-primary, #ff5c5c) !important;
        border: 1.5px solid rgba(255, 92, 92, 0.2) !important;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .btn-header-add:hover i {
        color: var(--palette-primary, #ff5c5c) !important;
    }

    .btn-header-schedule:active,
    .btn-header-add:active {
        transform: translateY(-1px) scale(0.98) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
    }

    /* ── Empty state ── */
    .target-empty-state {
        padding: 48px 24px;
        text-align: center;
    }

    .target-empty-state .empty-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #fff0f0, #ffe5e5);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: rgba(255,92,92,0.45);
        margin-bottom: 14px;
        box-shadow: 0 4px 14px rgba(255,92,92,0.12);
    }

    /* ── Floating actions menu on the right side of Gantt bar on hover ── */
    .gantt-actions-menu {
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%) translateX(2px);
        display: flex;
        gap: 4px;
        background: transparent;
        padding: 0 4px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
        z-index: 99;
    }

    /* Invisible bridge to prevent flickering when mouse moves from Gantt bar to hover menu */
    .gantt-actions-menu::before {
        content: '';
        position: absolute;
        right: 100%;
        top: -10px;
        bottom: -10px;
        width: 20px;
        background: transparent;
    }

    .gantt-bar-wrapper:hover .gantt-actions-menu {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(-50%) translateX(6px);
    }

    .btn-gantt-menu-item {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        transition: all 0.18s ease;
        cursor: pointer;
        text-decoration: none;
        padding: 0;
        line-height: 0;
    }

    .btn-gantt-menu-item i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 0 !important;
    }

    .btn-gantt-menu-item:hover {
        transform: scale(1.15);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.22);
    }

    .btn-gantt-menu-item.edit {
        background: rgba(13, 110, 253, 0.9);
    }

    .btn-gantt-menu-item.edit:hover {
        background: #0d6efd;
    }

    .btn-gantt-menu-item.delete {
        background: rgba(220, 53, 69, 0.9);
    }

    .btn-gantt-menu-item.delete:hover {
        background: #dc3545;
    }
</style>


