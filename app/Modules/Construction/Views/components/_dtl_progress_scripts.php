<script>
    // Toggle chevron rotation on collapse
    $(document).on('show.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').removeClass('collapsed');
    });
    $(document).on('hide.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').addClass('collapsed');
    });
</script>
