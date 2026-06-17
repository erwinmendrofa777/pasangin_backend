<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: rgba(255, 92, 92, 0.1); color: var(--palette-primary);">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <div class="stat-val"><?= number_format($stats['total']) ?></div>
                <div class="stat-lbl">Total Sent</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: rgba(3, 105, 161, 0.1); color: #0369a1;">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <div class="stat-val"><?= number_format($stats['client']) ?></div>
                <div class="stat-lbl">Clients</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: rgba(133, 77, 14, 0.1); color: #854d0e;">
                <i class="fas fa-tools"></i>
            </div>
            <div>
                <div class="stat-val"><?= number_format($stats['tukang']) ?></div>
                <div class="stat-lbl">Tukang</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: rgba(21, 128, 61, 0.1); color: #15803d;">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <div class="stat-val"><?= number_format($stats['supplier']) ?></div>
                <div class="stat-lbl">Suppliers</div>
            </div>
        </div>
    </div>
</div>
