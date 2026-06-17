<script>
    function selectRow(tr) {
        // Hapus seleksi sebelumnya
        document.querySelectorAll('#mainTable tr.item-row.selected').forEach(function (el) {
            el.classList.remove('selected');
        });
        tr.classList.add('selected');

        var rabId = tr.getAttribute('data-rab-id');
        var addendumId = tr.getAttribute('data-addendum-id');
        var group = tr.getAttribute('data-group');
        var subgroup = tr.getAttribute('data-subgroup');
        var activity = tr.getAttribute('data-activity');
        var jobApps = tr.getAttribute('data-job-apps');
        var cid = <?= json_encode($construction['id'] ?? '') ?>;

        // Isi hidden field rab_id (POST)
        var hiddenInputRab = document.getElementById('inp-rab-id-' + cid);
        if (hiddenInputRab) hiddenInputRab.value = rabId || '';

        var hiddenInputAddendum = document.getElementById('inp-addendum-id-' + cid);
        if (hiddenInputAddendum) hiddenInputAddendum.value = addendumId || '';

        // Isi field tampilan
        var inpGroup = document.getElementById('inp-group-' + cid);
        var inpSubgroup = document.getElementById('inp-subgroup-' + cid);
        var inpName = document.getElementById('inp-name-' + cid);
        var inpTukang = document.getElementById('inp-tukang-' + cid);
        if (inpGroup) inpGroup.value = group;
        if (inpSubgroup) inpSubgroup.value = subgroup;
        if (inpName) inpName.value = activity;
        if (inpTukang) {
            inpTukang.value = jobApps || '';
        }

        // Aktifkan tombol simpan
        var btn = document.getElementById('btn-submit-target-' + cid);
        if (btn) btn.disabled = false;

        // Info label
        var info = document.getElementById('selected-info-' + cid);
        if (info) info.textContent = '— ' + activity;

        // Fokus ke input mulai minggu
        var inpStart = document.getElementById('inp-start-' + cid);
        if (inpStart) inpStart.focus();
    }
</script>
