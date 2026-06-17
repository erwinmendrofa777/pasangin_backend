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
                
                $.ajax({
                    url: '<?= base_url("admin/design/reject-design") ?>/' + designId,
                    type: 'POST',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                        revision_note: note
                    },
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

        /* ===== Kanban Search Filter ===== */
        $('#kanbanSearchInput').on('keyup', function () {
            var query = this.value.toLowerCase().trim();
            
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
                $('#modalDetailRightCol').addClass('d-none').removeClass('d-flex');
                $('#modalDetailLeftCol').removeClass('col-6 border-end pe-4').addClass('col-12');
                $('#kanbanCardDetailModal .modal-dialog').removeClass('modal-xl').addClass('modal-lg');
                
                // Form keterangan dapat diedit hanya oleh kadiv
                if (isKadiv) {
                    $('#modalDetailKeterangan').prop('readonly', false).attr('placeholder', 'Tulis keterangan atau instruksi tugas di sini...');
                    $('#btnSaveKeterangan').show();
                } else {
                    $('#modalDetailKeterangan').prop('readonly', true).attr('placeholder', 'Tidak ada keterangan tambahan.');
                    $('#btnSaveKeterangan').hide();
                }
            } else {
                $('#modalDetailRightCol').removeClass('d-none').addClass('d-flex');
                $('#modalDetailLeftCol').removeClass('col-12').addClass('col-6 border-end pe-4');
                $('#kanbanCardDetailModal .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
                
                // Form keterangan hanya dapat dilihat (readonly)
                $('#modalDetailKeterangan').prop('readonly', true).attr('placeholder', 'Tidak ada keterangan tambahan.');
                $('#btnSaveKeterangan').hide();
                
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
                            var html = '';
                            res.data.forEach(function (item) {
                                var fileExt = item.file.split('.').pop().toLowerCase();
                                var fileUrl = '<?= base_url("uploads/design_results") ?>/' + item.file;
                                var iconClass = 'icon-img';
                                var iconFa = 'fa-file-image';
                                
                                if (fileExt === 'pdf') {
                                    iconClass = 'icon-pdf';
                                    iconFa = 'fa-file-pdf';
                                } else if (['mp4', 'mov', 'avi', 'webm', 'mkv'].includes(fileExt)) {
                                    iconClass = 'icon-video';
                                    iconFa = 'fa-file-video';
                                }
                                
                                var badgeClass = 'bg-warning text-dark';
                                var badgeText = 'Menunggu';
                                if (item.status === 'APPROVED') {
                                    badgeClass = 'bg-success text-white';
                                    badgeText = 'Disetujui';
                                } else if (item.status === 'REJECTED') {
                                    badgeClass = 'bg-danger text-white';
                                    badgeText = 'Ditolak';
                                }
                                
                                var formattedDate = new Date(item.created_at).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                });
                                
                                html += '<div class="design-item-row">';
                                html += '  <div class="design-item-info">';
                                html += '    <div class="design-item-icon ' + iconClass + '">';
                                html += '      <i class="fas ' + iconFa + '"></i>';
                                html += '    </div>';
                                html += '    <div class="design-item-details">';
                                html += '      <span class="design-item-title">' + item.design_name + ' <span class="badge rounded-pill ' + badgeClass + '" style="font-size: 8px; padding: 2px 6px;">Rev. ' + item.revision_number + '</span></span>';
                                html += '      <span class="design-item-meta">' + (item.admin_name || 'Desainer') + ' &bull; ' + formattedDate + '</span>';
                                
                                if (item.status === 'REJECTED' && item.revision_note) {
                                    html += '    <small class="text-danger mt-1" style="font-size: 10px; line-height: 1.2;"><i class="fas fa-exclamation-circle me-1"></i>Revisi: ' + item.revision_note + '</small>';
                                }
                                
                                html += '    </div>';
                                html += '  </div>';
                                html += '  <div class="design-item-actions">';
                                html += '    <a href="' + fileUrl + '" target="_blank" class="btn-kanban-action" title="Lihat Berkas" style="width: 26px; height: 26px; margin: 0;">';
                                html += '      <i class="fas fa-eye"></i>';
                                html += '    </a>';
                                html += '    <a href="' + fileUrl + '" download class="btn-kanban-action" title="Unduh" style="width: 26px; height: 26px; margin: 0;">';
                                html += '      <i class="fas fa-download"></i>';
                                html += '    </a>';
                                html += '  </div>';
                                html += '</div>';
                            });
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

        /* ===== File Upload Label Change Handler ===== */
        $(document).on('change', '#modalUploadFile', function () {
            var filename = this.files.length ? this.files[0].name : 'Pilih file...';
            $('#modalUploadFileNameLabel').text(filename);
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
