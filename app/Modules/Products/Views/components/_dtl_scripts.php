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
            timeout: 6000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    /* ===== Confirm Status Modal ===== */
    document.addEventListener('DOMContentLoaded', function() {

        var confirmModal = document.getElementById('confirmStatusModal');
        if (confirmModal) {
            confirmModal.addEventListener('show.bs.modal', function(event) {
                var btn = event.relatedTarget;
                var newStatus = btn.getAttribute('data-status');
                var statusLabel = btn.getAttribute('data-status-label');
                var color = btn.getAttribute('data-color');
                var icon = btn.getAttribute('data-icon');

                // Set form action
                document.getElementById('updateStatusForm').action =
                    '<?= base_url('admin/products/update_status/' . $product['id']) ?>/' + newStatus;

                // Color mapping
                var colorMap = {
                    success: {
                        bg: '#d1e7dd',
                        color: '#0a5c36'
                    },
                    warning: {
                        bg: '#fff3cd',
                        color: '#7d5a00'
                    },
                    danger: {
                        bg: '#f8d7da',
                        color: '#842029'
                    },
                    dark: {
                        bg: '#dee2e6',
                        color: '#212529'
                    },
                };
                var c = colorMap[color] || {
                    bg: '#e7f0ff',
                    color: '#0d6efd'
                };

                var iconWrap = document.getElementById('modalIconWrap');
                if (iconWrap) {
                    iconWrap.style.background = c.bg;
                    iconWrap.style.color = c.color;
                    iconWrap.innerHTML = '<i class="' + icon + '"></i>';
                }

                var label = document.getElementById('modalStatusLabel');
                if (label) {
                    label.textContent = statusLabel;
                    label.style.color = c.color;
                }

                var confirmBtn = document.getElementById('modalConfirmBtn');
                if (confirmBtn) {
                    confirmBtn.className = 'btn btn-' + color + ' px-4 fw-semibold';
                }
            });
        }

        /* Loading spinner on submit */
        var updateForm = document.getElementById('updateStatusForm');
        if (updateForm) {
            updateForm.addEventListener('submit', function() {
                var btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                }
            });
        }
    });
</script>
