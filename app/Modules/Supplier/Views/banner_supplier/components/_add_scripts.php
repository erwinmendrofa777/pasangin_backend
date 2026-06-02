<script>
    $(document).ready(function() {
        // Initialize Select2
        if ($('.select2').length > 0) {
            $('.select2').select2({
                placeholder: "-- Cari Supplier --",
                allowClear: true,
                width: '100%'
            });
        }

        /* ===== Loading on Submit ===== */
        document.getElementById('create-banner-form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('.ladda-button');
            if (submitBtn) {
                const l = Ladda.create(submitBtn);
                l.start();
            }
        });
    });

    /* ===== Image Preview ===== */
    function previewImage() {
        const input = document.querySelector('#image');
        const preview = document.querySelector('#img-preview');
        const placeholder = document.querySelector('#img-preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Flash Messages
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
