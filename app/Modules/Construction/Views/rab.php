<div class="card header-card" style="margin-bottom: 16px;">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Manajemen RAB Proyek</h5>
                        <p class="text-muted mb-0 small">Kelola data rancangan anggaran biaya (RAB) untuk proyek
                            konstruksi ini.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                    <?php
                    $isLocked = false;
                    if (!empty($rab_list)) {
                        $isLocked = (int) $rab_list[0]['is_locked'] === 1;
                    }
                    ?>
                    <?php if (!$isLocked): ?>
                        <button type="button" id="btnSaveDraft" class="btn-adm btn-adm-success ladda-button"
                            data-style="zoom-in" onclick="saveAllRab(false)">
                            <i class="fas fa-save"></i> Simpan Draf
                        </button>
                        <button type="button" class="btn-adm btn-adm-success" data-bs-toggle="modal"
                            data-bs-target="#modalImportRab">
                            <i class="fas fa-file-import"></i> Import Excel
                        </button>
                        <button type="button" id="btnLockRab" class="btn-adm btn-adm-danger ladda-button"
                            data-style="zoom-in" onclick="saveAllRab(true)">
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
                            class="btn-adm btn-adm-warning ladda-button" data-style="zoom-in"
                            onclick="Ladda.create(this).start();">
                            <i class="fas fa-lock-open"></i> Buka Kunci
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rab-panel">
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
                                <tr class="row-rab-subtotal">
                                    <td colspan="4" class="text-center" style="padding-left: 10px;">
                                        <?php if (!$isLocked): ?>
                                            <button type="button" class="btn btn-sm btn-link text-primary p-0"
                                                style="font-size: 11px; text-decoration: none; font-weight: 500;"
                                                onclick="addNewRabRowAt('<?= esc($currentRoman) ?>', '<?= esc($currentGroupName ?? 'PEKERJAAN') ?>', this)">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td colspan="3" class="text-end fw-bold text-uppercase"
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
                                    <input type="text" class="input-rab-task input-rab-task-picker"
                                        value="<?= esc($rab['activity_name']) ?>" readonly
                                        data-ahsp-id="<?= esc($rab['ahsp_id']) ?>" placeholder="Pilih Pekerjaan (AHSP)..."
                                        data-bs-toggle="modal" data-bs-target="#modalAhspPicker">
                                </td>
                                <td class="">
                                    <input type="number" step="0.01" class="input-rab-vol input-vol"
                                        value="<?= $rab['volume'] ?>" oninput="calculateGrandTotalRab()">
                                </td>
                                <td>
                                    <select class="input-rab-unit input-unit" <?= $isLocked ? 'disabled' : '' ?>>
                                        <option value="">— Pilih —</option>
                                        <?php
                                        $found = false;
                                        if (!empty($satuan_options)) {
                                            foreach ($satuan_options as $s) {
                                                $isSelected = strcasecmp($s['nama_satuan'], $rab['unit']) === 0;
                                                if ($isSelected) {
                                                    $found = true;
                                                }
                                                echo '<option value="' . esc($s['nama_satuan']) . '"' . ($isSelected ? ' selected' : '') . '>' . esc($s['nama_satuan']) . '</option>';
                                            }
                                        }
                                        if (!$found && !empty($rab['unit'])) {
                                            echo '<option value="' . esc($rab['unit']) . '" selected>' . esc($rab['unit']) . '</option>';
                                        }
                                        ?>
                                    </select>
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
                                        readonly>
                                </td>
                                <td class="row-rab-total"><?= number_format($subTotal, 2, ',', '.') ?></td>
                                <td>
                                    <?php if ($rab['is_locked'] == 1): ?>
                                        <div class="tbl-actions" style="justify-content:center;">
                                            <button class="tbl-btn tbl-btn-detail" title="Detail AHSP"
                                                onclick="openAhspDetailModal('<?= esc($rab['ahsp_id']) ?>', '<?= esc(addslashes($rab['activity_name'])) ?>', <?= $rab['id'] ?>, <?= (float) $rab['volume'] ?>)">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="tbl-actions">
                                            <button class="tbl-btn tbl-btn-detail" title="Detail AHSP"
                                                onclick="openAhspDetailModal('<?= esc($rab['ahsp_id']) ?>', '<?= esc(addslashes($rab['activity_name'])) ?>', <?= $rab['id'] ?>, <?= (float) $rab['volume'] ?>)">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
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
                            <tr class="row-rab-subtotal">
                                <td colspan="4" class="text-center" style="padding-left: 10px;">
                                    <?php if (!$isLocked): ?>
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0"
                                            style="font-size: 11px; text-decoration: none; font-weight: 500;"
                                            onclick="addNewRabRowAt('<?= esc($currentRoman) ?>', '<?= esc($currentGroupName ?? 'PEKERJAAN') ?>', this)">
                                            <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td colspan="3" class="text-end fw-bold text-uppercase"
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
                    endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 10px;">
                            <?php if (!$isLocked): ?>
                                <button type="button" class="btn btn-sm btn-link text-primary p-0"
                                    style="font-size: 11px; text-decoration: none; font-weight: 500;"
                                    onclick="addNewRabRow()">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    <?= empty($rab_list) ? 'Tambah Baris Pertama' : 'Tambah Kelompok' ?>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td colspan="3" class="text-end pe-3"
                            style="font-size:11px;letter-spacing:.06em;text-transform:uppercase; vertical-align: middle;">
                            Estimasi Total RAB
                        </td>
                        <td id="grandTotalRab" colspan="2" class="text-center" style="vertical-align: middle;">Rp
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header"
                style="background: var(--palette-primary); color: white; border-top-left-radius: 16px; border-top-right-radius: 16px; padding: 16px 20px;">
                <h5 class="modal-title text-white" id="modalRabMaterialTitle"><i class="fas fa-boxes me-2"></i> Pilihan
                    Produk untuk Bahan Pekerjaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 600px; overflow-y: auto; padding: 20px;">
                <div class="alert alert-info d-flex align-items-start gap-2 mb-4" style="font-size: 13px;">
                    <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                    <div>
                        <strong>Petunjuk:</strong> Pekerjaan ini memiliki beberapa kebutuhan bahan/material. Silakan
                        tentukan produk spesifik yang akan digunakan untuk masing-masing kebutuhan bahan di bawah ini.
                        Harga satuan pekerjaan akan dihitung ulang secara otomatis setelah Anda memilih produk.
                    </div>
                </div>

                <!-- Accordion container untuk Bahan Kebutuhan -->
                <div class="accordion mt-3" id="accordionRabMaterials">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── AHSP Detail Modal ── -->
