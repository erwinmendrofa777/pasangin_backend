<!-- Load Tabler Icons CDN so that user's ti classes render beautifully! -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  .ms-root,
  .ms-root * {
    box-sizing: border-box;
  }

  .ms-root {
    font-family: sans-serif;
    color: #1a1a2e;
  }

  .ms-root .ms-header {
    background: #4f46e5;
    border-radius: 12px 12px 0 0;
    padding: 1.1rem 1.4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .ms-root .ms-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .ms-root .ms-header-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .ms-root .ms-header-icon .ti {
    font-size: 18px;
    color: #fff;
  }

  .ms-root .ms-header-title {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
  }

  .ms-root .ms-header-sub {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.75);
    margin-top: 2px;
  }

  .ms-root .ms-header-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
  }

  .ms-root .ms-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.25);
  }

  .ms-root .ms-badge.total {
    background: #fff;
    color: #4f46e5;
    font-weight: 600;
  }

  .ms-root .ms-badge .ti {
    font-size: 13px;
  }

  .ms-root .ms-toolbar {
    background: #ffffff;
    border-left: 1px solid #e2e4e9;
    border-right: 1px solid #e2e4e9;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .ms-root .ms-search-wrap {
    position: relative;
    flex: 1;
    min-width: 160px;
  }

  .ms-root .ms-search-wrap .ti {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #6b7280;
    pointer-events: none;
  }

  .ms-root .ms-search {
    width: 100%;
    padding: 7px 10px 7px 32px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #f9fafb;
    color: #1a1a2e;
    outline: none;
  }

  .ms-root .ms-search:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    background: #fff;
  }

  .ms-root .ms-filter-group {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .ms-root .ms-filter-dd-btn {
    padding: 6px 13px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    cursor: pointer;
    background: #ffffff;
    color: #4b5563;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    outline: none;
  }

  .ms-root .ms-filter-dd-btn:hover {
    background: #f3f4f6;
    border-color: #a5b4fc;
    color: #4f46e5;
  }

  .ms-root .ms-filter-dd-btn:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  .ms-root .ms-table-wrap {
    border: 1px solid #e2e4e9;
    border-top: none;
    border-radius: 0 0 12px 12px;
    overflow: visible !important;
    background: #ffffff;
  }

  .ms-root table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }

  .ms-root thead th {
    background: #f3f4f6;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #6b7280;
    text-transform: uppercase;
    padding: 10px 14px;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
  }

  .ms-root thead th:nth-child(1) {
    width: 46px;
    text-align: center;
  }

  .ms-root thead th:nth-child(2) {
    width: 180px;
  }

  .ms-root thead th:nth-child(3) {}

  .ms-root thead th:nth-child(4) {
    width: 150px;
  }

  .ms-root thead th:nth-child(5) {
    width: 110px;
    text-align: center;
  }

  .ms-root thead th:nth-child(6) {
    width: 145px;
    text-align: center;
  }

  .ms-root thead th:nth-child(7) {
    width: 150px;
    text-align: center;
  }

  .ms-root tbody tr {
    border-bottom: 1px solid #f0f0f5;
    transition: background 0.1s;
  }

  .ms-root tbody tr:last-child {
    border-bottom: none;
  }

  .ms-root tbody tr:hover {
    background: #f8f8ff;
  }

  .ms-root tbody td {
    padding: 11px 14px;
    font-size: 13px;
    vertical-align: middle;
    color: #1a1a2e;
  }

  .ms-root tbody td:last-child {
    padding-right: 24px;
  }

  .ms-root .row-no {
    text-align: center;
  }

  .ms-root .row-no span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: #e5e7eb;
    color: #4b5563;
    font-size: 11px;
    font-weight: 600;
  }

  .ms-root .type-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
  }

  .ms-root .type-badge.bahan {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
  }

  .ms-root .type-badge.alat {
    background: #ede9fe;
    color: #5b21b6;
    border: 1px solid #c4b5fd;
  }

  .ms-root .type-badge .ti {
    font-size: 11px;
  }

  .ms-root .item-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
  }

  .ms-root .item-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 12px;
    background: #f0f0ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
  }

  .ms-root .item-chip .ti {
    font-size: 11px;
    color: #6366f1;
  }

  .ms-root .time-date {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
  }

  .ms-root .time-clock {
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 3px;
  }

  .ms-root .time-clock .ti {
    font-size: 11px;
  }

  .ms-root .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    outline: none;
    transition: all 0.15s;
  }

  .ms-root .status-pill:hover {
    opacity: 0.85;
    transform: translateY(-1px);
  }

  .ms-root .status-pill.pending {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
  }

  .ms-root .status-pill.approved {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #86efac;
  }

  .ms-root .status-pill.rejected {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
  }

  .ms-root .status-pill .ti {
    font-size: 12px;
  }

  .ms-root .status-cell {
    text-align: center;
  }

  .ms-root .dd-wrap {
    position: relative;
    display: inline-block;
  }

  .ms-root .dd-menu {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 6px);
    background: #ffffff;
    border: 1px solid #e2e4e9;
    border-radius: 12px;
    padding: 5px;
    z-index: 1050;
    min-width: 150px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
  }

  .ms-root .dd-wrap.dropup .dd-menu {
    top: auto;
    bottom: calc(100% + 6px);
  }

  .ms-root .dd-wrap.open .dd-menu {
    display: block;
  }

  .ms-root .dd-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 11px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: background 0.1s;
  }

  .ms-root .dd-item .ti {
    font-size: 14px;
  }

  .ms-root .dd-item.pending {
    color: #92400e;
  }

  .ms-root .dd-item.pending:hover {
    background: #fffbeb;
  }

  .ms-root .dd-item.approved {
    color: #15803d;
  }

  .ms-root .dd-item.approved:hover {
    background: #f0fdf4;
  }

  .ms-root .dd-item.rejected {
    color: #b91c1c;
  }

  .ms-root .dd-item.rejected:hover {
    background: #fef2f2;
  }

  .ms-root .dd-item.all-item {
    color: #4b5563;
  }

  .ms-root .dd-item.all-item:hover {
    background: #ede9fe;
    color: #4f46e5;
  }

  .ms-root .dd-item.bahan-item {
    color: #b45309;
  }

  .ms-root .dd-item.bahan-item:hover {
    background: #fffbeb;
    color: #b45309;
  }

  .ms-root .dd-item.alat-item {
    color: #6d28d9;
  }

  .ms-root .dd-item.alat-item:hover {
    background: #ede9fe;
    color: #6d28d9;
  }

  .ms-root .dd-menu.filter-menu {
    left: 0;
    right: auto;
    min-width: 135px;
  }

  .ms-root .ms-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px 16px;
    border: 1px solid #e2e4e9;
    border-top: none;
    border-radius: 0 0 12px 12px;
    background: #f9fafb;
    font-size: 12px;
    color: #6b7280;
  }

  .ms-root .pag-btns {
    display: flex;
    gap: 4px;
  }

  .ms-root .pag-btn {
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    cursor: pointer;
    font-size: 12px;
    background: #ffffff;
    color: #374151;
    transition: all 0.12s;
  }

  .ms-root .pag-btn:hover:not(:disabled):not(.active) {
    background: #ede9fe;
    color: #4f46e5;
    border-color: #a5b4fc;
  }

  .ms-root .pag-btn.active {
    background: #4f46e5;
    color: #fff;
    border-color: #4f46e5;
  }

  .ms-root .pag-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
  }

  .ms-root .empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: #fff;
  }

  .ms-root .empty-state .ti {
    font-size: 40px;
    color: #9ca3af;
  }

  .ms-root .empty-state p {
    font-size: 13px;
    color: #6b7280;
    margin-top: 8px;
  }

  .ms-root .ms-add-btn {
    padding: 6px 13px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #4f46e5;
    border-radius: 20px;
    cursor: pointer;
    background: #4f46e5;
    color: #fff;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    outline: none;
  }

  .ms-root .ms-add-btn:hover {
    background: #394eea;
    border-color: #394eea;
  }

  .ms-root .btn-action {
    padding: 0;
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    outline: none;
    color: #fff !important;
    text-decoration: none !important;
    margin: 0 2px;
  }

  .ms-root .btn-action.detail {
    background: #4f46e5;
    border: 1px solid #4f46e5;
  }

  .ms-root .btn-action.detail:hover {
    background: #3b30e2;
    border-color: #3b30e2;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
    color: #fff !important;
  }

  .ms-root .btn-action.edit {
    background: #ffa426;
    border: 1px solid #ffa426;
  }

  .ms-root .btn-action.edit:hover {
    background: #ffb755;
    border-color: #ffb755;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 164, 38, 0.3);
    color: #fff !important;
  }

  .ms-root .btn-action.delete {
    background: #fc544b;
    border: 1px solid #fc544b;
  }

  .ms-root .btn-action.delete:hover {
    background: #fd726a;
    border-color: #fd726a;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(252, 84, 75, 0.3);
    color: #fff !important;
  }

  /* Modal Custom Style */
  .ms-modal-content {
    border-radius: 16px !important;
    border: none !important;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
    overflow: hidden;
    background: #ffffff;
  }

  .ms-modal-header {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #ffffff;
    padding: 1.25rem 1.5rem !important;
    border-bottom: none !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .ms-modal-header .modal-title {
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.3px;
  }

  .ms-modal-header .btn-close-custom {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #ffffff;
    transition: all 0.2s ease;
    padding: 0;
    outline: none;
  }

  .ms-modal-header .btn-close-custom:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
  }

  .ms-modal-header .btn-close-custom i {
    font-size: 16px;
  }

  /* Segmented Card Selector */
  .ms-root .ms-type-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .ms-root .ms-type-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border: 2px solid #e2e4e9;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #ffffff;
    margin: 0;
  }

  .ms-root .ms-type-card i {
    font-size: 20px;
    color: #6b7280;
    transition: all 0.2s ease;
  }

  .ms-root .ms-type-card .card-text {
    display: flex;
    flex-direction: column;
  }

  .ms-root .ms-type-card .title {
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    transition: all 0.2s ease;
  }

  .ms-root .ms-type-card .desc {
    font-size: 11px;
    color: #9ca3af;
  }

  /* Active/Hover states */
  .ms-root .ms-type-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
  }

  /* When radio is checked */
  .ms-root #type-bahan:checked+.ms-type-card.bahan {
    border-color: #fbbf24;
    background: #fffdf5;
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15);
  }

  .ms-root #type-bahan:checked+.ms-type-card.bahan i {
    color: #d97706;
  }

  .ms-root #type-bahan:checked+.ms-type-card.bahan .title {
    color: #b45309;
  }

  .ms-root #type-alat:checked+.ms-type-card.alat {
    border-color: #8b5cf6;
    background: #fbfaff;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.15);
  }

  .ms-root #type-alat:checked+.ms-type-card.alat i {
    color: #7c3aed;
  }

  .ms-root #type-alat:checked+.ms-type-card.alat .title {
    color: #6d28d9;
  }

  /* Custom Textarea */
  .ms-root .ms-textarea-container {
    border: 1px solid #d1d5db;
    border-radius: 12px;
    background: #f9fafb;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .ms-root .ms-textarea-container:focus-within {
    border-color: #6366f1;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
  }

  .ms-root .ms-textarea-hint {
    padding: 8px 12px;
    border-top: 1px solid #f0f0f5;
    background: #f8fafc;
    font-size: 11px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .ms-root .ms-textarea-hint i {
    font-size: 13px;
    color: #6366f1;
  }

  /* Custom Buttons */
  .ms-root .ms-btn-secondary {
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    background: #ffffff;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    outline: none;
  }

  .ms-root .ms-btn-secondary:hover {
    background: #f9fafb;
    border-color: #cbd5e1;
    color: #1f2937;
  }

  .ms-root .ms-btn-primary {
    padding: 8px 20px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    border-radius: 20px;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    outline: none;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
  }

  .ms-root .ms-btn-primary:hover {
    background: linear-gradient(135deg, #3b30e2 0%, #4f46e5 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
  }

  .ms-root .ms-select-container:focus-within {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
  }

  .ms-root .ms-select-custom:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
  }

  .ms-root .custom-select-trigger:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
  }
  .ms-root .custom-select-option:hover {
    background: #f5f3ff !important;
    color: #4f46e5 !important;
  }
  .ms-root .custom-select-option:hover .ti {
    color: #4f46e5 !important;
  }
  .ms-root .custom-select-options::-webkit-scrollbar {
    width: 6px;
  }
  .ms-root .custom-select-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
  .ms-root .custom-select-options::-webkit-scrollbar-track {
    background: transparent;
  }

  /* Detail Modal Custom Styles */
  .ms-root .detail-section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #4b5563;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .ms-root .detail-section-title i {
    font-size: 15px;
    color: #4f46e5;
  }
  .ms-root .detail-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    background: #f8fafc;
    border: 1px solid #e2e4e9;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
  }
  .ms-root .detail-info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .ms-root .detail-info-label {
    font-size: 10px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .ms-root .detail-info-value {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .ms-root .detail-item-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #ffffff;
    border: 1px solid #e2e4e9;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 8px;
    transition: all 0.15s ease;
  }
  .ms-root .detail-item-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
    transform: translateX(4px);
  }
  .ms-root .detail-item-card i {
    color: #6366f1;
    font-size: 16px;
  }
  .ms-root .detail-photo-wrapper {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e4e9;
    background: #f9fafb;
    margin-top: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    text-align: center;
    padding: 8px;
  }
  .ms-root .detail-photo-wrapper img {
    max-width: 100%;
    max-height: 250px;
    border-radius: 8px;
    object-fit: contain;
    transition: transform 0.2s ease;
  }
  .ms-root .detail-photo-wrapper img:hover {
    transform: scale(1.02);
  }
  .ms-root .detail-comment-box {
    margin-top: 8px;
    padding: 12px 16px;
    background: #fffbeb;
    border-left: 4px solid #d97706;
    border-radius: 4px 12px 12px 4px;
    font-size: 13px;
    font-weight: 500;
    color: #92400e;
    line-height: 1.5;
  }
