<!-- ===== HEADER & STATS CARD ===== -->
<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-tools" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Kelola Mitra Tukang</h5>
                        <p class="text-muted mb-0 small">Daftar, cari, dan kelola profil serta status verifikasi mitra
                            tukang Pasangin.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                    <!-- Search Input -->
                    <div class="search-wrapper" style="width: 240px; max-width: 100%;">
                        <input type="text" class="search-input w-100" id="searchInput"
                            placeholder="Cari nama, spesialisasi, telepon..." style="padding-left: 38px !important;">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <!-- Tambah Mitra Button -->
                    <?php if (can('tukang_create')): ?>
                        <a href="<?= base_url('admin/tukang/create') ?>"
                            class="btn btn-primary d-flex align-items-center justify-content-center"
                            style="border-radius: 10px; font-size: 0.82rem; height: 40px; padding: 0 16px; white-space: nowrap; font-weight: 600;">
                            <i class="fas fa-plus me-1"></i> Tambah Mitra
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        $totalMandor = 0;
        $totalTukang = 0;
        $uniqueGroups = [];
        $noGroupCount = 0;

        foreach ($tukang as $t) {
            if (($t['role'] ?? '') === 'mandor') {
                $totalMandor++;
            } elseif (($t['role'] ?? '') === 'tukang') {
                $totalTukang++;
            }

            if (!empty($t['group_name'])) {
                $uniqueGroups[$t['group_name']] = true;
            } else {
                $noGroupCount++;
            }
        }
        $totalGroups = count($uniqueGroups);
        ?>

        <!-- Row Ringkasan Statistik -->
        <div class="row g-3 align-items-center mt-2">
            <!-- Col 1: Total Kelompok -->
            <div class="col-6 col-lg-3">
                <div class="d-flex align-items-center py-2 px-1">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(59, 130, 246, 0.08); color: #3b82f6; flex-shrink: 0;">
                        <i class="fas fa-users-cog" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Total
                            Kelompok</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $totalGroups ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 2: Mitra Mandor -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.08); color: #f59e0b; flex-shrink: 0;">
                        <i class="fas fa-user-tie" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Mitra
                            Mandor</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $totalMandor ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 3: Mitra Tukang -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.08); color: #10b981; flex-shrink: 0;">
                        <i class="fas fa-hammer" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Mitra
                            Tukang</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $totalTukang ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Col 4: Tanpa Kelompok -->
            <div class="col-6 col-lg-3 stats-divider">
                <div class="d-flex align-items-center py-2 ps-lg-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px; background: rgba(107, 114, 128, 0.08); color: #6b7280; flex-shrink: 0;">
                        <i class="fas fa-user-slash" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block mb-0 fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 0.3px; text-transform: uppercase;">Tanpa
                            Kelompok</span>
                        <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.35rem; line-height: 1.2;">
                            <?= $noGroupCount ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>