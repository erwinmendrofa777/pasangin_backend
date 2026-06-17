<script>
    <?php
    $isLockedLocal = false;
    if (!empty($rab_list)) {
        $isLockedLocal = (int) $rab_list[0]['is_locked'] === 1;
    }
    ?>
    const isLocked = <?= $isLockedLocal ? 'true' : 'false' ?>;
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

        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
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
        let clean = valStr.toString().replace(/\./g, '').replace(/,/g, '.');
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

        $.post('<?= base_url('admin/renovation/save_all_rab/' . $renovation['id']) ?>', data, function (res) {
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
        $.get('<?= base_url('admin/renovation/delete_rab_row') ?>/' + id, function (res) {
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

        // Reset select2 value
        $('#selectProductRab').val('').trigger('change');

        // Destroy existing select2 if initialized, then re-initialize with dropdownParent
        if ($('#selectProductRab').hasClass("select2-hidden-accessible")) {
            $('#selectProductRab').select2('destroy');
        }
        $('#selectProductRab').select2({
            dropdownParent: $('#modalRabMaterials'),
            placeholder: "— Pilih Produk —",
            allowClear: true
        });

        const modal = new bootstrap.Modal(document.getElementById('modalRabMaterials'));
        modal.show();
    }

    function loadRabMaterials(rabId) {
        // Show loading state
        $('#rabMaterialList').html(
            `<div class="empty-materials" style="border-style: solid; border-color: #f1f5f9;">
                <i class="fas fa-spinner fa-spin" style="color: var(--palette-primary); opacity: 1;"></i>
                <span>Memuat daftar bahan...</span>
            </div>`
        );

        $.get('<?= base_url('admin/renovation/get_rab_materials') ?>/' + rabId, function (data) {
            // Reset all options to original text and enabled state
            $('#selectProductRab option').each(function () {
                const opt = $(this);
                if (!opt.data('original-text')) {
                    opt.data('original-text', opt.text());
                }
                opt.text(opt.data('original-text'));
                opt.prop('disabled', false);
            });

            if (!Array.isArray(data) || data.length === 0) {
                $('#selectProductRab').trigger('change.select2');
                $('#rabMaterialList').html(
                    `<div class="empty-materials">
                        <i class="fas fa-box-open"></i>
                        Belum ada bahan yang dipilih
                    </div>`
                );
                return;
            }

            // Disable options and append suffix for selected items
            data.forEach(function (m) {
                if (m.product_id) {
                    const opt = $(`#selectProductRab option[value="${m.product_id}"]`);
                    if (opt.length > 0) {
                        opt.text(opt.data('original-text') + ' — (Produk sudah dipilih)');
                        opt.prop('disabled', true);
                    }
                }
            });
            $('#selectProductRab').trigger('change.select2');

            let html = '';
            data.forEach(function (m) {
                const supplierInfo = m.supplier_name ? `<span class="mat-meta"><i class="fas fa-store me-1"></i>${m.supplier_name}</span>` : '';
                const stockInfo = m.stock !== null ? `<span class="mat-meta"><i class="fas fa-cubes me-1"></i>Stok: ${m.stock} ${m.unit || ''}</span>` : '';
                const unitInfo = m.unit ? `<span class="mat-meta-badge">${m.unit}</span>` : '';

                // Photo URL
                let photoHtml = '';
                if (m.photo) {
                    const photoSrc = m.photo.indexOf('http') === 0 ? m.photo : '<?= base_url('uploads/products') ?>/' + m.photo;
                    photoHtml = `<img src="${photoSrc}" alt="${m.material_name}" class="mat-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                 <div class="mat-photo-placeholder" style="display:none;"><i class="fas fa-box"></i></div>`;
                } else {
                    photoHtml = `<div class="mat-photo-placeholder"><i class="fas fa-box"></i></div>`;
                }

                html += `<div class="material-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="mat-photo-wrapper">
                            ${photoHtml}
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="mat-name">${m.material_name}</span>
                                ${unitInfo}
                            </div>
                            <div class="mat-price">Rp ${Number(m.price).toLocaleString('id-ID')}</div>
                            <div class="mat-meta-list d-flex flex-wrap gap-3 mt-1">
                                ${supplierInfo}
                                ${stockInfo}
                            </div>
                        </div>
                    </div>
                    <button class="tbl-btn tbl-btn-del ms-3" title="Hapus" onclick="deleteRabMaterial(this, ${m.id})">
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
        $.post('<?= base_url('admin/renovation/add_rab_material') ?>', {
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

    function deleteRabMaterial(btn, id) {
        if (!confirm('Hapus bahan ini?')) return;
        const $btn = $(btn);
        const originalContent = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.get('<?= base_url('admin/renovation/delete_rab_material') ?>/' + id, function (res) {
            if (res.status) {
                loadRabMaterials(activeRabId);
            } else {
                alert(res.message);
                $btn.html(originalContent).prop('disabled', false);
            }
        }).fail(function () {
            alert('Gagal menghapus bahan!');
            $btn.html(originalContent).prop('disabled', false);
        });
    }

    function submitImportRabExcel() {
        const fileInput = document.getElementById('importExcelFile');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan pilih file Excel/Spreadsheet terlebih dahulu!');
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
            url: '<?= base_url('admin/renovation/import-rab-excel/' . $renovation['id']) ?>',
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
                let errorMsg = 'Gagal melakukan import data!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert('❌ ' + errorMsg);
            }
        });
    }
</script>
