<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Permohonan Pembangunan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Daftar Proyek Pembangunan <?= $this->endSection() ?><?= $this->section('content') ?>
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
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Data Proyek Masuk</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-construction">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Detail Lokasi</th>
                                <th>Tgl Survey</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($projects as $key => $row): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <span class="font-weight-bold"><?= $row['full_name'] ?></span>
                                    <div class="text-small text-muted"><?= $row['phone'] ?></div>
                                </td>
                                <td>
                                    <span class="font-weight-bold">Luas Tanah: <?= $row['land_area'] ?> m²</span><br>
                                    <small class="text-muted text-wrap" style="display:block; max-width: 250px;">
                                        <?= substr($row['address'], 0, 50) ?>...
                                    </small>
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt text-muted"></i> <?= date('d M Y', strtotime($row['survey_date'])) ?>
                                </td>
                                <td>
                                    <?php 
                                        $badgeColor = 'secondary';
                                        $statusLabel = $row['status'];

                                        // Mapping Warna Status
                                        if($row['status'] == 'PENDING') {
                                            $badgeColor = 'warning';
                                            $statusLabel = 'Menunggu';
                                        }
                                        elseif($row['status'] == 'SURVEY') {
                                            $badgeColor = 'info';
                                            $statusLabel = 'Tahap Survey';
                                        }
                                        elseif($row['status'] == 'DESIGNING') {
                                            $badgeColor = 'primary';
                                            $statusLabel = 'Proses Desain';
                                        }
                                        elseif($row['status'] == 'RAB') {
                                            $badgeColor = 'dark';
                                            $statusLabel = 'Penyusunan RAB';
                                        }
                                        elseif($row['status'] == 'CONSTRUCTION') {
                                            $badgeColor = 'primary';
                                            $statusLabel = 'Konstruksi';
                                        }
                                        elseif($row['status'] == 'COMPLETED') {
                                            $badgeColor = 'success';
                                            $statusLabel = 'Selesai';
                                        }
                                        elseif($row['status'] == 'CANCELLED') {
                                            $badgeColor = 'danger';
                                            $statusLabel = 'Batal';
                                        }
                                    ?>
                                    <div class="badge badge-<?= $badgeColor ?>"><?= $statusLabel ?></div>
                                </td>
                                <td>
                                    <!-- Tombol Detail -->
                                    <a href="<?= base_url('admin/construction/detail/'.$row['id']) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Kelola Proyek"><i class="fas fa-tools"></i> Kelola</a>
                                    
                                    <!-- Tombol Hapus (Optional) -->
                                    <!-- <a href="<?= base_url('admin/construction/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash"></i></a> -->
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
    // Aktifkan Datatable
    $("#table-construction").dataTable();
</script>
<?= $this->endSection() ?>
