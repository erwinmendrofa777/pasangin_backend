<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    
    <!-- Form Tambah Konsep Baru -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Konsep Baru</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin/price-estimate/concept/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="name">Nama Konsep</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Minimalis, Industrial, Modern" required>
                </div>
                <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                    <span class="ladda-label">Simpan Konsep</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Konsep dan Kualitasnya -->
    <?php foreach ($concepts as $concept) : ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Konsep: <?= $concept['name'] ?></h6>
                <div>
                    <button class="btn btn-sm btn-warning btn-edit-concept" data-id="<?= $concept['id'] ?>" data-name="<?= $concept['name'] ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="<?= site_url('admin/price-estimate/concept/delete/' . $concept['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Anda yakin ingin menghapus konsep ini? Semua kualitas di dalamnya juga akan terhapus.')) { Ladda.create(this).start(); return true; } return false;">
                        <span class="ladda-label"><i class="fas fa-trash"></i> Hapus</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold">Daftar Kualitas</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Label Kualitas</th>
                                <th>Harga Minimum (per m²)</th>
                                <th>Harga Maksimum (per m²)</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($concept['qualities'])) : ?>
                                <?php foreach ($concept['qualities'] as $quality) : ?>
                                    <tr>
                                        <td><?= $quality['label'] ?></td>
                                        <td>Rp <?= number_format($quality['min_price'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($quality['max_price'], 0, ',', '.') ?></td>
                                        <td><?= $quality['description'] ?? '-' ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning btn-edit-quality" 
                                                data-id="<?= $quality['id'] ?>" 
                                                data-label="<?= $quality['label'] ?>" 
                                                data-min-price="<?= $quality['min_price'] ?>" 
                                                data-max-price="<?= $quality['max_price'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= site_url('admin/price-estimate/quality/delete/' . $quality['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Anda yakin ingin menghapus kualitas ini?')) { Ladda.create(this).start(); return true; } return false;">
                                                <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data kualitas untuk konsep ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <hr>

                <h6 class="font-weight-bold mt-4">Tambah Kualitas Baru untuk Konsep "<?= $concept['name'] ?>"</h6>
                <form action="<?= site_url('admin/price-estimate/quality/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="concept_id" value="<?= $concept['id'] ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Label Kualitas</label>
                                <input type="text" name="label" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Harga Minimum</label>
                                <input type="number" name="min_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Harga Maksimum</label>
                                <input type="number" name="max_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" name="description" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info ladda-button" data-style="zoom-in">
                        <span class="ladda-label">Tambah Kualitas</span>
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<!-- Modal Edit Konsep -->
<div class="modal fade" id="editConceptModal" tabindex="-1" role="dialog" aria-labelledby="editConceptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editConceptModalLabel">Edit Konsep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editConceptForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_concept_name">Nama Konsep</label>
                        <input type="text" id="edit_concept_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                        <span class="ladda-label">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kualitas -->
<div class="modal fade" id="editQualityModal" tabindex="-1" role="dialog" aria-labelledby="editQualityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQualityModalLabel">Edit Kualitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editQualityForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_quality_label">Label Kualitas</label>
                        <input type="text" id="edit_quality_label" name="label" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_quality_min_price">Harga Minimum</label>
                        <input type="number" id="edit_quality_min_price" name="min_price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_quality_max_price">Harga Maksimum</label>
                        <input type="number" id="edit_quality_max_price" name="max_price" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                        <span class="ladda-label">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Script untuk modal edit konsep
        $('.btn-edit-concept').on('click', function() {
            // Ambil data dari tombol
            const id = $(this).data('id');
            const name = $(this).data('name');

            // Set nilai pada form di dalam modal
            $('#edit_concept_name').val(name);

            // Set action URL untuk form
            const url = "<?= site_url('admin/price-estimate/concept/update/') ?>" + id;
            $('#editConceptForm').attr('action', url);

            // Tampilkan modal
            $('#editConceptModal').modal('show');
        });

        // Script untuk modal edit kualitas
        $('.btn-edit-quality').on('click', function() {
            // Ambil data dari tombol
            const id = $(this).data('id'); 
            const label = $(this).data('label');
            const minPrice = $(this).data('min-price');
            const maxPrice = $(this).data('max-price');

            // Set nilai pada form di dalam modal
            $('#edit_quality_label').val(label);
            $('#edit_quality_min_price').val(minPrice);
            $('#edit_quality_max_price').val(maxPrice);

            // Set action URL untuk form
            const url = "<?= site_url('admin/price-estimate/quality/update/') ?>" + id;
            $('#editQualityForm').attr('action', url);

            // Tampilkan modal
            $('#editQualityModal').modal('show');
        });
    });

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
