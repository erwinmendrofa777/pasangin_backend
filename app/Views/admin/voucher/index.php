<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Voucher &mdash; Madinah
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Voucher Diskon
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
                <h4>Daftar Voucher</h4>
                <div class="card-header-action d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon, role...">
                        <div class="input-group-append">
                            <span class="input-group-text" style="height: 32px;">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <a href="<?= base_url('admin/vouchers/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Baru
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md table-hover" id="table-1">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Gambar</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Diskon</th>
                                <th class="text-center">Berlaku Sampai</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            <?php foreach($vouchers as $key => $row): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td>
                                    <img alt="image" data-toggle="tooltip" title="<?= $row['name'] ?>" src="<?= base_url('uploads/vouchers/'.$row['image']) ?>" class="rounded my-2" style="object-fit: cover; object-position: center; width:100px; height: 100px;" onerror="this.src='https://placehold.co/80x50?text=No+Image'">
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="letter-spacing: 1px;"><?= $row['code'] ?></span>
                                </td>
                                <td class="font-weight-600"><?= $row['name'] ?></td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        Rp <?= number_format($row['discount_nominal'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y', strtotime($row['valid_until'])) ?>
                                </td>
                                <td>
                                    <?php if($row['is_active'] == 1): ?>
                                        <div class="badge badge-success">Aktif</div>
                                    <?php else: ?>
                                        <div class="badge badge-danger">Non-Aktif</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/vouchers/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus?')) { Ladda.create(this).start(); return true; } return false;">
                                        <span class="ladda-label"><i class="fas fa-trash"></i> Hapus</span>
                                    </a>
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
            { "sortable": false, "targets": [1,2,3,4,5,6,7] } // Foto dan Aksi tidak bisa di-sort
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

    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
});
</script>
<?= $this->endSection() ?>