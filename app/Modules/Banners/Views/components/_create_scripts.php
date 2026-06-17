<script>
$(document).ready(function() {
    // 1. Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 10000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 10000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    // 2. Interaksi Seleksi Target Aplikasi (Radio Card)
    $('.target-app-card').on('click', function() {
        $('.target-app-card').removeClass('active');
        $(this).addClass('active');
        const value = $(this).data('target');
        $('#target-app-value').val(value);
    });

    // 3. Sinkronisasi Teks Judul ke Live Preview
    function updateTitlePreview() {
        const titleVal = $('#banner-title-input').val().trim();
        const overlayText = $('#banner-title-overlay-text');
        if (titleVal === "") {
            overlayText.hide();
        } else {
            overlayText.text(titleVal).show();
        }
    }
    $('#banner-title-input').on('input', updateTitlePreview);

    // 4. Trigger Klik Area Dropzone ke File Input Tersembunyi
    $('#banner-dropzone').on('click', function() {
        $('#banner-file-input').click();
    });

    $('#banner-file-input').on('click', function(e) {
        e.stopPropagation();
    });

    // 5. Fungsi Pemrosesan Berkas Gambar Pilihan
    function handleFileSelect(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Validasi format file gambar
            if (!file.type.startsWith('image/')) {
                iziToast.error({
                    title: 'Gagal',
                    message: 'Format berkas harus berupa gambar (JPG/PNG)!',
                    position: 'topCenter'
                });
                $('#banner-file-input').val('');
                return;
            }
            
            // Validasi ukuran berkas (Maks 2MB)
            if (file.size > 2 * 1024 * 1024) {
                iziToast.error({
                    title: 'Gagal',
                    message: 'Ukuran gambar terlalu besar (maksimal 2MB)!',
                    position: 'topCenter'
                });
                $('#banner-file-input').val('');
                return;
            }
            
            // Tampilkan visual Loading ringan
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#banner-preview-image').attr('src', e.target.result);
                $('#banner-dropzone').hide();
                $('#banner-preview-wrapper').show();
                updateTitlePreview();
            }
            reader.readAsDataURL(file);
        }
    }

    // Event Change ketika User memilih berkas manual
    $('#banner-file-input').on('change', function() {
        handleFileSelect(this.files);
    });

    // 6. Logika Drag and Drop Event Listener
    const dropzone = $('#banner-dropzone');
    
    dropzone.on('dragover', function(e) {
        e.preventDefault();
        dropzone.addClass('dragover');
    });
    
    dropzone.on('dragleave', function(e) {
        e.preventDefault();
        dropzone.removeClass('dragover');
    });
    
    dropzone.on('drop', function(e) {
        e.preventDefault();
        dropzone.removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            // Pasang berkas yang di-drop ke input file tersembunyi
            $('#banner-file-input')[0].files = files;
            handleFileSelect(files);
        }
    });

    // 7. Tombol Reset / Ganti Gambar Pratinjau
    $('#btn-remove-preview').on('click', function() {
        $('#banner-file-input').val('');
        $('#banner-preview-image').attr('src', '');
        $('#banner-preview-wrapper').hide();
        $('#banner-dropzone').show();
    });

    // 8. Integrasi Ladda Loading Spinner untuk tombol submit form
    $(document).on('submit', '#banner-create-form', function() {
        const btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            const l = Ladda.create(btn[0]);
            l.start();
        }
    });
});
</script>
