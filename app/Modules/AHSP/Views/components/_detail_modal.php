<!-- ===== DETAIL MODAL ===== -->
<div class="modal fade" id="ahspDetailModal" tabindex="-1" aria-labelledby="ahspDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h6 class="modal-title fw-bold text-dark" id="ahspDetailModalTitle" style="font-size: 1.1rem;">
                    <i class="fas fa-clipboard-list me-2 text-primary"></i>Rincian Detail AHSP
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body py-4 px-4">
                <!-- Header Data Read Only -->
                <div class="card p-3 mb-4 border-0" style="background: #f8fafc; border-radius: 12px;">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;"><i class="fas fa-info-circle me-1 text-primary"></i>Informasi Pekerjaan</h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <span class="text-muted d-block small">Kode AHSP</span>
                            <span class="fw-bold text-primary" id="detail-kode">-</span>
                        </div>
                        <div class="col-12 col-md-8">
                            <span class="text-muted d-block small">Uraian Pekerjaan</span>
                            <span class="fw-bold text-dark" id="detail-uraian">-</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs nav-tabs-premium mb-3" id="ahspDetailTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detail-bahan-tab" data-bs-toggle="tab" data-bs-target="#detail-bahan-pane" type="button" role="tab" aria-controls="detail-bahan-pane" aria-selected="true">
                            <i class="fas fa-boxes me-2"></i>Daftar Bahan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detail-tenaga-tab" data-bs-toggle="tab" data-bs-target="#detail-tenaga-pane" type="button" role="tab" aria-controls="detail-tenaga-pane" aria-selected="false">
                            <i class="fas fa-users me-2"></i>Tenaga Kerja
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="ahspDetailTabContent">
                    <!-- Tab Bahan -->
                    <div class="tab-pane fade show active" id="detail-bahan-pane" role="tabpanel" aria-labelledby="detail-bahan-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="table-bahan-detail">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">No</th>
                                        <th style="width: 150px;">Kode Bahan</th>
                                        <th>Nama / Uraian Bahan</th>
                                        <th style="width: 120px;">Satuan</th>
                                        <th style="width: 150px;" class="text-end">Koefisien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated dynamically by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Tenaga Kerja -->
                    <div class="tab-pane fade" id="detail-tenaga-pane" role="tabpanel" aria-labelledby="detail-tenaga-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="table-tenaga-detail">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">No</th>
                                        <th style="width: 150px;">Kode Tenaga</th>
                                        <th>Klasifikasi Pekerja</th>
                                        <th style="width: 120px;">Satuan</th>
                                        <th style="width: 150px;" class="text-end">Koefisien</th>
                                        <th style="width: 180px;" class="text-end">Harga Satuan (Rp)</th>
                                        <th style="width: 180px;" class="text-end">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated dynamically by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-end gap-2 pt-0 pb-4 pe-4">
                <button type="button" class="btn btn-primary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius:8px; font-size: 0.85rem; height: 38px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
