<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function () {
        var userRole = '<?= strtolower(session()->get('role') ?? "") ?>';
        var isDrafterOrArsitek = ['drafter', 'arsitek'].includes(userRole);
        var isKadiv = (userRole === 'kepala divisi desain' || <?= in_array('super_admin_override', session()->get('permissions') ?? []) ? 'true' : 'false' ?>);

        /* ===== DataTables ===== */
        if ($('#table-1').length) {
            var table = $('#table-1').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": "Tidak ada data yang tersedia",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan"
                },
                "columnDefs": [{
                    "sortable": false,
                    "targets": [6]
                }],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'r<"table-responsive"t><"dt-footer d-flex justify-content-between align-items-center"ip>',
                "drawCallback": function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            /* ===== Custom Dropdown Status ===== */
            var dropdownTrigger = $('#dropdownStatusTrigger');
            var dropdownMenu = $('#dropdownStatusMenu');

            dropdownTrigger.on('click', function (e) {
                e.stopPropagation();
                $(this).toggleClass('open');
                dropdownMenu.toggleClass('show');
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    dropdownTrigger.removeClass('open');
                    dropdownMenu.removeClass('show');
                }
            });

            var statusLabels = {
                'pending': 'PENDING',
                'survey': 'SURVEY SCHEDULED',
                'payment': 'PAYMENT VERIFIED',
                'completed': 'COMPLETED',
                'cancelled': 'CANCELLED'
            };

            $('.dropdown-item-custom').on('click', function (e) {
                e.preventDefault();
                $('.dropdown-item-custom').removeClass('active');
                $(this).addClass('active');

                var val = $(this).data('value');
                var text = $(this).text().trim();
                $('#selectedStatusText').text(text);

                dropdownTrigger.removeClass('open');
                dropdownMenu.removeClass('show');

                if (val === 'all') {
                    table.column(5).search('').draw();
                } else {
                    var searchVal = statusLabels[val] || '';
                    table.column(5).search(searchVal).draw();
                }
            });

            /* ===== Custom Search ===== */
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });
            $('#searchInput').on('search', function () {
                if (this.value === '') table.search('').draw();
            });
        }

        /* ===== Kanban Board Drag & Drop ===== */
        if ($('.sortable-list').length) {
            $('.sortable-list').each(function () {
                var list = this;
                var columnStatus = $(list).data('status');
                
                Sortable.create(list, {
                    group: {
                        name: 'kanban',
                        put: function (to, from, dragEl, event) {
                            // Jangan izinkan pemindahan manual ke kolom TINJAUAN
                            return to.el.dataset.status !== 'TINJAUAN';
                        }
                    },
                    disabled: (columnStatus === 'TINJAUAN' || columnStatus === 'DONE') && isDrafterOrArsitek,
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function (evt) {
                        var cardEl = evt.item;
                        var targetId = cardEl.dataset.id;
                        var fromCol = evt.from.dataset.status;
                        var toCol = evt.to.dataset.status;
                        
                        if (fromCol === toCol) return;
                        
                        // Dari TINJAUAN ke DONE (Approval)
                        if (fromCol === 'TINJAUAN' && toCol === 'DONE') {
                            var designItem = $(cardEl).find('.review-preview-item').first();
                            var designId = designItem.data('design-id');
                            if (designId) {
                                confirmApproveDesign(designId, targetId, cardEl, evt.from, evt.to);
                            } else {
                                updateTargetStatus(targetId, 'DONE', cardEl, evt.from, evt.to);
                            }
                        }
                        // Dari TINJAUAN ke PROGRESS / PENDING (Reject/Revision)
                        else if (fromCol === 'TINJAUAN' && (toCol === 'ON PROGRESS' || toCol === 'PENDING')) {
                            var designItem = $(cardEl).find('.review-preview-item').first();
                            var designId = designItem.data('design-id');
                            if (designId) {
                                showRejectModal(designId, targetId, toCol, cardEl, evt.from, evt.to);
                            } else {
                                updateTargetStatus(targetId, toCol, cardEl, evt.from, evt.to);
                            }
                        }
                        // Pemindahan normal lainnya
                        else {
                            updateTargetStatus(targetId, toCol, cardEl, evt.from, evt.to);
                        }
                    }
                });
            });

            // Re-assign Designer Dropdown Change Listener
            $(document).on('change', '.kanban-card-designer-select', function () {
                var select = this;
                var targetId = $(select).data('target-id');
                var designerId = $(select).val();
                
                $.ajax({
                    url: '<?= base_url("admin/design/update-target-designer-ajax") ?>',
                    type: 'POST',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                        target_id: targetId,
                        user_admin_id: designerId
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            iziToast.success({
                                title: 'Berhasil',
                                message: res.message,
                                position: 'topRight'
                            });
                        } else {
                            iziToast.error({
                                title: 'Gagal',
                                message: res.message,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function () {
                        iziToast.error({
                            title: 'Error',
                            message: 'Gagal memperbarui desainer.',
                            position: 'topRight'
                        });
                    }
                });
            });
        }

        /* ===== Kanban Action Helpers ===== */
        function updateTargetStatus(targetId, status, cardEl, fromContainer, toContainer) {
            $.ajax({
                url: '<?= base_url("admin/design/update-target-status-ajax") ?>',
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    target_id: targetId,
                    status: status
                },
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        iziToast.success({
                            title: 'Berhasil',
                            message: res.message,
                            position: 'topRight'
                        });
                        updateCounters();
                        updateCardBadge(cardEl, status);
                    } else {
                        iziToast.error({
                            title: 'Gagal',
                            message: res.message,
                            position: 'topRight'
                        });
                        $(fromContainer).append(cardEl);
                        updateCounters();
                    }
                },
                error: function () {
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan sistem.',
                        position: 'topRight'
                    });
                    $(fromContainer).append(cardEl);
                    updateCounters();
                }
            });
        }

        function confirmApproveDesign(designId, targetId, cardEl, fromContainer, toContainer) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Apakah Anda yakin ingin menyetujui hasil desain ini? Semua revisi pending lain untuk target ini akan otomatis ditolak.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("admin/design/approve-design") ?>/' + designId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                updateTargetStatus(targetId, 'DONE', cardEl, fromContainer, toContainer);
                            } else {
                                iziToast.error({
                                    title: 'Gagal',
                                    message: res.message,
                                    position: 'topRight'
                                });
                                $(fromContainer).append(cardEl);
                                updateCounters();
                            }
                        },
                        error: function () {
                            iziToast.error({
                                title: 'Error',
                                message: 'Gagal menyetujui desain.',
                                position: 'topRight'
                            });
                            $(fromContainer).append(cardEl);
                            updateCounters();
                        }
                    });
                } else {
                    $(fromContainer).append(cardEl);
                    updateCounters();
                }
            });
        }

        function showRejectModal(designId, targetId, nextStatus, cardEl, fromContainer, toContainer) {
            $('#kanbanRejectDesignId').val(designId);
            $('#kanbanRejectTargetId').val(targetId);
            $('#kanbanRejectNote').val('');
            
            var modal = new bootstrap.Modal(document.getElementById('kanbanModalReject'));
            modal.show();
            
            $('#btnBatalReject, [data-bs-dismiss="modal"]').off('click').on('click', function() {
                $(fromContainer).append(cardEl);
                updateCounters();
            });
            
            $('#kanbanModalReject').off('hidden.bs.modal').on('hidden.bs.modal', function () {
                if ($('#kanbanRejectDesignId').val() !== '') {
                    $(fromContainer).append(cardEl);
                    updateCounters();
                }
            });

            $('#kanbanRejectForm').off('submit').on('submit', function (e) {
                e.preventDefault();
                var note = $('#kanbanRejectNote').val();
                if (!note) return;
                
                $('#kanbanRejectDesignId').val('');
                modal.hide();
                
                // Gunakan FormData untuk mengirim data teks dan file lampiran sekaligus
                var formData = new FormData(this);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                $.ajax({
                    url: '<?= base_url("admin/design/reject-design") ?>/' + designId,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            updateTargetStatus(targetId, nextStatus, cardEl, fromContainer, toContainer);
                        } else {
                            iziToast.error({
                                title: 'Gagal',
                                message: res.message,
                                position: 'topRight'
                            });
                            $(fromContainer).append(cardEl);
                            updateCounters();
                        }
                    },
                    error: function () {
                        iziToast.error({
                            title: 'Error',
                            message: 'Gagal menolak desain.',
                            position: 'topRight'
                        });
                        $(fromContainer).append(cardEl);
                        updateCounters();
                    }
                });
            });
        }

        /* ===== Aksi Approve / Reject Desain Langsung dari Modal Detail Kartu ===== */
        window.confirmApproveDesignInModal = function (designId) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Apakah Anda yakin ingin menyetujui hasil desain ini? Semua revisi pending lain untuk target ini akan otomatis ditolak.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("admin/design/approve-design") ?>/' + designId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                iziToast.success({
                                    title: 'Berhasil',
                                    message: res.message,
                                    position: 'topRight'
                                });
                                location.reload(); // Muat ulang halaman agar kolom kanban tersinkronisasi
                            } else {
                                iziToast.error({
                                    title: 'Gagal',
                                    message: res.message,
                                    position: 'topRight'
                                });
                            }
                        },
                        error: function () {
                            iziToast.error({
                                title: 'Error',
                                message: 'Gagal menyetujui desain.',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });
        };

        window.showRejectModalInModal = function (designId) {
            var targetId = $('#modalUploadTargetId').val();
            
            $('#kanbanRejectDesignId').val(designId);
            $('#kanbanRejectTargetId').val(targetId);
            $('#kanbanRejectNote').val('');
            $('#kanbanRejectImages').val('');
            
            var modal = new bootstrap.Modal(document.getElementById('kanbanModalReject'));
            modal.show();
            
            $('#kanbanRejectForm').off('submit').on('submit', function (e) {
                e.preventDefault();
                var note = $('#kanbanRejectNote').val();
                if (!note) return;
                
                modal.hide();
                
                var formData = new FormData(this);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                $.ajax({
                    url: '<?= base_url("admin/design/reject-design") ?>/' + designId,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            // Update status target kanban kembali ke ON PROGRESS
                            $.ajax({
                                url: '<?= base_url("admin/design/update-target-status-ajax") ?>',
                                type: 'POST',
                                data: {
                                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                                    target_id: targetId,
                                    status: 'ON PROGRESS'
                                },
                                dataType: 'json',
                                success: function () {
                                    iziToast.success({
                                        title: 'Berhasil',
                                        message: 'Desain berhasil ditolak dan status dikembalikan.',
                                        position: 'topRight'
                                    });
                                    location.reload();
                                },
                                error: function() {
                                    location.reload();
                                }
                            });
                        } else {
                            iziToast.error({
                                title: 'Gagal',
                                message: res.message,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function () {
                        iziToast.error({
                            title: 'Error',
                            message: 'Gagal menolak desain.',
                            position: 'topRight'
                        });
                    }
                });
            });
        };

        /* ===== Kanban Search Filter ===== */
        function runKanbanSearch(query) {
            query = query.toLowerCase().trim();
            
            $('.kanban-card').each(function () {
                var card = this;
                var taskName = $(card).find('.kanban-card-title').text().toLowerCase();
                var concept = $(card).find('.kanban-card-concept').text().toLowerCase();
                // Cari data klien, biarkan jika title "Klien"
                var client = $(card).find('[title="Klien"]').text().toLowerCase();
                var designer = $(card).find('.kanban-card-designer-select option:selected').text().toLowerCase();
                
                if (taskName.includes(query) || concept.includes(query) || client.includes(query) || designer.includes(query)) {
                    $(card).show();
                } else {
                    $(card).hide();
                }
            });
            
            updateCounters();
        }

        $('#kanbanSearchInput').on('input', function () {
            var val = this.value;
            runKanbanSearch(val);
            // Toggle tombol clear
            $('#kanbanSearchClear').css('display', val.length > 0 ? 'block' : 'none');
        });

        $('#kanbanSearchClear').on('click', function () {
            $('#kanbanSearchInput').val('').trigger('input').focus();
        });

        function updateCounters() {
            $('.kanban-column').each(function () {
                var col = this;
                var count = $(col).find('.kanban-card:visible').length;
                $(col).find('.kanban-column-count').text(count);
                
                var body = $(col).find('.kanban-column-body');
                if (count === 0) {
                    if (body.find('.kanban-column-empty').length === 0) {
                        body.append('<div class="kanban-column-empty"><i class="fas fa-tasks fa-lg opacity-50"></i><span>Kosong</span></div>');
                    }
                } else {
                    body.find('.kanban-column-empty').remove();
                }
            });
        }
        
        function updateCardBadge(cardEl, status) {
            var badge = $(cardEl).find('.kanban-card-badge');
            badge.removeClass('badge-pending badge-progress badge-review badge-revisi badge-done');
            
            if (status === 'PENDING') {
                badge.addClass('badge-pending').text('Belum Dikerjakan');
            } else if (status === 'ON PROGRESS') {
                var hasPreview = $(cardEl).find('.review-preview-row').length > 0;
                if (hasPreview) {
                    badge.addClass('badge-revisi').text('Perlu Revisi');
                } else {
                    badge.addClass('badge-progress').text('Sedang Diproses');
                }
            } else if (status === 'DONE') {
                badge.addClass('badge-done').text('Selesai');
            }
        }

        /* ===== Kanban Card Click Detail Modal ===== */
        $(document).on('click', '.kanban-card', function (e) {
            // Jangan buka modal jika mengklik select dropdown, glightbox link, atau button aksi detail
            if ($(e.target).closest('.kanban-card-designer-select, .review-preview-item, .btn-kanban-action').length) {
                return;
            }
            
            var card = this;
            var targetId = $(card).attr('data-id');
            var taskName = $(card).attr('data-task-name');
            var concept = $(card).attr('data-concept');
            var client = $(card).attr('data-client-name');
            var designer = $(card).find('.kanban-card-designer-select option:selected').text().trim();
            var timeline = $(card).attr('data-timeline');
            var projectTimeline = $(card).attr('data-project-timeline');
            var status = $(card).attr('data-status');
            
            // Dapatkan badge class
            var badgeEl = $(card).find('.kanban-card-badge');
            var badgeClass = badgeEl.attr('class');
            var statusText = badgeEl.text().trim();
            
            var keterangan = $(card).attr('data-keterangan');
            var createdAt = $(card).attr('data-created-at');
            var requestId = $(card).attr('data-request-id');
            
            // Reset form upload dan set data target
            if ($('#modalUploadDesignForm').length) {
                $('#modalUploadDesignForm')[0].reset();
                $('#modalUploadFileNameLabel').text('Pilih file...');
                $('#modalUploadTargetId').val(targetId);
                $('#modalUploadDesignForm').attr('action', '<?= base_url("admin/design/add-design-result") ?>/' + requestId);
            }
            
            // Isi data modal
            $('#modalDetailTaskName').text(taskName);
            
            // Set desainer dengan toleransi jika kosong
            if (!designer || designer.startsWith('—')) {
                designer = 'Belum Ditugaskan';
            }
            $('#modalDetailDesigner').html('<i class="fas fa-user-circle opacity-75 me-1"></i> ' + designer);
            $('#modalDetailTimeline').text(timeline);
            $('#modalDetailTimelineHeader').text(projectTimeline);
            $('#modalDetailKeterangan').val(keterangan);
            $('#modalDetailClientName').text(client || 'Internal');
            
            // Atur class badge status di modal
            var targetBadgeClass = 'modal-badge';
            if (badgeClass.includes('badge-pending')) targetBadgeClass += ' modal-badge-pending';
            else if (badgeClass.includes('badge-progress')) targetBadgeClass += ' modal-badge-progress';
            else if (badgeClass.includes('badge-review')) targetBadgeClass += ' modal-badge-review';
            else if (badgeClass.includes('badge-revisi')) targetBadgeClass += ' modal-badge-revisi';
            else if (badgeClass.includes('badge-done')) targetBadgeClass += ' modal-badge-done';
            else targetBadgeClass += ' modal-badge-secondary';
            
            $('#modalDetailStatus').attr('class', targetBadgeClass).text(statusText);
            

            // Sembunyikan kolom kanan jika status Belum Dikerjakan (pending)
            if (badgeClass.includes('badge-pending')) {
                $('#modalDetailLeftCol').removeClass('d-none col-6 border-end pe-4 col-12').addClass('col-12');
                $('#modalDetailRightCol').addClass('d-none').removeClass('d-flex col-6 ps-4 col-12');
                $('#kanbanCardDetailModal .modal-dialog').removeClass('modal-xl').addClass('modal-lg');
                $('#kanbanModalFooter').hide();
                
                // Form keterangan dapat diedit hanya oleh kadiv
                if (isKadiv) {
                    $('#modalDetailKeterangan').prop('readonly', false).attr('placeholder', 'Tulis keterangan atau instruksi tugas di sini...');
                    $('#btnSaveKeterangan').show();
                } else {
                    $('#modalDetailKeterangan').prop('readonly', true).attr('placeholder', 'Tidak ada keterangan tambahan.');
                    $('#btnSaveKeterangan').hide();
                }
            } else {
                // Untuk status bukan pending (Progress, Tinjauan, Selesai): Sembunyikan kolom informasi (kiri)
                $('#modalDetailLeftCol').addClass('d-none').removeClass('col-12 col-6 border-end pe-4');
                $('#modalDetailRightCol').removeClass('d-none col-6 ps-4 col-12 d-flex flex-column').addClass('col-12');
                
                // Form keterangan hanya dapat dilihat (readonly)
                $('#modalDetailKeterangan').prop('readonly', true).attr('placeholder', 'Tidak ada keterangan tambahan.');
                $('#btnSaveKeterangan').hide();

                // ── Aturan show/hide Upload Section berdasarkan status ──
                var isProgress = badgeClass.includes('badge-progress') || badgeClass.includes('badge-revisi');

                if (isProgress) {
                    // Sedang Diproses / Perlu Revisi: tampilkan form upload + hasil desain berdampingan (2 kolom)
                    $('#kanbanCardDetailModal .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
                    $('#modalUploadSection').show().removeClass('col-12 border-bottom pb-4').addClass('col-md-6 border-end pb-0');
                    $('#modalDesignResultsSection').removeClass('col-12 pt-4').addClass('col-md-6 pt-0').css('max-height', '');
                    $('#modalDesignResultsList').css('max-height', '320px');
                    $('#kanbanModalFooter').show();
                } else {
                    // Dalam Tinjauan / Selesai: sembunyikan form upload, hasil desain satu kolom penuh
                    $('#kanbanCardDetailModal .modal-dialog').removeClass('modal-xl').addClass('modal-lg');
                    $('#modalUploadSection').hide();
                    $('#modalDesignResultsSection').removeClass('col-md-6 pt-0').addClass('col-12 pt-0').css('max-height', '');
                    $('#modalDesignResultsList').css('max-height', '420px');
                    $('#kanbanModalFooter').hide();
                }
                
                // Ambil daftar hasil desain via AJAX hanya jika status bukan pending
                $('#modalDesignResultsList').html('<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-1"></i> Memuat berkas desain...</div>');
                $.ajax({
                    url: '<?= base_url("admin/design/get-target-designs-ajax") ?>',
                    type: 'POST',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                        target_id: targetId
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status && res.data.length > 0) {
                            // Kelompokkan file berdasarkan nomor revisi
                            var grouped = {};
                            res.data.forEach(function (item) {
                                var rev = item.revision_number || 1;
                                if (!grouped[rev]) {
                                    grouped[rev] = [];
                                }
                                grouped[rev].push(item);
                            });

                            // Urutkan nomor revisi dari yang terbaru (terbesar)
                            var revNums = Object.keys(grouped).map(Number).sort(function (a, b) { return b - a; });

                            var html = '<div class="timeline-container" style="position: relative; padding-left: 20px; border-left: 2px solid #e2e8f0; margin-left: 10px; text-align: left;">';
                            
                            revNums.forEach(function (revNum, index) {
                                var files = grouped[revNum];
                                var firstDesign = files[0];
                                var revSt = firstDesign.status || 'PENDING';
                                var formattedDate = new Date(firstDesign.created_at).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                }) + ', ' + new Date(firstDesign.created_at).toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                var badgeClass = 'bg-warning text-dark';
                                var headerBg = '#fffdf5';
                                var headerBorder = '#fef3c7';
                                if (revSt === 'APPROVED') {
                                    badgeClass = 'bg-success text-white';
                                    headerBg = '#f0fdf4';
                                    headerBorder = '#bbf7d0';
                                } else if (revSt === 'REJECTED') {
                                    badgeClass = 'bg-danger text-white';
                                    headerBg = '#fef2f2';
                                    headerBorder = '#fecaca';
                                }

                                var dotColor = '#cbd5e1';
                                if (revSt === 'APPROVED') dotColor = '#22c55e';
                                else if (revSt === 'REJECTED') dotColor = '#ef4444';

                                var isFirst = index === 0;

                                html += '<div class="timeline-item mb-3" style="position: relative;">';
                                // Timeline Dot
                                html += '  <div class="timeline-dot" style="position: absolute; left: -26px; top: 12px; width: 10px; height: 10px; border-radius: 50%; background: ' + dotColor + '; border: 2px solid #fff; box-shadow: 0 0 0 2px ' + dotColor + '44;"></div>';
                                
                                // Revision Header Card
                                html += '  <div class="rev-header-card d-flex justify-content-between align-items-center p-2 rounded" style="background: ' + headerBg + '; border: 1px solid ' + headerBorder + '; cursor: pointer; user-select: none;" onclick="$(\'#rev-body-' + revNum + '\').collapse(\'toggle\')">';
                                html += '    <div class="d-flex align-items-center gap-2">';
                                html += '      <i class="fas fa-chevron-down text-muted" style="font-size: 9px;"></i>';
                                if (revSt === 'APPROVED') {
                                    html += '  <span style="font-size:12px;">✅</span>';
                                }
                                html += '      <span class="fw-bold text-dark" style="font-size:12px; font-family: \'Plus Jakarta Sans\', sans-serif;">Rev. ' + revNum + '</span>';
                                html += '      <span class="badge ' + badgeClass + ' px-2 py-0.5" style="font-size: 8px; font-weight: 700; border-radius: 4px;">' + revSt + '</span>';
                                html += '    </div>';
                                html += '    <div class="d-flex align-items-center gap-3">';
                                html += '      <small class="text-muted" style="font-size: 9px;"><i class="far fa-calendar-alt me-1"></i>' + formattedDate + '</small>';
                                if (revSt === 'PENDING') {
                                    html += '  <div class="d-flex gap-2 ms-2" onclick="event.stopPropagation();">';
                                    html += '    <button type="button" class="btn btn-xs btn-success fw-bold" style="font-size: 9.5px; border-radius: 6px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;" onclick="confirmApproveDesignInModal(' + firstDesign.id + ')"><i class="fas fa-check"></i> Approve</button>';
                                    html += '    <button type="button" class="btn btn-xs btn-outline-danger fw-bold" style="font-size: 9.5px; border-radius: 6px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;" onclick="showRejectModalInModal(' + firstDesign.id + ')"><i class="fas fa-times"></i> Reject</button>';
                                    html += '  </div>';
                                }
                                html += '    </div>';
                                html += '  </div>';

                                // Revision Body (Accordion Collapse)
                                html += '  <div id="rev-body-' + revNum + '" class="collapse ' + (isFirst ? 'show' : '') + ' mt-2">';
                                html += '    <div class="d-flex flex-column gap-2">';

                                files.forEach(function (fileItem) {
                                    var fileUrl = '<?= base_url("uploads/design_results") ?>/' + fileItem.file;
                                    var ext = fileItem.file.split('.').pop().toLowerCase();
                                    var fileType = fileItem.design_type || 'general';

                                    html += '  <div class="d-flex align-items-center gap-3 p-2 rounded border bg-light design-file-item" style="border: 1px solid #e2e8f0 !important; background: #fafbfc !important;">';
                                    
                                    // Render Icon / Thumb
                                    if (fileType === 'pdf') {
                                        html += '    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;border-radius:8px;background:#fee2e2;border: 1px solid #fca5a5;">';
                                        html += '      <i class="far fa-file-pdf text-danger" style="font-size:20px;"></i>';
                                        html += '    </div>';
                                    } else if (fileType === 'video') {
                                        html += '    <div class="d-flex align-items-center justify-content-center flex-shrink-0 position-relative" style="width:48px;height:48px;border-radius:8px;background:#fef3c7;border: 1px solid #fcd34d;">';
                                        html += '      <i class="far fa-file-video text-warning" style="font-size:20px;"></i>';
                                        html += '      <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);"><i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:10px;"></i></span>';
                                        html += '    </div>';
                                    } else if (fileType === '3d') {
                                        html += '    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;border-radius:8px;background:#ecfeff;border: 1px solid #a5f3fc;">';
                                        html += '      <i class="fas fa-cubes text-info" style="font-size:20px;"></i>';
                                        html += '    </div>';
                                    } else {
                                        html += '    <img src="' + fileUrl + '" style="width:48px;height:48px;object-fit:cover;border-radius:8px;flex-shrink:0;border: 1px solid #cbd5e1;" alt="' + fileItem.design_name + '">';
                                    }

                                    // Render File Details
                                    html += '    <div class="flex-grow-1 min-w-0">';
                                    html += '      <div class="fw-semibold text-dark text-truncate" style="font-size:11px;" title="' + fileItem.design_name + '">' + fileItem.design_name + '</div>';
                                    html += '      <small class="text-muted d-block" style="font-size: 8px; margin-top: 1px; text-transform: uppercase;">' + (fileType === '3d' ? '3D OBJECT' : ext) + '</small>';
                                    
                                    // Render Action Buttons under details
                                    html += '      <div class="mt-2 d-flex gap-2 align-items-center">';
                                    if (fileType === '3d') {
                                        var safeFile = (fileItem.file || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                                        html += '    <button type="button" class="btn btn-xs btn-outline-info px-2 py-0.5 fw-bold" style="font-size: 8.5px; border-radius: 4px;" title="Salin Nama Objek" onclick="navigator.clipboard.writeText(\'' + safeFile + '\'); iziToast.success({title: \'Copied\', message: \'Nama objek disalin!\', position: \'topRight\'})">';
                                        html += '      <i class="far fa-copy me-0.5"></i> Salin Nama Objek';
                                        html += '    </button>';
                                    } else if (fileType === 'pdf') {
                                        html += '    <a href="' + fileUrl + '" target="_blank" class="btn btn-xs btn-outline-danger px-2 py-0.5 fw-bold" style="font-size: 8.5px; border-radius: 4px;" title="Lihat PDF">';
                                        html += '      <i class="fas fa-file-pdf me-0.5"></i> Lihat PDF';
                                        html += '    </a>';
                                    } else if (fileType === 'video') {
                                        html += '    <a href="' + fileUrl + '" target="_blank" class="btn btn-xs btn-outline-warning px-2 py-0.5 fw-bold" style="font-size: 8.5px; border-radius: 4px;" title="Putar Video">';
                                        html += '      <i class="fas fa-play me-0.5"></i> Putar Video';
                                        html += '    </a>';
                                    } else {
                                        html += '    <a href="' + fileUrl + '" target="_blank" class="btn btn-xs btn-outline-primary px-2 py-0.5 fw-bold" style="font-size: 8.5px; border-radius: 4px;" title="Lihat Gambar">';
                                        html += '      <i class="fas fa-eye me-0.5"></i> Lihat';
                                        html += '    </a>';
                                    }

                                    // Hapus Button (Khusus Admin)
                                    html += '        <a href="<?= base_url("admin/design/delete-design") ?>/' + fileItem.id + '" class="btn btn-xs btn-outline-danger px-2 py-0.5 fw-bold" style="font-size: 8.5px; border-radius: 4px;" onclick="return confirm(\'Hapus file ini?\');" title="Hapus">';
                                    html += '          <i class="fas fa-trash-alt me-0.5"></i> Hapus';
                                    html += '        </a>';

                                    html += '      </div>';
                                    html += '    </div>';
                                    html += '  </div>'; // End design item
                                });

                                // Revision Note (if exists)
                                if (firstDesign.revision_note) {
                                    html += '    <div class="mt-2 px-3 py-2 rounded bg-light border-start border-3 border-secondary text-start" style="font-size:11px; line-height: 1.4; color: #475569;">';
                                    html += '      <i class="fas fa-comment-alt text-muted me-1"></i> <strong>Catatan:</strong> ' + firstDesign.revision_note;
                                    html += '    </div>';
                                }

                                html += '    </div>'; // End flex-column
                                html += '  </div>'; // End collapse
                                html += '</div>'; // End timeline-item
                            });

                            html += '</div>'; // End timeline-container
                            $('#modalDesignResultsList').html(html);
                        } else {
                            $('#modalDesignResultsList').html('<div class="text-center py-4 text-muted" style="font-size: 12px;"><i class="fas fa-folder-open fa-lg d-block mb-2 opacity-50"></i>Belum ada hasil desain diunggah.</div>');
                        }
                    },
                    error: function () {
                        $('#modalDesignResultsList').html('<div class="text-center py-4 text-danger" style="font-size: 12px;"><i class="fas fa-exclamation-triangle fa-lg d-block mb-2 opacity-50"></i>Gagal memuat berkas desain.</div>');
                    }
                });
            }
            
            // Tampilkan modal
            var detailModal = new bootstrap.Modal(document.getElementById('kanbanCardDetailModal'));
            detailModal.show();
        });

        /* ===== Kanban Dropzone & File Preview Logic ===== */
        var modalUploadedFiles = [];

        function formatBytes(bytes, decimals = 1) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Klik dropzone mentrigger input file
        $(document).on('click', '#dropzoneArea', function () {
            $('#modalUploadFile').click();
        });
        $(document).on('click', '#modalUploadFile', function (e) {
            e.stopPropagation();
        });

        // Event Drag and Drop
        $(document).on('dragenter dragover', '#dropzoneArea', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        $(document).on('dragleave drop', '#dropzoneArea', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });
        $(document).on('drop', '#dropzoneArea', function (e) {
            var dt = e.originalEvent.dataTransfer;
            var files = dt.files;
            if (files && files.length > 0) {
                addModalFilesToQueue(files);
            }
        });

        function addModalFilesToQueue(files) {
            Array.from(files).forEach(function (file) {
                modalUploadedFiles.push(file);
            });
            renderModalPreviews();
        }

        $(document).on('change', '#modalUploadFile', function (e) {
            if (e.target.files && e.target.files.length > 0) {
                addModalFilesToQueue(e.target.files);
                e.target.value = '';
            }
        });

        $(document).on('input', '#modal3dObjectNameInput', function () {
            renderModalPreviews();
        });

        window.removeModalPreviewFile = function (index) {
            modalUploadedFiles.splice(index, 1);
            renderModalPreviews();
        };

        window.clearModal3dInput = function () {
            $('#modal3dObjectNameInput').val('');
            renderModalPreviews();
        };

        function renderModalPreviews() {
            var previewContainer = $('#modalUploadPreviewContainer');
            var previewList = previewContainer.find('.preview-files-list');
            if (!previewList.length) return;

            previewList.html('');
            var objectVal = $('#modal3dObjectNameInput').val().trim();
            var hasItems = false;

            // Render 3D object name
            if (objectVal.length > 0) {
                hasItems = true;
                var itemHtml = `
                    <div class="preview-item">
                        <div class="preview-item-info">
                            <div class="preview-thumb-container file-icon-3d">
                                <i class="fas fa-cubes" style="font-size: 16px; color: #0ea5e9;"></i>
                            </div>
                            <div class="preview-file-details">
                                <div class="preview-file-name" title="${objectVal}">3D: ${objectVal}</div>
                                <small class="preview-file-size">Objek Unity / String</small>
                            </div>
                        </div>
                        <button type="button" class="btn-remove-preview" onclick="clearModal3dInput()" title="Hapus Objek 3D">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                previewList.append(itemHtml);
            }

            // Render all files
            modalUploadedFiles.forEach(function (file, index) {
                hasItems = true;
                var ext = file.name.split('.').pop().toLowerCase();
                var badgeClass = 'file-icon-general';
                var iconClass = 'far fa-file-alt';
                var isImage = false;
                var thumbUrl = '';
                var typeLabel = 'UMUM';
                var typeBadgeColor = '#94a3b8';

                if (file.type.startsWith('image/')) {
                    isImage = true;
                    thumbUrl = URL.createObjectURL(file);
                    badgeClass = 'file-icon-image';
                    typeLabel = 'GAMBAR'; typeBadgeColor = '#6366f1';
                } else if (file.type === 'application/pdf' || ext === 'pdf') {
                    badgeClass = 'file-icon-pdf';
                    iconClass = 'far fa-file-pdf';
                    typeLabel = 'PDF'; typeBadgeColor = '#ef4444';
                } else if (file.type.startsWith('video/') || ['mp4', 'mov', 'avi', 'webm', 'mkv'].includes(ext)) {
                    badgeClass = 'file-icon-video';
                    iconClass = 'far fa-file-video';
                    typeLabel = 'VIDEO'; typeBadgeColor = '#f59e0b';
                } else if (['obj', 'fbx', 'glb', 'gltf', 'dwg', 'rvt'].includes(ext)) {
                    badgeClass = 'file-icon-3d';
                    iconClass = 'fas fa-cubes';
                    typeLabel = '3D'; typeBadgeColor = '#0ea5e9';
                }

                var visualBlock = isImage
                    ? `<img src="${thumbUrl}" class="preview-thumb-img" onload="window.URL.revokeObjectURL('${thumbUrl}')">`
                    : `<i class="${iconClass}" style="font-size: 16px;"></i>`;

                var itemHtml = `
                    <div class="preview-item">
                        <div class="preview-item-info">
                            <div class="preview-thumb-container ${badgeClass}">
                                ${visualBlock}
                            </div>
                            <div class="preview-file-details">
                                <div class="preview-file-name" title="${file.name}">${file.name}</div>
                                <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                                    <small class="preview-file-size">${formatBytes(file.size)}</small>
                                    <span style="font-size:8.5px; font-weight:800; padding:1px 5px; border-radius:4px; background:${typeBadgeColor}18; color:${typeBadgeColor}; border:1px solid ${typeBadgeColor}44; text-transform:uppercase; letter-spacing:0.4px;">${typeLabel}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-remove-preview" onclick="removeModalPreviewFile(${index})" title="Hapus Berkas">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                previewList.append(itemHtml);
            });

            if (hasItems) {
                previewContainer.removeClass('d-none');
            } else {
                previewContainer.addClass('d-none');
            }
        }

        // Generate Key untuk modal
        $(document).on('click', '#btnGenerate3dKey', function () {
            var btn = this;
            var originalHtml = $(btn).html();
            $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');

            var taskName = $('#modalDetailTaskName').text().trim();
            var clientName = $('#modalDetailClientName').text().trim();

            var cleanClientName = clientName.replace(/[^a-zA-Z0-9]/g, '_').replace(/_+/g, '_').replace(/^_+|_+$/g, '');
            var cleanTargetName = taskName.replace(/[^a-zA-Z0-9]/g, '_').replace(/_+/g, '_').replace(/^_+|_+$/g, '');

            var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var randomPart = '';
            for (var i = 0; i < 10; i++) {
                randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            var randomKey = '';
            if (cleanClientName) randomKey += cleanClientName + '_';
            if (cleanTargetName) randomKey += cleanTargetName + '_';
            randomKey += randomPart;

            $.ajax({
                url: '<?= base_url("admin/design/check-3d-name-ajax") ?>',
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    name: randomKey
                },
                dataType: 'json',
                success: function (res) {
                    $(btn).prop('disabled', false).html(originalHtml);
                    if (res.status && !res.exists) {
                        $('#modal3dObjectNameInput').val(randomKey);
                        renderModalPreviews();
                        iziToast.success({title: 'Success', message: 'Unique 3D Key berhasil dibuat!', position: 'topRight'});
                    } else {
                        iziToast.error({title: 'Error', message: 'Key sudah terpakai, klik kembali untuk generate key baru.', position: 'topRight'});
                    }
                },
                error: function () {
                    $(btn).prop('disabled', false).html(originalHtml);
                    // Fallback
                    $('#modal3dObjectNameInput').val(randomKey);
                    renderModalPreviews();
                }
            });
        });

        // Submit sync
        $(document).on('submit', '#modalUploadDesignForm', function (e) {
            var objectInput = $('#modal3dObjectNameInput').val().trim();
            var hasFiles = modalUploadedFiles.length > 0;
            var hasObject = objectInput.length > 0;

            if (!hasFiles && !hasObject) {
                e.preventDefault();
                iziToast.error({
                    title: 'Validasi Gagal',
                    message: 'Wajib mengunggah file desain atau mengisi nama objek 3D!',
                    position: 'topRight'
                });
                return;
            }

            // Sync files
            var filesInput = $('#modalUploadFile')[0];
            if (filesInput) {
                var dt = new DataTransfer();
                modalUploadedFiles.forEach(function (file) {
                    dt.items.add(file);
                });
                filesInput.files = dt.files;
            }
        });

        // Reset files queue when modal is closed
        $('#kanbanCardDetailModal').on('hidden.bs.modal', function () {
            modalUploadedFiles = [];
            renderModalPreviews();
        });

        /* ===== Save Keterangan Description Handler ===== */
        $(document).on('click', '#btnSaveKeterangan', function () {
            var btn = this;
            var targetId = $('#modalUploadTargetId').val();
            var keterangan = $('#modalDetailKeterangan').val();
            
            $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
            
            $.ajax({
                url: '<?= base_url("admin/design/update-target-keterangan-ajax") ?>',
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    target_id: targetId,
                    keterangan: keterangan
                },
                dataType: 'json',
                success: function (res) {
                    $(btn).prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Catatan');
                    if (res.status) {
                        iziToast.success({
                            title: 'Berhasil',
                            message: res.message,
                            position: 'topRight'
                        });
                        $('[data-id="' + targetId + '"]').attr('data-keterangan', keterangan);
                    } else {
                        iziToast.error({
                            title: 'Gagal',
                            message: res.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function () {
                    $(btn).prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Catatan');
                    iziToast.error({
                        title: 'Error',
                        message: 'Terjadi kesalahan saat menyimpan keterangan.',
                        position: 'topRight'
                    });
                }
            });
        });

        /* ===== Tooltips ===== */
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
