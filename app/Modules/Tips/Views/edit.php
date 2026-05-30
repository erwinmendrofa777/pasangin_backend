<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Edit Tips & Tricks
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

    /* Editor.js Wrapper */
    .editor-wrapper {
        border: 2px solid #f1f3f9;
        border-radius: 12px;
        min-height: 400px;
        background: #fff;
        transition: border-color 0.2s;
        overflow: visible;
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

    /* Image Preview */
    .image-preview-container {
        width: 100%;
        height: 200px;
        border: 2px dashed #f1f3f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 15px;
        cursor: pointer;
        transition: 0.3s;
        position: relative;
    }

    .image-preview-container:hover {
        border-color: #6777ef;
        background: #fcfcff;
    }

    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-container i {
        font-size: 40px;
        color: #d0d4f5;
    }

    .edit-image-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: bold;
        color: #6777ef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        pointer-events: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-11">
        <div class="card form-card">
            <div class="card-header d-flex align-items-center">
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">
                    <i class="fas fa-edit me-2"></i>Edit Tips & Tricks
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/tips/update/' . $tips['id']) ?>" method="post" id="tips-form" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="content" id="content-hidden">

                    <div class="row g-4">
                        <!-- Left Side: Basic Info -->
                        <div class="col-12 col-md-4">
                            <div class="mb-4">
                                <label class="form-label-custom">Gambar Sampul</label>
                                <div class="image-preview-container" onclick="document.getElementById('image-input').click()">
                                    <?php if (!empty($tips['image'])): ?>
                                        <img src="<?= base_url('uploads/tips/' . $tips['image']) ?>" id="img-preview">
                                        <i class="fas fa-image d-none" id="placeholder-icon"></i>
                                    <?php else: ?>
                                        <img src="" id="img-preview" class="d-none">
                                        <i class="fas fa-image" id="placeholder-icon"></i>
                                    <?php endif; ?>
                                    <div class="edit-image-overlay"><i class="fas fa-camera me-1"></i> Ganti</div>
                                </div>
                                <!-- Tidak required saat edit -->
                                <input type="file" name="image" id="image-input" class="d-none" accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Target Aplikasi</label>
                                <select class="form-select border-0 shadow-none bg-light" name="target_app" style="border-radius: 10px; height: 45px;" required>
                                    <option value="Client" <?= (strtolower($tips['target_app']) == 'client') ? 'selected' : '' ?>>Client</option>
                                    <option value="Tukang" <?= (strtolower($tips['target_app']) == 'tukang') ? 'selected' : '' ?>>Tukang</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Status</label>
                                <select class="form-select border-0 shadow-none bg-light" name="is_active" style="border-radius: 10px; height: 45px;">
                                    <option value="1" <?= ($tips['is_active'] == 1) ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= ($tips['is_active'] == 0) ? 'selected' : '' ?>>Draft</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Side: Content -->
                        <div class="col-12 col-md-8">
                            <div class="mb-4">
                                <label class="form-label-custom">Judul Tips</label>
                                <input type="text" name="title" class="form-control border-0 shadow-none bg-light" 
                                    style="border-radius: 10px; height: 45px;" value="<?= esc($tips['title']) ?>" placeholder="Contoh: Cara Merawat Keramik Kamar Mandi" required>
                            </div>

                            <div class="mb-0">
                                <label class="form-label-custom">Isi Konten</label>
                                <p class="text-muted small mb-2" style="margin-top: -4px;">
                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                    Gunakan toolbar <kbd>+</kbd> untuk menambah gambar, judul, atau daftar.
                                </p>
                                <div class="editor-wrapper">
                                    <div id="editorjs"></div>
                                </div>
                                <div id="editor-error" class="text-danger small fw-bold mt-2 ps-1" style="display:none;">
                                    <i class="fas fa-exclamation-circle me-1"></i> Konten tips tidak boleh kosong.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5 border-top pt-4">
                        <a href="<?= base_url('admin/tips/detail/' . $tips['id']) ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in"
                            style="border-radius: 10px;" id="submit-btn">
                            <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Perubahan</span>
                        </button>
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
<?= $this->endSection() ?>
