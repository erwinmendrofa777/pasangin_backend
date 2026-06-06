<!-- ══════════ SECTION 1: PREVIEW LOWONGAN ══════════ -->
<div class="loker-section mb-4">
    <div class="loker-preview-card">
        <div class="loker-preview-header">
            <h6 class="text-white mb-0" style="font-weight:700; font-size:0.9rem;">
                <i class="fas fa-bullhorn mr-2"></i> Data Lowongan
            </h6>
        </div>

        <div class="loker-info-grid">
            <!-- Left Column -->
            <div class="loker-info-left">
                <div class="loker-field-label">
                    <i class="fas fa-clipboard-list text-primary"></i> Detail Tugas Pekerjaan
                </div>
                <div class="loker-field-value">
                    <?= esc($job_info['detail_pekerjaan'] ?? '') ?: '<span class="text-muted font-italic" style="font-weight:400;">Belum ada rincian tugas.</span>' ?>
                </div>

                <div class="loker-field-label">
                    <i class="fas fa-map-marker-alt text-danger"></i> Detail Lokasi (Patokan)
                </div>
                <div class="loker-field-value mb-0">
                    <?= esc($job_info['detail_lokasi'] ?? '') ?: '<span class="text-muted font-italic" style="font-weight:400;">Belum ada patokan lokasi.</span>' ?>
                </div>
            </div>

            <!-- Right Column -->
            <div class="loker-info-right">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="loker-stat-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="loker-stat-icon" style="background:rgba(255, 92, 92,0.12); color:var(--palette-primary);">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <div class="loker-field-label mb-1">Mess Tukang</div>
                                    <div style="font-size:0.95rem; font-weight:700; color:#34395e;">
                                        <?php
                                        $mess = $job_info['tempat_tinggal'] ?? '-';
                                        $messColor = $mess === 'Ada' ? 'text-success' : 'text-danger';
                                        ?>
                                        <span class="<?= $messColor ?>"><?= esc($mess) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="loker-stat-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="loker-stat-icon" style="background:rgba(71,195,99,0.12); color:#47c363;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <div class="loker-field-label mb-1">Upah / Hari</div>
                                    <div style="font-size:0.95rem; font-weight:700; color:var(--palette-primary);">
                                        Rp <?= number_format($job_info['upah_per_hari'] ?? 0, 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="loker-stat-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="loker-stat-icon" style="background:rgba(252,84,75,0.12); color:#fc544b;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="loker-field-label mb-1">Mulai</div>
                                    <div style="font-size:0.88rem; font-weight:600; color:#34395e;">
                                        <?= !empty($job_info['tanggal_mulai']) ? date('d M Y', strtotime($job_info['tanggal_mulai'])) : '-' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="loker-stat-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="loker-stat-icon" style="background:rgba(255,164,38,0.12); color:#ffa426;">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div>
                                    <div class="loker-field-label mb-1">Selesai</div>
                                    <div style="font-size:0.88rem; font-weight:600; color:#34395e;">
                                        <?= !empty($job_info['tanggal_akhir']) ? date('d M Y', strtotime($job_info['tanggal_akhir'])) : '-' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ SECTION 2: FORM INPUT ══════════ -->
<div class="loker-section">
    <div class="loker-form-card">
        <div class="loker-form-header">
            <h6 class="text-white mb-0" style="font-weight:700; font-size:0.88rem;">
                <i class="fas fa-edit mr-2"></i> Form Input Detail Proyek Tukang
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('admin/construction/update-job-info') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $construction['id'] ?>">

                <div class="row g-3">
                    <!-- Detail Pekerjaan -->
                    <div class="col-md-6">
                        <label class="loker-form-label">
                            <i class="fas fa-clipboard-list mr-1 text-primary" style="font-size:0.7rem;"></i> Rincian
                            Detail Pekerjaan
                        </label>
                        <textarea name="detail_pekerjaan" class="form-control loker-form-input" rows="4"
                            placeholder="Masukkan detail tugas pekerjaan..."><?= $job_info['detail_pekerjaan'] ?? '' ?></textarea>
                    </div>

                    <!-- Patokan Lokasi -->
                    <div class="col-md-6">
                        <label class="loker-form-label">
                            <i class="fas fa-map-marker-alt mr-1 text-danger" style="font-size:0.7rem;"></i> Patokan
                            Lokasi Proyek
                        </label>
                        <textarea name="detail_lokasi" class="form-control loker-form-input" rows="4"
                            placeholder="Masukkan patokan lokasi proyek..."><?= $job_info['detail_lokasi'] ?? '' ?></textarea>
                    </div>

                    <!-- Mess -->
                    <div class="col-md-4">
                        <label class="loker-form-label">
                            <i class="fas fa-home mr-1 text-primary" style="font-size:0.7rem;"></i> Mess
                        </label>
                        <select name="tempat_tinggal" class="form-control loker-form-input">
                            <option value="Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Ada') ? 'selected' : '' ?>>Ada
                            </option>
                            <option value="Tidak Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Tidak Ada') ? 'selected' : '' ?>>Tidak Ada</option>
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="col-md-4">
                        <label class="loker-form-label">
                            <i class="fas fa-calendar-alt mr-1 text-success" style="font-size:0.7rem;"></i> Tanggal
                            Mulai
                            – Akhir
                        </label>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <input type="date" name="tanggal_mulai" class="form-control loker-form-input"
                                value="<?= $job_info['tanggal_mulai'] ?? '' ?>">
                            <input type="date" name="tanggal_akhir" class="form-control loker-form-input"
                                value="<?= $job_info['tanggal_akhir'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- Upah -->
                    <div class="col-md-4">
                        <label class="loker-form-label">
                            <i class="fas fa-money-bill-wave mr-1 text-warning" style="font-size:0.7rem;"></i> Upah per
                            Hari (Rp)
                        </label>
                        <input type="number" name="upah_per_hari" class="form-control loker-form-input"
                            placeholder="Contoh: 150000" value="<?= $job_info['upah_per_hari'] ?? '' ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-loker-submit btn-block mt-4 ladda-button"
                    data-style="zoom-in">
                    <span class="ladda-label"><i class="fas fa-save mr-2"></i>Update Info Pekerjaan</span>
                </button>
            </form>
        </div>
    </div>
</div>