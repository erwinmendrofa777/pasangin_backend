<script>
    function fillInvoiceForm(description, amount, el) {
        $('#invoice_description').val(description);
        $('#invoice_amount').val(amount);
        $('#invoice_amount_visible').val(amount.toLocaleString('id-ID'));
        document.querySelectorAll('.rab-click-row').forEach(function (r) {
            r.classList.remove('rab-selected');
        });
        if (el) el.classList.add('rab-selected');
        var info = document.getElementById('selectedRabInfo');
        var name = document.getElementById('selectedRabName');
        if (info) info.style.display = 'block';
        if (name) name.textContent = description;
        var form = document.getElementById('invoice_description');
        if (form) {
            setTimeout(function () {
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                form.focus();
            }, 80);
        }
    }

    function toggleRabGroup(id, headerEl) {
        var el = document.getElementById(id);
        if (!el) return;
        var chev = headerEl ? headerEl.querySelector('i.fa-chevron-down') : null;
        var isOpen = el.classList.contains('show');
        if (isOpen) {
            el.classList.remove('show');
            if (chev) chev.style.transform = 'rotate(-90deg)';
        } else {
            el.classList.add('show');
            if (chev) chev.style.transform = 'rotate(0deg)';
        }
    }

    function clearInvoiceForm() {
        $('#invoice_description').val('');
        $('#invoice_amount').val('');
        $('#invoice_amount_visible').val('');
        document.querySelectorAll('.rab-click-row').forEach(function (r) {
            r.classList.remove('rab-selected');
        });
        var info = document.getElementById('selectedRabInfo');
        if (info) info.style.display = 'none';
    }

    // ── Currency Format ──
    function formatCurrencyInput(el) {
        let raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        let hidden = el.id === 'invoice_amount_visible' ? document.getElementById('invoice_amount') : null;
        if (hidden) hidden.value = raw;
    }
</script>
