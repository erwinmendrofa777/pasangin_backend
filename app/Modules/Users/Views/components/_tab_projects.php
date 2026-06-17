<div class="py-2">
    <p class="section-title mb-4"><i class="fas fa-building me-2"></i>Daftar Pengajuan Proyek</p>

    <!-- Loading State -->
    <div id="projects-loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">Memuat data proyek...</p>
    </div>

    <!-- Error State -->
    <div id="projects-error" class="alert alert-danger d-none" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data proyek. Silakan coba lagi.
    </div>

    <!-- Empty State for All -->
    <div id="projects-empty" class="text-center py-5 d-none">
        <div class="mb-3">
            <i class="fas fa-folder-open text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
        </div>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Belum ada pengajuan proyek untuk user ini.</p>
    </div>

    <!-- Projects Content wrapper -->
    <div id="projects-wrapper" class="d-none">
        
        <!-- SECTION 1: KONSTRUKSI -->
        <div class="card mb-4 border-0 shadow-sm" style="border-radius:12px; background: #fafbfe;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark d-flex align-items-center mb-3">
                    <i class="fas fa-hammer me-2 text-primary"></i>Pengajuan Konstruksi
                    <span class="badge ms-2 bg-primary rounded-pill" id="construction-badge-count" style="font-size:0.75rem;">0</span>
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white rounded border mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px;" class="text-center">ID</th>
                                <th>Luas Tanah / Bangunan</th>
                                <th>Tanggal Survei</th>
                                <th>Total Pembayaran</th>
                                <th class="text-center" style="width:130px;">Status</th>
                                <th class="text-center" style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="construction-table-body">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DESAIN -->
        <div class="card mb-4 border-0 shadow-sm" style="border-radius:12px; background: #fafbfe;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark d-flex align-items-center mb-3">
                    <i class="fas fa-drafting-compass me-2" style="color:#0d9488;"></i>Pengajuan Desain
                    <span class="badge ms-2 rounded-pill text-white" id="design-badge-count" style="background-color:#0d9488; font-size:0.75rem;">0</span>
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white rounded border mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px;" class="text-center">ID</th>
                                <th>Konsep Desain</th>
                                <th>Luas Tanah / Bangunan</th>
                                <th>Total Pembayaran</th>
                                <th class="text-center" style="width:130px;">Status</th>
                                <th class="text-center" style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="design-table-body">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION 3: RENOVASI -->
        <div class="card border-0 shadow-sm" style="border-radius:12px; background: #fafbfe;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark d-flex align-items-center mb-3">
                    <i class="fas fa-tools me-2" style="color:#d97706;"></i>Pengajuan Renovasi
                    <span class="badge ms-2 rounded-pill text-white" id="renovation-badge-count" style="background-color:#d97706; font-size:0.75rem;">0</span>
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white rounded border mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px;" class="text-center">ID</th>
                                <th>Tipe Renovasi</th>
                                <th>Alamat Lokasi</th>
                                <th>Total Pembayaran</th>
                                <th class="text-center" style="width:130px;">Status</th>
                                <th class="text-center" style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="renovation-table-body">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
