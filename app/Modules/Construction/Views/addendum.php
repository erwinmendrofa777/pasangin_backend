<div class="addendum-panel">

    <!-- ── Panel Header ── -->
    <div class="addendum-panel-header">
        <h6 class="addendum-panel-title">
            <span class="icon-wrap mb-0"><i class="fas fa-file-contract"></i></span>
            <span>Manajemen Addendum Proyek</span>
        </h6>
        <div class="header-actions d-flex flex-wrap gap-2">
            <?php
            $isAddLocked = false;
            if (!empty($addendum_list)) {
                $isAddLocked = (int) $addendum_list[0]['is_locked'] === 1;
            }
            ?>
            <?php if (!$isAddLocked): ?>
                <button type="button" id="btnSaveAddDraft" class="btn-adm btn-adm-success ladda-button" data-style="zoom-in" onclick="saveAllAddendum(false)">
                    <i class="fas fa-save"></i> Simpan Draf
                </button>
                <button type="button" id="btnLockAddendum" class="btn-adm btn-adm-danger ladda-button" data-style="zoom-in" onclick="saveAllAddendum(true)">
                    <i class="fas fa-lock"></i> Kunci &amp; Simpan Addendum
                </button>
            <?php else: ?>
                <a href="<?= base_url('admin/construction/unlock_addendum/' . $construction['id']) ?>"
                    class="btn-adm btn-adm-warning ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();">
                    <i class="fas fa-lock-open"></i> Buka Kunci
                </a>
            <?php endif; ?>
            <?php if (!$isAddLocked): ?>
                <button class="btn-adm btn-adm-primary" onclick="addNewAddendumRow()">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Table ── -->
    <div class="addendum-table-wrapper">
        <div class="addendum-table-scroll">
            <table class="tbl-addendum">
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
                <tbody id="addendumBody">
                    <?php
                    $grandTotalAddendum = 0;
                    if (!empty($addendum_list)):
                        foreach ($addendum_list as $add):
                            $subTotalAdd = $add['volume'] * $add['current_unit_price'];
                            $grandTotalAddendum += $subTotalAdd;
                            ?>
                            <tr data-id="<?= $add['id'] ?>" class="<?= $add['is_locked'] ? 'row-locked' : '' ?>">
                                <td>
                                    <input type="text" class="input-add-roman input-roman"
                                        value="<?= esc($add['roman_number'] ?? 'I') ?>">
                                </td>
                                <td>
                                    <input type="text" class="input-add-group-name"
                                        value="<?= esc($add['group_name'] ?? 'PEKERJAAN') ?>">
                                </td>
                                <td>
                                    <input type="text" class="input-add-section" value="<?= esc($add['section_group']) ?>"
                                        oninput="calculateGrandTotalAddendum()">
                                </td>
                                <td>
                                    <input type="text" class="input-add-task" value="<?= esc($add['activity_name']) ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="input-add-vol input-vol"
                                        value="<?= $add['volume'] ?>" oninput="calculateGrandTotalAddendum()">
                                </td>
                                <td>
                                    <input type="text" class="input-add-unit input-unit" value="<?= esc($add['unit']) ?>">
                                </td>
                                <td>
                                    <input type="number" class="input-add-price input-price"
                                        value="<?= (int) $add['current_unit_price'] ?>" oninput="calculateGrandTotalAddendum()">
                                </td>
                                <td class="row-add-total"><?= number_format($subTotalAdd, 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($add['is_locked'] == 1): ?>
                                        <div style="display:flex;justify-content:center;">
                                            <span class="lock-badge"><i class="fas fa-lock"></i></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="tbl-actions">
                                            <button class="tbl-btn tbl-btn-mat" title="Bahan"
                                                onclick="openAddendumMaterialModal(<?= $add['id'] ?>, '<?= esc($add['activity_name']) ?>')">
                                                <i class="fas fa-boxes"></i>
                                            </button>
                                            <button class="tbl-btn tbl-btn-del" title="Hapus"
                                                onclick="deleteAddendumRow(this, <?= $add['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach;
                    endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end pe-3"
                            style="color:#6b7088;font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
                            Estimasi Total Addendum
                        </td>
                        <td colspan="2" id="grandTotalAddendum">Rp
                            <?= isset($grandTotalAddendum) ? number_format($grandTotalAddendum, 0, ',', '.') : '0' ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div><!-- /addendum-panel -->


<!-- ── Materials Modal ── -->
<div class="modal fade modal-addendum" id="modalAddendumMaterials" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalAddendumMaterialTitle">Opsi Bahan Addendum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">

                <!-- Material List -->
                <div id="addendumMaterialList" class="mb-4"></div>

                <!-- Add Product -->
                <div class="add-product-card">
                    <label>Tambahkan Opsi Produk</label>
                    <select id="selectProductAddendum" class="form-select select2" style="width:100%">
                        <option value="">— Pilih Produk —</option>
                        <?php foreach ($all_products as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= esc($p['name']) ?> — Rp <?= number_format($p['price']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn-adm btn-adm-primary w-100 mt-3" onclick="submitProductToAddendumMaterial()">
                        <i class="fas fa-plus"></i> Tambah Bahan
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>