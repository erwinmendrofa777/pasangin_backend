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
