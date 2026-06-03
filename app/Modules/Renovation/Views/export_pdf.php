<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }
        .company-address {
            font-size: 10px;
            color: #666;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-meta {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Tabel Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: middle;
        }
        .table-data th {
            background-color: #f0f6ff;
            color: #0d6efd;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            text-align: center;
        }
        .table-data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }

        /* Badge Status */
        .status-badge {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <?php
    $total_rab = 0;
    $total_invoice = 0;
    $total_anggaran = 0;
    if (!empty($projects)) {
        foreach ($projects as $p) {
            $total_rab += (int) ($p['rab_total'] ?? 0);
            $total_invoice += (int) ($p['total_invoice'] ?? 0);
            $total_anggaran += (int) ($p['total_payment'] ?? 0);
        }
    }
    ?>

    <!-- Kop Surat -->
    <table class="kop-table">
        <tr>
            <td class="text-left" style="width: 70%;">
                <div class="company-name">PT. Pendowo Tiga Construction</div>
                <div class="company-address">
                    Jalan Ki Ageng Getas Pendowo, RT 02 RW 12, Kel. Kuripan, Kec. Purwodadi, Kab. Grobogan<br>
                    Email: info@pendowotigaconstruction.com | Web: www.pendowotigaconstruction.com
                </div>
            </td>
            <td class="text-right" style="width: 30%; vertical-align: bottom; font-size: 10px; color: #555;">
                Tanggal Cetak: <?= tanggal_indo($tanggal_cetak) ?>
            </td>
        </tr>
    </table>

    <!-- Judul -->
    <div class="report-title">Laporan Proyek Renovasi</div>
    <div class="report-meta">Total Proyek: <?= count($projects) ?> Proyek</div>

    <!-- Ringkasan Anggaran -->
    <table style="width: 100%; margin-bottom: 20px; border-spacing: 15px; border-collapse: separate; margin-left: -15px; margin-right: -15px;">
        <tr>
            <td style="width: 33.33%; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #6c757d; font-weight: bold; margin-bottom: 5px;">Total RAB Proyek</div>
                <div style="font-size: 16px; font-weight: bold; color: #333;">Rp <?= number_format($total_rab, 0, ',', '.') ?></div>
            </td>
            <td style="width: 33.33%; background-color: #f0f6ff; border: 1px solid #bce0fd; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #0d6efd; font-weight: bold; margin-bottom: 5px;">Total Invoice Terbit</div>
                <div style="font-size: 16px; font-weight: bold; color: #0d6efd;">Rp <?= number_format($total_invoice, 0, ',', '.') ?></div>
            </td>
            <td style="width: 33.33%; background-color: #e6f9ed; border: 1px solid #b7ebc6; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #198754; font-weight: bold; margin-bottom: 5px;">Total Anggaran Kontrak</div>
                <div style="font-size: 16px; font-weight: bold; color: #198754;">Rp <?= number_format($total_anggaran, 0, ',', '.') ?></div>
            </td>
        </tr>
    </table>

    <!-- Data -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Pelanggan</th>
                <th style="width: 15%;">Tipe Renovasi</th>
                <th style="width: 15%;">Estimasi Pengerjaan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 11%;">RAB Proyek</th>
                <th style="width: 11%;">Total Invoice</th>
                <th style="width: 12%;">Total Anggaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $key => $row): ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td>
                            <span class="fw-bold"><?= esc($row['full_name'] ?: '-') ?></span><br>
                            <span style="font-size: 9px; color: #666;"><?= esc($row['phone'] ?: '-') ?></span>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="color: #0d6efd;"><?= esc($row['renovation_type'] ?: '-') ?></span><br>
                            <span style="font-size: 9px; color: #666;"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                        </td>
                        <td class="text-center">
                            <?php
                            if (!empty($row['start_date']) && !empty($row['week'])) {
                                $start = new DateTime($row['start_date']);
                                $end = clone $start;
                                $end->modify('+' . $row['week'] . ' weeks');

                                echo $start->format('d M') . ' - ' . $end->format('d M Y') . ' (' . $row['week'] . ' MINGGU)';
                            } else {
                                echo '<span style="font-style: italic; color: #888;">Belum diatur</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $status = strtoupper($row['status']);
                            $statusMap = [
                                'PENDING' => 'Menunggu',
                                'SURVEY' => 'Survey',
                                'DESIGNING' => 'Desain',
                                'RAB' => 'RAB',
                                'CONSTRUCTION' => 'Konstruksi',
                                'COMPLETED' => 'Selesai',
                                'CANCELLED' => 'Batal',
                            ];
                            $label = $statusMap[$status] ?? $status;
                            ?>
                            <span class="status-badge">
                                <?= esc($label) ?>
                            </span>
                        </td>
                        <td class="text-right">Rp <?= number_format($row['rab_total'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_invoice'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_payment'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Row Total -->
                <tr style="background-color: #f0f6ff; font-weight: bold;">
                    <td colspan="5" class="text-right" style="padding: 10px;">GRAND TOTAL:</td>
                    <td class="text-right">Rp <?= number_format($total_rab, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_invoice, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_anggaran, 0, ',', '.') ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #888;">Tidak ada data proyek renovasi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
