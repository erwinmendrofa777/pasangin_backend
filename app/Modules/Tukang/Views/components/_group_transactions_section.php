<?php
// ── Hitung stats ────────────────────────────────────────────────────────────
$gtTotal    = count($groupTransactions);
$gtPending  = 0;
$gtApproved = 0;
$gtRejected = 0;
foreach ($groupTransactions as $gt) {
    if ($gt['status'] === 'pending')  $gtPending++;
    elseif ($gt['status'] === 'approved') $gtApproved++;
    elseif ($gt['status'] === 'rejected') $gtRejected++;
}
?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     SECTION: TRANSAKSI DISTRIBUSI KELOMPOK
     ═══════════════════════════════════════════════════════════════════════════ -->
<style>
    /* ── Stats Bar ─────────────────────────────────────────────────────────── */
    .gt-stat-card {
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        background: #fff;
        transition: all 0.2s ease;
    }
    .gt-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .gt-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .gt-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 2px;
    }
    .gt-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    /* ── Section Header ────────────────────────────────────────────────────── */
    .gt-section-header {
        border-left: 4px solid var(--palette-primary);
        padding-left: 14px;
    }

    /* ── Table ─────────────────────────────────────────────────────────────── */
    #gt-table thead tr {
        background: linear-gradient(135deg, #1e293b, #334155);
    }
    #gt-table thead th {
        color: rgba(255,255,255,0.9) !important;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: none;
        padding: 13px 14px;
        white-space: nowrap;
    }
    #gt-table thead th:first-child { border-top-left-radius: 12px; }
    #gt-table thead th:last-child  { border-top-right-radius: 12px; }
    #gt-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        font-size: 0.87rem;
        border-color: #f1f5f9;
    }
    #gt-table tbody tr:hover { background: #fafcff !important; }

    /* ── Vote Progress ─────────────────────────────────────────────────────── */
    .vote-progress-wrap {
        width: 110px;
    }
    .vote-progress-bar {
        height: 5px;
        border-radius: 99px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 4px;
    }
    .vote-progress-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.4s ease;
    }

    /* ── Status Badges ─────────────────────────────────────────────────────── */
    .badge-gt-pending  { background:#fff7ed!important; color:#ea580c!important; border:1px solid #ffedd5!important; }
    .badge-gt-approved { background:#f0fdf4!important; color:#16a34a!important; border:1px solid #bbf7d0!important; }
    .badge-gt-rejected { background:#fef2f2!important; color:#dc2626!important; border:1px solid #fee2e2!important; }
    .badge-gt-inflow   { background:#eff6ff!important; color:#2563eb!important; border:1px solid #bfdbfe!important; }
    .badge-gt-outflow  { background:#fdf4ff!important; color:#9333ea!important; border:1px solid #e9d5ff!important; }

    /* ── Vote Avatars in Modal ─────────────────────────────────────────────── */
    .voter-row {
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 8px;
        border: 1px solid #f1f5f9;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .voter-row.vote-approved { border-left: 3px solid #16a34a; }
    .voter-row.vote-rejected { border-left: 3px solid #dc2626; }
    .voter-row.vote-pending  { border-left: 3px solid #f59e0b; }
    .voter-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ── Distribution Table in Modal ──────────────────────────────────────── */
    .dist-table th {
        background: #f8fafc;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .dist-table td {
        font-size: 0.84rem;
        padding: 9px 12px;
        border-color: #f1f5f9;
        vertical-align: middle;
    }

    /* ── Empty State ───────────────────────────────────────────────────────── */
    .gt-empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .gt-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        background: rgba(255,92,92,0.06);
        color: var(--palette-primary);
        font-size: 1.6rem;
    }
</style>

<div class="mb-4">
    <!-- ── Section Title ─────────────────────────────────────────────────── -->
    <div class="gt-section-header mb-4">
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem; letter-spacing: -0.2px;">
            <i class="fas fa-exchange-alt me-2" style="color: var(--palette-primary);"></i>
            Transaksi Distribusi Kelompok
        </h5>
        <span class="text-muted small">Riwayat distribusi saldo dan status persetujuan anggota</span>
    </div>

    <!-- ── Stats Cards ───────────────────────────────────────────────────── -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background:rgba(99,102,241,0.08); color:#6366f1;">
                    <i class="fas fa-list-alt"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Total</div>
                    <div class="gt-stat-value"><?= $gtTotal ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background:rgba(234,88,12,0.08); color:#ea580c;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Pending</div>
                    <div class="gt-stat-value"><?= $gtPending ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background:rgba(22,163,74,0.08); color:#16a34a;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Approved</div>
                    <div class="gt-stat-value"><?= $gtApproved ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background:rgba(220,38,38,0.08); color:#dc2626;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Rejected</div>
                    <div class="gt-stat-value"><?= $gtRejected ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Table Card ────────────────────────────────────────────────────── -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
         style="border: 1px solid rgba(226,232,240,0.8) !important;">
        <div class="card-body p-0">
            <?php if (empty($groupTransactions)): ?>
                <!-- Empty State -->
                <div class="gt-empty-state">
                    <div class="gt-empty-icon"><i class="fas fa-inbox"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Belum Ada Transaksi</h6>
                    <p class="small text-muted mb-0">Transaksi distribusi saldo kelompok akan muncul di sini setelah mandor membuat distribusi.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="gt-table"
                           style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:4%;">NO</th>
                                <th style="width:18%;">NAMA KELOMPOK</th>
                                <th style="width:14%;">NOMINAL</th>
                                <th style="width:8%;">TIPE</th>
                                <th style="width:20%;">DESKRIPSI</th>
                                <th class="text-center" style="width:16%;">PROGRESS VOTING</th>
                                <th class="text-center" style="width:11%;">STATUS</th>
                                <th class="text-center" style="width:5%;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupTransactions as $idx => $gt):
                                $majority   = floor($gt['total_members'] / 2) + 1;
                                $approved   = (int) $gt['total_approved'];
                                $rejected   = (int) $gt['total_rejected'];
                                $total      = (int) $gt['total_members'];
                                $pct        = $total > 0 ? round(($approved / $total) * 100) : 0;
                                $progressColor = $gt['status'] === 'approved' ? '#16a34a'
                                              : ($gt['status'] === 'rejected' ? '#dc2626' : '#f59e0b');

                                // Status badge
                                switch ($gt['status']) {
                                    case 'approved':
                                        $badgeClass = 'badge-gt-approved';
                                        $badgeIcon  = 'fas fa-check-circle';
                                        $badgeLabel = 'Approved';
                                        break;
                                    case 'rejected':
                                        $badgeClass = 'badge-gt-rejected';
                                        $badgeIcon  = 'fas fa-times-circle';
                                        $badgeLabel = 'Rejected';
                                        break;
                                    default:
                                        $badgeClass = 'badge-gt-pending';
                                        $badgeIcon  = 'fas fa-clock';
                                        $badgeLabel = 'Pending';
                                        break;
                                }

                                // Tipe badge
                                $typeBadge = $gt['type'] === 'inflow'
                                    ? '<span class="badge badge-gt-inflow px-2 py-1" style="font-size:0.68rem; font-weight:700; border-radius:20px;"><i class="fas fa-arrow-down me-1"></i>Masuk</span>'
                                    : '<span class="badge badge-gt-outflow px-2 py-1" style="font-size:0.68rem; font-weight:700; border-radius:20px;"><i class="fas fa-arrow-up me-1"></i>Keluar</span>';
                            ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:30px;height:30px;background:rgba(255,92,92,0.08);color:var(--palette-primary);font-size:0.75rem;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size:0.85rem;"><?= esc($gt['name_group']) ?></div>
                                            <div class="text-muted" style="font-size:0.72rem;">
                                                <i class="fas fa-crown me-1 opacity-50"></i><?= esc($gt['mandor_name']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark" style="font-size:0.88rem;">
                                        Rp <?= number_format((float)$gt['amount'], 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td><?= $typeBadge ?></td>
                                <td>
                                    <span class="text-muted" style="font-size:0.82rem;">
                                        <?= esc(mb_strimwidth($gt['description'] ?: '-', 0, 50, '…')) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="vote-progress-wrap mx-auto">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span style="font-size:0.7rem; font-weight:700; color:#475569;">
                                                <?= $approved ?>/<?= $total ?> Approved
                                            </span>
                                            <span style="font-size:0.7rem; color:#94a3b8;"><?= $pct ?>%</span>
                                        </div>
                                        <div class="vote-progress-bar">
                                            <div class="vote-progress-fill"
                                                 style="width:<?= $pct ?>%; background:<?= $progressColor ?>;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 d-inline-flex align-items-center gap-1"
                                          style="font-size:0.7rem; font-weight:700; border-radius:20px;">
                                        <i class="<?= $badgeIcon ?>"></i> <?= $badgeLabel ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-secondary px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-gt-<?= $gt['id'] ?>"
                                            style="border-radius:8px; font-size:0.75rem; font-weight:600; height:32px;">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     MODALS — Detail per Transaksi
     ══════════════════════════════════════════════════════════════════════════ -->
<?php foreach ($groupTransactions as $gt):
    $majority    = floor((int)$gt['total_members'] / 2) + 1;
    $approved    = (int)$gt['total_approved'];
    $rejected    = (int)$gt['total_rejected'];
    $totalMem    = (int)$gt['total_members'];
    $voted       = count($gt['approvals']);
    $notVotedCnt = $totalMem - $voted;

    // Kumpulkan tukang_id yang sudah vote
    $votedIds = array_column($gt['approvals'], 'tukang_id');

    switch ($gt['status']) {
        case 'approved': $statusColor = '#16a34a'; $statusBg = '#f0fdf4'; $statusIcon = 'fa-check-circle'; $statusLabel = 'Approved'; break;
        case 'rejected': $statusColor = '#dc2626'; $statusBg = '#fef2f2'; $statusIcon = 'fa-times-circle'; $statusLabel = 'Rejected'; break;
        default:         $statusColor = '#ea580c'; $statusBg = '#fff7ed'; $statusIcon = 'fa-clock';         $statusLabel = 'Pending';  break;
    }
?>
<div class="modal fade" id="modal-gt-<?= $gt['id'] ?>" tabindex="-1"
     aria-labelledby="modal-gt-label-<?= $gt['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius:20px; overflow:hidden;">

            <!-- Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                         style="width:44px;height:44px;background:linear-gradient(135deg,rgba(255,92,92,0.08),rgba(255,92,92,0.18));color:var(--palette-primary);">
                        <i class="fas fa-exchange-alt" style="font-size:1.1rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modal-gt-label-<?= $gt['id'] ?>"
                            style="font-size:1rem;">
                            Detail Distribusi — <?= esc($gt['name_group']) ?>
                        </h5>
                        <span class="text-muted small">TRX #<?= $gt['id'] ?> &bull; <?= date('d M Y, H:i', strtotime($gt['created_at'])) ?></span>
                    </div>
                    <button type="button" class="btn-close-custom shadow-none border-0 rounded-circle d-flex align-items-center justify-content-center ms-auto"
                            data-bs-dismiss="modal" aria-label="Close"
                            style="width:32px;height:32px;background:#f1f5f9;color:#64748b;cursor:pointer;border:none;">
                        <i class="fas fa-times" style="font-size:0.9rem;"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 pb-2" style="background:#f8fafc;">

                <!-- Summary Row -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="bg-white rounded-3 border p-3 text-center" style="border-color:#e2e8f0!important;">
                            <div class="text-muted mb-1" style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Nominal</div>
                            <div class="fw-bold text-dark" style="font-size:0.92rem;">
                                Rp <?= number_format((float)$gt['amount'], 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="bg-white rounded-3 border p-3 text-center" style="border-color:#e2e8f0!important;">
                            <div class="text-muted mb-1" style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Tipe</div>
                            <div class="fw-bold" style="font-size:0.9rem;color:<?= $gt['type']==='inflow' ? '#2563eb' : '#9333ea' ?>;">
                                <i class="fas <?= $gt['type']==='inflow' ? 'fa-arrow-down' : 'fa-arrow-up' ?> me-1"></i>
                                <?= $gt['type'] === 'inflow' ? 'Masuk' : 'Keluar' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 border p-3 text-center" style="background:<?= $statusBg ?>;border-color:<?= $statusColor ?>30!important;">
                            <div class="text-muted mb-1" style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Status</div>
                            <div class="fw-bold" style="font-size:0.9rem;color:<?= $statusColor ?>;">
                                <i class="fas <?= $statusIcon ?> me-1"></i><?= $statusLabel ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="bg-white rounded-3 border p-3 text-center" style="border-color:#e2e8f0!important;">
                            <div class="text-muted mb-1" style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Voting</div>
                            <div class="fw-bold text-dark" style="font-size:0.9rem;">
                                <?= $approved ?>/<?= $totalMem ?>
                                <span style="font-size:0.72rem;color:#94a3b8;font-weight:400;">approved</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <?php if (!empty($gt['description'])): ?>
                <div class="bg-white rounded-3 border p-3 mb-4" style="border-color:#e2e8f0!important;">
                    <div class="text-muted mb-1" style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="fas fa-comment-alt me-1"></i>Deskripsi
                    </div>
                    <p class="mb-0 text-dark" style="font-size:0.85rem;"><?= esc($gt['description']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Tabel Distribusi -->
                <?php if (!empty($gt['distributions'])): ?>
                <div class="mb-4">
                    <div class="fw-bold text-dark mb-2" style="font-size:0.82rem;">
                        <i class="fas fa-share-alt me-2" style="color:var(--palette-primary);"></i>Rincian Distribusi
                    </div>
                    <div class="rounded-3 border overflow-hidden bg-white" style="border-color:#e2e8f0!important;">
                        <table class="table table-hover mb-0 dist-table">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-2">Nama Tukang</th>
                                    <th class="py-2">Peran</th>
                                    <th class="py-2 text-end pe-3">Jumlah</th>
                                    <th class="py-2 text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($gt['distributions'] as $dist): ?>
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">
                                        <?= esc($dist['name'] ?? $dist['tukang_name'] ?? '-') ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($dist['role']) && $dist['role'] === 'mandor'): ?>
                                            <span class="badge" style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;font-size:0.65rem;">
                                                <i class="fas fa-crown me-1"></i>Mandor
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background:#f0f9ff;color:#0284c7;border:1px solid #bae6fd;font-size:0.65rem;">
                                                <i class="fas fa-hard-hat me-1"></i>Tukang
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-dark">
                                        Rp <?= number_format((float)($dist['amount'] ?? 0), 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center text-muted" style="font-size:0.8rem;">
                                        <?php
                                        $gtAmt = (float)$gt['amount'];
                                        $dAmt  = (float)($dist['amount'] ?? 0);
                                        echo $gtAmt > 0 ? round(($dAmt / $gtAmt) * 100, 1) . '%' : '-';
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Status Voting Anggota -->
                <div class="mb-2">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark" style="font-size:0.82rem;">
                            <i class="fas fa-vote-yea me-2" style="color:var(--palette-primary);"></i>Status Voting Anggota
                        </div>
                        <span class="badge bg-light text-dark border" style="font-size:0.72rem;">
                            Perlu <?= $majority ?> dari <?= $totalMem ?> suara
                        </span>
                    </div>

                    <?php if (empty($gt['approvals'])): ?>
                        <div class="text-center py-4 text-muted bg-white rounded-3 border" style="border-color:#e2e8f0!important;">
                            <i class="fas fa-hourglass-half mb-2 d-block" style="font-size:1.3rem; color:#f59e0b;"></i>
                            <span style="font-size:0.82rem;">Belum ada anggota yang memberikan suara.</span>
                        </div>
                    <?php else: ?>
                        <?php foreach ($gt['approvals'] as $vote):
                            $voteClass = $vote['vote'] === 'approved' ? 'vote-approved' : 'vote-rejected';
                            $voteColor = $vote['vote'] === 'approved' ? '#16a34a' : '#dc2626';
                            $voteBg    = $vote['vote'] === 'approved' ? '#f0fdf4' : '#fef2f2';
                            $voteIcon  = $vote['vote'] === 'approved' ? 'fa-check' : 'fa-times';
                            $voteLabel = $vote['vote'] === 'approved' ? 'Approved' : 'Rejected';
                            $initial   = mb_strtoupper(mb_substr($vote['voter_name'] ?? 'T', 0, 1));
                        ?>
                        <div class="voter-row <?= $voteClass ?>">
                            <div class="voter-avatar" style="background:<?= $voteBg ?>; color:<?= $voteColor ?>;">
                                <?= $initial ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.85rem;"><?= esc($vote['voter_name']) ?></div>
                                <div class="text-muted" style="font-size:0.72rem;">
                                    <?= $vote['voter_role'] === 'mandor' ? '<i class="fas fa-crown me-1" style="color:#d97706;"></i>Mandor' : '<i class="fas fa-hard-hat me-1" style="color:#0284c7;"></i>Tukang' ?>
                                    <?php if (!empty($vote['voted_at'])): ?>
                                        &bull; <?= date('d M Y H:i', strtotime($vote['voted_at'])) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="badge fw-bold px-3 py-2"
                                  style="background:<?= $voteBg ?>;color:<?= $voteColor ?>;border:1px solid <?= $voteColor ?>30;font-size:0.7rem;border-radius:20px;">
                                <i class="fas <?= $voteIcon ?> me-1"></i><?= $voteLabel ?>
                            </span>
                        </div>
                        <?php endforeach; ?>

                        <?php if ($notVotedCnt > 0): ?>
                        <div class="voter-row vote-pending" style="opacity:0.65;">
                            <div class="voter-avatar" style="background:#fff7ed;color:#ea580c;">
                                <i class="fas fa-ellipsis-h" style="font-size:0.75rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.84rem;"><?= $notVotedCnt ?> anggota belum memberikan suara</div>
                                <div class="text-muted" style="font-size:0.72rem;">Menunggu respons</div>
                            </div>
                            <span class="badge fw-bold px-3 py-2"
                                  style="background:#fff7ed;color:#ea580c;border:1px solid #ffedd5;font-size:0.7rem;border-radius:20px;">
                                <i class="fas fa-clock me-1"></i>Belum Vote
                            </span>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer border-top px-4 py-3" style="border-color:#f1f5f9!important;background:#fff;">
                <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary"
                        data-bs-dismiss="modal"
                        style="border-radius:10px; font-size:0.85rem; transition:all 0.2s ease;">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
<?php endforeach; ?>
