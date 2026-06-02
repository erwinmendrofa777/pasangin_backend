<script>
    function updateProgressStatus(el, id, newStatus, oldStatus) {
        if (newStatus === oldStatus) return;
        if (!confirm('Ubah status menjadi ' + newStatus + '?')) return;
        $.post('<?= base_url('admin/renovation/update_progress_status') ?>/' + id + '/' + newStatus, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function (res) {
            location.reload();
        }).fail(function () {
            alert('Gagal update status!');
        });
    }

    // Toggle chevron rotation on collapse
    $(document).on('show.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').removeClass('collapsed');
    });
    $(document).on('hide.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').addClass('collapsed');
    });
</script>
