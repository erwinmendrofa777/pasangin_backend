<!-- ===== KONTEN NOTIFIKASI ===== -->
<div class="card section-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-edit me-2"></i>Konten Notifikasi</h6>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <label class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                placeholder="Contoh: Promo Diskon 50%!" required>
        </div>
        <div class="mb-0">
            <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control" rows="5" style="height: 120px;"
                placeholder="Tuliskan deskripsi lengkap pesan..." required></textarea>
        </div>
    </div>
</div>

<!-- ===== SUBMIT BUTTON ===== -->
<div class="row g-3 justify-content-center mt-2">
    <div class="col-12 col-md-8">
        <?php if (can('notification_create')): ?>
            <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                <span class="ladda-label"><i class="fas fa-paper-plane me-2"></i>Kirim Notifikasi Sekarang</span>
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-secondary w-100 btn-save"
                style="background: #6c757d;" disabled>
                <i class="fas fa-lock me-2"></i>Akses Ditolak
            </button>
        <?php endif; ?>
    </div>
</div>
