<script>
    $(document).ready(function () {
        // Status Update Handler
        $('.btn-update-status').on('click', function () {
            var status = $(this).data('status');
            var id = '<?= $banner['id'] ?>';

            Swal.fire({
                title: 'Ubah Status?',
                text: "Status banner akan diubah menjadi " + status + ".",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--palette-primary)',
                cancelButtonColor: '#adb5bd',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/banner-supplier/update-status') ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            status: status,
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        },
                        success: function (response) {
                            if (response.status) {
                                iziToast.success({ title: 'Berhasil!', message: response.message, position: 'topCenter' });
                                setTimeout(() => { location.reload(); }, 1000);
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        // Delete Handler
        $('.btn-delete').on('click', function () {
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
                    $('#deleteForm').attr('action', '<?= base_url('admin/banner-supplier/delete') ?>/' + id).submit();
                }
            });
        });

        // Flash Messages
        <?php if (session()->getFlashdata('success')): ?>
            iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
        <?php endif; ?>

        // Initialize GLightbox
        if (window.GLightbox) {
            GLightbox({ selector: '.glightbox' });
        }
    });
</script>
