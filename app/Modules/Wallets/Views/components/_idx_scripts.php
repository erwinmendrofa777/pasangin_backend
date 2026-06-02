<?php
/**
 * Component: _idx_scripts.php
 * Description: Script JS untuk halaman Saldo Mitra Tukang (Wallets Index)
 * Pattern: Composite Pattern - Leaf Component
 * Dependencies: jQuery, DataTables, Ladda, iziToast
 */
?>

<script>
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
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
        // Konfigurasi DataTables
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
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
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>'
        });

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

        // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di form modal)
        $(document).on('submit', 'form', function (e) {
            var form = this;
            var btn = $(form).find('.ladda-button');
            if (btn.length > 0) {
                e.preventDefault();
                var l = Ladda.create(btn[0]);
                l.start();

                setTimeout(function () {
                    form.submit();
                }, 100);
            }
        });
    });
</script>
