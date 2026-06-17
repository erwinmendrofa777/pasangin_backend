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
            message: '<?= strip_tags(session()->getFlashdata('error')) ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function () {
        var table = $('#table-1').DataTable({
            "order": [[1, "desc"]],
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
                "emptyTable": "Tidak ada log aktivitas yang tersedia",
                "zeroRecords": "Log tidak ditemukan"
            },
            "columnDefs": [{
                "sortable": false,
                "targets": [0, 2, 4, 5]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
            "drawCallback": function (settings) {
                // Re-initialize tooltips after table redraw
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#searchInput').on('search', function () {
            if (this.value === '') table.search('').draw();
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
