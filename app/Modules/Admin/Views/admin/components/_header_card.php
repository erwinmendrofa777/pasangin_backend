<!-- ===== HEADER & FILTER CARD ===== -->
<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-user-tie" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Kelola Admin</h5>
                        <p class="text-muted mb-0 small">Manajemen data administrator untuk mengontrol hak akses sistem.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                    <!-- Search Input -->
                    <div class="search-wrapper" style="width: 240px; max-width: 100%;">
                        <input type="text" class="search-input w-100" id="searchInput" placeholder="Cari admin..." style="padding-left: 38px !important;">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <!-- Tambah Admin Button -->
                    <?php if (can('admin_create')): ?>
                        <a href="<?= base_url('admin/admin/create') ?>"
                            class="btn btn-primary d-flex align-items-center justify-content-center"
                            style="border-radius: 10px; font-size: 0.82rem; height: 40px; padding: 0 16px; white-space: nowrap; font-weight: 600;">
                            <i class="fas fa-plus me-1"></i> Tambah Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
