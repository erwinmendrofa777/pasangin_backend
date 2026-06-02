<style>
    /* ── RAB PANEL – Premium Design System ── */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

    .rab-panel {
        font-family: 'Outfit', sans-serif;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.01);
        overflow: hidden;
    }

    /* ── Panel Header ── */
    .rab-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        padding: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
    }

    .rab-panel-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .rab-panel-title .icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e0e7ff 0%, #e0f2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-size: 18px;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.08);
    }

    .rab-panel-title span {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    /* ── Buttons ── */
    .btn-adm {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-adm:hover {
        transform: translateY(-2px);
    }

    .btn-adm:active {
        transform: translateY(0) scale(0.97);
    }

    .btn-adm-primary {
        background: linear-gradient(135deg, #3d5af1, #4f46e5);
        color: #fff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-adm-primary:hover {
        background: linear-gradient(135deg, #2d48e0, #4338ca);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        color: #fff;
    }

    .btn-adm-danger {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.05);
    }

    .btn-adm-danger:hover {
        background: #fee2e2;
        color: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }

    .btn-adm-warning {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.05);
    }

    .btn-adm-warning:hover {
        background: #fef3c7;
        color: #b45309;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
    }

    .btn-adm-success {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.05);
    }

    .btn-adm-success:hover {
        background: #d1fae5;
        color: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    /* ── Table Wrapper ── */
    .rab-table-wrapper {
        background: #ffffff;
    }

    .rab-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ── Table ── */
    .tbl-rab {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Outfit', sans-serif;
        font-size: 11px;
    }

    .tbl-rab thead th {
        padding: 8px 10px;
        background: #3d5af1;
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-bottom: 2px solid #2d48e0;
        white-space: nowrap;
        text-align: center;
    }

    .tbl-rab tbody tr {
        transition: background 0.15s ease;
    }

    .tbl-rab tbody tr:hover {
        background: #f8fafc;
    }

    .tbl-rab tbody td {
        padding: 4px 6px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        transition: background 0.15s ease;
    }

    .tbl-rab tfoot td {
        padding: 10px 12px;
        background: #eff2ff;
        font-size: 11px;
        font-weight: 600;
        color: #3d5af1;
        border-top: 2px solid #cbd5e1;
    }

    /* ── Row Inputs (Sleek Bordered Style) ── */
    .tbl-rab input[type="text"],
    .tbl-rab input[type="number"],
    .tbl-rab input[type="float"] {
        width: 100%;
        padding: 3px 6px;
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        color: #0f172a;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }

    .tbl-rab input[type="text"]:focus,
    .tbl-rab input[type="number"]:focus,
    .tbl-rab input[type="float"]:focus {
        border-color: #3d5af1 !important;
        box-shadow: 0 0 0 3px rgba(61, 90, 241, 0.15) !important;
    }

    /* ── Hide Number Input Arrows ── */
    .tbl-rab input[type="number"]::-webkit-outer-spin-button,
    .tbl-rab input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .tbl-rab input[type="number"] {
        -moz-appearance: textfield;
    }

    /* number/price uses mono for alignment */
    .input-price,
    .input-vol {
        font-family: 'JetBrains Mono', monospace !important;
        text-align: right;
        font-size: 10px !important;
    }

    .input-unit {
        text-align: center;
    }

    .input-roman {
        text-align: center;
        font-weight: 700;
        color: #4f46e5 !important;
    }

    /* ── Row States ── */
    .row-locked td {
        background: #ffffff;
        opacity: 1;
    }

    .row-locked input {
        pointer-events: none;
        background: transparent !important;
        border-color: transparent !important;
    }

    .row-locked input:not(.input-roman) {
        color: #0f172a !important;
    }

    .row-locked input.input-roman {
        color: #4f46e5 !important;
    }

    /* ── Total Cell ── */
    .row-rab-total {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 600;
        text-align: right;
        color: #10b981;
        padding-right: 14px !important;
        white-space: nowrap;
    }

    /* ── Subtotal Row ── */
    .row-rab-subtotal td {
        background: #eff2ff !important;
        font-weight: 700;
        border-top: 1px solid #cbd5e1;
        border-bottom: 2px solid #b4c6fc !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        color: #3d5af1;
        font-size: 11px;
    }

    /* ── Grand Total ── */
    #grandTotalRab {
        font-family: 'Outfit', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #3d5af1 !important;
        text-align: center;
        padding-right: 14px !important;
    }

    /* ── Mini action buttons inside table ── */
    .tbl-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .tbl-btn:active {
        transform: scale(0.9);
    }

    .tbl-btn-mat {
        background: #eef2ff;
        color: #4f46e5;
    }

    .tbl-btn-mat:hover {
        background: #e0e7ff;
        color: #3730a3;
        transform: translateY(-2px);
    }

    .tbl-btn-del {
        background: #fef2f2;
        color: #ef4444;
    }

    .tbl-btn-del:hover {
        background: #fee2e2;
        color: #b91c1c;
        transform: translateY(-2px);
    }

    .tbl-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    /* ── Lock Badge ── */
    .lock-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 12px;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.04);
    }

    /* ── Min Widths ── */
    .col-roman {
        min-width: 70px;
        width: 6%;
    }

    .col-group {
        min-width: 170px;
        width: 15%;
    }

    .col-section {
        min-width: 170px;
        width: 15%;
    }

    .col-task {
        min-width: 220px;
        width: 20%;
    }

    .col-vol {
        min-width: 80px;
        width: 7%;
    }

    .col-unit {
        min-width: 80px;
        width: 6%;
    }

    .col-price {
        min-width: 140px;
        width: 12%;
    }

    .col-total {
        min-width: 140px;
        width: 13%;
    }

    .col-aksi {
        min-width: 110px;
        width: 8%;
    }

    /* ── Section grouping color hint (Fade on Row Hover) ── */
    .section-repeated {
        color: transparent !important;
        -webkit-text-fill-color: transparent !important;
    }

    .tbl-rab tr:hover .section-repeated {
        color: #94a3b8 !important;
        -webkit-text-fill-color: #94a3b8 !important;
        opacity: 0.35;
    }

    .section-first {
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    /* ── Modal ── */
    .modal-rab .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .modal-rab .modal-header {
        background: linear-gradient(135deg, #3d5af1 0%, #4f46e5 100%);
        padding: 20px 24px;
        border: none;
    }

    .modal-rab .modal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
    }

    .modal-rab .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-rab .modal-body {
        padding: 24px;
        background: #f8fafc;
    }

    .material-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }

    .material-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .material-item .mat-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
    }

    .material-item .mat-price {
        font-size: 12px;
        color: #64748b;
        font-family: 'JetBrains Mono', monospace;
        margin-top: 2px;
    }

    .add-product-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .add-product-card label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 8px;
    }

    .add-product-card select.form-control,
    .add-product-card select.form-select {
        border-radius: 10px;
        font-size: 13px;
        border-color: #cbd5e1;
        padding: 10px 14px;
    }

    .empty-materials {
        text-align: center;
        padding: 32px 24px;
        color: #94a3b8;
        font-size: 13px;
    }

    .empty-materials i {
        font-size: 32px;
        display: block;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    @media (max-width: 576px) {
        .rab-panel-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-adm {
            justify-content: center;
            width: 100%;
        }
    }

    .row-add-row-in-group td {
        padding: 8px 12px !important;
        background-color: #fafbfc !important;
        border-top: 1px dashed #e2e8f0 !important;
        border-bottom: 1px dashed #e2e8f0 !important;
    }

    .row-add-row-in-group button {
        color: #3b82f6 !important;
        transition: all 0.2s ease;
        font-weight: 600;
    }

    .row-add-row-in-group button:hover {
        color: #1d4ed8 !important;
        transform: translateX(4px);
        text-decoration: none !important;
    }

    /* ── Fix Select2 Z-Index in Modal ── */
    .select2-container--open,
    .select2-dropdown {
        z-index: 9999 !important;
    }
</style>
