<?php
$invoiceList = $invoice_list ?? [];
$totalPaid = array_sum(array_map(fn($i) => $i['status'] == 'PAID' ? (float) $i['amount'] : 0, $invoiceList));
$grandTotal = array_sum(array_column($list_tagihan, 'total_price'));
$paidPct = $grandTotal > 0 ? min(100, round($totalPaid / $grandTotal * 100)) : 0;
$countUnpaid = count(array_filter($invoiceList, fn($i) => $i['status'] != 'PAID'));
$isLocked = !empty($rab_list) && $rab_list[0]['is_locked'] == 1;

// Group RAB by roman + group_name
$groupedRabs = [];
foreach ($list_tagihan as $r) {
    $key = $r['roman_number'] . '_' . $r['group_name'];
    if (!isset($groupedRabs[$key])) {
        $groupedRabs[$key] = [
            'label' => $r['roman_number'] . '. ' . $r['group_name'],
            'total' => 0,
            'items' => []
        ];
    }
    $groupedRabs[$key]['total'] += (float) $r['total_price'];
    $groupedRabs[$key]['items'][] = $r;
}

// Kumpulkan deskripsi tagihan yang sudah dibuat (untuk cegah duplikat)
$invoicedDescs = array_map('strtolower', array_column($invoiceList, 'description'));
?>

<?php if ($isLocked): ?>

    <div class="card bg-primary text-white border-0 mb-4">
        <div class="card-body py-4">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <small class="text-white-50 text-uppercase" style="letter-spacing:.8px;font-size:11px;">Nilai
                        Kontrak</small>
                    <h3 class="font-weight-bold mb-1" style="color: #fff !important;">Rp <?= number_format($grandTotal) ?></h3>
                    <small class="text-white-50"><?= count($list_tagihan) ?> item pekerjaan &bull; RAB terkunci</small>
                    <div class="mt-3">
                        <small class="text-white-50"><?= $paidPct ?>% terbayar</small>
                        <div class="progress mt-1" style="height:6px;background:rgba(255,255,255,.2);border-radius:10px;">
                            <div class="progress-bar bg-white" style="width:<?= $paidPct ?>%;border-radius:10px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 mt-3 mt-md-0">
                    <div class="row text-center">
                        <div class="col-12 col-sm-4 px-2 mb-2 mb-sm-0">
                            <div class="py-2 px-1 text-center" style="background:rgba(255,255,255,.15);border-radius:10px;">
                                <div class="font-weight-bold" style="font-size:1.1rem; color: #fff !important;">Rp <?= number_format($totalPaid) ?>
                                </div>
                                <small class="text-white-50 d-block" style="font-size:0.75rem;">Terbayar</small>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 px-2">
                            <div class="py-2 px-1 text-center" style="background:rgba(255,255,255,.15);border-radius:10px;">
                                <div class="font-weight-bold" style="font-size:1.1rem; color: #fff !important;"><?= $countUnpaid ?></div>
                                <small class="text-white-50 d-block" style="font-size:0.75rem;">Tagihan Aktif</small>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 px-2">
                            <div class="py-2 px-1 text-center" style="background:rgba(255,255,255,.15);border-radius:10px;">
                                <div class="font-weight-bold" style="font-size:1.1rem; color: #fff !important;"><?= count($invoiceList) ?></div>
                                <small class="text-white-50 d-block" style="font-size:0.75rem;">Total Tagihan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- ===== RAB BELUM DIKUNCI ===== -->
    <div class="alert alert-warning d-flex align-items-start mb-4">
        <div>
            <i class="fas fa-lock fa-lg"></i>
            <strong>RAB Belum Dikunci</strong><br>
            <small>Kunci RAB di tab <strong>Kelola RAB</strong> terlebih dahulu untuk mengaktifkan referensi nilai kontrak.
                Anda tetap bisa membuat tagihan manual.</small>
        </div>
    </div>
<?php endif; ?>