</style>

<h2 class="sr-only">Daftar pengajuan bahan dan alat dari tukang di lapangan</h2>

<div class="ms-root" style="position:relative; padding-bottom: 60px;">
  <div class="ms-header">
    <div class="ms-header-left">
      <div class="ms-header-icon"><i class="ti ti-package" aria-hidden="true"></i></div>
      <div>
        <div class="ms-header-title">Daftar Pengajuan Bahan &amp; Alat</div>
        <div class="ms-header-sub">Pengajuan tambahan dari Tukang / Pekerja di lapangan</div>
      </div>
    </div>
    <div class="ms-header-badges">
      <span class="ms-badge"><i class="ti ti-hammer" aria-hidden="true"></i> Bahan: <strong
          id="cnt-bahan">0</strong></span>
      <span class="ms-badge"><i class="ti ti-tool" aria-hidden="true"></i> Alat: <strong id="cnt-alat">0</strong></span>
      <span class="ms-badge total"><i class="ti ti-list" aria-hidden="true"></i> <strong id="cnt-total">0</strong>
        Total</span>
    </div>
  </div>

  <div class="ms-toolbar">
    <div class="ms-search-wrap">
      <i class="ti ti-search" aria-hidden="true"></i>
      <input class="ms-search" type="text" id="ms-search-input" placeholder="Cari pengajuan...">
    </div>
    <div class="ms-filter-group">
      <!-- Dropdown Filter Tipe -->
      <div class="dd-wrap" id="dd-filter-type">
        <button class="ms-filter-dd-btn" onclick="toggleFilterDd()" id="filter-dropdown-btn"
          aria-label="Filter tipe pengajuan" aria-haspopup="true" aria-expanded="false">
          <i class="ti ti-filter" aria-hidden="true"></i> Filter: Semua <i class="ti ti-chevron-down"
            style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>
        </button>
        <div class="dd-menu filter-menu" role="menu">
          <div class="dd-item all-item" role="menuitem" onclick="setFilterType('all')"><i class="ti ti-list"
              aria-hidden="true"></i>Semua</div>
          <div class="dd-item bahan-item" role="menuitem" onclick="setFilterType('bahan')"><i class="ti ti-hammer"
              aria-hidden="true"></i>Bahan</div>
          <div class="dd-item alat-item" role="menuitem" onclick="setFilterType('alat')"><i class="ti ti-tool"
              aria-hidden="true"></i>Alat</div>
        </div>
      </div>
      <button class="ms-add-btn" onclick="openAddModal()">
        <i class="ti ti-plus" aria-hidden="true"></i> Tambah Pengajuan
      </button>
    </div>
  </div>

  <div class="ms-table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width: 46px; text-align: center;">No</th>
          <th style="width: 180px;">Nama Tukang</th>
          <th>Title</th>
          <th style="width: 150px;">Tanggal</th>
          <th style="width: 110px; text-align: center;">Tipe</th>
          <th style="width: 145px; text-align: center;">Status</th>
          <th style="width: 150px; text-align: center;">Aksi</th>
        </tr>
      </thead>
      <tbody id="ms-tbody"></tbody>
    </table>
    <div class="empty-state" id="ms-empty" style="display:none;">
      <i class="ti ti-package-off" aria-hidden="true"></i>
      <p>Tidak ada data yang cocok</p>
    </div>
  </div>

  <div class="ms-footer">
    <span id="ms-info-text" style="color:#4b5563;">Memuat data...</span>
    <div class="pag-btns" id="pag-btns"></div>
  </div>

