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
                    var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending' }[st] || st;

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
            
            // Update the status in progressDataList array
            var found = progressDataList.find(function(p) { return parseInt(p.id) === parseInt(progressId); });
            if (found) {
                found.status = newStatus;
            }

            var stLower = newStatus.toLowerCase();
            var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending' }[newStatus] || newStatus;
            
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
                var stLabel = { APPROVED: 'Approved', REJECTED: 'Rejected', PENDING: 'Pending' }[currentStatus] || currentStatus;
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

    function selectRow(tr) {
        // Hapus seleksi sebelumnya
        document.querySelectorAll('#mainTable tr.item-row.selected').forEach(function (el) {
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

    function openBuatLowonganModal(event, targetId, targetName, laborRate, volume) {
        if (event) event.stopPropagation();

        var cid = <?= json_encode($construction['id'] ?? '') ?>;
        var startDateStr = <?= json_encode($construction['start_date'] ?? null) ?>;
        
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
        
        document.getElementById('display-unit-wage').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(laborRate || 0));
        document.getElementById('display-volume').innerText = (parseFloat(volume) || 0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('display-total-wage-calc').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(calculatedWage);

        var job = jobsByTarget[targetId] || null;
        if (job) {
            document.getElementById('inp-detail-pekerjaan-loker').value = job.detail_pekerjaan || '';
            document.getElementById('inp-detail-lokasi-loker').value = job.detail_lokasi || '';
        } else {
            document.getElementById('inp-detail-pekerjaan-loker').value = '';
        }

        var modalEl = document.getElementById('modalBuatLowongan');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function openLihatLowonganModal(event, targetId, targetName) {
        if (event) event.stopPropagation();

        var job = jobsByTarget[targetId] || null;
        if (!job) {
            alert('Lowongan belum dibuat!');
            return;
        }

        document.getElementById('lihat-loker-subtitle').innerText = 'Target: ' + targetName;
        document.getElementById('display-detail-pekerjaan-loker').innerHTML = (job.detail_pekerjaan || '').replace(/\n/g, '<br>');
        document.getElementById('display-detail-lokasi-loker').innerHTML = (job.detail_lokasi || '').replace(/\n/g, '<br>');

        var dateMulai = job.tanggal_mulai ? new Date(job.tanggal_mulai) : null;
        var dateAkhir = job.tanggal_akhir ? new Date(job.tanggal_akhir) : null;
        var options = { day: 'numeric', month: 'short', year: 'numeric' };
        var jadwalText = '?';
        if (dateMulai && dateAkhir) {
            jadwalText = `<div>${dateMulai.toLocaleDateString('id-ID', options)}<br>s.d.<br>${dateAkhir.toLocaleDateString('id-ID', options)}</div>`;
        }
        document.getElementById('display-jadwal-kerja-loker').innerHTML = `<i class="far fa-calendar-alt text-indigo me-2" style="color:#6366f1"></i> ${jadwalText}`;

        var upahFormatted = new Intl.NumberFormat('id-ID').format(job.upah || 0);
        document.getElementById('display-upah-loker').innerHTML = `<i class="fas fa-wallet text-indigo me-2" style="color:#6366f1"></i> Rp ${upahFormatted}`;

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
            var listGroup = document.createElement('div');
            listGroup.className = 'list-group list-group-flush';

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
                        <a href="https://wa.me/${whatsapp}" target="_blank" class="btn btn-sm btn-success px-2 py-0.5 ms-2" style="border-radius: 6px; font-size: 0.75rem; line-height: 1.3;">
                            <i class="fab fa-whatsapp me-1"></i> Chat
                        </a>
                    `;
                }

                var item = document.createElement('div');
                item.className = 'list-group-item p-4 border-bottom';
                item.style.backgroundColor = '#fff';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">${app.tukang_name || 'Tukang #' + app.tukang_id}</h6>
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Melamar pada: ${app.created_at || '-'}</span>
                        </div>
                        <span class="badge ${stBadgeClass} text-uppercase" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 6px; letter-spacing: 0.3px; font-weight: 600;">${st}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-3 align-items-center my-3" style="font-size: 0.82rem; color: #475569;">
                        ${app.phone ? `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone-alt text-success me-1.5"></i>
                            <span>${app.phone}</span>
                            ${chatButton}
                        </div>` : ''}
                        ${app.specialization ? `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tools text-primary me-1.5"></i>
                            <span>Spesialisasi: <strong>${app.specialization}</strong></span>
                        </div>` : ''}
                    </div>
                    <?php if (can('construction_pelamar')): ?>
                        <form action="<?= base_url('admin/construction/update_applicant_status') ?>" method="post" class="mt-3">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="${app.id}">
                            <input type="hidden" name="construction_target_id" value="${targetId}">
                            <div class="input-group input-group-sm w-100" style="max-width: 320px;">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px; font-size: 0.78rem;">Ubah Status:</span>
                                <select name="status" class="form-select border-start-0 text-dark fw-medium" style="border-radius: 0; font-size: 0.8rem; background-color: #fff; height: 34px;">
                                    <option value="Berkas Diproses" ${st === 'Berkas Diproses' ? 'selected' : ''}>Berkas Diproses</option>
                                    <option value="Proses Test" ${st === 'Proses Test' ? 'selected' : ''}>Proses Test</option>
                                    <option value="Proses Aktivasi" ${st === 'Proses Aktivasi' ? 'selected' : ''}>Proses Aktivasi</option>
                                    <option value="Siap Kerja" ${st === 'Siap Kerja' ? 'selected' : ''}>Siap Kerja</option>
                                    <option value="Ditolak" ${st === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                                </select>
                                <button type="submit" class="btn btn-primary px-3" style="border-radius: 0 8px 8px 0; font-size: 0.8rem; font-weight: 600;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                `;
                listGroup.appendChild(item);
            });
            container.appendChild(listGroup);
        }

        var modalEl = document.getElementById('modalLihatLowongan');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    $(document).ready(function() {
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
