<script>
    $(document).ready(function() {

        /* ===== Flash Messages ===== */
        <?php if (session()->getFlashdata('success')) : ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil',
                message: '<?= session()->getFlashdata('success') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            iziToast.error({
                timeout: 5000,
                title: 'Gagal',
                message: '<?= is_array(session()->getFlashdata('error')) ? implode(' ', session()->getFlashdata('error')) : session()->getFlashdata('error') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        $('[data-toggle="tooltip"]').tooltip();

        // Handling active pills behavior explicitly
        $('.nav-pills .nav-link').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });

    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
</script>
