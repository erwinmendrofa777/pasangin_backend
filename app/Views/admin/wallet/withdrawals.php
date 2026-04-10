<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Permintaan Tarik Dana <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Permintaan Penarikan Dana Tukang <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Pengajuan Penarikan</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/wallet') ?>" class="btn btn-primary">Kembali ke Daftar Saldo</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Tukang</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($requests)): foreach($requests as $r): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                <td>
                                    <strong><?= esc($r['tukang_name']) ?></strong><br>
                                    <small class="text-muted"><?= $r['phone'] ?></small>
                                </td>
                                <td class="font-weight-bold text-danger">
                                    Rp <?= number_format($r['amount'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php if($r['status'] == 'pending'): ?>
                                        <span class="badge text-bg-warning text-white">Menunggu</span>
                                    <?php elseif($r['status'] == 'approved'): ?>
                                        <span class="badge badge-success">Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($r['status'] == 'pending'): ?>
                                        <a href="<?= base_url('admin/wallet/withdraw-approve/' . $r['id'] . '/approved') ?>" class="btn btn-sm btn-success ladda-button" data-style="zoom-in" onclick="if(confirm('Setujui penarikan ini?')) { Ladda.create(this).start(); return true; } return false;">
                                            <span class="ladda-label">Setujui</span>
                                        </a>
                                        <a href="<?= base_url('admin/wallet/withdraw-approve/' . $r['id'] . '/rejected') ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Tolak penarikan ini?')) { Ladda.create(this).start(); return true; } return false;">
                                            <span class="ladda-label">Tolak</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada permintaan penarikan dana</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 20000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 20000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    
    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
</script>
<?= $this->endSection() ?>