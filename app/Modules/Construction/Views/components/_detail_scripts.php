<!-- JS Libraries -->
<script>
    // Sliding Nav Logic - Globally Accessible
    function scrollNav(direction) {
        const container = document.querySelector('.nav-tabs-premium');
        const scrollAmount = 400;
        const currentScroll = container.scrollLeft;

        if (direction === 'left') {
            container.scrollTo({
                left: currentScroll - scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollTo({
                left: currentScroll + scrollAmount,
                behavior: 'smooth'
            });
        }
    }


    // chocolate js
    $(document).ready(function () {
        // Flash Messages
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

        // Ladda Integration
        $(document).on('submit', 'form', function () {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });

        // Status Selection Logic - Klik tombol untuk memilih status baru
        $(document).on('click', '.status-action-btn', function () {
            var $btn = $(this);
            var newStatus = $btn.data('status');

            // Jika tombol ini sudah merupakan status aktif yang belum diubah, skip
            // (tapi tetap boleh diklik untuk re-confirm pilihan)

            // Update hidden input dengan status baru
            $('#selectedStatusInput').val(newStatus);

            // Reset semua tombol ke outline
            $('.status-action-btn').each(function () {
                var color = $(this).data('color');
                $(this).removeClass('btn-' + color + ' btn-current-status').addClass('btn-outline-' + color);
                $(this).find('.status-icon').removeClass('fa-check-circle').addClass('fa-chevron-right').css('font-size', '0.75rem').css('opacity', '0.6');
            });

            // Set tombol yang diklik menjadi solid (aktif)
            var color = $btn.data('color');
            $btn.removeClass('btn-outline-' + color).addClass('btn-' + color + ' btn-current-status');
            $btn.find('.status-icon').removeClass('fa-chevron-right').addClass('fa-check-circle').css('font-size', '1rem').css('opacity', '1');
        });

        // Restore Tab Logic
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });

        // Call RAB calculation functions if they exist (defined in sub-views)
        try {
            calculateGrandTotal();
        } catch (e) { }
        try {
            calculateGrandTotalAddendum();
        } catch (e) { }

    });
</script>
