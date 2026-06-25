<script>
    const progressDataList = <?= json_encode($progress_list ?? []) ?>;
    const jobsByTarget = <?= json_encode($jobs_by_target ?? []) ?>;
    const applicantsByTarget = <?= json_encode($applicants_by_target ?? []) ?>;

    function showProgressList(event, targetId, activityName) {
        if (event) {
            event.stopPropagation();
        }
        
        // Set subtitle
        var subtitle = document.getElementById('progress-modal-subtitle');
        if (subtitle) subtitle.innerText = activityName;
        
        // Filter progress list
        var filtered = progressDataList.filter(function(item) {
            return parseInt(item.target_id) === parseInt(targetId);
        });
        
        var container = document.getElementById('progress-list-container');
        if (container) {
            container.innerHTML = '';
            
            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted" style="font-size: 2.5rem;">
                            <i class="fas fa-info-circle text-secondary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Laporan Progress</h6>
                        <p class="text-muted small mb-0">Belum ada laporan progress dari tukang untuk pekerjaan ini.</p>
                    </div>
                `;
            } else {
                filtered.forEach(function(item, index) {
                    var st = (item.status || 'PENDING').toUpperCase();
                    var stLower = st.toLowerCase(); // 'approved', 'rejected', 'pending'
                    var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending', PENDING_CLIENT: 'Pending Client' }[st] || st;

                    // Week number badge
                    var weekHtml = '';
                    if (item.week_number) {
                        weekHtml = `<span class="prog-week-badge">
                            <i class="fas fa-calendar-week"></i> Minggu ke-${item.week_number}
                        </span>`;
                    }

                    // Photo / video
                    var mediaHtml = '';
                    if (item.photo) {
                        var fileUrl = `<?= base_url('uploads/construction/progress/') ?>` + item.photo;
                        var ext = item.photo.split('.').pop().toLowerCase();
                        var isVideo = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'].indexOf(ext) !== -1;

                        if (isVideo) {
                            mediaHtml = `
                                <div class="mt-2">
                                    <a href="${fileUrl}" target="_blank"
                                       class="btn btn-sm btn-outline-warning"
                                       style="border-radius:6px;font-size:0.75rem;">
                                        <i class="fas fa-play me-1"></i> Lihat Video
                                    </a>
                                </div>`;
                        } else {
                            mediaHtml = `
                                <div class="prog-photo-wrap mt-2">
                                    <a href="${fileUrl}" target="_blank" title="Lihat foto progress">
                                        <img src="${fileUrl}" alt="Foto Progress #${index + 1}">
                                    </a>
                                </div>`;
                        }
                    } else {
                        mediaHtml = `
                            <div class="prog-no-photo-sm">
                                <i class="fas fa-image"></i> <span>Tidak ada foto</span>
                            </div>`;
                    }

                    var card = document.createElement('div');
                    card.className = 'prog-card st-' + stLower;
                    card.setAttribute('data-progress-id', item.id);
                    card.innerHTML = `
                        <div class="d-flex align-items-start justify-content-between mb-2 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div style="position:relative;">
                                    <button class="prog-status-pill ${stLower}" onclick="toggleProgDropdown(this, event)">
                                        <span class="prog-dot"></span> ${stLabel} <i class="fas fa-chevron-down ms-1" style="font-size:.5rem;"></i>
                                    </button>
                                    <div class="prog-status-dropdown">
                                        <a class="opt-pending" onclick="updateProgStatus(event, this, ${item.id}, 'PENDING')"><span class="dot-dd"></span> Pending</a>
                                        <a class="opt-approved" onclick="updateProgStatus(event, this, ${item.id}, 'APPROVED')"><span class="dot-dd"></span> Approved</a>
                                        <a class="opt-rejected" onclick="updateProgStatus(event, this, ${item.id}, 'REJECTED')"><span class="dot-dd"></span> Rejected</a>
                                    </div>
                                </div>
                                ${weekHtml}
                            </div>
                            <span class="prog-vol-badge">
                                <i class="fas fa-box me-1 text-primary"></i> ${parseFloat(item.volume).toFixed(2)}
                            </span>
                        </div>
                        <p class="prog-keterangan">
                            ${item.keterangan && item.keterangan !== '-'
                                ? '<i class="fas fa-comment-alt me-1 text-muted" style="opacity:.5;"></i>' + item.keterangan
                                : '<span class="text-muted fst-italic">Tidak ada keterangan</span>'}
                        </p>
                        <div class="prog-date-label">
                            <i class="fas fa-clock me-1"></i>${item.created_at}
                        </div>
                        ${mediaHtml}
                    `;
                    container.appendChild(card);
                });
            }
        }
        
        // Show modal
        var modalEl = document.getElementById('modalProgressList');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function toggleProgDropdown(btn, event) {
        if (event) {
            event.stopPropagation();
        }
        // Close semua dropdown lain
        document.querySelectorAll('.prog-status-dropdown.open').forEach(function(el) {
            if (el !== btn.nextElementSibling) el.classList.remove('open');
        });
        if (btn.nextElementSibling) {
            btn.nextElementSibling.classList.toggle('open');
        }
    }

    function updateProgStatus(event, link, progressId, newStatus) {
        if (event) {
            event.stopPropagation();
        }
        var card = link.closest('.prog-card');
        var pill = card.querySelector('.prog-status-pill');
        var dropdown = card.querySelector('.prog-status-dropdown');
        if (dropdown) dropdown.classList.remove('open');

        // Show spinner
        if (pill) {
            pill.innerHTML = '<span class="prog-status-loading"></span>';
        }

        var url = '<?= base_url('admin/construction/update_progress_status/') ?>' + progressId + '/' + newStatus;
        
        fetch(url, { 
            method: 'GET', 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            var mappedStatus = newStatus;
            if (newStatus === 'APPROVED') {
                mappedStatus = 'PENDING_CLIENT';
            }
            
            // Update the status in progressDataList array
            var found = progressDataList.find(function(p) { return parseInt(p.id) === parseInt(progressId); });
            if (found) {
                found.status = mappedStatus;
            }

            var stLower = mappedStatus.toLowerCase();
            var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending', PENDING_CLIENT: 'Pending Client' }[mappedStatus] || mappedStatus;
            
            if (pill) {
                pill.className = 'prog-status-pill ' + stLower;
                pill.innerHTML = '<span class="prog-dot"></span> ' + stLabel + ' <i class="fas fa-chevron-down ms-1" style="font-size:.5rem;"></i>';
            }
            if (card) {
                card.className = 'prog-card st-' + stLower;
            }
        })
        .catch(function(err) {
            console.error(err);
            if (pill) {
                var currentStatus = 'PENDING';
                var found = progressDataList.find(function(p) { return parseInt(p.id) === parseInt(progressId); });
                if (found && found.status) {
                    currentStatus = found.status.toUpperCase();
                }
                var stLower = currentStatus.toLowerCase();
                var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending', PENDING_CLIENT: 'Pending Client' }[currentStatus] || currentStatus;
                pill.className = 'prog-status-pill ' + stLower;
                pill.innerHTML = '<span class="prog-dot"></span> ' + stLabel + ' <i class="fas fa-chevron-down ms-1" style="font-size:.5rem;"></i>';
            }
            alert('Gagal update status!');
        });
    }

    // Close dropdown on outside click
    document.addEventListener('click', function() {
        document.querySelectorAll('.prog-status-dropdown.open').forEach(function(el) {
            el.classList.remove('open');
        });
    });

    function selectRow(element) {
        var tr = element.closest('tr');
        if (!tr) return;

        // Hapus seleksi sebelumnya
        document.querySelectorAll('table.table-schedule tr.item-row.selected').forEach(function (el) {
            el.classList.remove('selected');
        });
        tr.classList.add('selected');

        var rabId = tr.getAttribute('data-rab-id');
        var addendumId = tr.getAttribute('data-addendum-id');
        var group = tr.getAttribute('data-group');
        var subgroup = tr.getAttribute('data-subgroup');
        var activity = tr.getAttribute('data-activity');
        var jobApps = tr.getAttribute('data-job-apps');
        var targetId = tr.getAttribute('data-target-id');
        var cid = <?= json_encode($construction['id'] ?? '') ?>;

        // Isi hidden field rab_id (POST)
        var hiddenInputRab = document.getElementById('inp-rab-id-' + cid);
        if (hiddenInputRab) hiddenInputRab.value = rabId || '';

        var hiddenInputAddendum = document.getElementById('inp-addendum-id-' + cid);
        if (hiddenInputAddendum) hiddenInputAddendum.value = addendumId || '';

        // Isi field tampilan
        var inpGroup = document.getElementById('inp-group-' + cid);
        var inpSubgroup = document.getElementById('inp-subgroup-' + cid);
        var inpName = document.getElementById('inp-name-' + cid);
        var inpTukang = document.getElementById('inp-tukang-' + cid);
        if (inpGroup) inpGroup.value = group;
        if (inpSubgroup) inpSubgroup.value = subgroup;
        if (inpName) inpName.value = activity;
        
        // Rebuild and filter Tukang dropdown
        if (inpTukang) {
            inpTukang.innerHTML = '<option value="">Pilih Tukang</option>';
            var targetApplicants = applicantsByTarget[targetId] || [];
            targetApplicants.forEach(function(app) {
                var opt = document.createElement('option');
                opt.value = app.id;
                opt.textContent = app.tukang_name + ' (' + app.status + ')';
                inpTukang.appendChild(opt);
            });
            inpTukang.value = jobApps || '';
        }

        // Aktifkan tombol simpan
        var btn = document.getElementById('btn-submit-target-' + cid);
        if (btn) btn.disabled = false;

        // Info label
        var info = document.getElementById('selected-info-' + cid);
        if (info) info.textContent = '— ' + activity;

        // Fokus ke input mulai minggu
        var inpStart = document.getElementById('inp-start-' + cid);
        if (inpStart) inpStart.focus();

        // Open the modal
        var modalEl = document.getElementById('modalTargetEdit');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function toggleGroup(groupClass, headerRow) {
        var rows = document.querySelectorAll('tr.' + groupClass);
        var chevron = headerRow.querySelector('.group-chevron');
        var isCollapsed = headerRow.classList.contains('collapsed');
        
        if (isCollapsed) {
            headerRow.classList.remove('collapsed');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            rows.forEach(function (row) {
                row.style.display = '';
            });
        } else {
            headerRow.classList.add('collapsed');
            if (chevron) chevron.style.transform = 'rotate(-90deg)';
            rows.forEach(function (row) {
                row.style.display = 'none';
            });
        }
    }

    /**
     * Toggle visibility of a week-group (every 5 weeks) using the week header trigger.
     * @param {number} grpIdx  - zero-based group index
     * @param {HTMLElement} btn - the toggle button/header clicked
     */
    function toggleWeekGroup(grpIdx, btn) {
        // Find all trigger headers for this group (across both tables)
        var triggers = document.querySelectorAll('th.week-group-trigger[data-group-idx="' + grpIdx + '"]');
        
        // Find all collapsible cells in both tables belonging to this group index (excluding the trigger itself)
        var cells = document.querySelectorAll('[data-wg="' + grpIdx + '"]');
        
        var isNowCollapsed = btn.classList.contains('collapsed-trigger');

        if (isNowCollapsed) {
            // Expand (Open)
            triggers.forEach(function(th) {
                th.classList.remove('collapsed-trigger');
                
                // Reset label text
                var label = th.querySelector('.week-label-text');
                if (label) label.textContent = th.getAttribute('data-label-original');
                
                // Show date subtext
                var subtext = th.querySelector('.week-date-subtext');
                if (subtext) subtext.style.display = '';
            });
            
            cells.forEach(function(c) { 
                c.classList.remove('wg-hidden'); 
            });
        } else {
            // Collapse (Close)
            triggers.forEach(function(th) {
                th.classList.add('collapsed-trigger');
                
                // Change label text to range
                var label = th.querySelector('.week-label-text');
                if (label) {
                    var start = th.getAttribute('data-start-week');
                    var end = th.getAttribute('data-end-week');
                    label.textContent = 'MG ' + start + '–' + end;
                }
                
                // Hide date subtext
                var subtext = th.querySelector('.week-date-subtext');
                if (subtext) subtext.style.display = 'none';
            });
            
            cells.forEach(function(c) { 
                c.classList.add('wg-hidden'); 
            });
        }
        
        // Recalculate visibility of target spanned cells
        updateActiveCellsVisibility();
    }

    /**
     * Update visibility of spanned target cells based on collapsed week groups.
     */
    function updateActiveCellsVisibility() {
        var cells = document.querySelectorAll('.cell-bar.week-active');
        cells.forEach(function(cell) {
            var startWk = parseInt(cell.getAttribute('data-start-wk'));
            var endWk = parseInt(cell.getAttribute('data-end-wk'));
            var weekGroupSize = 5;
            
            var hasVisibleWeek = false;
            for (var w = startWk; w <= endWk; w++) {
                var grpIdxOfW = Math.floor((w - 1) / weekGroupSize);
                var isFirstOfGrp = ((w - 1) % weekGroupSize === 0);
                
                // Query the trigger element for this week's group
                var thTrigger = document.querySelector('th.week-group-trigger[data-group-idx="' + grpIdxOfW + '"]');
                var isGrpCollapsed = thTrigger && thTrigger.classList.contains('collapsed-trigger');
                
                // A week is visible if:
                // - It is the first week of a group (always visible to act as expand trigger)
                // - OR the group trigger is not collapsed.
                if (isFirstOfGrp || !isGrpCollapsed) {
                    hasVisibleWeek = true;
                    break;
                }
            }
            
            if (hasVisibleWeek) {
                cell.classList.remove('wg-hidden');
            } else {
                cell.classList.add('wg-hidden');
            }
        });
    }

    /**
     * Toggle visibility of detail columns (RAB prices & volume details)
     */
    function toggleDetailColumns() {
        var body = document.body;
        var btn = document.getElementById('toggleDetailCols');
        
        if (body.classList.contains('show-detail-cols')) {
            body.classList.remove('show-detail-cols');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-eye me-1"></i> Tampilkan Detail';
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-outline-secondary');
            }
            localStorage.setItem('show_target_detail_cols', 'false');
        } else {
            body.classList.add('show-detail-cols');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Sembunyikan Detail';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-secondary');
            }
            localStorage.setItem('show_target_detail_cols', 'true');
        }
    }

    // Client-side real-time filter for Manage Vacancies table
    function filterLokerTable() {
        var query = document.getElementById('search-loker-input').value.toLowerCase().trim();
        
        // Filter both panels
        ['#panel-rab-utama', '#panel-addendum'].forEach(function(panelSelector) {
            var panel = document.querySelector(panelSelector);
            if (!panel) return;
            var rows = panel.querySelectorAll('tbody tr.loker-row');
            var emptyRow = panel.querySelector('tbody tr.empty-search-row');
            var visibleCount = 0;
            
            rows.forEach(function(row) {
                var searchText = row.getAttribute('data-search-text') || '';
                if (searchText.indexOf(query) !== -1) {
                    row.style.setProperty('display', '', 'important');
                    visibleCount++;
                } else {
                    row.style.setProperty('display', 'none', 'important');
                }
            });
            
            if (emptyRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    emptyRow.style.setProperty('display', '', 'important');
                    var p = emptyRow.querySelector('p');
                    if (p) p.textContent = 'Tidak ada lowongan pekerjaan yang cocok dengan pencarian Anda.';
                } else if (rows.length === 0) {
                    emptyRow.style.setProperty('display', '', 'important');
                } else {
                    emptyRow.style.setProperty('display', 'none', 'important');
                }
            }
        });
    }

    // Dynamic Skill Chips renderer and click-toggles
    function renderSkillChips(selectedIds = []) {
        var container = document.getElementById('skills-chips-container');
        var select = document.getElementById('inp-skills-loker');
        if (!container || !select) return;

        container.innerHTML = '';
        
        Array.from(select.options).forEach(function(option) {
            var id = option.value;
            var name = option.text;
            var isSelected = selectedIds.includes(parseInt(id));
            
            // Sync selection in hidden select
            option.selected = isSelected;

            var chip = document.createElement('div');
            chip.className = 'skill-chip' + (isSelected ? ' active' : '');
            chip.setAttribute('data-id', id);
            chip.setAttribute('data-name', name.toLowerCase());
            chip.innerHTML = `<i class="fas ${isSelected ? 'fa-check' : 'fa-plus'}"></i> <span>${name}</span>`;
            
            chip.addEventListener('click', function(e) {
                e.preventDefault();
                var nowSelected = !option.selected;
                option.selected = nowSelected;
                
                // Update visual representation
                if (nowSelected) {
                    chip.classList.add('active');
                    chip.querySelector('i').className = 'fas fa-check';
                } else {
                    chip.classList.remove('active');
                    chip.querySelector('i').className = 'fas fa-plus';
                }
            });
            
            container.appendChild(chip);
        });
    }

    function openBuatLowonganModal(event, targetId, targetName, laborRate, volume, ahspId = 0) {
        if (event) event.stopPropagation();

        // Close Kelola modal if it's open to prevent backdrop stacking
        var kelolaModalEl = document.getElementById('modalKelolaLowongan');
        if (kelolaModalEl) {
            var kelolaInst = bootstrap.Modal.getInstance(kelolaModalEl);
            if (kelolaInst) kelolaInst.hide();
        }

        var cid = <?= json_encode($construction['id'] ?? '') ?>;
        var startDateStr = <?= json_encode($construction['start_date'] ?? null) ?>;
        if (!startDateStr) {
            startDateStr = <?= json_encode($construction['created_at'] ?? null) ?>;
        }
        if (!startDateStr) {
            startDateStr = new Date().toISOString().slice(0, 10);
        }
        
        var startWeek = 1;
        var endWeek = 2;
        
        var targetList = <?= json_encode($target_list ?? []) ?>;
        var targetObj = targetList.find(function(t) { return parseInt(t.id) === parseInt(targetId); });
        if (targetObj) {
            startWeek = parseInt(targetObj.start_week);
            endWeek = parseInt(targetObj.end_week);
        }

        var dateMulaiStr = '';
        var dateAkhirStr = '';
        var displayJadwal = 'Belum ditentukan';
        var displayDurasi = '-';
        
        if (startDateStr) {
            var dMulai = new Date(startDateStr);
            dMulai.setDate(dMulai.getDate() + (startWeek - 1) * 7);
            
            var dAkhir = new Date(startDateStr);
            dAkhir.setDate(dAkhir.getDate() + (endWeek * 7));

            var yM = dMulai.getFullYear();
            var mM = String(dMulai.getMonth() + 1).padStart(2, '0');
            var dM = String(dMulai.getDate()).padStart(2, '0');
            dateMulaiStr = `${yM}-${mM}-${dM}`;

            var yA = dAkhir.getFullYear();
            var mA = String(dAkhir.getMonth() + 1).padStart(2, '0');
            var dA = String(dAkhir.getDate()).padStart(2, '0');
            dateAkhirStr = `${yA}-${mA}-${dA}`;

            var options = { day: 'numeric', month: 'short', year: 'numeric' };
            displayJadwal = dMulai.toLocaleDateString('id-ID', options) + ' – ' + dAkhir.toLocaleDateString('id-ID', options);
            displayDurasi = (endWeek - startWeek + 1) + ' Minggu (' + ((endWeek - startWeek + 1) * 7) + ' hari)';
        }

        document.getElementById('inp-target-id-loker').value = targetId;
        document.getElementById('inp-mulai-loker').value = dateMulaiStr;
        document.getElementById('inp-akhir-loker').value = dateAkhirStr;
        document.getElementById('display-jadwal-loker').innerText = displayJadwal;
        document.getElementById('display-durasi-loker').innerText = displayDurasi;
        document.getElementById('job-modal-subtitle').innerText = 'Target: ' + targetName;

        var calculatedWage = Math.round((parseFloat(laborRate) || 0) * (parseFloat(volume) || 0));
        
        // Update display inputs
        document.getElementById('inp-upah-loker').value = calculatedWage;
        document.getElementById('display-upah-loker-input').value = new Intl.NumberFormat('id-ID').format(calculatedWage);
        
        function renderSimpleBreakdown(rate, vol) {
            var calculatedWage = Math.round((parseFloat(rate) || 0) * (parseFloat(vol) || 0));
            var breakdownContainer = document.getElementById('upah-breakdown-container');
            if (breakdownContainer) {
                breakdownContainer.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12.5px;">
                        <span>Upah per Unit (Tenaga Kerja):</span>
                        <span class="fw-bold text-dark">Rp ${new Intl.NumberFormat('id-ID').format(Math.round(rate || 0))}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12.5px;">
                        <span>Volume Pekerjaan Target:</span>
                        <span class="fw-bold text-dark">${(parseFloat(vol) || 0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                    <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                    <div class="d-flex justify-content-between align-items-center" style="font-size: 12.5px;">
                        <span class="fw-bold">Total Upah (Unit × Volume):</span>
                        <span class="fw-bold text-success" style="font-size: 13.5px;">Rp ${new Intl.NumberFormat('id-ID').format(calculatedWage)}</span>
                    </div>
                `;
            }
        }

        var breakdownContainer = document.getElementById('upah-breakdown-container');
        if (breakdownContainer) {
            if (ahspId && parseInt(ahspId) > 0) {
                breakdownContainer.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i> Memuat rincian upah...
                    </div>
                `;
                $.get('<?= base_url('admin/ahsp/show') ?>/' + ahspId, function(res) {
                    if (res.status && res.data && res.data.tenaga_kerja && res.data.tenaga_kerja.length > 0) {
                        var rowsHtml = '';
                        var totalUnitLabor = 0;
                        var totalAllLabor = 0;
                        res.data.tenaga_kerja.forEach(function(t, idx) {
                            var koef = parseFloat(t.koefisien) || 0;
                            var hargaSatuan = parseFloat(t.harga_satuan) || 0;
                            var jumlah = koef * hargaSatuan;
                            totalUnitLabor += jumlah;
                            
                            var totalHarga = jumlah * (parseFloat(volume) || 0);
                            totalAllLabor += totalHarga;
                            
                            rowsHtml += `
                                <tr>
                                    <td class="ps-3 fw-medium text-dark">${t.uraian || '-'}</td>
                                    <td class="text-center">${t.satuan || '-'}</td>
                                    <td class="text-end font-monospace">${koef.toFixed(4).replace('.', ',')}</td>
                                    <td class="text-end font-monospace">Rp ${Math.round(hargaSatuan).toLocaleString('id-ID')}</td>
                                    <td class="text-end font-monospace fw-bold text-dark">Rp ${Math.round(jumlah).toLocaleString('id-ID')}</td>
                                    <td class="text-end font-monospace fw-bold text-dark pe-3">Rp ${Math.round(totalHarga).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                        });
                        
                        breakdownContainer.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12.5px;">
                                <span>Upah per Unit (Tenaga Kerja):</span>
                                <span class="fw-bold text-dark">Rp ${new Intl.NumberFormat('id-ID').format(Math.round(totalUnitLabor))}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12.5px;">
                                <span>Volume Pekerjaan Target:</span>
                                <span class="fw-bold text-dark">${(parseFloat(volume) || 0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                            </div>
                            <hr style="margin: 8px 0; border-top: 1px dashed #cbd5e1;">
                            <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 12.5px;">
                                <span class="fw-bold">Total Upah (Unit × Volume):</span>
                                <span class="fw-bold text-success" style="font-size: 13.5px;">Rp ${new Intl.NumberFormat('id-ID').format(Math.round(totalAllLabor))}</span>
                            </div>

                            <!-- Accordion Rincian Tenaga Kerja -->
                            <div class="accordion" id="accordionTenagaKerja">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTenaga">
                                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTenaga" aria-expanded="false" aria-controls="collapseTenaga" style="padding: 10px 14px; font-size: 12px; border-bottom: 1px solid #e2e8f0; box-shadow: none;">
                                            <i class="fas fa-list-ul me-2"></i> Lihat Rincian Tenaga Kerja (AHSP)
                                        </button>
                                    </h2>
                                    <div id="collapseTenaga" class="accordion-collapse collapse" aria-labelledby="headingTenaga" data-bs-parent="#accordionTenagaKerja">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive" style="border: none; border-radius: 0; overflow: hidden; background: #fff;">
                                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 11px;">
                                                    <thead class="table-light text-secondary" style="font-size: 9.5px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">
                                                        <tr>
                                                            <th class="ps-3">Klasifikasi Pekerja</th>
                                                            <th class="text-center" style="width: 70px;">Satuan</th>
                                                            <th class="text-end" style="width: 90px;">Koefisien</th>
                                                            <th class="text-end" style="width: 120px;">Harga Satuan</th>
                                                            <th class="text-end" style="width: 120px;">Jumlah</th>
                                                            <th class="text-end pe-3" style="width: 140px;">Total Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${rowsHtml}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        renderSimpleBreakdown(laborRate, volume);
                    }
                }).fail(function() {
                    renderSimpleBreakdown(laborRate, volume);
                });
            } else {
                renderSimpleBreakdown(laborRate, volume);
            }
        }

        var job = jobsByTarget[targetId] || null;
        var searchInp = document.getElementById('skills-search-input');
        if (searchInp) searchInp.value = '';

        var selectedSkillIds = [];
        if (job) {
            document.getElementById('inp-detail-lokasi-loker').value = job.detail_lokasi || '';
            
            // Collect pre-selected skill IDs
            if (job.skills && Array.isArray(job.skills)) {
                selectedSkillIds = job.skills.map(function(s) { return parseInt(s.id); });
            }
        } else {
            document.getElementById('inp-detail-lokasi-loker').value = '';
        }

        // Render skill chips with active states
        renderSkillChips(selectedSkillIds);

        // Populate is_open status dropdown
        var isOpenSelect = document.getElementById('inp-is-open-loker');
        if (isOpenSelect) {
            isOpenSelect.value = (job && job.is_open !== undefined) ? job.is_open : '1';
        }

        var modalEl = document.getElementById('modalBuatLowongan');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function openLihatLowonganModal(event, targetId, targetName) {
        if (event) event.stopPropagation();

        // Close Kelola modal if it's open to prevent backdrop stacking
        var kelolaModalEl = document.getElementById('modalKelolaLowongan');
        if (kelolaModalEl) {
            var kelolaInst = bootstrap.Modal.getInstance(kelolaModalEl);
            if (kelolaInst) kelolaInst.hide();
        }

        var job = jobsByTarget[targetId] || null;
        if (!job) {
            alert('Lowongan belum dibuat!');
            return;
        }

        document.getElementById('lihat-loker-subtitle').innerText = 'Target: ' + targetName;
        document.getElementById('display-detail-pekerjaan-loker').innerHTML = targetName || '';
        document.getElementById('display-detail-lokasi-loker').innerHTML = (job.detail_lokasi || '').replace(/\n/g, '<br>');

        // Render qualifications (skills)
        var skillsContainer = document.getElementById('display-skills-loker');
        if (skillsContainer) {
            skillsContainer.innerHTML = '';
            if (job.skills && Array.isArray(job.skills) && job.skills.length > 0) {
                job.skills.forEach(function(sk) {
                    var span = document.createElement('span');
                    span.className = 'badge bg-light text-dark border me-1 mb-1';
                    span.style.fontSize = '0.8rem';
                    span.style.padding = '6px 12px';
                    span.style.borderRadius = '6px';
                    span.style.fontWeight = '500';
                    span.textContent = sk.skill_name;
                    skillsContainer.appendChild(span);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-muted fst-italic" style="font-size: 0.85rem;">Tidak ada kualifikasi skill khusus</span>';
            }
        }

        // Render status lowongan
        var statusContainer = document.getElementById('display-status-loker');
        if (statusContainer) {
            statusContainer.innerHTML = '';
            var isOpen = (job.is_open !== undefined && job.is_open !== null) ? parseInt(job.is_open) !== 0 : true;
            var span = document.createElement('span');
            if (isOpen) {
                span.className = 'badge bg-success text-white';
                span.textContent = 'Dibuka';
            } else {
                span.className = 'badge bg-danger text-white';
                span.textContent = 'Ditutup';
            }
            span.style.fontSize = '0.8rem';
            span.style.padding = '6px 12px';
            span.style.borderRadius = '6px';
            span.style.fontWeight = '600';
            statusContainer.appendChild(span);
        }

        var dateMulai = job.tanggal_mulai ? new Date(job.tanggal_mulai) : null;
        var dateAkhir = job.tanggal_akhir ? new Date(job.tanggal_akhir) : null;
        var options = { day: 'numeric', month: 'short', year: 'numeric' };
        var jadwalText = '?';
        if (dateMulai && dateAkhir) {
            jadwalText = `<div>${dateMulai.toLocaleDateString('id-ID', options)} – ${dateAkhir.toLocaleDateString('id-ID', options)}</div>`;
        }
        document.getElementById('display-jadwal-kerja-loker').innerHTML = jadwalText;

        var upahFormatted = new Intl.NumberFormat('id-ID').format(job.upah || 0);
        document.getElementById('display-upah-loker').innerHTML = `Rp ${upahFormatted}`;

        var applicants = applicantsByTarget[targetId] || [];
        document.getElementById('display-jumlah-pelamar-loker').innerText = applicants.length + ' Pelamar';

        var container = document.getElementById('lihat-pelamar-container');
        container.innerHTML = '';

        if (applicants.length === 0) {
            container.innerHTML = `
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-users-slash fa-3x mb-3 text-secondary opacity-30"></i>
                    <p class="small mb-0 fw-medium">Belum ada pelamar tukang yang masuk untuk lowongan target ini.</p>
                </div>
            `;
        } else {
            applicants.forEach(function(app) {
                var st = app.status || 'Berkas Diproses';
                var whatsapp = app.phone ? app.phone.replace(/[^0-9]/g, '') : '';
                var stBadgeClass = 'bg-warning text-dark';
                if (st === 'Siap Kerja' || st === 'Approved') stBadgeClass = 'bg-success text-white';
                if (st === 'Ditolak') stBadgeClass = 'bg-danger text-white';
                if (st === 'Proses Test') stBadgeClass = 'bg-primary text-white';
                if (st === 'Proses Aktivasi') stBadgeClass = 'bg-info text-white';

                var chatButton = '';
                if (whatsapp) {
                    chatButton = `
                        <a href="https://wa.me/${whatsapp}" target="_blank" class="wa-btn">
                            <i class="fab fa-whatsapp"></i> Chat WhatsApp
                        </a>
                    `;
                }

                // Initial generation
                var initials = (app.tukang_name || 'TK')
                    .split(' ')
                    .map(function(n) { return n[0]; })
                    .slice(0, 2)
                    .join('')
                    .toUpperCase();
                
                var colors = ['#4f46e5', '#10b981', '#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#06b6d4', '#14b8a6'];
                var sum = 0;
                var nameStr = app.tukang_name || '';
                for (var i = 0; i < nameStr.length; i++) {
                    sum += nameStr.charCodeAt(i);
                }
                var avatarBg = colors[sum % colors.length];

                var stLower = st.toLowerCase().replace(/ /g, '-');
                var card = document.createElement('div');
                card.className = 'applicant-card status-' + stLower;
                card.innerHTML = `
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="applicant-avatar-circle" style="background-color: ${avatarBg};">
                                ${initials}
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 14px; font-family: 'Outfit', sans-serif;">${app.tukang_name || 'Tukang #' + app.tukang_id}</h6>
                                <span class="text-muted d-block mt-0.5" style="font-size: 11px;"><i class="far fa-clock me-1"></i>Melamar pada: ${app.created_at || '-'}</span>
                            </div>
                        </div>
                        <span class="badge ${stBadgeClass} text-uppercase" style="font-size: 9.5px; padding: 5px 10px; border-radius: 20px; letter-spacing: 0.5px; font-weight: 700;">${st}</span>
                    </div>
                    
                    <div class="row g-2 mb-3" style="font-size: 12px; color: #475569; background: #f8fafc; padding: 12px; border-radius: 8px;">
                        ${app.phone ? `
                        <div class="col-sm-7 d-flex align-items-center gap-2 flex-wrap">
                            <i class="fas fa-phone-alt text-success"></i>
                            <span class="fw-semibold">${app.phone}</span>
                            ${chatButton}
                        </div>` : ''}
                        ${app.specialization ? `
                        <div class="col-sm-5 d-flex align-items-center gap-2">
                            <i class="fas fa-tools text-primary"></i>
                            <span>Spesialisasi: <strong class="text-dark">${app.specialization}</strong></span>
                        </div>` : ''}
                    </div>
                    
                    <?php if (can('construction_pelamar')): ?>
                        <form action="<?= base_url('admin/construction/update_applicant_status') ?>" method="post" class="mt-2 pt-2 border-top border-light">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="${app.id}">
                            <input type="hidden" name="construction_target_id" value="${targetId}">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-secondary fw-semibold" style="font-size: 12px;">Status Pelamar:</span>
                                <div class="input-group input-group-sm" style="max-width: 280px; width: 100%;">
                                    <select name="status" class="form-select text-dark fw-bold" style="border-radius: 8px 0 0 8px; font-size: 12px; background-color: #fff; height: 36px; border: 1.5px solid #cbd5e1;">
                                        <option value="Berkas Diproses" ${st === 'Berkas Diproses' ? 'selected' : ''}>Berkas Diproses</option>
                                        <option value="Proses Test" ${st === 'Proses Test' ? 'selected' : ''}>Proses Test</option>
                                        <option value="Proses Aktivasi" ${st === 'Proses Aktivasi' ? 'selected' : ''}>Proses Aktivasi</option>
                                        <option value="Siap Kerja" ${st === 'Siap Kerja' ? 'selected' : ''}>Siap Kerja</option>
                                        <option value="Ditolak" ${st === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary px-3" style="border-radius: 0 8px 8px 0; font-size: 12px; font-weight: 700; background-color: #4f46e5; border-color: #4f46e5;">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                `;
                container.appendChild(card);
            });
        }

        var modalEl = document.getElementById('modalLihatLowongan');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function toggleJobStatus(btn) {
        var jobId = btn.getAttribute('data-job-id');
        if (!jobId) return;

        // Visual loading state
        var originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengubah...';

        var postData = {};
        postData['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';

        $.post('<?= base_url('admin/construction/toggle-job-status') ?>/' + jobId, postData, function(res) {
            btn.disabled = false;
            if (res.status) {
                // Update badge state dynamically
                var is_open = parseInt(res.is_open) === 1;
                var numApplicants = btn.getAttribute('data-applicants') || '0';
                
                // Update properties in client-side jobsByTarget object
                for (var targetId in jobsByTarget) {
                    if (jobsByTarget[targetId] && parseInt(jobsByTarget[targetId].id) === parseInt(jobId)) {
                        jobsByTarget[targetId].is_open = res.is_open;
                        break;
                    }
                }

                if (is_open) {
                    btn.className = 'btn btn-sm badge bg-success text-white px-2.5 py-1.5 fw-semibold toggle-job-status-btn';
                    btn.style.border = 'none';
                    btn.style.boxShadow = '0 2px 6px rgba(25, 135, 84, 0.2)';
                    btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Dibuka (' + numApplicants + ' Pelamar)';
                } else {
                    btn.className = 'btn btn-sm badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold toggle-job-status-btn';
                    btn.style.border = '1px solid rgba(220, 53, 69, 0.15)';
                    btn.style.boxShadow = 'none';
                    btn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Ditutup (' + numApplicants + ' Pelamar)';
                }
            } else {
                alert(res.message || 'Gagal mengubah status lowongan');
                btn.innerHTML = originalContent;
            }
        }).fail(function() {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            alert('Terjadi kesalahan jaringan.');
        });
    }

    $(document).ready(function() {
        // Skill chips search input listener
        $(document).on('input', '#skills-search-input', function(e) {
            var q = e.target.value.toLowerCase().trim();
            var chips = document.querySelectorAll('#skills-chips-container .skill-chip');
            chips.forEach(function(chip) {
                var name = chip.getAttribute('data-name') || '';
                if (name.indexOf(q) !== -1) {
                    chip.style.display = '';
                } else {
                    chip.style.display = 'none';
                }
            });
        });

        // Restore detail columns preference
        if (localStorage.getItem('show_target_detail_cols') === 'true') {
            document.body.classList.add('show-detail-cols');
            var btn = document.getElementById('toggleDetailCols');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Sembunyikan Detail';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-secondary');
            }
        }

        // Initial visibility check for spanned target cells
        updateActiveCellsVisibility();

        // Re-open kelola modal when child modals are closed
        $('#modalBuatLowongan, #modalLihatLowongan').on('hidden.bs.modal', function () {
            var kelolaModalEl = document.getElementById('modalKelolaLowongan');
            if (kelolaModalEl && kelolaModalEl.classList.contains('show') === false) {
                // Check if any other modal is currently opening/shown (like stacking)
                if ($('.modal.show').length === 0) {
                    var kelolaInst = bootstrap.Modal.getInstance(kelolaModalEl) || new bootstrap.Modal(kelolaModalEl);
                    kelolaInst.show();
                }
            }
        });

        <?php if (session()->getFlashdata('open_target_job_modal')): ?>
            var targetIdToOpen = <?= json_encode(session()->getFlashdata('open_target_job_modal')) ?>;
            var targetListObj = <?= json_encode($target_list ?? []) ?>;
            var targetItem = targetListObj.find(function(t) { return parseInt(t.id) === parseInt(targetIdToOpen); });
            var targetName = 'Target #' + targetIdToOpen;
            if (targetItem) {
                var rabsObj = <?= json_encode($rab ?? []) ?>;
                var addendumsObj = <?= json_encode($addendum ?? []) ?>;
                if (targetItem.id_construction_rabs) {
                    var r = rabsObj.find(function(item) { return parseInt(item.id) === parseInt(targetItem.id_construction_rabs); });
                    if (r) targetName = r.activity_name;
                } else if (targetItem.id_construction_addendum) {
                    var a = addendumsObj.find(function(item) { return parseInt(item.id) === parseInt(targetItem.id_construction_addendum); });
                    if (a) targetName = '[ADDENDUM] ' + a.activity_name;
                }
            }
            openLihatLowonganModal(null, targetIdToOpen, targetName);
        <?php endif; ?>
    });
</script>