<div class="row">

    <!-- ===== KIRI: Tagihan + RAB ===== -->
    <div class="col-md-12">

        <!-- Daftar Tagihan -->
        <h6 class="font-weight-bold text-primary mb-3">
            <i class="fas fa-receipt mr-1"></i> Tagihan Terkirim
            <span class="badge badge-primary ml-1"><?= count($invoiceList) ?></span>
        </h6>

        <?php if (!empty($invoiceList)): ?>
            <div class="table-responsive mb-4">
                <table class="table table-hover table-bordered align-middle text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Keterangan</th>
                            <th class="text-right">Nominal</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width:60px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoiceList as $inv): ?>
                            <tr>
                                <td class="font-weight-bold"><?= esc($inv['description']) ?></td>
                                <td class="text-right text-primary font-weight-bold">Rp <?= number_format($inv['amount']) ?>
                                </td>
                                <td class="text-muted small">
                                    <?= $inv['due_date'] ? date('d M Y', strtotime($inv['due_date'])) : '-' ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($inv['status'] == 'PAID'): ?>
                                        <span class="badge badge-success" style="padding: 0.5rem 2rem">LUNAS</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-warning text-white">BELUM BAYAR</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>"
                                        class="btn btn-sm btn-danger ladda-button" data-style="zoom-in"
                                        onclick="if(confirm('Hapus tagihan ini?')){Ladda.create(this).start();return true;}return false;">
                                        <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4 mb-4 border rounded">
                <i class="fas fa-file-invoice fa-2x mb-2 d-block text-primary opacity-50"></i>
                Belum ada tagihan. Klik item RAB di bawah untuk membuat tagihan.
            </div>
        <?php endif; ?>

    </div>

</div><!-- end row -->

