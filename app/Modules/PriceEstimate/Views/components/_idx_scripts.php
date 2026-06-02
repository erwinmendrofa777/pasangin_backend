<script>
    $(document).ready(function() {
        // Modal Edit Konsep
        $('.btn-edit-concept').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#edit_concept_name').val(name);
            $('#editConceptForm').attr('action', "<?= site_url('admin/price-estimate/concept/update/') ?>" + id);
            $('#editConceptModal').modal('show');
        });

        // Modal Tambah Kualitas (Set Concept ID)
        $('.btn-add-quality').on('click', function() {
            const conceptId = $(this).data('concept-id');
            const conceptName = $(this).data('concept-name');
            $('#add_quality_concept_id').val(conceptId);
            $('#add_quality_concept_name').text('Konsep: ' + conceptName);
            $('#addQualityModal').modal('show');
        });

        // Modal Edit Kualitas
        $('.btn-edit-quality').on('click', function() {
            const id = $(this).data('id');
            const label = $(this).data('label');
            const minPrice = $(this).data('min-price');
            const maxPrice = $(this).data('max-price');
            const desc = $(this).data('desc');

            $('#edit_quality_label').val(label);
            $('#edit_quality_min_price').val(minPrice);
            $('#edit_quality_max_price').val(maxPrice);
            $('#edit_quality_desc').val(desc);

            $('#editQualityForm').attr('action', "<?= site_url('admin/price-estimate/quality/update/') ?>" + id);
            $('#editQualityModal').modal('show');
        });

        // Ladda Spinner on Submit
        $(document).on('submit', 'form', function() {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });

        // Toast Messages
        <?php if (session()->getFlashdata('message')): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil!',
                message: '<?= session()->getFlashdata('message') ?>',
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
    });
</script>
