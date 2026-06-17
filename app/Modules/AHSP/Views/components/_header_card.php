<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-12 col-md-auto d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-light-danger rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.08);">
                    <i class="fas fa-clipboard-list text-primary" style="font-size: 1.4rem;"></i>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Kelola AHSP</h5>
                    <p class="text-muted mb-0 small">Mengelola master data Analisa Harga Satuan Pekerjaan beserta bahan dan tenaga kerja.</p>
                </div>
            </div>
            
            <div class="col-12 col-md-auto d-flex align-items-center gap-3 justify-content-md-end">
                <!-- Premium Search -->
                <div class="search-wrapper" style="min-width: 250px;">
                    <input type="text" class="search-input w-100" id="searchInput" placeholder="Cari kode atau uraian AHSP..." style="padding-left: 40px; padding-right: 16px;">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <!-- Tambah AHSP Button -->
                <?php if (can('ahsp_create')): ?>
                    <button type="button" 
                            class="btn btn-primary d-flex align-items-center justify-content-center btn-tambah-ahsp"
                            data-bs-toggle="modal" data-bs-target="#ahspModal"
                            style="border-radius:10px; font-size: 0.85rem; height: 40px; padding: 0 20px; font-weight: 700;">
                        <i class="fas fa-plus me-1"></i> Tambah AHSP
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
