<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Kelola Tips <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Tips & Tricks <?= $this->endSection() ?>

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
                <h4>Daftar Artikel Tips</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/tips/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Judul & Target</th>
                                <th>Deskripsi Singkat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tips as $key => $row): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <img src="<?= base_url('uploads/tips/'.$row['image']) ?>" width="80" class="rounded">
                                </td>
                                <td>
                                    <span class="font-weight-bold"><?= $row['title'] ?></span><br>
                                    <?php if($row['target_app'] == 'client'): ?>
                                        <div class="badge badge-info mt-1">Client</div>
                                    <?php else: ?>
                                        <div class="badge badge-warning mt-1">Tukang</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Potong teks deskripsi biar tabel gak kepanjangan -->
                                    <?= substr(strip_tags($row['content']), 0, 50) ?>...
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/tips/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
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
