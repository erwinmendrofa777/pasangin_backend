<script>
    function fillInvoiceForm(description, amount, el) {
        $('#invoice_description').val(description);
        $('#invoice_amount').val(amount);
        $('#invoice_amount_visible').val(amount.toLocaleString('id-ID'));
        document.querySelectorAll('.rab-click-row').forEach(r => r.classList.remove('rab-selected'));
        if (el) el.classList.add('rab-selected');
        $('#selectedRabInfo').fadeIn(250);
        $('#selectedRabName').text(description);
        const target = document.getElementById('invoice_description');
        target.focus();
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function toggleRabGroup(id, headerEl) {
        const el = document.getElementById(id);
        const icon = headerEl.querySelector('i.fa-chevron-down');
        const isOpen = el.classList.contains('show');
        if (isOpen) {
            $(el).collapse('hide');
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            $(el).collapse('show');
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    }

    function clearInvoiceForm() {
        $('#invoice_description').val('');
        $('#invoice_amount').val('');
        $('#invoice_amount_visible').val('');
        document.querySelectorAll('.rab-click-row').forEach(r => r.classList.remove('rab-selected'));
        $('#selectedRabInfo').fadeOut(250);
    }

    function formatCurrencyInput(el) {
        let raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        document.getElementById('invoice_amount').value = raw;
    }
</script>
