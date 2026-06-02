<script>
  (function () {
    function selectRow(tr, cid) {
      // Clear previous selection across ALL tables
      document.querySelectorAll('tr.row-item.selected').forEach(function (el) {
        el.classList.remove('selected');
      });
      tr.classList.add('selected');

      var rabId = tr.getAttribute('data-rab-id') || '';
      var addendumId = tr.getAttribute('data-addendum-id') || '';
      var group = tr.getAttribute('data-group') || '';
      var subgroup = tr.getAttribute('data-subgroup') || '';
      var activity = tr.getAttribute('data-activity') || '';
      var bobot = tr.getAttribute('data-bobot') || '';
      var jobApps = tr.getAttribute('data-job-apps') || '';

      function g(id) { return document.getElementById(id); }

      var inpRab = g('inp-rab-id-' + cid);
      var inpAdd = g('inp-addendum-id-' + cid);
      var inpGroup = g('inp-group-' + cid);
      var inpSub = g('inp-subgroup-' + cid);
      var inpName = g('inp-name-' + cid);
      var inpBobot = g('inp-bobot-' + cid);
      var inpTukang = g('inp-tukang-' + cid);
      var btn = g('btn-submit-target-' + cid);
      var infoEl = g('selected-info-' + cid);
      var selCard = g('sel-card-' + cid);
      var selName = g('sel-card-name-' + cid);
      var selMeta = g('sel-card-meta-' + cid);

      if (inpRab) inpRab.value = rabId;
      if (inpAdd) inpAdd.value = addendumId;
      if (inpGroup) inpGroup.value = group;
      if (inpSub) inpSub.value = subgroup;
      if (inpName) inpName.value = activity;
      if (inpBobot) inpBobot.value = bobot;
      if (inpTukang) inpTukang.value = jobApps;
      if (btn) btn.disabled = false;

      if (infoEl) infoEl.textContent = '— ' + activity;
      if (selCard) selCard.classList.add('visible');
      if (selName) selName.textContent = activity;
      if (selMeta) selMeta.textContent = (group ? group : '') + (subgroup ? ' › ' + subgroup : '');

      var inpStart = g('inp-start-' + cid);
      if (inpStart) inpStart.focus();
    }

    // Expose globally (used in onclick attributes)
    window.selectRow = selectRow;
  })();
</script>
