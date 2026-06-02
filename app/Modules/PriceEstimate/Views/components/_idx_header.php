<!-- HEADER BANNER -->
<div class="card page-header-card mb-4 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h4 class="text-primary mb-2 fw-bold">Estimasi Harga Per m²</h4>
                <p class="text-muted mb-0 small">Konfigurasi standar biaya konstruksi berdasarkan konsep arsitektur dan tier kualitas material.</p>
                <div class="mt-3 d-flex gap-3">
                    <div class="stat-pill shadow-sm">
                        <span>Konsep</span>
                        <span class="stat-num"><?= number_format($stats['total_concepts']) ?></span>
                    </div>
                    <div class="stat-pill shadow-sm">
                        <span>Tier Kualitas</span>
                        <span class="stat-num"><?= number_format($stats['total_qualities']) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end mt-4 mt-md-0">
                <?php if (can('price-estimate_create')): ?>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold shadow-primary" data-bs-toggle="modal" data-bs-target="#addConceptModal" style="border-radius: 12px; height: 46px;">
                    <i class="fas fa-plus me-2"></i>Tambah Konsep Baru
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
