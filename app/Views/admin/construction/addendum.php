<style>
    /* ── MOBILE FRIENDLY TABLE ADDENDUM ── */
    .table-addendum-container {
        -webkit-overflow-scrolling: touch;
    }

    .table-addendum th,
    .table-addendum td {
        vertical-align: middle !important;
    }

    /* Minimum widths to prevent squishing on mobile */
    .table-addendum th:nth-child(1), .input-add-roman { min-width: 60px; }
    .table-addendum th:nth-child(2), .input-add-group-name { min-width: 160px; }
    .table-addendum th:nth-child(3), .input-add-section { min-width: 160px; }
    .table-addendum th:nth-child(4), .input-add-task { min-width: 220px; }
    .table-addendum th:nth-child(5), .input-add-vol { min-width: 70px; }
    .table-addendum th:nth-child(6), .input-add-unit { min-width: 80px; }
    .table-addendum th:nth-child(7), .input-add-price { min-width: 130px; }
    .table-addendum th:nth-child(8), .row-add-total { min-width: 130px; }
    .table-addendum th:nth-child(9) { min-width: 110px; }

    @media (max-width: 767px) {
        .addendum-header-actions {
            flex-direction: column;
            align-items: stretch !important;
            gap: 10px;
        }
        .addendum-header-actions .btn-group,
        .addendum-header-actions > div,
        .addendum-header-actions .btn {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 addendum-header-actions">
    <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-file-contract mr-2"></i>Manajemen Addendum Proyek</h6>
    <div class="d-flex gap-2 flex-wrap">
        <?php if (!empty($addendum_list) && $addendum_list[0]['is_locked'] == 0): ?>
            <a href="<?= base_url('admin/construction/lock_addendum/' . $construction['id']) ?>" class="btn btn-danger btn-sm shadow-sm ladda-button flex-grow-1" data-style="zoom-in" onclick="if(confirm('Kunci Addendum kawan? Data tidak bisa diubah lagi!')) { Ladda.create(this).start(); return true; } return false;">
                <span class="ladda-label"><i class="fas fa-lock mr-1"></i> Kunci Addendum</span>
            </a>
        <?php elseif (!empty($addendum_list)): ?>
            <a href="<?= base_url('admin/construction/unlock_addendum/' . $construction['id']) ?>" class="btn btn-warning btn-sm shadow-sm ladda-button flex-grow-1" data-style="zoom-in" onclick="Ladda.create(this).start();">
                <span class="ladda-label"><i class="fas fa-lock-open mr-1"></i> Buka Kunci</span>
            </a>
        <?php endif; ?>
        <button class="btn btn-success btn-sm shadow-sm flex-grow-1" onclick="addNewAddendumRow()"><i class="fas fa-plus mr-1"></i> Tambah Baris</button>
    </div>
</div>
<div class="table-responsive table-addendum-container">
    <table class="table table-bordered table-sm table-rab table-addendum">
        <thead class="bg-light text-center small">
            <tr>
                <th width="5%">Roman</th>
                <th width="15%">Grup Utama</th>
                <th width="15%">Sub Grup</th>
                <th width="20%">Pekerjaan</th>
                <th width="8%">Vol</th>
                <th width="7%">Satuan</th>
                <th width="12%">Harga (Rp)</th>
                <th width="13%">Total (Rp)</th>
                <th width="5%">Aksi</th>
            </tr>
        </thead>
        <tbody id="addendumBody">
            <?php if (!empty($addendum_list)): foreach ($addendum_list as $add): ?>
                    <tr data-id="<?= $add['id'] ?>" class="<?= $add['is_locked'] ? 'row-locked' : '' ?>">
                        <td><input type="text" class="input-add-roman input-roman" value="<?= esc($add['roman_number'] ?? 'I') ?>"></td>
                        <td><input type="text" class="input-add-group-name input-group-name" value="<?= esc($add['group_name'] ?? 'PEKERJAAN') ?>"></td>
                        <td><input type="text" class="input-add-section input-section" value="<?= esc($add['section_group']) ?>"></td>
                        <td><input type="text" class="input-add-task input-task" value="<?= esc($add['activity_name']) ?>"></td>
                        <td><input type="float" step="0.01" class="input-add-vol input-vol text-center" value="<?= $add['volume'] ?>"></td>
                        <td><input type="text" class="input-add-unit input-unit text-center" value="<?= esc($add['unit']) ?>"></td>
                        <td><input type="float" class="input-add-price input-price text-right" value="<?= (int)$add['current_unit_price'] ?>"></td>
                        <td class="text-right font-weight-bold row-add-total">0</td>
                        <td class="text-center">
                            <?php if ($add['is_locked'] == 1): ?>
                                <i class="fas fa-lock text-muted"></i>
                            <?php else: ?>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-xs" onclick="openAddendumMaterialModal(<?= $add['id'] ?>, '<?= esc($add['activity_name']) ?>')"><i class="fas fa-boxes"></i></button>
                                    <button class="btn btn-success btn-xs" onclick="saveAddendumRow(this)"><i class="fas fa-save"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="deleteAddendumRow(this, <?= $add['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light font-weight-bold text-primary">
                <td colspan="7" class="text-right">ESTIMASI TOTAL ADDENDUM</td>
                <td class="text-right" id="grandTotalAddendum">Rp 0</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="modal fade" id="modalAddendumMaterials" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAddendumMaterialTitle">Opsi Bahan Addendum</h5><button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="addendumMaterialList" class="mb-4"></div>
                <div class="card bg-light p-3 border-0">
                    <h6>Tambahkan Opsi Produk:</h6>
                    <select id="selectProductAddendum" class="form-control select2" style="width: 100%;">
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($all_products as $p): ?>
                            <option value="<?= $p['id'] ?>"> <?= esc($p['name']) ?> - Rp <?= number_format($p['price']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-success btn-sm btn-block mt-3" onclick="submitProductToAddendumMaterial()">Tambah Bahan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let activeAddendumId = 0;

    function calculateGrandTotalAddendum() {
        let total = 0;
        let lastS = "";
        $('#addendumBody .input-add-vol').each(function() {
            let row = $(this).closest('tr');
            let s = row.find('.input-add-section').val();
            let v = parseFloat($(this).val()) || 0;
            let p = parseFloat(row.find('.input-add-price').val()) || 0;
            let sub = v * p;
            row.find('.row-add-total').text(sub.toLocaleString('id-ID'));
            total += sub;
            if (s === lastS && s !== "") {
                row.find('.input-add-section').css('color', 'transparent');
            } else {
                row.find('.input-add-section').css('color', '#34395e').css('font-weight', 'bold');
            }
            lastS = s;
        });
        $('#grandTotalAddendum').text('Rp ' + total.toLocaleString('id-ID'));
    }

    $(document).on('input', '#addendumBody .input-add-vol, #addendumBody .input-add-price, #addendumBody .input-add-section', function() {
        calculateGrandTotalAddendum();
    });

    function addNewAddendumRow() {
        $('#addendumBody').append(`<tr data-id="0">
            <td><input type="text" class="input-add-roman input-roman" value="I"></td>
            <td><input type="text" class="input-add-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-add-section" placeholder="Sub..."></td>
            <td><input type="text" class="input-add-task" placeholder="Pekerjaan..."></td>
            <td><input type="number" class="input-add-vol text-center" value="1" step="0.01"></td>
            <td><input type="text" class="input-add-unit text-center" value="unit"></td>
            <td><input type="number" class="input-add-price text-right" value="0"></td>
            <td class="text-right row-add-total">0</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class="btn btn-success btn-xs" onclick="saveAddendumRow(this)"><i class="fas fa-save"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); calculateGrandTotalAddendum();"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`);
    }

    function saveAddendumRow(btn) {
        const row = $(btn).closest('tr');
        if (row.hasClass('row-locked')) {
            alert('Data terkunci kawan!');
            return;
        }
        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            id: row.attr('data-id'),
            construction_id: <?= $construction['id'] ?>,
            roman_number: row.find('.input-add-roman').val(),
            group_name: row.find('.input-add-group-name').val(),
            section_group: row.find('.input-add-section').val(),
            task_name: row.find('.input-add-task').val(),
            volume: row.find('.input-add-vol').val(),
            unit: row.find('.input-add-unit').val(),
            price: row.find('.input-add-price').val()
        };
        $.post('<?= base_url('admin/construction/save_addendum_row') ?>', data, function(res) {
            if (res.status) {
                row.attr('data-id', res.id);
                alert('ðŸ‘ ' + res.message);
            } else {
                alert('âŒ ' + res.message);
            }
        }).fail(function() {
            alert('Gagal!');
        });
    }

    function deleteAddendumRow(btn, id) {
        if (confirm('Hapus baris ini?')) {
            $.get('<?= base_url('admin/construction/delete_addendum_row') ?>/' + id, function(res) {
                if (res.status) {
                    $(btn).closest('tr').remove();
                    calculateGrandTotalAddendum();
                } else {
                    alert(res.message);
                }
            });
        }
    }

    // â”€â”€ Addendum Materials Modal â”€â”€
    function openAddendumMaterialModal(addendumId, activityName) {
        activeAddendumId = addendumId;
        $('#modalAddendumMaterialTitle').text('Opsi Bahan Addendum: ' + activityName);
        loadAddendumMaterials(addendumId);
        $('#modalAddendumMaterials').modal('show');
    }

    function loadAddendumMaterials(addendumId) {
        $.get('<?= base_url('admin/construction/get_addendum_materials') ?>/' + addendumId, function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<p class="text-muted small text-center">Belum ada bahan dipilih</p>';
            } else {
                data.forEach(function(m) {
                    html += `<div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white border rounded">
                        <span class="small"><strong>${m.material_name}</strong> &mdash; Rp ${Number(m.price).toLocaleString('id-ID')}</span>
                        <button class="btn btn-xs btn-danger" onclick="deleteAddendumMaterial(${m.id})"><i class="fas fa-times"></i></button>
                    </div>`;
                });
            }
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
        }, function(res) {
            if (res.status) {
                loadAddendumMaterials(activeAddendumId);
            } else {
                alert(res.message);
            }
        });
    }

    function deleteAddendumMaterial(id) {
        if (confirm('Hapus bahan ini?')) {
            $.get('<?= base_url('admin/construction/delete_addendum_material') ?>/' + id, function(res) {
                if (res.status) {
                    loadAddendumMaterials(activeAddendumId);
                }
            });
        }
    }
</script>