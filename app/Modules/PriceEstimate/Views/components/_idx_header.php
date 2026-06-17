<!-- ===== HEADER & FILTER CARD ===== -->
<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-calculator" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Estimasi Harga Per m²</h5>
                        <p class="text-muted mb-0 small">Konfigurasi standar biaya konstruksi berdasarkan konsep arsitektur dan tier kualitas material.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                    <?php if (can('price-estimate_create')): ?>
                        <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#addConceptModal"
                            style="border-radius: 10px; font-size: 0.82rem; height: 40px; padding: 0 16px; white-space: nowrap; font-weight: 600;">
                            <i class="fas fa-plus me-1"></i> Tambah Konsep Baru
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

