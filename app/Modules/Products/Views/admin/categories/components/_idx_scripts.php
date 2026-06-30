<script>
    $(document).ready(function () {

        <?php if (!empty($categories)): ?>
            // Konfigurasi DataTables
            var table = $('#table-categories').DataTable({
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
                    "targets": [4]
                }],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            // Hubungkan search input custom dengan DataTables search
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Clear search saat input dikosongkan
            $('#searchInput').on('search', function () {
                if (this.value === '') {
                    table.search('').draw();
                }
            });

            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();
        <?php endif; ?>

        // Helper to clear error states in modal
        function clearModalErrors() {
            $('#name').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.alert-danger').remove();
        }

        // Event listener saat modal form dibuka untuk TAMBAH data
        $('.btn-tambah-category').on('click', function () {
            clearModalErrors();
            $('#categoryModalTitle').html('<i class="fas fa-tags me-2 text-primary"></i>Tambah Kategori Baru');
            $('#categoryForm').attr('action', '<?= site_url("admin/product-categories/store") ?>');
            $('#category_id').val('');
            $('#name').val('');
        });

        // Event listener saat modal form dibuka untuk EDIT data (delegasi event agar bekerja saat pagination berubah)
        $(document).on('click', '.btn-edit-category', function () {
            clearModalErrors();
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            $('#categoryModalTitle').html('<i class="fas fa-tags me-2 text-primary"></i>Edit Kategori Produk');
            $('#categoryForm').attr('action', '<?= site_url("admin/product-categories/update") ?>/' + id);
            $('#category_id').val(id);
            $('#name').val(nama);
        });

        // Auto-open modal jika ada error dari submission sebelumnya
        <?php if (!empty($validationErrors) || (!empty($error) && old('name') !== null)): ?>
            $('#categoryModal').modal('show');
        <?php endif; ?>

        /* ===== Flash Messages ===== */
        <?php if (!empty($success)): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil',
                message: '<?= $success ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            iziToast.error({
                timeout: 5000,
                title: 'Gagal',
                message: '<?= $error ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        <?php if (!empty($validationErrors)): ?>
            <?php foreach ($validationErrors as $err): ?>
                iziToast.error({
                    timeout: 6000,
                    title: 'Gagal',
                    message: '<?= esc($err) ?>',
                    position: 'topCenter'
                });
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>
