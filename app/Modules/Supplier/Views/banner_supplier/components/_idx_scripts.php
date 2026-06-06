<script>
    $(document).ready(function () {
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
                "targets": [1, 2, 5]
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

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Clear search when input is cleared
        $('#searchInput').on('search', function () {
            if (this.value === '') {
                table.search('').draw();
            }
        });

        // Delete handler (Using Event Delegation for DataTables compatibility)
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Banner?',
                text: "Data banner dan file akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: 'var(--palette-primary)',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var deleteUrl = '<?= base_url('admin/banner-supplier/delete') ?>/' + id;
                    $('#deleteForm').attr('action', deleteUrl).submit();
                }
            });
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    // Flash Messages
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
