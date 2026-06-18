<script>
    function fillInvoiceForm(description, amount, rabId, el) {
        $('#invoice_description').val(description);
        $('#invoice_amount').val(amount);
        $('#invoice_amount_visible').val(amount.toLocaleString('id-ID'));
        $('#invoice_rab_id').val(rabId || '');
        document.querySelectorAll('.rab-click-row').forEach(function (r) {
            r.classList.remove('rab-selected');
        });
        if (el) el.classList.add('rab-selected');
        var info = document.getElementById('selectedRabInfo');
        var name = document.getElementById('selectedRabName');
        if (info) info.style.display = 'block';
        if (name) name.textContent = description;
        
        // Membuka Modal
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCreateInvoice'));
            myModal.show();
        } else {
            $('#modalCreateInvoice').modal('show');
        }
        
        setTimeout(function () {
            var input = document.getElementById('invoice_description');
            if (input) input.focus();
        }, 150);
    }

    function toggleRabGroup(id, headerEl) {
        var el = document.getElementById(id);
        if (!el) return;
        var chev = headerEl ? headerEl.querySelector('i.fa-chevron-down') : null;
        var isOpen = el.classList.contains('show');
        if (isOpen) {
            el.classList.remove('show');
            if (chev) chev.style.transform = 'rotate(-90deg)';
        } else {
            el.classList.add('show');
            if (chev) chev.style.transform = 'rotate(0deg)';
        }
    }

    function clearInvoiceForm() {
        $('#invoice_description').val('');
        $('#invoice_amount').val('');
        $('#invoice_amount_visible').val('');
        $('#invoice_rab_id').val('');
        document.querySelectorAll('.rab-click-row').forEach(function (r) {
            r.classList.remove('rab-selected');
        });
        var info = document.getElementById('selectedRabInfo');
        if (info) info.style.display = 'none';
    }

    // ── Currency Format ──
    function formatCurrencyInput(el) {
        let raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        let hidden = el.id === 'invoice_amount_visible' ? document.getElementById('invoice_amount') : null;
        if (hidden) hidden.value = raw;
    }

    // ── Toggle RAB Detail Accordion ──
    function toggleRabDetail(rabId, ahspId, activityName, rabVolume, rowEl) {
        if (!ahspId) {
            return;
        }

        const detailRowId = 'rab-detail-' + rabId;
        let detailRow = document.getElementById(detailRowId);

        if (detailRow) {
            $(detailRow).slideToggle(200);
            return;
        }

        // Create detail row
        const colCount = $(rowEl).children('td').length;
        const newRowHtml = `
            <tr id="${detailRowId}" class="bg-light align-middle text-wrap" style="display:none;">
                <td colspan="${colCount}" style="padding: 15px 20px;">
                    <div class="card border-0 shadow-sm" style="border-radius:12px;">
                        <div class="card-body p-4" id="rab-detail-body-${rabId}">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #3b82f6;"></i>
                                <div class="mt-2 fw-medium" style="font-size:12px;">Memuat rincian pekerjaan & material...</div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        `;

        $(rowEl).after(newRowHtml);
        $('#' + detailRowId).slideDown(200);

        // Fetch AHSP data & materials data in parallel
        const ahspReq = $.get('<?= base_url('admin/ahsp/show') ?>/' + ahspId);
        const matsReq = rabId ? $.get('<?= base_url('admin/construction/get_rab_materials') ?>/' + rabId) : $.Deferred().resolve(null).promise();

        $.when(ahspReq, matsReq).done(function (ahspArgs, matsArgs) {
            const res = Array.isArray(ahspArgs) ? ahspArgs[0] : ahspArgs;
            const matsRaw = matsArgs ? (Array.isArray(matsArgs) ? matsArgs[0] : matsArgs) : null;

            if (!res || !res.status || !res.data) {
                $('#rab-detail-body-' + rabId).html(`
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle" style="font-size: 24px;"></i>
                        <div class="mt-2 fw-bold" style="font-size:12px;">Gagal memuat rincian pekerjaan.</div>
                    </div>
                `);
                return;
            }

            const ahsp = res.data;
            const volume = parseFloat(rabVolume) || 0;

            // Build price map: ahsp_bahan_id -> selected product info
            const priceMap = {};
            const productNameMap = {};
            const productUnitMap = {};
            if (Array.isArray(matsRaw)) {
                matsRaw.forEach(function (mat) {
                    if (mat.recommendations && mat.recommendations.length > 0) {
                        const sel = mat.recommendations.find(r => r.selected);
                        if (sel) {
                            priceMap[mat.ahsp_bahan_id] = parseFloat(sel.product_price) || 0;
                            productNameMap[mat.ahsp_bahan_id] = sel.product_name || '';
                            productUnitMap[mat.ahsp_bahan_id] = sel.product_unit || '';
                        }
                    }
                });
            }

            /* ── Build Bahan table ── */
            let bahanRows = '';
            let bahanTotal = 0;
            let grandTotalHargaBahan = 0;
            const bahanCount = (ahsp.bahan || []).length;

            if (ahsp.bahan && ahsp.bahan.length > 0) {
                ahsp.bahan.forEach(function (b, i) {
                    const hargaSatuan = priceMap[b.id] || 0;
                    const prodName = productNameMap[b.id] || '';
                    const prodUnit = productUnitMap[b.id] || '';
                    const jumlah = parseFloat(b.koefisien || 0) * hargaSatuan;
                    bahanTotal += jumlah;

                    let totalHarga = 0;
                    let hargaCell = '';

                    if (hargaSatuan > 0) {
                        totalHarga = jumlah * volume;
                        grandTotalHargaBahan += totalHarga;
                        hargaCell = `
                            <td class="text-end font-monospace">Rp ${hargaSatuan.toLocaleString('id-ID')}</td>
                            <td class="text-end font-monospace fw-bold text-dark">Rp ${jumlah.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                            <td class="text-end font-monospace fw-bold text-dark">Rp ${totalHarga.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                        `;
                    } else {
                        hargaCell = `
                            <td class="text-center text-muted"><span class="badge bg-light text-secondary border" style="font-size:9px;">Belum dipilih</span></td>
                            <td class="text-center text-muted">—</td>
                            <td class="text-center text-muted">—</td>
                        `;
                    }

                    const productBadge = prodName 
                        ? `<span class="badge border bg-white text-dark text-start d-block text-wrap" style="border-color: #cbd5e1 !important; font-weight: 500; font-size:11px;"><i class="fas fa-check-circle text-success me-1"></i>${prodName} (${prodUnit})</span>`
                        : `<span class="badge bg-warning-light text-warning border border-warning-light d-inline-block text-start" style="font-size:10px;"><i class="fas fa-exclamation-triangle me-1"></i>Belum terhubung</span>`;

                    bahanRows += `
                        <tr>
                            <td class="text-center text-muted">${i + 1}</td>
                            <td class="fw-semibold text-dark">${b.uraian || '-'}</td>
                            <td>${productBadge}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border">${b.satuan || '-'}</span></td>
                            <td class="text-end font-monospace fw-bold text-primary">${parseFloat(b.koefisien || 0).toFixed(4).replace('.', ',')}</td>
                            ${hargaCell}
                        </tr>
                    `;
                });
            } else {
                bahanRows = `<tr><td colspan="8" class="text-center text-muted py-3">Tidak membutuhkan bahan/material.</td></tr>`;
            }

            /* ── Build Tenaga Kerja table ── */
            let tkRows = '';
            let tkTotal = 0;
            let grandTotalHargaTk = 0;
            const tenagaCount = (ahsp.tenaga_kerja || []).length;

            if (ahsp.tenaga_kerja && ahsp.tenaga_kerja.length > 0) {
                ahsp.tenaga_kerja.forEach(function (t, i) {
                    const jumlah = parseFloat(t.koefisien || 0) * parseFloat(t.harga_satuan || 0);
                    tkTotal += jumlah;
                    const totalHarga = jumlah * volume;
                    grandTotalHargaTk += totalHarga;
                    tkRows += `
                        <tr>
                            <td class="text-center text-muted">${i + 1}</td>
                            <td class="fw-semibold text-dark">${t.uraian || '-'}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border">${t.satuan || '-'}</span></td>
                            <td class="text-end font-monospace">${parseFloat(t.koefisien || 0).toFixed(4).replace('.', ',')}</td>
                            <td class="text-end font-monospace">Rp ${Number(t.harga_satuan || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end font-monospace fw-bold text-dark">Rp ${jumlah.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                            <td class="text-end font-monospace fw-bold text-dark">Rp ${totalHarga.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                        </tr>
                    `;
                });
            } else {
                tkRows = `<tr><td colspan="7" class="text-center text-muted py-3">Tidak membutuhkan tenaga kerja.</td></tr>`;
            }

            const accordionId = 'accordionDetailAhsp-' + rabId;
            const collapseBahanId = 'collapseBahan-' + rabId;
            const collapseTenagaId = 'collapseTenaga-' + rabId;

            const html = `
                <div style="font-size:13px; color:#1e293b;" class="mb-3">
                    <strong>Pekerjaan:</strong> ${activityName} &bull; <strong>Volume:</strong> ${volume.toLocaleString('id-ID')}
                </div>
                
                <div class="accordion" id="${accordionId}">
                    <!-- Accordion Bahan -->
                    <div class="accordion-item mb-2" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff;">
                        <h2 class="accordion-header" id="headingBahan-${rabId}">
                            <button class="accordion-button collapsed py-2 px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseBahanId}" aria-expanded="false" aria-controls="${collapseBahanId}" style="font-size: 12px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                <i class="fas fa-boxes me-2 text-primary"></i> Rincian Bahan / Material (${bahanCount})
                            </button>
                        </h2>
                        <div id="${collapseBahanId}" class="accordion-collapse collapse" aria-labelledby="headingBahan-${rabId}" data-bs-parent="#${accordionId}">
                            <div class="accordion-body p-0" style="background: #fff;">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 11px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 40px;">No</th>
                                                <th>Nama Bahan</th>
                                                <th style="width: 200px;">Produk Terpilih</th>
                                                <th class="text-center" style="width: 70px;">Satuan</th>
                                                <th class="text-end" style="width: 80px;">Koefisien</th>
                                                <th class="text-end" style="width: 110px;">Harga Satuan</th>
                                                <th class="text-end" style="width: 110px;">Jumlah</th>
                                                <th class="text-end" style="width: 110px;">Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${bahanRows}
                                            ${bahanTotal > 0 ? `
                                            <tr style="background:#f0f9ff; border-top:2px solid #bae6fd;">
                                                <td colspan="5" class="text-end fw-bold text-uppercase text-primary" style="font-size: 10px; padding: 8px 10px;">Total Bahan (AHSP)</td>
                                                <td colspan="2" class="text-end font-monospace fw-bold text-dark" style="padding: 8px 10px;">Rp ${bahanTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="padding: 8px 10px;">Rp ${grandTotalHargaBahan.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                            </tr>` : ''}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Tenaga Kerja -->
                    <div class="accordion-item" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff;">
                        <h2 class="accordion-header" id="headingTenaga-${rabId}">
                            <button class="accordion-button collapsed py-2 px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseTenagaId}" aria-expanded="false" aria-controls="${collapseTenagaId}" style="font-size: 12px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                <i class="fas fa-users me-2 text-warning"></i> Rincian Tenaga Kerja (${tenagaCount})
                            </button>
                        </h2>
                        <div id="${collapseTenagaId}" class="accordion-collapse collapse" aria-labelledby="headingTenaga-${rabId}" data-bs-parent="#${accordionId}">
                            <div class="accordion-body p-0" style="background: #fff;">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 11px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 40px;">No</th>
                                                <th>Klasifikasi Pekerja</th>
                                                <th class="text-center" style="width: 70px;">Satuan</th>
                                                <th class="text-end" style="width: 80px;">Koefisien</th>
                                                <th class="text-end" style="width: 110px;">Harga Satuan</th>
                                                <th class="text-end" style="width: 110px;">Jumlah</th>
                                                <th class="text-end" style="width: 110px;">Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tkRows}
                                            ${tkTotal > 0 ? `
                                            <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                                                <td colspan="5" class="text-end fw-bold text-uppercase text-secondary" style="font-size: 10px; padding: 8px 10px;">Total Tenaga Kerja</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="padding: 8px 10px;">Rp ${tkTotal.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                                <td class="text-end font-monospace fw-bold text-dark" style="padding: 8px 10px;">Rp ${grandTotalHargaTk.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                                            </tr>` : ''}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#rab-detail-body-' + rabId).html(html);

        }).fail(function () {
            $('#rab-detail-body-' + rabId).html(`
                <div class="text-center py-4 text-warning">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    <div class="mt-2 fw-medium" style="font-size:12px;">Gagal menghubungi server untuk memuat detail.</div>
                </div>
            `);
        });
    }
</script>
