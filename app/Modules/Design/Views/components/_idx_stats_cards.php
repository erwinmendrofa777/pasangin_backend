<!-- ===== STATS CARDS (Only for Design Division roles) ===== -->
<?php if (in_array(strtolower(session()->get('role') ?? ''), ['kepala divisi desain', 'drafter', 'arsitek'])): ?>
<div class="row mb-0">
    <!-- Card Survei -->
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Survei Lapangan</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['surveys']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Laporan</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-route fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25" style="font-size: 0.8rem;">
                <span class="text-white-75">Total file laporan survei yang diunggah</span>
            </div>
        </div>
    </div>

    <!-- Card Target -->
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #6f42c1 0%, #a881af 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Target Pengerjaan</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['targets']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Target</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-bullseye fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between"
                style="font-size: 0.8rem;">
                <span class="text-white-75"><i
                        class="fas fa-check-circle me-1"></i><?= $workStats['targets']['done'] ?? 0 ?> Selesai</span>
                <span class="text-white-75"><i
                        class="fas fa-spinner me-1"></i><?= $workStats['targets']['progress'] ?? 0 ?> Proses</span>
                <span class="text-white-75"><i
                        class="fas fa-clock me-1"></i><?= $workStats['targets']['pending'] ?? 0 ?> Antri</span>
            </div>
        </div>
    </div>

    <!-- Card Desain -->
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-0"
            style="background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 mb-1"
                        style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Hasil Desain</h6>
                    <h3 class="mb-0 fw-bold"><?= number_format($workStats['designs']['total'] ?? 0) ?> <span
                            style="font-size: 0.95rem; font-weight: normal;">Berkas</span></h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;">
                    <i class="fas fa-magic fa-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top border-white border-opacity-25 d-flex justify-content-between"
                style="font-size: 0.8rem;">
                <span class="text-white-75"><i
                        class="fas fa-check-double me-1"></i><?= $workStats['designs']['approved'] ?? 0 ?>
                    Disetujui</span>
                <span class="text-white-75"><i
                        class="fas fa-exclamation-triangle me-1"></i><?= $workStats['designs']['rejected'] ?? 0 ?>
                    Revisi</span>
                <span class="text-white-75"><i
                        class="fas fa-hourglass-half me-1"></i><?= $workStats['designs']['pending'] ?? 0 ?>
                    Tinjau</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
