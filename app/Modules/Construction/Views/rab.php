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

<div class="rab-panel">

    <!-- ── Panel Header ── -->
    <div class="rab-panel-header">
        <h6 class="rab-panel-title">
            <span class="icon-wrap mb-0"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="item-align-center">Manajemen RAB Proyek</span>
        </h6>
        <div class="header-actions d-flex flex-wrap gap-2">
            <?php
            $isLocked = false;
            if (!empty($rab_list)) {
                $isLocked = (int) $rab_list[0]['is_locked'] === 1;
            }
            ?>
            <?php if (!$isLocked): ?>
                <button type="button" id="btnSaveDraft" class="btn-adm btn-adm-success ladda-button" data-style="zoom-in"
                    onclick="saveAllRab(false)">
                    <i class="fas fa-save"></i> Simpan Draf
                </button>
                <button type="button" class="btn-adm btn-adm-success" data-bs-toggle="modal"
                    data-bs-target="#modalImportRab">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <button type="button" id="btnLockRab" class="btn-adm btn-adm-danger ladda-button" data-style="zoom-in"
                    onclick="saveAllRab(true)">
                    <i class="fas fa-lock"></i> Kunci & Simpan RAB
                </button>
            <?php else: ?>
                <a href="<?= base_url('admin/construction/cetak-pdf/' . $construction['id']) ?>" target="_blank"
                    class="btn-adm btn-adm-success">
                    <i class="fas fa-file-pdf"></i> Generate Kontrak
                </a>
                <a href="<?= base_url('admin/construction/export-rab-excel/' . $construction['id']) ?>"
                    class="btn-adm btn-adm-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="<?= base_url('admin/construction/unlock_rab/' . $construction['id']) ?>"
                    class="btn-adm btn-adm-warning ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();">
                    <i class="fas fa-lock-open"></i> Buka Kunci
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Table ── -->
    <div class="rab-table-wrapper">
        <div class="rab-table-scroll">
            <table class="tbl-rab">
                <thead>
                    <tr>
                        <th class="col-roman">Roman</th>
                        <th class="col-group">Grup Utama</th>
                        <th class="col-section">Sub Grup</th>
                        <th class="col-task">Pekerjaan</th>
                        <th class="col-vol">Vol</th>
                        <th class="col-unit">Satuan</th>
                        <th class="col-price">Harga (Rp)</th>
                        <th class="col-total">Total (Rp)</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rabBody">
                    <?php
                    $grandTotalRab = 0;
                    if (!empty($rab_list)):
                        $currentRoman = null;
                        $currentGroupName = '';
                        $currentGroupSum = 0;
                        $lastRoman = '';
                        $lastGroupName = '';
                        $lastSectionGroup = '';
                        foreach ($rab_list as $index => $rab):
                            $subTotal = $rab['volume'] * $rab['current_unit_price'];
                            $grandTotalRab += $subTotal;

                            // Group transition
                            if ($currentRoman !== null && $currentRoman !== $rab['roman_number']) {
                                ?>
                                <?php if (!$isLocked): ?>
                                    <tr class="row-add-row-in-group">
                                        <td colspan="3"></td>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-sm btn-link text-primary p-0"
                                                style="font-size: 11px; text-decoration: none; font-weight: 500;"
                                                onclick="addNewRabRowAt('<?= esc($currentRoman) ?>', '<?= esc($currentGroupName ?? 'PEKERJAAN') ?>', this)">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                                            </button>
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="row-rab-subtotal">
                                    <td colspan="7" class="text-end fw-bold text-uppercase"
                                        style="color: #4a5568; padding-right: 15px !important;">
                                        SUB TOTAL PEKERJAAN <?= esc($currentRoman) ?>
                                    </td>
                                    <td class="font-monospace text-end text-success fw-bold"
                                        style="padding-right: 14px !important; font-size: 12px;">
                                        <?= number_format($currentGroupSum, 2, ',', '.') ?>
                                    </td>
                                    <td></td>
                                </tr>
                                <?php
                                $currentGroupSum = 0;
                            }

                            $currentRoman = $rab['roman_number'];
                            $currentGroupName = $rab['group_name'] ?? 'PEKERJAAN';
                            $currentGroupSum += $subTotal;
                            ?>
                            <tr data-id="<?= $rab['id'] ?>" class="<?= $rab['is_locked'] ? 'row-locked' : '' ?>">
                                <td>
                                    <?php
                                    $romanClass = 'input-rab-roman input-roman';
                                    if ($rab['roman_number'] !== '' && $rab['roman_number'] === $lastRoman) {
                                        $romanClass .= ' section-repeated';
                                    }
                                    ?>
                                    <input type="text" class="<?= $romanClass ?>"
                                        value="<?= esc($rab['roman_number'] ?? 'I') ?>">
                                </td>
                                <td>
                                    <?php
                                    $groupClass = 'input-rab-group-name';
                                    if ($rab['group_name'] !== '' && $rab['group_name'] === $lastGroupName && $rab['roman_number'] === $lastRoman) {
                                        $groupClass .= ' section-repeated';
                                    }
                                    ?>
                                    <input type="text" class="<?= $groupClass ?>"
                                        value="<?= esc($rab['group_name'] ?? 'PEKERJAAN') ?>">
                                </td>
                                <td>
                                    <?php
                                    $sectionClass = 'input-rab-section';
                                    if ($rab['section_group'] !== '' && $rab['section_group'] === $lastSectionGroup && $rab['roman_number'] === $lastRoman) {
                                        $sectionClass .= ' section-repeated';
                                    }
                                    ?>
                                    <input type="text" class="<?= $sectionClass ?>" value="<?= esc($rab['section_group']) ?>"
                                        oninput="calculateGrandTotalRab()">
                                </td>
                                <td>
                                    <input type="text" class="input-rab-task" value="<?= esc($rab['activity_name']) ?>">
                                </td>
                                <td class="">
                                    <input type="number" step="0.01" class="input-rab-vol input-vol"
                                        value="<?= $rab['volume'] ?>" oninput="calculateGrandTotalRab()">
                                </td>
                                <td>
                                    <input type="text" class="input-rab-unit input-unit" value="<?= esc($rab['unit']) ?>">
                                </td>
                                <td>
                                    <?php
                                    $val = (float) $rab['current_unit_price'];
                                    $formattedPrice = number_format($val, 2, ',', '.');
                                    if (floor($val) == $val) {
                                        $formattedPrice = number_format($val, 0, ',', '.');
                                    }
                                    ?>
                                    <input type="text" class="input-rab-price input-price" value="<?= esc($formattedPrice) ?>"
                                        oninput="calculateGrandTotalRab()">
                                </td>
                                <td class="row-rab-total"><?= number_format($subTotal, 2, ',', '.') ?></td>
                                <td>
                                    <?php if ($rab['is_locked'] == 1): ?>
                                        <div style="display:flex;justify-content:center;">
                                            <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="tbl-actions">
                                            <button class="tbl-btn tbl-btn-mat" title="Bahan"
                                                onclick="openRabMaterialModal(<?= $rab['id'] ?>, '<?= esc($rab['activity_name']) ?>')">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                            <button class="tbl-btn tbl-btn-del" title="Hapus"
                                                onclick="deleteRabRow(this, <?= $rab['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            $lastRoman = $rab['roman_number'];
                            $lastGroupName = $rab['group_name'];
                            $lastSectionGroup = $rab['section_group'];
                            ?>
                        <?php endforeach;

                        // Output subtotal for the last group
                        if ($currentRoman !== null) {
                            ?>
                            <?php if (!$isLocked): ?>
                                <tr class="row-add-row-in-group">
                                    <td colspan="3"></td>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0"
                                            style="font-size: 11px; text-decoration: none; font-weight: 500;"
                                            onclick="addNewRabRowAt('<?= esc($currentRoman) ?>', '<?= esc($currentGroupName ?? 'PEKERJAAN') ?>', this)">
                                            <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                                        </button>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                            <tr class="row-rab-subtotal">
                                <td colspan="7" class="text-end fw-bold text-uppercase"
                                    style="color: #4a5568; padding-right: 15px !important;">
                                    SUB TOTAL PEKERJAAN <?= esc($currentRoman) ?>
                                </td>
                                <td class="font-monospace text-end text-success fw-bold"
                                    style="padding-right: 14px !important; font-size: 12px;">
                                    <?= number_format($currentGroupSum, 2, ',', '.') ?>
                                </td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    endif;
                    if (!empty($rab_list) && !$isLocked): ?>
                        <tr class="row-add-new-group">
                            <td colspan="9" class="text-center" style="padding: 12px;">
                                <button type="button" class="btn-adm btn-adm-primary" onclick="addNewRabRow()">
                                    <i class="fas fa-plus-circle"></i> Tambah Kelompok / Baris Baru
                                </button>
                            </td>
                        </tr>
                    <?php elseif (empty($rab_list) && !$isLocked): ?>
                        <tr class="row-add-new-group">
                            <td colspan="9" class="text-center" style="padding: 20px;">
                                <button type="button" class="btn-adm btn-adm-primary" onclick="addNewRabRow()">
                                    <i class="fas fa-plus-circle"></i> Tambah Baris Pertama
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-end pe-3"
                            style="font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                            Estimasi Total RAB
                        </td>
                        <td id="grandTotalRab" colspan=3 class="text-center">Rp
                            <?= isset($grandTotalRab) ? number_format($grandTotalRab, 2, ',', '.') : '0.00' ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div><!-- /rab-panel -->


<!-- ── Materials Modal ── -->
<div class="modal fade modal-rab" id="modalRabMaterials" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalRabMaterialTitle">Opsi Bahan RAB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">

                <!-- Material List -->
                <div id="rabMaterialList" class="mb-4"></div>

                <!-- Add Product -->
                <div class="add-product-card">
                    <label>Tambahkan Opsi Produk</label>
                    <select id="selectProductRab" class="form-select select2" style="width:100%">
                        <option value="">— Pilih Produk —</option>
                        <?php foreach ($all_products as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= esc($p['name']) ?> — Rp <?= number_format($p['price'], 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn-adm btn-adm-primary w-100 mt-3" onclick="submitProductToRabMaterial()">
                        <i class="fas fa-plus"></i> Tambah Bahan
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ── Import RAB Modal ── -->
<div class="modal fade modal-rab" id="modalImportRab" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-excel me-2"></i>Import RAB dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-start gap-2 mb-3" style="font-size: 13px;">
                    <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0 text-warning"></i>
                    <div>
                        <strong>Peringatan!</strong> Mengimpor file baru akan menghapus draf data RAB lama yang belum
                        dikunci pada proyek ini .
                    </div>
                </div>

                <div class="mb-4 text-center">
                    <p style="font-size: 13px; color: #6b7088;">Silakan unduh template Excel resmi di bawah ini sebagai
                        acuan pengisian data :</p>
                    <a href="<?= base_url('admin/construction/download-rab-template/' . $construction['id']) ?>"
                        class="btn-adm btn-adm-warning w-100 py-2 justify-content-center">
                        <i class="fas fa-download"></i> Unduh Template Excel
                    </a>
                </div>

                <div class="add-product-card">
                    <label class="mb-2">Pilih File Spreadsheet (.xlsx, .xls, .csv)</label>
                    <input type="file" id="importExcelFile" class="form-control" accept=".xlsx, .xls, .csv">
                    <button type="button" id="btnSubmitImportExcel"
                        class="btn-adm btn-adm-primary w-100 mt-3 ladda-button" data-style="zoom-in"
                        onclick="submitImportRabExcel()">
                        <i class="fas fa-upload me-1"></i> Mulai Import Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const isLocked = <?= $isLocked ? 'true' : 'false' ?>;
    let activeRabId = 0;
    $(document).ready(function () {
        calculateGrandTotalRab();
        setTimeout(calculateGrandTotalRab, 100);
        setTimeout(calculateGrandTotalRab, 300);
        setTimeout(calculateGrandTotalRab, 600);
        setTimeout(calculateGrandTotalRab, 1000);

        // Panggil ulang setiap kali tab berpindah/ditampilkan
        $(document).on('shown.bs.tab', function () {
            calculateGrandTotalRab();
        });
    });

    /* ── Grand Total & Subtotals ── */
    function calculateGrandTotalRab() {
        let total = 0;
        let lastSection = '';
        let lastRoman = '';
        let lastGroupName = '';

        // Remove existing subtotal rows and in-group add-row buttons before recalculation
        $('#rabBody .row-rab-subtotal, #rabBody .row-add-row-in-group').remove();

        const dataRows = $('#rabBody tr:not(.row-add-new-group)');
        if (dataRows.length === 0) {
            $('#grandTotalRab').text('Rp 0,00');
            return;
        }

        let currentRoman = null;
        let currentGroupName = null;
        let currentGroupSum = 0;

        dataRows.each(function () {
            const row = $(this);
            const romanInput = row.find('.input-rab-roman');
            const roman = (romanInput.val() || 'I').trim().toUpperCase();
            const groupNameInput = row.find('.input-rab-group-name');
            const groupName = (groupNameInput.val() || 'PEKERJAAN').trim();

            const section = (row.find('.input-rab-section').val() || '').trim();
            const vol = parseFloat(row.find('.input-rab-vol').val()) || 0;
            const price = parseRupiahToFloat(row.find('.input-rab-price').val()) || 0;
            const sub = vol * price;

            row.find('.row-rab-total').text(sub.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            total += sub;

            /* Roman grouping visual */
            if (roman !== '' && roman === lastRoman) {
                romanInput.addClass('section-repeated');
            } else {
                romanInput.removeClass('section-repeated');
            }

            /* Group name grouping visual */
            if (groupName !== '' && groupName === lastGroupName && roman === lastRoman) {
                groupNameInput.addClass('section-repeated');
            } else {
                groupNameInput.removeClass('section-repeated');
            }

            /* Section grouping visual */
            const sInput = row.find('.input-rab-section');
            if (section !== '' && section === lastSection && roman === lastRoman) {
                sInput.addClass('section-repeated');
            } else {
                sInput.removeClass('section-repeated');
            }

            lastSection = section;
            lastRoman = roman;
            lastGroupName = groupName;

            if (currentRoman !== null && roman !== currentRoman) {
                // Insert add-row button and subtotal row BEFORE this row (which is the start of the new group)
                if (!isLocked) {
                    const addRowHtml = `<tr class="row-add-row-in-group">
                        <td colspan="3"></td>
                        <td colspan="5">
                            <button type="button" class="btn btn-sm btn-link text-primary p-0" style="font-size: 11px; text-decoration: none; font-weight: 500;" onclick="addNewRabRowAt('${currentRoman}', '${currentGroupName}', this)">
                                <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                            </button>
                        </td>
                        <td></td>
                    </tr>`;
                    row.before(addRowHtml);
                }
                const subtotalHtml = `<tr class="row-rab-subtotal">
                    <td colspan="7" class="text-end fw-bold text-uppercase" style="color: #4a5568; padding-right: 15px !important;">
                        SUB TOTAL PEKERJAAN ${currentRoman}
                    </td>
                    <td class="font-monospace text-end text-success fw-bold" style="padding-right: 14px !important; font-size: 12px;">
                        ${currentGroupSum.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </td>
                    <td></td>
                </tr>`;
                row.before(subtotalHtml);
                currentGroupSum = 0;
            }

            currentRoman = roman;
            currentGroupName = groupName;
            currentGroupSum += sub;
        });

        // Insert the last subtotal row at the end of the table body
        if (currentRoman !== null) {
            if (!isLocked) {
                const addRowHtml = `<tr class="row-add-row-in-group">
                    <td colspan="3"></td>
                    <td colspan="5">
                        <button type="button" class="btn btn-sm btn-link text-primary p-0" style="font-size: 11px; text-decoration: none; font-weight: 500;" onclick="addNewRabRowAt('${currentRoman}', '${currentGroupName}', this)">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </td>
                    <td></td>
                </tr>`;
                $('#rabBody').append(addRowHtml);
            }
            const subtotalHtml = `<tr class="row-rab-subtotal">
                <td colspan="7" class="text-end fw-bold text-uppercase" style="color: #4a5568; padding-right: 15px !important;">
                    SUB TOTAL PEKERJAAN ${currentRoman}
                </td>
                <td class="font-monospace text-end text-success fw-bold" style="padding-right: 14px !important; font-size: 12px;">
                    ${currentGroupSum.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
                <td></td>
            </tr>`;
            $('#rabBody').append(subtotalHtml);
        }

        // Move "Tambah Kelompok Baru" row to the bottom
        if ($('.row-add-new-group').length > 0) {
            $('#rabBody').append($('.row-add-new-group'));
        }

        $('#grandTotalRab').text('Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // Realtime perhitungan grand total
    $(document).on('input keyup change', '.input-rab-vol, .input-rab-price, .input-rab-section, .input-rab-roman, .input-rab-group-name', function () {
        calculateGrandTotalRab();
    });

    // Make text visible on focus, and restore transparency on blur
    $(document).on('focus', '.input-rab-roman, .input-rab-group-name, .input-rab-section', function () {
        $(this).removeClass('section-repeated');
    });

    $(document).on('blur', '.input-rab-roman, .input-rab-group-name, .input-rab-section', function () {
        calculateGrandTotalRab();
    });

    // Format input rupiah real-time
    $(document).on('input', '.input-price', function () {
        let cursorPosition = this.selectionStart;
        let originalLength = this.value.length;

        let formatted = formatRupiah(this.value);
        this.value = formatted;

        let newLength = this.value.length;
        cursorPosition = cursorPosition + (newLength - originalLength);
        this.setSelectionRange(cursorPosition, cursorPosition);
    });

    function formatRupiah(value) {
        let clean = value.replace(/[^0-9,]/g, '');
        let parts = clean.split(',');
        let numberPart = parts[0];
        let decimalPart = parts.length > 1 ? ',' + parts[1] : '';
        let formattedNumber = numberPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return formattedNumber + decimalPart;
    }

    function parseRupiahToFloat(valStr) {
        if (!valStr) return 0;
        let clean = valStr.replace(/\./g, '').replace(/,/g, '.');
        return parseFloat(clean) || 0;
    }

    /* ── Add New Row ── */
    function addNewRabRow() {
        const newRow = `<tr data-id="0">
            <td><input type="text" class="input-rab-roman input-roman" value="I"></td>
            <td><input type="text" class="input-rab-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-rab-section" placeholder="Sub grup..." oninput="calculateGrandTotalRab()"></td>
            <td><input type="text" class="input-rab-task" placeholder="Nama pekerjaan..."></td>
            <td><input type="number" class="input-rab-vol input-vol" value="1" step="0.01" oninput="calculateGrandTotalRab()"></td>
            <td><input type="text" class="input-rab-unit input-unit" value="unit"></td>
            <td><input type="text" class="input-rab-price input-price" value="0" oninput="calculateGrandTotalRab()"></td>
            <td class="row-rab-total">0</td>
            <td>
                <div class="tbl-actions">
                    <button class="tbl-btn tbl-btn-del" title="Hapus"
                        onclick="$(this).closest('tr').remove(); calculateGrandTotalRab();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
        $('#rabBody').append(newRow);
        calculateGrandTotalRab();
    }

    function addNewRabRowAt(roman, groupName, buttonEl) {
        const subtotalRow = $(buttonEl).closest('tr');
        const newRow = `<tr data-id="0">
            <td><input type="text" class="input-rab-roman input-roman" value="${roman}"></td>
            <td><input type="text" class="input-rab-group-name" value="${groupName}"></td>
            <td><input type="text" class="input-rab-section" placeholder="Sub grup..." oninput="calculateGrandTotalRab()"></td>
            <td><input type="text" class="input-rab-task" placeholder="Nama pekerjaan..."></td>
            <td><input type="number" class="input-rab-vol input-vol" value="1" step="0.01" oninput="calculateGrandTotalRab()"></td>
            <td><input type="text" class="input-rab-unit input-unit" value="unit"></td>
            <td><input type="text" class="input-rab-price input-price" value="0" oninput="calculateGrandTotalRab()"></td>
            <td class="row-rab-total">0</td>
            <td>
                <div class="tbl-actions">
                    <button class="tbl-btn tbl-btn-del" title="Hapus"
                        onclick="$(this).closest('tr').remove(); calculateGrandTotalRab();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
        subtotalRow.before(newRow);
        calculateGrandTotalRab();
    }

    /* ── Save & Lock All Rows ── */
    function saveAllRab(shouldLock) {
        if (shouldLock) {
            if (!confirm('Kunci RAB? Data tidak bisa diubah lagi dan total RAB akan dimasukkan ke request proyek!')) {
                return;
            }
        }

        const btnId = shouldLock ? '#btnLockRab' : '#btnSaveDraft';
        const l = Ladda.create(document.querySelector(btnId));
        l.start();

        const rowsData = [];
        $('#rabBody tr:not(.row-rab-subtotal):not(.row-add-new-group):not(.row-add-row-in-group)').each(function () {
            const row = $(this);
            if (!row.hasClass('row-locked')) {
                rowsData.push({
                    id: row.attr('data-id'),
                    roman_number: row.find('.input-rab-roman').val(),
                    group_name: row.find('.input-rab-group-name').val(),
                    section_group: row.find('.input-rab-section').val(),
                    task_name: row.find('.input-rab-task').val(),
                    volume: row.find('.input-rab-vol').val(),
                    unit: row.find('.input-rab-unit').val(),
                    price: parseRupiahToFloat(row.find('.input-rab-price').val())
                });
            }
        });

        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rows: rowsData,
            lock: shouldLock
        };

        $.post('<?= base_url('admin/construction/save_all_rab/' . $construction['id']) ?>', data, function (res) {
            l.stop();
            if (res.status) {
                alert('✅ ' + res.message);
                window.location.reload();
            } else {
                alert('❌ ' + res.message);
            }
        }).fail(function () {
            l.stop();
            alert('Gagal menyimpan data RAB!');
        });
    }

    /* ── Delete Row ── */
    function deleteRabRow(btn, id) {
        if (!confirm('Hapus baris ini?')) return;
        $.get('<?= base_url('admin/construction/delete_rab_row') ?>/' + id, function (res) {
            if (res.status) {
                $(btn).closest('tr').remove();
                calculateGrandTotalRab();
            } else {
                alert(res.message);
            }
        });
    }

    /* ── Materials Modal ── */
    function openRabMaterialModal(rabId, activityName) {
        activeRabId = rabId;
        $('#modalRabMaterialTitle').text('Bahan: ' + activityName);
        loadRabMaterials(rabId);
        const modal = new bootstrap.Modal(document.getElementById('modalRabMaterials'));
        modal.show();
    }

    function loadRabMaterials(rabId) {
        $.get('<?= base_url('admin/construction/get_rab_materials') ?>/' + rabId, function (data) {
            if (!Array.isArray(data) || data.length === 0) {
                $('#rabMaterialList').html(
                    `<div class="empty-materials">
                        <i class="fas fa-box-open"></i>
                        Belum ada bahan yang dipilih
                    </div>`
                );
                return;
            }
            let html = '';
            data.forEach(function (m) {
                html += `<div class="material-item">
                    <div>
                        <div class="mat-name">${m.material_name}</div>
                        <div class="mat-price">Rp ${Number(m.price).toLocaleString('id-ID')}</div>
                    </div>
                    <button class="tbl-btn tbl-btn-del" title="Hapus" onclick="deleteRabMaterial(${m.id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`;
            });
            $('#rabMaterialList').html(html);
        });
    }

    function submitProductToRabMaterial() {
        const productId = $('#selectProductRab').val();
        if (!productId) {
            alert('Pilih produk terlebih dahulu!');
            return;
        }
        $.post('<?= base_url('admin/construction/add_rab_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rab_id: activeRabId,
            product_id: productId
        }, function (res) {
            if (res.status) {
                loadRabMaterials(activeRabId);
            } else {
                alert('❌ ' + res.message);
            }
        }).fail(function () {
            alert('Gagal menambahkan bahan!');
        });
    }

    function deleteRabMaterial(id) {
        if (!confirm('Hapus bahan ini?')) return;
        $.get('<?= base_url('admin/construction/delete_rab_material') ?>/' + id, function (res) {
            if (res.status) {
                loadRabMaterials(activeRabId);
            } else {
                alert(res.message);
            }
        });
    }

    function submitImportRabExcel() {
        const fileInput = document.getElementById('importExcelFile');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan pilih file Excel/Spreadsheet terlebih dahulu  !');
            return;
        }

        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('excel_file', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        const btn = document.getElementById('btnSubmitImportExcel');
        const l = Ladda.create(btn);
        l.start();

        $.ajax({
            url: '<?= base_url('admin/construction/import-rab-excel/' . $construction['id']) ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                l.stop();
                if (res.status) {
                    alert('✅ ' + res.message);
                    window.location.reload();
                } else {
                    alert('❌ ' + res.message);
                }
            },
            error: function (xhr) {
                l.stop();
                let errorMsg = 'Gagal melakukan import data  !';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert('❌ ' + errorMsg);
            }
        });
    }
</script>