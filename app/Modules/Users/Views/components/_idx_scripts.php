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
        /* ===== DataTables ===== */
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
                "targets": [1, 3, 4, 6]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
            "drawCallback": function () {
                $('[data-toggle="tooltip"]').tooltip();
                if (window.globalLightbox) {
                    window.globalLightbox.reload();
                }
            }
        });

        /* ===== Custom Search ===== */
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function () {
            if (this.value === '') table.search('').draw();
        });

        /* ===== Tooltips ===== */
        $('[data-toggle="tooltip"]').tooltip();

        /* ===== Ladda on form submit ===== */
        $(document).on('submit', 'form', function () {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });
    });
</script>
