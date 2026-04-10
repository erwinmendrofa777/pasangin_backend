<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Banner
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Banner Iklan
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
                <h4>Daftar Banner</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/banner/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Target App</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($banners as $key => $row): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td>
                                    <img alt="image" src="<?= base_url('uploads/banners/'.$row['image']) ?>" width="100" class="rounded" data-toggle="tooltip" title="<?= $row['title'] ?>">
                                </td>
                                <td><?= $row['title'] ?: '-' ?></td>
                                <td>
                                    <?php if($row['target_app'] == 'client'): ?>
                                        <div class="badge badge-info">Client App</div>
                                    <?php else: ?>
                                        <div class="badge badge-warning">Tukang App</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="badge badge-success">Active</div>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/banner/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus banner ini?')">
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
    // Aktifkan DataTables Stisla
    $("#table-1").dataTable({
        "columnDefs": [
            { "sortable": false, "targets": [1, 5] }
        ]
    });
</script>
<?= $this->endSection() ?>
