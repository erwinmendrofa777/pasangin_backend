<?php
$invoiceList = $invoice_list ?? [];
$totalPaid = array_sum(array_map(fn($i) => $i['status'] == 'PAID' ? (float) $i['amount'] : 0, $invoiceList));
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

// Kumpulkan deskripsi tagihan yang berasal dari RAB
$rabDescs = [];
foreach ($list_tagihan as $r) {
    $rabDescs[] = strtolower(trim(($r['sub_group_name'] ? $r['sub_group_name'] . ' — ' : '') . $r['activity_name']));
}

// Filter tagihan yang dibuat manual / di luar RAB
$manualInvoices = [];
foreach ($invoiceList as $inv) {
    $descLower = strtolower(trim($inv['description']));
    if (!in_array($descLower, $rabDescs)) {
        $manualInvoices[] = $inv;
    }
}

// Hitung total nilai kontrak (RAB + Tagihan Tambahan)
$rabTotal = array_sum(array_column($list_tagihan, 'total_price'));
$manualTotal = array_sum(array_column($manualInvoices, 'amount'));
$grandTotal = $rabTotal + $manualTotal;

$paidPct = $grandTotal > 0 ? min(100, round($totalPaid / $grandTotal * 100)) : 0;
$countUnpaid = count(array_filter($invoiceList, fn($i) => $i['status'] != 'PAID'));
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
                    <div class="mt-2">
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
                    <div class="mt-3 px-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-light text-primary font-weight-bold py-2 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreateInvoice" onclick="clearInvoiceForm()">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Tagihan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- ===== RAB BELUM DIKUNCI ===== -->
    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-4">
        <div>
            <i class="fas fa-lock fa-lg mr-2"></i>
            <strong>RAB Belum Dikunci</strong><br>
            <small>Kunci RAB di tab <strong>Kelola RAB</strong> terlebih dahulu untuk mengaktifkan referensi nilai kontrak.
                Anda tetap bisa membuat tagihan manual.</small>
        </div>
        <button type="button" class="btn btn-warning text-white font-weight-bold ml-3" data-bs-toggle="modal" data-bs-target="#modalCreateInvoice" onclick="clearInvoiceForm()">
            <i class="fas fa-plus-circle mr-1"></i> Buat Tagihan
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">

        <!-- Rincian RAB -->
        <h6 class="font-weight-bold text-primary mb-3">
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
                                        <th class="text-right" style="width:130px;">Total</th>
                                        <th class="text-center" style="width:120px;">Status Bayar</th>
                                        <th style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['items'] as $rab):
                                        $desc = trim(($rab['sub_group_name'] ? $rab['sub_group_name'] . ' — ' : '') . $rab['activity_name']);
                                        
                                        // Cari invoice yang cocok berdasarkan rab_id (prioritas) atau deskripsi (fallback)
                                        $matchedInvoice = null;
                                        foreach ($invoiceList as $inv) {
                                            if (!empty($inv['rab_id']) && (int)$inv['rab_id'] === (int)$rab['id']) {
                                                $matchedInvoice = $inv;
                                                break;
                                            }
                                            if (empty($inv['rab_id']) && strtolower(trim($inv['description'])) === strtolower($desc)) {
                                                $matchedInvoice = $inv;
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($matchedInvoice): ?>
                                             <tr class="rab-click-row <?= $matchedInvoice['status'] == 'PAID' ? 'table-success-light' : 'table-warning-light' ?>" style="cursor:pointer; vertical-align: middle;"
                                                 onclick="toggleRabDetail(<?= $rab['id'] ?>, '<?= esc($rab['ahsp_id']) ?>', '<?= esc(addslashes($desc)) ?>', <?= (float)$rab['volume'] ?>, this)"
                                                 title="Klik untuk detail pekerjaan">
                                                 <td>
                                                     <?php if ($rab['sub_group_name']): ?>
                                                         <small class="text-muted"><?= esc($rab['sub_group_name']) ?> &rsaquo;</small><br>
                                                     <?php endif; ?>
                                                     <span class="font-weight-500"><?= esc($rab['activity_name']) ?></span>
                                                 </td>
                                                 <td class="text-right text-muted font-weight-bold">Rp <?= number_format((int) $rab['total_price']) ?></td>
                                                 <td class="text-center">
                                                     <?php if ($matchedInvoice['status'] == 'PAID'): ?>
                                                         <span class="badge bg-success text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                                     <?php else: ?>
                                                         <span class="badge bg-warning text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-clock mr-1"></i>BELUM BAYAR</span>
                                                     <?php endif; ?>
                                                 </td>
                                                 <td class="text-center" onclick="event.stopPropagation();">
                                                     <a href="<?= base_url('admin/construction/delete_invoice/' . $matchedInvoice['id'] . '/' . $construction['id']) ?>"
                                                         class="btn btn-sm btn-danger p-1 ladda-button" data-style="zoom-in"
                                                         onclick="if(confirm('Hapus tagihan untuk pekerjaan ini?')){Ladda.create(this).start();return true;}return false;"
                                                         title="Hapus Tagihan">
                                                         <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                                     </a>
                                                 </td>
                                             </tr>
                                         <?php else: ?>
                                             <tr style="cursor:pointer; vertical-align: middle;" class="rab-click-row"
                                                 onclick="toggleRabDetail(<?= $rab['id'] ?>, '<?= esc($rab['ahsp_id']) ?>', '<?= esc(addslashes($desc)) ?>', <?= (float)$rab['volume'] ?>, this)"
                                                 title="Klik untuk detail pekerjaan">
                                                 <td>
                                                     <?php if ($rab['sub_group_name']): ?>
                                                         <small class="text-muted"><?= esc($rab['sub_group_name']) ?> &rsaquo;</small><br>
                                                     <?php endif; ?>
                                                     <span class="font-weight-500"><?= esc($rab['activity_name']) ?></span>
                                                 </td>
                                                 <td class="text-right text-primary font-weight-bold">Rp <?= number_format((int) $rab['total_price']) ?></td>
                                                 <td class="text-center">
                                                     <span class="badge bg-secondary text-white px-2 py-1" style="font-size:10px;">BELUM DITAGIH</span>
                                                 </td>
                                                 <td class="text-center text-primary" onclick="event.stopPropagation(); fillInvoiceForm('<?= addslashes($desc) ?>', <?= (int) $rab['total_price'] ?>, <?= $rab['id'] ?>, this)">
                                                     <button type="button" class="btn btn-sm btn-outline-primary p-1" title="Buat Tagihan">
                                                         <i class="fas fa-plus-circle"></i>
                                                     </button>
                                                 </td>
                                             </tr>
                                         <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <!-- Tagihan Tambahan (Diluar RAB) -->
            <?php if (!empty($manualInvoices)): ?>
                <div class="card border-primary mt-3 mb-1 shadow-sm">
                    <div class="card-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold small text-white" style="color: #fff !important;"><i class="fas fa-file-invoice-dollar mr-1"></i> Tagihan Tambahan (Diluar RAB)</span>
                        <span class="badge bg-white text-primary font-weight-bold"><?= count($manualInvoices) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Keterangan</th>
                                    <th class="text-right text-end" style="width:130px;">Nominal</th>
                                    <th class="text-center" style="width:130px;">Jatuh Tempo</th>
                                    <th class="text-center" style="width:120px;">Status Bayar</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($manualInvoices as $inv): ?>
                                    <tr>
                                        <td class="font-weight-500 text-dark"><?= esc($inv['description']) ?></td>
                                        <td class="text-right text-end text-primary font-weight-bold">Rp <?= number_format($inv['amount']) ?></td>
                                        <td class="text-center text-muted small">
                                            <?= $inv['due_date'] ? date('d M Y', strtotime($inv['due_date'])) : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($inv['status'] == 'PAID'): ?>
                                                <span class="badge bg-success text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-clock mr-1"></i>BELUM BAYAR</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>"
                                                class="btn btn-sm btn-danger p-1 ladda-button" data-style="zoom-in"
                                                onclick="if(confirm('Hapus tagihan manual ini?')){Ladda.create(this).start();return true;}return false;"
                                                title="Hapus Tagihan">
                                                <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

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

            <!-- Tagihan Tambahan (Diluar RAB) jika RAB tidak locked -->
            <?php if (!empty($manualInvoices)): ?>
                <div class="card border-primary mt-3 shadow-sm">
                    <div class="card-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold small text-white" style="color: #fff !important;"><i class="fas fa-file-invoice-dollar mr-1"></i> Tagihan Tambahan (Diluar RAB)</span>
                        <span class="badge bg-white text-primary font-weight-bold"><?= count($manualInvoices) ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Keterangan</th>
                                    <th class="text-right text-end" style="width:130px;">Nominal</th>
                                    <th class="text-center" style="width:130px;">Jatuh Tempo</th>
                                    <th class="text-center" style="width:120px;">Status Bayar</th>
                                    <th style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($manualInvoices as $inv): ?>
                                    <tr>
                                        <td class="font-weight-500 text-dark"><?= esc($inv['description']) ?></td>
                                        <td class="text-right text-end text-primary font-weight-bold">Rp <?= number_format($inv['amount']) ?></td>
                                        <td class="text-center text-muted small">
                                            <?= $inv['due_date'] ? date('d M Y', strtotime($inv['due_date'])) : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($inv['status'] == 'PAID'): ?>
                                                <span class="badge bg-success text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-check-circle mr-1"></i>LUNAS</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-white px-2 py-1" style="font-size:10px;"><i class="fas fa-clock mr-1"></i>BELUM BAYAR</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin/construction/delete_invoice/' . $inv['id'] . '/' . $construction['id']) ?>"
                                                class="btn btn-sm btn-danger p-1 ladda-button" data-style="zoom-in"
                                                onclick="if(confirm('Hapus tagihan manual ini?')){Ladda.create(this).start();return true;}return false;"
                                                title="Hapus Tagihan">
                                                <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<!-- Modal Buat Tagihan Baru -->
<div class="modal fade" id="modalCreateInvoice" tabindex="-1" aria-labelledby="modalCreateInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold text-white" id="modalCreateInvoiceLabel" style="color: #fff !important;">
                    <i class="fas fa-plus-circle mr-1"></i> Buat Tagihan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%); border: none; background: transparent; color: white; font-size: 1.5rem; line-height: 1;">&times;</button>
            </div>
            <form action="<?= base_url('admin/construction/create_invoice') ?>" method="post">
                <div class="modal-body text-dark">
                    <input type="hidden" name="construction_id" value="<?= $construction['id'] ?>">
                    <input type="hidden" name="rab_id" id="invoice_rab_id">

                    <!-- Indicator terisi dari RAB -->
                    <div id="selectedRabInfo" class="alert alert-info py-2 px-3 small mb-3" style="display:none;">
                        <i class="fas fa-check-circle mr-1"></i> <strong>Pekerjaan Dipilih:</strong>
                        <div id="selectedRabName" class="mt-1 font-weight-bold"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Keterangan / Item Tagihan</label>
                        <input type="text" id="invoice_description" name="description"
                            class="form-control" placeholder="Contoh: Termin 1 atau Nama Pekerjaan" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary font-weight-bold">Rp</span>
                            <input type="text" id="invoice_amount_visible" class="form-control" required
                                onkeyup="formatCurrencyInput(this)" placeholder="0">
                        </div>
                        <input type="hidden" id="invoice_amount" name="amount" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">Batas Waktu Bayar (Due Date)</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="clearInvoiceForm()" class="btn btn-outline-secondary">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                        <span class="ladda-label"><i class="fas fa-paper-plane mr-1"></i> Kirim Tagihan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>