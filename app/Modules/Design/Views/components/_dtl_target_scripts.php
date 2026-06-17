<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startInput = document.getElementById('start_date_input');
        const targetInput = document.getElementById('target_date_input');
        const totalInput = document.getElementById('total_hari_input');

        function calculateDays() {
            if (startInput.value && targetInput.value) {
                const start = new Date(startInput.value);
                const end = new Date(targetInput.value);
                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    totalInput.value = diffDays + " Hari";
                } else {
                    totalInput.value = "0 Hari";
                }
            }
        }

        if (startInput && targetInput) {
            startInput.addEventListener('change', calculateDays);
            targetInput.addEventListener('change', calculateDays);
        }

        // Initialize Bootstrap 5 Tooltips for Gantt Bars
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
    });
</script>
