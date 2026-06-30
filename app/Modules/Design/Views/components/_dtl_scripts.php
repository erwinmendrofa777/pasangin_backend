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

    document.addEventListener("DOMContentLoaded", function () {
        // Pindahkan semua modal ke body untuk mencegah backdrop menutupi modal (Bootstrap backdrop bug)
        document.querySelectorAll('.modal').forEach(function (modal) {
            document.body.appendChild(modal);
        });

        // Hapus penanganan flash pencegah wrong-tab agar Bootstrap normal kembali
        document.documentElement.classList.remove('has-tab-hash');
        document.documentElement.removeAttribute('data-active-tab');

        var hash = location.hash.replace(/^#/, '');
        if (hash) {
            var triggerEl = document.querySelector('#myTab a[href="#' + hash + '"]');
            if (triggerEl) {
                triggerEl.scrollIntoView({ block: 'nearest', inline: 'center' });
            }
        }

        // Update URL hash ketika tab di-klik dan jalankan animasi
        var tabLinks = document.querySelectorAll('#myTab a[data-bs-toggle="tab"]');
        tabLinks.forEach(function (link) {
            link.addEventListener('shown.bs.tab', function (e) {
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
                    // Bersihkan kelas animasi lama (jika ada)
                    pane.classList.remove('animate__animated', 'animate__fadeIn', 'animate__faster', 'animate__fadeInUpMini');
                    // Trigger reflow untuk mengulang animasi
                    void pane.offsetWidth;
                    // Tambahkan kelas animasi
                    pane.classList.add('animate__animated', 'animate__fadeInUpMini');
                }
            });
        });


        // Cek visibilitas tombol scroll awal
        updateScrollButtons();
        window.addEventListener('resize', updateScrollButtons);
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
