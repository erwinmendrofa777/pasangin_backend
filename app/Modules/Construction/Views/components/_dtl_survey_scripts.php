<script>
    document.addEventListener('DOMContentLoaded', function () {
        const surveyInput = document.getElementById('surveyFileInput');
        if (surveyInput) {
            surveyInput.addEventListener('change', function (e) {
                const files = e.target.files;
                const display = document.getElementById('surveyFileNameDisplay');
                if (display) {
                    if (files && files.length > 0) {
                        const fileNames = Array.from(files).map(f => f.name).join(', ');
                        display.textContent = files.length === 1 ? fileNames : `${files.length} file terpilih: ${fileNames}`;
                        display.style.color = '#34395e';
                        display.style.fontWeight = '600';
                    } else {
                        display.textContent = 'Pilih atau seret file...';
                        display.style.color = '#6c757d';
                        display.style.fontWeight = '400';
                    }
                }
            });
        }
    });
</script>
