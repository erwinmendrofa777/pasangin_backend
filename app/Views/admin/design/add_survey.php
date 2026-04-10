<?= $this->extend('layout/app') ?>

<?= $this->section('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?> (Proyek #<?= esc($request_id); ?>)</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Upload Laporan Survey</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        
                        <!-- PERHATIKAN: form action mengarah ke 'saveSurvey' -->
                        <form action="/admin/designrequests/saveSurvey" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="design_request_id" value="<?= esc($request_id); ?>">

                            <div class="card-body">

                                <?php if(session()->has('errors')): ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                        <?php foreach (session('errors') as $error) : ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="title">Judul Laporan</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Laporan Survey Awal" value="<?= old('title'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="note">Catatan (Opsional)</label>
                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Tulis catatan jika ada..."><?= old('note'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="file">File Laporan (PDF, JPG, PNG - Maks 5MB)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file" required>
                                            <label class="custom-file-label" for="file">Pilih file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Upload & Simpan</button>
                                <a href="/admin/designrequests/show/<?= esc($request_id); ?>" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>

