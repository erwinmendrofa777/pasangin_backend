<script>
    function scrollNav(direction) {
        const container = document.querySelector('.nav-tabs-premium');
        const scrollAmount = 200;
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }

    // Otomatis membuka Tab berdasarkan URL Hash
    document.addEventListener("DOMContentLoaded", function () {
        var hash = location.hash.replace(/^#/, '');
        if (hash) {
            var triggerEl = document.querySelector('#myTab a[href="#' + hash + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
                triggerEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }

        // Update URL hash ketika tab di-klik
        var tabLinks = document.querySelectorAll('#myTab a[data-bs-toggle="tab"]');
        tabLinks.forEach(function (link) {
            link.addEventListener('shown.bs.tab', function (e) {
                if (history.pushState) {
                    history.pushState(null, null, e.target.hash);
                } else {
                    window.location.hash = e.target.hash;
                }
            });
        });
    });

    // Flash Messages
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 20000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 20000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    // Ladda Loading untuk tombol submit
    $(document).on('submit', 'form', function () {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });

    // Reload GLightbox ketika pindah tab
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function () {
        if (window.globalLightbox) {
            window.globalLightbox.reload();
        }
    });
</script>
