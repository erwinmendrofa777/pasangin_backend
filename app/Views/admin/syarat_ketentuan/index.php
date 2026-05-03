<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>

<?php
/**
 * Extract plain text preview from Editor.js JSON string.
 * Returns up to $maxLen characters from the first text-bearing block.
 */
function editorPreview(?string $json, int $maxLen = 150): string {
    if (!$json) return '-';
    $data = json_decode($json, true);
    if (!$data || !isset($data['blocks'])) return esc(mb_strimwidth($json, 0, $maxLen, '...'));
    $texts = [];
    foreach ($data['blocks'] as $block) {
        $type = $block['type'] ?? '';
        if (in_array($type, ['paragraph', 'header'])) {
            $texts[] = strip_tags($block['data']['text'] ?? '');
        } elseif ($type === 'list') {
            foreach (($block['data']['items'] ?? []) as $item) {
                $texts[] = '• ' . strip_tags($item);
            }
        }
    }
    $preview = implode(' ', $texts);
    return esc(mb_strimwidth($preview, 0, $maxLen, '...'));
}
?>

<?= $this->section('style') ?>
<style>
    /* Styling Tabs Premium */
    .nav-pills.custom-pills .nav-link {
        color: #495057;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        margin-right: 8px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 1px solid transparent;
    }

    .nav-pills.custom-pills .nav-link:hover {
        background: #e9ecef;
    }

    .nav-pills.custom-pills .nav-link.active {
        background: #6777EF;
        color: #fff;
        box-shadow: 0 4px 10px rgba(103, 119, 239, 0.3);
    }

    /* Table Styling */
    .table-custom {
        border-spacing: 0;
        border-collapse: separate;
    }

    .table-custom thead th {
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

    .table-custom thead tr {
        background: #f0f6ff;
    }

    .table-custom tbody tr {
        transition: all 0.3s;
    }

    .table-custom tbody tr:hover {
        background-color: #f8f9fc;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-custom td {
        vertical-align: middle;
        padding: 15px 12px;
        border-bottom: 1px solid #e3e6f0;
    }

    .desc-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm rounded-lg" style="border: none;">
            <div class="card-header d-flex justify-content-between align-items-center bg-white pt-4 pb-3" style="border-bottom: 1px solid #f0f0f0;">
                <h4 class="m-0 text-primary fw-bold"><i class="fas fa-file-contract me-2"></i>Kelola Syarat & Ketentuan</h4>
                <?php if (can('syarat_ketentuan_create')): ?>
                <a href="<?= base_url('admin/syarat_ketentuan/create') ?>" class="btn btn-primary btn-sm px-3 py-2" style="border-radius: 8px;">
                    <i class="fas fa-plus me-1"></i> Tambah Baru
                </a>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-pills custom-pills mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-client-tab" data-toggle="pill" href="#pills-client" role="tab" aria-controls="pills-client" aria-selected="true" style="border:none;">
                            <i class="fas fa-user me-2"></i>Aplikasi Client
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-tukang-tab" data-toggle="pill" href="#pills-tukang" role="tab" aria-controls="pills-tukang" aria-selected="false" style="border:none;">
                            <i class="fas fa-tools me-2"></i>Aplikasi Tukang
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-supplier-tab" data-toggle="pill" href="#pills-supplier" role="tab" aria-controls="pills-supplier" aria-selected="false" style="border:none;">
                            <i class="fas fa-store me-2"></i>Aplikasi Supplier
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-proyek-tab" data-toggle="pill" href="#pills-proyek" role="tab" aria-controls="pills-proyek" aria-selected="false" style="border:none;">
                            <i class="fas fa-store me-2"></i>Proyek
                        </a>
                    </li>
                </ul>

                <!-- Tab Contents -->
                <div class="tab-content" id="pills-tabContent">

                    <!-- TAB CLIENT -->
                    <div class="tab-pane fade show active" id="pills-client" role="tabpanel" aria-labelledby="pills-client-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-client">
                                <thead>
                                    <tr>
                                        <th class="text-center text-primary fw-bold" width="5%">No</th>
                                        <th class="text-primary fw-bold" width="25%">Judul T&C</th>
                                        <th class="text-primary fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center text-primary fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($client_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan untuk Aplikasi Client</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($client_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td class="py-2">
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (can('syarat_ketentuan_update')): ?>
                                                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>" class="btn btn-light btn-sm text-primary shadow-sm rounded" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (can('syarat_ketentuan_delete')): ?>
                                                    <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>" class="btn btn-light btn-sm text-danger shadow-sm rounded ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;" data-toggle="tooltip" title="Hapus">
                                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                    </a>
                                                    <?php endif; ?>

                                                    <?php if (!can('syarat_ketentuan_update') && !can('syarat_ketentuan_delete')): ?>
                                                    <span class="badge badge-light"><i class="fas fa-lock"></i></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB TUKANG -->
                    <div class="tab-pane fade" id="pills-tukang" role="tabpanel" aria-labelledby="pills-tukang-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-tukang">
                                <thead>
                                    <tr>
                                        <th class="text-center text-primary fw-bold" width="5%">No</th>
                                        <th class="text-primary fw-bold" width="25%">Judul T&C</th>
                                        <th class="text-primary fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center text-primary fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tukang_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan untuk Aplikasi Tukang</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($tukang_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>" class="btn btn-light btn-sm text-primary shadow-sm rounded" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>" class="btn btn-light btn-sm text-danger shadow-sm rounded ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;" data-toggle="tooltip" title="Hapus">
                                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB SUPPLIER -->
                    <div class="tab-pane fade" id="pills-supplier" role="tabpanel" aria-labelledby="pills-supplier-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-supplier">
                                <thead>
                                    <tr>
                                        <th class="text-center text-primary fw-bold" width="5%">No</th>
                                        <th class="text-primary fw-bold" width="25%">Judul T&C</th>
                                        <th class="text-primary fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center text-primary fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($supplier_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan untuk Aplikasi Supplier</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($supplier_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>" class="btn btn-light btn-sm text-primary shadow-sm rounded" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>" class="btn btn-light btn-sm text-danger shadow-sm rounded ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;" data-toggle="tooltip" title="Hapus">
                                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB PROYEK -->
                    <div class="tab-pane fade" id="pills-proyek" role="tabpanel" aria-labelledby="pills-proyek-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-proyek">
                                <thead>
                                    <tr>
                                        <th class="text-center text-primary fw-bold" width="5%">No</th>
                                        <th class="text-primary fw-bold" width="25%">Judul T&C</th>
                                        <th class="text-primary fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center text-primary fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($proyek_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan untuk Proyek</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($proyek_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>" class="btn btn-light btn-sm text-primary shadow-sm rounded" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>" class="btn btn-light btn-sm text-danger shadow-sm rounded ladda-button" data-style="zoom-in" onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;" data-toggle="tooltip" title="Hapus">
                                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {

        /* ===== Flash Messages ===== */
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

        $('[data-toggle="tooltip"]').tooltip();

        // Handling active pills behavior explicitly
        $('.nav-pills .nav-link').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });

    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
</script>
<?= $this->endSection() ?>