<script>
    $(document).ready(function () {
        /* ===== Toggle Specific User Container ===== */
        $('input[name="send_type"]').change(function () {
            if ($(this).val() === 'specific') {
                $('#specificUserContainer').slideDown();
                $('#targetId').prop('required', true);
                initSelect2();
            } else {
                $('#specificUserContainer').slideUp();
                $('#targetId').prop('required', false);
            }
        });

        /* ===== Re-init Select2 jika role berubah ===== */
        $('#targetRole').change(function () {
            if ($('input[name="send_type"]:checked').val() === 'specific') {
                $('#targetId').val(null).trigger('change');
                initSelect2();
            }
        });

        function initSelect2() {
            var role = $('#targetRole').val();
            $('#targetId').select2({
                ajax: {
                    url: '<?= base_url('admin/notification/searchUsers') ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term, role: role };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                },
                placeholder: 'Ketik nama / no HP...',
                minimumInputLength: 3,
                allowClear: true,
                width: '100%'
            });
        }

        /* ===== Loading on Submit ===== */
        document.getElementById('create-notification-form').addEventListener('submit', function () {
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
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
