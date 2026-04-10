<?= $this->section('style') ?>
<!-- CSS Libraries -->
  <link rel="stylesheet" href="<?= base_url('assets/modules/chocolat/dist/css/chocolat.css') ?>">
<?= $this->endSection() ?>

<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Konstruksi <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Proyek Konstruksi <?= $this->endSection() ?>

<?= $this->section('content'); ?>

<style>
    .section-title { font-size: 16px; font-weight: 600; color: #34395e; margin-bottom: 20px; }
    
    .tab-content { 
        padding-top: 20px; 
        min-height: 500px;
        background: #fff;
        padding: 25px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.03);
    }
    .summary-box { 
        background: #f4f6f9; 
        border-left: 5px solid #6777ef; 
        padding: 20px; 
        margin-bottom: 25px; 
        border-radius: 5px;
    }
    .summary-label { 
        font-size: 10px; 
        text-transform: uppercase; 
        font-weight: 700; 
        color: #888; 
        display: block; 
        margin-bottom: 5px; 
    }
    .nav-tabs { border-bottom: none; }
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #888;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        background-color: transparent !important;
        border-bottom: 3px solid #6777ef !important; 
        color: #6777ef !important;
        font-weight: bold;
    }
    /* Style Khusus RAB Excel-Style */
    .table-rab input { border: none; background: transparent; width: 100%; padding: 5px; font-size: 13px; }
    .table-rab input:focus { background: #fff; border: 1px solid #6777ef; outline: none; border-radius: 4px; }
    .table-rab tr:hover { background-color: #fcfcfc; }
    .input-roman { font-weight: bold; text-align: center; color: #6777ef; }
    .row-locked { background-color: #f9f9f9 !important; }
    .row-locked input { color: #999; pointer-events: none; }
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><a href="<?= base_url('admin/construction') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
            <div><span class="badge badge-primary px-3 py-2">Status: <?= strtoupper($construction['status']) ?></span></div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header p-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-left: 20px;">
                    <li class="nav-item"><a class="nav-link active font-weight-bold" data-toggle="tab" href="#detail"><i class="fas fa-user"></i> Detail</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold text-success" data-toggle="tab" href="#pelamar"><i class="fas fa-users"></i> Pelamar</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold text-warning" data-toggle="tab" href="#target"><i class="fas fa-bullseye"></i> Target</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#survey"><i class="fas fa-map-marked-alt"></i> Survey</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#desain"><i class="fas fa-drafting-compass"></i> Desain</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold text-primary" data-toggle="tab" href="#rab"><i class="fas fa-file-invoice-dollar"></i> Kelola RAB</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#payment"><i class="fas fa-wallet"></i> Pembayaran</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#progress"><i class="fas fa-tasks"></i> Progress</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#info-pekerjaan"><i class="fas fa-briefcase"></i> Lowongan</a></li>
                </ul>
            </div>

            <div class="card-body pt-0">
                <div class="tab-content pt-0 pe-1 ps-1" id="myTabContent">
                
                    <!-- ------------ -->
                    <!-- tab 1 detail -->
                    <!-- ------------ -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Update Status Proyek</div>
                                <form action="<?= base_url('admin/construction/update-status') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group">
                                        <select name="status" class="form-control select2">
                                            <?php $st = ['PENDING', 'SURVEY', 'DESIGNING', 'RAB', 'CONSTRUCTION', 'COMPLETED', 'CANCELLED']; 
                                            foreach($st as $s): ?>
                                                <option value="<?= $s ?>" <?= $construction['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Status</span>
                                    </button>
                                </form>
                                <div class="mt-4">
                                    <div class="section-title">Kontak Klien</div>
                                    <h6 class="font-weight-bold"><?= $construction['phone'] ?></h6>
                                    <p class="text-muted small"><?= $construction['email'] ?></p>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $construction['phone']) ?>" target="_blank" class="btn btn-success btn-sm btn-block"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                </div>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Informasi Dasar</div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><strong>Nama Klien:</strong><br><?= $construction['full_name'] ?></div>
                                    <div class="col-md-6 mb-3"><strong>Luas Bangunan:</strong><br><?= $construction['building_area'] ?> m²</div>
                                    <div class="col-12"><strong>Alamat:</strong><br><?= $construction['address'] ?></div>
                                </div>
                                <div class="section-title">Foto Lokasi</div>
                                <div class="col-12 col-sm-6 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="gallery">
                                                <?php if($construction['gambar1']): ?>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($construction['gambar' . $i]): ?>
                                                            <div class="gallery-item" data-image="<?= base_url('uploads/construction/' . $construction['gambar' . $i]) ?>" data-title="Image <?= $i ?>"></div>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-light border text-center text-muted small">Belum ada foto lokasi.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ------------ -->
                    <!-- tab 2 pemalamar -->
                    <!-- ------------ -->
                    <div class="tab-pane fade" id="pelamar" role="tabpanel">
                        <div class="section-title">Daftar Tukang yang Melamar Proyek Ini</div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Tukang</th>
                                        <th>No. HP</th>
                                        <th>Keahlian</th>
                                        <th>Status</th>
                                        <th>Update Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($applicants)): foreach($applicants as $app): ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= esc($app['tukang_name']) ?></td>
                                        <td><?= esc($app['phone']) ?></td>
                                        <td><span class="badge badge-light"><?= esc($app['specialization']) ?></span></td>
                                        <td>
                                            <?php 
                                                $badge = 'secondary';
                                                if($app['status'] == 'Siap Kerja') $badge = 'success';
                                                elseif($app['status'] == 'Ditolak') $badge = 'danger';
                                                elseif($app['status'] == 'Berkas Diproses') $badge = 'info';
                                                else $badge = 'warning';
                                            ?>
                                            <span class="badge badge-<?= $badge ?>"><?= esc($app['status']) ?></span>
                                        </td>
                                        <td>
                                            <form action="<?= base_url('admin/construction/update_applicant_status') ?>" method="post" class="form-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                                <select name="status" class="form-control form-control-sm mr-2">
                                                    <option value="Berkas Diproses" <?= $app['status'] == 'Berkas Diproses' ? 'selected' : '' ?>>Berkas Diproses</option>
                                                    <option value="Proses Test" <?= $app['status'] == 'Proses Test' ? 'selected' : '' ?>>Proses Test</option>
                                                    <option value="Proses Aktivasi" <?= $app['status'] == 'Proses Aktivasi' ? 'selected' : '' ?>>Proses Aktivasi</option>
                                                    <option value="Siap Kerja" <?= $app['status'] == 'Siap Kerja' ? 'selected' : '' ?>>Siap Kerja</option>
                                                    <option value="Ditolak" <?= $app['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in">
                                                    <span class="ladda-label">Update</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada tukang yang melamar di proyek ini</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- --------- -->
                    <!-- tab 3 target -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="target" role="tabpanel">

                        <?= $this->include('admin/construction/target') ?>

                    </div>

                    <!-- ------------ -->
                    <!-- tab 4 survey -->
                    <!-- ------------ -->
                    <div class="tab-pane fade" id="survey" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Riwayat Survey</div>
                                <table class="table table-striped"><thead><tr class="text-center"><th>Tanggal</th><th>Judul</th><th>File</th><th style="width: 40%;">Komentar</th><th>Aksi</th></tr></thead><tbody>
                                    <?php if (!empty($survey_list)): foreach($survey_list as $srv): ?>
                                    <tr class="text-center">
                                        <td><?= date('d/m/Y', strtotime($srv['created_at'])) ?></td>
                                        <td><?= $srv['survey_title'] ?></td>
                                        <td><?php if(!empty($srv['survey_file'])): ?><a href="<?= base_url('uploads/construction/survey/' . $srv['survey_file']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a><?php endif; ?></td>
                                        <?php if($srv['comment'] == null): ?>
                                            <td class="text-center text-muted">belum ada komentar dari client</td>
                                        <?php else: ?>
                                            <td><?= $srv['comment'] ?></td>
                                        <?php endif; ?>
                                        <td><a href="<?= base_url('admin/construction/delete-survey/' . $srv['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody></table>
                            </div>
                        </div>
                                                
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Tambah Survey</div>
                                <form action="<?= base_url('admin/construction/upload-survey') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Judul</label><input type="text" name="survey_title" class="form-control" required></div>
                                    <div class="form-group"><label>Catatan</label><textarea name="survey_notes" class="form-control" rows="4"></textarea></div>
                                    <div class="form-group"><label>File</label><input type="file" name="survey_file" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Survey</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ------------ -->
                    <!-- tab 5 desain -->
                    <!-- ------------ -->
                    <div class="tab-pane fade" id="desain" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Riwayat Desain</div>
                                <table class="table table-striped"><thead><tr class="text-center"><th>Tanggal</th><th>Judul</th><th>File</th><th style="width: 40%;">Komentar</th><th>Aksi</th></tr></thead><tbody>
                                    <?php if (!empty($design_list)): foreach($design_list as $design): ?>
                                    <tr class="text-center">
                                        <td><?= date('d/m/Y', strtotime($design['created_at'])) ?></td>
                                        <td><?= $design['title'] ?></td>
                                        <td><?php if(!empty($design['file'])): ?><a href="<?= base_url('uploads/construction/designs/' . $design['file']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a><?php endif; ?></td>
                                        <?php if($design['comment'] == null): ?>
                                            <td class="text-center text-muted">belum ada komentar dari client</td>
                                        <?php else: ?>
                                            <td><?= $design['comment'] ?></td>
                                        <?php endif; ?>
                                        <td><a href="<?= base_url('admin/construction/delete-design/' . $design['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody></table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Upload Desain</div>
                                <form action="<?= base_url('admin/construction/upload-design') ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="form-group"><label>Judul File</label><input type="text" name="design_title" class="form-control" required></div>
                                    <div class="form-group"><label>File Gambar/PDF</label><input type="file" name="design_2d" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Upload Desain</span>
                                    </button>
                                </form>
                            </div>
                    
                        </div>
                    </div>

                    <!-- --------- -->
                    <!-- tab 6 rab -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="rab" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-primary">Manajemen RAB Proyek</h6>
                            <div>
                                <a class="btn btn-primary btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();" href="<?= base_url('admin/kontrak/cetak-pdf/' . $construction['id']) ?>">
                                    <span class="ladda-label"><i class="fa-solid fa-file-lines"></i> preview kontrak</span>
                                </a>
                                <?php if (!empty($rab_list) && $rab_list[0]['is_locked'] == 0): ?>
                                    <a href="<?= base_url('admin/construction/lock_rab/' . $construction['id']) ?>" class="btn btn-danger btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Kunci RAB kawan? Data tidak bisa diubah lagi!')) { Ladda.create(this).start(); return true; } return false;">
                                        <span class="ladda-label"><i class="fas fa-lock"></i> Kunci RAB</span>
                                    </a>
                                <?php elseif(!empty($rab_list)): ?>
                                    <a href="<?= base_url('admin/construction/unlock_rab/' . $construction['id']) ?>" class="btn btn-warning btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();">
                                        <span class="ladda-label"><i class="fas fa-lock-open"></i> Buka Kunci</span>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-success btn-sm shadow-sm" onclick="addNewRabRow()"><i class="fas fa-plus"></i> Tambah Baris</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-rab">
                                <thead class="bg-light text-center small">
                                    <tr>
                                        <th width="5%">Roman</th>
                                        <th width="15%">Grup Utama</th>
                                        <th width="15%">Sub Grup</th>
                                        <th width="20%">Pekerjaan</th>
                                        <th width="8%">Vol</th>
                                        <th width="7%">Satuan</th>
                                        <th width="12%">Harga (Rp)</th>
                                        <th width="13%">Total (Rp)</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rabBody">
                                    <?php if (!empty($rab_list)): foreach($rab_list as $rab): ?>
                                    <tr data-id="<?= $rab['id'] ?>" class="<?= $rab['is_locked'] ? 'row-locked' : '' ?>">
                                        <td><input type="text" class="input-roman" value="<?= esc($rab['roman_number'] ?? 'I') ?>"></td>
                                        <td><input type="text" class="input-group-name" value="<?= esc($rab['group_name'] ?? 'PEKERJAAN') ?>"></td>
                                        <td><input type="text" class="input-section" value="<?= esc($rab['section_group']) ?>"></td>
                                        <td><input type="text" class="input-task" value="<?= esc($rab['activity_name']) ?>"></td>
                                        <td><input type="float" step="0.01" class="input-vol text-center" value="<?= $rab['volume'] ?>"></td>
                                        <td><input type="text" class="input-unit text-center" value="<?= esc($rab['unit']) ?>"></td>
                                        <td><input type="float" class="input-price text-right" value="<?= (int)$rab['current_unit_price'] ?>"></td>
                                        <td class="text-right font-weight-bold row-total">0</td>
                                        <td class="text-center">
                                            <?php if ($rab['is_locked'] == 1): ?>
                                                <i class="fas fa-lock text-muted"></i>
                                            <?php else: ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-primary btn-xs" onclick="openMaterialModal(<?= $rab['id'] ?>, '<?= esc($rab['activity_name']) ?>')"><i class="fas fa-boxes"></i></button>
                                                    <button class="btn btn-success btn-xs" onclick="saveRabRow(this)"><i class="fas fa-save"></i></button>
                                                    <button class="btn btn-danger btn-xs" onclick="deleteRabRow(this, <?= $rab['id'] ?>)"><i class="fas fa-trash"></i></button>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light font-weight-bold text-primary">
                                        <td colspan="7" class="text-right">ESTIMASI TOTAL</td>
                                        <td class="text-right" id="grandTotalRab">Rp 0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ---------------- -->
                    <!-- tab 7 pembayaran -->
                    <!-- ---------------- -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        
                        <?php  if(!empty($rab_list) && $rab_list[0]['is_locked'] == 1): ?>
                            <div class="row">
                                <div class="col-md-12 pl-md-4">
                                    <div class="section-title">Daftar Tagihan</div>
                                    <table class="table table-hover"><thead><tr><th>Keterangan</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                                        <?php if (!empty($invoice_list)): foreach($invoice_list as $inv): ?>
                                        <tr>
                                            <td><?= $inv['description'] ?></td>
                                            <td class="font-weight-bold text-primary">Rp <?= number_format($inv['amount']) ?></td>
                                            <td><span class="badge <?= $inv['status'] == 'PAID' ? 'badge-success' : 'text-bg-warning text-white' ?>"><?= $inv['status'] ?></span></td>
                                            <td><a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                                    <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada tagihan</td></tr>
                                        <?php endif; ?>`
                                    </tbody></table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="section-title">Nilai kontrak</div>
                                    <table class="table table-hover"><thead><tr><th>Keterangan</th><th>Nominal</th></tr></thead><tbody>
                                        <?php if (!empty($list_tagihan)): foreach($list_tagihan as $key => $inv): ?>
                                        <tr style="cursor: pointer;" onclick="$('#invoice_description').val('<?= addslashes($inv['group_name']) ?>'); $('#invoice_amount').val('<?= $inv['total_price'] ?>'); $('#invoice_amount_visible').val('<?= number_format($inv['total_price'], 0, ',', '.') ?>');" title="Klik untuk buat tagihan">
                                            <td><?= $inv['group_name'] ?></td>
                                            <td class="font-weight-bold text-primary">Rp <?= number_format($inv['total_price']) ?></td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody></table>
                                </div>
                                <div class="col-md-6 border-right">
                                    <div class="section-title">Buat Tagihan</div>
                                    <form action="<?= base_url('admin/construction/create_invoice') ?>" method="post">
                                        <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">
                                        <div class="form-group"><label>Keterangan</label><input type="text" id="invoice_description" name="description" class="form-control" placeholder="Contoh: Termin 1" required></div>
                                        <div class="form-group">
                                            <label>Nominal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                                <input type="text" id="invoice_amount_visible" class="form-control" required onkeyup="formatCurrencyInput(this)">
                                            </div>
                                            <input type="hidden" id="invoice_amount" name="amount" required>
                                        </div>
                                        <div class="form-group"><label>Batas Waktu</label><input type="date" name="due_date" class="form-control" required></div>
                                        <button type="submit" class="btn btn-primary btn-block shadow ladda-button" data-style="zoom-in">
                                            <span class="ladda-label">Kirim Tagihan</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-md-4 border-right">
                                    <div class="section-title">Buat Tagihan</div>
                                    <form action="<?= base_url('admin/construction/create_invoice') ?>" method="post">
                                        <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">
                                        <div class="form-group"><label>Keterangan</label><input type="text" id="invoice_description" name="description" class="form-control" placeholder="Contoh: Termin 1" required></div>
                                        <div class="form-group">
                                            <label>Nominal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                                <input type="text" id="invoice_amount_visible" class="form-control" required onkeyup="formatCurrencyInput(this)">
                                            </div>
                                            <input type="hidden" id="invoice_amount" name="amount" required>
                                        </div>
                                        <div class="form-group"><label>Batas Waktu</label><input type="date" name="due_date" class="form-control" required></div>
                                        <button type="submit" class="btn btn-primary btn-block shadow ladda-button" data-style="zoom-in">
                                            <span class="ladda-label">Kirim Tagihan</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-8 pl-md-4">
                                    <div class="section-title">Daftar Tagihan</div>
                                    <table class="table table-hover"><thead><tr><th>Keterangan</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                                        <?php if (!empty($invoice_list)): foreach($invoice_list as $inv): ?>
                                        <tr>
                                            <td><?= $inv['description'] ?></td>
                                            <td class="font-weight-bold text-primary">Rp <?= number_format($inv['amount']) ?></td>
                                            <td><span class="badge <?= $inv['status'] == 'PAID' ? 'badge-success' : 'text-bg-warning text-white' ?>"><?= $inv['status'] ?></span></td>
                                            <td><a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                                    <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody></table>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- -------------- -->
                    <!-- tab 8 progress -->
                    <!-- -------------- -->
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        
                    </div>

                    <!-- -------------------- -->
                    <!-- tab 9 info pekerjaan -->
                    <!-- -------------------- -->
                    <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="section-title">Data Lowongan (Live di Aplikasi Tukang)</div>
                                <div class="summary-box">
                                    <div class="row">
                                        <div class="col-md-6 border-right">
                                            <span class="summary-label">DETAIL TUGAS PEKERJAAN</span>
                                            <p class="font-weight-600"><?= nl2br($job_info['detail_pekerjaan'] ?? 'Belum ada rincian tugas.') ?></p>
                                            <span class="summary-label mt-3">DETAIL LOKASI (PATOKAN)</span>
                                            <p class="font-weight-600"><?= nl2br($job_info['detail_lokasi'] ?? 'Belum ada patokan lokasi.') ?></p>
                                        </div>
                                        <div class="col-md-6 pl-md-4">
                                            <div class="row">
                                                <div class="col-6 mb-3"><span class="summary-label">MESS TUKANG</span><h6 class="font-weight-bold"><?= $job_info['tempat_tinggal'] ?? '-' ?></h6></div>
                                                <div class="col-6 mb-3"><span class="summary-label">UPAH / HARI</span><h6 class="text-primary font-weight-bold">Rp <?= number_format($job_info['upah_per_hari'] ?? 0, 0, ',', '.') ?></h6></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="section-title">Form Input Detail Proyek Tukang</div>
                                <form action="<?= base_url('admin/construction/update-job-info') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                                    <div class="row">
                                        <div class="col-md-6"><div class="form-group"><label>Rincian Detail Pekerjaan</label><textarea name="detail_pekerjaan" class="form-control" rows="4"><?= $job_info['detail_pekerjaan'] ?? '' ?></textarea></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Patokan Lokasi Proyek</label><textarea name="detail_lokasi" class="form-control" rows="4"><?= $job_info['detail_lokasi'] ?? '' ?></textarea></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Mess</label><select name="tempat_tinggal" class="form-control"><option value="Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Ada') ? 'selected' : '' ?>>Ada</option><option value="Tidak Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Tidak Ada') ? 'selected' : '' ?>>Tidak Ada</option></select></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Tanggal Mulai-Akhir</label><div class="input-group"><input type="date" name="tanggal_mulai" class="form-control" value="<?= $job_info['tanggal_mulai'] ?? '' ?>"><input type="date" name="tanggal_akhir" class="form-control" value="<?= $job_info['tanggal_akhir'] ?? '' ?>"></div></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Upah (Rp)</label><input type="number" name="upah_per_hari" class="form-control" value="<?= $job_info['upah_per_hari'] ?? '' ?>"></div></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Update Info Pekerjaan</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMaterials" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title" id="modalMaterialTitle">Opsi Bahan</h5><button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button></div>
            <div class="modal-body">
                <div id="materialList" class="mb-4"></div>
                <div class="card bg-light p-3 border-0">
                    <h6>Tambahkan Opsi Produk:</h6>
                    <select id="selectProductRab" class="form-control select2" style="width: 100%;">
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach($all_products as $p): ?>
                            <option value="<?= $p['id'] ?>"> <?= esc($p['name']) ?> - Rp <?= number_format($p['price']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-success btn-sm btn-block mt-3" onclick="submitProductToMaterial()">Tambah Bahan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<!-- JS Libraries -->
<script src="<?= base_url('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') ?>"></script>
<script>
    let activeRabId = 0;
    $(document).ready(function() { 
        calculateGrandTotal(); 
        var hash = window.location.hash;
        if (hash) { $('.nav-tabs a[href="' + hash + '"]').tab('show'); }
        $('.nav-tabs a').on('shown.bs.tab', function (e) { window.location.hash = e.target.hash; });
    });

    function calculateGrandTotal() {
        let total = 0; let lastS = "";
        $('.input-vol').each(function() {
            let row = $(this).closest('tr');
            let s = row.find('.input-section').val();
            let v = parseFloat($(this).val()) || 0;
            let p = parseFloat(row.find('.input-price').val()) || 0;
            let sub = v * p;
            row.find('.row-total').text(sub.toLocaleString('id-ID'));
            total += sub;
            if (s === lastS && s !== "") { row.find('.input-section').css('color', 'transparent'); }
            else { row.find('.input-section').css('color', '#34395e').css('font-weight', 'bold'); }
            lastS = s;
        });
        $('#grandTotalRab').text('Rp ' + total.toLocaleString('id-ID'));
    }

    $(document).on('input', '.input-vol, .input-price, .input-section', function() { calculateGrandTotal(); });

    function addNewRabRow() {
        $('#rabBody').append(`<tr data-id="0">
            <td><input type="text" class="input-roman" value="I"></td>
            <td><input type="text" class="input-group-name" value="PEKERJAAN"></td>
            <td><input type="text" class="input-section" placeholder="Sub..."></td>
            <td><input type="text" class="input-task" placeholder="Pekerjaan..."></td>
            <td><input type="number" class="input-vol text-center" value="1"></td>
            <td><input type="text" class="input-unit text-center" value="unit"></td>
            <td><input type="number" class="input-price text-right" value="0"></td>
            <td class="text-right row-total">0</td>
            <td class="text-center">
                <div class="btn-group">
                    <button class="btn btn-success btn-xs" onclick="saveRabRow(this)"><i class="fas fa-save"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); calculateGrandTotal();"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>`);
    }

    function saveRabRow(btn) {
        const row = $(btn).closest('tr');
        if (row.hasClass('row-locked')) { alert('Data terkunci kawan!'); return; }

        const data = {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            id: row.attr('data-id'),
            construction_id: <?= $construction['id'] ?>,
            roman_number: row.find('.input-roman').val(),
            group_name: row.find('.input-group-name').val(),
            section_group: row.find('.input-section').val(),
            task_name: row.find('.input-task').val(),
            volume: row.find('.input-vol').val(),
            unit: row.find('.input-unit').val(),
            price: row.find('.input-price').val()
        };

        $.post('<?= base_url('admin/construction/save_rab_row') ?>', data, function(res) {
            if(res.status) { 
                row.attr('data-id', res.id); 
                alert('👍 ' + res.message); 
                // Opsional: location.reload(); jika kawan ingin grup lsg ter-update secara visual
            } else { alert('❌ ' + res.message); }
        }).fail(function(xhr) { alert('Gagal kawan, cek console!'); });
    }

    function deleteRabRow(btn, id) {
        if(confirm('Hapus baris ini?')) {
            $.get('<?= base_url('admin/construction/delete_rab_row') ?>/' + id, function(res) {
                if(res.status) { $(btn).closest('tr').remove(); calculateGrandTotal(); }
                else { alert(res.message); }
            });
        }
    }

    function updateTargetStatus(element, targetId, newStatus, currentStatus) {
        if (newStatus === currentStatus) {
         return;
        }
        if (confirm(`Anda yakin ingin mengubah status target menjadi "${newStatus}"?`)) {
            // Activate Ladda on the dropdown-toggle button
            var toggleBtn = $(element).closest('.btn-group').find('.dropdown-toggle.ladda-button')[0];
            if (toggleBtn) {
                var l = Ladda.create(toggleBtn);
                l.start();
            }
            
            // Buat form tersembunyi secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= base_url('admin/construction/update_target_status') ?>/${targetId}/${newStatus}`;

            // Tambahkan token CSRF untuk keamanan
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit(); // Kirim form
        }
    }
    
    function openMaterialModal(id, title) {
        activeRabId = id; $('#modalMaterialTitle').text('Bahan: ' + title); $('#modalMaterials').modal('show');
        $.get('<?= base_url('admin/construction/get_rab_materials') ?>/' + id, function(data) {
            let h = '<div class="list-group shadow-sm">';
            data.forEach(item => {
                h += `<div class="list-group-item d-flex justify-content-between">
                    <span>${item.material_name} (Rp ${parseInt(item.price).toLocaleString('id-ID')})</span>
                    <button class="btn btn-link text-danger p-0" onclick="deleteMaterial(${item.id})"><i class="fas fa-trash"></i></button>
                </div>`;
            });
            $('#materialList').html(h + (data.length ? '' : '<div class="text-center text-muted">Belum ada bahan</div>') + '</div>');
        });
    }

    function submitProductToMaterial() {
        const pId = $('#selectProductRab').val();
        if(!pId) return alert('Pilih produk kawan!');
        $.post('<?= base_url('admin/construction/add_rab_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rab_id: activeRabId, product_id: pId
        }, function(res) { 
            if(res.status) { openMaterialModal(activeRabId, ''); }
            else { alert(res.message); }
        });
    }

    function deleteMaterial(id) {
        if(confirm('Hapus bahan?')) {
            $.get('<?= base_url('admin/construction/delete_rab_material') ?>/' + id, function() { openMaterialModal(activeRabId, ''); });
        }
    }

    function formatCurrencyInput(input) {
        let number_string = input.value.replace(/[^,\d]/g, '').toString();
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        input.value = rupiah;
        
        document.getElementById('invoice_amount').value = number_string.replace(/,/g, '.');
    }
</script>

<?= $this->endSection(); ?>

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