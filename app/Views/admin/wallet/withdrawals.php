<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Permintaan Tarik Dana
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Permintaan Penarikan Dana Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f6ff;
    }

    #table-1 thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #f8fbff !important;
    }

    #table-1 tbody td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    @media (max-width: 768px) {
        .table-card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
            padding: 16px !important;
        }

        .header-actions {
            width: 100% !important;
        }

        .header-actions .btn {
            width: 100% !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm table-card">
    <!-- Card Header -->
    <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-md-center p-3 table-card-header" style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <a href="<?= base_url('admin/wallet') ?>" class="btn btn-secondary d-flex align-items-center justify-content-center text-nowrap" style="border-radius: 12px; font-size: 0.78rem; padding: 7px 16px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center justify-content-end" style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            Daftar Pengajuan Penarikan <i class="fas fa-file-invoice-dollar ms-2"></i>
        </h6>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Nama Tukang</th>
                        <th class="text-center">Nominal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): foreach ($requests as $r): ?>
                            <tr class="text-center align-middle">
                                <td class="text-muted" style="font-size:0.85rem;">
                                    <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                                </td>
                                <td class="text-start ps-3">
                                    <strong class="text-dark"><?= esc($r['tukang_name']) ?></strong><br>
                                    <small class="text-muted"><?= $r['phone'] ?></small>
                                </td>
                                <td class="fw-bold text-danger">
                                    Rp <?= number_format($r['amount'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php if ($r['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 50px;">Menunggu</span>
                                    <?php elseif ($r['status'] == 'approved'): ?>
                                        <span class="badge bg-success px-3 py-2" style="border-radius: 50px;">Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-3 py-2" style="border-radius: 50px;">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($r['status'] == 'pending'): ?>
                                        <?php if (can('wallet_withdraw_request')): ?>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?= base_url('admin/wallet/withdraw-approve/' . $r['id'] . '/approved') ?>" class="btn btn-sm btn-success ladda-button d-flex align-items-center justify-content-center" data-style="zoom-in" onclick="if(confirm('Setujui penarikan ini?')) { Ladda.create(this).start(); return true; } return false;" style="border-radius: 8px;">
                                                    <i class="fas fa-check me-1"></i><span class="ladda-label">Setujui</span>
                                                </a>
                                                <a href="<?= base_url('admin/wallet/withdraw-approve/' . $r['id'] . '/rejected') ?>" class="btn btn-sm btn-danger ladda-button d-flex align-items-center justify-content-center" data-style="zoom-in" onclick="if(confirm('Tolak penarikan ini?')) { Ladda.create(this).start(); return true; } return false;" style="border-radius: 8px;">
                                                    <i class="fas fa-times me-1"></i><span class="ladda-label">Tolak</span>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted"><i class="fas fa-lock me-1"></i> No Access</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">Tidak ada permintaan penarikan dana</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>