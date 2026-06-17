<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <!-- Nav Tabs Section with padding and border-bottom -->
            <div class="p-4 border-bottom" style="border-color: #f0f4fa !important;">
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-client-tab" data-toggle="pill" href="#pills-client"
                            role="tab" aria-controls="pills-client" aria-selected="true">
                            <i class="fas fa-user me-2"></i>Aplikasi Client
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-tukang-tab" data-toggle="pill" href="#pills-tukang" role="tab"
                            aria-controls="pills-tukang" aria-selected="false">
                            <i class="fas fa-tools me-2"></i>Aplikasi Tukang
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-supplier-tab" data-toggle="pill" href="#pills-supplier" role="tab"
                            aria-controls="pills-supplier" aria-selected="false">
                            <i class="fas fa-store me-2"></i>Aplikasi Supplier
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-proyek-tab" data-toggle="pill" href="#pills-proyek" role="tab"
                            aria-controls="pills-proyek" aria-selected="false">
                            <i class="fas fa-briefcase me-2"></i>Proyek
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <!-- Tab Contents -->
                <div class="tab-content" id="pills-tabContent">

                    <!-- TAB CLIENT -->
                    <div class="tab-pane fade show active pb-0" id="pills-client" role="tabpanel"
                        aria-labelledby="pills-client-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-client">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold" width="5%">No</th>
                                        <th class="fw-bold" width="25%">Judul T&C</th>
                                        <th class="fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($client_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan
                                                untuk Aplikasi Client</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($client_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td class="py-2">
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (can('syarat_ketentuan_update')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-edit me-1" data-toggle="tooltip"
                                                            title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (can('syarat_ketentuan_delete')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-circle-delete ladda-button"
                                                            data-style="zoom-in"
                                                            onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                            data-toggle="tooltip" title="Hapus">
                                                            <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
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
                    <div class="tab-pane fade pb-0" id="pills-tukang" role="tabpanel"
                        aria-labelledby="pills-tukang-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-tukang">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold" width="5%">No</th>
                                        <th class="fw-bold" width="25%">Judul T&C</th>
                                        <th class="fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tukang_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan
                                                untuk Aplikasi Tukang</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($tukang_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (can('syarat_ketentuan_update')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-edit me-1" data-toggle="tooltip"
                                                            title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (can('syarat_ketentuan_delete')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-circle-delete ladda-button"
                                                            data-style="zoom-in"
                                                            onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                            data-toggle="tooltip" title="Hapus">
                                                            <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
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

                    <!-- TAB SUPPLIER -->
                    <div class="tab-pane fade pb-0" id="pills-supplier" role="tabpanel"
                        aria-labelledby="pills-supplier-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-supplier">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold" width="5%">No</th>
                                        <th class="fw-bold" width="25%">Judul T&C</th>
                                        <th class="fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($supplier_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan
                                                untuk Aplikasi Supplier</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($supplier_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (can('syarat_ketentuan_update')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-edit me-1" data-toggle="tooltip"
                                                            title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (can('syarat_ketentuan_delete')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-circle-delete ladda-button"
                                                            data-style="zoom-in"
                                                            onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                            data-toggle="tooltip" title="Hapus">
                                                            <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
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

                    <!-- TAB PROYEK -->
                    <div class="tab-pane fade pb-0" id="pills-proyek" role="tabpanel"
                        aria-labelledby="pills-proyek-tab">
                        <div class="table-responsive">
                            <table class="table table-custom w-100" id="table-proyek">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold" width="5%">No</th>
                                        <th class="fw-bold" width="25%">Judul T&C</th>
                                        <th class="fw-bold" width="55%">Deskripsi / Konten</th>
                                        <th class="text-center fw-bold" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($proyek_data)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada Syarat & Ketentuan
                                                untuk Proyek</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($proyek_data as $key => $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td class="fw-bold text-dark"><?= esc($row['title']) ?></td>
                                                <td>
                                                    <p class="desc-text text-muted"><?= editorPreview($row['description']) ?>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (can('syarat_ketentuan_update')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-edit me-1" data-toggle="tooltip"
                                                            title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (can('syarat_ketentuan_delete')): ?>
                                                        <a href="<?= base_url('admin/syarat_ketentuan/delete/' . $row['id']) ?>"
                                                            class="btn-circle-action btn-circle-delete ladda-button"
                                                            data-style="zoom-in"
                                                            onclick="if(confirm('Hapus prasyarat ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                            data-toggle="tooltip" title="Hapus">
                                                            <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
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

                </div>
            </div>
        </div>

    </div>
</div>