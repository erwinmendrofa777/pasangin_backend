
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
                <button type="button" id="btnSaveDraft" class="btn-adm btn-adm-success ladda-button" data-style="zoom-in" onclick="saveAllRab(false)">
                    <i class="fas fa-save"></i> Simpan Draf
                </button>
                <button type="button" class="btn-adm btn-adm-success" data-bs-toggle="modal"
                    data-bs-target="#modalImportRab">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <button type="button" id="btnLockRab" class="btn-adm btn-adm-danger ladda-button" data-style="zoom-in" onclick="saveAllRab(true)">
                    <i class="fas fa-lock"></i> Kunci & Simpan RAB
                </button>
            <?php else: ?>
                <a href="<?= base_url('admin/renovation/cetak-pdf/' . $renovation['id']) ?>" target="_blank"
                    class="btn-adm btn-adm-success">
                    <i class="fas fa-file-pdf"></i> Generate Kontrak
                </a>
                <a href="<?= base_url('admin/renovation/export-rab-excel/' . $renovation['id']) ?>"
                    class="btn-adm btn-adm-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="<?= base_url('admin/renovation/unlock_rab/' . $renovation['id']) ?>"
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
                        <td id="grandTotalRab" colspan="2" class="text-center">Rp
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
                        dikunci pada proyek ini.
                    </div>
                </div>

                <div class="mb-4 text-center">
                    <p style="font-size: 13px; color: #6b7088;">Silakan unduh template Excel resmi di bawah ini sebagai
                        acuan pengisian data :</p>
                    <a href="<?= base_url('admin/renovation/download-rab-template/' . $renovation['id']) ?>"
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


