<?php
// Grouping data
$groups = [];
$noGroup = [];

if (!function_exists('schedRangeLabel')) {
    function schedRangeLabel(int $startWeek, int $endWeek, ?string $startDate, ?int $workday): string
    {
        $workday = ($workday > 0) ? $workday : 7;
        if (!$startDate) {
            return 'Minggu M' . $startWeek . ($startWeek !== $endWeek ? ' - ' . $endWeek : '');
        }
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $d = new \DateTime($startDate);
        $d->modify('+' . (($startWeek - 1) * 7) . ' days');

        $e = new \DateTime($startDate);
        $e->modify('+' . (($endWeek - 1) * 7 + ($workday - 1)) . ' days');

        $mStart = $months[(int)$d->format('n') - 1];
        $mEnd = $months[(int)$e->format('n') - 1];
        
        if ($d->format('Y') === $e->format('Y')) {
            return $d->format('j') . ' ' . $mStart . ' – ' . $e->format('j') . ' ' . $mEnd . ' ' . $e->format('Y');
        } else {
            return $d->format('j') . ' ' . $mStart . ' ' . $d->format('Y') . ' – ' . $e->format('j') . ' ' . $mEnd . ' ' . $e->format('Y');
        }
    }
}

foreach ($tukang as $row) {
    if (!empty($row['group_name'])) {
        $gName = $row['group_name'];
        if (!isset($groups[$gName])) {
            $groups[$gName] = [
                'name_group' => $gName,
                'referral_code' => $row['group_referral_code'] ?? '',
                'mandor' => null,
                'members' => []
            ];
        }
        if ($row['group_status'] === 'owner') {
            $groups[$gName]['mandor'] = $row;
        } else {
            $groups[$gName]['members'][] = $row;
        }
    } else {
        $noGroup[] = $row;
    }
}
?>

