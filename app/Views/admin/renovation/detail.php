<?= $this->section('style') ?>
<!-- CSS Libraries -->
  <link rel="stylesheet" href="<?= base_url('assets/modules/chocolat/dist/css/chocolat.css') ?>">
<?= $this->endSection() ?>

<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Renovasi - <?= $project['full_name'] ?> <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Proyek Renovasi: <?= $project['full_name'] ?> <?= $this->endSection() ?>

<?= $this->section('content'); ?>

<style>
    .section-title { font-size: 16px; font-weight: 600; color: #34395e; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .tab-content { padding: 25px; background: #fff; border-radius: 0 0 10px 10px; min-height: 500px; box-shadow: 0 4px 8px rgba(0,0,0,0.03); }
    .summary-box { background: #f4f6f9; border-left: 5px solid #6777ef; padding: 20px; margin-bottom: 25px; border-radius: 5px; }
    .summary-label { font-size: 10px; text-transform: uppercase; font-weight: 700; color: #888; display: block; margin-bottom: 5px; }
    .nav-tabs .nav-link { border: none; border-bottom: 3px solid transparent; color: #888; padding: 12px 20px; font-weight: bold; }
    .nav-tabs .nav-link.active { background: transparent !important; border-bottom: 3px solid #6777ef !important; color: #6777ef !important; }

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
            <a href="<?= base_url('admin/renovation') ?>" class="btn btn-secondary btn-sm shadow-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            <span class="badge badge-primary px-3 py-2" style="font-size: 14px;">STATUS: <?= strtoupper($project['status']) ?></span>
        </div>

        <div class="card">
            <div class="card-header p-0 pb-0 mb-0">
                <ul class="nav nav-tabs" id="renovationTab" role="tablist" style="padding-left: 20px; padding-top: 10px;">
                    <li class="nav-item"><a class="nav-link font-weight-bold active" data-toggle="tab" href="#detail" role="tab"><i class="fas fa-info-circle"></i> Detail</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold text-warning" data-toggle="tab" href="#target"><i class="fas fa-bullseye"></i> Target</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#survey" role="tab"><i class="fas fa-map-marked-alt"></i> Survey</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#desain" role="tab"><i class="fas fa-drafting-compass"></i> Desain</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#rab" role="tab"><i class="fas fa-file-invoice-dollar"></i> RAB</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#payment" role="tab"><i class="fas fa-wallet"></i> Pembayaran</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#progress" role="tab"><i class="fas fa-tasks"></i> Progress</a></li>
                    <li class="nav-item"><a class="nav-link font-weight-bold" data-toggle="tab" href="#info-pekerjaan" role="tab"><i class="fas fa-briefcase"></i> Info Pekerjaan</a></li>
                </ul>
            </div>

            <div class="card-body pt-0 mt-0">
                <div class="tab-content pt-0 mt-0" id="renovationTabContent">
                    
                    <!-- ------------ -->
                    <!-- tab 1 detail -->
                    <!-- ------------ -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Update Status Renovasi</div>
                                <form action="<?= base_url('admin/renovation/update_status/' . $project['id']) ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $project['id'] ?>">
                                    <div class="form-group">
                                        <select name="status" class="form-control select2">
                                            <?php foreach(['PENDING', 'SURVEY', 'DESIGN', 'RAB', 'DEAL', 'PROGRESS', 'DONE','CANCEL'] as $st) : ?>
                                                <option value="<?= $st ?>" <?= $project['status'] == $st ? 'selected' : '' ?>><?= $st ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block shadow ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Status</span>
                                    </button>
                                </form>
                                <div class="mt-4">
                                    <div class="section-title">Kontak Klien</div>
                                    <h6 class="font-weight-bold"><?= $project['phone'] ?></h6>
                                    <p class="text-muted small"><?= $project['email'] ?></p>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $project['phone']) ?>" target="_blank" class="btn btn-success btn-sm btn-block"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                </div>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Informasi Renovasi (<?= $project['renovation_type'] ?>)</div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><span class="summary-label">Nama Klien</span><p class="font-weight-bold"><?= $project['full_name'] ?></p></div>
                                    <div class="col-md-6 mb-3"><span class="summary-label">Tgl Survey</span><p class="font-weight-bold"><?= date('d F Y', strtotime($project['survey_date'])) ?></p></div>
                                    <div class="col-12 mb-3"><span class="summary-label">Alamat Lokasi</span><p class="font-weight-bold"><?= $project['address'] ?></p></div>
                                    <div class="col-12 mb-3"><span class="summary-label">Deskripsi Permintaan</span><p><?= nl2br($project['description']) ?></p></div>
                                </div>
                                <div class="section-title">Foto Lokasi</div>
                                <div class="col-12 col-sm-6 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="gallery">
                                                <?php if($project['gambar1']): ?>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($project['gambar' . $i]): ?>
                                                            <div class="gallery-item" data-image="<?= base_url('uploads/renovation/' . $project['gambar' . $i]) ?>" data-title="Image <?= $i ?>"></div>
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
                    <!-- tab 2 target -->
                    <!-- ------------ -->
                    <div class="tab-pane fade" id="target" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Daftar Milestone / Target Proyek</div>
                                <table class="table table-striped">
                                    <thead><tr><th>Nama Tukang</th><th>Target</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if(!empty($target_list)): foreach($target_list as $trg): ?>
                                        <tr>
                                            <td><?= esc($trg['tukang_name']) ?></td>
                                            <td><strong><?= esc($trg['target_name']) ?></strong></td>
                                            <td><?= date('d/m/Y', strtotime($trg['target_date'])) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm <?= ($trg['status'] == 'Achieved') ? 'btn-success' : 'btn-warning text-white' ?> dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?= $trg['status'] ?>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); updateTargetStatus(<?= $trg['id'] ?>, 'Pending', '<?= $trg['status'] ?>')">Pending</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); updateTargetStatus(<?= $trg['id'] ?>, 'Achieved', '<?= $trg['status'] ?>')">Achieved</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td><a href="<?= base_url('admin/renovation/delete-target/' . $trg['id'] . '/' . $project['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="8" class="text-center text-muted">Belum ada target.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 pl-md-4">
                                <div class="section-title">Daftar RAB</div>
                                <?php
                                $grouped_rab = [];
                                if (!empty($rab_list)) {
                                    foreach ($rab_list as $rab) {
                                        $grouped_rab[$rab['group_name']][] = $rab;
                                    }
                                }
                                ?>
                                <div class="accordion accordion-flush" id="accordionFlushRAB">
                                    <?php if (!empty($grouped_rab)): $i = 0; ?>
                                        <?php foreach ($grouped_rab as $group_name => $items): $i++; ?>
                                            <?php $collapseId = 'flush-collapseRab' . $i; ?>
                                            <?php $headingId = 'flush-headingRab' . $i; ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="<?= $headingId ?>">
                                                    <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>" style="padding: 10px 15px; font-size: 13px; font-weight: bold;">
                                                        <?= esc($group_name) ?>
                                                    </button>
                                                </h2>
                                                <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $headingId ?>" data-parent="#accordionFlushRAB">
                                                    <div class="accordion-body p-0">
                                                        <table class="table table-striped table-hover table-sm mb-0" style="font-size: 15px;">
                                                            <thead><tr><th class="pl-4">Sub group</th><th>Pekerjaan</th></tr></thead>
                                                            <tbody>
                                                                <?php foreach ($items as $rab): ?>
                                                                <?php $fullname = !empty($rab['sub_group_name']) ? $rab['sub_group_name'] . ' - ' . $rab['activity_name'] : $rab['activity_name']; ?>
                                                                <tr style="cursor: pointer;" onclick="$('#inputTargetName').val('<?= addslashes($fullname) ?>');" title="Klik untuk jadikan Target">
                                                                    <td class="pl-4" style="width: 50%;"><?= esc($rab['sub_group_name']) ?></td>
                                                                    <td><?= esc($rab['activity_name']) ?></td>                                            
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center text-muted my-4 p-4">Belum ada daftar RAB.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6 border-right">
                                <div class="section-title">Tambah Target Baru</div>
                                <form action="<?= base_url('admin/renovation/add-target') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="renovation_id" value="<?= $project['id'] ?>">
                                    <div class="form-group"><label>Nama Target</label><input type="text" id="inputTargetName" name="target_name" class="form-control" placeholder="Contoh: Selesai Pondasi" required></div>
                                    <div class="form-group">
                                        <label>Pilih Tukang</label>
                                        <select name="id_job_applications" class="form-control" required>
                                            <option value="">-- Pilih Tukang --</option>
                                            <?php foreach($worker as $wrk): ?>
                                            <option value="<?= $wrk['id'] ?>"><?= esc($wrk['tukang_name']) ?> - <?= $wrk['phone'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Deadline Tanggal</label><input type="date" name="target_date" class="form-control" required></div>
                                    <div class="form-group"><label>Keterangan</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                                    <button type="submit" class="btn btn-primary btn-block shadow-sm ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Target</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- ------------ -->
                    <!-- tab 3 survey -->
                    <!-- ------------ -->
                    <div class="tab-pane fade" id="survey" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Riwayat Survey</div>
                                <table class="table table-striped"><thead><tr class="text-center"><th>Tanggal</th><th>Judul</th><th>File</th><th style="width: 40%;">Komentar</th><th>Aksi</th></tr></thead><tbody>
                                <?php if(!empty($surveys)): foreach($surveys as $srv): ?>
                                    <tr class="text-center">
                                        <td><?= date('d/m/Y', strtotime($srv['created_at'])) ?></td>
                                        <td><?= $srv['title'] ?></td>
                                        <td><?php if(!empty($srv['file_url'])): ?><a href="<?= base_url('uploads/survey/' . $srv['file_url']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a><?php endif; ?></td>
                                        <?php if($srv['comment'] == null): ?>
                                            <td class="text-muted">belum ada komentar dari client</td>
                                        <?php else: ?>
                                            <td><?= $srv['comment'] ?></td>
                                        <?php endif; ?>
                                        <td><a href="<?= base_url('admin/renovation/delete_survey/' . $srv['id'] . '/' . $project['id']) ?>" class="btn btn-sm btn-danger shadow-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <div class="text-center py-5 text-muted small">Belum ada riwayat survey kawan.</div>
                                <?php endif; ?>
                                </tbody></table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Tambah Data Survey</div>
                                <form action="<?= base_url('admin/renovation/add-survey/' . $project['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label>Judul Survey</label><input type="text" name="title" class="form-control" required></div>
                                    <div class="form-group"><label>Hasil Survey / Catatan</label><textarea name="description" class="form-control" rows="5" required></textarea></div>
                                    <div class="form-group"><label>Foto Survey</label><input type="file" name="file_url" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block shadow-sm ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Hasil Survey</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- --------- -->
                    <!-- tab 4 desain -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="desain" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Riwayat Desain</div>
                                <table class="table table-striped"><thead><tr class="text-center"><th>Tanggal</th><th>Judul</th><th>File</th><th style="width: 40%;">Komentar</th><th>Aksi</th></tr></thead><tbody>
                                    <?php if (!empty($designs)): foreach($designs as $design): ?>
                                    <tr class="text-center">
                                        <td><?= date('d/m/Y', strtotime($design['created_at'])) ?></td>
                                        <td><?= $design['title'] ?></td>
                                        <td><?php if(!empty($design['file_url'])): ?><a href="<?= base_url('uploads/designs/' . $design['file_url']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a><?php endif; ?></td>
                                        <?php if($design['comment'] == null): ?>
                                            <td class="text-center text-muted">belum ada komentar dari client</td>
                                        <?php else: ?>
                                            <td><?= $design['comment'] ?></td>
                                        <?php endif; ?>
                                        <td><a href="<?= base_url('admin/renovation/delete-survey/' . $design['id'] . '/' . $project['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;"><span class="ladda-label"><i class="fas fa-trash"></i></span></a></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody></table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Upload Desain Renovasi</div>
                                <form action="<?= base_url('admin/renovation/add-design/' . $project['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" class="form-control" required></div>
                                    <div class="form-group"><label>File Desain</label><input type="file" name="file_url" class="form-control" required></div>
                                    <button type="submit" class="btn btn-primary btn-block shadow-sm ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Desain</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- --------- -->
                    <!-- tab 5 rab -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="rab" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-primary">Manajemen RAB Proyek</h6>
                            <div>
                                <a class="btn btn-primary btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();" href="<?= base_url('admin/renovation/cetak-pdf/' . $project['id']) ?>">
                                    <span class="ladda-label"><i class="fa-solid fa-file-lines"></i> preview kontrak</span>
                                </a>
                                <?php if (!empty($rab_list) && $rab_list[0]['is_locked'] == 0): ?>
                                    <a href="<?= base_url('admin/renovation/lock_rab/' . $project['id']) ?>" class="btn btn-danger btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Kunci RAB kawan? Data tidak bisa diubah lagi!')) { Ladda.create(this).start(); return true; } return false;">
                                        <span class="ladda-label"><i class="fas fa-lock"></i> Kunci RAB</span>
                                    </a>
                                <?php elseif(!empty($rab_list)): ?>
                                    <a href="<?= base_url('admin/renovation/unlock_rab/' . $project['id']) ?>" class="btn btn-warning btn-sm shadow-sm ladda-button" data-style="zoom-in" onclick="Ladda.create(this).start();">
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

                    <!-- --------- -->
                    <!-- tab 6 payment -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">

                        <div class="row">
                            <div class="col-md-12 pl-md-4">
                                <div class="section-title">Daftar Tagihan</div>
                                <table class="table table-hover">
                                    <thead><tr><th>Keterangan</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if(!empty($invoices)): foreach($invoices as $inv): ?>
                                            <tr>
                                                <td class="text-left small font-weight-bold"><?= $inv['description'] ?></td>
                                                <td class="text-primary font-weight-bold">Rp <?= number_format($inv['amount']) ?></td>
                                                <td><span class="badge <?= $inv['status'] == 'PAID' ? 'badge-success' : 'text-bg-warning text-white' ?>"><?= $inv['status'] ?></span></td>
                                                <td><a href="<?= base_url('admin/renovation/delete_invoice/' . $inv['id'] . '/' . $project['id']) ?>" class="btn btn-sm btn-danger ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                                    <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada tagihan</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kontrak</td></tr>
                                    <?php endif; ?>
                                </tbody></table>
                            </div>
                            <div class="col-md-6 border-right">
                                <div class="section-title">Buat Tagihan (Invoice)</div>
                                <form action="<?= base_url('admin/renovation/create-invoice') ?>" method="post">
                                    <input type="hidden" name="renovation_id" value="<?= $project['id'] ?>">
                                    <div class="form-group"><label>Deskripsi Tagihan</label><input type="text" name="description" class="form-control" placeholder="Contoh: DP Renovasi 50%" required></div>
                                    <div class="form-group"><label>Nominal (Rp)</label><input type="number" name="amount" class="form-control" required></div>
                                    <div class="form-group"><label>Batas Waktu</label><input type="date" name="due_date" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block shadow ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Kirim Invoice</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- --------- -->
                    <!-- tab 7 progress -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="section-title">Lapor Progress Renovasi</div>
                                <form action="<?= base_url('admin/renovation/add-progress/' . $project['id']) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label>Judul/Tahap</label><input type="text" name="title" class="form-control" required></div>
                                    <div class="form-group"><label>Minggu Ke</label><input type="number" name="week_number" class="form-control" required></div>
                                    <div class="form-group"><label>Keterangan</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                                    <div class="form-group"><label>Foto Lapangan</label><input type="file" name="photo_url" class="form-control"></div>
                                    <button type="submit" class="btn btn-primary btn-block shadow ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Simpan Progress</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8 pl-md-4">
                                <div class="section-title">Timeline Progress</div>
                                <?php if (!empty($progress)): foreach($progress as $pgs): ?>
                                    <div class="card mb-2 border shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="font-weight-bold"><?= $pgs['title'] ?></h6>
                                                <small class="text-muted"><?= date('d/m/Y', strtotime($pgs['created_at'])) ?></small>
                                            </div>
                                            <p class="small mb-1"><?= $pgs['description'] ?></p>
                                            <?php if($pgs['photo_url']): ?>
                                                <a href="<?= base_url('uploads/progress/' . $pgs['photo_url']) ?>" target="_blank" class="small text-primary font-weight-bold">Lihat Foto Progres</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="text-center py-5 text-muted small">Belum ada laporan progress lapangan.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- --------- -->
                    <!-- tab 8 info pekerjaan -->
                    <!-- --------- -->
                    <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7 border-right">
                                <div class="section-title">Update Lowongan Tukang (Renovasi)</div>
                                <form action="<?= base_url('admin/renovation/update-job-info') ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $project['id'] ?>">
                                    <div class="row">
                                        <div class="col-12"><div class="form-group"><label>Rincian Tugas</label><textarea name="detail_pekerjaan" class="form-control" rows="3"><?= $job_info['detail_pekerjaan'] ?? '' ?></textarea></div></div>
                                        <div class="col-12"><div class="form-group"><label>Rincian Lokasi</label><textarea name="detail_lokasi" class="form-control" rows="3"><?= $job_info['detail_lokasi'] ?? '' ?></textarea></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Upah / Hari (Rp)</label><input type="number" name="upah_per_hari" class="form-control" value="<?= $job_info['upah_per_hari'] ?? '' ?>"></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Mess Tukang</label><select name="tempat_tinggal" class="form-control"><option value="Ada" <?= ($job_info['tempat_tinggal'] ?? '') == 'Ada' ? 'selected' : '' ?>>Ada</option><option value="Tidak Ada" <?= ($job_info['tempat_tinggal'] ?? '') == 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option></select></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Tgl Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="<?= $job_info['tanggal_mulai'] ?? '' ?>"></div></div>
                                        <div class="col-md-6"><div class="form-group"><label>Tgl Selesai</label><input type="date" name="tanggal_akhir" class="form-control" value="<?= $job_info['tanggal_akhir'] ?? '' ?>"></div></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block shadow-sm ladda-button" data-style="zoom-in"><span class="ladda-label">Update Info Lowongan</span></button>
                                </form>
                            </div>
                            <div class="col-md-5">
                                <div class="section-title">Pelamar Tukang (<?= count($applicants) ?>)</div>
                                <?php if(!empty($applicants)): foreach($applicants as $app): ?>
                                    <div class="p-3 border rounded mb-2 bg-light shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 font-weight-bold text-dark">ID Tukang: <?= $app['tukang_id'] ?></h6>
                                            <span class="badge badge-info small"><?= $app['status'] ?></span>
                                        </div>
                                        <p class="small mb-2 mt-1"><?= date('d M Y', strtotime($app['created_at'])) ?></p>
                                        <form action="<?= base_url('admin/renovation/update_applicant_status') ?>" method="post" class="d-flex mt-2">
                                            <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                            <select name="status" class="form-control form-control-sm mr-2">
                                                <option value="PENDING" <?= $app['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                <option value="ACCEPTED" <?= $app['status'] == 'ACCEPTED' ? 'selected' : '' ?>>TERIMA</option>
                                                <option value="REJECTED" <?= $app['status'] == 'REJECTED' ? 'selected' : '' ?>>TOLAK</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label">Update</span></button>
                                        </form>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="text-center py-5 text-muted small">Belum ada pelamar tukang.</div>
                                <?php endif; ?>
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

    function updateTargetStatus(targetId, newStatus, currentStatus) {
        if (newStatus === currentStatus) {
         return;
        }
        
        if (confirm(`Anda yakin ingin mengubah status target menjadi "${newStatus}"?`)) {
            // Buat form tersembunyi secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= base_url('admin/renovation/update_target_status') ?>/${targetId}/${newStatus}`;

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
            renovation_id: <?= $project['id'] ?>,
            roman_number: row.find('.input-roman').val(),
            group_name: row.find('.input-group-name').val(),
            section_group: row.find('.input-section').val(),
            task_name: row.find('.input-task').val(),
            volume: row.find('.input-vol').val(),
            unit: row.find('.input-unit').val(),
            price: row.find('.input-price').val()
        };

        $.post('<?= base_url('admin/renovation/save_rab_row') ?>', data, function(res) {
            if(res.status) { 
                row.attr('data-id', res.id); 
                alert('👍 ' + res.message); 
                // Opsional: location.reload(); jika kawan ingin grup lsg ter-update secara visual
            } else { alert('❌ ' + res.message); }
        }).fail(function(xhr) { alert('Gagal kawan, cek console!'); });
    }

    function deleteRabRow(btn, id) {
        if(confirm('Hapus baris ini?')) {
            $.get('<?= base_url('admin/renovation/delete_rab_row') ?>/' + id, function(res) {
                if(res.status) { $(btn).closest('tr').remove(); calculateGrandTotal(); }
                else { alert(res.message); }
            });
        }
    }



    function openMaterialModal(id, title) {
        activeRabId = id; $('#modalMaterialTitle').text('Bahan: ' + title); $('#modalMaterials').modal('show');
        $.get('<?= base_url('admin/renovation/get_rab_materials') ?>/' + id, function(data) {
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
        $.post('<?= base_url('admin/renovation/add_rab_material') ?>', {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            rab_id: activeRabId, product_id: pId
        }, function(res) { 
            if(res.status) { openMaterialModal(activeRabId, ''); }
            else { alert(res.message); }
        });
    }

    function deleteMaterial(id) {
        if(confirm('Hapus bahan?')) {
            $.get('<?= base_url('admin/renovation/delete_rab_material') ?>/' + id, function() { openMaterialModal(activeRabId, ''); });
        }
    }
</script>

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