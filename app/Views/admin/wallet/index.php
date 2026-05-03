<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Saldo Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Saldo Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 45%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 44px !important;
        border-radius: 12px !important;
        border: 1.5px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.88rem;
        height: 42px;
        width: 400px;
    }

    .search-wrapper input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #adb5bd;
        opacity: 0.8;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f6ff;
    }

    #table-1 thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #f8fbff !important;
    }

    #table-1 tbody td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px;
        border-top: 1px solid #f0f4fa;
        background: #fafcff;
    }

    .dataTables_info {
        font-size: 0.82rem;
        color: #6c757d !important;
    }

    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }

    .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #e7f0ff !important;
        border-color: #e7f0ff !important;
        color: #0d6efd !important;
    }

    mark {
        background-color: #dbeafe;
        color: #1d4ed8;
        padding: 1px 3px;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .search-wrapper input {
            width: 100%;
        }

        .search-wrapper .search-icon {
            top: 50%;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
            padding: 16px !important;
        }

        .header-actions {
            width: 100% !important;
        }

        .search-wrapper {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dt-footer {
            flex-direction: column;
            gap: 12px;
            padding: 16px !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header: Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header" style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center" style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-wallet me-2"></i>Daftar Saldo
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput"
                    placeholder="Cari nama, No HP...">
            </div>
            <?php if (can('wallet_withdraw_request')): ?>
                <a href="<?= base_url('admin/wallet/withdrawals') ?>" class="btn btn-warning d-flex align-items-center justify-content-center text-nowrap mt-2 mt-md-0" style="border-radius: 12px; font-size: 0.88rem; padding: 5px 16px; color: #fff;">
                    <i class="fas fa-file-invoice-dollar me-1"></i> Tarik Dana
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center">Nama Tukang</th>
                        <th class="text-center">No. HP</th>
                        <th class="text-center">Saldo Saat Ini</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tukang as $key => $t): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="fw-semibold text-start ps-3"><?= esc($t['name']) ?></td>
                            <td class="text-muted"><?= esc($t['phone'] ?: '-') ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($t['balance'], 0, ',', '.') ?></td>
                            <td>
                                <?php if (can('wallet_manage')): ?>
                                    <button class="btn btn-primary btn-sm my-1" style="border-radius: 8px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#modalSaldo<?= $t['id'] ?>">
                                        <i class="fas fa-edit me-1"></i>Kelola Saldo
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted"><i class="fas fa-lock me-1"></i> No Access</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals diletakkan di luar table untuk menghindari bug DOM HTML -->
<?php foreach ($tukang as $t): ?>
    <div class="modal fade" id="modalSaldo<?= $t['id'] ?>" tabindex="-1" aria-labelledby="modalSaldoLabel<?= $t['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('admin/wallet/update-balance') ?>" method="post" class="w-100">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="background: #f8f9fa; border-bottom: 1px solid #e9ecef; border-radius: 16px 16px 0 0; padding: 16px 20px;">
                        <h6 class="modal-title fw-bold text-primary mb-0" id="modalSaldoLabel<?= $t['id'] ?>">
                            <i class="fas fa-wallet me-2"></i>Kelola Saldo: <?= esc($t['name']) ?>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="tukang_id" value="<?= $t['id'] ?>">

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Jenis Transaksi</label>
                            <select name="type" class="form-select" style="border-radius: 10px; border: 1.5px solid #dee2e6;">
                                <option value="income">Tambah Saldo (Upah/Bonus)</option>
                                <option value="withdraw">Potong Saldo (Denda/Admin)</option>
                            </select>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control" style="border-radius: 10px; border: 1.5px solid #dee2e6;" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.85rem;">Keterangan</label>
                            <textarea name="description" class="form-control" rows="3" style="border-radius: 10px; border: 1.5px solid #dee2e6;" placeholder="Contoh: Upah Proyek ID #12"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 16px 20px;">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold ladda-button" data-style="zoom-in" style="border-radius: 10px;">
                            <span class="ladda-label">Simpan Transaksi</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    $(document).ready(function() {
        // Konfigurasi DataTables
        var table = $('#table-1').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "emptyTable": "Tidak ada data yang tersedia",
                "zeroRecords": "Tidak ada data yang cocok ditemukan"
            },
            "columnDefs": [{
                "sortable": false,
                "targets": [4]
            }],
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>'
        });

        // Hubungkan search input custom dengan DataTables search
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Clear search when input is cleared
        $('#searchInput').on('search', function() {
            if (this.value === '') {
                table.search('').draw();
            }
        });

        // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di form modal)
        $(document).on('submit', 'form', function(e) {
            var form = this;
            var btn = $(form).find('.ladda-button');
            if (btn.length > 0) {
                e.preventDefault();
                var l = Ladda.create(btn[0]);
                l.start();

                setTimeout(function() {
                    form.submit();
                }, 100);
            }
        });
    });
</script>
<?= $this->endSection() ?>