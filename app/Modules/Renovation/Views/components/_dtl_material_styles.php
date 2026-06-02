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
    background: #2563eb;
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
    color: #2563eb;
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
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
    border-color: #93c5fd;
    color: #2563eb;
  }

  .ms-root .ms-filter-dd-btn:focus {
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
    background: #f8faff;
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
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
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
    background: #eff6ff;
    color: #1e3a8a;
    border: 1px solid #bfdbfe;
  }

  .ms-root .item-chip .ti {
    font-size: 11px;
    color: #3b82f6;
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
    background: #dbeafe;
    color: #2563eb;
  }

  .ms-root .dd-item.bahan-item {
    color: #b45309;
  }

  .ms-root .dd-item.bahan-item:hover {
    background: #fffbeb;
    color: #b45309;
  }

  .ms-root .dd-item.alat-item {
    color: #1e40af;
  }

  .ms-root .dd-item.alat-item:hover {
    background: #eff6ff;
    color: #1e40af;
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
    background: #dbeafe;
    color: #2563eb;
    border-color: #bfdbfe;
  }

  .ms-root .pag-btn.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
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
    border: 1px solid #2563eb;
    border-radius: 20px;
    cursor: pointer;
    background: #2563eb;
    color: #fff;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    outline: none;
  }

  .ms-root .ms-add-btn:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
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
    background: #2563eb;
    border: 1px solid #2563eb;
  }

  .ms-root .btn-action.detail:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
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
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
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
    border-color: #3b82f6;
    background: #f8fafc;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
  }

  .ms-root #type-alat:checked+.ms-type-card.alat i {
    color: #2563eb;
  }

  .ms-root #type-alat:checked+.ms-type-card.alat .title {
    color: #1e40af;
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
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
    color: #2563eb;
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
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    outline: none;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  }

  .ms-root .ms-btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
  }

  .ms-root .ms-select-container:focus-within {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
  }

  .ms-root .ms-select-custom:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
  }

  .ms-root .custom-select-trigger:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
  }

  .ms-root .custom-select-option:hover {
    background: #eff6ff !important;
    color: #2563eb !important;
  }

  .ms-root .custom-select-option:hover .ti {
    color: #2563eb !important;
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
    color: #2563eb;
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
    color: #3b82f6;
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
