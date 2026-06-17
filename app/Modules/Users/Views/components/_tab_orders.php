<div class="py-2">
    <p class="section-title mb-3"><i class="fas fa-shopping-cart me-2"></i>Daftar Riwayat Pesanan</p>
    
    <!-- Loading State -->
    <div id="orders-loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">Memuat data pesanan...</p>
    </div>

    <!-- Error State -->
    <div id="orders-error" class="alert alert-danger d-none" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data pesanan. Silakan coba lagi.
    </div>

    <!-- Empty State -->
    <div id="orders-empty" class="text-center py-5 d-none">
        <div class="mb-3">
            <i class="fas fa-shopping-bag text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
        </div>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Belum ada riwayat pesanan untuk user ini.</p>
    </div>

    <!-- Table Content -->
    <div id="orders-table-wrapper" class="table-responsive d-none">
        <table class="table table-hover align-middle" style="width:100%;">
            <thead class="table-light">
                <tr>
                    <th style="width:100px;">ID Order</th>
                    <th>Total Pembayaran</th>
                    <th class="text-center" style="width:150px;">Status</th>
                    <th class="text-center" style="width:180px;">Tanggal Pesanan</th>
                    <th class="text-center" style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                <!-- Populated via AJAX -->
            </tbody>
        </table>
    </div>
</div>
