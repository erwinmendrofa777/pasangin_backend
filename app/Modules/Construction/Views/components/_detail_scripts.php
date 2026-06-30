<!-- JS Libraries -->
<script>
    // Sliding Nav Logic - Globally Accessible
    function scrollNav(direction) {
        const container = document.querySelector('.nav-tabs-premium');
        const scrollAmount = 200;
        if (direction === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }

    // Tab Navigation Logic via Arrow Buttons - Globally Accessible
    function navigateTab(direction) {
        const tabs = Array.from(document.querySelectorAll('#myTab .nav-link'));
        const activeLink = document.querySelector('#myTab .nav-link.active');
        if (!activeLink) return;
        
        let index = tabs.indexOf(activeLink);
        if (index === -1) return;
        
        if (direction === 'left') {
            index = index - 1;
            if (index < 0) index = tabs.length - 1;
        } else {
            index = index + 1;
            if (index >= tabs.length) index = 0;
        }
        
        const targetTab = tabs[index];
        if (targetTab) {
            targetTab.click();
            targetTab.scrollIntoView({ block: 'nearest', inline: 'center' });
        }
    }

    function updateScrollButtons() {
        const container = document.querySelector('.nav-tabs-premium');
        const btnLeft = document.querySelector('.nav-scroll-btn.left');
        const btnRight = document.querySelector('.nav-scroll-btn.right');
        if (!container || !btnLeft || !btnRight) return;

        const hasScroll = container.scrollWidth > container.clientWidth;
        if (hasScroll) {
            btnLeft.style.display = 'flex';
            btnRight.style.display = 'flex';
        } else {
            btnLeft.style.display = 'none';
            btnRight.style.display = 'none';
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

        // Hapus penanganan flash pencegah wrong-tab agar Bootstrap normal kembali
        document.documentElement.classList.remove('has-tab-hash');
        document.documentElement.removeAttribute('data-active-tab');

        // Restore Tab Logic
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
            var triggerEl = document.querySelector('#myTab a[href="' + hash + '"]');
            if (triggerEl) {
                triggerEl.scrollIntoView({ block: 'nearest', inline: 'center' });
            }
        }

        // Update URL hash ketika tab di-klik dan jalankan animasi
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            // 1. Update URL Hash
            if (history.pushState) {
                history.pushState(null, null, e.target.hash);
            } else {
                window.location.hash = e.target.hash;
            }

            // 2. Animasi Tab Content dengan Animate.css (fadeInUpMini)
            var targetSelector = e.target.hash;
            var pane = document.querySelector(targetSelector);
            if (pane) {
                pane.classList.remove('animate__animated', 'animate__fadeIn', 'animate__faster', 'animate__fadeInUpMini');
                void pane.offsetWidth;
                pane.classList.add('animate__animated', 'animate__fadeInUpMini');
            }
        });

        // Cek visibilitas tombol scroll awal
        updateScrollButtons();
        window.addEventListener('resize', updateScrollButtons);

        // Call RAB calculation functions if they exist (defined in sub-views)
        try {
            calculateGrandTotal();
        } catch (e) { }
        try {
            calculateGrandTotalAddendum();
        } catch (e) { }

    });
</script>
