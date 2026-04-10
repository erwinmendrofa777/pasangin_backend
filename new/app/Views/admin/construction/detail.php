<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Konstruksi <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Proyek Konstruksi <?= $this->endSection() ?>

<?= $this->section('content'); ?>

<style>
    .section-title { font-size: 16px; font-weight: 600; color: #34395e; margin-bottom: 20px; }
    .file-preview-card { border: 1px dashed #ddd; padding: 20px; text-align: center; border-radius: 5px; background: #f9f9f9; }
    .tab-content { padding-top: 20px; }
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><a href="<?= base_url('admin/construction') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a></div>
            <div><span class="badge badge-primary px-3 py-2" style="font-size: 14px;">Status: <?= strtoupper($construction['status']) ?></span></div>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><?= session()->getFlashdata('success'); ?></div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><?= session()->getFlashdata('error'); ?></div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 20px; padding-top: 10px;">
                    <li class="nav-item"><a class="nav-link active font-weight-bold" id="detail-tab" data-toggle="tab" href="#detail" role="tab"><i class="fas fa-user"></i> Detail</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" id="survey-tab" data-toggle="tab" href="#survey" role="tab"><i class="fas fa-map-marked-alt"></i> Survey</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" id="desain-tab" data-toggle="tab" href="#desain" role="tab"><i class="fas fa-drafting-compass"></i> Desain</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" id="rab-tab" data-toggle="tab" href="#rab" role="tab"><i class="fas fa-file-invoice-dollar"></i> RAB</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" id="payment-tab" data-toggle="tab" href="#payment" role="tab"><i class="fas fa-wallet"></i> Pembayaran</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" id="progress-tab" data-toggle="tab" href="#progress" role="tab"><i class="fas fa-tasks"></i> Progress</a></li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    
                    <!-- TAB 1: DETAIL -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Update Status</div>
                                <form action="<?= base_url('admin/construction/update-status') ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group">
                                        <select name="status" class="form-control select2">
                                            <option value="Pending" <?= $construction['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Surveyed" <?= $construction['status'] == 'Surveyed' ? 'selected' : '' ?>>Surveyed</option>
                                            <option value="Design" <?= $construction['status'] == 'Design' ? 'selected' : '' ?>>Design</option>
                                            <option value="RAB" <?= $construction['status'] == 'RAB' ? 'selected' : '' ?>>RAB</option>
                                            <option value="Construction" <?= $construction['status'] == 'Construction' ? 'selected' : '' ?>>Construction</option>
                                            <option value="Done" <?= $construction['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                </form>
                                <div class="mt-4">
                                    <div class="section-title">Kontak</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">HP: <span class="font-weight-bold"><?= $construction['phone'] ?></span></li>
                                        <li class="list-group-item px-0"><a href="https://wa.me/<?= $construction['phone'] ?>" target="_blank" class="btn btn-success btn-sm btn-block"><i class="fab fa-whatsapp"></i> Chat WhatsApp</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Info Proyek</div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><strong>Nama:</strong> <?= $construction['full_name'] ?></div>
                                    <div class="col-md-6 mb-3"><strong>Tgl Survey:</strong> <?= date('d F Y', strtotime($construction['survey_date'])) ?></div>
                                    <div class="col-md-6 mb-3"><strong>Luas Tanah:</strong> <?= $construction['land_area'] ?> m²</div>
                                    <div class="col-md-6 mb-3"><strong>Luas Bangunan:</strong> <?= $construction['building_area'] ?> m²</div>
                                    <div class="col-12 mb-3"><strong>Alamat:</strong> <?= $construction['address'] ?></div>
                                </div>
                                <div class="section-title mt-3">Foto Lokasi</div>
                                <?php if(!empty($construction['location_photo'])): ?>
                                    <img src="<?= base_url('uploads/construction/' . $construction['location_photo']) ?>" class="img-fluid rounded border" style="max-height: 250px;">
                                <?php else: ?>
                                    <div class="alert alert-secondary">Tidak ada foto lokasi.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: SURVEY -->
                    <div class="tab-pane fade" id="survey" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Tambah Laporan Survey</div>
                                <form action="<?= base_url('admin/construction/upload-survey') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Judul</label><input type="text" name="survey_title" class="form-control" required></div>
                                    <div class="form-group"><label>Catatan</label><textarea name="survey_notes" class="form-control" rows="4"></textarea></div>
                                    <div class="form-group"><label>File</label><input type="file" name="survey_file" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Riwayat Survey</div>
                                <?php if (!empty($survey_list)): ?>
                                    <table class="table table-striped"><thead><tr><th>Tanggal</th><th>Judul</th><th>File</th><th>Aksi</th></tr></thead><tbody>
                                        <?php foreach($survey_list as $srv): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($srv['created_at'])) ?></td>
                                            <td><?= $srv['survey_title'] ?></td>
                                            <td><?php if(!empty($srv['survey_file'])): ?><a href="<?= base_url('uploads/construction/survey/' . $srv['survey_file']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a><?php endif; ?></td>
                                            <td><a href="<?= base_url('admin/construction/delete-survey/' . $srv['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody></table>
                                <?php else: ?>
                                    <div class="file-preview-card"><p>Belum ada riwayat survey.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: DESAIN -->
                    <div class="tab-pane fade" id="desain" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Upload Desain</div>
                                <form action="<?= base_url('admin/construction/upload-design') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Nama File</label><input type="text" name="design_title" class="form-control" required></div>
                                    <div class="form-group"><label>File</label><input type="file" name="design_2d" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block">Upload</button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Galeri Desain</div>
                                <?php if (!empty($design_list)): ?>
                                    <div class="row">
                                        <?php foreach($design_list as $design): ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card border shadow-sm">
                                                <div class="card-body p-2 text-center">
                                                    <a href="<?= base_url('uploads/construction/designs/' . $design['file']) ?>" target="_blank">Lihat File</a>
                                                    <h6 class="mt-2"><?= $design['title'] ?></h6>
                                                    <a href="<?= base_url('admin/construction/delete-design/' . $design['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger btn-block mt-2" onclick="return confirm('Hapus?')">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="file-preview-card"><p>Belum ada desain.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: RAB -->
                    <div class="tab-pane fade" id="rab" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Buat RAB</div>
                                <form action="<?= base_url('admin/construction/upload-rab') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Total (Rp)</label><input type="number" name="rab_total" class="form-control" value="<?= $construction['rab_total'] ?>"></div>
                                    <div class="form-group"><label>File PDF</label><input type="file" name="rab_file" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Status RAB</div>
                                <h4>Rp <?= number_format($construction['rab_total'], 0, ',', '.') ?></h4>
                                <?php if (!empty($construction['rab_file'])): ?>
                                    <a href="<?= base_url('uploads/construction/rab/' . $construction['rab_file']) ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Download PDF</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: PEMBAYARAN (UNDERSCORE FIX) -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Buat Tagihan (Midtrans)</div>
                                <form action="<?= base_url('admin/construction/create_invoice') ?>" method="post">
                                    <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Keterangan</label><input type="text" name="description" class="form-control" required></div>
                                    <div class="form-group"><label>Nominal (Rp)</label><input type="number" name="amount" class="form-control" required></div>
                                    <div class="form-group"><label>Jatuh Tempo</label><input type="date" name="due_date" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block">Kirim Tagihan</button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Daftar Tagihan</div>
                                <table class="table table-hover"><thead><tr><th>Ket</th><th>Nominal</th><th>Status</th><th>Link</th><th>Aksi</th></tr></thead><tbody>
                                    <?php if (!empty($invoice_list)): foreach($invoice_list as $inv): ?>
                                    <tr>
                                        <td><?= $inv['description'] ?></td>
                                        <td><?= number_format($inv['amount']) ?></td>
                                        <td><?= $inv['status'] ?></td>
                                        <td><?php if($inv['status'] != 'PAID'): ?><a href="<?= $inv['payment_url'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Pay</a><?php endif; ?></td>
                                        <td><a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody></table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: PROGRESS -->
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Update Progress</div>
                                <form action="<?= base_url('admin/construction/add-progress') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Minggu Ke</label><input type="number" name="week_number" class="form-control" required></div>
                                    <div class="form-group"><label>Persen (%)</label><input type="number" name="percentage" class="form-control" required></div>
                                    <div class="form-group"><label>Foto</label><input type="file" name="photo" class="form-control" required></div>
                                    <div class="form-group"><label>Ket</label><textarea name="description" class="form-control" required></textarea></div>
                                    <button type="submit" class="btn btn-success btn-block">Simpan</button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Riwayat Progress</div>
                                <table class="table table-striped"><thead><tr><th>Mgg</th><th>Ket</th><th>Foto</th><th>%</th><th>Aksi</th></tr></thead><tbody>
                                    <?php if(!empty($progress_list)): foreach($progress_list as $row): ?>
                                    <tr>
                                        <td><?= $row['week_number'] ?></td>
                                        <td><?= $row['description'] ?></td>
                                        <td><?php if($row['photo_url']): ?><a href="<?= base_url('uploads/construction/progress/'.$row['photo_url']) ?>" target="_blank">Lihat</a><?php endif; ?></td>
                                        <td><?= $row['percentage'] ?>%</td>
                                        <td><a href="<?= base_url('admin/construction/delete-progress/' . $row['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody></table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var hash = window.location.hash;
        if (hash) { $('.nav-tabs a[href="' + hash + '"]').tab('show'); }
        $('.nav-tabs a').on('shown.bs.tab', function (e) { window.location.hash = e.target.hash; });
    });
</script>
<?= $this->endSection(); ?>
