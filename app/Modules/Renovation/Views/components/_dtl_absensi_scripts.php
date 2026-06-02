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

    $(document).ready(function () {
        // Inisialisasi DataTable segera (meski tab tersembunyi) agar pagination terbentuk
        if ($.fn.DataTable && document.getElementById('table-absensi')) {
            if ($.fn.DataTable.isDataTable('#table-absensi')) {
                $('#table-absensi').DataTable().destroy();
            }
            var absensiTable = $('#table-absensi').DataTable({
                "pageLength": 5,
                "ordering": true,
                "info": true,
                "searching": false,
                "lengthChange": false,
                "stateSave": false,
                "language": {
                    "emptyTable": "Tidak ada data tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 data",
                    "paginate": {
                        "first": "«", "last": "»",
                        "next": "›", "previous": "‹"
                    }
                },
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm mt-3');
                }
            });

            // Saat tab absensi dibuka, adjust kolom agar pagination dan layout benar
            $(document).on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href') || $(e.target).attr('data-bs-target');
                if (target === '#absensi') {
                    absensiTable.columns.adjust().draw();
                }
            });
        }
    });

    function confirmDeleteAttendance(id, renovationId) {
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
                window.location.href = "<?= base_url('admin/renovation/delete-attendance') ?>/" + id + "/" + renovationId;
            }
        });
    }
</script>
