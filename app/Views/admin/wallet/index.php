<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Saldo Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Saldo Mitra Tukang
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
                <h4>Daftar Saldo Mitra Tukang</h4>
                <div class="card-header-action d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon, role...">
                        <div class="input-group-append">
                            <span class="input-group-text" style="height: 32px;">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <a href="<?= base_url('admin/wallet/withdrawals') ?>" class="btn btn-warning">Cek Permintaan Tarik Dana</a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md table-hover" id="table-1">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">Nama Tukang</th>
                                <th class="text-center">No. HP</th>
                                <th class="text-center">Saldo Saat Ini</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            <?php foreach($tukang as $t): ?>
                            <tr>
                                <td><?= $t['name'] ?></td>
                                <td><?= $t['phone'] ?></td>
                                <td class="font-weight-bold text-success">Rp <?= number_format($t['balance'], 0, ',', '.') ?></td>
                                <td>
                                    <button class=" my-2 btn btn-primary btn-sm" data-toggle="modal" data-target="#modalSaldo<?= $t['id'] ?>">Kelola Saldo</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modals diletakkan di luar table untuk menghindari bug DOM HTML -->
        <?php foreach($tukang as $t): ?>
        <div class="modal fade" id="modalSaldo<?= $t['id'] ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('admin/wallet/update-balance') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header"><h5>Kelola Saldo: <?= $t['name'] ?></h5></div>
                        <div class="modal-body">
                            <input type="hidden" name="tukang_id" value="<?= $t['id'] ?>">
                            <div class="form-group">
                                <label>Jenis Transaksi</label>
                                <select name="type" class="form-control">
                                    <option value="income">Tambah Saldo (Upah/Bonus)</option>
                                    <option value="withdraw">Potong Saldo (Denda/Admin)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nominal (Rp)</label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Contoh: Upah Proyek ID #12"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                                <span class="ladda-label">Simpan Transaksi</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>

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
            { "sortable": false, "targets": [0,1,2,3] } // Foto dan Aksi tidak bisa di-sort
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

    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di form modal)
    $(document).on('submit', 'form', function(e) {
        var form = this;
        var btn = $(form).find('.ladda-button');
        if (btn.length > 0) {
            // Mencegah submit langsung agar UI thread bisa merender animasi Ladda terlebih dahulu
            e.preventDefault();
            var l = Ladda.create(btn[0]);
            l.start();
            
            // Lanjutkan eksekusi form submit native secara asinkron
            setTimeout(function() {
                form.submit();
            }, 100);
        }
    });
});
</script>
<?= $this->endSection() ?>