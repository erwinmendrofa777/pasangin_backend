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
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    // Data existing dari DB
    const existingData = <?= json_encode(json_decode($data['description'] ?? '{}')) ?? '{}' ?>;

    const editor = new EditorJS({
        holder: 'editorjs',
        data: (existingData && existingData.blocks) ? existingData : undefined,
        placeholder: 'Mulai tulis deskripsi tentang Aplikasi Pasangin di sini...',
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

    const form = document.getElementById('about-form');
    const laddaBtn = form.querySelector('.ladda-button');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        try {
            const outputData = await editor.save();

            if (!outputData.blocks || outputData.blocks.length === 0) {
                document.getElementById('editor-error').style.display = 'block';
                return;
            }

            document.getElementById('editor-error').style.display = 'none';
            document.getElementById('description-hidden').value = JSON.stringify(outputData);

            if (laddaBtn) {
                const laddaInstance = Ladda.create(laddaBtn);
                laddaInstance.start();
            }

            form.submit();

        } catch (error) {
            console.error('Editor save failed:', error);
        }
    });
</script>
