<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Permohonan Desain <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Daftar Permohonan Desain <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- Notifikasi -->
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
                <h4>Data Permohonan Masuk</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Info Survey</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($requests as $key => $row): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="font-weight-bold"><?= $row['full_name'] ?></span>
                                    <div class="text-small text-muted"><?= $row['phone_number'] ?></div>
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt text-muted"></i> <?= date('d M Y', strtotime($row['survey_date'])) ?><br>
                                    <small class="text-primary"><?= $row['design_concept'] ?></small>
                                </td>
                                <td>
                                    Rp <?= number_format($row['total_payment'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php 
                                        $badgeColor = 'secondary';
                                        if($row['status'] == 'PENDING') $badgeColor = 'warning';
                                        elseif($row['status'] == 'SURVEY_SCHEDULED') $badgeColor = 'info';
                                        elseif($row['status'] == 'PAYMENT_VERIFIED') $badgeColor = 'primary';
                                        elseif($row['status'] == 'COMPLETED') $badgeColor = 'success';
                                        elseif($row['status'] == 'CANCELLED') $badgeColor = 'danger';
                                    ?>
                                    <div class="badge badge-<?= $badgeColor ?>"><?= $row['status'] ?></div>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/design/show/'.$row['id']) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    <a href="<?= base_url('admin/design/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash"></i></a>
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
    $("#table-1").dataTable();
</script>
<?= $this->endSection() ?>
