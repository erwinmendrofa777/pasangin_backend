<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Exception;

class TukangGroupApi extends ResourceController
{
    protected $format = 'json';

    /**
     * 1. CREATE GROUP (Mandor Only)
     * POST: api/tukang/group/create
     */
    public function create()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $rules = [
            'name_group' => 'required|min_length[3]|max_length[100]'
        ];

        $messages = [
            'name_group' => [
                'required' => 'Nama grup wajib diisi.',
                'min_length' => 'Nama grup terlalu pendek (minimal 3 karakter).',
                'max_length' => 'Nama grup terlalu panjang (maksimal 100 karakter).'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        
        try {
            // Cek jika Mandor sudah memiliki grup
            $existing = $groupModel->where('tukang_id', $user->uid)->first();
            if ($existing) {
                return $this->fail('Anda sudah memiliki grup.', 400);
            }

            $referralCode = $this->_generateReferralCode();

            $groupModel->insert([
                'name_group' => $this->request->getVar('name_group'),
                'tukang_id' => $user->uid,
                'referral_code' => $referralCode,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated([
                'status' => true,
                'message' => 'Grup berhasil dibuat.',
                'data' => [
                    'id' => $groupModel->getInsertID(),
                    'name_group' => $this->request->getVar('name_group'),
                    'referral_code' => $referralCode
                ]
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 2. GET GROUP DETAILS & MEMBERS (Mandor Only)
     * GET: api/tukang/group/detail
     */
    public function getGroup()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }
 
        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        
        try {
            $group = $groupModel->where('tukang_id', $user->uid)->first();
            if (!$group) {
                return $this->failNotFound('Grup tidak ditemukan.');
            }
 
            $db = \Config\Database::connect();
            $members = $db->table('tukang_group_members tgm')
                ->select('tgm.id as member_record_id, tgm.tukang_id, tgm.status, tgm.joined_at, t.name, t.email, t.phone, t.profile_photo')
                ->join('tukang t', 't.id = tgm.tukang_id')
                ->where('tgm.tukang_group_id', $group['id'])
                ->get()
                ->getResultArray();
 
            // Format profile photo URL
            foreach ($members as &$member) {
                if (!empty($member['profile_photo'])) {
                    $member['profile_photo'] = base_url('uploads/tukang/' . $member['profile_photo']);
                }
            }
 
            $group['members'] = $members;
            $group['group_balance'] = isset($group['balance']) ? (float)$group['balance'] : 0.00;
            unset($group['balance']);
 
            return $this->respond([
                'status' => true,
                'message' => 'Detail grup berhasil diambil.',
                'data' => $group
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 3. UPDATE GROUP NAME (Mandor Only)
     * POST: api/tukang/group/update
     */
    public function update($id = null)
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $rules = [
            'name_group' => 'required|min_length[3]|max_length[100]'
        ];

        $messages = [
            'name_group' => [
                'required' => 'Nama grup wajib diisi.',
                'min_length' => 'Nama grup terlalu pendek (minimal 3 karakter).',
                'max_length' => 'Nama grup terlalu panjang (maksimal 100 karakter).'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();

        try {
            $group = $groupModel->where('tukang_id', $user->uid)->first();
            if (!$group) {
                return $this->failNotFound('Grup tidak ditemukan.');
            }

            $groupModel->update($group['id'], [
                'name_group' => $this->request->getVar('name_group')
            ]);

            return $this->respond([
                'status' => true,
                'message' => 'Nama grup berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 4. UPDATE MEMBER STATUS (Mandor Only)
     * POST: api/tukang/group/member-status
     */
    public function updateMemberStatus()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $rules = [
            'member_id' => 'required|integer',
            'status' => 'required|in_list[approved,rejected]'
        ];

        $messages = [
            'member_id' => [
                'required' => 'ID Anggota wajib diisi.',
                'integer' => 'ID Anggota harus berupa angka.'
            ],
            'status' => [
                'required' => 'Status wajib diisi.',
                'in_list' => 'Status harus approved atau rejected.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        $memberModel = new \App\Modules\Tukang\Models\TukangGroupMemberModel();

        try {
            $group = $groupModel->where('tukang_id', $user->uid)->first();
            if (!$group) {
                return $this->failNotFound('Grup tidak ditemukan.');
            }

            $member = $memberModel->where('id', $this->request->getVar('member_id'))
                                  ->where('tukang_group_id', $group['id'])
                                  ->first();
            if (!$member) {
                return $this->failNotFound('Data anggota grup tidak ditemukan.');
            }

            $updateData = [
                'status' => $this->request->getVar('status')
            ];

            if ($this->request->getVar('status') === 'approved') {
                $updateData['joined_at'] = date('Y-m-d H:i:s');
            }

            $memberModel->update($member['id'], $updateData);

            return $this->respond([
                'status' => true,
                'message' => 'Status keanggotaan berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 5. REMOVE MEMBER (Mandor Only)
     * POST: api/tukang/group/remove-member
     */
    public function removeMember()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $rules = [
            'tukang_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        $memberModel = new \App\Modules\Tukang\Models\TukangGroupMemberModel();

        try {
            $group = $groupModel->where('tukang_id', $user->uid)->first();
            if (!$group) {
                return $this->failNotFound('Grup tidak ditemukan.');
            }

            $member = $memberModel->where('tukang_id', $this->request->getVar('tukang_id'))
                                  ->where('tukang_group_id', $group['id'])
                                  ->first();
            if (!$member) {
                return $this->failNotFound('Anggota tidak ditemukan di grup Anda.');
            }

            $memberModel->delete($member['id']);

            return $this->respond([
                'status' => true,
                'message' => 'Anggota berhasil dihapus dari grup.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 6. JOIN GROUP (Tukang Only)
     * POST: api/tukang/group/join
     */
    public function join()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'tukang') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Tukang.');
        }

        $rules = [
            'referral_code' => 'required|alpha_numeric|exact_length[10]'
        ];

        $messages = [
            'referral_code' => [
                'required' => 'Kode referral wajib diisi.',
                'alpha_numeric' => 'Kode referral harus berupa alfanumerik.',
                'exact_length' => 'Kode referral harus tepat 10 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        $memberModel = new \App\Modules\Tukang\Models\TukangGroupMemberModel();

        try {
            // Cek apakah tukang sudah memiliki grup (baik pending maupun approved)
            $existing = $memberModel->where('tukang_id', $user->uid)
                                   ->groupStart()
                                       ->where('status', 'pending')
                                       ->orWhere('status', 'approved')
                                   ->groupEnd()
                                   ->first();
            if ($existing) {
                return $this->fail('Anda sudah bergabung atau sedang pending di grup lain.', 400);
            }

            // Hapus record status rejected sebelumnya jika ada agar tukang bisa mendaftar ulang
            $memberModel->where('tukang_id', $user->uid)->where('status', 'rejected')->delete();

            // Cari grup berdasarkan referral code
            $group = $groupModel->where('referral_code', strtoupper($this->request->getVar('referral_code')))->first();
            if (!$group) {
                return $this->failNotFound('Kode referral tidak valid atau grup tidak ditemukan.');
            }

            $memberModel->insert([
                'tukang_group_id' => $group['id'],
                'tukang_id' => $user->uid,
                'status' => 'pending',
                'joined_at' => null
            ]);

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permintaan bergabung berhasil dikirim. Menunggu persetujuan Mandor.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 7. LEAVE GROUP (Tukang Only)
     * POST: api/tukang/group/leave
     */
    public function leave()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'tukang') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Tukang.');
        }

        $memberModel = new \App\Modules\Tukang\Models\TukangGroupMemberModel();

        try {
            // Cari grup di mana tukang terdaftar (pending atau approved)
            $member = $memberModel->where('tukang_id', $user->uid)
                                  ->groupStart()
                                      ->where('status', 'pending')
                                      ->orWhere('status', 'approved')
                                  ->groupEnd()
                                  ->first();
            if (!$member) {
                return $this->failNotFound('Anda tidak tergabung dalam grup manapun.');
            }

            $memberModel->delete($member['id']);

            return $this->respond([
                'status' => true,
                'message' => 'Anda berhasil keluar dari grup.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 8. MY STATUS (Tukang Only)
     * GET: api/tukang/group/my-status
     */
    public function myStatus()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'tukang') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Tukang.');
        }
 
        try {
            $db = \Config\Database::connect();
            $member = $db->table('tukang_group_members tgm')
                ->select('tgm.id as member_record_id, tgm.status, tgm.joined_at, tg.id as group_id, tg.name_group, tg.referral_code, tg.balance as group_balance, m.name as mandor_name, m.phone as mandor_phone')
                ->join('tukang_group tg', 'tg.id = tgm.tukang_group_id')
                ->join('tukang m', 'm.id = tg.tukang_id')
                ->where('tgm.tukang_id', $user->uid)
                ->groupStart()
                    ->where('tgm.status', 'pending')
                    ->orWhere('tgm.status', 'approved')
                    ->orWhere('tgm.status', 'rejected')
                ->groupEnd()
                ->get()
                ->getRowArray();
 
            if (!$member) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Anda tidak memiliki grup.',
                    'data' => null
                ]);
            }
 
            $member['group_balance'] = isset($member['group_balance']) ? (float)$member['group_balance'] : 0.00;
 
            return $this->respond([
                'status' => true,
                'message' => 'Status keanggotaan grup berhasil diambil.',
                'data' => $member
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 9. GET MY GROUP REQUESTS (Tukang Only)
     * GET: api/tukang/group/requests
     */
    public function myRequests()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'tukang') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Tukang.');
        }

        try {
            $db = \Config\Database::connect();
            $requests = $db->table('tukang_group_members tgm')
                ->select('tgm.id as member_record_id, tgm.status, tgm.joined_at, tg.id as group_id, tg.name_group, tg.referral_code, m.name as mandor_name, m.phone as mandor_phone')
                ->join('tukang_group tg', 'tg.id = tgm.tukang_group_id')
                ->join('tukang m', 'm.id = tg.tukang_id')
                ->where('tgm.tukang_id', $user->uid)
                ->orderBy('tgm.id', 'DESC')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => true,
                'message' => 'Daftar permintaan persetujuan grup berhasil diambil.',
                'data' => $requests
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
 
    /**
     * 10. BULK DISTRIBUTE GROUP BALANCE (Mandor Only)
     * POST: api/tukang/group/distribute-bulk
     */
    public function distributeBulk()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $rawDistributions = $this->request->getVar('distributions');
        $distributions = is_array($rawDistributions) ? json_decode(json_encode($rawDistributions), true) : null;
        if (!is_array($distributions) || empty($distributions)) {
            return $this->respond(['status' => false, 'message' => 'Data distribusi wajib berupa array dan tidak boleh kosong.'], 400);
        }

        $sourceProjectType = $this->request->getVar('source_project_type');
        $sourceInvoiceId = $this->request->getVar('source_invoice_id'); // Ini adalah ID progress

        if (empty($sourceProjectType) || !in_array($sourceProjectType, ['construction', 'renovation'], true)) {
            return $this->respond(['status' => false, 'message' => 'source_project_type wajib diisi dan bernilai construction atau renovation.'], 400);
        }
        if (empty($sourceInvoiceId) || !is_numeric($sourceInvoiceId)) {
            return $this->respond(['status' => false, 'message' => 'source_invoice_id (ID progress pekerjaan) wajib diisi.'], 400);
        }

        // Validasi input format
        foreach ($distributions as $index => $dist) {
            if (!isset($dist['tukang_id']) || !is_numeric($dist['tukang_id'])) {
                return $this->respond(['status' => false, 'message' => "tukang_id pada indeks {$index} wajib diisi dan berupa angka."], 400);
            }
            if (!isset($dist['amount']) || !is_numeric($dist['amount']) || $dist['amount'] <= 0) {
                return $this->respond(['status' => false, 'message' => "amount pada indeks {$index} wajib berupa angka bernilai positif."], 400);
            }
        }

        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();
        
        try {
            $group = $groupModel->where('tukang_id', $user->uid)->first();
            if (!$group) {
                return $this->failNotFound('Grup Anda tidak ditemukan.');
            }

            $db = \Config\Database::connect();

            // 1. Validasi keberadaan inflow pekerjaan ini
            $inflow = $db->table('group_transactions')
                ->where('group_id', $group['id'])
                ->where('type', 'inflow')
                ->where('source_project_type', $sourceProjectType)
                ->where('source_invoice_id', $sourceInvoiceId)
                ->get()->getRowArray();

            if (!$inflow) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Pekerjaan/Progress dengan ID tersebut belum disetujui client atau tidak ditemukan.'
                ], 400);
            }

            $inflowAmount = (float)$inflow['amount'];

            // 2. Hitung total yang sudah didistribusikan (baik approved maupun pending)
            $outflowSum = $db->table('group_transactions')
                ->select('SUM(amount) as total')
                ->where('group_id', $group['id'])
                ->where('type', 'outflow')
                ->where('source_project_type', $sourceProjectType)
                ->where('source_invoice_id', $sourceInvoiceId)
                ->whereIn('status', ['approved', 'pending'])
                ->get()->getRowArray();
            
            $alreadyDistributed = $outflowSum ? (float)$outflowSum['total'] : 0.0;
            $remainingUndistributed = max(0.0, $inflowAmount - $alreadyDistributed);

            $totalAllocated = 0.0;
            foreach ($distributions as $dist) {
                $totalAllocated += (float)$dist['amount'];
            }

            if ($totalAllocated > $remainingUndistributed) {
                $formattedRemaining = number_format($remainingUndistributed, 0, ',', '.');
                $formattedAllocated = number_format($totalAllocated, 0, ',', '.');
                return $this->respond([
                    'status' => false,
                    'message' => "Total pembagian (Rp {$formattedAllocated}) melebihi sisa saldo pekerjaan yang belum dibagikan (Rp {$formattedRemaining})."
                ], 400);
            }

            $currentBalance = (float)$group['balance'];
            if ($currentBalance < $totalAllocated) {
                $formattedAllocated = number_format($totalAllocated, 0, ',', '.');
                return $this->respond([
                    'status' => false,
                    'message' => "Total pembagian (Rp {$formattedAllocated}) melebihi saldo grup saat ini."
                ], 400);
            }

            // Ambil list member approved untuk divalidasi
            $approvedMembers = $db->table('tukang_group_members')
                ->where('tukang_group_id', $group['id'])
                ->where('status', 'approved')
                ->get()
                ->getResultArray();
            
            $approvedTukangIds = array_map('intval', array_column($approvedMembers, 'tukang_id'));
            // Tambahkan ID mandor kelompok sendiri ke dalam daftar penerima yang sah
            $approvedTukangIds[] = (int)$group['tukang_id'];
 
            foreach ($distributions as $dist) {
                if (!in_array((int)$dist['tukang_id'], $approvedTukangIds, true)) {
                    return $this->respond([
                        'status' => false,
                        'message' => "Tukang ID {$dist['tukang_id']} bukan anggota aktif/disetujui di grup Anda."
                    ], 400);
                }
            }

            $totalMembers = count($approvedMembers);

            // Mulai DB Transaction
            $db->transStart();

            $groupTxModel = new \App\Modules\Tukang\Models\GroupTransactionsModel();

            // JIKA ANGGOTA KELOMPOK = 0 (Hanya mandor bekerja sendiri) -> Auto Approve Instan
            if ($totalMembers === 0) {
                // 1. Kurangi balance tukang_group
                $newGroupBalance = $currentBalance - $totalAllocated;
                $groupModel->update($group['id'], ['balance' => $newGroupBalance]);

                // 2. Catat outflow di group_transactions langsung approved
                $groupTxModel->insert([
                    'group_id' => $group['id'],
                    'amount' => $totalAllocated,
                    'type' => 'outflow',
                    'status' => 'approved',
                    'source_project_type' => $sourceProjectType,
                    'source_invoice_id' => $sourceInvoiceId,
                    'description' => 'Distribusi saldo grup langsung cair ke Mandor (tidak ada anggota lain).',
                    'distributions_data' => json_encode($distributions),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $groupTxId = $groupTxModel->getInsertID();

                // 3. Distribusikan ke Mandor
                $tukangModel = new \App\Modules\Tukang\Models\TukangModel();
                $tukangTxModel = new \App\Modules\Tukang\Models\TukangTransactionsModel();

                foreach ($distributions as $dist) {
                    $tukangId = (int)$dist['tukang_id'];
                    $amount = (float)$dist['amount'];

                    $tukang = $tukangModel->find($tukangId);
                    if ($tukang) {
                        $newTukangBalance = (float)$tukang['balance'] + $amount;
                        $tukangModel->update($tukangId, ['balance' => $newTukangBalance]);

                        $tukangTxModel->insert([
                            'tukang_id' => $tukangId,
                            'group_transaction_id' => $groupTxId,
                            'amount' => $amount,
                            'type' => 'income',
                            'description' => 'Penerimaan upah kelompok (Auto-approved)',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                $db->transComplete();

                return $this->respond([
                    'status' => true,
                    'message' => 'Berhasil mendistribusikan saldo grup (langsung cair karena tidak ada anggota lain).'
                ]);

            } else {
                // JIKA ADA ANGGOTA KELOMPOK -> Buat Transaksi Pending & Tunggu Persetujuan Mayoritas
                $groupTxModel->insert([
                    'group_id' => $group['id'],
                    'amount' => $totalAllocated,
                    'type' => 'outflow',
                    'status' => 'pending',
                    'source_project_type' => $sourceProjectType,
                    'source_invoice_id' => $sourceInvoiceId,
                    'description' => 'Usulan distribusi saldo untuk progress #' . $sourceInvoiceId . ' ke ' . count($distributions) . ' penerima.',
                    'distributions_data' => json_encode($distributions),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->respond([
                        'status' => false,
                        'message' => 'Gagal membuat usulan distribusi saldo (transaksi database gagal).'
                    ], 500);
                }

                // Kirim notifikasi push ke semua anggota aktif kelompok
                $notifService = new \App\Modules\Notifications\Services\NotificationService();
                $title = "Persetujuan Pembagian Upah";
                $message = "Mandor mengajukan pembagian upah Rp " . number_format($totalAllocated, 0, ',', '.') . " untuk pekerjaan " . ($inflow['description'] ?? 'pekerjaan konstruksi') . ". Silakan tinjau dan beri persetujuan.";
                
                foreach ($approvedMembers as $m) {
                    $notifService->sendPersonal('tukang', (int)$m['tukang_id'], $title, $message);
                }

                return $this->respond([
                    'status' => true,
                    'message' => 'Usulan pembagian saldo berhasil dikirim. Menunggu persetujuan dari mayoritas anggota kelompok.'
                ]);
            }

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 11. GET GROUP TRANSACTION HISTORY
     * GET: api/tukang/group/transactions
     */
    public function transactions()
    {
        $user = $this->request->user ?? null;
        if (!$user) {
            return $this->failUnauthorized('Akses ditolak. Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $groupId = null;

        if ($user->role === 'mandor') {
            $group = $db->table('tukang_group')->where('tukang_id', $user->uid)->get()->getRowArray();
            if ($group) {
                $groupId = $group['id'];
            }
        } elseif ($user->role === 'tukang') {
            $member = $db->table('tukang_group_members')
                ->where('tukang_id', $user->uid)
                ->where('status', 'approved')
                ->get()
                ->getRowArray();
            if ($member) {
                $groupId = $member['tukang_group_id'];
            }
        }

        if (!$groupId) {
            return $this->failNotFound('Kelompok tidak ditemukan atau Anda bukan anggota aktif kelompok.');
        }

        try {
            // Setup pagination
            $page = $this->request->getVar('page') ?: 1;
            $limit = $this->request->getVar('limit') ?: 20;
            $offset = ($page - 1) * $limit;

            // Fetch transactions
            $transactions = $db->table('group_transactions')
                ->where('group_id', $groupId)
                ->orderBy('id', 'DESC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            // Total count for pagination
            $totalCount = $db->table('group_transactions')
                ->where('group_id', $groupId)
                ->countAllResults();

            // Enrich outflow transactions with distribution details
            foreach ($transactions as &$tx) {
                $tx['amount'] = (float)$tx['amount'];
                $tx['source_invoice_id'] = $tx['source_invoice_id'] ? (int)$tx['source_invoice_id'] : null;
                
                if ($tx['type'] === 'outflow') {
                    if ($tx['status'] === 'approved') {
                        $recipients = $db->table('tukang_transactions tt')
                            ->select('tt.tukang_id, tt.amount, t.name as member_name')
                            ->join('tukang t', 't.id = tt.tukang_id')
                            ->where('tt.group_transaction_id', $tx['id'])
                            ->get()
                            ->getResultArray();
                        
                        foreach ($recipients as &$r) {
                            $r['amount'] = (float)$r['amount'];
                        }
                        $tx['distributions'] = $recipients;
                    } else {
                        // Jika masih pending atau rejected, baca dari distributions_data JSON
                        $rawDist = json_decode($tx['distributions_data'] ?? '[]', true);
                        $formattedDist = [];
                        if (!empty($rawDist)) {
                            $tIds = array_column($rawDist, 'tukang_id');
                            $tukangs = $db->table('tukang')->select('id, name')->whereIn('id', $tIds)->get()->getResultArray();
                            $nameMap = array_column($tukangs, 'name', 'id');
                            
                            foreach ($rawDist as $d) {
                                $formattedDist[] = [
                                    'tukang_id' => (int)$d['tukang_id'],
                                    'amount' => (float)$d['amount'],
                                    'member_name' => $nameMap[$d['tukang_id']] ?? 'Tidak Dikenal'
                                ];
                            }
                        }
                        $tx['distributions'] = $formattedDist;
                    }
                } else {
                    $tx['distributions'] = [];
                }
            }

            return $this->respond([
                'status' => true,
                'message' => 'Riwayat transaksi kelompok berhasil diambil.',
                'data' => [
                    'transactions' => $transactions,
                    'pagination' => [
                        'current_page' => (int)$page,
                        'limit' => (int)$limit,
                        'total_records' => $totalCount,
                        'total_pages' => ceil($totalCount / $limit)
                    ]
                ]
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 12. GET JOB FINANCIAL BALANCES & TARGET WAGES
     * GET: api/tukang/group/job-balances
     */
    public function jobBalances()
    {
        $user = $this->request->user ?? null;
        if (!$user || $user->role !== 'mandor') {
            return $this->failUnauthorized('Akses ditolak. Hanya untuk Mandor.');
        }

        $db = \Config\Database::connect();
        $group = $db->table('tukang_group')->where('tukang_id', $user->uid)->get()->getRowArray();
        if (!$group) {
            return $this->failNotFound('Grup Anda tidak ditemukan.');
        }

        try {
            // 1. Ambil semua target konstruksi yang terhubung ke job_applications mandor
            $targetsRaw = $db->table('construction_targets ct')
                ->select('
                    ct.id as target_id,
                    ct.construction_id,
                    COALESCE(ahsp.uraian, ca.activity_name) as target_name,
                    COALESCE(rab.volume, ca.volume) as target_volume,
                    COALESCE(rab.unit, ca.unit) as unit,
                    rab.ahsp_id
                ')
                ->join('job_applications ja', 'ja.id = ct.id_job_applications')
                ->join('rabs rab', 'rab.id = ct.id_construction_rabs', 'left')
                ->join('ahsp', 'ahsp.id = rab.ahsp_id', 'left')
                ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
                ->where('ja.tukang_id', $user->uid)
                ->where('ja.project_type', 'construction')
                ->get()
                ->getResultArray();

            if (empty($targetsRaw)) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Belum ada target pekerjaan konstruksi yang terhubung.',
                    'data' => []
                ]);
            }

            // Ambil list ahsp_id unik untuk di-query upah tenaganya secara massal
            $ahspIds = array_filter(array_unique(array_column($targetsRaw, 'ahsp_id')));
            $ahspUpahMap = [];
            if (!empty($ahspIds)) {
                $upahRaw = $db->table('ahsp_tenaga_kerja')
                    ->select('ahsp_id, SUM(harga_satuan * koefisien) as total_upah')
                    ->whereIn('ahsp_id', $ahspIds)
                    ->groupBy('ahsp_id')
                    ->get()
                    ->getResultArray();
                foreach ($upahRaw as $u) {
                    $ahspUpahMap[(int)$u['ahsp_id']] = (float)$u['total_upah'];
                }
            }

            $targetIds = array_column($targetsRaw, 'target_id');

            // 2. Ambil semua laporan progress APPROVED untuk target-target ini
            $progressRaw = $db->table('construction_progress cp')
                ->select('cp.id as progress_id, cp.id_construction_targets as target_id, cp.volume as progress_volume, cp.description, cp.created_at')
                ->whereIn('cp.id_construction_targets', $targetIds)
                ->where('cp.status', 'APPROVED')
                ->orderBy('cp.created_at', 'DESC')
                ->get()
                ->getResultArray();

            $progressIds = array_column($progressRaw, 'progress_id');

            // 3. Ambil total inflow dan outflow dari group_transactions untuk progress-progress ini
            $transactionsRaw = [];
            if (!empty($progressIds)) {
                $transactionsRaw = $db->table('group_transactions')
                    ->select('source_invoice_id as progress_id, type, status, SUM(amount) as total_amount')
                    ->where('group_id', $group['id'])
                    ->where('source_project_type', 'construction')
                    ->whereIn('source_invoice_id', $progressIds)
                    ->groupBy('source_invoice_id, type, status')
                    ->get()
                    ->getResultArray();
            }

            // Map transactions by progress_id and type/status
            $txMap = [];
            foreach ($transactionsRaw as $tx) {
                $pId = (int)$tx['progress_id'];
                $type = $tx['type'];
                $status = $tx['status'];
                $amt = (float)$tx['total_amount'];

                if (!isset($txMap[$pId])) {
                    $txMap[$pId] = [
                        'inflow' => 0.0,
                        'outflow_approved' => 0.0,
                        'outflow_pending' => 0.0
                    ];
                }

                if ($type === 'inflow') {
                    $txMap[$pId]['inflow'] += $amt;
                } elseif ($type === 'outflow') {
                    if ($status === 'approved') {
                        $txMap[$pId]['outflow_approved'] += $amt;
                    } elseif ($status === 'pending') {
                        $txMap[$pId]['outflow_pending'] += $amt;
                    }
                }
            }

            // Kelompokkan progress ke dalam masing-masing target
            $progressByTarget = [];
            foreach ($progressRaw as $p) {
                $tId = (int)$p['target_id'];
                $pId = (int)$p['progress_id'];

                $inflow = $txMap[$pId]['inflow'] ?? 0.0;
                $outflowApproved = $txMap[$pId]['outflow_approved'] ?? 0.0;
                $outflowPending = $txMap[$pId]['outflow_pending'] ?? 0.0;
                $undistributed = max(0.0, $inflow - ($outflowApproved + $outflowPending));

                $progressByTarget[$tId][] = [
                    'progress_id' => $pId,
                    'volume' => (float)$p['progress_volume'],
                    'description' => $p['description'],
                    'created_at' => $p['created_at'],
                    'wages_received' => $inflow,
                    'wages_distributed' => $outflowApproved,
                    'wages_pending' => $outflowPending,
                    'undistributed_balance' => $undistributed
                ];
            }

            // 4. Bangun data respons terstruktur per pekerjaan (target)
            $formattedData = [];
            foreach ($targetsRaw as $t) {
                $tId = (int)$t['target_id'];
                $ahspId = $t['ahsp_id'] ? (int)$t['ahsp_id'] : null;
                $upahPerVolume = $ahspId ? ($ahspUpahMap[$ahspId] ?? 0.0) : 0.0;

                $targetVolume = (float)$t['target_volume'];
                $totalWages = $targetVolume * $upahPerVolume;

                $progressList = $progressByTarget[$tId] ?? [];

                // Hitung akumulasi dari progress-progress
                $wagesReceived = 0.0;
                $wagesDistributed = 0.0;
                $wagesPending = 0.0;
                $undistributed = 0.0;

                foreach ($progressList as $p) {
                    $wagesReceived += $p['wages_received'];
                    $wagesDistributed += $p['wages_distributed'];
                    $wagesPending += $p['wages_pending'];
                    $undistributed += $p['undistributed_balance'];
                }

                $unreceivedBalance = max(0.0, $totalWages - $wagesReceived);

                $formattedData[] = [
                    'target_id' => $tId,
                    'construction_id' => (int)$t['construction_id'],
                    'job_name' => $t['target_name'],
                    'volume' => $targetVolume,
                    'unit' => $t['unit'],
                    'total_target_wages' => $totalWages,
                    'wages_received' => $wagesReceived,
                    'wages_distributed' => $wagesDistributed,
                    'wages_pending' => $wagesPending,
                    'undistributed_balance' => $undistributed,
                    'unreceived_balance' => $unreceivedBalance,
                    'progress_reports' => $progressList
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Daftar saldo pekerjaan kelompok berhasil diambil.',
                'data' => $formattedData
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 13. GET PENDING DISTRIBUTIONS (VOTING LIST)
     * GET: api/tukang/group/pending-distributions
     */
    public function pendingDistributions()
    {
        $user = $this->request->user ?? null;
        if (!$user) {
            return $this->failUnauthorized('Akses ditolak. Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $groupId = null;

        if ($user->role === 'mandor') {
            $group = $db->table('tukang_group')->where('tukang_id', $user->uid)->get()->getRowArray();
            if ($group) $groupId = $group['id'];
        } else {
            $member = $db->table('tukang_group_members')
                ->where('tukang_id', $user->uid)
                ->where('status', 'approved')
                ->get()->getRowArray();
            if ($member) $groupId = $member['tukang_group_id'];
        }

        if (!$groupId) {
            return $this->failNotFound('Kelompok tidak ditemukan atau Anda bukan anggota aktif kelompok.');
        }

        try {
            // Ambil anggota aktif kelompok (selain Mandor)
            $activeMembers = $db->table('tukang_group_members')
                ->where('tukang_group_id', $groupId)
                ->where('status', 'approved')
                ->get()->getResultArray();
            $totalMembers = count($activeMembers);

            // Ambil transaksi outflow pending
            $pendingTx = $db->table('group_transactions')
                ->where('group_id', $groupId)
                ->where('type', 'outflow')
                ->where('status', 'pending')
                ->orderBy('created_at', 'ASC')
                ->get()->getResultArray();

            $formatted = [];
            foreach ($pendingTx as $tx) {
                // Parse penerima
                $rawDist = json_decode($tx['distributions_data'] ?? '[]', true);
                $distributions = [];
                if (!empty($rawDist)) {
                    $tIds = array_column($rawDist, 'tukang_id');
                    $tukangs = $db->table('tukang')->select('id, name')->whereIn('id', $tIds)->get()->getResultArray();
                    $nameMap = array_column($tukangs, 'name', 'id');
                    
                    foreach ($rawDist as $d) {
                        $distributions[] = [
                            'tukang_id' => (int)$d['tukang_id'],
                            'amount' => (float)$d['amount'],
                            'member_name' => $nameMap[$d['tukang_id']] ?? 'Tidak Dikenal'
                        ];
                    }
                }

                // Hitung votes
                $votesRaw = $db->table('group_transaction_approvals')
                    ->where('group_transaction_id', $tx['id'])
                    ->get()->getResultArray();

                $approvedVotes = 0;
                $rejectedVotes = 0;
                $hasVoted = false;
                $userVote = null;

                foreach ($votesRaw as $v) {
                    if ($v['vote'] === 'approved') $approvedVotes++;
                    if ($v['vote'] === 'rejected') $rejectedVotes++;
                    if ((int)$v['tukang_id'] === (int)$user->uid) {
                        $hasVoted = true;
                        $userVote = $v['vote'];
                    }
                }

                $majorityRequired = floor($totalMembers / 2) + 1;

                $formatted[] = [
                    'id' => (int)$tx['id'],
                    'amount' => (float)$tx['amount'],
                    'source_project_type' => $tx['source_project_type'],
                    'source_invoice_id' => $tx['source_invoice_id'] ? (int)$tx['source_invoice_id'] : null,
                    'description' => $tx['description'],
                    'created_at' => $tx['created_at'],
                    'distributions' => $distributions,
                    'voting' => [
                        'total_voters' => $totalMembers,
                        'majority_required' => $majorityRequired,
                        'approved_votes' => $approvedVotes,
                        'rejected_votes' => $rejectedVotes,
                        'has_voted' => $hasVoted,
                        'user_vote' => $userVote
                    ]
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Daftar usulan pembagian saldo tertunda berhasil diambil.',
                'data' => $formatted
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 14. VOTE PENDING DISTRIBUTION (Tukang Members Only)
     * POST: api/tukang/group/vote-distribution
     */
    public function voteDistribution()
    {
        $user = $this->request->user ?? null;
        if (!$user) {
            return $this->failUnauthorized('Akses ditolak. Silakan login.');
        }

        $txId = $this->request->getVar('group_transaction_id');
        $vote = $this->request->getVar('vote'); // 'approved' atau 'rejected'

        if (empty($txId) || !is_numeric($txId)) {
            return $this->respond(['status' => false, 'message' => 'group_transaction_id wajib diisi dan berupa angka.'], 400);
        }
        if (empty($vote) || !in_array($vote, ['approved', 'rejected'], true)) {
            return $this->respond(['status' => false, 'message' => 'vote wajib bernilai approved atau rejected.'], 400);
        }

        $db = \Config\Database::connect();

        try {
            // 1. Verifikasi transaksi yang pending
            $tx = $db->table('group_transactions')
                ->where('id', $txId)
                ->where('type', 'outflow')
                ->where('status', 'pending')
                ->get()->getRowArray();

            if (!$tx) {
                return $this->respond(['status' => false, 'message' => 'Transaksi pending tidak ditemukan.'], 404);
            }

            $groupId = $tx['group_id'];

            // 2. Verifikasi pemilih adalah anggota aktif grup ini (Mandor dilarang vote)
            $member = $db->table('tukang_group_members')
                ->where('tukang_group_id', $groupId)
                ->where('tukang_id', $user->uid)
                ->where('status', 'approved')
                ->get()->getRowArray();

            if (!$member) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Akses ditolak. Hanya anggota aktif kelompok yang berhak memberikan persetujuan.'
                ], 403);
            }

            // 3. Cek apakah sudah pernah melakukan vote
            $existingVote = $db->table('group_transaction_approvals')
                ->where('group_transaction_id', $txId)
                ->where('tukang_id', $user->uid)
                ->get()->getRowArray();

            if ($existingVote) {
                return $this->respond(['status' => false, 'message' => 'Anda sudah memberikan suara untuk transaksi ini.'], 400);
            }

            // Hitung total anggota aktif kelompok (selain Mandor)
            $activeMembers = $db->table('tukang_group_members')
                ->where('tukang_group_id', $groupId)
                ->where('status', 'approved')
                ->get()->getResultArray();
            $totalMembers = count($activeMembers);

            $majorityRequired = floor($totalMembers / 2) + 1;

            // Mulai DB Transaction untuk merekam vote & mengevaluasi threshold
            $db->transStart();

            // Simpan vote
            $db->table('group_transaction_approvals')->insert([
                'group_transaction_id' => $txId,
                'tukang_id' => $user->uid,
                'vote' => $vote,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Hitung akumulasi vote terbaru
            $votes = $db->table('group_transaction_approvals')
                ->select('vote, COUNT(id) as count')
                ->where('group_transaction_id', $txId)
                ->groupBy('vote')
                ->get()->getResultArray();

            $approvedVotes = 0;
            $rejectedVotes = 0;
            foreach ($votes as $v) {
                if ($v['vote'] === 'approved') $approvedVotes = (int)$v['count'];
                if ($v['vote'] === 'rejected') $rejectedVotes = (int)$v['count'];
            }

            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $group = $db->table('tukang_group')->where('id', $groupId)->get()->getRowArray();
            $totalAmount = (float)$tx['amount'];

            // KONDISI A: Disetujui Mayoritas
            if ($approvedVotes >= $majorityRequired) {
                // Pastikan saldo kelompok masih cukup
                if ((float)$group['balance'] < $totalAmount) {
                    // Batalkan dan tolak otomatis jika saldo grup tidak mencukupi (double-spending safeguard)
                    $db->table('group_transactions')->where('id', $txId)->update(['status' => 'rejected']);
                    $db->transComplete();
                    
                    // Notifikasi ke Mandor
                    $notifService->sendPersonal('tukang', (int)$group['tukang_id'], "Pencairan Ditolak Sistem", "Saldo kas kelompok Anda tidak cukup untuk mencairkan usulan Rp " . number_format($totalAmount, 0, ',', '.') . ".");

                    return $this->respond([
                        'status' => false,
                        'message' => 'Transaksi otomatis ditolak oleh sistem karena saldo kas kelompok saat ini tidak mencukupi.'
                    ], 400);
                }

                // 1. Kurangi balance kelompok
                $newGroupBalance = (float)$group['balance'] - $totalAmount;
                $db->table('tukang_group')->where('id', $groupId)->update(['balance' => $newGroupBalance]);

                // 2. Ubah status transaksi menjadi approved
                $db->table('group_transactions')->where('id', $txId)->update(['status' => 'approved']);

                // 3. Cairkan ke masing-masing wallet penerima
                $distributions = json_decode($tx['distributions_data'], true);
                foreach ($distributions as $dist) {
                    $recipientId = (int)$dist['tukang_id'];
                    $distAmount = (float)$dist['amount'];

                    // Update wallet tukang
                    $recipient = $db->table('tukang')->where('id', $recipientId)->get()->getRowArray();
                    if ($recipient) {
                        $newPersonalBalance = (float)$recipient['balance'] + $distAmount;
                        $db->table('tukang')->where('id', $recipientId)->update(['balance' => $newPersonalBalance]);

                        // Catat transaksi wallet personal
                        $db->table('tukang_transactions')->insert([
                            'tukang_id' => $recipientId,
                            'group_transaction_id' => $txId,
                            'amount' => $distAmount,
                            'type' => 'income',
                            'description' => 'Penerimaan upah kelompok (Disetujui Mayoritas)',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                $db->transComplete();

                // Kirim notifikasi sukses cair ke Mandor & Anggota
                $title = "Pencairan Kas Kelompok Berhasil";
                $message = "Usulan pembagian Rp " . number_format($totalAmount, 0, ',', '.') . " disetujui mayoritas dan berhasil dicairkan.";
                
                $notifService->sendPersonal('tukang', (int)$group['tukang_id'], $title, $message);
                foreach ($activeMembers as $m) {
                    $notifService->sendPersonal('tukang', (int)$m['tukang_id'], $title, $message);
                }

                return $this->respond([
                    'status' => true,
                    'message' => 'Suara berhasil disimpan. Transaksi disetujui mayoritas dan dana telah dicairkan ke wallet anggota.'
                ]);

            } 
            // KONDISI B: Ditolak Mayoritas (Suara reject melebihi kapasitas sisa voter untuk mencapai mayoritas)
            elseif ($rejectedVotes > ($totalMembers - $majorityRequired)) {
                // Ubah status transaksi menjadi rejected
                $db->table('group_transactions')->where('id', $txId)->update(['status' => 'rejected']);
                
                $db->transComplete();

                // Kirim notifikasi penolakan ke Mandor
                $title = "Usulan Pembagian Ditolak";
                $message = "Usulan pembagian upah sebesar Rp " . number_format($totalAmount, 0, ',', '.') . " ditolak oleh mayoritas anggota.";
                $notifService->sendPersonal('tukang', (int)$group['tukang_id'], $title, $message);

                return $this->respond([
                    'status' => true,
                    'message' => 'Suara berhasil disimpan. Transaksi resmi ditolak oleh kelompok.'
                ]);
            }

            // KONDISI C: Suara direkam tetapi belum mencapai threshold keputusan
            $db->transComplete();

            return $this->respond([
                'status' => true,
                'message' => 'Suara Anda berhasil direkam. Menunggu suara dari anggota lain.'
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
 
    /**
     * Helper: Generate a unique 10-character uppercase alphanumeric referral code
     */
    private function _generateReferralCode()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        $groupModel = new \App\Modules\Tukang\Models\TukangGroupModel();

        do {
            $code = '';
            for ($i = 0; $i < 10; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exists = $groupModel->where('referral_code', $code)->first();
        } while ($exists);

        return $code;
    }
}
