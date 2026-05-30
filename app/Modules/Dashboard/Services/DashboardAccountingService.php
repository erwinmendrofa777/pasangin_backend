<?php

namespace App\Modules\Dashboard\Services;

/**
 * DashboardAccountingService
 *
 * Mengumpulkan statistik dan data agregat keuangan untuk Dashboard Accounting.
 */
class DashboardAccountingService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mengumpulkan seluruh metrik keuangan untuk dashboard akuntansi
     */
    public function getAccountingDashboardStats(): array
    {
        // 1. KPI (Statistik Utama)
        
        // Total Pendapatan Terbayar (Revenue)
        $designRevenue = $this->db->table('project_invoices')
            ->where('payment_status', 'PAID')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $constructionRevenue = $this->db->table('construction_invoices')
            ->where('status', 'PAID')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $renovationRevenue = $this->db->table('renovation_invoices')
            ->where('status', 'PAID')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $totalRevenue = $designRevenue + $constructionRevenue + $renovationRevenue;

        // Total Piutang Aktif (Tagihan Pending)
        $designReceivables = $this->db->table('project_invoices')
            ->where('payment_status', 'PENDING')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $constructionReceivables = $this->db->table('construction_invoices')
            ->where('status', 'PENDING')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $renovationReceivables = $this->db->table('renovation_invoices')
            ->where('status', 'PENDING')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;
            
        $totalReceivables = $designReceivables + $constructionReceivables + $renovationReceivables;

        // Antrean Pencairan Dana (Pending Payout)
        $pendingPayoutsRow = $this->db->table('withdrawal_requests')
            ->where('status', 'pending')
            ->selectCount('id', 'count')
            ->selectSum('amount', 'amount')
            ->get()->getRowArray();
            
        $pendingPayoutsCount = $pendingPayoutsRow['count'] ?? 0;
        $pendingPayoutsAmount = $pendingPayoutsRow['amount'] ?? 0;

        // Total Saldo Dompet Tukang (Liabilitas)
        $totalTukangBalance = $this->db->table('tukang')
            ->selectSum('balance')
            ->get()->getRowArray()['balance'] ?? 0;

        // Pencairan Dana Berhasil (Approved Payout)
        $approvedPayoutsAmount = $this->db->table('withdrawal_requests')
            ->where('status', 'approved')
            ->selectSum('amount')
            ->get()->getRowArray()['amount'] ?? 0;

        // Total Potongan Voucher (Diskon)
        $designVoucherDiscount = $this->db->table('project_invoices')
            ->join('vouchers', 'vouchers.code = project_invoices.voucher_code')
            ->where('project_invoices.payment_status', 'PAID')
            ->selectSum('vouchers.discount_nominal')
            ->get()->getRowArray()['discount_nominal'] ?? 0;

        $constructionVoucherDiscount = $this->db->table('construction_invoices')
            ->join('vouchers', 'vouchers.code = construction_invoices.voucher_code')
            ->where('construction_invoices.status', 'PAID')
            ->selectSum('vouchers.discount_nominal')
            ->get()->getRowArray()['discount_nominal'] ?? 0;

        $renovationVoucherDiscount = $this->db->table('renovation_invoices')
            ->join('vouchers', 'vouchers.code = renovation_invoices.voucher_code')
            ->where('renovation_invoices.status', 'PAID')
            ->selectSum('vouchers.discount_nominal')
            ->get()->getRowArray()['discount_nominal'] ?? 0;

        $totalVoucherDiscount = $designVoucherDiscount + $constructionVoucherDiscount + $renovationVoucherDiscount;

        // 2. Data Grafik: Tren Arus Kas Bulanan (6 Bulan Terakhir)
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $months[$monthKey] = [
                'label' => date('M Y', strtotime("-$i months")),
                'income' => 0,
                'expense' => 0
            ];
        }

        $sixMonthsAgo = date('Y-m-01 00:00:00', strtotime('-5 months'));

        $designMonthly = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total
            FROM project_invoices
            WHERE payment_status = 'PAID' AND created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();

        $constructionMonthly = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total
            FROM construction_invoices
            WHERE status = 'PAID' AND created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();

        $renovationMonthly = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total
            FROM renovation_invoices
            WHERE status = 'PAID' AND created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();

        $payoutsMonthly = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total
            FROM withdrawal_requests
            WHERE status = 'approved' AND created_at >= ?
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ", [$sixMonthsAgo])->getResultArray();

        foreach ($designMonthly as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['income'] += $row['total'];
            }
        }
        foreach ($constructionMonthly as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['income'] += $row['total'];
            }
        }
        foreach ($renovationMonthly as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['income'] += $row['total'];
            }
        }
        foreach ($payoutsMonthly as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['expense'] += $row['total'];
            }
        }

        $chartMonthlyLabels = [];
        $chartMonthlyIncome = [];
        $chartMonthlyExpense = [];

        foreach ($months as $data) {
            $chartMonthlyLabels[] = $data['label'];
            $chartMonthlyIncome[] = (int)$data['income'];
            $chartMonthlyExpense[] = (int)$data['expense'];
        }

        // 3. Tabel Detail & Aktivitas Terbaru
        
        // 5 Antrean Tarik Dana Terkini
        $pendingWithdrawals = $this->db->table('withdrawal_requests')
            ->select('withdrawal_requests.*, tukang.name as tukang_name, tukang.phone')
            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id')
            ->where('withdrawal_requests.status', 'pending')
            ->orderBy('withdrawal_requests.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 5 Invoice Terbaru (Gabungan 3 Divisi)
        $recentInvoices = $this->db->query("
            (SELECT 'Desain' as tipe, id, description, amount, voucher_code, payment_status as status, created_at FROM project_invoices)
            UNION ALL
            (SELECT 'Konstruksi' as tipe, id, description, amount, voucher_code, status, created_at FROM construction_invoices)
            UNION ALL
            (SELECT 'Renovasi' as tipe, id, description, amount, voucher_code, status, created_at FROM renovation_invoices)
            ORDER BY created_at DESC
            LIMIT 5
        ")->getResultArray();

        // 5 Voucher Promo Aktif
        $activeVouchers = $this->db->table('vouchers')
            ->where('is_active', 1)
            ->where('valid_until >=', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 4. Perhitungan Realisasi & Selisih Anggaran Proyek Konstruksi & Renovasi
        $projectRealizations = [];
        $totalProjectBudget = 0;
        $totalProjectRealization = 0;

        // Proyek Konstruksi
        $constructions = $this->db->table('construction_requests')
            ->select('id, full_name, address, status')
            ->get()->getResultArray();

        foreach ($constructions as $c) {
            $cId = $c['id'];
            
            $totalRAB = $this->db->table('construction_rabs')
                ->where('construction_id', $cId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;
                
            $totalAddendum = $this->db->table('construction_addendum')
                ->where('construction_id', $cId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;
                
            $totalBudget = $totalRAB + $totalAddendum;
            
            if ($totalBudget <= 0) {
                continue;
            }
            
            $progressRAB = $this->db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->where('cp.construction_id', $cId)
                ->where('cp.status', 'APPROVED')
                ->where('ct.id_construction_rabs IS NOT NULL')
                ->selectSum('cp.bobot')
                ->get()->getRowArray()['bobot'] ?? 0;
                
            $progressAddendum = $this->db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->where('cp.construction_id', $cId)
                ->where('cp.status', 'APPROVED')
                ->where('ct.id_construction_addendum IS NOT NULL')
                ->selectSum('cp.bobot')
                ->get()->getRowArray()['bobot'] ?? 0;
                
            $realizationRAB = ($progressRAB / 100) * $totalRAB;
            $realizationAddendum = ($progressAddendum / 100) * $totalAddendum;
            $realization = $realizationRAB + $realizationAddendum;
            
            $difference = $totalBudget - $realization;
            
            $projectRealizations[] = [
                'id'          => $cId,
                'name'        => $c['full_name'] ?: 'Tanpa Nama',
                'address'     => $c['address'] ?: 'Tanpa Alamat',
                'type'        => 'Konstruksi',
                'budget'      => $totalBudget,
                'realization' => $realization,
                'difference'  => $difference,
                'status'      => $c['status'],
                'detail_url'  => base_url('admin/construction/target/' . $cId)
            ];
            
            $totalProjectBudget += $totalBudget;
            $totalProjectRealization += $realization;
        }

        // Proyek Renovasi
        $renovations = $this->db->table('renovation_requests')
            ->select('id, full_name, address, status')
            ->get()->getResultArray();

        foreach ($renovations as $r) {
            $rId = $r['id'];
            
            $totalRAB = $this->db->table('renovation_rabs')
                ->where('renovation_id', $rId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;
                
            $totalBudget = $totalRAB;
            
            if ($totalBudget <= 0) {
                continue;
            }
            
            $progressRAB = $this->db->table('renovation_progress rp')
                ->join('renovation_targets rt', 'rt.id = rp.id_renovation_targets')
                ->where('rp.renovation_id', $rId)
                ->where('rp.status', 'APPROVED')
                ->where('rt.id_renovation_rabs IS NOT NULL')
                ->selectSum('rp.bobot')
                ->get()->getRowArray()['bobot'] ?? 0;
                
            $realization = ($progressRAB / 100) * $totalBudget;
            $difference = $totalBudget - $realization;
            
            $projectRealizations[] = [
                'id'          => $rId,
                'name'        => $r['full_name'] ?: 'Tanpa Nama',
                'address'     => $r['address'] ?: 'Tanpa Alamat',
                'type'        => 'Renovasi',
                'budget'      => $totalBudget,
                'realization' => $realization,
                'difference'  => $difference,
                'status'      => $r['status'],
                'detail_url'  => base_url('admin/renovation/target/' . $rId)
            ];
            
            $totalProjectBudget += $totalBudget;
            $totalProjectRealization += $realization;
        }

        $totalProjectDifference = $totalProjectBudget - $totalProjectRealization;

        return [
            'kpis' => [
                'total_revenue'           => $totalRevenue,
                'total_receivables'       => $totalReceivables,
                'pending_payouts_count'   => $pendingPayoutsCount,
                'pending_payouts_amount'  => $pendingPayoutsAmount,
                'total_tukang_balance'    => $totalTukangBalance,
                'approved_payouts_amount' => $approvedPayoutsAmount,
                'total_voucher_discount'  => $totalVoucherDiscount,
                'total_project_budget'      => $totalProjectBudget,
                'total_project_realization' => $totalProjectRealization,
                'total_project_difference'  => $totalProjectDifference,
            ],
            'charts' => [
                'cashflow_monthly' => [
                    'labels'  => $chartMonthlyLabels,
                    'income'  => $chartMonthlyIncome,
                    'expense' => $chartMonthlyExpense,
                ],
                'division_revenue' => [
                    'labels' => ['Desain', 'Konstruksi', 'Renovasi'],
                    'data'   => [
                        (int)$designRevenue,
                        (int)$constructionRevenue,
                        (int)$renovationRevenue
                    ]
                ]
            ],
            'pendingWithdrawals'  => $pendingWithdrawals,
            'recentInvoices'      => $recentInvoices,
            'activeVouchers'      => $activeVouchers,
            'projectRealizations' => $projectRealizations,
        ];
    }
}
