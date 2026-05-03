<style>
    /* ── Loker Canvas Design System (Primary Theme) ── */
    :root {
        --canvas-primary: #6777ef;
        --canvas-primary-dark: #394eea;
        --canvas-success: #47c363;
        --canvas-warning: #ffa426;
        --canvas-danger: #fc544b;
        --canvas-dark: #1e293b;
        --canvas-text: #64748b;
        --canvas-bg: #f8fafc;
        --canvas-radius: 20px;
    }

    .canvas-wrapper {
        font-family: 'Inter', 'Nunito', sans-serif;
        color: var(--canvas-dark);
        animation: canvasFadeIn 0.6s ease-out;
    }

    @keyframes canvasFadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hero Card (Primary BG) */
    .hero-primary-card {
        background: linear-gradient(135deg, var(--canvas-primary) 0%, #828eff 100%);
        border-radius: var(--canvas-radius);
        border: none;
        box-shadow: 0 10px 25px rgba(103, 119, 239, 0.25);
        color: #fff;
        margin-bottom: 35px;
    }

    .stat-divider {
        width: 1px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
    }

    .hero-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        margin-bottom: 5px;
        display: block;
    }

    .hero-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
    }

    /* Main Grid */
    .canvas-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    .canvas-card {
        background: #fff;
        border-radius: var(--canvas-radius);
        border: 1px solid #edf2f7;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .canvas-card-header {
        padding: 20px 25px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-accent {
        width: 4px;
        height: 20px;
        background: var(--canvas-primary);
        border-radius: 10px;
        margin-right: 12px;
    }

    /* Info Display */
    .info-group {
        margin-bottom: 22px;
    }

    .info-label {
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--canvas-primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        display: block;
    }

    .info-content {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--canvas-dark);
        line-height: 1.6;
    }

    /* Form Elements */
    .canvas-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #edf2f7;
        background: #fdfdff;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .canvas-input:focus {
        border-color: var(--canvas-primary);
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(103, 119, 239, 0.1);
    }

    .canvas-btn-primary {
        background: var(--canvas-primary);
        color: #fff;
        border: none;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(103, 119, 239, 0.3);
    }

    .canvas-btn-primary:hover {
        background: var(--canvas-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.4);
    }

    /* Radar List */
    .radar-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }

    .radar-item:last-child {
        border-bottom: none;
    }

    .radar-item:hover {
        background: #fcfcfd;
    }

    /* Mobile */
    @media (max-width: 1100px) {
        .canvas-grid {
            grid-template-columns: 1fr;
        }

        .stat-divider {
            display: none;
        }

        .hero-stat-item {
            text-align: center;
            margin-bottom: 15px;
        }

        .hero-stat-item:last-child {
            margin-bottom: 0;
        }
    }
</style>

