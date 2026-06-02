<div class="row justify-content-center">
    <div class="col-12 col-lg-11">
        <div class="card form-card">
            <div class="card-header d-flex align-items-center">
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">
                    <i class="fas fa-plus-circle me-2"></i>Buat Tips & Tricks Baru
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/tips/store') ?>" method="post" id="tips-form" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="content" id="content-hidden">

                    <div class="row g-4">
                        <!-- Left Side: Basic Info -->
                        <div class="col-12 col-md-4">
                            <div class="mb-4">
                                <label class="form-label-custom">Gambar Sampul</label>
                                <div class="image-preview-container" onclick="document.getElementById('image-input').click()">
                                    <i class="fas fa-image" id="placeholder-icon"></i>
                                    <img src="" id="img-preview" class="d-none">
                                </div>
                                <input type="file" name="image" id="image-input" class="d-none" accept="image/*" onchange="previewImage(this)" required>
                                <small class="text-muted">Klik kotak di atas untuk unggah gambar sampul tips.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Target Aplikasi</label>
                                <select class="form-select border-0 shadow-none bg-light" name="target_app" style="border-radius: 10px; height: 45px;" required>
                                    <option value="Client">Client</option>
                                    <option value="Tukang">Tukang</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Status</label>
                                <select class="form-select border-0 shadow-none bg-light" name="is_active" style="border-radius: 10px; height: 45px;">
                                    <option value="1">Aktif</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Side: Content -->
                        <div class="col-12 col-md-8">
                            <div class="mb-4">
                                <label class="form-label-custom">Judul Tips</label>
                                <input type="text" name="title" class="form-control border-0 shadow-none bg-light" 
                                    style="border-radius: 10px; height: 45px;" placeholder="Contoh: Cara Merawat Keramik Kamar Mandi" required>
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
                        <a href="<?= base_url('admin/tips') ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in"
                            style="border-radius: 10px;" id="submit-btn">
                            <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Tips</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
