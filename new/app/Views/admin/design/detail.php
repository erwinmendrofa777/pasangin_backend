<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Proyek #<?= $request['id'] ?> <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Kelola Proyek: <?= $request['full_name'] ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 mb-3">
        <a href="<?= base_url('admin/design') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Progress Proyek</h4>
            </div>
            <div class="card-body">
                <!-- MENU TABULASI -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="true">
                            <i class="fas fa-user"></i> Detail Pengajuan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="survey-tab" data-toggle="tab" href="#survey" role="tab" aria-controls="survey" aria-selected="false">
                            <i class="fas fa-clipboard-check"></i> Hasil Survey
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="design-tab" data-toggle="tab" href="#design" role="tab" aria-controls="design" aria-selected="false">
                            <i class="fas fa-drafting-compass"></i> Hasil Desain
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">
                            <i class="fas fa-wallet"></i> Pembayaran & Tagihan
                        </a>
                    </li>
                </ul>

                <!-- ISI KONTEN TAB -->
                <div class="tab-content" id="myTabContent">
                    
                    <!-- 1. TAB DETAIL (Info User & Status) -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                        <div class="row mt-4">
                            <div class="col-md-7">
                                <table class="table table-bordered">
                                    <tr><th width="150" class="bg-light">Nama Klien</th><td><?= $request['full_name'] ?></td></tr>
                                    <tr><th class="bg-light">Kontak</th><td><?= $request['phone_number'] ?> <a href="https://wa.me/62<?= substr($request['phone_number'], 1) ?>" target="_blank" class="badge badge-success ml-2"><i class="fab fa-whatsapp"></i> WA</a></td></tr>
                                    <tr><th class="bg-light">Lokasi</th><td><?= $request['location_address'] ?></td></tr>
                                    <tr><th class="bg-light">Peta</th><td><a href="https://maps.google.com/?q=<?= $request['latitude'] ?>,<?= $request['longitude'] ?>" target="_blank" class="btn btn-sm btn-primary">Lihat Maps</a></td></tr>
                                    <tr><th class="bg-light">Konsep</th><td><?= $request['design_concept'] ?></td></tr>
                                    <tr><th class="bg-light">Luas Tanah</th><td><?= $request['land_area'] ?> m²</td></tr>
                                </table>
                            </div>
                            <div class="col-md-5">
                                <div class="card card-warning">
                                    <div class="card-header"><h4>Update Status Utama</h4></div>
                                    <div class="card-body">
                                        <form action="<?= base_url('admin/design/update-status/' . $request['id']) ?>" method="post">
                                            <div class="form-group">
                                                <label>Status Pesanan</label>
                                                <select name="status" class="form-control selectric">
                                                    <option value="PENDING" <?= $request['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                    <option value="SURVEY_SCHEDULED" <?= $request['status'] == 'SURVEY_SCHEDULED' ? 'selected' : '' ?>>JADWAL SURVEY</option>
                                                    <option value="PAYMENT_VERIFIED" <?= $request['status'] == 'PAYMENT_VERIFIED' ? 'selected' : '' ?>>PEMBAYARAN OKE</option>
                                                    <option value="COMPLETED" <?= $request['status'] == 'COMPLETED' ? 'selected' : '' ?>>SELESAI</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-warning btn-block">Simpan Status</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. TAB SURVEY (Input & List Hasil Survey) -->
                    <div class="tab-pane fade" id="survey" role="tabpanel" aria-labelledby="survey-tab">
                        <div class="row mt-4">
                            <!-- Form Input Survey -->
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6><i class="fas fa-plus-circle"></i> Tambah Laporan Survey</h6>
                                        <hr>
                                        <form action="<?= base_url('admin/design/add-survey/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Judul Laporan</label>
                                                <input type="text" name="title" class="form-control" placeholder="Contoh: Pengukuran Tanah" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan Surveyor</label>
                                                <textarea name="note" class="form-control" style="height: 100px;"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Lampiran File/Foto</label>
                                                <input type="file" name="survey_file" class="form-control">
                                                <small class="text-muted">PDF atau JPG/PNG</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block">Simpan Laporan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- List Data Survey -->
                            <div class="col-md-8">
                                <h6>Riwayat Survey Lapangan</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Judul</th>
                                                <th>Catatan</th>
                                                <th>File</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Nanti kita loop data dari database disini -->
                                            <?php if(empty($surveys)): ?>
                                                <tr><td colspan="5" class="text-center text-muted">Belum ada data survey</td></tr>
                                            <?php else: ?>
                                                <?php foreach($surveys as $s): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($s['created_at'])) ?></td>
                                                    <td><?= $s['title'] ?></td>
                                                    <td><?= $s['note'] ?></td>
                                                    <td>
                                                        <?php if($s['file']): ?>
                                                            <a href="<?= base_url('uploads/survey/'.$s['file']) ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                                        <?php else: ?> - <?php endif; ?>
                                                    </td>
                                                    <td><a href="<?= base_url('admin/design/delete-survey/'.$s['id']) ?>" class="text-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. TAB HASIL DESAIN (Upload Gambar Kerja/3D) -->
                    <div class="tab-pane fade" id="design" role="tabpanel" aria-labelledby="design-tab">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6><i class="fas fa-upload"></i> Upload Hasil Desain</h6>
                                        <hr>
                                        <form action="<?= base_url('admin/design/add-design-result/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Nama Gambar/File</label>
                                                <input type="text" name="design_name" class="form-control" placeholder="Cth: Denah Lantai 1" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Upload File</label>
                                                <input type="file" name="design_file" class="form-control" required>
                                                <small class="text-muted">Format Gambar (JPG/PNG) atau PDF</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block">Upload Desain</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6>Galeri Desain</h6>
                                <div class="row">
                                    <?php if(empty($design_results)): ?>
                                        <div class="col-12 text-center text-muted">Belum ada desain diupload</div>
                                    <?php else: ?>
                                        <?php foreach($design_results as $d): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card border">
                                                <img src="<?= base_url('uploads/design_results/'.$d['file']) ?>" class="card-img-top" alt="Desain" style="height: 150px; object-fit: cover;">
                                                <div class="card-body p-2 text-center">
                                                    <h6 class="mb-1"><?= $d['design_name'] ?></h6>
                                                    <a href="<?= base_url('admin/design/delete-design/'.$d['id']) ?>" class="text-danger small" onclick="return confirm('Hapus?')">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TAB PEMBAYARAN (Tagihan) -->
                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6><i class="fas fa-file-invoice-dollar"></i> Buat Tagihan Baru</h6>
                                        <hr>
                                        <!-- Biaya Survey Otomatis -->
                                        <div class="alert alert-light border">
                                            <small>Biaya Survey:</small><br>
                                            <strong>Rp <?= number_format($request['survey_fee'] - $request['discount_amount'], 0, ',', '.') ?></strong>
                                            <br>
                                            <small class="text-muted">(Sudah termasuk diskon)</small>
                                        </div>

                                        <!-- ======================================================= -->
