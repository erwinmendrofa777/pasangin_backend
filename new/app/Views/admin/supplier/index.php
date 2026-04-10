<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Supplier<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">

        <!-- Notifikasi Sukses -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4>Daftar Supplier</h4>
                <div class="card-header-action">
                    <!-- Tombol Tambah Baru -->
                    <a href="<?= base_url('admin/suppliers/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Email</th>
                                <th>Kontak Person</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suppliers as $key => $supplier) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= esc($supplier['name']) ?></td>
                                    <td><?= esc($supplier['email']) ?></td>
                                    <td><?= esc($supplier['contact_person']) ?></td>
                                    <td><?= esc($supplier['phone']) ?></td>
                                    <td><?= esc($supplier['address']) ?></td>
                                    <td>
                                        <?php
                                        $status = $supplier['status'];
                                        $badge_class = '';
                                        switch ($status) {
                                            case 'approved':
                                                $badge_class = 'badge-success';
                                                break;
                                            case 'pending':
                                                $badge_class = 'badge-warning';
                                                break;
                                            case 'rejected':
                                            case 'banned':
                                                $badge_class = 'badge-danger';
                                                break;
                                            default:
                                                $badge_class = 'badge-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                                    </td>
                                    
                                    <!-- ====================================================== -->
                                    <!-- INI BAGIAN YANG DIPERBAIKI (TOMBOL AKSI DINAMIS)      -->
                                    <!-- ====================================================== -->
                                    <td class="text-center">
                                        <?php if ($supplier['status'] === 'pending') : ?>
                                            <!-- Form untuk tombol SETUJUI -->
                                            <form action="<?= route_to('admin.supplier.update_status', $supplier['id'], 'approved') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-icon icon-left btn-success btn-sm" onclick="return confirm('Anda yakin ingin MENYETUJUI supplier ini?')">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            <!-- Form untuk tombol TOLAK -->
                                            <form action="<?= route_to('admin.supplier.update_status', $supplier['id'], 'rejected') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-icon icon-left btn-danger btn-sm" onclick="return confirm('Anda yakin ingin MENOLAK supplier ini?')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        <?php elseif ($supplier['status'] === 'approved') : ?>
                                            <!-- Form untuk tombol BLOKIR -->
                                            <form action="<?= route_to('admin.supplier.update_status', $supplier['id'], 'banned') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-icon icon-left btn-danger btn-sm" onclick="return confirm('Anda yakin ingin MEMBLOKIR supplier ini?')">
                                                    <i class="fas fa-ban"></i> Blokir
                                                </button>
                                            </form>
                                            <!-- Tombol Edit -->
                                            <a href="/admin/suppliers/edit/<?= $supplier['id'] ?>" class="btn btn-icon icon-left btn-warning btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <?php else : // Untuk status 'rejected' atau 'banned' ?>
                                            <!-- Form untuk tombol AKTIFKAN KEMBALI -->
                                            <form action="<?= route_to('admin.supplier.update_status', $supplier['id'], 'approved') ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-icon icon-left btn-info btn-sm" onclick="return confirm('Anda yakin ingin MENGAKTIFKAN KEMBALI supplier ini?')">
                                                    <i class="fas fa-sync-alt"></i> Aktifkan
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
    // Inisialisasi DataTable
    $("#table-1").dataTable();
</script>
<?= $this->endSection() ?>

