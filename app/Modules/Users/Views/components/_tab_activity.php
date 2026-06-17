<div class="py-2">
    <p class="section-title mb-4"><i class="fas fa-history me-2"></i>Aktivitas & Log Akun</p>
    
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-light p-3 text-center h-100">
                <span class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">User ID</span>
                <span class="fs-5 fw-bold text-dark">#<?= esc($user['id']) ?></span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-light p-3 text-center h-100">
                <span class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Role Akun</span>
                <span class="fs-5 fw-bold text-primary text-capitalize"><?= esc($user['role']) ?></span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-light p-3 text-center h-100">
                <span class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Tanggal Registrasi</span>
                <span class="fs-6 fw-semibold text-dark"><?= !empty($user['created_at']) ? date('d M Y H:i', strtotime($user['created_at'])) : '-' ?></span>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-light p-3 text-center h-100">
                <span class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Terakhir Diupdate</span>
                <span class="fs-6 fw-semibold text-dark"><?= !empty($user['updated_at']) ? date('d M Y H:i', strtotime($user['updated_at'])) : '-' ?></span>
            </div>
        </div>
    </div>
</div>