<div class="row">
    <div class="col-md-8">

        <!-- Rincian RAB -->
        <h6 class="font-weight-bold text-primary mb-1">
            <i class="fas fa-list-alt mr-1"></i> Rincian Nilai Kontrak (RAB)
        </h6>

        <?php if ($isLocked): ?>
            <?php $gi = 0;
            foreach ($groupedRabs as $group):
                $gi++; ?>

                <!-- Group Header (accordion toggle) -->
                <div class="card mb-1 border-primary">
                    <div class="card-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center"
                        style="cursor:pointer;" onclick="toggleRabGroup('rabg<?= $gi ?>', this)">
                        <span class="font-weight-bold small"><?= esc($group['label']) ?></span>
                        <span class="small">
                            Rp <?= number_format($group['total']) ?>
                            <i class="fas fa-chevron-down ml-2" style="transition:transform .25s;"></i>
                        </span>
                    </div>
                    <div id="rabg<?= $gi ?>" class="collapse">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 text-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pekerjaan</th>
                                        <th class="text-center" style="width:80px;">Vol</th>
                                        <th class="text-center" style="width:70px;">Satuan</th>
                                        <th class="text-right" style="width:130px;">Harga Satuan</th>
                                        <th class="text-right" style="width:130px;">Total</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['items'] as $rab):
                                        $desc = trim(($rab['sub_group_name'] ? $rab['sub_group_name'] . ' — ' : '') . $rab['activity_name']);
                                        $alreadyBilled = in_array(strtolower($desc), $invoicedDescs);
                                        ?>
                                        <?php if ($alreadyBilled): ?>
                                            <tr class="table-secondary" style="opacity:.65;" title="Sudah ditagihkan">
                                                <td>
                                                    <?php if ($rab['sub_group_name']): ?>
                                                        <small class="text-muted"><?= esc($rab['sub_group_name']) ?> &rsaquo;</small><br>
                                                    <?php endif; ?>
                                                    <span class="font-weight-500 small"><?= esc($rab['activity_name']) ?></span>
                                                    <span class="badge badge-success ml-1 small" style="font-size:10px;">Sudah
                                                        Ditagihkan</span>
                                                </td>
                                                <td class="text-center text-muted small"><?= number_format((float) $rab['volume'], 2) ?>
                                                </td>
                                                <td class="text-center text-muted small"><?= esc($rab['unit']) ?></td>
                                                <td class="text-right text-muted small">Rp
                                                    <?= number_format((int) $rab['current_unit_price']) ?>
                                                </td>
                                                <td class="text-right text-muted font-weight-bold small">Rp
                                                    <?= number_format((int) $rab['total_price']) ?>
                                                </td>
                                                <td class="text-center text-muted"><i class="fas fa-check small"></i></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr style="cursor:pointer;" class="rab-click-row"
                                                onclick="fillInvoiceForm('<?= addslashes($desc) ?>', <?= (int) $rab['total_price'] ?>, this)"
                                                title="Klik untuk buat tagihan">
                                                <td>
                                                    <?php if ($rab['sub_group_name']): ?>
                                                        <small class="text-muted"><?= esc($rab['sub_group_name']) ?> &rsaquo;</small><br>
                                                    <?php endif; ?>
                                                    <span class="font-weight-500"><?= esc($rab['activity_name']) ?></span>
                                                </td>
                                                <td class="text-center"><?= number_format((float) $rab['volume'], 2) ?></td>
                                                <td class="text-center text-muted small"><?= esc($rab['unit']) ?></td>
                                                <td class="text-right text-muted small">Rp
                                                    <?= number_format((int) $rab['current_unit_price']) ?>
                                                </td>
                                                <td class="text-right text-primary font-weight-bold">Rp
                                                    <?= number_format((int) $rab['total_price']) ?>
                                                </td>
                                                <td class="text-center text-primary"><i class="fas fa-arrow-right small"></i></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <!-- Grand Total -->
            <div class="alert alert-primary d-flex justify-content-between align-items-center mt-2 mb-0 text-white" style="color: #fff !important;">
                <span class="font-weight-bold text-uppercase small text-white" style="color: #fff !important;">Total Nilai Kontrak</span>
                <span class="font-weight-bold text-white" style="font-size:16px; color: #fff !important;">Rp <?= number_format($grandTotal) ?></span>
            </div>

        <?php else: ?>

            <!-- Info RAB belum final -->
            <div class="card border-secondary shadow-sm mt-2">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-white small">
                        <i class="fas fa-list-alt mr-1"></i> Rincian RAB
                    </span>
                    <span class="badge badge-warning text-white">Belum Final</span>
                </div>
                <div class="card-body py-3 text-center text-muted">
                    <i class="fas fa-lock fa-2x mb-2 d-block text-warning"></i>
                    <strong>RAB belum final / belum dikunci.</strong><br>
                    <small>Kunci RAB terlebih dahulu untuk melihat rincian nilai kontrak and menggunakannya sebagai
                        referensi tagihan.</small>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- ===== KANAN: Form Tagihan ===== -->
    <div class="col-md-4">
        <div class="card border-primary shadow-sm mt-sm-0 mt-2" style="position:sticky;top:20px;">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 font-weight-bold text-white" style="color: #fff !important;"><i class="fas fa-plus-circle mr-1"></i> Buat Tagihan Baru</h6>
            </div>
            <div class="card-body">
                <!-- Indicator terisi dari RAB -->
                <div id="selectedRabInfo" class="alert alert-info py-2 px-3 small mb-3" style="display:none;">
                    <i class="fas fa-check-circle mr-1"></i> <strong>Dipilih:</strong>
                    <div id="selectedRabName" class="mt-1"></div>
                </div>

                <form action="<?= base_url('admin/construction/create_invoice') ?>" method="post">
                    <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">

                    <div class="form-group">
                        <label class="small font-weight-bold">Keterangan</label>
                        <input type="text" id="invoice_description" name="description"
                            class="form-control form-control-sm" placeholder="Contoh: Termin 1" required>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Nominal</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text bg-primary text-white border-primary font-weight-bold">Rp</span>
                            </div>
                            <input type="text" id="invoice_amount_visible" class="form-control" required
                                onkeyup="formatCurrencyInput(this)" placeholder="0">
                        </div>
                        <input type="hidden" id="invoice_amount" name="amount" required>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Batas Waktu Bayar</label>
                        <input type="date" name="due_date" class="form-control form-control-sm" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block ladda-button" data-style="zoom-in">
                        <span class="ladda-label"><i class="fas fa-paper-plane mr-1"></i> Kirim Tagihan</span>
                    </button>
                    <button type="button" onclick="clearInvoiceForm()"
                        class="btn btn-outline-secondary btn-block shadow-sm mt-2">
                        <i class="fas fa-times mr-1"></i> Reset
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>