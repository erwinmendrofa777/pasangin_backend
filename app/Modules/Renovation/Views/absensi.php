<div class="card border-0 shadow-none rounded-0 mb-0" style="margin-left: -20px; margin-right: -20px;">

    <!-- Card Header -->
    <div class="card-header bg-primary border-0 px-3 py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 w-100">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-10 flex-shrink-0"
                    style="width:44px;height:44px;border:1.5px solid rgba(255,255,255,0.25);">
                    <i class="fas fa-user-check text-white" style="font-size:1rem;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-white">Log Absensi Tukang</h5>
                    <small class="text-white opacity-75">Riwayat kehadiran pekerja Renovasi</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                <?php
                $totalMasuk = count(array_filter($attendance_list ?? [], fn($a) => $a['type'] === 'masuk'));
                $totalKeluar = count(array_filter($attendance_list ?? [], fn($a) => $a['type'] !== 'masuk'));
                ?>
                <span
                    class="badge bg-white bg-opacity-15 text-primary border border-white border-opacity-25 px-3 py-2 rounded-pill fw-semibold"
                    style="font-size:0.78rem;">
                    <i class="fas fa-sign-in-alt me-1 opacity-75"></i> Masuk: <?= $totalMasuk ?>
                </span>
                <span
                    class="badge bg-white bg-opacity-15 text-primary border border-white border-opacity-25 px-3 py-2 rounded-pill fw-semibold"
                    style="font-size:0.78rem;">
                    <i class="fas fa-sign-out-alt me-1 opacity-75"></i> Keluar: <?= $totalKeluar ?>
                </span>
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold" style="font-size:0.78rem;">
                    <i class="fas fa-list me-1"></i> <?= count($attendance_list ?? []) ?> Total
                </span>
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body p-0">
        <?php if (empty($attendance_list)): ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-user-clock text-muted opacity-25" style="font-size: 4rem;"></i>
                </div>
                <h6 class="text-secondary fw-semibold">Belum Ada Data Absensi</h6>
                <p class="text-muted small mb-0">Log kehadiran tukang untuk proyek ini belum tersedia.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="table-absensi">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;width:20px;">#</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Tipe</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Waktu</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Jumlah Tukang</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Bukti Video</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Lokasi</th>
                            <th class="text-uppercase text-muted fw-semibold"
                                style="font-size:0.7rem;letter-spacing:0.7px;">Deskripsi</th>
                            <th class="text-center text-uppercase text-muted fw-semibold pe-4"
                                style="font-size:0.7rem;letter-spacing:0.7px;width:50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_list as $index => $abs): ?>
                            <tr>
                                <!-- No -->
                                <td class="text-center">
                                    <span class="text-muted fw-medium" style="font-size:0.8rem;"><?= $index + 1 ?></span>
                                </td>

                                <!-- Tipe -->
                                <td>
                                    <?php if ($abs['type'] === 'masuk'): ?>
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                            style="background:rgba(25,135,84,0.1);color:#157347;border:1px solid rgba(25,135,84,0.25);font-size:0.72rem;">
                                            <i class="fas fa-sign-in-alt me-1"></i> MASUK
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                            style="background:rgba(220,53,69,0.1);color:#b02a37;border:1px solid rgba(220,53,69,0.25);font-size:0.72rem;">
                                            <i class="fas fa-sign-out-alt me-1"></i> KELUAR
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Waktu -->
                                <td>
                                    <div class="fw-semibold text-dark" style="font-size:0.85rem;">
                                        <?= date('d M Y', strtotime($abs['waktu'])) ?>
                                    </div>
                                    <div class="text-muted mt-1 d-flex align-items-center gap-1" style="font-size:0.75rem;">
                                        <i class="far fa-clock" style="font-size:0.65rem;"></i>
                                        <?= date('H:i:s', strtotime($abs['waktu'])) ?>
                                    </div>
                                </td>

                                <!-- Jumlah Tukang -->
                                <td>
                                    <span class="fw-semibold text-dark" style="font-size:0.85rem;">
                                        <?= (int)($abs['jumlah_tukang'] ?? 0) ?>
                                    </span>
                                    <small class="text-muted d-block" style="font-size:0.72rem;">orang</small>
                                </td>

                                <!-- Video -->
                                <td>
                                    <?php if (!empty($abs['file'])): ?>
                                        <?php $fileUrl = base_url('uploads/renovation/absen_tukang/' . $abs['file']); ?>
                                        <!-- Hidden video player container for GLightbox native playback -->
                                        <div style="display:none;" id="video-absen-<?= $abs['id'] ?>">
                                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                <div class="text-white mt-2 text-start px-2">
                                                    <h6 class="mb-1 fw-bold text-white">Video Absensi Tukang</h6>
                                                    <small class="text-muted">Tanggal: <?= date('d M Y', strtotime($abs['tanggal'])) ?> | Jam: <?= date('H:i:s', strtotime($abs['waktu'])) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#video-absen-<?= $abs['id'] ?>" class="glightbox btn btn-sm btn-outline-primary px-3 fw-semibold"
                                            data-gallery="absensi-gallery"
                                            data-type="inline"
                                            data-slide-class="glightbox-video-slide"
                                            style="font-size:0.76rem;border-radius:8px;">
                                            <i class="fas fa-play-circle me-1"></i> Lihat Video
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic" style="font-size:0.78rem;">
                                            <i class="fas fa-ban me-1 opacity-50"></i> Tidak ada
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Lokasi -->
                                <td>
                                    <a href="https://www.google.com/maps?q=<?= $abs['latitude'] ?>,<?= $abs['longitude'] ?>"
                                        target="_blank"
                                        class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold text-primary"
                                        style="font-size:0.78rem;">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Lihat Map
                                    </a>
                                </td>

                                <!-- Deskripsi -->
                                <?php
                                $deskripsi = $abs['deskripsi'] ?? '';
                                $maxChar = 30;
                                $isTooLong = mb_strlen($deskripsi) > $maxChar;
                                $shortText = $isTooLong ? mb_substr($deskripsi, 0, $maxChar) . '…' : $deskripsi;
                                $escapedFull = htmlspecialchars($deskripsi, ENT_QUOTES);
                                ?>
                                <td class="desc-cell">
                                    <?php if ($deskripsi): ?>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            <p class="mb-0 text-secondary desc-text"
                                                style="font-size:0.8rem;word-break:break-word;">
                                                <?= htmlspecialchars($shortText) ?>
                                            </p>
                                            <?php if ($isTooLong): ?>
                                                <button type="button" class="btn btn-link p-0 desc-popover-btn" data-bs-toggle="popover"
                                                    data-bs-html="true" data-bs-placement="left" data-bs-trigger="click"
                                                    data-bs-title="<i class='fas fa-align-left text-primary opacity-75'></i>Deskripsi Lengkap"
                                                    data-bs-content="<?= $escapedFull ?>" data-bs-custom-class="desc-popover"
                                                    style="text-decoration:none;">
                                                    <span
                                                        class="badge bg-light text-secondary border border-secondary border-opacity-10 px-2 py-1 rounded-pill"
                                                        style="font-size:0.7rem; font-weight: 600;">
                                                        Selengkapnya <i class="fas fa-chevron-down ms-1"
                                                            style="font-size:0.55rem; opacity:0.7;"></i>
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic" style="font-size:0.8rem;">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td class="text-center pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDeleteAttendance(<?= $abs['id'] ?>, <?= $renovation['id'] ?>)"
                                        title="Hapus Absensi"
                                        style="width:34px;height:34px;padding:0;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-trash-alt" style="font-size:0.78rem;"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
    // Init Bootstrap Popovers
    $(document).ready(function () {
        const popoverEls = document.querySelectorAll('[data-bs-toggle="popover"]');
        popoverEls.forEach(el => {
            new bootstrap.Popover(el, {
                html: true,
                sanitize: false,
                trigger: 'click',
            });
        });

        // Tutup popover saat klik di luar
        document.addEventListener('click', function (e) {
            popoverEls.forEach(el => {
                const popoverInstance = bootstrap.Popover.getInstance(el);
                if (popoverInstance && !el.contains(e.target)) {
                    popoverInstance.hide();
                }
            });
        });
    });

    $(document).ready(function () {
        // Inisialisasi DataTable segera (meski tab tersembunyi) agar pagination terbentuk
        if ($.fn.DataTable && document.getElementById('table-absensi')) {
            if ($.fn.DataTable.isDataTable('#table-absensi')) {
                $('#table-absensi').DataTable().destroy();
            }
            var absensiTable = $('#table-absensi').DataTable({
                "pageLength": 5,
                "ordering": true,
                "info": true,
                "searching": false,
                "lengthChange": false,
                "stateSave": false,
                "language": {
                    "emptyTable": "Tidak ada data tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 data",
                    "paginate": {
                        "first": "«", "last": "»",
                        "next": "›", "previous": "‹"
                    }
                },
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm mt-3');
                }
            });

            // Saat tab absensi dibuka, adjust kolom agar pagination dan layout benar
            $(document).on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href') || $(e.target).attr('data-bs-target');
                if (target === '#absensi') {
                    absensiTable.columns.adjust().draw();
                }
            });
        }
    });

    function confirmDeleteAttendance(id, renovationId) {
        Swal.fire({
            title: 'Hapus Absensi?',
            text: "Data absensi ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/renovation/delete-attendance') ?>/" + id + "/" + renovationId;
            }
        });
    }
