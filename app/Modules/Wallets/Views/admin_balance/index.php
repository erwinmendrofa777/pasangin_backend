<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Saldo Admin & Platform
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Saldo Admin & Platform
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== PREMIUM FINTECH CARDS ===== */
    .fintech-card {
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 30px;
    }
    
    .fintech-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.05);
    }
    
    .fintech-card-green {
        border-top: 5px solid #10B981;
    }
    
    .fintech-card-primary {
        border-top: 5px solid var(--palette-primary);
    }
    
    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .card-title-text {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .card-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .fintech-card-green .card-icon-wrap {
        background: #ecfdf5;
        color: #10B981;
    }
    
    .fintech-card-primary .card-icon-wrap {
        background: #fff5f5;
        color: var(--palette-primary);
    }

    .card-amount {
        font-size: 2.3rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin-bottom: 25px;
    }

    /* ===== ACTION BUTTONS ===== */
    .fintech-btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .fintech-btn {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1.5px solid transparent;
        cursor: pointer;
    }
    
    .fintech-btn-success {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }
    
    .fintech-btn-success:hover {
        background: #10B981;
        color: #ffffff;
        border-color: #10B981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    
    .fintech-btn-danger {
        background: #fff5f5;
        color: #e11d48;
        border-color: #fecdd3;
    }
    
    .fintech-btn-danger:hover {
        background: var(--palette-primary);
        color: #ffffff;
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.2);
    }
    
    .fintech-btn-secondary {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }
    
    .fintech-btn-secondary:hover {
        background: #64748b;
        color: #ffffff;
        border-color: #64748b;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.2);
    }

    /* ===== STATUS CHIPS ===== */
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 50px;
        border: 1px solid transparent;
    }
    
    .status-chip-success {
        background: #ecfdf5;
        color: #059669;
        border-color: #d1fae5;
    }
    
    .status-chip-danger {
        background: #fff5f5;
        color: #e11d48;
        border-color: #ffe4e4;
    }

    .status-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .status-chip-success .dot {
        background-color: #10B981;
        box-shadow: 0 0 8px #10B981;
        position: relative;
    }
    
    .status-chip-success .dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: inherit;
        top: 0;
        left: 0;
        animation: pulseRadar 1.8s ease-out infinite;
    }
    
    @keyframes pulseRadar {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(2.5); opacity: 0; }
    }
    
    .status-chip-danger .dot {
        background-color: #EF4444;
        box-shadow: 0 0 8px #EF4444;
    }

    /* ===== TABLE MODERNIZATION ===== */
    .table-card {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden !important;
        background: #fff !important;
        margin-top: 24px !important;
    }
    
    .table-card-header {
        border-bottom: 1px solid #f0f4fa !important;
        background: #fff !important;
    }
    
    .table-responsive {
        padding: 0 !important;
    }
    
    #table-transactions {
        margin-bottom: 0 !important;
        width: 100% !important;
    }
    
    #table-transactions thead tr {
        background: #fff5f5 !important;
    }
    
    #table-transactions thead th {
        color: var(--palette-primary) !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.6px !important;
        text-transform: uppercase !important;
        border-bottom: 2px solid #ffdddd !important;
        border-top: none !important;
        padding: 14px 12px !important;
        white-space: nowrap !important;
    }
    
    #table-transactions tbody tr {
        transition: background 0.15s ease !important;
    }
    
    #table-transactions tbody tr:hover {
        background-color: #fffafa !important;
    }
    
    #table-transactions tbody td {
        padding: 12px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f0f4fa !important;
        border-top: none !important;
        font-size: 0.88rem !important;
        color: #343a40 !important;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute !important;
        left: 16px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #adb5bd !important;
        font-size: 0.95rem !important;
        pointer-events: none !important;
        z-index: 5 !important;
    }

    .search-wrapper input {
        padding-left: 44px !important;
        border-radius: 12px !important;
        border: 1.5px solid #dee2e6 !important;
        transition: all 0.2s ease !important;
        font-size: 0.88rem !important;
        height: 42px !important;
    }

    .search-wrapper input:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1) !important;
    }

    .search-wrapper input::placeholder {
        color: #adb5bd !important;
        opacity: 0.8 !important;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px !important;
        border-top: 1px solid #f0f4fa !important;
        background: #fafcff !important;
    }

    .dataTables_info {
        font-size: 0.82rem !important;
        color: #6c757d !important;
    }

    .dataTables_paginate .page-item .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        margin: 0 3px !important;
        border: 1px solid transparent !important;
        color: var(--palette-primary) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.3) !important;
    }

    .dataTables_paginate .page-item:not(.active) .page-link:hover {
        background: #ffe5e5 !important;
        border-color: #ffe5e5 !important;
        color: var(--palette-primary) !important;
    }

    /* ===== BADGES ===== */
    .badge-premium {
        border-radius: 50px !important;
        padding: 6px 14px !important;
        font-size: 0.72rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
    }

    .badge-income {
        background-color: #ecfdf5 !important;
        color: #059669 !important;
        border: 1px solid #d1fae5 !important;
    }

    .badge-expense {
        background-color: #fff5f5 !important;
        color: #e11d48 !important;
        border: 1px solid #ffe4e4 !important;
    }

    .badge-source {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }

    /* ===== MODAL BEAUTIFICATION ===== */
    .fintech-modal .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    
    .fintech-modal .modal-header {
        padding: 24px 30px;
        border-bottom: none;
    }
    
    .fintech-modal .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -0.2px;
    }
    
    .fintech-modal .modal-body {
        padding: 30px;
    }
    
    .fintech-modal .modal-footer {
        padding: 20px 30px 24px;
        border-top: none;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .form-group-custom {
        margin-bottom: 20px;
    }
    
    .form-group-custom label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control-custom {
        border-radius: 12px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 12px 16px !important;
        font-size: 0.92rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        width: 100% !important;
    }
    
    .form-control-custom:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1) !important;
    }
    
    .form-control-custom::placeholder {
        color: #94a3b8 !important;
        font-weight: 500 !important;
    }
    
    .input-group-custom {
        display: flex !important;
        align-items: center !important;
        position: relative !important;
    }
    
    .input-group-custom .prefix {
        position: absolute !important;
        left: 16px !important;
        font-weight: 700 !important;
        color: #475569 !important;
        font-size: 0.92rem !important;
        pointer-events: none !important;
        z-index: 10 !important;
    }
    
    .input-group-custom .form-control-custom {
        padding-left: 45px !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="section-body">



    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- KPI Balance Cards -->
    <div class="row g-4 mb-4">
        <!-- Saldo Platform Lokal -->
        <div class="col-12 col-lg-6">
            <div class="fintech-card fintech-card-green h-100">
                <div class="card-meta">
                    <span class="card-title-text">Saldo Platform (Internal)</span>
                    <div class="card-icon-wrap">
                        <i class="fas fa-university"></i>
                    </div>
                </div>
                <div class="card-amount">
                    Rp <?= number_format($localBalance, 0, ',', '.') ?>
                </div>
                <!-- Manual Action Buttons -->
                <?php if (can('admin_balance_manage')): ?>
                    <div class="fintech-btn-group">
                        <button type="button" class="fintech-btn fintech-btn-success" data-bs-toggle="modal" data-bs-target="#modalDeposit">
                            <i class="fas fa-plus-circle"></i> Deposit Manual
                        </button>
                        <button type="button" class="fintech-btn fintech-btn-danger" data-bs-toggle="modal" data-bs-target="#modalWithdraw">
                            <i class="fas fa-minus-circle"></i> Tarik Saldo
                        </button>
                        <button type="button" id="btn-sync-midtrans" class="fintech-btn fintech-btn-secondary">
                            <i class="fas fa-sync"></i> Sinkronkan Saldo
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Saldo Live Midtrans -->
        <div class="col-12 col-lg-6">
            <div class="fintech-card fintech-card-primary h-100">
                <div class="card-meta">
                    <span class="card-title-text">Saldo Penampungan Midtrans (Live Payin)</span>
                    <div class="card-icon-wrap">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="card-amount">
                    Rp <?= number_format($midtransBalance, 0, ',', '.') ?>
                </div>
                <div>
                    <?php if ($midtransError): ?>
                        <span class="status-chip status-chip-danger" title="<?= esc($midtransMessage) ?>">
                            <span class="dot"></span>
                            <span>Midtrans Terputus</span>
                        </span>
                    <?php else: ?>
                        <span class="status-chip status-chip-success">
                            <span class="dot"></span>
                            <span>Koneksi Midtrans Terhubung</span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History Table -->
    <div class="card table-card">
        <!-- Card Header: Search -->
        <div class="d-flex justify-content-between align-items-center p-4 table-card-header"
            style="border-bottom: 1px solid #f0f4fa; background: #fff;">
            <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
                style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
                <i class="fas fa-history me-2"></i>Riwayat Mutasi Saldo Platform
            </h6>
            <div class="search-wrapper" style="width: 320px; max-width: 100%;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari transaksi...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table-transactions" style="width:100%;">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center" style="width: 5%">No</th>
                            <th class="text-center" style="width: 20%">Tanggal</th>
                            <th class="text-center" style="width: 15%">Tipe</th>
                            <th class="text-center" style="width: 15%">Kategori</th>
                            <th class="text-center" style="width: 15%">Nominal</th>
                            <th class="text-center" style="width: 30%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history)): ?>
                            <tr class="empty-row">
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat transaksi platform.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($history as $h): ?>
                                <tr class="text-center align-middle">
                                    <td>
                                        <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $no++ ?></span>
                                    </td>
                                    <td class="text-muted"><?= date('d M Y - H:i', strtotime($h['created_at'])) ?> WIB</td>
                                    <td>
                                        <?php if ($h['type'] === 'income'): ?>
                                            <span class="badge-premium badge-income">
                                                <i class="fas fa-arrow-down me-1"></i> MASUK
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-premium badge-expense">
                                                <i class="fas fa-arrow-up me-1"></i> KELUAR
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge-premium badge-source">
                                            <?= esc(str_replace('_', ' ', $h['source'])) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold <?= $h['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                                        <?= $h['type'] === 'income' ? '+' : '-' ?> Rp <?= number_format($h['amount'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-start ps-3">
                                        <?= esc($h['description']) ?>
                                        <?php if ($h['reference_id']): ?>
                                            <br><small class="text-muted">Ref: <b><?= esc($h['reference_id']) ?></b></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ===== MODALS FOR DEPOSIT & WITHDRAWAL ===== -->
<?php if (can('admin_balance_manage')): ?>
    <!-- Modal Deposit -->
    <div class="modal fade fintech-modal" id="modalDeposit" tabindex="-1" aria-labelledby="modalDepositLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('admin/admin-balance/deposit') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDepositLabel">
                            <i class="fas fa-plus-circle me-2 text-success"></i> Deposit Manual Saldo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-custom">
                            <label for="deposit_amount">Nominal Deposit (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <span class="prefix">Rp</span>
                                <input type="number" name="amount" id="deposit_amount" class="form-control-custom" placeholder="Contoh: 500000" min="1" required>
                            </div>
                        </div>
                        <div class="form-group-custom">
                            <label for="deposit_desc">Keterangan / Catatan <span class="text-danger">*</span></label>
                            <textarea name="description" id="deposit_desc" class="form-control-custom" rows="3" placeholder="Contoh: Suntikan modal awal platform" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="fintech-btn fintech-btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="fintech-btn fintech-btn-success">Simpan Deposit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Withdraw -->
    <div class="modal fade fintech-modal" id="modalWithdraw" tabindex="-1" aria-labelledby="modalWithdrawLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('admin/admin-balance/withdraw') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalWithdrawLabel">
                            <i class="fas fa-minus-circle me-2 text-primary"></i> Tarik Dana Platform
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group-custom">
                            <label for="withdraw_amount">Nominal Penarikan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <span class="prefix">Rp</span>
                                <input type="number" name="amount" id="withdraw_amount" class="form-control-custom" placeholder="Contoh: 200000" min="1" required>
                            </div>
                        </div>
                        <div class="form-group-custom">
                            <label for="withdraw_desc">Keterangan / Catatan <span class="text-danger">*</span></label>
                            <textarea name="description" id="withdraw_desc" class="form-control-custom" rows="3" placeholder="Contoh: Penarikan profit platform oleh owner" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="fintech-btn fintech-btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="fintech-btn fintech-btn-danger">Simpan Penarikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        if ($('#table-transactions tbody tr:not(.empty-row)').length > 0) {
            var table = $('#table-transactions').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": "Tidak ada data yang tersedia",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan"
                },
                "order": [[ 1, "desc" ]], // Urutkan berdasarkan tanggal desc secara default
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
                "drawCallback": function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            /* ===== Custom Search ===== */
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });
            $('#searchInput').on('search', function () {
                if (this.value === '') table.search('').draw();
            });
        }

        // AJAX sinkronisasi saldo Midtrans
        $('#btn-sync-midtrans').on('click', function(e) {
            e.preventDefault();
            if (!confirm('Apakah Anda yakin ingin menyinkronkan saldo lokal dengan live Midtrans? Sistem akan membuat transaksi penyesuaian (balance adjustment) jika ada selisih.')) {
                return;
            }

            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyinkronkan...');

            $.ajax({
                url: '<?= base_url('admin/admin-balance/sync') ?>',
                type: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert(response.message);
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan koneksi atau server error.');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
