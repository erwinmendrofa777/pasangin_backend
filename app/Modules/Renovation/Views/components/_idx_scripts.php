<script>
    $(document).ready(function () {
        // Konfigurasi DataTables dengan fitur search yang enhanced
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
                "targets": [3, 4, 5]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'r<"table-responsive"t><"dt-footer d-flex justify-content-between align-items-center"ip>', // Matches users/index.php design
            "drawCallback": function (settings) {
                // Re-initialize tooltips after table redraw
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
            'pending': 'Menunggu',
            'survey': 'Survey',
            'designing': 'Desain',
            'rab': 'RAB',
            'construction': 'Konstruksi',
            'completed': 'Selesai',
            'cancelled': 'Batal'
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
                table.column(4).search('').draw();
            } else {
                var searchVal = statusLabels[val] || '';
                table.column(4).search(searchVal).draw();
            }
        });

        /* ===== Custom Search ===== */
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Clear search when input is cleared
        $('#searchInput').on('search', function () {
            if (this.value === '') {
                table.search('').draw();
            }
        });

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
    });
</script>
