<form id="edit-banner-form" method="POST" action="<?= base_url('admin/banner-supplier/update/' . $banner['id']) ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Edit Banner Supplier
                            </h5>
                            <p class="text-white-50 small mb-0">Perbarui informasi banner promosi mitra supplier</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2 d-none d-sm-inline-block"
                            style="border-radius:50px; font-size:0.75rem; font-weight:700;">
                            <i class="fas fa-pencil-alt me-1 opacity-75"></i>EDIT DATA
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pe-0 ps-0">

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-9">

                            <!-- Banner Preview Area -->
                            <div class="text-center mb-4">
                                <div class="avatar-wrapper mx-auto">
                                    <img src="<?= base_url('uploads/supplier/banner/' . $banner['image']) ?>"
                                        alt="Preview" class="banner-preview-img" id="img-preview">

                                    <!-- Upload Button Overlay -->
                                    <label for="image" class="btn btn-primary position-absolute rounded-circle shadow"
                                        style="bottom: 10px; right: 10px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid #fff;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="image" name="image" class="d-none" accept="image/*"
                                        onchange="previewImage()">
                                </div>
                                <div class="mt-3">
                                    <p class="small text-muted">Biarkan kosong jika tidak ingin mengubah
                                        gambar.<br>Rekomendasi: 1200 x 600 px (Maks. 3MB)</p>
                                    <?php if (isset(session('errors')['image'])): ?>
                                        <div class="text-danger small fw-bold mt-1"><?= session('errors')['image'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Banner Information Form -->
                            <div class="card section-card mb-2">
                                <div class="card-header">
                                    <h6><i class="fas fa-edit me-2"></i>Informasi Banner</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Pilih Supplier <span
                                                class="text-danger">*</span></label>
                                        <select name="id_supplier" class="form-control select2" required>
                                            <option value="">-- Cari Supplier --</option>
                                            <?php foreach ($suppliers as $s): ?>
                                                <option value="<?= $s['id'] ?>" <?= (old('id_supplier') ?? $banner['id_supplier']) == $s['id'] ? 'selected' : '' ?>>
                                                    <?= esc($s['name']) ?> (ID: #<?= $s['id'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset(session('errors')['id_supplier'])): ?>
                                            <small
                                                class="text-danger fw-bold"><?= session('errors')['id_supplier'] ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Judul Promo / Banner <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Misal: Diskon Keramik Akhir Tahun"
                                            value="<?= old('title') ?? $banner['title'] ?>" required>
                                        <?php if (isset(session('errors')['title'])): ?>
                                            <small class="text-danger fw-bold"><?= session('errors')['title'] ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Mulai <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control"
                                                value="<?= old('start_date') ?? $banner['start_date'] ?>" required>
                                            <?php if (isset(session('errors')['start_date'])): ?>
                                                <small
                                                    class="text-danger fw-bold"><?= session('errors')['start_date'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Berakhir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="end_date" class="form-control"
                                                value="<?= old('end_date') ?? $banner['end_date'] ?>" required>
                                            <?php if (isset(session('errors')['end_date'])): ?>
                                                <small
                                                    class="text-danger fw-bold"><?= session('errors')['end_date'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label">Catatan (Internal)</label>
                                        <textarea name="note" class="form-control" rows="3"
                                            placeholder="Tambahkan catatan khusus jika ada..."><?= old('note') ?? $banner['note'] ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row g-3 justify-content-center">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                        <span class="ladda-label"><i class="fas fa-save me-2"></i>Perbarui Banner</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>