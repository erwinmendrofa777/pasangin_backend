<?= $this->extend('layout/app'); ?>

<?= $this->section('title') ?>
Permohonan Renovasi 
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
 Daftar Permohonan Renovasi 
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* Custom styling for search input */
    #searchInput {
        border-radius: 5px 0 0 5px;
        border-right: none;
    }
    
    #searchInput:focus {
        box-shadow: none;
        border-color: #6777ef;
    }
    
    .input-group-text {
        border-radius: 0 5px 5px 0;
        border-left: none;
        background-color: #6777ef;
        color: white;
        border-color: #6777ef;
    }
    
    /* Highlight search results */
    mark {
        background-color: #fffbdd;
        color: #856404;
        padding: 1px 2px;
        border-radius: 2px;
    }
    
    /* DataTables custom styling */
    .dataTables_length select {
        background-color: #fff;
        border: 1px solid #e4e6fc;
        border-radius: 5px;
        padding: 5px 10px;
    }
    
    .dataTables_info {
        color: #6c757d;
        font-size: 14px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header-action {
            flex-direction: column;
            gap: 10px;
        }
        
        .card-header-action .input-group {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">

        <div class="card shadow">
            <div class="card-header">
                <h4>Data Permohonan Masuk</h4>
                <div class="card-header-action d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon, role...">
                        <div class="input-group-append">
                            <span class="input-group-text" style="height: 32px;">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md table-hover" id="table-1">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pelanggan</th>
                                <th class="text-center">Info Pengajuan</th>
                                <th class="text-center">Total Biaya</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            <?php foreach ($requests as $key => $row) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <!-- PERBAIKAN: Menggunakan 'client_name' dari hasil JOIN di controller -->
                                        <span class="font-weight-bold"><?= esc($row['client_name'] ?? 'Klien tidak ditemukan') ?></span>
                                        <div class="text-small text-muted"><?= esc($row['phone_number'] ?? 'No. HP tidak ada') ?></div>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt text-muted"></i> <?= date('d M Y', strtotime($row['created_at'])) ?><br>
                                        <small class="text-primary"><?= esc($row['renovation_type']) ?></small>
                                    </td>
                                    <td>
                                        Rp <?= number_format($row['total_payment'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status = strtoupper($row['status']);
                                        $badgeColor = 'badge-secondary';
                                        if ($status == 'PENDING') $badgeColor = 'text-bg-warning text-white';
                                        elseif (in_array($status, ['SURVEY', 'DESIGN', 'RAB', 'DEAL','PROGRESS'])) $badgeColor = 'badge-info';
                                        elseif ($status == 'DONE') $badgeColor = 'badge-success';
                                        elseif ($status == 'CANCEL') $badgeColor = 'badge-danger';
                                        ?>
                                        <div class="badge my-2 <?= $badgeColor ?>"><?= esc(ucwords(str_replace('_', ' ', $row['status']))) ?></div>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/renovation/detail/' . $row['id']) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

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
    // end konfigurasi

$(document).ready(function() {
    // Konfigurasi DataTables dengan fitur search yang enhanced
    var table = $('#table-1').DataTable({
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
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
        "columnDefs": [
            { "sortable": false, "targets": [1,2,3,5] } // Foto dan Aksi tidak bisa di-sort
        ],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": 'rt<"d-flex justify-content-between mt-3"ip>', // Hide default search, show only table, length, pagination
        "drawCallback": function(settings) {
            // Re-initialize tooltips after table redraw
            $('[data-toggle="tooltip"]').tooltip();
        }
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

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>
