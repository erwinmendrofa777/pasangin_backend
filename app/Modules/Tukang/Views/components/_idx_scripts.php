<script>
    $(document).ready(function () {
        // Initialize Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Re-initialize lightboxes
        if (window.globalLightbox) {
            window.globalLightbox.reload();
        }

        var currentPage = 1;
        var itemsPerPage = 5;

        // Toggle Detail Row on row click (excluding buttons and links)
        $(document).on('click', '.group-parent-row', function (e) {
            if ($(e.target).closest('a, button').length) {
                return;
            }
            
            var parentRow = $(this);
            var childId = parentRow.attr('id').replace('parent-', 'child-');
            var childRow = $('#' + childId);

            if (parentRow.hasClass('expanded')) {
                parentRow.removeClass('expanded');
                childRow.fadeOut(150);
            } else {
                parentRow.addClass('expanded');
                childRow.fadeIn(150);
            }
        });

        // Toggle Detail Row via explicit Detail button
        $(document).on('click', '.toggle-detail-btn', function (e) {
            e.preventDefault();
            var parentRow = $(this).closest('.group-parent-row');
            var childId = parentRow.attr('id').replace('parent-', 'child-');
            var childRow = $('#' + childId);

            if (parentRow.hasClass('expanded')) {
                parentRow.removeClass('expanded');
                childRow.fadeOut(150);
            } else {
                parentRow.addClass('expanded');
                childRow.fadeIn(150);
            }
        });

        // Display rows for specific page
        function showPage(page) {
            currentPage = page;
            var start = (page - 1) * itemsPerPage;
            var end = start + itemsPerPage;

            var matchingParents = $('.group-parent-row').filter(function() {
                return $(this).data('matches-search') !== false;
            });

            // Hide all parent and child rows
            $('.group-parent-row').hide();
            $('.group-detail-row').hide();

            var searchQuery = $('#searchInput').val().toLowerCase().trim();
            
            matchingParents.slice(start, end).each(function() {
                var parent = $(this);
                parent.show();
                
                var childId = parent.attr('id').replace('parent-', 'child-');
                var childRow = $('#' + childId);
                
                if (searchQuery !== '') {
                    parent.addClass('expanded');
                    childRow.show();
                } else {
                    if (parent.hasClass('expanded')) {
                        childRow.show();
                    }
                }
            });

            updatePaginationControls(matchingParents.length, start, end);
        }

        // Render Pagination buttons and stats info
        function updatePaginationControls(totalItems, start, end) {
            var footer = $('#table-pagination-footer');
            var info = $('#pagination-info');
            var list = $('#pagination-list');
            list.empty();

            if (totalItems === 0) {
                info.text('Tidak ada data kelompok / grup yang cocok');
                list.empty();
                return;
            }

            var displayStart = start + 1;
            var displayEnd = Math.min(end, totalItems);
            info.text('Menampilkan ' + displayStart + '-' + displayEnd + ' dari ' + totalItems + ' kelompok');

            var totalPages = Math.ceil(totalItems / itemsPerPage);
            if (totalPages <= 1) {
                return;
            }

            // Previous Button
            var prevClass = (currentPage === 1) ? 'disabled' : '';
            list.append('<li class="page-item ' + prevClass + '"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '"><i class="fas fa-chevron-left"></i></a></li>');

            // Page numbers
            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                list.append('<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>');
            }

            // Next Button
            var nextClass = (currentPage === totalPages) ? 'disabled' : '';
            list.append('<li class="page-item ' + nextClass + '"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '"><i class="fas fa-chevron-right"></i></a></li>');
        }

        // Handle page navigation click
        $(document).on('click', '#pagination-list a', function(e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'));
            var parentLi = $(this).parent();
            if (parentLi.hasClass('disabled') || parentLi.hasClass('active')) {
                return;
            }
            showPage(page);
        });

        // Combined search query logic (Searches group name or partner details)
        function applySearch() {
            var searchQuery = $('#searchInput').val().toLowerCase().trim();

            $('.group-parent-row').each(function() {
                var parentRow = $(this);
                var gName = (parentRow.data('group-name') || '').toLowerCase();
                
                var isIndependentGroup = (gName === 'none');
                var groupTitle = isIndependentGroup ? 'mitra tanpa kelompok / mandiri' : gName;

                var groupMatches = (searchQuery === '' || groupTitle.indexOf(searchQuery) > -1);

                var childId = parentRow.attr('id').replace('parent-', 'child-');
                var childRow = $('#' + childId);
                
                var matchingChildCount = 0;

                // Loop members
                childRow.find('tbody tr').each(function() {
                    var row = $(this);
                    var rowText = row.text().toLowerCase();

                    if (searchQuery === '' || groupMatches || rowText.indexOf(searchQuery) > -1) {
                        row.show();
                        matchingChildCount++;
                    } else {
                        row.hide();
                    }
                });

                // A group row matches if search query is empty, or group name matches, or has matching members
                var parentMatches = (searchQuery === '' || groupMatches || matchingChildCount > 0);
                parentRow.data('matches-search', parentMatches);

                if (searchQuery !== '') {
                    if (parentMatches) {
                        parentRow.addClass('expanded');
                    } else {
                        parentRow.removeClass('expanded');
                    }
                } else {
                    parentRow.removeClass('expanded');
                }
            });

            showPage(1);
        }

        // Search trigger
        $('#searchInput').on('keyup search input', function() {
            applySearch();
        });

        // Run initial load
        applySearch();

        // Flash Messages Notifications
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
    });
</script>
