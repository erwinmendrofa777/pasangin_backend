<?php
/**
 * Component: _idx_scripts.php
 * Description: Script JS untuk halaman Saldo Admin & Platform
 * Pattern: Composite Pattern - Leaf Component
 * Dependencies: jQuery, DataTables, iziToast
 */
?>

<script>
    $(document).ready(function() {
        if ($('#table-transactions tbody tr:not(.empty-row)').length > 0) {
            var table = $('#table-transactions').DataTable({
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
                "order": [
                    [1, "desc"]
                ], // Urutkan berdasarkan tanggal desc
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
                "drawCallback": function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            /* ===== Custom Search ===== */
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#searchInput').on('search', function() {
                if (this.value === '') table.search('').draw();
            });
        }

        // AJAX sinkronisasi saldo Midtrans
        $('#btn-sync-midtrans').on('click', function(e) {
            e.preventDefault();
            if (!confirm('Apakah Anda yakin ingin menyinkronkan saldo lokal dengan live Midtrans? Sistem akan membuat transaksi penyesuaian (balance adjustment) jika ada selisih.')) {
                return;
            }

            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyinkronkan...');

            $.ajax({
                url: '<?= base_url('admin/admin-balance/sync') ?>',
                type: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert(response.message);
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan koneksi atau server error.');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
