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


<script>
    let activeAddendumId = 0;

    $(document).ready(function () {
        calculateGrandTotalAddendum();
    });

    /* ── Grand Total ── */
    function calculateGrandTotalAddendum() {
        let total = 0;
        let lastSection = '';

        $('#addendumBody .input-add-vol').each(function () {
            const row = $(this).closest('tr');
            const section = row.find('.input-add-section').val() || '';
            const vol = parseFloat($(this).val()) || 0;
            const price = parseFloat(row.find('.input-add-price').val()) || 0;
            const sub = vol * price;

            row.find('.row-add-total').text(sub.toLocaleString('id-ID'));
            total += sub;

            /* Section grouping visual */
            const sInput = row.find('.input-add-section');
            if (section !== '' && section === lastSection) {
                sInput.css({ color: 'transparent' });
            } else {
                sInput.css({ color: '#1a1d2e', fontWeight: '600' });
            }
            lastSection = section;
        });

        $('#grandTotalAddendum').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Realtime perhitungan grand total
    $(document).on('input keyup change', '.input-add-vol, .input-add-price, .input-add-section', function () {
        calculateGrandTotalAddendum();
    });

    /* ── Add New Row ── */
    function addNewAddendumRow() {
        const newRow = `<tr data-id="0">
            <td><input type="text" class="input-add-roman input-roman" value="I"></td>
            <td><input type="text" class="input-add-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-add-section" placeholder="Sub grup..." oninput="calculateGrandTotalAddendum()"></td>
            <td><input type="text" class="input-add-task" placeholder="Nama pekerjaan..."></td>
            <td><input type="number" class="input-add-vol input-vol" value="1" step="0.01" oninput="calculateGrandTotalAddendum()"></td>
            <td><input type="text" class="input-add-unit input-unit" value="unit"></td>
            <td><input type="number" class="input-add-price input-price" value="0" oninput="calculateGrandTotalAddendum()"></td>
            <td class="row-add-total">0</td>
            <td>
                <div class="tbl-actions">
                    <button class="tbl-btn tbl-btn-del" title="Hapus"
                        onclick="$(this).closest('tr').remove(); calculateGrandTotalAddendum();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
        $('#addendumBody').append(newRow);
        calculateGrandTotalAddendum();
    }

    /* ── Save & Lock All Addendum Rows ── */
    function saveAllAddendum(shouldLock) {
        if (shouldLock) {
            if (!confirm('Kunci Addendum? Data tidak bisa diubah lagi!\nTotal addendum akan ditambahkan ke RAB total proyek.')) {
                return;
            }
        }

        const btnId = shouldLock ? '#btnLockAddendum' : '#btnSaveAddDraft';
        const l = Ladda.create(document.querySelector(btnId));
        l.start();

        const rowsData = [];
        $('#addendumBody tr').each(function () {
            const row = $(this);
            if (!row.hasClass('row-locked')) {
                rowsData.push({
                    id: row.attr('data-id'),
                    roman_number: row.find('.input-add-roman').val(),
                    group_name: row.find('.input-add-group-name').val(),
                    section_group: row.find('.input-add-section').val(),
                    task_name: row.find('.input-add-task').val(),
                    volume: row.find('.input-add-vol').val(),
                    unit: row.find('.input-add-unit').val(),
                    price: row.find('.input-add-price').val()
                });
            }
        });

        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rows: rowsData,
            lock: shouldLock
        };

        $.post('<?= base_url('admin/construction/save_all_addendum/' . $construction['id']) ?>', data, function (res) {
            l.stop();
            if (res.status) {
                alert('✅ ' + res.message);
                window.location.reload();
            } else {
                alert('❌ ' + res.message);
            }
        }).fail(function () {
            l.stop();
            alert('Gagal menyimpan data Addendum!');
        });
    }

    /* ── Delete Row ── */
    function deleteAddendumRow(btn, id) {
        if (!confirm('Hapus baris ini?')) return;
        $.get('<?= base_url('admin/construction/delete_addendum_row') ?>/' + id, function (res) {
            if (res.status) {
                $(btn).closest('tr').remove();
                calculateGrandTotalAddendum();
            } else {
                alert(res.message);
            }
        });
    }

    /* ── Materials Modal ── */
    function openAddendumMaterialModal(addendumId, activityName) {
        activeAddendumId = addendumId;
        $('#modalAddendumMaterialTitle').text('Bahan: ' + activityName);
        loadAddendumMaterials(addendumId);
        const modal = new bootstrap.Modal(document.getElementById('modalAddendumMaterials'));
        modal.show();
    }

    function loadAddendumMaterials(addendumId) {
        $.get('<?= base_url('admin/construction/get_addendum_materials') ?>/' + addendumId, function (data) {
            console.log('Raw data:', data);
            // Cek apakah data adalah array valid
            if (!Array.isArray(data) || data.length === 0) {
                console.warn('No materials found for addendum:', addendumId);
                $('#addendumMaterialList').html(
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
                    <button class="tbl-btn tbl-btn-del" title="Hapus" onclick="deleteAddendumMaterial(${m.id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`;
            });
            $('#addendumMaterialList').html(html);
        });
    }

    function submitProductToAddendumMaterial() {
        const productId = $('#selectProductAddendum').val();
        if (!productId) {
            alert('Pilih produk terlebih dahulu!');
            return;
        }
        $.post('<?= base_url('admin/construction/add_addendum_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            addendum_id: activeAddendumId,
            product_id: productId
        }, function (res) {
            if (res.status) {
                loadAddendumMaterials(activeAddendumId);
                $('#selectProductAddendum').val('').trigger('change');
            } else {
                alert(res.message);
            }
        });
    }

    function deleteAddendumMaterial(id) {
        if (!confirm('Hapus bahan ini?')) return;
        $.get('<?= base_url('admin/construction/delete_addendum_material') ?>/' + id, function (res) {
            if (res.status) loadAddendumMaterials(activeAddendumId);
        });
    }

    /* ── Init ── */
    $(function () {
        calculateGrandTotalAddendum();
    });
</script>