</script>

<style>
    /* Kunci lebar kolom deskripsi agar tidak stretch */
    #table-absensi .desc-cell {
        max-width: 220px;
        min-width: 160px;
    }

    /* ===== POPOVER DESKRIPSI - Premium Style ===== */
    /* Bootstrap 5: customClass ditaruh langsung ke .popover */
    .desc-popover {
        max-width: 340px !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
        overflow: hidden;
    }

    .desc-popover .popover-arrow::before,
    .desc-popover .popover-arrow::after {
        border-left-color: #fff !important;
    }

    .desc-popover .popover-header {
        background: linear-gradient(135deg, #6777ef 0%, #4a5ce7 100%) !important;
        color: #fff !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        padding: 10px 14px !important;
        border-bottom: none !important;
        letter-spacing: 0.3px;
    }

    .desc-popover .popover-body {
        font-size: 0.82rem;
        line-height: 1.65;
        color: #495057;
        white-space: pre-wrap;
        word-break: break-word;
        padding: 12px 14px !important;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
    }

    .desc-popover .popover-body::-webkit-scrollbar {
        width: 4px;
    }

    .desc-popover .popover-body::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 4px;
    }

    /* Tombol selengkapnya */
    .desc-popover-btn:focus {
        box-shadow: none;
        outline: none;
    }

    .desc-popover-btn:hover .badge {
        background: #f1f3f9 !important;
        color: #6777ef !important;
        border-color: rgba(103, 119, 239, 0.3) !important;
        transition: all 0.2s ease;
    }

    /* DataTables */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.2rem 0.5rem !important;
        margin-left: 2px !important;
    }

    .dataTables_wrapper .dataTables_info {
        float: left;
        padding-top: 15px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        padding-top: 15px;
    }
    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000000 !important;
        border-radius: 16px;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.8) !important;
        max-width: 850px !important;
    }
    .glightbox-video-slide .gslide-inner-content {
        background: transparent !important;
    }
    .glightbox-video-slide .gslide-description {
        background: rgba(0, 0, 0, 0.85) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 20px !important;
    }
    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
    }
</style>