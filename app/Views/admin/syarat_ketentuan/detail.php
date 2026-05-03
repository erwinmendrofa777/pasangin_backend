<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
        overflow: hidden;
    }

    .detail-header {
        background: #fff;
        border-bottom: 1px solid #f8f9fa;
        padding: 30px;
    }

    .detail-body {
        background: #fff;
        padding: 40px;
    }

    .doc-content {
        background: #fdfdff;
        border: 1.5px solid #f1f3f9;
        border-radius: 16px;
        padding: 40px 48px;
        font-size: 1rem;
        line-height: 1.9;
        color: #343a40;
    }

    /* Editor.js Block Rendering Styles */
    .doc-content h1 { font-size: 2rem; font-weight: 800; margin: 1.5rem 0 0.75rem; color: #1a1d23; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem; }
    .doc-content h2 { font-size: 1.5rem; font-weight: 700; margin: 1.5rem 0 0.75rem; color: #2d3748; }
    .doc-content h3 { font-size: 1.2rem; font-weight: 700; margin: 1.2rem 0 0.6rem; color: #4a5568; }
    .doc-content h4 { font-size: 1rem; font-weight: 700; margin: 1rem 0 0.5rem; color: #718096; }

    .doc-content p { margin-bottom: 1rem; text-align: justify; }

    .doc-content ol, .doc-content ul {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .doc-content ol li, .doc-content ul li {
        margin-bottom: 0.4rem;
    }

    .doc-content hr.sk-delimiter {
        border: none;
        border-top: 3px solid #e9ecef;
        margin: 2rem auto;
        width: 40%;
        border-radius: 4px;
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        display: block;
    }

    .target-badge {
        display: inline-block;
        padding: 6px 16px;
        background: #f0f4ff;
        color: #6777ef;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
        border: 1px solid #e0e6ff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card detail-card">
            <div class="detail-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left small"></i>
                    </a>
                    <div>
                        <span class="meta-label">Pratinjau Dokumen</span>
                        <h4 class="mb-0 fw-800 text-dark"><?= esc($data['title']) ?></h4>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="target-badge">
                        <i class="fas fa-bullseye me-1"></i> <?= $data['target_app'] ?>
                    </span>
                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $data['id']) ?>" class="btn btn-warning px-3 fw-bold" style="border-radius: 10px;">
                        <i class="fas fa-pencil-alt me-2"></i>Edit
                    </a>
                </div>
            </div>
            <div class="detail-body">
                <div class="mb-4">
                    <span class="meta-label mb-3">Isi Dokumen</span>
                    <div class="doc-content shadow-sm" id="doc-rendered-content">
                        <!-- Content rendered by JS below -->
                    </div>
                </div>

                <div class="pt-4 border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="far fa-clock me-1"></i> Terakhir diperbarui: <span class="fw-bold"><?= date('d M Y, H:i') ?></span>
                    </div>
                    <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
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
<?= $this->endSection() ?>