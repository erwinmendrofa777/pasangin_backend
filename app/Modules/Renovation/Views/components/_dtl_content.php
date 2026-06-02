<div class="row">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <a href="<?= base_url('admin/renovation') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header p-0 bg-white" style="border-radius: 10px 10px 0 0;">
                <div class="nav-tabs-container">
                    <button class="nav-scroll-btn left" onclick="scrollNav('left')"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="nav-tabs-wrapper">

                        <ul class="nav nav-tabs nav-tabs-premium" id="myTab" role="tablist">

                            <?php if (can('renovation_detail')): ?>
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#detail"><i
                                             class="fas fa-info-circle"></i> Detail</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_target')): ?>
                                <li class="nav-item"><a class="nav-link text-warning" data-bs-toggle="tab" href="#target"><i
                                             class="fas fa-bullseye"></i> Target</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_survey')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#survey"><i
                                             class="fas fa-map-marker-alt"></i> Survey</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_desain')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desain"><i
                                             class="fas fa-bezier-curve"></i> Desain</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_rab')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab" href="#rab"><i
                                             class="fas fa-calculator"></i> Kelola RAB</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_pembayaran')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment"><i
                                             class="fas fa-credit-card"></i> Pembayaran</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_progress')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#progress"><i
                                             class="fas fa-chart-line"></i> Progress</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_lowongan')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#info-pekerjaan"><i
                                             class="fas fa-tools"></i> Lowongan</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_absensi')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab"
                                         href="#absensi"><i class="fas fa-user-check"></i> Absensi</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#material"><i
                                             class="fas fa-boxes"></i> Pengajuan Material</a></li>
                            <?php endif; ?>

                        </ul>
                    </div>
                    <button class="nav-scroll-btn right" onclick="scrollNav('right')"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="tab-content pt-0 pe-1 ps-1" id="myTabContent">

                    <!-- ------------ -->
                    <!-- tab 1 detail -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                        <?php
                        // Status Configuration for Renovation
                        $renStatus = $renovation['status'] ?? 'PENDING';
                        $renStatusMeta = [
                            'PENDING' => ['color' => 'warning', 'icon' => 'fas fa-clock', 'label' => 'Pending', 'desc' => 'Menunggu tindak lanjut'],
                            'SURVEY' => ['color' => 'info', 'icon' => 'fas fa-map-marked-alt', 'label' => 'Survey', 'desc' => 'Sedang tahap survey'],
                            'DESIGNING' => ['color' => 'primary', 'icon' => 'fas fa-drafting-compass', 'label' => 'Desain', 'desc' => 'Proses pembuatan desain'],
                            'RAB' => ['color' => 'secondary', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'RAB', 'desc' => 'Penyusunan RAB'],
                            'RENOVATION' => ['color' => 'primary', 'icon' => 'fas fa-hard-hat', 'label' => 'Renovasi', 'desc' => 'Pembangunan berjalan'],
                            'COMPLETED' => ['color' => 'success', 'icon' => 'fas fa-check-circle', 'label' => 'Selesai', 'desc' => 'Proyek telah selesai'],
                            'CANCELLED' => ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'label' => 'Dibatalkan', 'desc' => 'Proyek dibatalkan'],
                        ];
                        $currentRenMeta = $renStatusMeta[$renStatus] ?? ['color' => 'dark', 'icon' => 'fas fa-circle', 'label' => $renStatus, 'desc' => 'Status tidak diketahui'];

                        // Initials
                        $nameParts = explode(' ', trim($renovation['full_name'] ?? 'K'));
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
                        ?>

                        <div class="row g-4 align-items-start mt-1">

                            <!-- ======================== LEFT: PROFILE INFO ======================== -->
                            <div class="col-12 col-md-7 mb-4">
                                <div class="card profile-card">
                                    <!-- Hero Banner -->
                                    <div class="profile-hero pb-4">
                                        <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-end gap-3"
                                            style="z-index:1;">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="role-chip-hero">
                                                    <i class="fas fa-hard-hat me-1"></i>Proyek
                                                </span>
                                                <span
                                                    class="status-pill status-<?= strtolower($currentRenMeta['color']) ?>">
                                                    <span class="dot"></span><?= $currentRenMeta['label'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile Body-->
                                    <div class="profile-body">

                                        <!-- Info List: Kontak -->
                                        <div class="info-list mb-4">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <p class="section-title text-primary"><i
                                                        class="fas fa-address-book me-1"></i>Kontak Klien</p>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-user"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Nama</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['full_name'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-key"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Id User</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['user_id'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Tanggal Pengajuan</div>
                                                    <div class="info-value">
                                                        <?= isset($renovation['created_at']) ? date('d M Y', strtotime($renovation['created_at'])) : '-' ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Email</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['email'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon text-success" style="background:#d1e7dd;"><i
                                                        class="fab fa-whatsapp"></i></div>
                                                <div
                                                    class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                                    <div>
                                                        <div class="info-label">Telepon / WhatsApp</div>
                                                        <div class="info-value">
                                                            <?= esc($renovation['phone'] ?? '-') ?>
                                                        </div>
                                                    </div>
                                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $renovation['phone']) ?>"
                                                        target="_blank" class="btn btn-sm btn-success px-3 shadow-sm"
                                                        style="border-radius: 8px;"><i class="fab fa-whatsapp"></i>
                                                        Chat</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info List: Detail Proyek -->
                                        <p class="section-title text-primary"><i
                                                class="fas fa-clipboard-list me-1"></i>Detail Proyek & Keuangan</p>
                                        <div class="info-list mb-4">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-warning" style="background:#fff3cd;">
                                                            <i class="fas fa-vector-square"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Luas Tanah</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['land_area']) ? $renovation['land_area'] . ' m²' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-warning" style="background:#fff3cd;">
                                                            <i class="fas fa-home"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Luas Bangunan</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['building_area']) ? $renovation['building_area'] . ' m²' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-success" style="background:#d1e7dd;">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Rencana Mulai</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['start_date']) ? date('d M Y', strtotime($renovation['start_date'])) : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-success" style="background:#d1e7dd;">
                                                            <i class="fas fa-stopwatch"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Estimasi Waktu</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['week']) ? $renovation['week'] . ' Minggu' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="p-3 rounded"
                                                        style="background: #f8f9fa; border: 1px dashed #ced4da;">
                                                        <div
                                                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-1">
                                                            <span class="text-muted font-weight-bold text-uppercase"
                                                                style="font-size: 0.75rem;">Total Pembayaran
                                                                (Estimasi)</span>
                                                            <span class="font-weight-bold text-primary"
                                                                style="font-size: 1.1rem;">Rp
                                                                <?= number_format($renovation['total_payment'] ?? 0, 0, ',', '.') ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted font-weight-bold text-uppercase"
                                                                style="font-size: 0.75rem;">Kode Voucher</span>
                                                            <span>
                                                                <?php if (!empty($renovation['voucher_code'])): ?>
                                                                    <span class="badge badge-warning px-2 py-1"><i
                                                                            class="fas fa-ticket-alt mr-1"></i>
                                                                        <?= $renovation['voucher_code'] ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info List: Lokasi -->
                                        <p class="section-title text-primary"><i
                                                class="fas fa-map-marked-alt me-1"></i>Lokasi Geografis & Foto</p>
                                        <div class="info-list mb-3">
                                            <div class="info-item">
                                                <div class="info-icon text-danger" style="background:#f8d7da;"><i
                                                        class="fas fa-map-marker-alt"></i></div>
                                                <div>
                                                    <div class="info-label">Alamat Lengkap</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['address'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($renovation['latitude']) && !empty($renovation['longitude'])): ?>
                                            <div class="map-container shadow-sm p-1 bg-white mb-3"
                                                style="border-radius: 14px; border: 1px solid #e9ecef;">
                                                <iframe
                                                    src="https://maps.google.com/maps?q=<?= esc($renovation['latitude']) ?>,<?= esc($renovation['longitude']) ?>&hl=id&z=15&output=embed"
                                                    width="100%" height="220" style="border:0; border-radius:10px;"
                                                    allowfullscreen="" loading="lazy"
                                                    referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center p-4 bg-light mb-3"
                                                style="border-radius: 12px; border: 1px dashed #ced4da;">
                                                <i class="fas fa-map-marked-alt text-muted mb-2"
                                                    style="font-size:2rem; opacity:0.5;"></i>
                                                <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:500;">
                                                    Koordinat peta belum disetel.</p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="gallery gallery-md mt-3 d-flex flex-wrap gap-2">
                                            <?php
                                            $hasPhotos = false;
                                            for ($i = 1; $i <= 5; $i++) {
                                                if (!empty($renovation['gambar' . $i])) {
                                                    $hasPhotos = true;
                                                    $fileUrl = base_url('uploads/renovation/' . $renovation['gambar' . $i]);
                                                    ?>
                                                    <a href="<?= $fileUrl ?>" class="glightbox shadow-sm rounded"
                                                       data-gallery="renovation-gallery"
                                                       data-title="Foto Lokasi <?= $i ?>"
                                                       style="width: 75px; height: 75px; display: inline-block; overflow: hidden; border: 1px solid #e4e9f0;">
                                                        <img src="<?= $fileUrl ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Lokasi <?= $i ?>">
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            if (!$hasPhotos): ?>
                                                <div class="text-center text-muted small w-100 py-3 bg-light rounded"
                                                    style="border: 1px dashed #ced4da;">Belum ada foto lokasi yang
                                                    diunggah.
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ======================== RIGHT: UPDATE STATUS ======================== -->
                            <div class="col-12 col-md-5 mb-4">
                                <div class="card action-card">
                                    <!-- Card Header -->
                                    <div class="card-header">
                                        <h6 class="text-white mb-0 fw-bold">
                                            <i class="fas fa-sliders-h mr-2"></i>Kelola Status Proyek
                                        </h6>
                                    </div>

                                    <div class="card-body p-2 pt-2">
                                        <form id="updateStatusFormDirect"
                                            action="<?= base_url('admin/renovation/update_status') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $renovation['id'] ?>">
                                            <input type="hidden" name="status" id="selectedStatusInput"
                                                value="<?= $renStatus ?>">

                                            <div class="d-flex flex-column" style="gap: 10px;">
                                                <?php foreach ($renStatusMeta as $key => $act):
                                                    $isActive = ($renStatus === $key);
                                                    ?>
                                                    <button type="button"
                                                        class="btn <?= $isActive ? 'btn-' . $act['color'] . ' btn-current-status' : 'btn-outline-' . $act['color'] ?> status-action-btn text-left w-100"
                                                        data-status="<?= $key ?>" data-color="<?= $act['color'] ?>"
                                                        data-is-active="<?= $isActive ? 'true' : 'false' ?>">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="<?= $act['icon'] ?>"
                                                                    style="width:20px; text-align:center;"></i>
                                                                <div class="ml-2">
                                                                    <div
                                                                        style="font-size:0.88rem; font-weight:700; line-height:1.2; text-align: left;">
                                                                        <?= $act['label'] ?>
                                                                    </div>
                                                                    <div
                                                                        style="font-size:0.72rem; font-weight:400; opacity:0.75; text-align: left;">
                                                                        <?= $act['desc'] ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if ($isActive): ?>
                                                                <i class="fas fa-check-circle status-icon ml-2"
                                                                    style="font-size:1rem;"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-chevron-right status-icon ml-2"
                                                                    style="font-size:0.75rem; opacity:0.6;"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="mt-4 pt-3 border-top text-center">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block btn-lg ladda-button shadow-sm"
                                                    data-style="zoom-in" style="border-radius: 8px; font-weight: bold;">
                                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>

                                        <div class="mt-3 pt-3 border-top">
                                            <p class="text-muted mb-0" style="font-size:0.78rem;">
                                                <i class="fas fa-info-circle text-primary mr-1"></i>
                                                Pilih status baru lalu klik tombol Simpan. Tombol berwarna solid
                                                adalah
                                                status saat ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end tab 1 detail -->

                    <!-- --------- -->
                    <!-- tab 3 target -->
                    <!-- --------- -->
                    <?php if (can('renovation_target')): ?>
                        <div class="tab-pane fade" id="target" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\target') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ------------ -->
                    <!-- tab 4 survey -->
                    <!-- ------------ -->
                    <?php if (can('renovation_survey')): ?>
                        <div class="tab-pane fade" id="survey" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\survey') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ------------ -->
                    <!-- tab 5 desain -->
                    <!-- ------------ -->
                    <?php if (can('renovation_desain')): ?>
                        <div class="tab-pane fade" id="desain" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\desain') ?>
                        </div>
                    <?php endif; ?>

                    <!-- --------- -->
                    <!-- tab 6 rab -->
                    <!-- --------- -->
                    <?php if (can('renovation_rab')): ?>
                        <div class="tab-pane fade" id="rab" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\rab') ?>
                        </div>
                    <?php endif; ?>


                    <!-- ---------------- -->
                    <!-- tab 8 pembayaran -->
                    <!-- ---------------- -->
                    <?php if (can('renovation_pembayaran')): ?>
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\pembayaran') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------- -->
                    <!-- tab 9 progress -->
                    <!-- -------------- -->
                    <?php if (can('renovation_progress')): ?>
                        <div class="tab-pane fade" id="progress" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\progress') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------------- -->
                    <!-- tab 10 info pekerjaan -->
                    <!-- -------------------- -->
                    <?php if (can('renovation_progress')): ?>
                        <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\loker') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------------- -->
                    <!-- tab 11 absensi -->
                    <!-- -------------------- -->
                    <?php if (can('renovation_absensi')): ?>
                        <div class="tab-pane fade" id="absensi" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\absensi') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ---------------------- -->
                    <!-- tab 12 material        -->
                    <!-- ---------------------- -->
                    <?php if (can('renovation')): ?>
                        <div class="tab-pane fade" id="material" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\material_submissions') ?>
                        </div>
                    <?php endif; ?>

                </div><!-- end .tab-content -->
            </div><!-- end .card-body -->
        </div><!-- end .card -->
    </div><!-- end .col-12 -->
</div><!-- end .row -->
