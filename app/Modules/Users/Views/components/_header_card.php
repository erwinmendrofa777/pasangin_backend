<!-- ===== HEADER & FILTER CARD ===== -->
<div class="card header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                        <i class="fas fa-users" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;">Kelola User</h5>
                        <p class="text-muted mb-0 small">Daftar, cari, dan kelola status akun client serta pelanggan Pasangin.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                    <!-- Dropdown Filter Status (Custom Styled) -->
                    <div class="custom-dropdown filter-wrapper w-100 w-sm-auto" style="min-width: 140px;">
                        <button type="button" class="dropdown-trigger w-100 d-flex align-items-center justify-content-between px-3" id="dropdownStatusTrigger" style="padding-left: 36px !important;">
                            <span id="selectedStatusText">Semua Status</span>
                            <i class="fas fa-chevron-down arrow-icon" style="font-size: 0.75rem; color: #adb5bd; transition: transform 0.3s ease;"></i>
                        </button>
                        <i class="fas fa-filter filter-icon"></i>
                        <div class="dropdown-menu-list w-100" id="dropdownStatusMenu">
                            <a class="dropdown-item-custom active" data-value="all" href="javascript:void(0)">
                                <i class="fas fa-list text-muted me-2"></i> Semua Status
                            </a>
                            <a class="dropdown-item-custom" data-value="approved" href="javascript:void(0)">
                                <i class="fas fa-check-circle text-success me-2"></i> Approved
                            </a>
                            <a class="dropdown-item-custom" data-value="pending" href="javascript:void(0)">
                                <i class="fas fa-clock text-warning me-2"></i> Pending
                            </a>
                            <a class="dropdown-item-custom" data-value="rejected" href="javascript:void(0)">
                                <i class="fas fa-times-circle text-danger me-2"></i> Rejected
                            </a>
                            <a class="dropdown-item-custom" data-value="banned" href="javascript:void(0)">
                                <i class="fas fa-ban text-secondary me-2"></i> Banned
                            </a>
                        </div>
                    </div>
                    <!-- Search Input -->
                    <div class="search-wrapper w-100 w-sm-auto" style="min-width: 260px;">
                        <input type="text" class="search-input w-100" id="searchInput" placeholder="Cari nama, email, telepon..." style="padding-left: 38px !important;">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
