<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil!',
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
    <?php if (session()->getFlashdata('errors')): ?>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            iziToast.error({
                timeout: 5000,
                title: 'Input Error',
                message: '<?= $error ?>',
                position: 'topCenter'
            });
        <?php endforeach; ?>
    <?php endif; ?>

    /* ===== Initialize Select2 ===== */
    if ($('.select2').length > 0) {
        $('.select2').select2({
            placeholder: 'Pilih Keahlian...',
            allowClear: true
        });
    }

    /* ===== Loading on Submit ===== */
    document.getElementById('create-tukang-form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('.ladda-button');
        if (submitBtn) {
            const l = Ladda.create(submitBtn);
            l.start();
        }
    });

    /* ===== Unified File Preview ===== */
    function previewFile(input, type) {
        const preview = document.getElementById(type + '-preview');
        const placeholder = document.getElementById(type + '-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