<div class="modal fade modal-rab" id="modalAhspDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header"
                style="background: var(--palette-primary); color: white; border-top-left-radius: 16px; border-top-right-radius: 16px; padding: 16px 20px;">
                <h5 class="modal-title text-white" id="modalAhspDetailTitle"><i class="fas fa-clipboard-list me-2"></i>
                    Detail AHSP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="modalAhspDetailBody"
                style="max-height: 70vh; overflow-y: auto; padding: 24px; background: #f8fafc;">
                <!-- Populated dynamically via JS -->
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #94a3b8;"></i>
                    <div class="mt-2" style="font-size: 13px;">Memuat data AHSP...</div>
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

<!-- ── Modal Picker AHSP ── -->
<div class="modal fade modal-rab" id="modalAhspPicker" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header"
                style="background: var(--palette-primary); color: white; border-top-left-radius: 16px; border-top-right-radius: 16px; padding: 16px 20px;">
                <h5 class="modal-title text-white"><i class="fas fa-clipboard-list me-2"></i> Pilih Pekerjaan (AHSP)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="max-height: 550px; overflow-y: auto; padding: 20px;">
                <!-- Pencarian Cepat -->
                <div class="mb-3">
                    <div class="search-wrapper w-100" style="position: relative;">
                        <input type="text" id="searchAhspPicker" class="form-control search-input"
                            placeholder="Cari kode atau uraian pekerjaan AHSP..."
                            style="padding-left: 40px; height: 45px; border-radius: 10px; width: 100%;">
                        <i class="fas fa-search search-icon"
                            style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    </div>
                </div>

                <!-- Tabel List AHSP -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tableAhspPicker" style="font-size: 13px;">
                        <thead>
                            <tr style="background: #f4f6f9; color: #4a5568;">
                                <th style="width: 50px; padding: 12px;" class="text-center">Detail</th>
                                <th style="width: 120px; padding: 12px;">Kode</th>
                                <th style="padding: 12px;">Uraian Pekerjaan</th>
                                <th class="text-center" style="width: 100px; padding: 12px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ahsp_list)): ?>
                                <?php foreach ($ahsp_list as $a): ?>
                                    <tr class="ahsp-picker-row" data-id="<?= $a['id'] ?>" data-kode="<?= esc($a['kode']) ?>"
                                        data-uraian="<?= esc($a['uraian']) ?>">
                                        <td class="text-center toggle-details"
                                            style="padding: 12px; color: #64748b; font-size: 14px; cursor: pointer;">
                                            <i class="fas fa-chevron-right toggle-icon"
                                                style="transition: transform 0.25s ease;"></i>
                                        </td>
                                        <td class="fw-bold text-primary select-row-action"
                                            style="padding: 12px; cursor: pointer;"><?= esc($a['kode']) ?></td>
                                        <td class="fw-bold text-dark uraian-cell select-row-action"
                                            style="padding: 12px; cursor: pointer;">
                                            <?= esc($a['uraian']) ?>
                                        </td>
                                        <td class="text-center" style="padding: 12px;">
                                            <button type="button" class="btn btn-sm btn-primary btn-select-ahsp"
                                                style="font-size: 11px; border-radius: 8px; padding: 6px 12px;">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Detail Row dengan Accordion -->
                                    <tr class="ahsp-detail-row" id="detail-ahsp-<?= $a['id'] ?>"
                                        style="display: none; background: #f8fafc;">
                                        <td></td>
                                        <td colspan="3" style="padding: 15px 20px;">
                                            <div class="accordion" id="accordionAhsp-<?= $a['id'] ?>">

                                                <!-- Accordion Bahan -->
                                                <div class="accordion-item"
                                                    style="border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 8px; overflow: hidden; background: #fff;">
                                                    <h2 class="accordion-header" id="headingBahan-<?= $a['id'] ?>">
                                                        <button class="accordion-button collapsed py-2 px-3 fw-bold"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseBahan-<?= $a['id'] ?>"
                                                            aria-expanded="false" aria-controls="collapseBahan-<?= $a['id'] ?>"
                                                            style="font-size: 11px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                                            <i class="fas fa-boxes me-2 text-primary"></i> Rincian Bahan /
                                                            Material (<?= count($a['bahan'] ?? []) ?>)
                                                        </button>
                                                    </h2>
                                                    <div id="collapseBahan-<?= $a['id'] ?>" class="accordion-collapse collapse"
                                                        aria-labelledby="headingBahan-<?= $a['id'] ?>"
                                                        data-bs-parent="#accordionAhsp-<?= $a['id'] ?>">
                                                        <div class="accordion-body p-2" style="background: #fff;">
                                                            <?php if (!empty($a['bahan'])): ?>
                                                                <table class="table table-bordered table-sm m-0"
                                                                    style="font-size: 10px;">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th style="width: 50px;" class="text-center">No</th>
                                                                            <th>Nama Bahan</th>
                                                                            <th style="width: 100px;" class="text-center">Satuan
                                                                            </th>
                                                                            <th style="width: 100px;" class="text-end">Koefisien
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $noB = 1;
                                                                        foreach ($a['bahan'] as $b): ?>
                                                                            <tr>
                                                                                <td class="text-center"><?= $noB++ ?></td>
                                                                                <td class="fw-medium"><?= esc($b['uraian']) ?></td>
                                                                                <td class="text-center"><span
                                                                                        class="badge bg-light text-dark"><?= esc($b['satuan']) ?></span>
                                                                                </td>
                                                                                <td
                                                                                    class="text-end font-monospace fw-bold text-primary">
                                                                                    <?= number_format($b['koefisien'], 4, ',', '.') ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php else: ?>
                                                                <div class="text-center text-muted small p-2">Tidak membutuhkan
                                                                    bahan/material.</div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Accordion Tenaga Kerja -->
                                                <div class="accordion-item"
                                                    style="border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; background: #fff;">
                                                    <h2 class="accordion-header" id="headingTenaga-<?= $a['id'] ?>">
                                                        <button class="accordion-button collapsed py-2 px-3 fw-bold"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTenaga-<?= $a['id'] ?>"
                                                            aria-expanded="false" aria-controls="collapseTenaga-<?= $a['id'] ?>"
                                                            style="font-size: 11px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                                            <i class="fas fa-users me-2 text-primary"></i> Rincian Tenaga Kerja
                                                            (<?= count($a['tenaga_kerja'] ?? []) ?>)
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTenaga-<?= $a['id'] ?>" class="accordion-collapse collapse"
                                                        aria-labelledby="headingTenaga-<?= $a['id'] ?>"
                                                        data-bs-parent="#accordionAhsp-<?= $a['id'] ?>">
                                                        <div class="accordion-body p-2" style="background: #fff;">
                                                            <?php if (!empty($a['tenaga_kerja'])): ?>
                                                                <table class="table table-bordered table-sm m-0"
                                                                    style="font-size: 10px;">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th style="width: 50px;" class="text-center">No</th>
                                                                            <th>Klasifikasi Pekerja</th>
                                                                            <th style="width: 80px;" class="text-center">Satuan</th>
                                                                            <th style="width: 80px;" class="text-end">Koefisien</th>
                                                                            <th style="width: 120px;" class="text-end">Harga Satuan
                                                                            </th>
                                                                            <th style="width: 120px;" class="text-end">Jumlah</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $noT = 1;
                                                                        foreach ($a['tenaga_kerja'] as $t):
                                                                            $subTotalT = $t['koefisien'] * $t['harga_satuan'];
                                                                            ?>
                                                                            <tr>
                                                                                <td class="text-center"><?= $noT++ ?></td>
                                                                                <td class="fw-medium"><?= esc($t['uraian']) ?></td>
                                                                                <td class="text-center"><span
                                                                                        class="badge bg-light text-dark"><?= esc($t['satuan']) ?></span>
                                                                                </td>
                                                                                <td class="text-end font-monospace">
                                                                                    <?= number_format($t['koefisien'], 4, ',', '.') ?>
                                                                                </td>
                                                                                <td class="text-end font-monospace">Rp
                                                                                    <?= number_format($t['harga_satuan'], 0, ',', '.') ?>
                                                                                </td>
                                                                                <td class="text-end font-monospace fw-bold text-dark">Rp
                                                                                    <?= number_format($subTotalT, 0, ',', '.') ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php else: ?>
                                                                <div class="text-center text-muted small p-2">Tidak membutuhkan
                                                                    tenaga kerja.</div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 20px;">Tidak ada data
                                        AHSP.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3"
                    style="border-top: 1px solid #f1f5f9;">
                    <div class="text-muted" id="paginationInfo" style="font-size: 12px; font-weight: 500;">Menampilkan 0
                        - 0 dari 0 data</div>
                    <ul class="pagination pagination-sm m-0" id="pickerPaginationList" style="gap: 3px;">
                        <!-- dynamic pagination items -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>