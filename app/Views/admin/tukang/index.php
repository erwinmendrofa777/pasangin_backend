<?= $this->extend('layout/app'); ?>

<?= $this->section('title') ?>
 Manajemen Tukang 
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
 Daftar Mitra Tukang 
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
            <div class="card-header bg-white d-flex gap-2 justify-content-between">
                <h4>Data Statistik & Status Mitra</h4>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon, role...">
                    <div class="input-group-append">
                        <span class="input-group-text" style="height: 32px;">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-md" id="table-1">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" style="width:30%">Nama & Spesialisasi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">total rating</th>
                                <th class="text-center">score Skill</th>
                                <th class="text-center">score Behavior</th>
                                <th class="text-center" style="width:10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tukang as $key => $row) : ?>
                                <tr class="text-center align-middle">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td>
                                        <div class="font-weight-bold text-dark"><?= esc($row['name']) ?></div>
                                        <div class="text-small text-muted"><?= esc($row['specialization'] ?: 'Umum') ?></div>
                                    </td>
                                    <td>
                                        <form action="<?= base_url('admin/tukang/update-stats') ?>" method="post">
                                        <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="Berkas Diproses" <?= $row['status'] == 'Berkas Diproses' ? 'selected' : '' ?>>Berkas Diproses</option>
                                                <option value="Ditolak" <?= $row['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                                <option value="Proses Test" <?= $row['status'] == 'Proses Test' ? 'selected' : '' ?>>Proses Test</option>
                                                <option value="Proses Aktivasi" <?= $row['status'] == 'Proses Aktivasi' ? 'selected' : '' ?>>Proses Aktivasi</option>
                                                <option value="Siap Kerja" <?= $row['status'] == 'Siap Kerja' ? 'selected' : '' ?>>Siap Kerja</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center"><?= $row['rata_rata_rating'] ?></td>
                                    <td class="text-center"><?= $row['skill_score'] ?></td>
                                    <td class="text-center"><?= $row['behavior_score'] ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>" class="btn btn-info btn-sm" title="Detail Lengkap">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-whitesmoke text-muted small">
                <i class="fas fa-info-circle"></i> <strong>Tips:</strong> Tekan tombol biru (ikon save) untuk menyimpan perubahan skor pada masing-masing tukang kawan.
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
            { "sortable": false, "targets": [1,2,3,4,5,6] } // Foto dan Aksi tidak bisa di-sort
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