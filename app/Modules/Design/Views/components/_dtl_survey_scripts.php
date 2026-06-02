<script>
document.addEventListener('DOMContentLoaded', function() {
    const surveyInput = document.getElementById('surveyFileInput');
    if (surveyInput) {
        surveyInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih atau seret file...';
            const display = document.getElementById('surveyFileNameDisplay');
            if (display) {
                display.textContent = fileName;
                display.style.color = e.target.files[0] ? '#34395e' : '#6c757d';
                display.style.fontWeight = e.target.files[0] ? '600' : '400';
            }
        });
    }
});
</script>
