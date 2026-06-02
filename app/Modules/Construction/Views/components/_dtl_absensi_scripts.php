<script>
    // Init Bootstrap Popovers
    $(document).ready(function () {
        const popoverEls = document.querySelectorAll('[data-bs-toggle="popover"]');
        popoverEls.forEach(el => {
            new bootstrap.Popover(el, {
                html: true,
                sanitize: false,
                trigger: 'click',
            });
        });

        // Tutup popover saat klik di luar
        document.addEventListener('click', function (e) {
            popoverEls.forEach(el => {
                const popoverInstance = bootstrap.Popover.getInstance(el);
                if (popoverInstance && !el.contains(e.target)) {
                    popoverInstance.hide();
                }
            });
        });
    });

    // DataTable
    $(document).ready(function () {
        if ($.fn.DataTable) {
            if ($.fn.DataTable.isDataTable('#table-absensi')) {
                $('#table-absensi').DataTable().destroy();
            }
            $('#table-absensi').DataTable({
                "pageLength": 5,
                "ordering": true,
                "info": true,
                "searching": false,
                "lengthChange": false,
                "stateSave": true,
                "language": {
                    "emptyTable": "Tidak ada data tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "«", "last": "»",
                        "next": "›", "previous": "‹"
                    }
                },
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm mt-3');
                }
            });
        }
    });

    function confirmDeleteAttendance(id, constructionId) {
        Swal.fire({
            title: 'Hapus Absensi?',
            text: "Data absensi ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/construction/delete-attendance') ?>/" + id + "/" + constructionId;
            }
        });
    }
</script>