<!-- ======================================================= -->
                                    <!-- === FORM INI DIPERBAIKI TOTAL AGAR MENGIRIM DATA BENAR === -->
                                    <!-- ======================================================= -->
                                    <form action="<?= base_url('admin/design/add-invoice/' . $request['id']) ?>" method="post">
                                        
                                        <?= csrf_field() // WAJIB ADA untuk keamanan ?>

                                        <!-- INI BAGIAN PALING PENTING YANG SEBELUMNYA HILANG -->
                                        <input type="hidden" name="design_request_id" value="<?= $request['id'] ?>">

                                        <div class="form-group">
                                            <label>Keterangan Tagihan</label>
                                            <input type="text" name="description" class="form-control" placeholder="Cth: Termin 1 (DP)" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nominal (Rp)</label>
                                            <input type="number" name="amount" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jatuh Tempo</label>
                                            <input type="date" name="due_date" class="form-control" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block">Kirim Tagihan</button>
                                    </form>
                                </div></div>
                            </div>
                            <!-- ======================================================= -->
<!-- === KODE BARU YANG SUDAH DIPERBAIKI UNTUK WEB ADMIN === -->
<!-- ======================================================= -->
<div class="col-md-8">
    <h6>Daftar Tagihan & Status Bayar</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Bukti Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($invoices)): ?>
                    <tr><td colspan="6" class="text-center">Belum ada tagihan dibuat</td></tr>
                <?php else: ?>
                    <?php foreach($invoices as $inv): ?>
                    <tr>
                        <td><?= $inv['description'] ?></td>
                        <td>Rp <?= number_format($inv['amount'], 0, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($inv['due_date'])) ?></td>
                        <td>
                            <?php
                            // ======================================================
                            // === INI DIA PERBAIKAN PALING KRITIS DAN PALING FINAL ===
                            // ======================================================
                            // Kita sekarang membaca kolom 'payment_status', bukan 'status'
                            ?>
                            <?php if(isset($inv['payment_status']) && $inv['payment_status'] == 'PAID'): ?>
                                <span class="badge badge-success">Lunas</span>
                            <?php elseif($inv['status'] == 'PENDING'): // Anda bisa biarkan ini jika masih ada alur manual ?>
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                            <?php else: // Semua status lain dianggap Belum Bayar ?>
                                <span class="badge badge-danger">Belum Bayar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!empty($inv['proof_file'])): ?>
                                <a href="<?= base_url('uploads/payments/'.$inv['proof_file']) ?>" target="_blank" class="btn btn-sm btn-info">Cek Bukti</a>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Tombol Verifikasi hanya muncul jika status PENDING (dari alur manual) -->
                            <?php if($inv['status'] == 'PENDING'): ?>
                                <a href="<?= base_url('admin/design/verify-payment/'.$inv['id']) ?>" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('admin/design/delete-invoice/'.$inv['id']) ?>" class="text-danger ml-2" onclick="return confirm('Hapus tagihan?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

                        </div>
                    </div>

                </div> <!-- End Tab Content -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
