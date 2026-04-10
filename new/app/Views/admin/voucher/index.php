<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Voucher
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Voucher Diskon
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        
        <!-- Notifikasi Sukses -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Daftar Voucher</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/vouchers/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-voucher">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Diskon</th>
                                <th>Berlaku Sampai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($vouchers as $key => $row): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td>
                                    <!-- Cek gambar ada atau tidak -->
                                    <img alt="image" src="<?= base_url('uploads/vouchers/'.$row['image']) ?>" width="80" class="rounded" onerror="this.src='https://placehold.co/80x50?text=No+Image'">
                                </td>
                                <td>
                                    <span class="badge badge-primary"><?= $row['code'] ?></span>
                                </td>
                                <td><?= $row['name'] ?></td>
                                <td>
                                    Rp <?= number_format($row['discount_nominal'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?= date('d M Y', strtotime($row['valid_until'])) ?>
                                </td>
                                <td>
                                    <?php if($row['is_active'] == 1): ?>
                                        <div class="badge badge-success">Aktif</div>
                                    <?php else: ?>
                                        <div class="badge badge-danger">Non-Aktif</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/vouchers/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus voucher ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
    // Aktifkan DataTables
    $("#table-voucher").dataTable({
        "columnDefs": [
            { "sortable": false, "targets": [1, 7] } // Kolom Gambar & Aksi tidak bisa disortir
        ]
    });
</script>
<?= $this->endSection() ?>
