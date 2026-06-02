<!-- Editor.js Local Assets -->
<script src="<?= base_url('assets/js/editorjs/editorjs.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/header.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/list.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/delimiter.min.js') ?>"></script>
<!-- Image Tool via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
                document.getElementById('img-preview').classList.remove('d-none');
                document.getElementById('placeholder-icon').classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Parse data JSON awal
    const initialData = <?= $tips['content'] ?: '{}' ?>;

    const editor = new EditorJS({
        holder: 'editorjs',
        placeholder: 'Mulai tulis konten tips di sini...',
        data: initialData,
        tools: {
            header: {
                class: Header,
                config: {
                    levels: [2, 3, 4],
                    defaultLevel: 2
                }
            },
            list: {
                class: List,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'unordered'
                }
            },
            delimiter: Delimiter,
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '<?= base_url('admin/tips/upload-image') ?>',
                    },
                    additionalRequestHeaders: {
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    }
                }
            }
        }
    });

    const form = document.getElementById('tips-form');
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
            document.getElementById('content-hidden').value = JSON.stringify(outputData);

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
