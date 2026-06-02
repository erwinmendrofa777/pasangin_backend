<!-- Editor.js Local Assets -->
<script src="<?= base_url('assets/js/editorjs/editorjs.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/header.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/list.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/delimiter.min.js') ?>"></script>

<script>
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= is_array(session()->getFlashdata('error')) ? implode(' ', session()->getFlashdata('error')) : session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    // Initialize Editor.js
    const editor = new EditorJS({
        holder: 'editorjs',
        autofocus: true,
        placeholder: 'Mulai tulis isi dokumen Syarat & Ketentuan di sini...',
        tools: {
            header: {
                class: Header,
                config: {
                    levels: [1, 2, 3, 4],
                    defaultLevel: 2
                }
            },
            list: {
                class: List,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'ordered'
                }
            },
            delimiter: Delimiter,
        }
    });

    // Handle form submit: serialize editor to hidden input before POST
    const form = document.getElementById('sk-form');
    const laddaBtn = form.querySelector('.ladda-button');
    let laddaInstance;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        try {
            const outputData = await editor.save();

            // Validate editor has content
            if (!outputData.blocks || outputData.blocks.length === 0) {
                document.getElementById('editor-error').style.display = 'block';
                return;
            }

            document.getElementById('editor-error').style.display = 'none';

            // Set JSON string to hidden input
            document.getElementById('description-hidden').value = JSON.stringify(outputData);

            // Start Ladda loading
            laddaInstance = Ladda.create(laddaBtn);
            laddaInstance.start();

            // Submit the form
            form.submit();

        } catch (error) {
            console.error('Editor save failed:', error);
        }
    });
</script>
