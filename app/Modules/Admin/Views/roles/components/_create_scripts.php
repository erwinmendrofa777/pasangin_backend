<?php
$grandTotal = 0;
foreach ($available_menus as $menus) {
    foreach ($menus as $k => $v) {
        $grandTotal++;
        if (is_array($v)) $grandTotal += count($v['actions']);
    }
}
?>
<script>
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 6000,
            title: 'Gagal',
            message: '<?= strip_tags(session()->getFlashdata('error')) ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {

        /* ── Ladda ── */
        const form = document.getElementById('create-role-form');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('.ladda-button');
                if (btn) Ladda.create(btn).start();
            });
        }

        /* ── Pill visual sync ── */
        function syncPill(pill) {
            const cb = pill.querySelector('input[type="checkbox"]');
            if (cb) pill.classList.toggle('on', cb.checked);
        }

        function initPills() {
            document.querySelectorAll('.perm-pill').forEach(pill => {
                syncPill(pill);
                const cb = pill.querySelector('input');
                if (cb) cb.addEventListener('change', () => {
                    syncPill(pill);
                    updateAll();
                });
            });
        }

        /* ── Update counters ── */
        const grandTotal = <?= $grandTotal ?>;

        function updateAll() {
            let globalChecked = 0;

            document.querySelectorAll('.tab-pane').forEach(pane => {
                const id = pane.id;
                const checked = pane.querySelectorAll('.perm-cb:checked').length;
                const total = pane.querySelectorAll('.perm-cb').length;
                globalChecked += checked;

                const pc = document.getElementById('pane-counter-' + id);
                if (pc) pc.textContent = checked + ' dipilih';

                const badge = document.getElementById('badge-' + id);
                if (badge) badge.textContent = checked + ' / ' + total;
            });

            document.querySelectorAll('.parent-cb').forEach(parent => {
                const key = parent.id.replace('perm_', '');
                const children = document.querySelectorAll(`.child-cb[data-parent="${parent.id}"]`);
                const chChecked = document.querySelectorAll(`.child-cb[data-parent="${parent.id}"]:checked`).length;

                const cntEl = document.getElementById('cnt_' + key);
                if (cntEl) cntEl.textContent = chChecked + ' / ' + children.length;

                const card = document.getElementById('card_' + key);
                if (card) card.classList.toggle('has-checked', parent.checked || chChecked > 0);
            });

            document.getElementById('globalCount').textContent = globalChecked + ' dari ' + grandTotal;
        }

        /* ── Parent → children ── */
        document.querySelectorAll('.parent-cb').forEach(parent => {
            parent.addEventListener('change', function() {
                document.querySelectorAll(`.child-cb[data-parent="${this.id}"]`).forEach(child => {
                    child.checked = this.checked;
                    const pill = child.closest('.perm-pill');
                    if (pill) syncPill(pill);
                });
                updateAll();
            });
        });

        /* ── Child → parent ── */
        document.querySelectorAll('.child-cb').forEach(child => {
            child.addEventListener('change', function() {
                if (this.checked) {
                    const parent = document.getElementById(this.getAttribute('data-parent'));
                    if (parent) parent.checked = true;
                }
                updateAll();
            });
        });

        /* ── Tab switching ── */
        document.querySelectorAll('.perm-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.perm-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const target = document.getElementById(this.dataset.target);
                if (target) target.classList.add('active');
            });
        });

        /* ── Select All / Clear All ── */
        let allOn = false;
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            allOn = !allOn;
            document.querySelectorAll('.perm-cb').forEach(cb => {
                cb.checked = allOn;
                const pill = cb.closest('.perm-pill');
                if (pill) syncPill(pill);
            });
            this.textContent = allOn ? '✕ Hapus Semua' : '☑ Pilih Semua';
            updateAll();
        });

        initPills();
        updateAll();
    });
</script>