</div>

<!-- Form tersembunyi untuk posting pembaruan status -->
<form id="formUpdateStatusMaterial" action="" method="post" style="display:none;">
  <?= csrf_field() ?>
  <input type="hidden" name="status" id="inputStatusMaterial">
</form>

<!-- Modal Bootstrap 5 untuk Tambah / Edit Pengajuan -->
<div class="modal fade" id="modalMaterialSubmission" tabindex="-1" aria-labelledby="modalMaterialSubmissionLabel"
  aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content ms-modal-content ms-root">
      <div class="ms-modal-header">
        <h5 class="modal-title" id="modalMaterialSubmissionLabel">Tambah Pengajuan</h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form id="formMaterialSubmission" action="" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="construction_id" value="<?= $construction['id'] ?? '' ?>">
        <div class="modal-body" style="padding: 24px;">
          <!-- Judul Pengajuan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Judul Pengajuan</label>
            <input type="text" class="form-control" name="title" id="submission-title" placeholder="Contoh: Pengajuan Semen Cor Lantai 2" style="border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #1a1a2e; width: 100%; outline: none; background: #ffffff;">
          </div>

          <!-- Tukang yang Mengajukan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Tukang Yang Mengajukan</label>
            <div class="custom-select-wrapper" style="position: relative; width: 100%;">
              <input type="hidden" name="job_applications_id" id="submission-job-app" value="">
              <button type="button" class="custom-select-trigger" onclick="toggleCustomSelect(event)" style="display: flex; align-items: center; justify-content: space-between; width: 100%; height: 42px; padding: 0 14px; font-size: 13px; font-weight: 500; color: #1a1a2e; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; outline: none; cursor: pointer; transition: all 0.2s ease;">
                <span style="display: flex; align-items: center; gap: 8px;">
                  <i class="ti ti-user" style="font-size: 16px; color: #6b7280;" aria-hidden="true"></i>
                  <span id="custom-select-label">-- Pilih Tukang (Opsional) --</span>
                </span>
                <i class="ti ti-chevron-down" style="font-size: 14px; color: #6b7280; transition: transform 0.2s; pointer-events: none;" id="custom-select-arrow" aria-hidden="true"></i>
              </button>
              <div class="custom-select-options" id="custom-select-options-list" style="display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: #ffffff; border: 1px solid #e2e4e9; border-radius: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); z-index: 1080; max-height: 200px; overflow-y: auto; padding: 6px;">
                <div class="custom-select-option" onclick="selectCustomOption('', '-- Pilih Tukang (Opsional) --')" style="padding: 10px 12px; font-size: 13px; color: #4b5563; border-radius: 6px; cursor: pointer; transition: background 0.15s; display: flex; align-items: center; gap: 8px;">
                  <i class="ti ti-user-x" style="font-size: 14px; color: #9ca3af;" aria-hidden="true"></i>
                  <span>-- Pilih Tukang (Opsional) --</span>
                </div>
                <?php if (!empty($applicants)): ?>
                  <?php foreach ($applicants as $app): ?>
                    <div class="custom-select-option" data-value="<?= $app['id'] ?>" onclick="selectCustomOption('<?= $app['id'] ?>', '<?= esc($app['tukang_name']) ?>')" style="padding: 10px 12px; font-size: 13px; color: #1a1a2e; border-radius: 6px; cursor: pointer; transition: background 0.15s; display: flex; align-items: center; justify-content: space-between;">
                      <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="ti ti-user" style="font-size: 14px; color: #4f46e5;" aria-hidden="true"></i>
                        <span style="font-weight: 500;"><?= esc($app['tukang_name']) ?></span>
                      </span>
                      <span style="font-size: 11px; padding: 2px 8px; border-radius: 12px; background: #f3f4f6; color: #6b7280; font-weight: 600;"><?= esc($app['status']) ?></span>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Tipe Pengajuan Selector -->
          <div class="mb-3">
            <label class="form-label"
              style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Tipe
              Pengajuan</label>
            <div class="ms-type-selector">
              <input type="radio" name="type" id="type-bahan" value="bahan" checked style="display: none;">
              <label for="type-bahan" class="ms-type-card bahan">
                <i class="ti ti-hammer" aria-hidden="true"></i>
                <div class="card-text">
                  <span class="title">Bahan</span>
                </div>
              </label>

              <input type="radio" name="type" id="type-alat" value="alat" style="display: none;">
              <label for="type-alat" class="ms-type-card alat">
                <i class="ti ti-tool" aria-hidden="true"></i>
                <div class="card-text">
                  <span class="title">Alat</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Deskripsi Textarea -->
          <div class="mb-3">
            <label class="form-label"
              style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Deskripsi /
              Daftar Item</label>
            <div class="ms-textarea-container">
              <textarea class="form-control" name="description" id="submission-description" rows="5" required
                style="border: none; outline: none; box-shadow: none; padding: 12px; font-size: 13px; font-weight: 500; resize: none; background: transparent; width: 100%; height: 100%; min-height: 120px; color: #1a1a2e;"></textarea>
              <div class="ms-textarea-hint">
                <i class="ti ti-info-circle" aria-hidden="true"></i> Pisahkan setiap item dengan baris baru (Enter)
              </div>
            </div>
          </div>

          <!-- Foto Bukti/Kebutuhan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Foto Bukti/Kebutuhan</label>
            <div class="ms-file-upload-wrap" style="position: relative; border: 2px dashed #d1d5db; border-radius: 8px; padding: 16px; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.2s ease;">
              <input type="file" name="photo" id="submission-photo" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="handleFileChange(this)">
              <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; z-index: 1;">
                <i class="ti ti-camera" style="font-size: 26px; color: #6366f1;" aria-hidden="true"></i>
                <div style="font-size: 13px; font-weight: 600; color: #4f46e5;" id="photo-file-label">Pilih Foto atau Ambil Gambar</div>
                <div style="font-size: 11px; color: #6b7280;">Format: JPG, PNG, WebP (Maks. 5MB)</div>
              </div>
            </div>
            <div id="edit-photo-preview-wrap" style="display:none; margin-top: 12px; text-align: center;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Preview Foto:</div>
              <img id="edit-photo-preview" src="" style="max-height: 120px; border-radius: 8px; border: 1px solid #e2e4e9; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #f0f0f5; padding: 1rem 1.5rem; gap: 8px;">
          <button type="button" class="ms-btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x" aria-hidden="true"></i> Batal
          </button>
          <button type="submit" class="ms-btn-primary">
            <i class="ti ti-device-floppy" aria-hidden="true"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Bootstrap 5 untuk Detail Pengajuan -->
