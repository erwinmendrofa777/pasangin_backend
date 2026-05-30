<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tentang Aplikasi Pasangin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tentang Aplikasi Pasangin
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
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">
                    <i class="fas fa-info-circle me-2"></i>Tentang Aplikasi Pasangin
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/about_application/update') ?>" method="post" id="about-form">
                    <?= csrf_field() ?>
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="description" id="description-hidden">

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi Aplikasi</label>
                            <p class="text-muted small mb-2" style="margin-top: -4px;">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Gunakan toolbar <kbd>+</kbd> untuk menambah blok (Heading, List, Delimiter).
                            </p>
                            <div class="editor-wrapper">
                                <div id="editorjs"></div>
                            </div>
                            <div id="editor-error" class="text-danger small fw-bold mt-2 ps-1" style="display:none;">
                                <i class="fas fa-exclamation-circle me-1"></i> Deskripsi tidak boleh kosong.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <?php if (can('about_application_update')): ?>
                            <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in"
                                style="border-radius: 10px;" id="submit-btn">
                                <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Perubahan</span>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary px-4 fw-bold" style="border-radius: 10px;"
                                disabled>
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
<?= $this->endSection() ?>