<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Proyek #<?= $request['id'] ?> <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Kelola Proyek: <?= $request['full_name'] ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 mb-3">
        <a href="<?= base_url('admin/design') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
    </div>

        <div class="card">
            <div class="card-header">
                <h4>Progress Proyek</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab"><i class="fas fa-user"></i> Detail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="survey-tab" data-toggle="tab" href="#survey" role="tab"><i class="fas fa-clipboard-check"></i> Survey</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="design-tab" data-toggle="tab" href="#design" role="tab"><i class="fas fa-drafting-compass"></i> Desain</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab"><i class="fas fa-tasks"></i> Target & Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab"><i class="fas fa-wallet"></i> Pembayaran</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- 1. TAB DETAIL -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr><th width="200" class="bg-light">Nama Klien</th><td><?= $request['full_name'] ?></td></tr>
                                    <tr><th class="bg-light">Kontak</th><td><?= $request['phone_number'] ?></td></tr>
                                    <tr><th class="bg-light">Konsep</th><td><?= $request['design_concept'] ?></td></tr>
                                    <tr><th class="bg-light">Luas Tanah</th><td><?= $request['land_area'] ?> m²</td></tr>
                                    <?php 
                                        $badgeColor = 'badge-secondary';
                                        if($request['status'] == 'PENDING') $badgeColor = 'text-white text-bg-warning';
                                        elseif($request['status'] == 'SURVEY_SCHEDULED') $badgeColor = 'badge-info';
                                        elseif($request['status'] == 'PAYMENT_VERIFIED') $badgeColor = 'badge-primary';
                                        elseif($request['status'] == 'COMPLETED') $badgeColor = 'badge-success';
                                        elseif($request['status'] == 'CANCELLED') $badgeColor = 'badge-danger';
                                    ?>
                                    <tr><th class="bg-light">Status Saat Ini</th><td><span class="badge <?= $badgeColor ?>"><?= $request['status'] ?></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 2. TAB SURVEY -->
                    <div class="tab-pane fade" id="survey" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6>Tambah Laporan Survey</h6>
                                <hr>
                                <form action="<?= base_url('admin/design/add-survey/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" class="form-control" required></div>
                                    <div class="form-group"><label>Note</label><textarea name="note" class="form-control"></textarea></div>
                                    <div class="form-group"><label>File</label><input type="file" name="survey_file" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <h6>Riwayat Survey</h6>
                                <hr>
                                <table class="table table-striped">
                                    <thead><tr><th>Tanggal</th><th>Judul</th><th>File</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if(empty($surveys)): ?><tr><td colspan="4" class="text-center">Belum ada survey</td></tr><?php endif; ?>
                                        <?php foreach($surveys as $s): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($s['created_at'])) ?></td>
                                            <td><?= $s['title'] ?></td>
                                            <td><a href="<?= base_url('uploads/survey/'.$s['file']) ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a></td>
                                            <td><a href="<?= base_url('admin/design/delete-survey/'.$s['id']) ?>" class="btn btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 3. TAB DESAIN -->
                    <div class="tab-pane fade" id="design" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6>Upload Hasil Desain</h6>
                                <hr>
                                <form action="<?= base_url('admin/design/add-design-result/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label>Nama Gambar</label><input type="text" name="design_name" class="form-control" required></div>
                                    <div class="form-group"><label>File Desain</label><input type="file" name="design_file" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Upload</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <h6>Galeri Desain</h6>
                                <hr>
                                <div class="row">
                                    <?php if(empty($design_results)): ?><div class="col-12 text-center text-muted">Belum ada desain</div><?php endif; ?>
                                    <?php foreach($design_results as $d): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <img src="<?= base_url('uploads/design_results/'.$d['file']) ?>" class="card-img-top" style="height: 120px; object-fit: cover;">
                                            <div class="card-body p-2 text-center">
                                                <small><?= $d['design_name'] ?></small><br>
                                                <a href="<?= base_url('admin/design/delete-design/'.$d['id']) ?>" class="text-danger small ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                                    <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TAB PROGRESS -->
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6>Atur Target & Progress</h6>
                                        <hr>
                                        <form action="<?= base_url('admin/design/update-progress/' . $request['id']) ?>" method="post">
                                            <div class="form-group">
                                                <label>Target Selesai Proyek</label>
                                                <input type="date" name="target_date" class="form-control" value="<?= $request['target_date'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Progress Pengerjaan (0 - 100%)</label>
                                                <input type="number" name="progress_percent" class="form-control" min="0" max="100" value="<?= $request['progress_percent'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Update Status Utama</label>
                                                <select name="status" class="form-control">
                                                    <option value="PENDING" <?= $request['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                    <option value="SURVEY_SCHEDULED" <?= $request['status'] == 'SURVEY_SCHEDULED' ? 'selected' : '' ?>>JADWAL SURVEY</option>
                                                    <option value="PROCESS" <?= $request['status'] == 'PROCESS' ? 'selected' : '' ?>>DALAM PROSES</option>
                                                    <option value="COMPLETED" <?= $request['status'] == 'COMPLETED' ? 'selected' : '' ?>>SELESAI</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                                <span class="ladda-label">Simpan Perubahan</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <h1 class="display-4 text-primary"><?= $request['progress_percent'] ?>%</h1>
                                <div class="progress mb-3" style="height: 30px; border-radius: 15px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $request['progress_percent'] ?>%;"><?= $request['progress_percent'] ?>%</div>
                                </div>
                                <p>Target: <strong><?= $request['target_date'] ? date('d M Y', strtotime($request['target_date'])) : '-' ?></strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- 5. TAB PEMBAYARAN -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6>Buat Tagihan</h6>
                                <hr>
                                <form action="<?= base_url('admin/design/add-invoice/' . $request['id']) ?>" method="post">
                                    <div class="form-group"><label>Deskripsi</label><input type="text" name="description" class="form-control" required></div>
                                    <div class="form-group"><label>Nominal</label><input type="number" name="amount" class="form-control" required></div>
                                    <div class="form-group"><label>Jatuh Tempo</label><input type="date" name="due_date" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Kirim Tagihan</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <h6>Daftar Tagihan</h6>
                                <hr>
                                <table class="table table-bordered">
                                    <thead><tr><th>Keterangan</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if(empty($invoices)): ?><tr><td colspan="4" class="text-center">Belum ada tagihan</td></tr><?php endif; ?>
                                        <?php foreach($invoices as $inv): ?>
                                        <tr>
                                            <td><?= $inv['description'] ?></td>
                                            <td>Rp <?= number_format($inv['amount'], 0, ',', '.') ?></td>
                                            <td><span class="badge badge-<?= ($inv['payment_status']=='PAID')?'success':'danger' ?>"><?= $inv['payment_status'] ?? 'UNPAID' ?></span></td>
                                            <td><a href="<?= base_url('admin/design/delete-invoice/'.$inv['id']) ?>" class="btn btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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