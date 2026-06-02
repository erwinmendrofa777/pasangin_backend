<script>
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 6000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>

    /* ===== Loading on Submit (Ladda) ===== */
    document.addEventListener('DOMContentLoaded', function () {
        const editForm = document.getElementById('edit-user-form');
        
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                const submitBtn = this.querySelector('.ladda-button');
                if (submitBtn) {
                    const l = Ladda.create(submitBtn);
                    l.start();
                }
            });
        }
    });

    /* ===== Image Preview ===== */
    function previewImage() {
        const avatar = document.querySelector('#avatar');
        const imgPreview = document.querySelector('#img-preview');
        const imgPreviewInitials = document.querySelector('#img-preview-initials');

        if (avatar.files && avatar.files[0]) {
            const fileReader = new FileReader();
            fileReader.readAsDataURL(avatar.files[0]);

            fileReader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.classList.remove('d-none');
                
                // Hide initials if they exist
                if (imgPreviewInitials) {
                    imgPreviewInitials.classList.remove('d-flex');
                    imgPreviewInitials.classList.add('d-none');
                }
            }
        }
    }
</script>