<div class="card table-card mb-4 border-0 shadow-sm rounded-4 overflow-hidden"
    style="border: 1px solid rgba(226, 232, 240, 0.8) !important;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="main-group-table"
                style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr class="bg-primary text-white text-center">
                        <th class="ps-4 py-3"
                            style="width: 5%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-top-left-radius: 12px; border-bottom: none;">
                            NO</th>
                        <th class="text-start py-3"
                            style="width: 45%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                            NAMA KELOMPOK / GRUP</th>
                        <th class="text-start py-3"
                            style="width: 20%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                            KODE REFERRAL</th>
                        <th class="py-3"
                            style="width: 15%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: none;">
                            JUMLAH ANGGOTA</th>
                        <th class="py-3"
                            style="width: 15%; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-top-right-radius: 12px; border-bottom: none;">
                            AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rowIdx = 1;
                    foreach ($groups as $gName => $group):
                        $totalMembers = count($group['members']) + ($group['mandor'] ? 1 : 0);
                        $parentId = 'parent-' . md5($gName);
                        $childId = 'child-' . md5($gName);
                        ?>
                        <!-- Group Parent Row -->
                        <tr class="group-parent-row" id="<?= $parentId ?>" data-group-name="<?= esc($gName) ?>"
                            style="cursor: pointer; transition: background 0.2s ease;">
                            <td class="ps-4 fw-bold text-muted text-center"><?= $rowIdx++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 32px; height: 32px; background: rgba(255, 92, 92, 0.08); color: var(--palette-primary); flex-shrink: 0;">
                                        <i class="fas fa-chevron-right transition-icon"
                                            style="font-size: 0.8rem; transition: transform 0.2s ease;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark text-lg"><?= esc($group['name_group']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2"
                                    style="font-size: 0.75rem; font-weight: 600; border-radius: 6px;">
                                    <?= esc($group['referral_code'] ?: '-') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-3 py-2"
                                    style="font-size: 0.72rem; font-weight: 600;">
                                    <?= $totalMembers ?> Anggota
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary px-3"
                                    data-bs-toggle="modal" data-bs-target="#modal-targets-<?= md5($gName) ?>"
                                    style="border-radius: 8px; font-size: 0.75rem; font-weight: 600; height: 34px;">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        <!-- Group Child Row -->
                        <tr class="group-detail-row" id="<?= $childId ?>" data-parent-id="<?= $parentId ?>"
                            style="display: none; background-color: #f8fafc;">
                            <td colspan="5" class="p-4" style="border-bottom: 1px solid #e2e8f0;">
                                <div class="table-responsive rounded-3 border bg-white shadow-sm overflow-hidden">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr class="bg-light text-center">
                                                <th class="py-3"
                                                    style="width: 5%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    No</th>
                                                <th class="py-3"
                                                    style="width: 10%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Foto</th>
                                                <th class="text-start py-3"
                                                    style="width: 30%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Nama</th>
                                                <th class="text-start py-3"
                                                    style="width: 20%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Peran Kelompok</th>
                                                <th class="text-start py-3"
                                                    style="width: 15%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Email & Telepon</th>
                                                <th class="py-3"
                                                    style="width: 15%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Status Akun</th>
                                                <th class="py-3"
                                                    style="width: 5%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $memberIdx = 1;
                                            if ($group['mandor']):
                                                $row = $group['mandor'];
                                                $photoSrc = !empty($row['profile_photo']) ? base_url('uploads/tukang/' . $row['profile_photo']) : base_url('uploads/tukang/default.jpg');
                                                ?>
                                                <tr class="align-middle text-center" data-role="mandor">
                                                    <td class="fw-bold text-muted"><?= $memberIdx++ ?></td>
                                                    <td>
                                                        <a href="<?= $photoSrc ?>" class="glightbox"
                                                            data-title="<?= esc($row['name']) ?>">
                                                            <img src="<?= $photoSrc ?>" class="tukang-avatar"
                                                                alt="<?= esc($row['name']) ?>"
                                                                style="width: 42px; height: 42px; border-radius: 8px;">
                                                        </a>
                                                    </td>
                                                    <td class="text-start">
                                                        <div class="fw-bold text-dark">
                                                            <?= esc($row['name']) ?>
                                                            <span class="badge bg-warning text-dark text-capitalize ms-1"
                                                                style="font-size: 0.65rem;">Mandor</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-start">
                                                        <span class="badge badge-soft-owner px-3 py-2 text-capitalize"
                                                            style="font-size: 0.72rem; font-weight: 600;">
                                                            <i class="fas fa-crown me-1"></i> Mandor / Pemilik
                                                        </span>
                                                    </td>
                                                    <td class="text-start">
                                                        <div class="small text-dark fw-semibold"><i
                                                                class="fas fa-envelope me-1 opacity-50"></i><?= esc($row['email'] ?: '-') ?>
                                                        </div>
                                                        <div class="small text-muted"><i
                                                                class="fas fa-phone me-1 opacity-50"></i><?= esc($row['phone'] ?: '-') ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status = $row['status'];
                                                        $statusClass = 'status-berkas';
                                                        $icon = 'fas fa-file-alt';
                                                        switch ($status) {
                                                            case 'Berkas Diproses':
                                                                $statusClass = 'status-berkas';
                                                                $icon = 'fas fa-file-medical';
                                                                break;
                                                            case 'Ditolak':
                                                                $statusClass = 'status-ditolak';
                                                                $icon = 'fas fa-times-circle';
                                                                break;
                                                            case 'Proses Test':
                                                                $statusClass = 'status-test';
                                                                $icon = 'fas fa-vial';
                                                                break;
                                                            case 'Proses Aktivasi':
                                                                $statusClass = 'status-aktivasi';
                                                                $icon = 'fas fa-user-check';
                                                                break;
                                                            case 'Siap Kerja':
                                                                $statusClass = 'status-siap';
                                                                $icon = 'fas fa-check-double';
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="status-badge <?= $statusClass ?>"><i
                                                                class="<?= $icon ?> me-1"></i> <?= $status ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <?php if (can('tukang')): ?>
                                                                <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>"
                                                                    class="btn-action btn-action-detail"><i
                                                                        class="fas fa-eye"></i></a>
                                                            <?php endif; ?>
                                                            <?php if (can('tukang_delete')): ?>
                                                                <a href="<?= base_url('admin/tukang/delete/' . $row['id']) ?>"
                                                                    class="btn-action btn-action-delete"
                                                                    onclick="return confirm('Hapus data mitra ini?')"><i
                                                                        class="fas fa-trash-alt"></i></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>

                                            <?php
                                            foreach ($group['members'] as $row):
                                                $photoSrc = !empty($row['profile_photo']) ? base_url('uploads/tukang/' . $row['profile_photo']) : base_url('uploads/tukang/default.jpg');
                                                ?>
                                                <tr class="align-middle text-center" data-role="tukang">
                                                    <td class="fw-bold text-muted"><?= $memberIdx++ ?></td>
                                                    <td>
                                                        <a href="<?= $photoSrc ?>" class="glightbox"
                                                            data-title="<?= esc($row['name']) ?>">
                                                            <img src="<?= $photoSrc ?>" class="tukang-avatar"
                                                                alt="<?= esc($row['name']) ?>"
                                                                style="width: 42px; height: 42px; border-radius: 8px;">
                                                        </a>
                                                    </td>
                                                    <td class="text-start">
                                                        <div class="fw-bold text-dark">
                                                            <?= esc($row['name']) ?>
                                                            <span class="badge bg-secondary text-capitalize ms-1"
                                                                style="font-size: 0.65rem;">Tukang</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-start">
                                                        <?php
                                                        $badgeClass = 'badge-soft-pending';
                                                        $badgeText = 'Menunggu Persetujuan';
                                                        $memberIcon = 'fas fa-clock';
                                                        if ($row['group_status'] === 'approved') {
                                                            $badgeClass = 'badge-soft-approved';
                                                            $badgeText = 'Anggota';
                                                            $memberIcon = 'fas fa-check-circle';
                                                        } elseif ($row['group_status'] === 'rejected') {
                                                            $badgeClass = 'badge-soft-rejected';
                                                            $badgeText = 'Ditolak';
                                                            $memberIcon = 'fas fa-times-circle';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $badgeClass ?> px-3 py-2"
                                                            style="font-size: 0.72rem; font-weight: 600;">
                                                            <i class="<?= $memberIcon ?> me-1"></i> <?= $badgeText ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-start">
                                                        <div class="small text-dark fw-semibold"><i
                                                                class="fas fa-envelope me-1 opacity-50"></i><?= esc($row['email'] ?: '-') ?>
                                                        </div>
                                                        <div class="small text-muted"><i
                                                                class="fas fa-phone me-1 opacity-50"></i><?= esc($row['phone'] ?: '-') ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status = $row['status'];
                                                        $statusClass = 'status-berkas';
                                                        $icon = 'fas fa-file-alt';
                                                        switch ($status) {
                                                            case 'Berkas Diproses':
                                                                $statusClass = 'status-berkas';
                                                                $icon = 'fas fa-file-medical';
                                                                break;
                                                            case 'Ditolak':
                                                                $statusClass = 'status-ditolak';
                                                                $icon = 'fas fa-times-circle';
                                                                break;
                                                            case 'Proses Test':
                                                                $statusClass = 'status-test';
                                                                $icon = 'fas fa-vial';
                                                                break;
                                                            case 'Proses Aktivasi':
                                                                $statusClass = 'status-aktivasi';
                                                                $icon = 'fas fa-user-check';
                                                                break;
                                                            case 'Siap Kerja':
                                                                $statusClass = 'status-siap';
                                                                $icon = 'fas fa-check-double';
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="status-badge <?= $statusClass ?>"><i
                                                                class="<?= $icon ?> me-1"></i> <?= $status ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <?php if (can('tukang')): ?>
                                                                <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>"
                                                                    class="btn-action btn-action-detail"><i
                                                                        class="fas fa-eye"></i></a>
                                                            <?php endif; ?>
                                                            <?php if (can('tukang_delete')): ?>
                                                                <a href="<?= base_url('admin/tukang/delete/' . $row['id']) ?>"
                                                                    class="btn-action btn-action-delete"
                                                                    onclick="return confirm('Hapus data mitra ini?')"><i
                                                                        class="fas fa-trash-alt"></i></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Independent Group Row -->
                    <?php
                    $parentId = 'parent-nogroup';
                    $childId = 'child-nogroup';
                    ?>
                    <tr class="group-parent-row" id="<?= $parentId ?>" data-group-name="none"
                        style="cursor: pointer; transition: background 0.2s ease;">
                        <td class="ps-4 fw-bold text-muted text-center"><?= $rowIdx++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 32px; height: 32px; background: rgba(107, 114, 128, 0.08); color: #6b7280; flex-shrink: 0;">
                                    <i class="fas fa-chevron-right transition-icon"
                                        style="font-size: 0.8rem; transition: transform 0.2s ease;"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-muted text-lg">Mitra Tanpa Kelompok / Mandiri</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small italic">-</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill px-3 py-2"
                                style="font-size: 0.72rem; font-weight: 600;">
                                <?= count($noGroup) ?> Mitra
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary px-3"
                                data-bs-toggle="modal" data-bs-target="#modal-targets-nogroup"
                                style="border-radius: 8px; font-size: 0.75rem; font-weight: 600; height: 34px;">
                                <i class="fas fa-eye me-1"></i> Detail
                            </button>
                        </td>
                    </tr>
                    <!-- Independent Child Row -->
                    <tr class="group-detail-row" id="<?= $childId ?>" data-parent-id="<?= $parentId ?>"
                        style="display: none; background-color: #f8fafc;">
                        <td colspan="5" class="p-4" style="border-bottom: 1px solid #e2e8f0;">
                            <div class="table-responsive rounded-3 border bg-white shadow-sm overflow-hidden">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead>
                                        <tr class="bg-light text-center">
                                            <th class="py-3"
                                                style="width: 5%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                No</th>
                                            <th class="py-3"
                                                style="width: 10%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Foto</th>
                                            <th class="text-start py-3"
                                                style="width: 30%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Nama</th>
                                            <th class="text-start py-3"
                                                style="width: 20%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Peran Kelompok</th>
                                            <th class="text-start py-3"
                                                style="width: 15%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Email & Telepon</th>
                                            <th class="py-3"
                                                style="width: 15%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Status Akun</th>
                                            <th class="py-3"
                                                style="width: 5%; font-size: 0.72rem; font-weight: 700; color: #000000 !important; text-transform: uppercase;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $memberIdx = 1;
                                        foreach ($noGroup as $row):
                                            $photoSrc = !empty($row['profile_photo']) ? base_url('uploads/tukang/' . $row['profile_photo']) : base_url('uploads/tukang/default.jpg');
                                            ?>
                                            <tr class="align-middle text-center" data-role="<?= esc($row['role']) ?>">
                                                <td class="fw-bold text-muted"><?= $memberIdx++ ?></td>
                                                <td>
                                                    <a href="<?= $photoSrc ?>" class="glightbox"
                                                        data-title="<?= esc($row['name']) ?>">
                                                        <img src="<?= $photoSrc ?>" class="tukang-avatar"
                                                            alt="<?= esc($row['name']) ?>"
                                                            style="width: 42px; height: 42px; border-radius: 8px;">
                                                    </a>
                                                </td>
                                                <td class="text-start">
                                                    <div class="fw-bold text-dark">
                                                        <?= esc($row['name']) ?>
                                                        <span class="badge bg-secondary text-capitalize ms-1"
                                                            style="font-size: 0.65rem;"><?= esc($row['role'] ?? 'tukang') ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-start">
                                                    <span class="text-muted small italic">
                                                        <i class="fas fa-user-slash me-1 opacity-50"></i> Mandiri
                                                    </span>
                                                </td>
                                                <td class="text-start">
                                                    <div class="small text-dark fw-semibold"><i
                                                            class="fas fa-envelope me-1 opacity-50"></i><?= esc($row['email'] ?: '-') ?>
                                                    </div>
                                                    <div class="small text-muted"><i
                                                            class="fas fa-phone me-1 opacity-50"></i><?= esc($row['phone'] ?: '-') ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status = $row['status'];
                                                    $statusClass = 'status-berkas';
                                                    $icon = 'fas fa-file-alt';
                                                    switch ($status) {
                                                        case 'Berkas Diproses':
                                                            $statusClass = 'status-berkas';
                                                            $icon = 'fas fa-file-medical';
                                                            break;
                                                        case 'Ditolak':
                                                            $statusClass = 'status-ditolak';
                                                            $icon = 'fas fa-times-circle';
                                                            break;
                                                        case 'Proses Test':
                                                            $statusClass = 'status-test';
                                                            $icon = 'fas fa-vial';
                                                            break;
                                                        case 'Proses Aktivasi':
                                                            $statusClass = 'status-aktivasi';
                                                            $icon = 'fas fa-user-check';
                                                            break;
                                                        case 'Siap Kerja':
                                                            $statusClass = 'status-siap';
                                                            $icon = 'fas fa-check-double';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="status-badge <?= $statusClass ?>"><i
                                                            class="<?= $icon ?> me-1"></i> <?= $status ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <?php if (can('tukang')): ?>
                                                            <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>"
                                                                class="btn-action btn-action-detail"><i
                                                                    class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                        <?php if (can('tukang_delete')): ?>
                                                            <a href="<?= base_url('admin/tukang/delete/' . $row['id']) ?>"
                                                                class="btn-action btn-action-delete"
                                                                onclick="return confirm('Hapus data mitra ini?')"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    <div class="card-footer dt-footer d-flex justify-content-between align-items-center" id="table-pagination-footer">
        <div class="dataTables_info" id="pagination-info" role="status" aria-live="polite"></div>
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination mb-0" id="pagination-list"></ul>
        </div>
    </div>
</div>

<!-- Target Modals for Groups -->
<?php foreach ($groups as $gName => $group): ?>
    <div class="modal fade" id="modal-targets-<?= md5($gName) ?>" tabindex="-1" aria-labelledby="modalLabel-<?= md5($gName) ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-3 pt-4 px-4">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3 shadow-sm"
                                 style="width: 42px; height: 42px; background: linear-gradient(135deg, rgba(255, 92, 92, 0.08), rgba(255, 92, 92, 0.18)); color: var(--palette-primary);">
                                <i class="fas fa-tasks" style="font-size: 1.15rem;"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark mb-0" id="modalLabel-<?= md5($gName) ?>" style="font-size: 1.1rem; letter-spacing: -0.2px;">
                                    Target Pekerjaan Kelompok
                                </h5>
                                <span class="text-muted small fw-medium d-block mt-0.5" style="font-size: 0.8rem;">Nama Grup: <strong class="text-primary"><?= esc($group['name_group']) ?></strong></span>
                            </div>
                        </div>
                        <button type="button" class="btn-close-custom shadow-none border-0 rounded-circle d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close"
                                style="width: 32px; height: 32px; background: #f1f5f9; color: #64748b; transition: all 0.2s ease; cursor: pointer; border: none; outline: none;">
                            <i class="fas fa-times" style="font-size: 0.9rem;"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body p-4" style="background-color: #f8fafc; max-height: 60vh; overflow-y: auto;">
                    <div class="target-jobs-container">
                        <?php
                        $targets = $constructionTargets['groups'][$gName] ?? [];
                        if (empty($targets)):
                        ?>
                            <div class="text-center py-5 text-muted bg-white rounded-4 border p-4" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 54px; height: 54px; background: rgba(255, 92, 92, 0.05); color: var(--palette-primary);">
                                    <i class="fas fa-clipboard-list" style="font-size: 1.4rem;"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Target Pekerjaan</h6>
                                <p class="small text-muted mb-0">Kelompok ini belum ditugaskan target pekerjaan konstruksi apa pun.</p>
                            </div>
                        <?php
                        else:
                            foreach ($targets as $t):
                                $statusText = strtoupper($t['status'] ?? 'NOT_STARTED');
                                $borderLeftColor = '#64748b'; // Default slate
                                $statusClass = 'badge-status-notstarted';
                                $statusLabel = 'BELUM MULAI';
                                $statusIcon = 'fas fa-hourglass-start';
                                
                                if ($statusText === 'IN_PROGRESS' || $statusText === 'IN PROGRESS' || $statusText === 'PROGRESS') {
                                    $statusClass = 'badge-status-inprogress';
                                    $statusLabel = 'IN PROGRESS';
                                    $statusIcon = 'fas fa-spinner fa-spin';
                                    $borderLeftColor = '#3b82f6'; // Blue
                                } elseif ($statusText === 'COMPLETED' || $statusText === 'SELESAI' || $statusText === 'ACHIEVED') {
                                    $statusClass = 'badge-status-completed';
                                    $statusLabel = 'SELESAI';
                                    $statusIcon = 'fas fa-check-circle';
                                    $borderLeftColor = '#10b981'; // Green
                                } elseif ($statusText === 'PENDING') {
                                    $statusClass = 'badge-status-pending';
                                    $statusLabel = 'PENDING';
                                    $statusIcon = 'fas fa-clock';
                                    $borderLeftColor = '#f59e0b'; // Amber
                                }
                        ?>
                            <!-- Premium Target Card -->
                            <div class="premium-target-card mb-3 border bg-white position-relative"
                                 style="border-left: 4px solid <?= $borderLeftColor ?> !important; border-color: rgba(226, 232, 240, 0.8) !important; border-radius: 12px; transition: all 0.2s ease; padding: 1.1rem 1.25rem !important;">
                                <div class="row align-items-center g-3">
                                    <!-- Left Side: ID, Title, Address -->
                                    <div class="col-12 col-md-8">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <span class="badge project-card-badge px-2.5 py-1" style="font-size: 0.68rem; font-weight: 700;">
                                                <i class="fas fa-project-diagram me-1" style="font-size: 0.65rem;"></i> Proyek #<?= esc($t['construction_id']) ?>
                                            </span>
                                            <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 0.68rem; font-weight: 600; border-color: #e2e8f0 !important;">
                                                <i class="far fa-calendar-alt text-muted me-1.5" style="font-size: 0.72rem;"></i> <?= schedRangeLabel((int)$t['start_week'], (int)$t['end_week'], $t['start_date'] ?? null, isset($t['workday']) ? (int)$t['workday'] : null) ?>
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.92rem; line-height: 1.4; color: #0f172a !important;">
                                            <?= esc($t['activity_name'] ?: '-') ?>
                                        </h6>
                                        <div class="small text-muted d-flex align-items-start" style="font-size: 0.74rem; line-height: 1.35;">
                                            <i class="fas fa-map-marker-alt text-primary me-2 mt-0.5" style="font-size: 0.75rem; flex-shrink: 0;"></i>
                                            <span><?= esc($t['project_address'] ?: '-') ?></span>
                                        </div>
                                    </div>
                                    <!-- Right Side: Volume & Status -->
                                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-between justify-content-md-end gap-3.5 border-top border-top-md-0 pt-3 pt-md-0"
                                         style="border-color: #f1f5f9 !important;">
                                        <div class="text-start text-md-end me-md-2">
                                            <span class="text-muted d-block small mb-0.5" style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Volume</span>
                                            <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #0f172a !important;">
                                                <?= (float)$t['volume'] ?> <span class="text-muted fw-semibold" style="font-size: 0.78rem;"><?= esc($t['unit'] ?: '-') ?></span>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="badge <?= $statusClass ?> px-3 py-2 d-inline-flex align-items-center gap-1.5" style="font-size: 0.72rem; font-weight: 700; border-radius: 30px;">
                                                <i class="<?= $statusIcon ?>"></i> <?= $statusLabel ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3 pb-4 px-4" style="border-color: #f1f5f9 !important;">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; transition: all 0.2s ease;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Target Modal for Independent (No Group) -->
<div class="modal fade" id="modal-targets-nogroup" tabindex="-1" aria-labelledby="modalLabel-nogroup" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-3 pt-4 px-4">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 shadow-sm"
                             style="width: 42px; height: 42px; background: linear-gradient(135deg, rgba(100, 116, 139, 0.08), rgba(100, 116, 139, 0.18)); color: #475569;">
                            <i class="fas fa-tasks" style="font-size: 1.15rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalLabel-nogroup" style="font-size: 1.1rem; letter-spacing: -0.2px;">
                                Target Pekerjaan Mandiri
                            </h5>
                            <span class="text-muted small fw-medium d-block mt-0.5" style="font-size: 0.8rem;">Mitra: <strong class="text-secondary">Mandiri / Tanpa Kelompok</strong></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close-custom shadow-none border-0 rounded-circle d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Close"
                            style="width: 32px; height: 32px; background: #f1f5f9; color: #64748b; transition: all 0.2s ease; cursor: pointer; border: none; outline: none;">
                        <i class="fas fa-times" style="font-size: 0.9rem;"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc; max-height: 60vh; overflow-y: auto;">
                <div class="target-jobs-container">
                    <?php
                    $targets = $constructionTargets['independent'] ?? [];
                    if (empty($targets)):
                    ?>
                        <div class="text-center py-5 text-muted bg-white rounded-4 border p-4" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 54px; height: 54px; background: rgba(100, 116, 139, 0.05); color: #475569;">
                                <i class="fas fa-clipboard-list" style="font-size: 1.4rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Target Pekerjaan</h6>
                            <p class="small text-muted mb-0">Mitra mandiri ini belum ditugaskan target pekerjaan konstruksi apa pun.</p>
                        </div>
                    <?php
                    else:
                        $tIdx = 1;
                        foreach ($targets as $t):
                            $statusText = strtoupper($t['status'] ?? 'NOT_STARTED');
                            $borderLeftColor = '#64748b'; // Default slate
                            $statusClass = 'badge-status-notstarted';
                            $statusLabel = 'BELUM MULAI';
                            $statusIcon = 'fas fa-hourglass-start';
                            
                            if ($statusText === 'IN_PROGRESS' || $statusText === 'IN PROGRESS' || $statusText === 'PROGRESS') {
                                $statusClass = 'badge-status-inprogress';
                                $statusLabel = 'IN PROGRESS';
                                $statusIcon = 'fas fa-spinner fa-spin';
                                $borderLeftColor = '#3b82f6'; // Blue
                            } elseif ($statusText === 'COMPLETED' || $statusText === 'SELESAI' || $statusText === 'ACHIEVED') {
                                $statusClass = 'badge-status-completed';
                                $statusLabel = 'SELESAI';
                                $statusIcon = 'fas fa-check-circle';
                                $borderLeftColor = '#10b981'; // Green
                            } elseif ($statusText === 'PENDING') {
                                $statusClass = 'badge-status-pending';
                                $statusLabel = 'PENDING';
                                $statusIcon = 'fas fa-clock';
                                $borderLeftColor = '#f59e0b'; // Amber
                            }
                    ?>
                        <!-- Premium Target Card -->
                        <div class="premium-target-card mb-3 border bg-white position-relative"
                             style="border-left: 4px solid <?= $borderLeftColor ?> !important; border-color: rgba(226, 232, 240, 0.8) !important; border-radius: 12px; transition: all 0.2s ease; padding: 1.1rem 1.25rem !important;">
                            <div class="row align-items-center g-3">
                                <!-- Left Side: ID, Title, Address -->
                                <div class="col-12 col-md-8">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge project-card-badge px-2.5 py-1" style="font-size: 0.68rem; font-weight: 700;">
                                            <i class="fas fa-project-diagram me-1" style="font-size: 0.65rem;"></i> Proyek #<?= esc($t['construction_id']) ?>
                                        </span>
                                        <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 0.68rem; font-weight: 600; border-color: #e2e8f0 !important;">
                                            <i class="far fa-calendar-alt text-muted me-1.5" style="font-size: 0.72rem;"></i> <?= schedRangeLabel((int)$t['start_week'], (int)$t['end_week'], $t['start_date'] ?? null, isset($t['workday']) ? (int)$t['workday'] : null) ?>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.92rem; line-height: 1.4; color: #0f172a !important;">
                                        <?= esc($t['activity_name'] ?: '-') ?>
                                    </h6>
                                    <div class="small text-muted d-flex align-items-start" style="font-size: 0.74rem; line-height: 1.35;">
                                        <i class="fas fa-map-marker-alt text-primary me-2 mt-0.5" style="font-size: 0.75rem; flex-shrink: 0;"></i>
                                        <span><?= esc($t['project_address'] ?: '-') ?></span>
                                    </div>
                                </div>
                                <!-- Right Side: Volume & Status -->
                                <div class="col-12 col-md-4 d-flex align-items-center justify-content-between justify-content-md-end gap-3.5 border-top border-top-md-0 pt-3 pt-md-0"
                                     style="border-color: #f1f5f9 !important;">
                                    <div class="text-start text-md-end me-md-2">
                                        <span class="text-muted d-block small mb-0.5" style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Volume</span>
                                        <span class="fw-bold text-dark" style="font-size: 0.95rem; color: #0f172a !important;">
                                            <?= (float)$t['volume'] ?> <span class="text-muted fw-semibold" style="font-size: 0.78rem;"><?= esc($t['unit'] ?: '-') ?></span>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge <?= $statusClass ?> px-3 py-2 d-inline-flex align-items-center gap-1.5" style="font-size: 0.72rem; font-weight: 700; border-radius: 30px;">
                                            <i class="<?= $statusIcon ?>"></i> <?= $statusLabel ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <div class="modal-footer border-top pt-3 pb-4 px-4" style="border-color: #f1f5f9 !important;">
                <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 0.85rem; transition: all 0.2s ease;">Tutup</button>
            </div>
        </div>
    </div>
</div>