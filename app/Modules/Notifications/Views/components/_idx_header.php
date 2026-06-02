<!-- ===== HEADER SECTION: Ringkasan Statistik ===== -->
<div class="card page-header-card mb-2 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="text-primary mb-2 fw-bold">Riwayat Notifikasi</h4>
                <p class="text-muted mb-0 small">Pantau semua notifikasi sistem dan promosi yang telah dikirimkan ke
                    mitra &amp; pelanggan.</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end gap-2 mt-3 mt-md-0">
                <div class="stat-pill shadow-sm">
                    <span>Total Sent</span>
                    <span class="stat-num"><?= number_format($stats['total']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Clients</span>
                    <span class="stat-num"><?= number_format($stats['client']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Tukang</span>
                    <span class="stat-num"><?= number_format($stats['tukang']) ?></span>
                </div>
                <div class="stat-pill shadow-sm">
                    <span>Suppliers</span>
                    <span class="stat-num"><?= number_format($stats['supplier']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
