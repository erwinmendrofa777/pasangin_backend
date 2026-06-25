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

            $totalAllocated = 0;
            foreach ($distributions as $dist) {
                $totalAllocated += (float) $dist['amount'];
            }

            $currentBalance = (float) $group['balance'];
            if ($currentBalance < $totalAllocated) {
                $formattedAllocated = number_format($totalAllocated, 0, ',', '.');
                return $this->respond([
                    'status' => false,
                    'message' => "Total pembagian (Rp {$formattedAllocated}) melebihi saldo grup saat ini."
                ], 400);
            }

            $db = \Config\Database::connect();
            
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

            // Mulai DB Transaction
            $db->transStart();

            // 1. Kurangi balance tukang_group
            $newGroupBalance = $currentBalance - $totalAllocated;
            $groupModel->update($group['id'], ['balance' => $newGroupBalance]);

            // 2. Catat outflow di group_transactions
            $groupTxModel = new \App\Modules\Tukang\Models\GroupTransactionsModel();
            $groupTxModel->insert([
                'group_id' => $group['id'],
                'amount' => $totalAllocated,
                'type' => 'outflow',
                'description' => 'Distribusi saldo grup ke ' . count($distributions) . ' anggota.',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $groupTxId = $groupTxModel->getInsertID();

            // 3. Distribusikan ke masing-masing tukang
            $tukangModel = new \App\Modules\Tukang\Models\TukangModel();
            $tukangTxModel = new \App\Modules\Tukang\Models\TukangTransactionsModel();

            foreach ($distributions as $dist) {
                $tukangId = (int) $dist['tukang_id'];
                $amount = (float) $dist['amount'];

                $tukang = $tukangModel->find($tukangId);
                if (!$tukang) {
                    throw new Exception("Data tukang dengan ID {$tukangId} tidak ditemukan.");
                }
                
                $newTukangBalance = (float)$tukang['balance'] + $amount;

                // Update saldo wallet tukang
                $tukangModel->update($tukangId, ['balance' => $newTukangBalance]);

                // Catat transaksi wallet tukang
                $tukangTxModel->insert([
                    'tukang_id' => $tukangId,
                    'group_transaction_id' => $groupTxId,
                    'amount' => $amount,
                    'type' => 'income',
                    'description' => 'Penerimaan saldo grup'
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Gagal mendistribusikan saldo grup (transaksi database gagal).'
                ], 500);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Berhasil mendistribusikan saldo grup ke ' . count($distributions) . ' anggota.'
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
