<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil!',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 6000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function () {
        var ordersLoaded = false;
        var projectsLoaded = false;

        // Lazy load tab contents on click
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetId = $(e.target).attr('id');
            
            if (targetId === 'orders-tab' && !ordersLoaded) {
                loadOrders();
            } else if (targetId === 'projects-tab' && !projectsLoaded) {
                loadProjects();
            }
        });

        // Formatting Helpers
        function formatRupiah(number) {
            if (number === null || number === undefined) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            var date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) + ' ' + date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getOrderStatusBadge(status) {
            var statusLower = (status || '').toLowerCase();
            var badges = {
                'success': '<span class="badge bg-success">Success</span>',
                'completed': '<span class="badge bg-success">Completed</span>',
                'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                'processing': '<span class="badge bg-info text-white">Processing</span>',
                'failed': '<span class="badge bg-danger">Failed</span>',
                'cancelled': '<span class="badge bg-danger">Cancelled</span>'
            };
            return badges[statusLower] || '<span class="badge bg-secondary">' + status + '</span>';
        }

        function getProjectStatusBadge(status) {
            var statusLower = (status || '').toLowerCase();
            var badges = {
                'survey': '<span class="badge bg-info text-white">Survey</span>',
                'design': '<span class="badge text-white" style="background-color:#0d9488;">Design</span>',
                'rab': '<span class="badge bg-primary">RAB</span>',
                'contract': '<span class="badge bg-secondary">Contract</span>',
                'invoice': '<span class="badge bg-warning text-dark">Invoice</span>',
                'work': '<span class="badge text-white" style="background-color:#6f42c1;">Work</span>',
                'done': '<span class="badge bg-success">Done</span>',
                'rejected': '<span class="badge bg-danger">Rejected</span>',
                'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                'approved': '<span class="badge bg-success">Approved</span>'
            };
            return badges[statusLower] || '<span class="badge bg-secondary">' + status + '</span>';
        }

        function escHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;")
                      .replace(/</g, "&lt;")
                      .replace(/>/g, "&gt;")
                      .replace(/"/g, "&quot;")
                      .replace(/'/g, "&#039;");
        }

        // AJAX loader for Orders
        function loadOrders() {
            $('#orders-loading').removeClass('d-none');
            $('#orders-table-wrapper').addClass('d-none');
            $('#orders-empty').addClass('d-none');
            $('#orders-error').addClass('d-none');
            
            $.ajax({
                url: '<?= base_url("admin/users/get_orders/" . $user["id"]) ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#orders-loading').addClass('d-none');
                    if (response.status === 'success') {
                        var orders = response.data;
                        if (orders && orders.length > 0) {
                            var html = '';
                            orders.forEach(function(order) {
                                var totalVal = order.total_price || order.total_payment || 0;
                                var detailUrl = '<?= base_url("admin/orders/detail") ?>/' + order.id;
                                html += '<tr>' +
                                    '<td class="fw-bold">#' + order.id + '</td>' +
                                    '<td class="fw-semibold">' + formatRupiah(totalVal) + '</td>' +
                                    '<td class="text-center">' + getOrderStatusBadge(order.status) + '</td>' +
                                    '<td class="text-center text-muted">' + formatDate(order.created_at) + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="' + detailUrl + '" class="btn btn-sm btn-outline-primary" style="border-radius:6px; font-size:0.78rem; padding: 4px 10px;">' +
                                            '<i class="fas fa-eye me-1"></i>Detail' +
                                        '</a>' +
                                    '</td>' +
                                    '</tr>';
                            });
                            $('#orders-table-body').html(html);
                            $('#orders-table-wrapper').removeClass('d-none');
                            ordersLoaded = true;
                        } else {
                            $('#orders-empty').removeClass('d-none');
                        }
                    } else {
                        $('#orders-error').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#orders-loading').addClass('d-none');
                    $('#orders-error').removeClass('d-none');
                }
            });
        }

        // AJAX loader for Projects
        function loadProjects() {
            $('#projects-loading').removeClass('d-none');
            $('#projects-wrapper').addClass('d-none');
            $('#projects-empty').addClass('d-none');
            $('#projects-error').addClass('d-none');
            
            $.ajax({
                url: '<?= base_url("admin/users/get_projects/" . $user["id"]) ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#projects-loading').addClass('d-none');
                    if (response.status === 'success') {
                        var data = response.data;
                        var construction = data.construction || [];
                        var design = data.design || [];
                        var renovation = data.renovation || [];
                        
                        var totalCount = construction.length + design.length + renovation.length;
                        
                        if (totalCount === 0) {
                            $('#projects-empty').removeClass('d-none');
                            return;
                        }
                        
                        // 1. Construction Table
                        $('#construction-badge-count').text(construction.length);
                        var cHtml = '';
                        if (construction.length > 0) {
                            construction.forEach(function(item) {
                                var landBuilding = (item.land_area || '-') + ' m² / ' + (item.building_area || '-') + ' m²';
                                var detailUrl = '<?= base_url("admin/construction/detail") ?>/' + item.id;
                                cHtml += '<tr>' +
                                    '<td class="text-center fw-bold">#' + item.id + '</td>' +
                                    '<td>' + landBuilding + '</td>' +
                                    '<td>' + (item.survey_date || '-') + '</td>' +
                                    '<td class="fw-semibold">' + formatRupiah(item.total_payment) + '</td>' +
                                    '<td class="text-center">' + getProjectStatusBadge(item.status) + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="' + detailUrl + '" class="btn btn-sm btn-outline-primary" style="border-radius:6px; font-size:0.75rem; padding: 4px 10px;">' +
                                            '<i class="fas fa-eye me-1"></i>Detail' +
                                        '</a>' +
                                    '</td>' +
                                    '</tr>';
                            });
                        } else {
                            cHtml = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data pengajuan konstruksi.</td></tr>';
                        }
                        $('#construction-table-body').html(cHtml);
                        
                        // 2. Design Table
                        $('#design-badge-count').text(design.length);
                        var dHtml = '';
                        if (design.length > 0) {
                            design.forEach(function(item) {
                                var landBuilding = (item.land_area || '-') + ' m² / ' + (item.building_area || '-') + ' m²';
                                var detailUrl = '<?= base_url("admin/design/show") ?>/' + item.id;
                                dHtml += '<tr>' +
                                    '<td class="text-center fw-bold">#' + item.id + '</td>' +
                                    '<td>' + escHtml(item.design_concept || '-') + '</td>' +
                                    '<td>' + landBuilding + '</td>' +
                                    '<td class="fw-semibold">' + formatRupiah(item.total_payment) + '</td>' +
                                    '<td class="text-center">' + getProjectStatusBadge(item.status) + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="' + detailUrl + '" class="btn btn-sm btn-outline-primary" style="border-radius:6px; font-size:0.75rem; padding: 4px 10px;">' +
                                            '<i class="fas fa-eye me-1"></i>Detail' +
                                        '</a>' +
                                    '</td>' +
                                    '</tr>';
                            });
                        } else {
                            dHtml = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data pengajuan desain.</td></tr>';
                        }
                        $('#design-table-body').html(dHtml);
                        
                        // 3. Renovation Table
                        $('#renovation-badge-count').text(renovation.length);
                        var rHtml = '';
                        if (renovation.length > 0) {
                            renovation.forEach(function(item) {
                                var detailUrl = '<?= base_url("admin/renovation/detail") ?>/' + item.id;
                                rHtml += '<tr>' +
                                    '<td class="text-center fw-bold">#' + item.id + '</td>' +
                                    '<td>' + escHtml(item.renovation_type || '-') + '</td>' +
                                    '<td>' + escHtml(item.address || '-') + '</td>' +
                                    '<td class="fw-semibold">' + formatRupiah(item.total_payment) + '</td>' +
                                    '<td class="text-center">' + getProjectStatusBadge(item.status) + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="' + detailUrl + '" class="btn btn-sm btn-outline-primary" style="border-radius:6px; font-size:0.75rem; padding: 4px 10px;">' +
                                            '<i class="fas fa-eye me-1"></i>Detail' +
                                        '</a>' +
                                    '</td>' +
                                    '</tr>';
                            });
                        } else {
                            rHtml = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data pengajuan renovasi.</td></tr>';
                        }
                        $('#renovation-table-body').html(rHtml);
                        
                        $('#projects-wrapper').removeClass('d-none');
                        projectsLoaded = true;
                    } else {
                        $('#projects-error').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#projects-loading').addClass('d-none');
                    $('#projects-error').removeClass('d-none');
                }
            });
        }
    });
</script>