<div class="modal fade" id="modalMaterialDetail" tabindex="-1" aria-labelledby="modalMaterialDetailLabel"
  aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content ms-modal-content ms-root">
      <div class="ms-modal-header">
        <h5 class="modal-title" id="modalMaterialDetailLabel">Detail Pengajuan Bahan & Alat</h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <div class="modal-body" style="padding: 24px;">
        <!-- Header Info (Judul) -->
        <div style="margin-bottom: 20px;">
          <h4 id="detail-title" style="font-weight: 700; color: #1a1a2e; margin-bottom: 8px; font-size: 18px; word-break: break-word;">Judul Pengajuan</h4>
          <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span id="detail-type" class="type-badge"></span>
            <span id="detail-status" class="status-pill" style="cursor: default; pointer-events: none;"></span>
          </div>
        </div>

        <!-- Info Grid -->
        <div class="detail-info-grid">
          <div class="detail-info-item">
            <span class="detail-info-label">Diajukan Oleh</span>
            <span id="detail-tukang" class="detail-info-value">-</span>
          </div>
          <div class="detail-info-item">
            <span class="detail-info-label">Tanggal Pengajuan</span>
            <span id="detail-date" class="detail-info-value">-</span>
          </div>
        </div>

        <div class="row">
          <!-- Left Column: Items List -->
          <div class="col-md-6 mb-3">
            <div class="detail-section-title">
              <i class="ti ti-list-check"></i> Daftar Item
            </div>
            <div id="detail-items-container" style="max-height: 250px; overflow-y: auto; padding-right: 4px;">
              <!-- Items populated dynamically -->
            </div>
          </div>

          <!-- Right Column: Photo Attachment -->
          <div class="col-md-6 mb-3" id="detail-photo-container">
            <div class="detail-section-title">
              <i class="ti ti-photo"></i> Foto Bukti / Kebutuhan
            </div>
            <div class="detail-photo-wrapper">
              <a id="detail-photo-link" href="" target="_blank" title="Buka gambar di tab baru">
                <img id="detail-photo-img" src="" alt="Foto Bukti">
              </a>
            </div>
          </div>
        </div>

        <!-- Catatan Admin (Feedback) -->
        <div id="detail-comment-wrap" style="display: none; margin-top: 15px;">
          <div class="detail-section-title" style="color: #b45309;">
            <i class="ti ti-message" style="color: #b45309;"></i> Catatan dari Admin
          </div>
          <div class="detail-comment-box" id="detail-comment-text">
            Catatan...
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: 1px solid #f0f0f5; padding: 1rem 1.5rem;">
        <button type="button" class="ms-btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x" aria-hidden="true"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>


