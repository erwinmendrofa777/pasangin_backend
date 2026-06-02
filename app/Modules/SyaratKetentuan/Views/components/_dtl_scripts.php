<script>
    // Editor.js JSON data from PHP
    const editorData = <?= $data['description'] ?? '{}' ?>;

    // Render Editor.js blocks to HTML
    function renderEditorBlocks(data) {
        const container = document.getElementById('doc-rendered-content');
        if (!data || !data.blocks || data.blocks.length === 0) {
            container.innerHTML = '<p class="text-muted fst-italic">Tidak ada konten.</p>';
            return;
        }

        let html = '';
        data.blocks.forEach(block => {
            switch (block.type) {
                case 'header':
                    const level = block.data.level || 2;
                    html += `<h${level}>${block.data.text}</h${level}>`;
                    break;

                case 'paragraph':
                    html += `<p>${block.data.text}</p>`;
                    break;

                case 'list':
                    const tag = block.data.style === 'ordered' ? 'ol' : 'ul';
                    const items = block.data.items.map(item => `<li>${item}</li>`).join('');
                    html += `<${tag}>${items}</${tag}>`;
                    break;

                case 'delimiter':
                    html += `<hr class="sk-delimiter">`;
                    break;

                default:
                    // Fallback: try to show text content if available
                    if (block.data && block.data.text) {
                        html += `<p>${block.data.text}</p>`;
                    }
                    break;
            }
        });

        container.innerHTML = html;
    }

    renderEditorBlocks(editorData);
</script>
