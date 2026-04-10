<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">

        <div class="card shadow">
            <div class="card-header">
                <h4>Daftar Syarat & Ketentuan</h4>
                <div class="card-header-action d-flex gap-2">
                    <a href="<?= base_url('admin/syarat_ketentuan/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Baru</a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md table-hover" id="table-1">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" style="width: 25%;">Judul</th>
                                <th class="text-center" style="width: 60%;">Deskripsi</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            <?php foreach($data as $key => $row): ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $row['title'] ?></td>
                                <td>
                                    <!-- Potong teks deskripsi biar tabel gak kepanjangan -->
                                    <?= $row['description'] ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/syarat_ketentuan/edit/'.$row['id']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="<?= base_url('admin/syarat_ketentuan/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
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