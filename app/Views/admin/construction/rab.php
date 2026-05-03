<style>
    /* Style Khusus RAB Excel-Style */
    .table-rab input {
        border: none;
        background: transparent;
        width: 100%;
        padding: 5px;
        font-size: 13px;
    }

    .table-rab input:focus {
        background: #fff;
        border: 1px solid #6777ef;
        outline: none;
        border-radius: 4px;
    }

    .table-rab tr:hover {
        background-color: #fcfcfc;
    }

    .input-roman {
        font-weight: bold;
        text-align: center;
        color: #ff0404ff;
    }

    .row-locked {
        background-color: #f9f9f9 !important;
    }

    .row-locked input {
        color: #999;
        pointer-events: none;
    }

    /* ── MOBILE FRIENDLY TABLE ── */
    .table-rab-container {
        -webkit-overflow-scrolling: touch;
    }

    .table-rab th,
    .table-rab td {
        vertical-align: middle !important;
    }

    /* Minimum widths to prevent squishing on mobile */
    .table-rab th:nth-child(1), .input-roman { min-width: 60px; }
    .table-rab th:nth-child(2), .input-group-name { min-width: 160px; }
    .table-rab th:nth-child(3), .input-section { min-width: 160px; }
    .table-rab th:nth-child(4), .input-task { min-width: 220px; }
    .table-rab th:nth-child(5), .input-vol { min-width: 70px; }
    .table-rab th:nth-child(6), .input-unit { min-width: 80px; }
    .table-rab th:nth-child(7), .input-price { min-width: 130px; }
    .table-rab th:nth-child(8), .row-total { min-width: 130px; }
    .table-rab th:nth-child(9) { min-width: 110px; }

    @media (max-width: 767px) {
        .rab-header-actions {
            flex-direction: column;
            align-items: stretch !important;
            gap: 10px;
        }
        .rab-header-actions .btn-group,
        .rab-header-actions > div,
        .rab-header-actions .btn {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 rab-header-actions">
    <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i>Manajemen RAB Proyek</h6>
    <div class="d-flex gap-2 flex-wrap">
        <?php if (!empty($rab_list) && $rab_list[0]['is_locked'] == 0): ?>
            <a href="<?= base_url('admin/construction/lock_rab/' . $construction['id']) ?>" class="btn btn-danger btn-sm shadow-sm flex-grow-1" onclick="return confirm('Kunci RAB kawan? Data tidak bisa diubah lagi!')"><i class="fas fa-lock mr-1"></i> Kunci RAB</a>
        <?php elseif (!empty($rab_list)): ?>
            <a href="<?= base_url('admin/construction/unlock_rab/' . $construction['id']) ?>" class="btn btn-warning btn-sm shadow-sm flex-grow-1"><i class="fas fa-lock-open mr-1"></i> Buka Kunci</a>
        <?php endif; ?>
        <button class="btn btn-success btn-sm shadow-sm flex-grow-1" onclick="addNewRabRow()"><i class="fas fa-plus mr-1"></i> Tambah Baris</button>
    </div>
</div>
<div class="table-responsive table-rab-container">
    <table class="table table-bordered table-sm table-rab">
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
        <tbody id="rabBody">
            <?php if (!empty($rab_list)): foreach ($rab_list as $rab): ?>
                    <tr data-id="<?= $rab['id'] ?>" class="<?= $rab['is_locked'] ? 'row-locked' : '' ?>">
                        <td><input type="text" class="input-roman" value="<?= esc($rab['roman_number'] ?? 'I') ?>"></td>
                        <td><input type="text" class="input-group-name" value="<?= esc($rab['group_name'] ?? 'PEKERJAAN') ?>"></td>
                        <td><input type="text" class="input-section" value="<?= esc($rab['section_group']) ?>"></td>
                        <td><input type="text" class="input-task" value="<?= esc($rab['activity_name']) ?>"></td>
                        <td><input type="number" step="0.01" class="input-vol text-center" value="<?= $rab['volume'] ?>"></td>
                        <td><input type="text" class="input-unit text-center" value="<?= esc($rab['unit']) ?>"></td>
                        <td><input type="number" class="input-price text-right" value="<?= (int)$rab['current_unit_price'] ?>"></td>
                        <td class="text-right font-weight-bold row-total">0</td>
                        <td class="text-center">
                            <?php if ($rab['is_locked'] == 1): ?>
                                <i class="fas fa-lock text-muted"></i>
                            <?php else: ?>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-xs" onclick="openMaterialModal(<?= $rab['id'] ?>, '<?= esc($rab['activity_name']) ?>')"><i class="fas fa-boxes"></i></button>
                                    <button class="btn btn-success btn-xs" onclick="saveRabRow(this)"><i class="fas fa-save"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="deleteRabRow(this, <?= $rab['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light font-weight-bold text-primary">
                <td colspan="7" class="text-right">ESTIMASI TOTAL</td>
                <td class="text-right" id="grandTotalRab">Rp 0</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    let activeRabId = 0;
    $(document).ready(function() {
        calculateGrandTotal();
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });

    function calculateGrandTotal() {
        let total = 0;
        let lastS = "";
        $('.input-vol').each(function() {
            let row = $(this).closest('tr');
            let s = row.find('.input-section').val();
            let v = parseFloat($(this).val()) || 0;
            let p = parseFloat(row.find('.input-price').val()) || 0;
            let sub = v * p;
            row.find('.row-total').text(sub.toLocaleString('id-ID'));
            total += sub;
            if (s === lastS && s !== "") {
                row.find('.input-section').css('color', 'transparent');
            } else {
                row.find('.input-section').css('color', '#34395e').css('font-weight', 'bold');
            }
            lastS = s;
        });
        $('#grandTotalRab').text('Rp ' + total.toLocaleString('id-ID'));
    }

    $(document).on('input', '.input-vol, .input-price, .input-section', function() {
        calculateGrandTotal();
    });

    function addNewRabRow() {
        $('#rabBody').append(`<tr data-id="0">
            <td><input type="text" class="input-roman" value="I"></td>
            <td><input type="text" class="input-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-section" placeholder="Sub..."></td>
            <td><input type="text" class="input-task" placeholder="Pekerjaan..."></td>
            <td><input type="number" class="input-vol text-center" value="1"></td>
            <td><input type="text" class="input-unit text-center" value="unit"></td>
            <td><input type="number" class="input-price text-right" value="0"></td>
            <td class="text-right row-total">0</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class="btn btn-success btn-xs" onclick="saveRabRow(this)"><i class="fas fa-save"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); calculateGrandTotal();"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`);
    }

    function saveRabRow(btn) {
        const row = $(btn).closest('tr');
        if (row.hasClass('row-locked')) {
            alert('Data terkunci kawan!');
            return;
        }

        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            id: row.attr('data-id'),
            construction_id: <?= $construction['id'] ?>,
            roman_number: row.find('.input-roman').val(),
            group_name: row.find('.input-group-name').val(),
            section_group: row.find('.input-section').val(),
            task_name: row.find('.input-task').val(),
            volume: row.find('.input-vol').val(),
            unit: row.find('.input-unit').val(),
            price: row.find('.input-price').val()
        };

        $.post('<?= base_url('admin/construction/save_rab_row') ?>', data, function(res) {
            if (res.status) {
                row.attr('data-id', res.id);
                alert('👍 ' + res.message);
                // Opsional: location.reload(); jika kawan ingin grup lsg ter-update secara visual
            } else {
                alert('❌ ' + res.message);
            }
        }).fail(function(xhr) {
            alert('Gagal kawan, cek console!');
        });
    }

    function deleteRabRow(btn, id) {
        if (confirm('Hapus baris ini?')) {
            $.get('<?= base_url('admin/construction/delete_rab_row') ?>/' + id, function(res) {
                if (res.status) {
                    $(btn).closest('tr').remove();
                    calculateGrandTotal();
                } else {
                    alert(res.message);
                }
            });
        }
    }

    function openMaterialModal(id, title) {
        activeRabId = id;
        $('#modalMaterialTitle').text('Bahan: ' + title);
        $('#modalMaterials').modal('show');
        $.get('<?= base_url('admin/construction/get_rab_materials') ?>/' + id, function(data) {
            let h = '<div class="list-group shadow-sm">';
            data.forEach(item => {
                h += `<div class="list-group-item d-flex justify-content-between">
                    <span>${item.material_name} (Rp ${parseInt(item.price).toLocaleString('id-ID')})</span>
                    <button class="btn btn-link text-danger p-0" onclick="deleteMaterial(${item.id})"><i class="fas fa-trash"></i></button>
                </div>`;
            });
            $('#materialList').html(h + (data.length ? '' : '<div class="text-center text-muted">Belum ada bahan</div>') + '</div>');
        });
    }

    function submitProductToMaterial() {
        const pId = $('#selectProductRab').val();
        if (!pId) return alert('Pilih produk kawan!');
        $.post('<?= base_url('admin/construction/add_rab_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rab_id: activeRabId,
            product_id: pId
        }, function(res) {
            if (res.status) {
                openMaterialModal(activeRabId, '');
            } else {
                alert(res.message);
            }
        });
    }

    function deleteMaterial(id) {
        if (confirm('Hapus bahan?')) {
            $.get('<?= base_url('admin/construction/delete_rab_material') ?>/' + id, function() {
                openMaterialModal(activeRabId, '');
            });
        }
    }
</script>