<!-- ===== BANNER PREVIEW AREA ===== -->
<div class="text-center mb-4">
    <div class="avatar-wrapper mx-auto">
        <div class="banner-placeholder" id="img-preview-placeholder">
            <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
            <span style="font-size: 0.75rem; font-weight: 700; opacity: 0.8;">GAMBAR BANNER (OPSIONAL)</span>
        </div>
        <img src="" alt="Preview" class="banner-preview-img d-none" id="img-preview">

        <!-- Upload Button Overlay -->
        <label for="image" class="btn btn-primary position-absolute rounded-circle shadow"
            style="bottom: 10px; right: 10px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid #fff;">
            <i class="fas fa-camera"></i>
        </label>
        <input type="file" id="image" name="image" class="d-none" accept="image/*"
            onchange="previewImage()">
    </div>
    <div class="mt-3">
        <p class="small text-muted mb-0">Rekomendasi ukuran: 1280 x 720 px (Maks. 500KB)</p>
    </div>
</div>
