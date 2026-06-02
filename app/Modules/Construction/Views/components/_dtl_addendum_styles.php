<style>
    /* ── ADDENDUM PANEL – Bootstrap 5 ── */
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

    .addendum-panel {
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Panel Header ── */
    .addendum-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 24px;
        background: #fff;
        border: 1px solid #e8e8e8;
        border-bottom: none;
        border-radius: 14px 14px 0 0;
    }

    .addendum-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .addendum-panel-title .icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d5af1;
        font-size: 16px;
    }

    .addendum-panel-title span {
        font-size: 15px;
        font-weight: 600;
        color: #1a1d2e;
        letter-spacing: -0.01em;
    }

    /* ── Buttons ── */
    .btn-adm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-adm:active {
        transform: scale(0.97);
    }

    .btn-adm-primary {
        background: #3d5af1;
        color: #fff;
    }

    .btn-adm-primary:hover {
        background: #2d48e0;
        color: #fff;
        box-shadow: 0 4px 12px rgba(61, 90, 241, 0.3);
    }

    .btn-adm-danger {
        background: #fff0f0;
        color: #c0392b;
        border: 1px solid #ffd0cc;
    }

    .btn-adm-danger:hover {
        background: #ffe0dc;
        color: #a93226;
    }

    .btn-adm-warning {
        background: #fff8e6;
        color: #b7791f;
        border: 1px solid #fde8a0;
    }

    .btn-adm-warning:hover {
        background: #fff0c8;
        color: #a06015;
    }

    .btn-adm-success {
        background: #e8f8f0;
        color: #1a7f4b;
        border: 1px solid #b8e8cc;
    }

    .btn-adm-success:hover {
        background: #d0f0e0;
        color: #155f38;
    }

    /* ── Table Wrapper ── */
    .addendum-table-wrapper {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 0 0 14px 14px;
        overflow: hidden;
    }

    .addendum-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ── Table ── */
    .tbl-addendum {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'DM Sans', sans-serif;
        font-size: 12px;
    }

    .tbl-addendum thead th {
        padding: 12px 14px;
        background: #f7f8fc;
        color: #6b7088;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-bottom: 2px solid #e8e8e8;
        white-space: nowrap;
        text-align: center;
    }

    .tbl-addendum tbody tr {
        transition: background 0.12s;
    }

    .tbl-addendum tbody tr:hover {
        background: #fafbff;
    }

    .tbl-addendum tbody td {
        padding: 6px 8px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .tbl-addendum tfoot td {
        padding: 14px 16px;
        background: #f7f8fc;
        font-size: 13px;
        font-weight: 600;
        color: #1a1d2e;
        border-top: 2px solid #e8e8e8;
    }

    /* ── Row Inputs ── */
    .tbl-addendum input[type="text"],
    .tbl-addendum input[type="number"],
    .tbl-addendum input[type="float"] {
        width: 100%;
        padding: 5px 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 12px;
        color: #1a1d2e;
        background: #fff;
        border: 1px solid #e4e7f0;
        border-radius: 7px;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
    }

    .tbl-addendum input:focus {
        border-color: #3d5af1;
        box-shadow: 0 0 0 3px rgba(61, 90, 241, 0.12);
    }

    /* ── Hide Number Input Arrows ── */
    .tbl-addendum input[type="number"]::-webkit-outer-spin-button,
    .tbl-addendum input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .tbl-addendum input[type="number"] {
        -moz-appearance: textfield;
    }

    /* number/price uses mono for alignment */
    .input-price,
    .input-vol {
        font-family: 'JetBrains Mono', monospace !important;
        text-align: right;
    }

    .input-unit {
        text-align: center;
    }

    .input-roman {
        text-align: center;
        font-weight: 600;
        color: #3d5af1 !important;
    }

    /* ── Row States ── */
    .row-locked td {
        background: #fafafa;
        opacity: 0.78;
    }

    .row-locked input {
        pointer-events: none;
        background: #f5f5f5 !important;
        border-color: transparent !important;
        color: #888 !important;
    }

    /* ── Total Cell ── */
    .row-add-total {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 500;
        text-align: right;
        color: #1a7f4b;
        padding-right: 14px !important;
        white-space: nowrap;
    }

    /* ── Grand Total ── */
    #grandTotalAddendum {
        font-family: 'JetBrains Mono', monospace;
        font-size: 14px;
        color: #3d5af1;
        text-align: right;
        padding-right: 14px !important;
    }

    /* ── Mini action buttons inside table ── */
    .tbl-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 7px;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.12s, transform 0.1s;
        flex-shrink: 0;
    }

    .tbl-btn:active {
        transform: scale(0.92);
    }

    .tbl-btn-mat {
        background: #eff2ff;
        color: #3d5af1;
    }

    .tbl-btn-mat:hover {
        background: #dce3ff;
    }

    .tbl-btn-save {
        background: #e8f8f0;
        color: #1a7f4b;
    }

    .tbl-btn-save:hover {
        background: #d0f0e0;
    }

    .tbl-btn-del {
        background: #fff0f0;
        color: #c0392b;
    }

    .tbl-btn-del:hover {
        background: #ffe0dc;
    }

    .tbl-actions {
        display: flex;
        gap: 4px;
        justify-content: center;
    }

    /* ── Lock Badge ── */
    .lock-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f2f2f2;
        color: #999;
        font-size: 11px;
    }

    /* ── Min Widths ── */
    .col-roman {
        min-width: 60px;
        width: 5%;
    }

    .col-group {
        min-width: 150px;
        width: 14%;
    }

    .col-section {
        min-width: 150px;
        width: 15%;
    }

    .col-task {
        min-width: 200px;
        width: 20%;
    }

    .col-vol {
        min-width: 70px;
        width: 7%;
    }

    .col-unit {
        min-width: 75px;
        width: 6%;
    }

    .col-price {
        min-width: 130px;
        width: 12%;
    }

    .col-total {
        min-width: 130px;
        width: 13%;
    }

    .col-aksi {
        min-width: 105px;
        width: 8%;
    }

    /* ── Section grouping color hint ── */
    .section-repeated {
        color: transparent !important;
    }

    .section-first {
        color: #1a1d2e !important;
        font-weight: 600 !important;
    }

    /* ── Modal ── */
    .modal-addendum .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }

    .modal-addendum .modal-header {
        background: #3d5af1;
        padding: 18px 24px;
        border: none;
    }

    .modal-addendum .modal-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #fff;
    }

    .modal-addendum .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.7;
    }

    .modal-addendum .modal-body {
        padding: 24px;
        background: #f7f8fc;
    }

    .material-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 8px;
        transition: box-shadow 0.12s;
    }

    .material-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .material-item .mat-name {
        font-size: 13px;
        font-weight: 500;
        color: #1a1d2e;
    }

    .material-item .mat-price {
        font-size: 12px;
        color: #6b7088;
        font-family: 'JetBrains Mono', monospace;
        margin-top: 1px;
    }

    .add-product-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 16px;
    }

    .add-product-card label {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #6b7088;
        margin-bottom: 8px;
    }

    .add-product-card select.form-control,
    .add-product-card select.form-select {
        border-radius: 8px;
        font-size: 13px;
        border-color: #e4e7f0;
    }

    .empty-materials {
        text-align: center;
        padding: 24px;
        color: #aaa;
        font-size: 13px;
    }

    .empty-materials i {
        font-size: 28px;
        display: block;
        margin-bottom: 8px;
        opacity: 0.35;
    }

    @media (max-width: 576px) {
        .addendum-panel-header {
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

    /* ── Fix Select2 Z-Index in Modal ── */
    .select2-container--open,
    .select2-dropdown {
        z-index: 9999 !important;
    }
</style>
