<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Syarat & Ketentuan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
    }

    .form-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f8f9fa;
        padding: 25px 30px;
    }

    .form-card .card-body {
        padding: 30px;
    }

    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 800;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control-custom {
        border-radius: 12px;
        border: 2px solid #f1f3f9;
        padding: 12px 16px;
        font-weight: 600;
        color: #495057;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #6777ef;
        background: #fff;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
        outline: none;
    }

    /* Editor.js Wrapper */
    .editor-wrapper {
        border: 2px solid #f1f3f9;
        border-radius: 12px;
        min-height: 400px;
        background: #fff;
        transition: border-color 0.2s;
        overflow: hidden;
    }

    .editor-wrapper:focus-within {
        border-color: #6777ef;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
    }

    #editorjs {
        padding: 16px 24px;
        min-height: 380px;
    }

    .codex-editor__redactor {
        padding-bottom: 60px !important;
    }

    .ce-block__content,
    .ce-toolbar__content {
        max-width: 100% !important;
    }

    .ce-toolbar__plus:hover,
    .ce-toolbar__settings-btn:hover {
        background: #6777ef !important;
        color: #fff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card form-card">
            <div class="card-header d-flex align-items-center">
                <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left small"></i>
                </a>
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">Edit Dokumen Syarat & Ketentuan</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/syarat_ketentuan/update/' . $data['id']) ?>" method="post" id="sk-form">
                    <?= csrf_field() ?>
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="description" id="description-hidden">

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Target Aplikasi</label>
                            <select name="target_app" class="form-select form-control-custom <?= session('errors.target_app') ? 'is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Target --</option>
                                <option value="CLIENT" <?= old('target_app', $data['target_app']) == 'CLIENT' ? 'selected' : '' ?>>Aplikasi Client</option>
                                <option value="TUKANG" <?= old('target_app', $data['target_app']) == 'TUKANG' ? 'selected' : '' ?>>Aplikasi Tukang (Mitra)</option>
                                <option value="SUPPLIER" <?= old('target_app', $data['target_app']) == 'SUPPLIER' ? 'selected' : '' ?>>Aplikasi Supplier</option>
                                <option value="PROYEK" <?= old('target_app', $data['target_app']) == 'PROYEK' ? 'selected' : '' ?>>Manajemen Proyek</option>
                            </select>
                            <?php if (session('errors.target_app')) : ?>
                                <div class="invalid-feedback fw-bold mt-2 ps-1"><?= session('errors.target_app') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Judul Dokumen</label>
                            <input type="text" name="title" class="form-control form-control-custom <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= old('title', $data['title']) ?>" placeholder="E.g. Kebijakan Privasi Client" required>
                            <?php if (session('errors.title')) : ?>
                                <div class="invalid-feedback fw-bold mt-2 ps-1"><?= session('errors.title') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Konten Dokumen</label>
                            <p class="text-muted small mb-2" style="margin-top: -4px;">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Gunakan toolbar <kbd>+</kbd> untuk menambah blok (Heading, List, Delimiter). Klik pada teks dan gunakan shortcut <kbd>/</kbd> untuk perintah cepat.
                            </p>
                            <div class="editor-wrapper">
                                <div id="editorjs"></div>
                            </div>
                            <div id="editor-error" class="text-danger small fw-bold mt-2 ps-1" style="display:none;">
                                <i class="fas fa-exclamation-circle me-1"></i> Konten dokumen tidak boleh kosong.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">Batal</a>
                        <?php if (can('syarat_ketentuan_update')): ?>
                        <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 10px;" id="submit-btn">
                            <span class="ladda-label"><i class="fas fa-save me-2"></i>Update Perubahan</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary px-4 fw-bold" style="border-radius: 10px;" disabled>
                            <i class="fas fa-lock me-2"></i>Akses Ditolak
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Editor.js Local Assets -->
<script src="<?= base_url('assets/js/editorjs/editorjs.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/header.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/list.min.js') ?>"></script>
<script src="<?= base_url('assets/js/editorjs/delimiter.min.js') ?>"></script>

<script>
    /* ===== Flash Messages ===== */
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

    // Existing data from DB passed as PHP JSON
    const existingData = <?= json_encode(json_decode($data['description'] ?? '{}')) ?? '{}' ?>;

    // Initialize Editor.js with existing data
    const editor = new EditorJS({
        holder: 'editorjs',
        data: (existingData && existingData.blocks) ? existingData : undefined,
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

    form.addEventListener('submit', async function(e) {
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
<?= $this->endSection() ?>