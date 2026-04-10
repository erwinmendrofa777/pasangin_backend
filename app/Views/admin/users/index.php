<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola User
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
kelola user
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
                <h4>Daftar User</h4>
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
                                <th class="text-center">Foto User</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Nomor Telepon</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $key => $row): ?>
                            <tr class="text-center align-middle">
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <?php if (strpos($row['avatar'], 'http') === 0): ?>
                                        <img src="<?= $row['avatar'] ?>" width="50" class="rounded-circle" style="object-fit: cover; object-position: center; width:50px; height: 50px;" data-toggle="tooltip" title="<?= $row['full_name'] ?>">
                                    <?php elseif(!empty($row['avatar'])): ?>
                                        <img alt="image" src="<?= base_url('uploads/profile/'.$row['avatar']) ?>" class="rounded-circle" style="object-fit: cover; object-position: center; width:50px; height: 50px;" data-toggle="tooltip" title="<?= $row['full_name'] ?>">
                                    <?php else: ?>
                                        <img alt="image" src="<?= base_url('uploads/profile/default.jpg') ?>" class="rounded-circle" style="object-fit: cover; object-position: center; width:50px; height: 50px;" data-toggle="tooltip" title="<?= $row['full_name'] ?>">
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['full_name'] ?: '-' ?></td>
                                <td><?= $row['email'] ?: '-' ?></td>
                                <td><?= $row['phone_number'] ?: '-' ?></td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $badge_class = '';
                                    switch ($status) {
                                        case 'approved':
                                            $badge_class = 'badge-success';
                                            break;
                                        case 'pending':
                                            $badge_class = 'badge text-white text-bg-warning';
                                            break;
                                        case 'rejected':
                                        case 'banned':
                                            $badge_class = 'badge-danger';
                                            break;
                                        default:
                                            $badge_class = 'badge-secondary';
                                    }
                                    ?>
                                    <span class="badge my-2 <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                                </td>

                                <!-- ====================================================== -->
                                <!-- INI BAGIAN YANG DIPERBAIKI (TOMBOL AKSI DINAMIS)      -->
                                <!-- ====================================================== -->
                                <td class="text-center">
                                    <?php if ($row['status'] === 'pending') : ?>
                                        <!-- Form untuk tombol SETUJUI -->
                                        <form action="<?= route_to('admin.users.update_status', $row['id'], 'approved') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-icon icon-left btn-success btn-sm ladda-button" data-style="zoom-in" onclick="return confirm('Anda yakin ingin MENYETUJUI row ini?')">
                                                <span class="ladda-label"><i class="fas fa-check"></i> Setujui</span>
                                            </button>
                                        </form>
                                        <!-- Form untuk tombol TOLAK -->
                                        <form action="<?= route_to('admin.users.update_status', $row['id'], 'rejected') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-icon icon-left btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="return confirm('Anda yakin ingin MENOLAK row ini?')">
                                                <span class="ladda-label"><i class="fas fa-times"></i> Tolak</span>
                                            </button>
                                        </form>
                                    <?php elseif ($row['status'] === 'approved') : ?>
                                        <!-- Form untuk tombol BLOKIR -->
                                        <form action="<?= route_to('admin.users.update_status', $row['id'], 'banned') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-icon icon-left btn-danger btn-sm ladda-button" data-style="zoom-in" onclick="return confirm('Anda yakin ingin MEMBLOKIR row ini?')">
                                                <span class="ladda-label"><i class="fas fa-ban"></i> Blokir</span>
                                            </button>
                                        </form>
                                        <!-- Tombol Edit -->
                                        <a href="/admin/users/edit/<?= $row['id'] ?>" class="btn btn-icon icon-left btn-warning btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <?php else : // Untuk status 'rejected' atau 'banned' ?>
                                        <!-- Form untuk tombol AKTIFKAN KEMBALI -->
                                        <form action="<?= route_to('admin.users.update_status', $row['id'], 'approved') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-icon icon-left btn-info btn-sm ladda-button" data-style="zoom-in" onclick="return confirm('Anda yakin ingin MENGAKTIFKAN KEMBALI row ini?')">
                                                <span class="ladda-label"><i class="fas fa-sync-alt"></i> Aktifkan</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
            { "sortable": false, "targets": [1,3,4,6] } // Foto dan Aksi tidak bisa di-sort
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