<script>
  // Load real database data dynamically from PHP
  const demo = <?= json_encode(array_map(function ($sub) {
    $description = trim($sub['description'] ?? '');
    $items = [];
    $decoded = json_decode($description, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
      $items = $decoded;
    } else {
      $jsonLike = str_replace("'", '"', $description);
      $decodedJsonLike = json_decode($jsonLike, true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($decodedJsonLike)) {
        $items = $decodedJsonLike;
      } else {
        if (strpos($description, '[') === 0 && strrpos($description, ']') === strlen($description) - 1) {
          $cleanDesc = trim($description, '[]');
          preg_match_all('/\'([^\']*)\'|"([^"]*)"|([^,]+)/', $cleanDesc, $matches);
          $combined = array_merge(array_filter($matches[1]), array_filter($matches[2]));
          if (!empty($combined)) {
            $items = $combined;
          } else {
            $items = array_map(function ($val) {
              return trim($val, " '\"");
            }, explode(',', $cleanDesc));
          }
        } else {
          if (strpos($description, "\n") !== false) {
            $items = explode("\n", $description);
          } else {
            $items = [$description];
          }
        }
      }
    }
    return [
      'id' => (int) $sub['id'],
      'type' => $sub['type'],
      'items' => array_filter(array_map('trim', $items)),
      'date' => $sub['created_at'],
      'status' => $sub['status'],
      'title' => $sub['title'] ?? '',
      'comment' => $sub['comment'] ?? '',
      'photo_url' => !empty($sub['photo']) ? base_url('uploads/construction/material_submissions/' . $sub['photo']) : null,
      'job_applications_id' => $sub['job_applications_id'] ?? null,
      'tukang_name' => $sub['tukang_name'] ?? null
    ];
  }, $material_submissions ?? [])) ?>;

  let data = demo.map(d => ({ ...d }));
  let filter = 'all', search = '', page = 1;
  const PER_PAGE = 5;

  const statusConfig = {
    pending: { icon: 'ti-clock', label: 'PENDING', cls: 'pending' },
    approved: { icon: 'ti-circle-check', label: 'APPROVED', cls: 'approved' },
    rejected: { icon: 'ti-circle-x', label: 'REJECTED', cls: 'rejected' },
  };
  const typeConfig = {
    bahan: { icon: 'ti-hammer', label: 'BAHAN' },
    alat: { icon: 'ti-tool', label: 'ALAT' },
  };

  function filtered() {
    return data.filter(r => {
      const matchType = filter === 'all' || (r.type || '').toLowerCase() === filter.toLowerCase();
      const q = search.toLowerCase();
      const matchSearch = !q ||
        r.items.some(i => i.toLowerCase().includes(q)) ||
        (r.type || '').toLowerCase().includes(q) ||
        (r.title || '').toLowerCase().includes(q) ||
        (r.tukang_name || '').toLowerCase().includes(q) ||
        (r.status || '').toLowerCase().includes(q);
      return matchType && matchSearch;
    });
  }

  function fmtDate(str) {
    if (!str) return '-';
    const d = new Date(str.replace(' ', 'T'));
    if (isNaN(d.getTime())) return str;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
  }
  function fmtTime(str) {
    if (!str) return '';
    return str.split(' ')[1] || '';
  }

  function renderBadges() {
    document.getElementById('cnt-bahan').textContent = data.filter(d => (d.type || '').toLowerCase() === 'bahan').length;
    document.getElementById('cnt-alat').textContent = data.filter(d => (d.type || '').toLowerCase() === 'alat').length;
    document.getElementById('cnt-total').textContent = data.length;
  }

  function renderTable() {
    const rows = filtered();
    const total = rows.length;
    const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
    if (page > totalPages) page = totalPages;
    const start = (page - 1) * PER_PAGE;
    const slice = rows.slice(start, start + PER_PAGE);

    const tbody = document.getElementById('ms-tbody');
    const empty = document.getElementById('ms-empty');

    if (slice.length === 0) {
      tbody.innerHTML = '';
      empty.style.display = '';
    } else {
      empty.style.display = 'none';
      tbody.innerHTML = slice.map((r, i) => {
        const statusKey = (r.status || 'pending').toLowerCase();
        const sc = statusConfig[statusKey] || statusConfig.pending;

        const typeKey = (r.type || 'bahan').toLowerCase();
        const tc = typeConfig[typeKey] || typeConfig.bahan;

        const globalIdx = start + i + 1;
        return `<tr>
        <td class="row-no"><span>${globalIdx}</span></td>
        <td style="font-weight: 600; color: #1a1a2e; word-break: break-word;">${r.tukang_name || 'Admin'}</td>
        <td style="font-weight: 500; font-size: 13px; color: #1b1b2f; word-break: break-word;">${r.title || 'Pengajuan Tanpa Judul'}</td>
        <td>
          <div class="time-date">${fmtDate(r.date)}</div>
          <div class="time-clock"><i class="ti ti-clock" aria-hidden="true"></i>${fmtTime(r.date)}</div>
        </td>
        <td style="text-align: center;">
          <span class="type-badge ${typeKey}"><i class="ti ${tc.icon}" aria-hidden="true"></i>${tc.label}</span>
        </td>
        <td class="status-cell">
          <div class="dd-wrap" id="dd-${r.id}">
            <button class="status-pill ${sc.cls}" onclick="toggleDd(${r.id})" aria-label="Ubah status" aria-haspopup="true" aria-expanded="false">
              <i class="ti ${sc.icon}" aria-hidden="true"></i>${sc.label}
              <i class="ti ti-chevron-down" style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>
            </button>
            <div class="dd-menu" role="menu">
              <div class="dd-item pending"  role="menuitem" onclick="setStatus(${r.id},'pending')"><i class="ti ti-clock" aria-hidden="true"></i>Pending</div>
              <div class="dd-item approved" role="menuitem" onclick="setStatus(${r.id},'approved')"><i class="ti ti-circle-check" aria-hidden="true"></i>Approved</div>
              <div class="dd-item rejected" role="menuitem" onclick="setStatus(${r.id},'rejected')"><i class="ti ti-circle-x" aria-hidden="true"></i>Rejected</div>
            </div>
          </div>
        </td>
        <td style="text-align: center; white-space: nowrap;">
          <button class="btn-action detail" onclick="openDetailModal(${r.id})" title="Detail"><i class="ti ti-eye" aria-hidden="true"></i></button>
          <button class="btn-action edit" onclick="openEditModal(${r.id})" title="Edit"><i class="ti ti-edit" aria-hidden="true"></i></button>
          <button class="btn-action delete" onclick="deleteSubmission(${r.id})" title="Hapus"><i class="ti ti-trash" aria-hidden="true"></i></button>
        </td>
      </tr>`;

      }).join('');
    }

    const infoEl = document.getElementById('ms-info-text');
    infoEl.textContent = total === 0
      ? 'Tidak ada data'
      : `Menampilkan ${start + 1}–${Math.min(start + PER_PAGE, total)} dari ${total} data`;

    const pagEl = document.getElementById('pag-btns');
    let h = `<button class="pag-btn" onclick="goPage(${page - 1})" ${page === 1 ? 'disabled' : ''} aria-label="Sebelumnya"><i class="ti ti-chevron-left" aria-hidden="true"></i></button>`;
    for (let p = 1; p <= totalPages; p++) {
      h += `<button class="pag-btn ${p === page ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    }
    h += `<button class="pag-btn" onclick="goPage(${page + 1})" ${page === totalPages ? 'disabled' : ''} aria-label="Berikutnya"><i class="ti ti-chevron-right" aria-hidden="true"></i></button>`;
    pagEl.innerHTML = h;
  }

  function goPage(p) {
    const total = Math.max(1, Math.ceil(filtered().length / PER_PAGE));
    if (p < 1 || p > total) return;
    page = p; renderTable();
  }

  function toggleDd(id) {
    document.querySelectorAll('.dd-wrap').forEach(el => {
      if (el.id !== 'dd-' + id) {
        el.classList.remove('open', 'dropup');
        el.querySelector('button')?.setAttribute('aria-expanded', 'false');
      }
    });

    const el = document.getElementById('dd-' + id);
    if (!el) return;

    // Calculate remaining space below button to determine dropup direction
    const rect = el.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    const menuHeight = 160; // Estimated dropdown menu height

    if (spaceBelow < menuHeight) {
      el.classList.add('dropup');
    } else {
      el.classList.remove('dropup');
    }

    const open = el.classList.toggle('open');
    el.querySelector('button').setAttribute('aria-expanded', String(open));
  }

  function setStatus(id, status) {
    const row = data.find(r => r.id === id);
    if (!row || (row.status || '').toLowerCase() === status.toLowerCase()) { closeAll(); return; }

    let title = '';
    let confirmText = '';
    let color = '';
    let desc = '';

    const typeLabel = (row.type || 'pengajuan').toLowerCase();

    if (status === 'approved') {
      title = 'Setujui Pengajuan?';
      confirmText = 'Ya, Setujui!';
      color = '#47c363';
      desc = `Pengajuan ${typeLabel} ini akan disetujui dan tukang akan menerima notifikasinya.`;
    } else if (status === 'rejected') {
      title = 'Tolak Pengajuan?';
      confirmText = 'Ya, Tolak!';
      color = '#fc544b';
      desc = `Pengajuan ${typeLabel} ini akan ditolak dan tukang akan menerima notifikasinya.`;
    } else {
      title = 'Kembalikan ke Pending?';
      confirmText = 'Ya, Ubah!';
      color = '#ffa426';
      desc = `Status pengajuan ${typeLabel} ini akan dikembalikan ke pending.`;
    }

    // Gunakan Stisla SweetAlert2 jika ada, atau fallback native confirm
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: title,
        text: desc,
        icon: 'question',
        input: 'text',
        inputPlaceholder: 'Tambahkan catatan admin (opsional)...',
        showCancelButton: true,
        confirmButtonColor: color,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `<i class="fas fa-check me-1"></i> ${confirmText}`,
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          const actionUrl = "<?= base_url('admin/construction/update-material-status') ?>/" + id;
          const form = document.getElementById('formUpdateStatusMaterial');
          form.action = actionUrl;
          document.getElementById('inputStatusMaterial').value = status;
          
          let commentInput = form.querySelector('input[name="comment"]');
          if (!commentInput) {
            commentInput = document.createElement('input');
            commentInput.type = 'hidden';
            commentInput.name = 'comment';
            form.appendChild(commentInput);
          }
          commentInput.value = result.value || '';

          form.submit();
        } else {
          closeAll();
        }
      });
    } else {
      const comment = prompt(`${title}\n\n${desc}\n\nTambahkan catatan admin (opsional):`);
      if (comment !== null) {
        const actionUrl = "<?= base_url('admin/construction/update-material-status') ?>/" + id;
        const form = document.getElementById('formUpdateStatusMaterial');
        form.action = actionUrl;
        document.getElementById('inputStatusMaterial').value = status;
        
        let commentInput = form.querySelector('input[name="comment"]');
        if (!commentInput) {
          commentInput = document.createElement('input');
          commentInput.type = 'hidden';
          commentInput.name = 'comment';
          form.appendChild(commentInput);
        }
        commentInput.value = comment;

        form.submit();
      } else {
        closeAll();
      }
    }
  }

  function closeAll() {
    document.querySelectorAll('.dd-wrap').forEach(el => {
      el.classList.remove('open', 'dropup');
      el.querySelector('button')?.setAttribute('aria-expanded', 'false');
    });
  }

  document.getElementById('ms-search-input').addEventListener('input', e => { search = e.target.value; page = 1; renderTable(); });
  function toggleFilterDd() {
    const el = document.getElementById('dd-filter-type');
    if (!el) return;
    const isOpen = el.classList.contains('open');
    closeAll();
    if (!isOpen) {
      el.classList.add('open');
      el.querySelector('button').setAttribute('aria-expanded', 'true');
    }
  }

  function setFilterType(type) {
    filter = type;
    page = 1;
    const btn = document.getElementById('filter-dropdown-btn');
    const labels = { all: 'Semua', bahan: 'Bahan', alat: 'Alat' };
    btn.innerHTML = `<i class="ti ti-filter" aria-hidden="true"></i> Filter: ${labels[type]} <i class="ti ti-chevron-down" style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>`;
    const el = document.getElementById('dd-filter-type');
    if (el) {
      el.classList.remove('open');
      el.querySelector('button').setAttribute('aria-expanded', 'false');
    }
    renderTable();
  }

  // Global click event to close dropdown when clicking outside
  document.addEventListener('click', e => {
    if (!e.target.closest('.dd-wrap')) {
      closeAll();
    }
    if (!e.target.closest('.custom-select-wrapper')) {
      closeCustomSelect();
    }
  });

  function toggleCustomSelect(e) {
    if (e) e.stopPropagation();
    const list = document.getElementById('custom-select-options-list');
    const arrow = document.getElementById('custom-select-arrow');
    if (!list || !arrow) return;
    const isOpen = list.style.display === 'block';
    
    // Close other dropdowns
    closeAll();
    
    if (!isOpen) {
      list.style.display = 'block';
      arrow.style.transform = 'rotate(180deg)';
    } else {
      list.style.display = 'none';
      arrow.style.transform = 'rotate(0deg)';
    }
  }

  function selectCustomOption(value, labelText) {
    document.getElementById('submission-job-app').value = value;
    document.getElementById('custom-select-label').textContent = labelText;
    
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });
    
    if (value) {
      const selectedOpt = document.querySelector(`.custom-select-option[data-value="${value}"]`);
      if (selectedOpt) {
        selectedOpt.style.background = '#ede9fe';
        selectedOpt.style.color = '#5b21b6';
      }
    }
    
    closeCustomSelect();
  }

  function closeCustomSelect() {
    const list = document.getElementById('custom-select-options-list');
    const arrow = document.getElementById('custom-select-arrow');
    if (list) list.style.display = 'none';
    if (arrow) arrow.style.transform = 'rotate(0deg)';
  }

  function handleFileChange(input) {
    const label = document.getElementById('photo-file-label');
    const preview = document.getElementById('edit-photo-preview');
    const previewWrap = document.getElementById('edit-photo-preview-wrap');
    
    if (input.files && input.files[0]) {
      const file = input.files[0];
      label.textContent = file.name;
      
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        previewWrap.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      label.textContent = 'Pilih Foto atau Ambil Gambar';
      previewWrap.style.display = 'none';
      preview.src = '';
    }
  }

  function openAddModal() {
    document.getElementById('modalMaterialSubmissionLabel').textContent = 'Tambah Pengajuan';
    document.getElementById('formMaterialSubmission').action = '<?= base_url("admin/construction/add-material-submission") ?>';
    document.getElementById('type-bahan').checked = true;
    document.getElementById('submission-title').value = '';
    
    // Reset custom select
    document.getElementById('submission-job-app').value = '';
    document.getElementById('custom-select-label').textContent = '-- Pilih Tukang (Opsional) --';
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });

    document.getElementById('submission-description').value = '';
    document.getElementById('submission-photo').value = '';
    document.getElementById('photo-file-label').textContent = 'Pilih Foto atau Ambil Gambar';
    document.getElementById('edit-photo-preview-wrap').style.display = 'none';
    document.getElementById('edit-photo-preview').src = '';

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialSubmission');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialSubmission').modal('show');
    }
  }

  function openDetailModal(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    // Populate the detail modal fields
    document.getElementById('detail-title').textContent = row.title || 'Pengajuan Tanpa Judul';
    document.getElementById('detail-tukang').textContent = row.tukang_name || 'Admin';
    
    // Type badge
    const typeEl = document.getElementById('detail-type');
    const typeKey = (row.type || 'bahan').toLowerCase();
    const tc = typeConfig[typeKey] || typeConfig.bahan;
    typeEl.className = `type-badge ${typeKey}`;
    typeEl.innerHTML = `<i class="ti ${tc.icon}"></i> ${tc.label}`;

    // Status pill
    const statusEl = document.getElementById('detail-status');
    const statusKey = (row.status || 'pending').toLowerCase();
    const sc = statusConfig[statusKey] || statusConfig.pending;
    statusEl.className = `status-pill ${sc.cls}`;
    statusEl.innerHTML = `<i class="ti ${sc.icon}"></i> ${sc.label}`;

    // Date and time
    document.getElementById('detail-date').innerHTML = `<i class="ti ti-calendar" style="margin-right: 4px;"></i>${fmtDate(row.date)} <i class="ti ti-clock" style="margin-left: 12px; margin-right: 4px;"></i>${fmtTime(row.date)}`;

    // Items list
    const itemsContainer = document.getElementById('detail-items-container');
    itemsContainer.innerHTML = '';
    if (row.items && row.items.length > 0) {
      row.items.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.className = 'detail-item-card';
        itemEl.innerHTML = `<i class="ti ti-cube"></i> <span>${item}</span>`;
        itemsContainer.appendChild(itemEl);
      });
    } else {
      itemsContainer.innerHTML = '<div style="color: #9ca3af; font-style: italic; font-size: 13px; padding: 8px;">Tidak ada deskripsi item</div>';
    }

    // Photo preview
    const photoContainer = document.getElementById('detail-photo-container');
    const photoImg = document.getElementById('detail-photo-img');
    const photoLink = document.getElementById('detail-photo-link');
    if (row.photo_url) {
      photoImg.src = row.photo_url;
      photoLink.href = row.photo_url;
      photoContainer.style.display = 'block';
    } else {
      photoContainer.style.display = 'none';
      photoImg.src = '';
      photoLink.href = '';
    }

    // Admin comment box
    const commentWrap = document.getElementById('detail-comment-wrap');
    const commentText = document.getElementById('detail-comment-text');
    if (row.comment) {
      commentText.textContent = row.comment;
      commentWrap.style.display = 'block';
    } else {
      commentWrap.style.display = 'none';
      commentText.textContent = '';
    }

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialDetail');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialDetail').modal('show');
    }
  }

  function openEditModal(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    document.getElementById('modalMaterialSubmissionLabel').textContent = 'Edit Pengajuan';
    document.getElementById('formMaterialSubmission').action = '<?= base_url("admin/construction/update-material-submission") ?>/' + id;

    document.getElementById('submission-title').value = row.title || '';
    
    // Populate custom select
    document.getElementById('submission-job-app').value = row.job_applications_id || '';
    const label = row.tukang_name ? row.tukang_name : '-- Pilih Tukang (Opsional) --';
    document.getElementById('custom-select-label').textContent = label;
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });
    if (row.job_applications_id) {
      const selectedOpt = document.querySelector(`.custom-select-option[data-value="${row.job_applications_id}"]`);
      if (selectedOpt) {
        selectedOpt.style.background = '#ede9fe';
        selectedOpt.style.color = '#5b21b6';
      }
    }
    
    const type = (row.type || 'bahan').toLowerCase();
    if (type === 'alat') {
      document.getElementById('type-alat').checked = true;
    } else {
      document.getElementById('type-bahan').checked = true;
    }

    document.getElementById('submission-description').value = row.items.join('\n');
    document.getElementById('submission-photo').value = '';

    if (row.photo_url) {
      document.getElementById('photo-file-label').textContent = 'Ganti Foto Bukti';
      document.getElementById('edit-photo-preview-wrap').style.display = 'block';
      document.getElementById('edit-photo-preview').src = row.photo_url;
    } else {
      document.getElementById('photo-file-label').textContent = 'Pilih Foto atau Ambil Gambar';
      document.getElementById('edit-photo-preview-wrap').style.display = 'none';
      document.getElementById('edit-photo-preview').src = '';
    }

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialSubmission');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialSubmission').modal('show');
    }
  }

  function deleteSubmission(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    const typeLabel = (row.type || 'pengajuan').toLowerCase();

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Pengajuan?',
        text: `Apakah Anda yakin ingin menghapus pengajuan ${typeLabel} ini? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fc544b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '<?= base_url("admin/construction/delete-material-submission") ?>/' + id;
        }
      });
    } else {
      if (confirm(`Apakah Anda yakin ingin menghapus pengajuan ${typeLabel} ini?`)) {
        window.location.href = '<?= base_url("admin/construction/delete-material-submission") ?>/' + id;
      }
    }
  }

  renderBadges();
  renderTable();
</script>