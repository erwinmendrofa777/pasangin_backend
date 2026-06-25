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

    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
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
        background: linear-gradient(135deg, #ffe5e5 0%, #fff5f5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--palette-primary);
        font-size: 18px;
        box-shadow: 0 4px 6px -1px rgba(255, 92, 92, 0.15);
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
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
    }

    .btn-adm-primary:hover {
        background: linear-gradient(135deg, var(--palette-primary-hover), var(--palette-primary));
        box-shadow: 0 6px 20px rgba(255, 92, 92, 0.35);
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
        background: var(--palette-primary);
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-bottom: 2px solid var(--palette-primary-hover);
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
        background: #fff5f5;
        font-size: 11px;
        font-weight: 600;
        color: var(--palette-primary);
        border-top: 2px solid #cbd5e1;
    }

    /* ── Row Inputs (Sleek Bordered Style) ── */
    .tbl-rab input[type="text"],
    .tbl-rab input[type="number"],
    .tbl-rab input[type="float"],
    .tbl-rab select {
        width: 100%;
        height: 28px;
        padding: 3px 6px;
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        color: #0f172a;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        box-sizing: border-box;
    }

    .tbl-rab input[type="text"]:focus,
    .tbl-rab input[type="number"]:focus,
    .tbl-rab input[type="float"]:focus,
    .tbl-rab select:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.15) !important;
    }

    .tbl-rab input[readonly] {
        background-color: #f8fafc !important;
        color: #64748b !important;
        pointer-events: none;
        border-color: #e2e8f0 !important;
    }

    .tbl-rab input.input-rab-task-picker[readonly] {
        pointer-events: auto !important;
        cursor: pointer !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 500;
    }

    .tbl-rab input.input-rab-task-picker[readonly]:hover {
        border-color: var(--palette-primary) !important;
        background-color: #fff5f5 !important;
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
        text-align-last: center;
    }

    .input-roman {
        text-align: center;
        font-weight: 700;
        color: var(--palette-primary) !important;
    }

    /* ── Row States ── */
    .row-locked td {
        background: #ffffff;
        opacity: 1;
    }

    .row-locked input,
    .row-locked select {
        pointer-events: none;
        background: transparent !important;
        border-color: transparent !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .row-locked input:not(.input-roman),
    .row-locked select {
        color: #0f172a !important;
    }

    .row-locked input.input-roman {
        color: var(--palette-primary) !important;
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
        background: #fff5f5 !important;
        font-weight: 700;
        border-top: 1px solid #cbd5e1;
        border-bottom: 2px solid #ffcccc !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        color: var(--palette-primary);
        font-size: 11px;
    }

    /* ── Grand Total ── */
    #grandTotalRab {
        font-family: 'Outfit', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: var(--palette-primary) !important;
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
        background: #fff5f5;
        color: var(--palette-primary);
    }

    .tbl-btn-mat:hover {
        background: #ffe5e5;
        color: var(--palette-primary-hover);
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

    .tbl-btn-detail {
        background: #eff6ff;
        color: #3b82f6;
        border: none;
    }

    .tbl-btn-detail:hover {
        background: #dbeafe;
        color: #1d4ed8;
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
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        padding: 20px 24px;
        border: none;
    }

    .modal-rab .modal-title,
    .modal-rab .modal-header .modal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #ffffff !important;
    }

    .modal-rab .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-rab .modal-body {
        padding: 24px;
        background: #f8fafc;
    }

    @media (min-width: 992px) {
        .modal-rab .modal-dialog.modal-lg {
            max-width: 900px !important;
        }
        .modal-rab .modal-content {
            height: 560px !important;
        }
        .modal-rab .modal-body {
            height: calc(560px - 70px) !important;
            padding: 24px !important;
        }
        .modal-rab .modal-body > .row {
            height: 100%;
        }
        .modal-rab .modal-body > .row > .col-md-5,
        .modal-rab .modal-body > .row > .col-md-7 {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        #rabMaterialList {
            flex-grow: 1;
            max-height: 430px !important;
            overflow-y: auto;
            padding-right: 4px;
        }
        .empty-materials {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
        }
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

    #rabMaterialList {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .mat-photo-wrapper {
        width: 56px;
        height: 56px;
        position: relative;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mat-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .mat-photo:hover {
        transform: scale(1.1);
    }

    .mat-photo-placeholder {
        width: 100%;
        height: 100%;
        background: #ffe5e5;
        color: var(--palette-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    #rabMaterialList::-webkit-scrollbar {
        width: 6px;
    }

    #rabMaterialList::-webkit-scrollbar-track {
        background: transparent;
    }

    #rabMaterialList::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #rabMaterialList::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .mat-meta-badge {
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .mat-meta-list {
        font-size: 11px;
        color: #64748b;
    }

    .mat-meta {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #64748b;
        font-size: 11px;
    }

    .mat-meta i {
        color: #94a3b8;
    }

    .empty-materials {
        text-align: center;
        padding: 40px 24px;
        color: #94a3b8;
        font-size: 13px;
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
    }

    .empty-materials i {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
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
        color: var(--palette-primary) !important;
        transition: all 0.2s ease;
        font-weight: 600;
    }

    .row-add-row-in-group button:hover {
        color: var(--palette-primary-hover) !important;
        transform: translateX(4px);
        text-decoration: none !important;
    }

    /* ── Fix Select2 Z-Index in Modal ── */
    .select2-container--open,
    .select2-dropdown {
        z-index: 9999 !important;
    }

    /* ── Select2 Custom Styling for Premium Modal ── */
    .modal-rab .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        padding: 0 16px !important;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        background-color: #fff !important;
        display: flex;
        align-items: center;
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-rab .select2-container .select2-selection--single:focus,
    .modal-rab .select2-container--open .select2-selection--single {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.15) !important;
    }

    .modal-rab .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        padding-right: 52px !important;
        color: #0f172a !important;
        line-height: 44px !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modal-rab .select2-selection__clear {
        position: absolute !important;
        right: 36px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 18px !important;
        color: #94a3b8 !important;
        cursor: pointer !important;
        z-index: 10 !important;
        background: transparent !important;
        border: none !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 4px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease, transform 0.15s ease;
    }

    .modal-rab .select2-selection__clear:hover {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.2) !important;
    }

    .modal-rab .select2-container .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        right: 14px !important;
        width: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        pointer-events: none;
    }

    .modal-rab .select2-container .select2-selection--single .select2-selection__arrow b {
        border-color: #64748b transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        margin-left: 0 !important;
        margin-top: 0 !important;
        position: static !important;
        transition: border-color 0.15s ease;
    }

    .modal-rab .select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #64748b transparent !important;
        border-width: 0 4px 5px 4px !important;
    }

    .modal-rab .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden;
        z-index: 10000 !important;
        background: #fff !important;
        padding: 8px !important;
        margin-top: 4px;
    }

    .modal-rab .select2-search--dropdown {
        padding: 6px 6px 10px 6px !important;
    }

    .modal-rab .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        outline: none !important;
    }

    .modal-rab .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.15) !important;
    }

    .modal-rab .select2-results__options {
        max-height: 250px !important;
    }

    .modal-rab .select2-results__option {
        padding: 8px 12px !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-family: 'Outfit', sans-serif !important;
        color: #334155 !important;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }

    .modal-rab .select2-results__option--highlighted[aria-selected] {
        background-color: var(--palette-primary) !important;
        color: #ffffff !important;
    }

    .modal-rab .select2-results__option[aria-selected=true] {
        background-color: #f1f5f9 !important;
        color: var(--palette-primary) !important;
        font-weight: 600 !important;
    }

    /* ── Recommended Product Cards Premium Styles & Transitions ── */
    .recommended-prod-row {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .recommended-prod-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05), 0 2px 4px rgba(0, 0, 0, 0.02) !important;
    }

    /* Tall & flexible dialog layout specific to Materials Modal */
    #modalRabMaterials .modal-content {
        height: 85vh !important;
        max-height: 850px !important;
        display: flex;
        flex-direction: column;
    }

    #modalRabMaterials .modal-body {
        height: calc(85vh - 70px) !important;
        max-height: calc(850px - 70px) !important;
        overflow-y: auto !important;
        flex-grow: 1;
    }

    /* ── Accordion Premium Styling in Modal ── */
    #accordionRabMaterials .accordion-item {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.2s ease;
    }

    #accordionRabMaterials .accordion-item:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
    }

    #accordionRabMaterials .accordion-button {
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif;
        box-shadow: none !important;
        padding: 16px 20px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #accordionRabMaterials .accordion-button:not(.collapsed) {
        background-color: #fafbfc !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #0f172a !important;
    }

    #accordionRabMaterials .accordion-button::after {
        margin-left: 15px;
        flex-shrink: 0;
        background-size: 14px;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    #accordionRabMaterials .accordion-button:not(.collapsed)::after {
        transform: rotate(-180deg) !important;
    }

    #accordionRabMaterials .collapsing {
        transition: height 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    #accordionRabMaterials .accordion-button {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !important;
    }
</style>