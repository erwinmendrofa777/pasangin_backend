<script>
    <?php
    $isLockedLocal = false;
    if (!empty($rab_list)) {
        $isLockedLocal = (int) $rab_list[0]['is_locked'] === 1;
    }

    $satuanHtmlOptions = '<option value="">— Pilih —</option>';
    if (!empty($satuan_options)) {
        foreach ($satuan_options as $s) {
            $satuanHtmlOptions .= '<option value="' . esc($s['nama_satuan']) . '">' . esc($s['nama_satuan']) . '</option>';
        }
    }
    ?>
    const isLocked = <?= $isLockedLocal ? 'true' : 'false' ?>;
    const satuanHtmlOptions = <?= json_encode($satuanHtmlOptions) ?>;
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

        // Inisialisasi awal pagination picker AHSP
        updateAhspPickerPagination();
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
                // Insert subtotal row BEFORE this row (which is the start of the new group)
                let subtotalHtml = `<tr class="row-rab-subtotal">
                    <td colspan="4" class="text-center" style="padding-left: 10px;">`;
                if (!isLocked) {
                    subtotalHtml += `<button type="button" class="btn btn-sm btn-link text-primary p-0" style="font-size: 11px; text-decoration: none; font-weight: 500;" onclick="addNewRabRowAt('${currentRoman}', '${currentGroupName}', this)">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                        </button>`;
                }
                subtotalHtml += `</td>
                    <td colspan="3" class="text-end fw-bold text-uppercase" style="color: #4a5568; padding-right: 15px !important;">
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
            let subtotalHtml = `<tr class="row-rab-subtotal">
                <td colspan="4" class="text-center" style="padding-left: 10px;">`;
            if (!isLocked) {
                subtotalHtml += `<button type="button" class="btn btn-sm btn-link text-primary p-0" style="font-size: 11px; text-decoration: none; font-weight: 500;" onclick="addNewRabRowAt('${currentRoman}', '${currentGroupName}', this)">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Baris
                    </button>`;
            }
            subtotalHtml += `</td>
                <td colspan="3" class="text-end fw-bold text-uppercase" style="color: #4a5568; padding-right: 15px !important;">
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

    function romanToInt(roman) {
        if (!roman) return 0;
        const lookup = {I:1, V:5, X:10, L:50, C:100, D:500, M:1000};
        let num = 0;
        let val = 0;
        for (let i = roman.length - 1; i >= 0; i--) {
            let temp = lookup[roman[i].toUpperCase()];
            if (!temp) continue;
            if (temp < val) {
                num -= temp;
            } else {
                num += temp;
            }
            val = temp;
        }
        return num;
    }

    function intToRoman(num) {
        if (num <= 0) return 'I';
        const roman = {
            M: 1000, CM: 900, D: 500, CD: 400,
            C: 100, XC: 90, L: 50, XL: 40,
            X: 10, IX: 9, V: 5, IV: 4, I: 1
        };
        let str = '';
        for (let i in roman) {
            let q = Math.floor(num / roman[i]);
            num -= q * roman[i];
            str += i.repeat(q);
        }
        return str;
    }

    /* ── Add New Row ── */
    function addNewRabRow() {
        let nextRoman = 'I';
        const lastRomanInput = $('#rabBody tr:not(.row-rab-subtotal):not(.row-add-new-group):not(.row-add-row-in-group) .input-rab-roman').last();
        if (lastRomanInput.length > 0) {
            const lastRomanVal = (lastRomanInput.val() || 'I').trim().toUpperCase();
            const lastValInt = romanToInt(lastRomanVal);
            nextRoman = intToRoman(lastValInt + 1);
        }

        const newRow = `<tr data-id="0">
            <td><input type="text" class="input-rab-roman input-roman" value="${nextRoman}"></td>
            <td><input type="text" class="input-rab-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-rab-section" placeholder="Sub grup..." oninput="calculateGrandTotalRab()"></td>
            <td><input type="text" class="input-rab-task input-rab-task-picker" placeholder="Pilih Pekerjaan (AHSP)..." readonly data-ahsp-id="" data-bs-toggle="modal" data-bs-target="#modalAhspPicker"></td>
            <td><input type="number" class="input-rab-vol input-vol" value="1" step="0.01" oninput="calculateGrandTotalRab()"></td>
            <td>
                <select class="input-rab-unit input-unit">
                    ${satuanHtmlOptions}
                </select>
            </td>
            <td><input type="text" class="input-rab-price input-price" value="0" readonly></td>
            <td class="row-rab-total">0</td>
            <td>
                <div class="tbl-actions">
                    <button type="button" class="tbl-btn tbl-btn-detail" title="Detail AHSP" onclick="triggerNewRowDetail(this)">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <button type="button" class="tbl-btn tbl-btn-mat" title="Bahan" onclick="triggerNewRowMaterial(this)">
                        <i class="fas fa-boxes"></i>
                    </button>
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
            <td><input type="text" class="input-rab-task input-rab-task-picker" placeholder="Pilih Pekerjaan (AHSP)..." readonly data-ahsp-id="" data-bs-toggle="modal" data-bs-target="#modalAhspPicker"></td>
            <td><input type="number" class="input-rab-vol input-vol" value="1" step="0.01" oninput="calculateGrandTotalRab()"></td>
            <td>
                <select class="input-rab-unit input-unit">
                    ${satuanHtmlOptions}
                </select>
            </td>
            <td><input type="text" class="input-rab-price input-price" value="0" readonly></td>
            <td class="row-rab-total">0</td>
            <td>
                <div class="tbl-actions">
                    <button type="button" class="tbl-btn tbl-btn-detail" title="Detail AHSP" onclick="triggerNewRowDetail(this)">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <button type="button" class="tbl-btn tbl-btn-mat" title="Bahan" onclick="triggerNewRowMaterial(this)">
                        <i class="fas fa-boxes"></i>
                    </button>
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

    /* ── Save All Rows ── */
    function saveAllRab() {
        const btnId = '#btnSaveDraft';
        const l = Ladda.create(document.querySelector(btnId));
        l.start();

        const rowsData = [];
        let validationFailed = false;
        $('#rabBody tr:not(.row-rab-subtotal):not(.row-add-new-group):not(.row-add-row-in-group)').each(function () {
            const row = $(this);
            const ahspId = row.find('.input-rab-task').data('ahsp-id') || row.find('.input-rab-task').attr('data-ahsp-id');
            if (!ahspId) {
                validationFailed = true;
                return false;
            }
            rowsData.push({
                id: row.attr('data-id'),
                roman_number: row.find('.input-rab-roman').val(),
                group_name: row.find('.input-rab-group-name').val(),
                section_group: row.find('.input-rab-section').val(),
                ahsp_id: ahspId,
                volume: row.find('.input-rab-vol').val(),
                unit: row.find('.input-rab-unit').val(),
                price: parseRupiahToFloat(row.find('.input-rab-price').val())
            });
        });

        if (validationFailed) {
            alert('⚠️ Harap pilih pekerjaan (AHSP) untuk semua baris baru/draf!');
            l.stop();
            return;
        }

        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rows: rowsData,
            lock: false
        };

        $.post('<?= base_url('admin/design/save_all_rab/' . $request['id']) ?>', data, function (res) {
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
        $.get('<?= base_url('admin/design/delete_rab_row') ?>/' + id, function (res) {
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
        $('#modalRabMaterialTitle').html('<i class="fas fa-boxes me-2"></i> Pilihan Produk: <strong>' + activityName + '</strong>');
        loadRabMaterials(rabId);

        const modal = new bootstrap.Modal(document.getElementById('modalRabMaterials'));
        modal.show();
    }

    function loadRabMaterials(rabId) {
        // Tampilkan loading spinner
        $('#accordionRabMaterials').html(
            `<div class="text-center py-5 text-muted">
                <i class="fas fa-spinner fa-spin me-2" style="color: var(--palette-primary); font-size: 24px;"></i> Memuat data kebutuhan bahan...
            </div>`
        );

        $.get('<?= base_url('admin/design/get_rab_materials') ?>/' + rabId, function (data) {
            if (!Array.isArray(data) || data.length === 0) {
                $('#accordionRabMaterials').html(
                    `<div class="text-center py-5 text-muted" style="border: 1px dashed #cbd5e1; border-radius: 12px; background: #ffffff;">
                        <i class="fas fa-info-circle me-1" style="font-size: 20px;"></i> Pekerjaan ini tidak membutuhkan bahan/material berdasarkan data AHSP.
                    </div>`
                );
                return;
            }

            let html = '';
            data.forEach(function (item, index) {
                // Susun daftar rekomendasi produk yang sudah ditambahkan
                let recommendationsHtml = '';
                if (item.recommendations && item.recommendations.length > 0) {
                    item.recommendations.forEach(function (rec) {
                        const photoSrc = rec.product_photo ? (rec.product_photo.indexOf('http') === 0 ? rec.product_photo : '<?= base_url('uploads/products') ?>/' + rec.product_photo) : '';
                        const photoHtml = photoSrc ? `<img src="${photoSrc}" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="fas fa-box text-muted" style="font-size: 16px;"></i>`;
                        const isChecked = rec.selected ? 'checked' : '';

                        let stockBadgeHtml = '';
                        if (rec.product_stock > 10) {
                            stockBadgeHtml = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #ecfdf5; color: #065f46; border-color: #a7f3d0 !important;"><i class="fas fa-check" style="font-size: 8px;"></i> Ready: ${rec.product_stock}</span>`;
                        } else if (rec.product_stock > 0) {
                            stockBadgeHtml = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #fffbeb; color: #92400e; border-color: #fde68a !important;"><i class="fas fa-exclamation-triangle" style="font-size: 8px;"></i> Sisa: ${rec.product_stock}</span>`;
                        } else {
                            stockBadgeHtml = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #fef2f2; color: #991b1b; border-color: #fca5a5 !important;"><i class="fas fa-times" style="font-size: 8px;"></i> Habis</span>`;
                        }

                        const pRating = parseFloat(rec.product_rating) || 5.0;
                        const pReviews = parseInt(rec.product_total_reviews) || 0;
                        const ratingHtml = `
                            <span class="text-warning fw-semibold ms-2" style="font-size: 11px;">
                                <i class="fas fa-star text-warning"></i> 
                                ${pRating.toFixed(1)} 
                                <span class="text-muted" style="font-weight: normal;">(${pReviews} ulasan)</span>
                            </span>
                        `;

                        recommendationsHtml += `
                            <div class="recommended-prod-row p-3 mb-3 text-start" style="background: ${rec.selected ? '#fffcfc' : '#ffffff'}; border-radius: 12px; border: 2px solid ${rec.selected ? 'var(--palette-primary)' : '#e2e8f0'}; box-shadow: ${rec.selected ? '0 4px 12px rgba(255, 92, 92, 0.08)' : '0 1px 3px rgba(0,0,0,0.02)'}; transition: all 0.2s ease; position: relative;">
                                <!-- Delete button at top right -->
                                <button type="button" class="btn delete-rec-btn" data-rec-id="${rec.id}" title="Hapus Rekomendasi" style="position: absolute; top: 10px; right: 10px; color: #ef4444; background: #fef2f2; border: 1px solid #fee2e2; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0; cursor: pointer; z-index: 5;">
                                    <i class="fas fa-times" style="font-size: 11px;"></i>
                                </button>

                                <div class="d-flex align-items-start gap-3">
                                    <!-- Radio choice -->
                                    <div class="form-check m-0 pt-2">
                                        <input class="form-check-input select-product-radio" type="radio" name="selected-product-${item.ahsp_bahan_id}" value="${rec.id}" ${isChecked} style="cursor: pointer; width: 18px; height: 18px; margin-top: 0; accent-color: var(--palette-primary);">
                                    </div>
                                    
                                    <!-- Product Photo -->
                                    <div style="flex-shrink: 0; width: 56px; height: 56px; border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                        ${photoHtml}
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                            <span class="fw-bold text-dark" style="font-size: 13px; word-break: break-word; font-family: 'Outfit', sans-serif;">${rec.product_name}</span>
                                            ${rec.selected ? `<span class="badge d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; padding: 3px 6px; background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;"><i class="fas fa-check-circle"></i> Pilihan Aktif</span>` : ''}
                                        </div>
                                        
                                        <!-- Price and Unit -->
                                        <div class="mb-2">
                                            <span style="font-size: 14px; font-weight: 700; color: var(--palette-primary); font-family: 'Outfit', sans-serif;">Rp ${Number(rec.product_price).toLocaleString('id-ID')}</span>
                                            <span class="text-muted" style="font-size: 11px;">/ ${rec.product_unit || ''}</span>
                                            ${ratingHtml}
                                        </div>

                                        <!-- Jumlah Harga (Koefisien x Harga Produk) -->
                                        <div class="mb-2 p-2" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; font-family: 'Outfit', sans-serif;">
                                            <span class="text-secondary fw-semibold"><i class="fas fa-calculator text-primary me-1"></i>Jumlah Harga:</span>
                                            <span class="fw-bold text-dark font-monospace" style="font-size: 12px; color: #0f172a !important;">Rp ${Number(item.koefisien * rec.product_price).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>
                                        </div>

                                        <!-- Product Specs Badges -->
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            ${stockBadgeHtml}
                                            <span class="badge bg-light text-secondary border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; border-color: #e2e8f0 !important;">
                                                <i class="fas fa-shopping-cart" style="font-size: 8px;"></i> Min: ${rec.product_min_order} ${rec.product_unit || ''}
                                            </span>
                                            ${rec.product_quantity > 0 ? `
                                            <span class="badge bg-light text-secondary border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; border-color: #e2e8f0 !important;">
                                                <i class="fas fa-box" style="font-size: 8px;"></i> Isi: ${rec.product_quantity} ${rec.product_unit || ''}
                                            </span>` : ''}
                                        </div>

                                        <!-- Product Description (if any) -->
                                        ${rec.product_description ? `
                                        <p class="text-muted mb-0 mt-1" style="font-size: 11px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; word-break: break-word;">
                                            ${rec.product_description}
                                        </p>` : ''}
                                     </div>
                                 </div>

                                 <!-- Supplier Bar -->
                                 <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: #f1f5f9 !important; font-size: 11px; font-family: 'Outfit', sans-serif;">
                                     <div class="d-flex align-items-center flex-wrap gap-2">
                                         <span class="fw-semibold text-secondary d-inline-flex align-items-center gap-1">
                                             <i class="fas fa-store text-primary" style="font-size: 11px;"></i> ${rec.supplier_name || 'Tanpa Supplier'}
                                         </span>
                                         ${rec.supplier_city ? `
                                         <span class="text-muted d-inline-flex align-items-center gap-1" style="font-size: 10px;">
                                             <i class="fas fa-map-marker-alt text-danger" style="font-size: 10px;"></i> ${rec.supplier_city}
                                         </span>` : ''}
                                         ${rec.supplier_rating > 0 ? `
                                         <span class="text-warning d-inline-flex align-items-center gap-1" style="font-size: 10px; font-weight: 600;">
                                             <i class="fas fa-star text-warning"></i> ${rec.supplier_rating.toFixed(1)}
                                             <span class="text-muted" style="font-weight: normal;">(${rec.supplier_total_reviews} ulasan)</span>
                                         </span>` : ''}
                                     </div>
                                     
                                     <!-- WhatsApp Chat link -->
                                     ${rec.supplier_phone ? `
                                     <a href="https://wa.me/${rec.supplier_phone.replace(/\D/g, '')}?text=Halo%20${encodeURIComponent(rec.supplier_name || '')},%20saya%20tertarik%20dengan%20produk%20${encodeURIComponent(rec.product_name)}%20di%20Pasangin" target="_blank" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 px-2 py-1" style="font-size: 10px; border-radius: 6px; padding: 2px 8px; border-color: #25d366; color: #25d366; background: transparent; font-weight: 500; text-decoration: none; transition: all 0.2s ease;" onmouseover="this.style.background='#25d366'; this.style.color='#ffffff';" onmouseout="this.style.background='transparent'; this.style.color='#25d366';">
                                         <i class="fab fa-whatsapp" style="font-size: 12px;"></i> Hubungi
                                     </a>` : ''}
                                 </div>
                             </div>
                        `;
                    });
                } else {
                    recommendationsHtml = `
                        <div class="text-center text-muted p-3" style="font-size: 12px; border: 1px dashed #cbd5e1; border-radius: 12px; background: #fafafa;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Belum ada rekomendasi produk.
                        </div>
                    `;
                }

                // Tentukan info pilihan aktif untuk ditaruh di header
                let activeProductHtml = '';
                let selectedRec = item.recommendations ? item.recommendations.find(r => r.selected) : null;
                if (selectedRec) {
                    activeProductHtml = `
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-success font-monospace" style="font-size: 13px;">Rp ${Number(selectedRec.product_price).toLocaleString('id-ID')}</span>
                            <span class="badge border bg-light text-dark" style="font-size: 9px; border-radius: 6px; padding: 2px 6px; border-color: #cbd5e1 !important;">/ ${selectedRec.product_unit || ''}</span>
                        </div>
                        <div class="text-truncate text-secondary" style="font-size: 11px; max-width: 250px;" title="${selectedRec.product_name}">
                            <i class="fas fa-check-circle text-success me-1"></i>${selectedRec.product_name}
                        </div>
                    `;
                } else {
                    activeProductHtml = `
                        <span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 10px; border-radius: 6px; background-color: #fffbeb; color: #92400e; border-color: #fde68a !important; padding: 4px 8px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 9px;"></i> Belum ada pilihan
                        </span>
                    `;
                }

                const isExpanded = false;
                const collapsedClass = 'collapsed';
                const showClass = '';
                const ariaExpanded = 'false';

                html += `
                    <div class="accordion-item" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 16px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        <h2 class="accordion-header" id="heading-bahan-${item.ahsp_bahan_id}">
                            <button class="accordion-button ${collapsedClass} d-flex align-items-center justify-content-between p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-bahan-${item.ahsp_bahan_id}" aria-expanded="${ariaExpanded}" aria-controls="collapse-bahan-${item.ahsp_bahan_id}" style="background: #ffffff; border: none; outline: none; width: 100%; box-shadow: none;">
                                
                                <!-- Uraian Bahan -->
                                <div class="d-flex flex-column text-start" style="flex: 1; min-width: 0; padding-right: 15px;">
                                    <span class="fw-bold text-dark" style="font-size: 13px; word-break: break-word; font-family: 'Outfit', sans-serif;">${item.uraian}</span>
                                    <span class="text-muted" style="font-size: 10px; margin-top: 2px;">Kode: ${item.kode || '-'}</span>
                                </div>
                                
                                <!-- Koefisien -->
                                <div class="text-end px-3" style="min-width: 120px;">
                                    <span class="text-muted d-block" style="font-size: 10px; font-family: 'Outfit', sans-serif;">Koefisien</span>
                                    <span class="fw-bold text-primary font-monospace" style="font-size: 12px;">${item.koefisien.toFixed(4).replace('.', ',')}</span>
                                    <span class="badge bg-light text-dark border ms-1" style="font-size: 9px; border-radius: 6px; padding: 2px 4px; border-color: #cbd5e1 !important;">${item.satuan || '-'}</span>
                                </div>

                                <!-- Pilihan Aktif -->
                                <div class="px-3 text-start" style="min-width: 250px; max-width: 350px;">
                                    <span class="text-muted d-block mb-1" style="font-size: 10px; font-family: 'Outfit', sans-serif;">Pilihan Aktif</span>
                                    ${activeProductHtml}
                                </div>

                            </button>
                        </h2>
                        <div id="collapse-bahan-${item.ahsp_bahan_id}" class="accordion-collapse collapse ${showClass}" aria-labelledby="heading-bahan-${item.ahsp_bahan_id}" data-bs-parent="#accordionRabMaterials">
                            <div class="accordion-body p-4" style="background: #fafbfc; border-top: 1px solid #f1f5f9;">
                                
                                <!-- Tombol Tambah Rekomendasi -->
                                <div class="add-product-wrapper mb-4">
                                    <button type="button" class="btn btn-adm btn-adm-primary btn-sm btn-open-product-picker d-inline-flex align-items-center gap-1" data-ahsp-bahan-id="${item.ahsp_bahan_id}" data-bahan-name="${item.uraian.replace(/"/g, '&quot;')}" style="border-radius: 8px; padding: 8px 16px;">
                                        <i class="fas fa-plus-circle"></i> Tambah Rekomendasi Produk
                                    </button>
                                </div>

                                <!-- Daftar Kartu Rekomendasi -->
                                <div>
                                    <label class="form-label fw-bold text-secondary mb-3" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-family: 'Outfit', sans-serif;">Alternatif Produk Rekomendasi</label>
                                    <div class="recommendations-container">
                                        ${recommendationsHtml}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                `;
            });

            $('#accordionRabMaterials').html(html);

            // Bind event ketika radio button diklik untuk memilih salah satu rekomendasi
            $(document).off('click', '.select-product-radio').on('click', '.select-product-radio', function () {
                const radio = $(this);
                const recId = radio.val();

                const container = $('#accordionRabMaterials');
                container.css('opacity', '0.5');

                $.post('<?= base_url('admin/design/select_rab_material') ?>', {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    id: recId
                }, function (res) {
                    container.css('opacity', '1');
                    if (res.status) {
                        loadRabMaterials(activeRabId);

                        // Update harga satuan baris RAB
                        const tr = $(`#rabBody tr[data-id="${activeRabId}"]`);
                        if (tr.length > 0) {
                            tr.find('.input-price').val(res.formatted_new_unit_price);

                            const vol = parseFloat(tr.find('.input-vol').val()) || 0;
                            const price = res.new_unit_price;
                            const total = vol * price;
                            tr.find('.row-rab-total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                            calculateGrandTotalRab();
                        }
                    } else {
                        alert('❌ ' + res.message);
                        loadRabMaterials(activeRabId);
                    }
                }).fail(function () {
                    container.css('opacity', '1');
                    alert('Gagal mengubah produk terpilih!');
                    loadRabMaterials(activeRabId);
                });
            });

            // Bind event ketika tombol hapus rekomendasi diklik
            $(document).off('click', '.delete-rec-btn').on('click', '.delete-rec-btn', function () {
                const btn = $(this);
                const recId = btn.data('rec-id');

                if (!confirm('Hapus rekomendasi produk ini?')) return;

                const container = $('#accordionRabMaterials');
                container.css('opacity', '0.5');

                $.get('<?= base_url('admin/design/delete_rab_material') ?>/' + recId, function (res) {
                    container.css('opacity', '1');
                    if (res.status) {
                        loadRabMaterials(activeRabId);

                        // Update harga satuan baris RAB
                        const tr = $(`#rabBody tr[data-id="${activeRabId}"]`);
                        if (tr.length > 0) {
                            tr.find('.input-price').val(res.formatted_new_unit_price);

                            const vol = parseFloat(tr.find('.input-vol').val()) || 0;
                            const price = res.new_unit_price;
                            const total = vol * price;
                            tr.find('.row-rab-total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                            calculateGrandTotalRab();
                        }
                    } else {
                        alert('❌ ' + res.message);
                        loadRabMaterials(activeRabId);
                    }
                }).fail(function () {
                    container.css('opacity', '1');
                    alert('Gagal menghapus rekomendasi!');
                    loadRabMaterials(activeRabId);
                });
            });
        });
    }

    let activeAhspBahanId = null;
    let productCurrentPage = 1;
    const productPageSize = 12;

    // Klik tombol untuk membuka modal pencarian produk & supplier
    $(document).on('click', '.btn-open-product-picker', function () {
        const btn = $(this);
        activeAhspBahanId = btn.data('ahsp-bahan-id');
        const bahanName = btn.data('bahan-name') || '';

        // Reset search input ke nama bahan untuk filter awal
        $('#searchProductPicker').val(bahanName);
        $('#modalProductPickerTitle').html(`<i class="fas fa-box-open me-2"></i> Pilih Produk Rekomendasi untuk: <strong>${bahanName}</strong>`);
        productCurrentPage = 1;

        // Sembunyikan modal material utama terlebih dahulu agar tidak bertumpuk
        const parentModal = bootstrap.Modal.getInstance(document.getElementById('modalRabMaterials'));
        if (parentModal) {
            parentModal.hide();
        }

        // Buka modal
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProductPicker'));
        modal.show();

        // Render data
        updateProductPickerPagination();
    });

    // Fitur live search di modal produk
    $('#searchProductPicker').on('input', function () {
        productCurrentPage = 1;
        updateProductPickerPagination();
    });

    // Pindah halaman pagination di modal produk
    $(document).on('click', '#productPaginationList .page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).attr('data-page'));
        if (!isNaN(page)) {
            productCurrentPage = page;
            updateProductPickerPagination();
        }
    });

    function updateProductPickerPagination() {
        const query = $('#searchProductPicker').val().toLowerCase();
        let matchedProducts = [];

        allProducts.forEach(function (p) {
            const name = (p.name || '').toLowerCase();
            const desc = (p.description || '').toLowerCase();
            const supplier = (p.supplier_name || '').toLowerCase();

            if (name.includes(query) || desc.includes(query) || supplier.includes(query)) {
                matchedProducts.push(p);
            }
        });

        const totalItems = matchedProducts.length;
        const totalPages = Math.ceil(totalItems / productPageSize) || 1;

        if (productCurrentPage > totalPages) {
            productCurrentPage = totalPages;
        }
        if (productCurrentPage < 1) {
            productCurrentPage = 1;
        }

        const start = (productCurrentPage - 1) * productPageSize;
        const end = Math.min(start + productPageSize, totalItems);

        let html = '';
        if (totalItems === 0) {
            html = `
                <div class="col-12 text-center text-muted py-5" style="border: 1px dashed #cbd5e1; border-radius: 12px; background: #fafafa; width: 100%;">
                    <i class="fas fa-box-open mb-2" style="font-size: 24px; color: #94a3b8;"></i>
                    <div style="font-size: 13px;">Tidak ada produk yang cocok.</div>
                </div>
            `;
            $('#productPaginationInfo').text('Menampilkan 0 data');
        } else {
            for (let i = start; i < end; i++) {
                const p = matchedProducts[i];
                const photoSrc = p.photo ? (p.photo.indexOf('http') === 0 ? p.photo : '<?= base_url('uploads/products') ?>/' + p.photo) : '';
                const photoHtml = photoSrc ? `<img src="${photoSrc}" style="width: 100%; height: 100%; object-fit: cover;">` : `<i class="fas fa-box text-muted" style="font-size: 20px;"></i>`;

                let stockBadge = '';
                if (p.stock > 10) {
                    stockBadge = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #ecfdf5; color: #065f46; border-color: #a7f3d0 !important; padding: 4px 6px;"><i class="fas fa-check" style="font-size: 8px;"></i> Ready: ${p.stock}</span>`;
                } else if (p.stock > 0) {
                    stockBadge = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #fffbeb; color: #92400e; border-color: #fde68a !important; padding: 4px 6px;"><i class="fas fa-exclamation-triangle" style="font-size: 8px;"></i> Sisa: ${p.stock}</span>`;
                } else {
                    stockBadge = `<span class="badge border d-inline-flex align-items-center gap-1" style="font-size: 9px; border-radius: 6px; background-color: #fef2f2; color: #991b1b; border-color: #fca5a5 !important; padding: 4px 6px;"><i class="fas fa-times" style="font-size: 8px;"></i> Habis</span>`;
                }

                const pRating = parseFloat(p.rata_rata_rating) || 5.0;
                const pReviews = parseInt(p.total_ulasan) || 0;

                html += `
                    <div class="col">
                        <div class="card h-100 product-picker-card d-flex flex-column" data-id="${p.id}">
                            <!-- Foto Produk -->
                            <div style="position: relative; width: 100%; height: 110px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid #f1f5f9;">
                                ${photoHtml}
                            </div>
                            <!-- Detail Produk -->
                            <div class="card-body p-2 d-flex flex-column flex-grow-1">
                                <!-- Nama Produk (Max 2 Baris) -->
                                <h6 class="text-dark mb-1 text-truncate-2" style="font-size: 12px; font-family: 'Outfit', sans-serif; line-height: 1.4; height: 34px; overflow: hidden; font-weight: 500;" title="${p.name}">
                                    ${p.name}
                                </h6>
                                
                                <!-- Nama Supplier -->
                                <div class="text-secondary text-truncate mb-2" style="font-size: 11px; font-family: 'Outfit', sans-serif;" title="${p.supplier_name || 'Tanpa Supplier'}">
                                    <i class="fas fa-store text-primary me-1" style="font-size: 10px;"></i>${p.supplier_name || 'Tanpa Supplier'}
                                </div>
                                
                                <!-- Harga -->
                                <div style="color: var(--palette-primary); font-size: 14px; font-weight: 700; font-family: 'Outfit', sans-serif;">
                                    Rp${Number(p.price).toLocaleString('id-ID')}
                                </div>
                                
                                <div class="mt-auto">
                                    <!-- Rating & Stok -->
                                    <div class="d-flex align-items-center gap-1" style="font-size: 10px; color: #757575;">
                                        <span class="text-warning"><i class="fas fa-star" style="font-size: 9px;"></i></span>
                                        <span style="color: #ffb900; font-weight: 600;">${pRating.toFixed(1)}</span>
                                        <span style="color: #ccc;">|</span>
                                        <span>Ready: ${p.stock}</span>
                                    </div>
                                    
                                    <!-- Lokasi Supplier -->
                                    <div class="mt-2 text-end text-muted" style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.02em;">
                                        <i class="fas fa-map-marker-alt me-1" style="font-size: 8px; color: #999;"></i>${(p.supplier_city || 'Luar Kota').trim()}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            $('#productPaginationInfo').text(`Menampilkan ${start + 1} - ${end} dari ${totalItems} data`);
        }

        $('#productPickerGrid').html(html);

        // Render pagination controls
        let paginationHtml = '';
        paginationHtml += `
            <li class="page-item ${productCurrentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${productCurrentPage - 1}" aria-label="Previous" style="color: var(--palette-primary); border-radius: 6px;">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= productCurrentPage - 1 && i <= productCurrentPage + 1)) {
                const isActive = (productCurrentPage === i);
                paginationHtml += `
                    <li class="page-item ${isActive ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}" style="${isActive ? 'background-color: var(--palette-primary); border-color: var(--palette-primary); color: white;' : 'color: var(--palette-primary);'} border-radius: 6px; margin: 0 2px;">${i}</a>
                    </li>
                `;
            } else if (i === 2 || i === totalPages - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link" style="border: none; background: transparent;">...</span></li>`;
            }
        }

        paginationHtml += `
            <li class="page-item ${productCurrentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${productCurrentPage + 1}" aria-label="Next" style="color: var(--palette-primary); border-radius: 6px;">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        $('#productPaginationList').html(paginationHtml);
    }

    // Klik kartu di modal produk picker untuk memilih produk
    $(document).on('click', '.product-picker-card', function () {
        const card = $(this);
        const productId = card.data('id');

        if (!productId || !activeAhspBahanId) return;

        // Cegah klik ganda selama pemrosesan
        card.css('pointer-events', 'none');

        $.post('<?= base_url('admin/design/add_rab_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rab_id: activeRabId,
            ahsp_bahan_id: activeAhspBahanId,
            product_id: productId
        }, function (res) {
            card.css('pointer-events', 'auto');
            if (res.status) {
                // Sembunyikan modal picker produk
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalProductPicker'));
                if (modal) modal.hide();

                // Refresh modal material utama
                loadRabMaterials(activeRabId);

                // Update harga satuan baris RAB
                const tr = $(`#rabBody tr[data-id="${activeRabId}"]`);
                if (tr.length > 0) {
                    tr.find('.input-price').val(res.formatted_new_unit_price);

                    // Hitung ulang subtotal baris
                    const vol = parseFloat(tr.find('.input-vol').val()) || 0;
                    const price = res.new_unit_price;
                    const total = vol * price;
                    tr.find('.row-rab-total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                    // Hitung ulang grand total
                    calculateGrandTotalRab();
                }
            } else {
                alert('❌ ' + res.message);
            }
        }).fail(function () {
            card.css('pointer-events', 'auto');
            alert('Gagal menambahkan rekomendasi produk!');
        });
    });

    // Ketika modal pencarian produk ditutup, tampilkan kembali modal material utama
    $(document).on('hidden.bs.modal', '#modalProductPicker', function () {
        const parentModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRabMaterials'));
        parentModal.show();
    });

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
            url: '<?= base_url('admin/design/import-rab-excel/' . $request['id']) ?>',
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

    const allProducts = <?= json_encode($all_products) ?>;
    let currentTaskInputTarget = null;

    let ahspCurrentPage = 1;
    const ahspPageSize = 10;

    // Klik input pekerjaan untuk merekam input target yang aktif
    $(document).on('click', '.input-rab-task-picker', function () {
        if (isLocked) return;
        currentTaskInputTarget = $(this);

        // Reset input search & reset page
        $('#searchAhspPicker').val('');
        ahspCurrentPage = 1;
        updateAhspPickerPagination();
    });

    // Fitur Live Search di Modal Picker
    $('#searchAhspPicker').on('input', function () {
        ahspCurrentPage = 1; // reset page on search
        updateAhspPickerPagination();
    });

    // Handle pagination page clicks
    $(document).on('click', '#pickerPaginationList .page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).attr('data-page'));
        if (!isNaN(page)) {
            ahspCurrentPage = page;
            updateAhspPickerPagination();
        }
    });

    function updateAhspPickerPagination() {
        const query = $('#searchAhspPicker').val().toLowerCase();
        let matchedRows = [];

        // Cari baris utama yang cocok dengan query
        $('#tableAhspPicker tbody tr.ahsp-picker-row').each(function () {
            const row = $(this);
            const kode = (row.attr('data-kode') || '').toLowerCase();
            const uraian = (row.attr('data-uraian') || '').toLowerCase();

            if (kode.includes(query) || uraian.includes(query)) {
                matchedRows.push(row);
            }
        });

        const totalItems = matchedRows.length;
        const totalPages = Math.ceil(totalItems / ahspPageSize) || 1;

        // Jaga index halaman tetap di batas valid
        if (ahspCurrentPage > totalPages) {
            ahspCurrentPage = totalPages;
        }
        if (ahspCurrentPage < 1) {
            ahspCurrentPage = 1;
        }

        // Sembunyikan semua baris utama & baris detail terlebih dahulu
        $('#tableAhspPicker tbody tr.ahsp-picker-row').hide();
        $('#tableAhspPicker tbody tr.ahsp-detail-row').hide();
        // Reset rotasi chevron
        $('#tableAhspPicker tbody tr.ahsp-picker-row .toggle-icon').css('transform', 'none');

        // Tampilkan potongan baris untuk halaman aktif
        const start = (ahspCurrentPage - 1) * ahspPageSize;
        const end = Math.min(start + ahspPageSize, totalItems);

        for (let i = start; i < end; i++) {
            matchedRows[i].show();
        }

        // Penanganan baris kosong jika 0 pencocokan
        const emptyRow = $('#tableAhspPicker tbody tr.empty-placeholder-row');
        if (totalItems === 0) {
            if (emptyRow.length === 0) {
                $('#tableAhspPicker tbody').append(`
                    <tr class="empty-placeholder-row">
                        <td colspan="4" class="text-center text-muted" style="padding: 20px;">Tidak ada data AHSP yang cocok.</td>
                    </tr>
                `);
            } else {
                emptyRow.show();
            }
            $('#paginationInfo').text('Menampilkan 0 data');
        } else {
            if (emptyRow.length > 0) {
                emptyRow.hide();
            }
            $('#paginationInfo').text(`Menampilkan ${start + 1} - ${end} dari ${totalItems} data`);
        }

        // Render tombol pagination
        let paginationHtml = '';

        // Tombol Sebelumnya
        paginationHtml += `
            <li class="page-item ${ahspCurrentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${ahspCurrentPage - 1}" aria-label="Previous" style="color: var(--palette-primary); border-radius: 6px;">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        // Tombol Halaman Angka
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= ahspCurrentPage - 1 && i <= ahspCurrentPage + 1)) {
                const isActive = (ahspCurrentPage === i);
                paginationHtml += `
                    <li class="page-item ${isActive ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}" style="${isActive ? 'background-color: var(--palette-primary); border-color: var(--palette-primary); color: white;' : 'color: var(--palette-primary);'} border-radius: 6px; margin: 0 2px;">${i}</a>
                    </li>
                `;
            } else if (i === 2 || i === totalPages - 1) {
                paginationHtml += `<li class="page-item disabled"><span class="page-link" style="border: none; background: transparent;">...</span></li>`;
            }
        }

        // Tombol Selanjutnya
        paginationHtml += `
            <li class="page-item ${ahspCurrentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${ahspCurrentPage + 1}" aria-label="Next" style="color: var(--palette-primary); border-radius: 6px;">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        $('#pickerPaginationList').html(paginationHtml);
    }

    // Klik baris tabel AHSP untuk memilih pekerjaan (hanya lewat kolom text/aksi, bukan chevron detail)
    $(document).on('click', '#tableAhspPicker tbody tr.ahsp-picker-row .select-row-action, #tableAhspPicker tbody tr.ahsp-picker-row .btn-select-ahsp', function (e) {
        const row = $(this).closest('tr.ahsp-picker-row');
        const id = row.attr('data-id');
        const uraian = row.attr('data-uraian');
        const satuan = row.attr('data-satuan');

        if (currentTaskInputTarget) {
            currentTaskInputTarget.val(uraian);
            currentTaskInputTarget.data('ahsp-id', id);
            currentTaskInputTarget.attr('data-ahsp-id', id);

            const rowTarget = currentTaskInputTarget.closest('tr');

            // Set unit (satuan) otomatis
            const selectUnit = rowTarget.find('.input-rab-unit');
            if (selectUnit.length > 0 && satuan) {
                selectUnit.val(satuan);
                if (selectUnit.val() !== satuan) {
                    selectUnit.append(new Option(satuan, satuan, true, true));
                }
            }

            // Hitung harga otomatis dari detail AHSP
            $.get('<?= base_url('admin/ahsp/show') ?>/' + id, function (res) {
                if (res.status && res.data) {
                    const ahsp = res.data;
                    let totalTenaga = 0;
                    if (ahsp.tenaga_kerja) {
                        ahsp.tenaga_kerja.forEach(function (tk) {
                            totalTenaga += parseFloat(tk.koefisien || 0) * parseFloat(tk.harga_satuan || 0);
                        });
                    }

                    let totalBahan = 0;
                    if (ahsp.bahan) {
                        ahsp.bahan.forEach(function (b) {
                            let matchedProduct = null;
                            const bahanUraianClean = (b.uraian || '').toLowerCase().trim();

                            for (let i = 0; i < allProducts.length; i++) {
                                const p = allProducts[i];
                                const pNameClean = (p.name || '').toLowerCase().trim();
                                if (pNameClean === bahanUraianClean || pNameClean.includes(bahanUraianClean) || bahanUraianClean.includes(pNameClean)) {
                                    matchedProduct = p;
                                    break;
                                }
                            }

                            if (matchedProduct) {
                                totalBahan += parseFloat(b.koefisien || 0) * parseFloat(matchedProduct.price || 0);
                            }
                        });
                    }

                    const totalHargaAHSP = totalTenaga + totalBahan;
                    const priceInput = rowTarget.find('.input-price');
                    priceInput.val(totalHargaAHSP.toLocaleString('id-ID'));
                    calculateGrandTotalRab();
                }
            });

            // Tutup modal
            const modalEl = document.getElementById('modalAhspPicker');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    });

    // Toggle detail accordion di Modal AHSP Picker
    $(document).on('click', '#tableAhspPicker tbody tr.ahsp-picker-row .toggle-details', function (e) {
        e.stopPropagation(); // Stop selection trigger
        const row = $(this).closest('tr');
        const detailRow = row.next('.ahsp-detail-row');
        const icon = $(this).find('.toggle-icon');

        detailRow.slideToggle(200);

        // Rotate icon
        if (icon.css('transform') === 'none' || icon.css('transform') === 'matrix(1, 0, 0, 1, 0, 0)') {
            icon.css('transform', 'rotate(90deg)');
        } else {
            icon.css('transform', 'none');
        }
    });

    /* ── AHSP Detail Modal ── */
    function openAhspDetailModal(ahspId, activityName, rabId, rabVolume) {
        if (!ahspId) {
            alert('Pekerjaan belum dipilih.');
            return;
        }

        $('#modalAhspDetailTitle').html('<i class="fas fa-clipboard-list me-2"></i> Detail AHSP: <strong>' + activityName + '</strong>');
        $('#modalAhspDetailBody').html(`
            <div class="text-center py-5 text-muted">
                <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #94a3b8;"></i>
                <div class="mt-3" style="font-size: 13px; font-family: 'Outfit', sans-serif;">Memuat data AHSP...</div>
            </div>
        `);

        const modal = new bootstrap.Modal(document.getElementById('modalAhspDetail'));
        modal.show();

        // Fetch AHSP data & materials data in parallel
        const ahspReq = $.get('<?= base_url('admin/ahsp/show') ?>/' + ahspId);
        const matsReq = rabId ? $.get('<?= base_url('admin/design/get_rab_materials') ?>/' + rabId) : $.Deferred().resolve(null).promise();

        $.when(ahspReq, matsReq).done(function (ahspArgs, matsArgs) {
            const res = Array.isArray(ahspArgs) ? ahspArgs[0] : ahspArgs;
            const matsRaw = matsArgs ? (Array.isArray(matsArgs) ? matsArgs[0] : matsArgs) : null;

            if (!res || !res.status || !res.data) {
                $('#modalAhspDetailBody').html(`
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-exclamation-circle" style="font-size: 28px; color: #ef4444;"></i>
                        <div class="mt-3" style="font-size: 13px;">Gagal memuat data AHSP.</div>
                    </div>
                `);
                return;
            }

            const ahsp = res.data;

            // Retrieve the volume value
            let volume = parseFloat(rabVolume) || 0;
            if (rabId) {
                const rowVolInput = $('tr[data-id="' + rabId + '"]').find('.input-vol');
                if (rowVolInput.length) {
                    const liveVol = parseFloat(rowVolInput.val());
                    if (!isNaN(liveVol)) {
                        volume = liveVol;
                    }
                }
            }

            // Build price map: ahsp_bahan_id -> selected product price
            const priceMap = {};
            const productNameMap = {};
            if (Array.isArray(matsRaw)) {
                matsRaw.forEach(function (mat) {
                    if (mat.recommendations && mat.recommendations.length > 0) {
                        const sel = mat.recommendations.find(r => r.selected);
                        if (sel) {
                            priceMap[mat.ahsp_bahan_id] = parseFloat(sel.product_price) || 0;
                            productNameMap[mat.ahsp_bahan_id] = sel.product_name || '';
                        }
                    }
                });
            }

            /* ── Build Bahan table ── */
            let bahanRows = '';
            let bahanTotal = 0;
            let grandTotalHargaBahan = 0;
            const hasPrices = Object.keys(priceMap).length > 0;

            if (ahsp.bahan && ahsp.bahan.length > 0) {
                ahsp.bahan.forEach(function (b, i) {
                    const hargaSatuan = priceMap[b.id] || 0;
                    const jumlah = parseFloat(b.koefisien || 0) * hargaSatuan;
                    bahanTotal += jumlah;

                    let totalHarga = 0;
                    let hargaCell = '';

                    if (hargaSatuan > 0) {
                        totalHarga = jumlah * volume;
                        grandTotalHargaBahan += totalHarga;
                        hargaCell = `
                            <td class="text-end font-monospace" style="font-size:11px;">Rp ${hargaSatuan.toLocaleString('id-ID')}</td>
                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:11px;">Rp ${jumlah.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:11px;">Rp ${totalHarga.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                        `;
                    } else {
                        hargaCell = `
                            <td class="text-center text-muted" style="font-size:10px;"><span class="badge" style="background:#f1f5f9;color:#94a3b8;font-size:9px;">Belum dipilih</span></td>
                            <td class="text-center text-muted" style="font-size:10px;">—</td>
                            <td class="text-center text-muted" style="font-size:10px;">—</td>
                        `;
                    }

                    bahanRows += `
                        <tr>
                            <td class="text-center text-muted" style="font-size:11px;">${i + 1}</td>
                            <td style="font-size:12px; font-weight:500;">${b.uraian || '-'}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border" style="font-size:10px; border-color:#cbd5e1 !important;">${b.satuan || '-'}</span></td>
                            <td class="text-end font-monospace fw-bold text-primary" style="font-size:11px;">${parseFloat(b.koefisien || 0).toFixed(4).replace('.', ',')}</td>
                            ${hargaCell}
                        </tr>
                    `;
                });
            } else {
                bahanRows = `<tr><td colspan="7" class="text-center text-muted" style="font-size:12px; padding:16px;">Tidak membutuhkan bahan/material.</td></tr>`;
            }

            /* ── Build Tenaga Kerja table ── */
            let tkRows = '';
            let tkTotal = 0;
            let grandTotalHargaTk = 0;
            if (ahsp.tenaga_kerja && ahsp.tenaga_kerja.length > 0) {
                ahsp.tenaga_kerja.forEach(function (t, i) {
                    const jumlah = parseFloat(t.koefisien || 0) * parseFloat(t.harga_satuan || 0);
                    tkTotal += jumlah;
                    const totalHarga = jumlah * volume;
                    grandTotalHargaTk += totalHarga;
                    tkRows += `
                        <tr>
                            <td class="text-center text-muted" style="font-size:11px;">${i + 1}</td>
                            <td style="font-size:12px; font-weight:500;">${t.uraian || '-'}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border" style="font-size:10px; border-color:#cbd5e1 !important;">${t.satuan || '-'}</span></td>
                            <td class="text-end font-monospace" style="font-size:11px;">${parseFloat(t.koefisien || 0).toFixed(4).replace('.', ',')}</td>
                            <td class="text-end font-monospace" style="font-size:11px;">Rp ${Number(t.harga_satuan || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:11px;">Rp ${jumlah.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                            <td class="text-end font-monospace fw-bold text-dark" style="font-size:11px;">Rp ${totalHarga.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                        </tr>
                    `;
                });
            } else {
                tkRows = `<tr><td colspan="7" class="text-center text-muted" style="font-size:12px; padding:16px;">Tidak membutuhkan tenaga kerja.</td></tr>`;
            }

            const thStyle = `padding:10px 12px;font-size:10px;text-transform:uppercase;letter-spacing:.05em;color:#64748b;font-weight:700;`;
            const thCtrStyle = thStyle + `width:40px;`;

            const html = `
                <!-- AHSP Info Card -->
                <div class="mb-4 p-4" style="background:#ffffff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                    <div class="d-flex align-items-start gap-4 flex-wrap">
                        <div style="flex:0 0 auto;">
                            <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#1e293b,#334155);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-clipboard-list" style="color:#ffffff;font-size:22px;"></i>
                            </div>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="fw-bold" style="font-size:16px; color:#0f172a; font-family:'Outfit',sans-serif; word-break:break-word;">${ahsp.uraian || '-'}</div>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                <span class="d-inline-flex align-items-center gap-1" style="font-size:12px; color:#64748b;">
                                    <i class="fas fa-hashtag text-primary" style="font-size:11px;"></i>
                                    <strong>Kode:</strong>&nbsp;${ahsp.kode || '-'}
                                </span>
                                ${ahsp.satuan ? `<span class="d-inline-flex align-items-center gap-1" style="font-size:12px; color:#64748b;"><i class="fas fa-ruler text-primary" style="font-size:11px;"></i> <strong>Satuan:</strong>&nbsp;${ahsp.satuan}</span>` : ''}
                                ${bahanTotal > 0 ? `<span class="d-inline-flex align-items-center gap-1" style="font-size:12px; color:#64748b;"><i class="fas fa-boxes text-primary" style="font-size:11px;"></i> <strong>Total Bahan:</strong>&nbsp;<span class="fw-bold text-dark font-monospace">Rp ${bahanTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span></span>` : ''}
                                ${tkTotal > 0 ? `<span class="d-inline-flex align-items-center gap-1" style="font-size:12px; color:#64748b;"><i class="fas fa-coins text-warning" style="font-size:11px;"></i> <strong>Total Tenaga Kerja:</strong>&nbsp;<span class="fw-bold text-dark font-monospace">Rp ${tkTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span></span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accordion: Bahan -->
                <div class="accordion mb-3" id="accordionDetailAhsp">
                    <div class="accordion-item" style="border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom:12px;">
                        <h2 class="accordion-header" id="headingDetailBahan">
                            <button class="accordion-button collapsed fw-semibold py-3 px-4" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseDetailBahan"
                                aria-expanded="false" aria-controls="collapseDetailBahan"
                                style="font-size:13px; font-family:'Outfit',sans-serif; background:#ffffff; color:#0f172a; border:none; box-shadow:none;">
                                <i class="fas fa-boxes me-2 text-primary"></i>
                                Rincian Bahan / Material
                                <span class="badge ms-2" style="background:#eff6ff;color:#3b82f6;font-size:10px;border-radius:6px;padding:3px 8px;">${(ahsp.bahan || []).length} item</span>
                            </button>
                        </h2>
                        <div id="collapseDetailBahan" class="accordion-collapse collapse" aria-labelledby="headingDetailBahan">
                            <div class="accordion-body p-0" style="background:#fafbfc;">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle m-0" style="font-size:12px;">
                                        <thead style="background:#f1f5f9;">
                                            <tr>
                                                <th style="${thCtrStyle}" class="text-center">No</th>
                                                <th style="${thStyle}">Nama Bahan</th>
                                                <th style="${thStyle}width:90px;" class="text-center">Satuan</th>
                                                <th style="${thStyle}width:110px;" class="text-end">Koefisien</th>
                                                <th style="${thStyle}width:140px;" class="text-end">Harga Satuan</th>
                                                <th style="${thStyle}width:140px;" class="text-end">Harga</th>
                                                <th style="${thStyle}width:140px;" class="text-end">Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${bahanRows}
                                            ${bahanTotal > 0 ? `
                                            <tr style="background:#f0f9ff; border-top:2px solid #bae6fd;">
                                                <td colspan="5" class="text-end fw-bold text-uppercase" style="font-size:10px; color:#0369a1; padding:10px 12px; letter-spacing:.04em;">Total Bahan</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="font-size:12px; padding:10px 12px;">Rp ${bahanTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="font-size:12px; padding:10px 12px;">Rp ${grandTotalHargaBahan.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                            </tr>` : ''}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion: Tenaga Kerja -->
                    <div class="accordion-item" style="border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
                        <h2 class="accordion-header" id="headingDetailTenaga">
                            <button class="accordion-button collapsed fw-semibold py-3 px-4" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseDetailTenaga"
                                aria-expanded="false" aria-controls="collapseDetailTenaga"
                                style="font-size:13px; font-family:'Outfit',sans-serif; background:#ffffff; color:#0f172a; border:none; box-shadow:none;">
                                <i class="fas fa-hard-hat me-2 text-warning"></i>
                                Rincian Tenaga Kerja
                                <span class="badge ms-2" style="background:#fffbeb;color:#d97706;font-size:10px;border-radius:6px;padding:3px 8px;">${(ahsp.tenaga_kerja || []).length} item</span>
                            </button>
                        </h2>
                        <div id="collapseDetailTenaga" class="accordion-collapse collapse" aria-labelledby="headingDetailTenaga">
                            <div class="accordion-body p-0" style="background:#fafbfc;">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle m-0" style="font-size:12px;">
                                        <thead style="background:#f1f5f9;">
                                            <tr>
                                                <th style="${thCtrStyle}" class="text-center">No</th>
                                                <th style="${thStyle}">Klasifikasi Pekerja</th>
                                                <th style="${thStyle}width:80px;" class="text-center">Satuan</th>
                                                <th style="${thStyle}width:90px;" class="text-end">Koefisien</th>
                                                <th style="${thStyle}width:120px;" class="text-end">Harga Satuan</th>
                                                <th style="${thStyle}width:130px;" class="text-end">Jumlah</th>
                                                <th style="${thStyle}width:140px;" class="text-end">Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tkRows}
                                            ${tkTotal > 0 ? `
                                            <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                                                <td colspan="5" class="text-end fw-bold text-uppercase" style="font-size:10px; color:#475569; padding:10px 12px; letter-spacing:.04em;">Total Tenaga Kerja</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="font-size:12px; padding:10px 12px;">Rp ${tkTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="font-size:12px; padding:10px 12px;">Rp ${grandTotalHargaTk.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                            </tr>` : ''}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hitung Ulang Harga Button -->
                ${(!isLocked && rabId) ? `
                <div class="mt-4 pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-top: 1px solid #e2e8f0;">
                    <div style="font-size:11px; color:#64748b;">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        Harga (Rp) = <strong>Total Bahan</strong> + <strong>Total Tenaga Kerja</strong>
                    </div>
                    <button type="button" id="btnRecalcAhspPrice" class="btn-adm btn-adm-primary" onclick="recalculateAhspRabPrice(${rabId})">
                        <i class="fas fa-sync-alt me-1"></i> Hitung Ulang Harga
                    </button>
                </div>
                ` : ''}
            `;

            $('#modalAhspDetailBody').html(html);

        }).fail(function () {
            $('#modalAhspDetailBody').html(`
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-exclamation-triangle" style="font-size: 28px; color: #f59e0b;"></i>
                    <div class="mt-3" style="font-size: 13px;">Gagal terhubung ke server. Silakan coba lagi.</div>
                </div>
            `);
        });
    }

    /* ── Recalculate RAB Row Price from AHSP Detail Modal ── */
    function recalculateAhspRabPrice(rabId) {
        const btn = $('#btnRecalcAhspPrice');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menghitung...');

        $.get('<?= base_url('admin/design/recalculate_rab_price') ?>/' + rabId, function (res) {
            if (res.status) {
                const tr = $(`#rabBody tr[data-id="${rabId}"]`);
                if (tr.length > 0) {
                    tr.find('.input-price').val(res.formatted_new_unit_price);
                    const vol = parseFloat(tr.find('.input-vol').val()) || 0;
                    const price = parseFloat(res.new_unit_price) || 0;
                    const total = vol * price;
                    tr.find('.row-rab-total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    calculateGrandTotalRab();
                }
                btn.html('<i class="fas fa-check-circle me-1" style="color:#22c55e;"></i> Harga Diperbarui!');
                setTimeout(function () {
                    btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Hitung Ulang Harga');
                }, 2500);
            } else {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Hitung Ulang Harga');
                alert('Gagal: ' + (res.message || 'Terjadi kesalahan.'));
            }
        }).fail(function () {
            btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Hitung Ulang Harga');
            alert('Gagal terhubung ke server.');
        });
    }

    function triggerNewRowDetail(btn) {
        const tr = $(btn).closest('tr');
        const ahspId = tr.find('.input-rab-task').data('ahsp-id') || tr.find('.input-rab-task').attr('data-ahsp-id');
        if (!ahspId) {
            alert('⚠️ Harap pilih pekerjaan (AHSP) terlebih dahulu.');
            return;
        }
        const taskName = tr.find('.input-rab-task').val();
        const vol = parseFloat(tr.find('.input-vol').val()) || 1;
        const rabId = tr.attr('data-id');
        openAhspDetailModal(ahspId, taskName, rabId === '0' ? 0 : rabId, vol);
    }

    function triggerNewRowMaterial(btn) {
        const tr = $(btn).closest('tr');
        const rabId = tr.attr('data-id');
        if (rabId === '0' || !rabId) {
            alert('⚠️ Harap klik "Simpan Draf" terlebih dahulu untuk menyimpan pekerjaan baru ini sebelum memilih bahan.');
            return;
        }
        const taskName = tr.find('.input-rab-task').val();
        openRabMaterialModal(rabId, taskName);
    }
</script>