<div class="canvas-wrapper mt-4">

    <!-- HERO SECTION (PRIMARY BG) -->
    <div class="hero-primary-card">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0 border-right-lg">
                    <span class="hero-stat-label" style="opacity:0.7 font-size:2rem">STATUS LOWONGAN AKTIF</span>
                    <p class="mb-0 small text-white-50">Tukang dapat melihat dan melamar proyek ini sekarang.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row text-center text-md-left">
                        <div class="col-md-4 hero-stat-item">
                            <span class="hero-stat-label">Total Pelamar</span>
                            <h3 class="hero-stat-value"><?= count($applicants) ?> Orang</h3>
                        </div>
                        <div class="col-md-1 d-none d-md-block">
                            <div class="stat-divider mx-auto"></div>
                        </div>
                        <div class="col-md-4 hero-stat-item">
                            <span class="hero-stat-label">Upah Harian</span>
                            <h3 class="hero-stat-value">Rp
                                <?= number_format($job_info['upah_per_hari'] ?? 0, 0, ',', '.') ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="canvas-grid">

        <!-- LEFT: MAIN CANVAS -->
        <div class="main-canvas">

            <!-- Preview Section -->
            <div class="canvas-card">
                <div class="canvas-card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-accent"></div>
                        <h6 class="font-weight-bold mb-0">Preview Tampilan Tukang</h6>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="info-group">
                                <span class="info-label">Rincian Tugas & Tanggung Jawab</span>
                                <div class="info-content">
                                    <?= nl2br(esc($job_info['detail_pekerjaan'] ?? '')) ?: '<em>Belum diatur</em>' ?>
                                </div>
                            </div>
                            <div class="info-group mb-0">
                                <span class="info-label">Lokasi & Patokan Proyek</span>
                                <div class="info-content">
                                    <?= nl2br(esc($job_info['detail_lokasi'] ?? '')) ?: '<em>Belum diatur</em>' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 bg-light p-4 rounded-lg">
                            <div class="info-group">
                                <span class="info-label">Fasilitas Mess</span>
                                <div class="info-content font-weight-bold">
                                    <?= esc($job_info['tempat_tinggal'] ?? '-') ?>
                                </div>
                            </div>
                            <div class="info-group mb-0">
                                <span class="info-label">Jadwal Mulai - Selesai</span>
                                <div class="info-content font-weight-bold">
                                    <i class="far fa-calendar-alt text-primary mr-2"></i>
                                    <?= !empty($job_info['tanggal_mulai']) ? date('d M', strtotime($job_info['tanggal_mulai'])) : '?' ?>
                                    -
                                    <?= !empty($job_info['tanggal_akhir']) ? date('d M Y', strtotime($job_info['tanggal_akhir'])) : '?' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Section -->
            <div class="canvas-card">
                <div class="canvas-card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-accent"></div>
                        <h6 class="font-weight-bold mb-0">Pengaturan Detail Lowongan</h6>
                    </div>
                </div>
                <div class="p-4">
                    <form action="<?= base_url('admin/renovation/update-job-info') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $renovation['id'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="canvas-form-label">Uraian Pekerjaan Tukang</label>
                                <textarea name="detail_pekerjaan" class="canvas-input" rows="4"
                                    placeholder="Apa saja tugas tukang?"><?= $job_info['detail_pekerjaan'] ?? '' ?></textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="canvas-form-label">Patokan Alamat (Lokasi)</label>
                                <textarea name="detail_lokasi" class="canvas-input" rows="4"
                                    placeholder="Rumah nomor berapa? Patokan apa?"><?= $job_info['detail_lokasi'] ?? '' ?></textarea>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="canvas-form-label">Mess Tukang</label>
                                <select name="tempat_tinggal" class="canvas-input">
                                    <option value="Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Ada') ? 'selected' : '' ?>>Disediakan</option>
                                    <option value="Tidak Ada" <?= (($job_info['tempat_tinggal'] ?? '') == 'Tidak Ada') ? 'selected' : '' ?>>Tidak Ada</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="canvas-form-label">Jadwal Proyek</label>
                                <div class="d-flex gap-2">
                                    <input type="date" name="tanggal_mulai" class="canvas-input"
                                        value="<?= $job_info['tanggal_mulai'] ?? '' ?>">
                                    <input type="date" name="tanggal_akhir" class="canvas-input"
                                        value="<?= $job_info['tanggal_akhir'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="canvas-form-label">Upah Harian (Rp)</label>
                                <input type="number" name="upah_per_hari" class="canvas-input" placeholder="0"
                                    value="<?= $job_info['upah_per_hari'] ?? '' ?>">
                            </div>
                        </div>

                        <button type="submit" class="canvas-btn-primary ladda-button" data-style="zoom-in">
                            <span class="ladda-label"><i class="fas fa-save mr-2"></i>Simpan & Perbarui Lowongan</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- RIGHT: APPLICANTS -->
        <div class="side-radar">
            <div class="canvas-card">
                <div class="canvas-card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-accent"></div>
                        <h6 class="font-weight-bold mb-0">Daftar Pelamar Masuk</h6>
                    </div>
                    <span class="badge badge-primary rounded-pill"><?= count($applicants) ?></span>
                </div>
                <div style="max-height: 800px; overflow-y: auto;">
                    <?php if (!empty($applicants)):
                        foreach ($applicants as $app):
                            $st = $app['status'] ?? 'Berkas Diproses';
                            
                            // Color mapping for new statuses
                            $stColor = 'badge-warning'; // Default for "Berkas Diproses"
                            if ($st === 'Siap Kerja') $stColor = 'badge-success';
                            if ($st === 'Ditolak') $stColor = 'badge-danger';
                            if ($st === 'Proses Test') $stColor = 'badge-primary';
                            if ($st === 'Proses Aktivasi') $stColor = 'badge-info';
                            ?>
                            <div class="radar-item">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0">
                                            <?= esc($app['tukang_name'] ?? 'Tukang #' . $app['tukang_id']) ?>
                                        </div>
                                        <small class="text-muted"><?= date('d M Y', strtotime($app['created_at'])) ?></small>
                                    </div>
                                    <span class="badge <?= $stColor ?> px-2 py-1" style="font-size:9px;"><?= strtoupper($st) ?></span>
                                </div>

                                <form action="<?= base_url('admin/renovation/update_applicant_status') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                    <div class="input-group">
                                        <select name="status" class="form-control form-control-sm canvas-input"
                                            style="padding: 6px 10px; height:auto; border-radius: 8px 0 0 8px;">
                                            <option value="Berkas Diproses" <?= $st === 'Berkas Diproses' ? 'selected' : '' ?>>Berkas Diproses</option>
                                            <option value="Proses Test" <?= $st === 'Proses Test' ? 'selected' : '' ?>>Proses Test</option>
                                            <option value="Proses Aktivasi" <?= $st === 'Proses Aktivasi' ? 'selected' : '' ?>>Proses Aktivasi</option>
                                            <option value="Siap Kerja" <?= $st === 'Siap Kerja' ? 'selected' : '' ?>>Siap Kerja</option>
                                            <option value="Ditolak" <?= $st === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary btn-sm px-3"
                                                style="border-radius: 0 8px 8px 0;">SET</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; else: ?>
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                            <p class="small mb-0">Belum ada pelamar tukang.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>