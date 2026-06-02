<script>
    $(document).ready(function () {

        <?php if (!empty($tips)): ?>
            // Konfigurasi DataTables dengan fitur search yang enhanced
            var table = $('#table-1').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
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
                    "targets": [1, 6]
                }
                ],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>', // Matches users/index.php design
                "drawCallback": function (settings) {
                    // Re-initialize tooltips after table redraw
                    $('[data-toggle="tooltip"]').tooltip();

                    // Re-initialize GLightbox
                    if (window.GLightbox) {
                        GLightbox({ selector: '.glightbox' });
                    }
                }
            });

            if (window.GLightbox) {
                GLightbox({ selector: '.glightbox' });
            }

            // Hubungkan search input custom dengan DataTables search
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Clear search when input is cleared
            $('#searchInput').on('search', function () {
                if (this.value === '') {
                    table.search('').draw();
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
            $(document).on('submit', 'form', function () {
                var btn = $(this).find('.ladda-button');
                if (btn.length > 0) {
                    var l = Ladda.create(btn[0]);
                    l.start();
                }
            });
        <?php endif; ?>

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
